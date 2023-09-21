<table width="80%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
	<tr>
		<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
		<td width="100%"><img src="img/pixel.gif" width="1" height="5"></td>
		<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
	</tr>
	<tr>
		<td><img src="img/pixel.gif" width="5" height="1"></td>
		<td>
		<div class="moduleTitleBar">
		<div class="moduleTitle"><div style="float: right; padding-right: 5px;"><a href="browse.php">&gt;&gt;&gt; Watch More Videos</a></div>
		Recently Archived
		</div>
		</div>
				
		<div class="moduleFeatured"> 
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr valign="top">

					<?php
					$stmt = $conn->prepare("SELECT * FROM videos ORDER BY num DESC LIMIT 5");
					$stmt->execute();
					foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $video) {
						$Now = new DateTime($video->reup_date);
						echo "
						<td width=\"20%\" align=\"center\"><a href=\"index.php?v=" . $video->id . "\"><img src=\"get_still.php?still_id=2&video_id="  . $video->id . "\" class=\"moduleFeaturedThumb\" width=\"120\" height=\"90\"></a>
						<div class=\"moduleFeaturedTitle\"><a href=\"index.php?v=" . $video->id . "\">" . $video->title . "</a></div>
						<div class=\"moduleFeaturedDetails\">Added: " . $Now->format('F') . " " . $Now->format('d') . ", " . $Now->format('Y') . "<br>by <a href=\"profile_videos.php?user=" . $video->uploaded_by . "\">" . $video->uploaded_by . "</a></div>
						</td>
						";
					}
					?>		
				</tr>
			</table>
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
