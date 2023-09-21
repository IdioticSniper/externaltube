<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/main/config.php");

if(!file_exists($_SERVER["DOCUMENT_ROOT"] . "/content/thumb/" . $_GET["video_id"] . ".2.jpg")) {
	$input = $_SERVER["DOCUMENT_ROOT"] . "/content/video/" . $_GET["video_id"] . ".flv";
	//die($input);
	$framecount = exec("$ffprobe -v error -select_streams v:0 -count_packets -show_entries stream=nb_read_packets -of csv=p=0 $input");
	$framecount2 = (int) $framecount;
	$thumb1 = round($framecount2 / 3);
	$thumb2 = round($framecount2 / 2);
	$thumb3 = round($framecount2 / 1.2);
	exec("$ffmpeg -i $input -vf \"select=gte(n\,$thumb1)\" -vframes 1 -s 120x90 " . $_SERVER["DOCUMENT_ROOT"] . "/content/thumb/" . $_GET["video_id"] . ".1.jpg");
	exec("$ffmpeg -i $input -vf \"select=gte(n\,$thumb2)\" -vframes 1 -s 120x90 " . $_SERVER["DOCUMENT_ROOT"] . "/content/thumb/" . $_GET["video_id"] . ".2.jpg");
	exec("$ffmpeg -i $input -vf \"select=gte(n\,$thumb3)\" -vframes 1 -s 120x90 " . $_SERVER["DOCUMENT_ROOT"] . "/content/thumb/" . $_GET["video_id"] . ".3.jpg");
	if(isset($_GET["still_id"])) {
		echo file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/content/thumb/" . $_GET["video_id"] . "." . $_GET["still_id"] . ".jpg");
	} else echo file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/content/thumb/" . $_GET["video_id"] . ".2.jpg");
}

if(isset($_GET["still_id"])) {
	echo file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/content/thumb/" . $_GET["video_id"] . "." . $_GET["still_id"] . ".jpg");
} else echo file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/content/thumb/" . $_GET["video_id"] . ".2.jpg");

?>