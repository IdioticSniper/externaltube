<?php
if(!isset($_GET["v"])) { die(); }
$stmt = $conn->prepare("SELECT * FROM videos WHERE id=:t0");
$stmt->bindParam(':t0', $_GET["v"]);
$stmt->execute();

if($stmt->rowCount() == 0) {
	header("Location: /");
}

foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $video);
$Now = new DateTime($video->reup_date);

if($video->length == 0) {
	$length = round((int)exec("$ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 ".$_SERVER["DOCUMENT_ROOT"] . "/content/video/" . $_GET["v"] . ".flv"));
	$stmt = $conn->prepare("UPDATE videos SET length=:t0 WHERE id=:t1");
	$stmt->bindParam(':t0', $length);
	$stmt->bindParam(':t1', $_GET["v"]);
	$stmt->execute();
	header("Location: /index.php?v=" . $_GET["v"]);
}

// permalinks
$video_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$embed = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]/embed.php?v=" . $_GET["v"];
?>
<table width="795" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td width="515" style="padding-right: 15px;">
		
		<div class="tableSubTitle"><?php echo $video->title; ?></div>
		<div style="font-size: 13px; font-weight: bold; text-align:center;">
		<a href="http://youtube.com/watch?v=<?php echo $_GET["v"]; ?>">View on YouTube</a>
		// <a href="http://web.archive.org/web/1/http://youtube.com/watch?v=<?php echo $_GET["v"]; ?>">View Archive</a>
		// <a href="/get_video.php?video_id=<?php echo $_GET["v"]; ?>">Download</a>
		</div>
		
		<div style="text-align: center; padding-bottom: 10px;">
		<div id="flashcontent">
		<div style="padding: 20px; font-size:14px; font-weight: bold;">
			Hello, you either have JavaScript turned off or an old version of Macromedia's Flash Player, <a href="http://www.macromedia.com/go/getflashplayer/">click here</a> to get the latest flash player.
		</div>
		</div>
		</div>
		
		<script type="text/javascript">
			// <![CDATA[
			
			var fo = new FlashObject("player.swf?video_id=<?php echo $_GET["v"]; ?>&l=<?php echo $video->length; ?>", "player", "425", "350", 7, "#FFFFFF");
			fo.write("flashcontent");
			
			// ]]>
		</script>

		<table width="425" cellpadding="0" cellspacing="0" border="0" align="center">
			<tr>
				<td>
					<div class="watchDescription"><?php echo nl2br(htmlspecialchars($video->description)); ?>
					<div class="watchAdded" style="margin-top: 5px;"></div>
					</div>
					<?php
					echo "<div class=\"watchTags\">Tags // ";
					$thetags = [];
					$thetags = array_merge($thetags, explode(" ", $video->tags));
					$thetags = array_unique($thetags);
					foreach($thetags as $tag) {
						echo "<a href=\"#\">" . $tag . "</a> : ";
					}
					echo "</div>";
					?>
		
					<div class="watchAdded">
					Added: <?php echo $Now->format('F') . " " . $Now->format('d') . ", " . $Now->format('Y'); ?> by <?php echo $video->uploaded_by; ?> // 
					<?php
					$stmt = $conn->prepare("SELECT id FROM videos WHERE uploaded_by=:t0");
					$stmt->bindParam(":t0", $video->uploaded_by);
					$stmt->execute();
					?>
					<a href="profile_videos.php?user=<?php echo $video->uploaded_by; ?>">Videos</a> (<?php echo $stmt->rowCount(); ?>)
					</div>
				</td>
			</tr>
		</table>

		<!-- watchTable -->
		
		<div style="padding: 15px 0px 10px 0px;">
		<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#E5ECF9">
			<tr>
				<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
				<td width="100%"><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<form name="linkForm" id="linkForm">
				<td><img src="img/pixel.gif" width="5" height="1"></td>
				<td align="center">
		
				<div style="font-size: 11px; font-weight: bold; color: #CC6600; padding: 5px 0px 5px 0px;">Share this video! Copy and paste this link:</div>
				<div style="font-size: 11px; padding-bottom: 15px;">
				<input name="video_link" type="text" onclick="javascript:document.linkForm.video_link.focus();document.linkForm.video_link.select();" value="<?php echo $video_url; ?>" size="50" readonly="true" style="font-size: 10px; text-align: center;">
				</div>
				
				<div style="font-size: 11px; font-weight: bold; color: #CC6600; padding: 5px 0px 5px 0px;">Video Player: Put a Video Player on your website</div>
				<div style="font-size: 11px; padding-bottom: 15px;">
				<input name="player" type="text" onclick="javascript:document.linkForm.video_link.focus();document.linkForm.player.select();" value="<iframe width=&quot;460&quot; height=&quot;357&quot; src=&quot;<?php echo $embed; ?>&quot; scrolling=&quot;off&quot; frameborder=&quot;0&quot;></iframe>" size="50" readonly="true" style="font-size: 10px; text-align: center;">
				</div>

				<div style="font-size: 11px; font-weight: bold; color: #CC6600; padding: 5px 0px 5px 0px;">Video List: Put a List of this user's videos on your website</div>
				<div style="font-size: 11px; padding-bottom: 15px;">
				<input name="video_list" type="text" onclick="javascript:document.linkForm.video_link.focus();document.linkForm.video_list.select();" value="<iframe id=&quot;videos_list&quot; name=&quot;videos_list&quot; src=&quot;<?php echo (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]"; ?>/videos_list.php?user=<?php echo $video->uploaded_by; ?>&quot; scrolling=&quot;auto&quot; width=&quot;265&quot; height=&quot;400&quot; frameborder=&quot;0&quot; marginheight=&quot;0&quot; marginwidth=&quot;0&quot;>" size="50" readonly="true" style="font-size: 10px; text-align: center;">
				</div>
				
				</td>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
				</form>
			</tr>
			<tr>
				<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</table>
		</div>
		
		<!-- watchTable -->
		<br>
		</td>
		<td width="280">
		
		<table width="280" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
			<tr>
				<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
				<td width="270">
				<div class="moduleTitleBar">
				<table width="270" cellpadding="0" cellspacing="0" border="0">
					<tr valign="top">
						<td><div class="moduleFrameBarTitle">Random Videos</div></td>
					</tr>
				</table>
				</div>

				<iframe id="side_results" name="side_results" src="random_results.php" scrolling="auto" width="270" height="400" frameborder="0" marginheight="0" marginwidth="0">[Content for browsers that don't support iframes goes here]</iframe>
				</td>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</table>
		</td>
	</tr>
</table>