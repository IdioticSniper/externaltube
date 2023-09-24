<?php 
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/main/config.php"); 

if(!isset($_GET["user"])) { die("No username set"); }

$stmt = $conn->prepare("SELECT * FROM videos WHERE uploaded_by=:t0 ORDER BY reup_date DESC");
$stmt->bindParam(":t0", $_GET["user"]);
$stmt->execute();
$totalVideos = $stmt->rowCount();

$stmt = $conn->prepare("SELECT * FROM videos WHERE uploaded_by=:t0 ORDER BY reup_date DESC LIMIT 50");
$stmt->bindParam(":t0", $_GET["user"]);
$stmt->execute();

$video_display = $totalVideos . " videos";
if($totalVideos > 50) { $video_display = "over " . $totalVideos . " videos, see <a href=\"http://youtube.com/results.php?user=" . $_GET["user"] . "\">more</a>"; }

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>ExternalTube - Your Digital Video Vault</title>
<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body style="background-color:#DDDDDD">
<div style="font-weight: bold; padding: 3px; margin-left: 5px; margin-right: 5px; border-bottom: 1px dashed #999;">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td> My Videos // <span style="text-transform: capitalize;"><?php echo $_GET["user"]; ?></span> // (<?php echo $video_display; ?>)</td>
		<td align="right"><div style="vertical-align: text-bottom; text-align: right; padding: 2px;">
		<a href="<?php echo (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]"; ?>" target="_blank"></a>
		</div></td>
	</tr>
</table>
</div>

<?php
foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $video) {
	$Now = new DateTime($video->reup_date);
	echo "<div class=\"moduleEntry\">
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr valign=\"top\">
		<td><a href=\"index.php?v=" . $video->id . "\" class=\"bold\" target=\"_parent\"><img src=\"get_still.php?video_id=" . $video->id . "\" class=\"moduleEntryThumb\" width=\"80\" height=\"60\"></a></td>
		<td width=\"100%\"><div class=\"moduleFrameTitle\"><a href=\"index.php?v=" . $video->id . "\" target=\"_parent\">" . $video->title . "</a></div>
		<div class=\"moduleFrameDetails\">Added: " . $Now->format('F') . " " . $Now->format('d') . ", " . $Now->format('Y') . " <br>by <a href=\"/profile.php?user=" . $video->uploaded_by . "\" target=\"_parent\">" . $video->uploaded_by . "</a></div>		
		</td>
	</tr>
</table>
</div>";
}
?>

</body>

