<?php
$database = array();
$ffmpeg = "ffmpeg";
$ffprobe = "ffprobe";
// --------------------------------------------------------
$database["ip_addr"]	= "127.0.0.1";
$database["username"]	= "root";
$database["password"]	= "";
$database["name"]		= "youtube";
// --------------------------------------------------------
try {
	$conn = new PDO("mysql:host=". $database["ip_addr"] . ";dbname=". $database["name"] . "", $database["username"], $database["password"]);
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
	}
// --------------------------------------------------------
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/main/dom_parser.php");
?>