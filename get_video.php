<?php

if(!isset($_GET["video_id"])) { die(); }

if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/content/video/" . $_GET["video_id"] . ".flv")) {
	header("Location: /content/video/" . $_GET["video_id"] . ".flv");
}

?>