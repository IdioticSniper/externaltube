<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/main/config.php");

if(!isset($_GET["v"])) { die(); }
$stmt = $conn->prepare("SELECT * FROM videos WHERE id=:t0");
$stmt->bindParam(':t0', $_GET["v"]);
$stmt->execute();

if($stmt->rowCount() == 0) { die("The video you requested does not exist"); }
$video_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $video) {
	echo "<script src=\"https://unpkg.com/@ruffle-rs/ruffle\"></script>
	<object width=\"425\" height=\"350\"><param name=\"movie\" value=\"" . $video_url . "/p.swf?video_id=" . $_GET["v"] . "&l=" . $video->length . "&url=" . $video_url . "/\"></param><embed src=\"" . $video_url . "/p.swf?video_id=" . $_GET["v"] . "&l=" . $video->length . "&url=" . $video_url . "/\" type=\"application/x-shockwave-flash\" width=\"425\" height=\"350\"></embed></object>";
}
?>