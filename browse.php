<?php
//  ##############   Include Files  ################ //
	require_once("includes/configuration.php");
	require_once("includes/db_connection.php");
	require_once("includes/functions.php");
	require_once("includes/pageRenderer.php");
//  ##############  Finish Includes  ############### //

//  ##############   Get Variables   ############### //
	$type = NULL;
	$author = NULL;
	if (isset($_GET["t"])) {$type = $_GET["t"];}
	if (isset($_GET["a"])) {$author = $_GET["a"];}
//  ##############  Finish Varibles  ############### //

// ###############  Setup Queries    ############### //

if ($type !== NULL) 
{
$category = $db->get_results("SELECT * FROM addon WHERE id LIKE '" . $db->escape($type) . "%' AND id NOT LIKE 'Common%' AND id NOT LIKE 'script.module%' ORDER BY downloads DESC");
$count = $db->get_var("SELECT count(*) FROM addon WHERE id LIKE '" . $db->escape($type) . "%' AND id NOT LIKE 'Common%' AND id NOT LIKE 'script.module%'");
}
else if ($author !== NULL) 
{
$category = $db->get_results("SELECT * FROM addon WHERE provider_name LIKE '" . $db->escape($author) . "%' AND id NOT LIKE 'script.module%' ORDER BY downloads DESC");
$count = $db->get_var("SELECT count(*) FROM addon WHERE provider_name LIKE '" . $db->escape($author) . "%' AND id NOT LIKE 'script.module%'");
}
//  ##############  Finish Queries  ############### //

$page = new PageRenderer();
$page->addRootlineItem(array( 'url' => 'browse.php?t=' . $type . '&amp;a=' . $author, 'name' => 'Browse'));

	$content ='<h2>Browsing</h2>';
	if ($type !== NULL || $author !== NULL)
		$content .= '<p>' . htmlspecialchars($type . $author) . '</p>';

	if (is_array($category) && count($category)) 
	{
		$content .= '
				<table border="0" align="center"><tr>
				<table cellspacing="28" class="transparenttable"> <tr>
		';
		$counter = 0;
		foreach ($category as $categories)
		{
			$counter++;					
			$content .= "<td>";
			$content .= "<a href='details.php?t=".$categories->id."'><img src='http://mirrors.xbmc.org/addons/eden/$categories->id/icon.png' width='78' height='78' /></a><br>";
			$content .= "<b>".substr($categories->name,0,16)."</b>";
			$content .= "<br>".substr($categories->provider_name,0,17);
			$content .= "<br /><img src='images/star_full_off.png' width='14' height='14' /><img src='images/star_full_off.png' width='14' height='14' /><img src='images/star_full_off.png' width='14' height='14' /><img src='images/star_full_off.png' width='14' height='14' /><img src='images/star_full_off.png' width='14' height='14' />";
			$content .= "</td>";

			if($counter % 4 == 0)
			{
				$content .= "</tr><tr>";
			}
		}
		$content .= "
						</tr>
		 		 </table></tr>
</table>
";
	}
	$content .= '
	<table width="610" class="transparenttable">
	  <tr>
	    <td align="center">' . $count . ' Plugins found<br>
		
		
	
		
		</td>
      </tr>
	</table>';

$content .= getDisclaimer();
$page->setContent($content);
echo $page->render();
?>