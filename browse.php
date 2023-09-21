<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/main/header.php");

if(!isset($_GET["s"])) { $_GET["s"] = "mr"; }
if($_GET["s"] == "mr") { $stmt = $conn->query("SELECT * FROM videos ORDER BY uploaded_on DESC LIMIT 20"); }
if($_GET["s"] == "r") { $stmt = $conn->query("SELECT * FROM videos ORDER BY rand() DESC LIMIT 20"); }

if(!isset ($_GET['page'])) {  
	$page_number = 1;  
} else {  
	$page_number = $_GET['page'];  
} 

$initial_page = ($page_number-1) * 20;
$total_rows  = $stmt->rowCount();
$total_pages = ceil($total_rows / 20);

if($_GET["s"] == "mr") { $stmt = $conn->query("SELECT * FROM videos ORDER BY uploaded_on DESC LIMIT " . $initial_page . "," . 20); }
if($_GET["s"] == "r") { $stmt = $conn->query("SELECT * FROM videos ORDER BY rand() DESC LIMIT " . $initial_page . "," . 20); }

if($page_number > 5) { $page_number = 1; }
if($page_number == 1) { 
	$video_count = 1; 
} else {
	$video_count = $page_number * $total_rows - 20;
}
?>

<table align="center" cellpadding="5" cellspacing="0" border="0">
	<tr>
		<?php 
		if($_GET["s"] == "mr") {
			echo "<td class=\"bold\">Most Recent</td>";
		} else {
			echo "<td><a href=\"browse.php?s=mr\">Most Recent</a></td>";
		}
		echo "<td>|</td>";
		if($_GET["s"] == "r") {
			echo "<td class=\"bold\">Random</td>";
		} else {
			echo "<td><a href=\"browse.php?s=r\">Random</a></td>";
		}
		?>
	</tr>
</table>

<br>

<table width="795" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
	<tr>
		<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
		<td><img src="img/pixel.gif" width="1" height="5"></td>
		<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
	</tr>
	<tr>
		<td><img src="img/pixel.gif" width="5" height="1"></td>
		<td width="785">
		<div class="moduleTitleBar">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr valign="top">
					<td>
						<div class="moduleTitle"><?php if($_GET["s"] == "mr") { echo "Most Recent"; } elseif($_GET["s"] == "r") { echo "Random"; } ?></div>
					</td>
					<td align="right">
						<div style="font-weight: bold; color: #444; margin-right: 5px;">
							Videos <?php echo $video_count . "-" . $page_number * $total_rows . " of 100</div>"; ?>
							
					</td>
				</tr>
			</table>
		</div>
		<div class="moduleFeatured">
			<table width="770" cellpadding="0" cellspacing="0" border="0">
				<tr valign="top">
				<?php
				$count = 0;
				foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $video) {
					$Now = new DateTime($video->reup_date);
					if($count == 0) { echo "<tr valign=\"top\">"; }
					echo "<td width=\"20%\" align=\"center\">
						<a href=\"index.php?v=" . $video->id . "\"><img src=\"get_still.php?video_id=" . $video->id . "\" width=\"120\" height=\"90\" class=\"moduleFeaturedThumb\"></a>
						<div class=\"moduleFeaturedTitle\"><a href=\"index.php?v=" . $video->id . "\">" . $video->title . "</a></div>
						<div class=\"moduleFeaturedDetails\">Added: " . $Now->format('F') . " " . $Now->format('d') . ", " . $Now->format('Y') . "<br>by <a href=\"profile_videos.php?user=" . $video->uploaded_by . "\">" . $video->uploaded_by . "</a></div>
					</td>";
					$count++;
					if($count == 5) { echo "</tr>"; $count = 0; }
				}
				?>
				</tbody></table>

			</div>
				<!-- begin paging -->
				<div style="font-size: 13px; font-weight: bold; color: #444; text-align: right; padding: 5px 0px 5px 0px;">Browse Pages: 
				<span style="background-color: #CCC; padding: 1px 4px 1px 4px; border: 1px solid #999; margin-right: 5px;"><a href="browse.php?page=1&s=<?php echo $_GET["s"]; ?>">1</a></span>
				<span style="background-color: #CCC; padding: 1px 4px 1px 4px; border: 1px solid #999; margin-right: 5px;"><a href="browse.php?page=2&s=<?php echo $_GET["s"]; ?>">2</a></span>
				<span style="background-color: #CCC; padding: 1px 4px 1px 4px; border: 1px solid #999; margin-right: 5px;"><a href="browse.php?page=3&s=<?php echo $_GET["s"]; ?>">3</a></span>
				<span style="background-color: #CCC; padding: 1px 4px 1px 4px; border: 1px solid #999; margin-right: 5px;"><a href="browse.php?page=4&s=<?php echo $_GET["s"]; ?>">4</a></span>
				<span style="background-color: #CCC; padding: 1px 4px 1px 4px; border: 1px solid #999; margin-right: 5px;"><a href="browse.php?page=5&s=<?php echo $_GET["s"]; ?>">5</a></span>
				<!-- end paging -->
				</div>
		</td>
		<td><img src="img/pixel.gif" width="5" height="1"></td>
	</tr>
	<tr>
		<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
		<td><img src="img/pixel.gif" width="1" height="5"></td>
		<td><img src="img/box_login_br.gif" width="5" height="5"></td>
	</tr>
</table>

		</div>
		</td>
	</tr>
</table>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/main/footer.html"); ?>