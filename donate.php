<?php
//  ##############   Include Files  ################ //
	require_once("includes/configuration.php");
	require_once("includes/db_connection.php");
	require_once("includes/functions.php");
	require_once("includes/pageRenderer.php");
//  ##############  Finish Includes  ############### //

$view = new TemplateView();
$content = $view->render('donationForm');

$donation = $db->get_results("SELECT * FROM donation ORDER BY datemade DESC LIMIT 20");
		if (is_array($donation)) { 
			$content .= '<img src="/images/transparent.png" width="32" alt="" />
				<p>
				<h2>Recent Donations</h2>';

				# Check the last 20 donations
				$content .= "<table width='950'><tbody><tr><th align='center'>Date</th><th align='center'>Country</th align='center'><th align='center'>Donor</th><th align='center'>Amount</th><th align='center'>Message</th></tr>";

				
					foreach ($donation as $donations)
							{
								$content .= "<td align='center'>".date(  "j F, Y", strtotime( $donations->datemade ) )."</td>";
								$content .= "<td align='center'><img src='/images/flags/". $donations->country. ".gif' width='30' height='22' /></td>";
								$content .= "<td align='center'>$donations->donor</td>";
								$content .= "<td align='center'>$".number_format($donations->amount)."</td>";
								$content .= "<td align='center'>$donations->message</td></tr>";
							}
				$content .= "</tbody></table>
				</p>";
		}

$page = new PageRenderer();
$page->setPageTitle('Donate to XBMC');
$page->setTemplate('pageNoSideColumn');
$page->setContent($content);
echo $page->render();
shutdown();
?>