<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/main/header.php");

if(mb_strlen($_GET["search"], 'utf8') < 3) { die("search query must be three chars minimum"); }
$search = str_replace(" ", "|", $_GET['search']);
$stmt = $conn->prepare("SELECT * FROM videos WHERE videos.tags REGEXP :t0 OR uploaded_by='$search' ORDER BY videos.uploaded_on DESC"); // Regex!
$stmt->bindParam(":t0", $search);
$stmt->execute();

?>
</div>

		<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
			<tr>
				<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
				<td width="100%"><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
				<td>

				<div class="moduleTitleBar">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr valign="top">
						<td><div class="moduleTitle">Search // <?php echo htmlspecialchars(trim($_GET['search'])); ?></div></td>
						<td align="right">
						<div style="font-weight: color: #444; margin-right: 5px;">Results <b><?php echo $stmt->rowCount(); ?></b></div>
						</td>
					</tr>
				</table>
				</div>
				<?php 
				foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $video) {
					$Now = new DateTime($video->reup_date);
					echo "<div class=\"moduleEntry\"> 
					<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
						<tr valign=\"top\">
							<td>
							<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
								<tr>
									<td><a href=\"index.php?v=" . $video->id . "&search=" . $_GET["search"] . "\"><img src=\"get_still.php?video_id=" . $video->id . "&still_id=1\" class=\"moduleEntryThumb\" width=\"100\" height=\"75\"></a></td>
									<td><a href=\"index.php?v=" . $video->id . "&search=" . $_GET["search"] . "\"><img src=\"get_still.php?video_id=" . $video->id . "&still_id=2\" class=\"moduleEntryThumb\" width=\"100\" height=\"75\"></a></td>
									<td><a href=\"index.php?v=" . $video->id . "&search=" . $_GET["search"] . "\"><img src=\"get_still.php?video_id=" . $video->id . "&still_id=3\" class=\"moduleEntryThumb\" width=\"100\" height=\"75\"></a></td>
								</tr>
							</table>
							
							</td>
							<td width=\"100%\"><div class=\"moduleEntryTitle\"><a href=\"index.php?v=" . $video->id . "&search=" . $_GET["search"] . "\">" . $video->title . "</a></div>
							<div class=\"moduleEntryDescription\">" . nl2br($video->description) . "</div>
							<div class=\"moduleEntryTags\">
							Tags // ";
							foreach(explode(" ", $video->tags) as $tag) echo '<a href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a> : ';
							echo "</div>
							<div class=\"moduleEntryDetails\">Added: " . $Now->format('F') . " " . $Now->format('d') . ", " . $Now->format('Y') . " by <a href=\"profile.php?user=" . $video->uploaded_by . "\">" . $video->uploaded_by . "</a></div>
														</td>
						</tr>
					</table>
				</div>";
				}
				?>
								</td>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</table>
		<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/main/footer.html"); ?>