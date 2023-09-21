<?php 
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/main/header.php"); 

if(isset($_GET["v"])) {
	require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/features/watch.php");
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/features/recently_added.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/main/footer.html");
?>