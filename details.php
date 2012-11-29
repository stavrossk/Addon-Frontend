<?php
//  ##############   Include Files  ################ //
	require_once("includes/configuration.php");
	require_once("includes/db_connection.php");
	require_once("includes/functions.php");
	require_once("includes/pageRenderer.php");
//  ##############  Finish Includes  ############### //

//  ##############   Get Variables   ############### //
	$type = $_GET["t"];
//  ##############  Finish Varibles  ############### //

// ###############  Setup Queries    ############### //
$detail = $db->get_results("SELECT * FROM addon WHERE id = '" . $db->escape($type) . "'");
$commentaddon = $db->get_results("SELECT * FROM comment WHERE addonid = '" . $db->escape($type) . "' ORDER BY date DESC LIMIT 5");
//  ##############  Finish Queries  ############### //

$page = new PageRenderer();
$page->addRootlineItem(array( 'url' => 'details.php?t=' . $type, 'name' => 'Details'));

$content = '';
						// Loop through the add-on details array
					if (isset($detail)) 
					{
						foreach ($detail as $details)
						{
							$content .= "<h2>$details->name ".$details->version."</h2>";
							$content .= "<table border='0' align='center'><tr><table cellspacing='28' class='transparenttable'><tr>";
							$content .= "<td>";
							$content .= "<img src='http://mirrors.xbmc.org/addons/eden/$details->id/icon.png' width='256' height='256' /><br>";
							$content .= "<br><b>Author:</b> <a href='/browse.php?a=".$details->provider_name."'>".$details->provider_name."</a>";
							$content .= "<br><br><b>Rating:</b> <img src='images/star_full_off.png' width='14' height='14' /><img src='images/star_full_off.png' width='14' height='14' /><img src='images/star_full_off.png' width='14' height='14' /><img src='images/star_full_off.png' width='14' height='14' /><img src='images/star_full_off.png' width='14' height='14' />";
							$content .= "<br><br><b>Downloads:</b> ".number_format($details->downloads);
							$content .= "<br><br><b>Description:</b> ".str_replace("[CR]","<br>",$details->description)."<br><br>";
							$content .= "<table align='center' width='500'class='transparenttable'><tr><td align='center'>";
							$content .= "<b>Forum Discussion:</b><br>";
						
						// Check forum link exists
							if (isset($details->forum)) 
							{ $content .= "<a href='".$details->forum."'><img src='images/forum.png' /></a>"; }
							else {$content .= "<img src='images/forumbw.png' />";}
						
						// Auto Generate Wiki Link
							$content .= " </td><td align='center'><b>Wiki Documentation:</b><br><a href='http://wiki.xbmc.org/index.php?title=Add-on:".$details->name."'><img src='images/wiki.png' /></a></td>";
						
						/* 
						// Donation stuff (**REMOVED FOR NOW**)
						$content .= "<td align='center'><b>Donate to Author:</b><br>";
						// Check donate link exists
						if (isset($details->donate)) 
						{
						$content .= "<a href='".$details->donate."'><img src='images/paypal.jpg' /></a>";
						}
						$content .= {echo "<img src='images/paypalbw.jpg' />";} */
						
						// Check sourcecode link exists
						$content .= "<td align='center'><b>Source Code:</b><br>";
						if (isset($details->sourcecode)) 
						{
							$content .= "<a href='".$details->sourcecode."'><img src='images/code.png' /></a>";
						}
						else {$content .= "<img src='images/codebw.png' />";}
						
						$content .= "</td><tr></table><br><br>";

						}
					}
				//	Else {echo "none found"};
					
$content .= '
		<!-- Comments Javascript Livefyre Embed -->
		<div id="livefyre-comments"></div>
		<script type="text/javascript" src="http://zor.livefyre.com/wjs/v3.0/javascripts/livefyre.js"></script>
		<script type="text/javascript">
			(function () {
			var articleId = fyre.conv.load.makeArticleId(null, [\'t\']);
			fyre.conv.load({}, [{
			el: \'livefyre-comments\',
			network: "livefyre.com",
			siteId: "314161",
			articleId: articleId,
			signed: false,
			collectionMeta: {
							articleId: articleId,
							url: fyre.conv.load.makeCollectionUrl(),
							}
			}], function() {});
			}());
		</script>
		<!-- END: Livefyre Embed -->
';

$content .= getDisclaimer();
$page->setContent($content);
echo $page->render();
?>