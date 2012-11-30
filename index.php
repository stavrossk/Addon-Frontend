<?php
//  ##############   Include Files  ################ //
	require_once("includes/configuration.php");
	require_once("includes/db_connection.php");
	require_once("includes/functions.php");
	require_once("includes/pageRenderer.php");
//  ##############  Finish Includes  ############### //

// ###############  Setup Queries    ############### //
$totalcount = $db->get_var("SELECT count(*) FROM addon");
//  ##############  Finish Queries  ############### //

$page = new PageRenderer();

$content = <<<EOF
				<h2>Categories</h2>
				<p>Browse the the Add-On categories below</p>
				
				<table border="0" align="center"><tr>
				<table cellspacing="26" class="transparenttable"> 
					<tr><td align="center">Metadata</td>
						<td align="center">Pictures</td>
						<td align="center">Video</td>
						<td align="center">Audio</td></tr>
					<tr><td><a href='browse.php?t=metadata'><img src='images/mainicon-metadata.png'/></a></td>
						<td><a href='browse.php?t=plugin.image'><img src='images/mainicon-picture.png'/></a></td>
						<td><a href='browse.php?t=plugin.video'><img src='images/mainicon-video.png'/></a></td>
						<td><a href='browse.php?t=plugin.audio'><img src='images/mainicon-music.png'/></a></td></tr>
					<tr><td align="center">Games</td>
						<td align="center">Programs</td>
						<td align="center">Scripts</td>
						<td align="center">Skins</td></tr>
					<tr><td><a href='browse.php?t=plugin.games'><img src='images/mainicon-game.png'/></a></td>
						<td><a href='browse.php?t=plugin.program'><img src='images/mainicon-program.png'/></a></td>
						<td><a href='browse.php?t=script'><img src='images/mainicon-script.png'/></a></td>
						<td><a href='browse.php?t=skin'><img src='images/mainicon-skin.png'/></a></td></tr>
		 		 </table></tr>
</table>
	<table width="610" class="transparenttable">
	  <tr>
	    <td align="center">$totalcount Plugins found<br>
		</td>
      </tr>
	</table>
EOF;

$content .= getDisclaimer();
$page->setContent($content);
echo $page->render();
?>
