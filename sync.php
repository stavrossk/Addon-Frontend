<?php
//  ##############   Include Files  ################ //
	require_once("includes/configuration.php");
	require_once("includes/db_connection.php");
	require_once("includes/functions.php");
	require_once("includes/pageRenderer.php");
//  ##############  Finish Includes  ############### //

$page = new PageRenderer();

				$content = '<h2>Status</h2>
				<p>Syncing With Addons.XML, using ' . ucfirst($configuration['repository']['version']) . ' Repository.</p>
				<ul>';

				# Check the XML exists
				$repositoryVersion = strtolower($configuration['repository']['version']);
				$xml = simplexml_load_file($configuration['repository']['importUrl']);
				if (isset($xml->addon['id']))
				{
					$counter = 0;

					foreach ($xml->addon as $addons) 
					{
						$counter++;
						$description = "";
						$summary = "";
						$log = "<b>ID: </b>".$addons['id']. " - ";
						foreach ($addons->children() as $nodeName => $node) {
							if ($nodeName == 'extension' && $node['point'] == 'xbmc.addon.metadata' && $node->children()) {
								$log .= '| meta data found |';
								foreach ($node->children() as $subNodeName => $subNode) {
									if ($subNodeName == 'description' 
										&& ($subNode['lang'] == 'en' || !isset($subNode['lang']) ) )
									{
											$description = $subNode;
											break;
									}
									if ($subNodeName == 'summary' 
										&& ($subNode['lang'] == 'en' || !isset($subNode['lang']) ) )
									{
											$summary = $subNode;
											break;
									}
								}
								if ($description == '' && $summary) {
									$description = $summary;
								}
								break;
							}
						}

						$id = $addons['id'];
						$name = $db->escape($addons['name']);
						$provider_name = $db->escape($addons['provider-name']);
						$version = $addons['version'];
						$description = $db->escape($description);
						
						//Check here to see if the Item already exists
						$check = $db->get_row("SELECT * FROM addon WHERE id = '$id'");
						
						if (isset($check->id))
						{
							//Item exists
							$log .= "match found ";
							//Check here to see if the addon needs to be updated
							if ($check->version == $version)
							{
								$log .= "- versions the same";
							// Update plugin here to new version number
							}
							else
							{
								$db->query("UPDATE addon SET version = '$version', updated = NOW(), provider_name = '$provider_name', description = '$description' WHERE id = '$id'");
								$log .= "<b>version updated</b>";
							}
						}
						else if ($description != "")
						{
							$db->query("INSERT INTO addon (id, name, provider_name, version, description, created, updated) VALUES ('$id', '$name', '$provider_name','$version', '$description', NOW(), NOW())");
						}
						else
						{
							$log .= " no description found";
						}

						// check if screenshots.zip exists
/*
						$screenshots = "http://mirrors.xbmc.org/addons/eden/".$id."/screenshots.zip";
						if(check_url("$screenshots")) {
							$log .= " - screenshot.zip found";
					 	}
*/						$content .= '<li>' . $log . '</li>';
					}
				}
				
				$content .=  '</ul>';

				// Now update the download stats
				$xmlsimple = simplexml_load_file($configuration['repository']['statsUrl']);
				if (isset($xmlsimple->addon['id']))
				{
					$content .=  '<h2>Updating download stats </h2><ul>';
					foreach ($xmlsimple->addon as $addons) 
					{	
						$downloads = intval($addons->downloads);
						$addonId = $addons['id'];

						if ($addonId && $downloads)
						{
							// To speed things up, don't check if the addon exists in the DB and then do the UPDATE query. If addon is not in DB, it won't update anything, but if it is, we saved 1 query per update
							// Plugin was found update with the downloads.
							if($db->query("UPDATE addon SET downloads = '$downloads' WHERE id = '" . $db->escape($addonId) . "'"))	$content .=  '<li>' . $addonId . " - " . $downloads . ' - <b> downloads updated</b></li>';
						}
					}
					$content .=  '</ul>';
				}

$page->setTemplate('pageNoSideColumn');
$page->setContent($content);
echo $page->render();
shutdown();
?>