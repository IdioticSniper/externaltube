<?php 
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/main/header.php"); 

if(!isset($_GET["user"])) { header("Location: /"); }

$stmt = $conn->prepare("SELECT * FROM videos WHERE uploaded_by=:t0 ORDER BY reup_date DESC");
$stmt->bindParam(":t0", $_GET["user"]);
$stmt->execute();

?>

<div style="padding: 0px 5px 0px 5px;">

<?php
if($stmt->rowCount() == 0) {
	echo "<table width=\"790\" align=\"center\" bgcolor=\"#666666\" cellpadding=\"6\" cellspacing=\"3\" border=\"0\">
	<tr>
		<td align=\"center\" bgcolor=\"#FFFFFF\">
			<p class=\"highlight\">
				This user has no videos at this time!
			</p>
		</td>
	</tr>
</table>";
	die(require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/main/footer.html"));
}
?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td style="padding-right: 15px;">

		<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
			<tr>
				<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
				<td width="100%"><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
				<td>
				
				<div class="watchTitleBar">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr valign="top">
							<td><div class="moduleTitle">Public Videos // <span style="text-transform: capitalize;"><?php echo $_GET["user"]; ?></span></div></td>
 							<td align="right"> 
								<div style="font-weight: bold; color: #444; margin-right: 5px;">Videos <?php echo $stmt->rowCount(); ?></div>
							</td>
						</tr>
					</table>
				</div>
				
				<?php foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $video) {
				$Now = new DateTime($video->reup_date);
				echo "				<div class=\"moduleEntry\"> 
					<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
						<tr valign=\"top\">
							<td><a href=\"index.php?v=" . $video->id . "\"><img src=\"get_still.php?video_id=" . $video->id . "\" class=\"moduleEntryThumb\" width=\"120\" height=\"90\"></a></td>
							<td width=\"100%\"><div class=\"moduleEntryTitle\"><a href=\"index.php?v=" . $video->id . "\">" . $video->title . "</a></div>
							<div class=\"moduleEntryDescription\">" . nl2br($video->description) . "</div>
							<div class=\"moduleEntryTags\">Tags // ";
							foreach(explode(" ", $video->tags) as $tag) echo '<a href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a> : ';
							echo "							</div>
		
							<div class=\"moduleEntryDetails\">Added: " . $Now->format('F') . " " . $Now->format('d') . ", " . $Now->format('Y') . " by <a href=\"profile_videos.php?user=" . $_GET["user"] . "\">" . $_GET["user"] . "</a></div>
							</td>
		
						</tr>
					</table>
				</div>";
				$related_tags = [];
				$related_tags = array_merge($related_tags, explode(" ", $video->tags));
				$related_tags = array_unique($related_tags);
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

		<td width="180">
		<div style="font-weight: bold; color: #333; margin: 0px 0px 5px 0px;">Related Tags:</div>
		<?php
		foreach($related_tags as $tag) {
			echo "<div style=\"padding: 0px 0px 5px 0px; color: #999;\">&#187; <a href=\"results.php?search=" . $tag . "\">" . $tag . "</a></div>";
		}
		?>
		</td>
	</tr>
</table>


<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/main/footer.html"); ?>