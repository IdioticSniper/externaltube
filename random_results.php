<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/main/config.php");
$stmt = $conn->prepare("SELECT * FROM videos ORDER BY rand() DESC LIMIT 10");
$stmt->execute();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>YouTube - Your Digital Video Repository</title>
<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body style="background-color:#DDDDDD">
<?php
foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $video) {
	$Now = new DateTime($video->reup_date);
	echo "<div class=\"moduleEntry\">
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr valign=\"top\">
		<td><a href=\"index.php?v=" . $video->id . "\" class=\"bold\" target=\"_parent\"><img src=\"get_still.php?video_id=" . $video->id . "\" class=\"moduleEntryThumb\" width=\"80\" height=\"60\"></a></td>
		<td width=\"100%\"><div class=\"moduleFrameTitle\"><a href=\"index.php?v=" . $video->id . "\" target=\"_parent\">" . $video->title . "</a></div>
		<div class=\"moduleFrameDetails\">Added: " . $Now->format('F') . " " . $Now->format('d') . ", " . $Now->format('Y') . "<br>by <a href=\"/profile.php?user=" . $video->uploaded_by . "\" target=\"_parent\">" . $video->uploaded_by . "</a></div>
		</td>
	</tr>
</table>
</div>
";
}
?>
</div>


</body>
