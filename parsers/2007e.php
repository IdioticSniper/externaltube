<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/main/config.php");

function scrape_2007e() {
    // create HTML DOM
    $html = file_get_html("http://web.archive.org/web/200701im_/http://youtube.com/watch?v=" . $_GET["scrape"]);
	
    // get article block
    foreach($html->find("div[id=baseDiv]") as $info) {
        // get title
        $item["title"] = trim($info->find("h1[id=video_title]", 0)->plaintext);
		
		if(empty($info->find("span[id=vidDescRemain]", 0)->plaintext)) {
			$item["desc"] = trim($info->find("span[id=vidDescBegin]", 0)->plaintext);
		} else $item["desc"] = trim($info->find("span[id=vidDescRemain]", 0)->plaintext);
		
		if(empty($info->find("span[id=vidTagsRemain]", 0)->plaintext)) {
			$item["tags"] = trim($info->find("span[id=vidTagsBegin]", 0)->plaintext);
		} else $item["tags"] = trim($info->find("span[id=vidTagsBegin]", 0)->plaintext);
		
		$item["date_ul"] = trim($info->find("div[id=subscribeCount]", 0)->plaintext);
        $ret[] = $item;
    }
	
    // clean up memory
    $html->clear();
    unset($html);
    return $ret;
}

// -----------------------------------------------------------------------------
if(isset($_GET["scrape"])) {
	if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/content/video/" . $_GET["scrape"] . ".flv")) { die ("Video already got scraped."); }
	$ret = scrape_2007e();
	
	if($ret == NULL) { die("\nID: " . $_GET["scrape"] . " - Scrape failed\n"); }
	
	foreach($ret as $v) {
		if($v["desc"] == NULL) { die("\nID: " . $_GET["scrape"] . " - DOM parser failed.\n"); }
		$arr = preg_split("/to /", $v['date_ul']);
		$v["tags"] = str_replace("&nbsp;", "", $v["tags"]);
		$stmt = $conn->prepare("INSERT videos (id, title, description, tags, uploaded_by) VALUES (:t0, :t1, :t2, :t3, :t5)");
		$stmt->bindParam(":t0", $_GET["scrape"]);
		$stmt->bindParam(":t1", $v["title"]);
		$stmt->bindParam(":t2", $v["desc"]);
		$stmt->bindParam(":t3", $v["tags"]);
		$stmt->bindParam(":t5", $arr[1]);
		$stmt->execute();
		echo "\nID: " . $_GET["scrape"] . " - Video metadata scraped\n";
	}

	if(!isset($_GET["cdn"])) {
		$url = "http://web.archive.org/web/1im_/http://wayback-fakeurl.archive.org/yt/img/" . $_GET["scrape"];
	} else {
		$url = "http://web.archive.org/web/1im_/http://" . $_GET["cdn"] . ".youtube.com/get_video?video_id=" . $_GET["scrape"];
	}
	
	$file_name = $_SERVER["DOCUMENT_ROOT"] . "/content/video/" . $_GET["scrape"] . ".flv";
	if (file_put_contents($file_name, file_get_contents($url))) {
		echo "ID: " . $_GET["scrape"] . " - FLV downloaded\n";
	} else {
		echo "ID: " . $_GET["scrape"] . " - FLV download failed\n";;
	}

	$input = $_SERVER["DOCUMENT_ROOT"] . "/content/video/" . $_GET["scrape"] . ".flv";
	//die($input);
	$framecount = exec("$ffprobe -v error -select_streams v:0 -count_packets -show_entries stream=nb_read_packets -of csv=p=0 $input");
	$framecount2 = (int) $framecount;
	$thumb1 = round($framecount2 / 3);
	$thumb2 = round($framecount2 / 2);
	$thumb3 = round($framecount2 / 1.2);
	exec("$ffmpeg -i $input -vf \"select=gte(n\,$thumb1)\" -vframes 1 -s 120x90 ../content/thumb/" . $_GET["scrape"] . ".1.jpg");
	exec("$ffmpeg -i $input -vf \"select=gte(n\,$thumb2)\" -vframes 1 -s 120x90 ../content/thumb/" . $_GET["scrape"] . ".2.jpg");
	exec("$ffmpeg -i $input -vf \"select=gte(n\,$thumb3)\" -vframes 1 -s 120x90 ../content/thumb/" . $_GET["scrape"] . ".3.jpg");
	
	$length = round((int)exec("$ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 ../content/video/" . $_GET["scrape"] . ".flv"));
	$stmt = $conn->prepare("UPDATE videos SET length=:t0 WHERE id=:t1");
	$stmt->bindParam(':t0', $length);
	$stmt->bindParam(':t1', $_GET["scrape"]);
	$stmt->execute();
}

?>
