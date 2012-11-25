<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
//  ##############   Include Files  ################ //
	require_once("includes/configuration.php");
	require_once("includes/db_connection.php");
	require_once("includes/functions.php");
//  ##############  Finish Includes  ############### //



?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />

	<link rel="stylesheet" href="stylesheets/dark.css" type="text/css" title="sky-blue" media="screen" />
		
	<style type="text/css">
		@import url(stylesheets/styles.css);			/*link to the main CSS file */
		@import url(stylesheets/ddsmoothmenu.css);		/*link to the CSS file for dropdown menu */
		@import url(stylesheets/tipsy.css);				/*link to the CSS file for tips */
	</style>
	
	<!-- Initialise jQuery Library -->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	
	<!-- Cufon Font Generator Plugin -->
	<script type="text/javascript" src="js/cufon/cufon-yui.js"></script>
	<script type="text/javascript" src="js/cufon/MgOpen_Modata_400-MgOpen_Modata_700.font.js"></script>
	<script type="text/javascript" src="js/cufon/cufon-load.js"></script>

	<!-- jQuery Watermark Plugin -->
	<script type="text/javascript" src="js/jquery.watermarkinput.js"></script>
	
	<!-- DropDown Menu Plugin -->
	<script type="text/javascript" src="js/ddsmoothmenu.js"></script> 
	
	<!-- jQuery Image Fading plugin -->
	<script type="text/javascript" src="js/jquery.color.js"></script>
	
	<!-- jQuery Tabs -->
	<script type="text/javascript" src="js/ui.core.js"></script>
	<script type="text/javascript" src="js/ui.tabs.js"></script>
	
	<!-- jQuery TinyCarousel -->
	<script type="text/javascript" src="js/jquery.tinycarousel.min.js"></script>
	
	<!-- jQuery Tipsy -->
	<script type="text/javascript" src="js/jquery.tipsy.js"></script>
	
	<!-- jQuery autoAlign -->
	<script type="text/javascript" src="js/jquery.autoAlign.js"></script>

	<script type="text/javascript" src="js/custom.js"></script>

	<title>XBMC | Add-On Repository</title>
</head>
<body id="sp">
<!-- Begin Container -->
<div class="container">
	<!-- Begin Header -->
	<div id="header">
		<!-- Site Logo -->
		<a href="index.html" class="logo"><img src="images/logo.png"  alt="" /></a>
		<!-- SearchBox -->
		<form action="" id="searchform">
			<div>
				<label for="s" class="screen-reader-text">Search for:</label>
				<input type="text" id="s" name="s" value=""/>
				<input type="submit" value="Search" id="searchsubmit"/>
			</div>
		</form>
		<div class="clear"></div>
	</div>
	<!-- End Header -->
	<!-- Start Main Nav -->
	<?php 
	include "includes/header.php"; 
	?>
	<!-- End Main Nav -->
	<!-- Start Breadcrumbs -->
	<!--<div id="breadcrumbs">
		<a href="#">Home</a> &raquo; 
		<a title="About" href="#">About</a> &raquo;
		<span class="current">About XBMC Add-Ons</span>
		<div class="clear"></div> 
	</div>-->
	<!-- End Breadcrumbs -->
	<!-- Start Page Title -->
	<div class="PageTitle">
		<h1>About Us</h1>
	</div>
	<!-- End Page Title -->
	<!-- Start Content Wrapper -->
	<div id="content_wrapper_sbr">
		<!-- Content Area -->
		<div id="content">
			<!-- Content Box -->
			<div class="box">
				
				<h2>Status</h2>
				<p>Syncing With Addons.XML, using <?=ucfirst($configuration['repository']['version']);?> Repository.</p>
				<ul>
				<?php 
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
*/						echo '<li>' . $log . '</li>';
					}
				}
				
				echo '</ul>';

				// Now update the download stats
				$xmlsimple = simplexml_load_file($configuration['repository']['statsUrl']);
				if (isset($xmlsimple->addon['id']))
				{
					echo '<h2>Updating download stats </h2><ul>';
					foreach ($xmlsimple->addon as $addons) 
					{	
						$downloads = intval($addons->downloads);
						$addonId = $addons['id'];

						if ($addonId && $downloads)
						{
							// To speed things up, don't check if the addon exists in the DB and then do the UPDATE query. If addon is not in DB, it won't update anything, but if it is, we saved 1 query per update
							// Plugin was found update with the downloads.
							if($db->query("UPDATE addon SET downloads = '$downloads' WHERE id = '" . $db->escape($addonId) . "'"))	echo '<li>' . $addonId . " - " . $downloads . ' - <b> downloads updated</b></li>';
						}
					}
					echo '</ul>';
				}

				?>
			</div>
		</div>

				<div class="clear"></div>
			</div>
	<!-- End Content Wrapper -->
		</div>
		<div class="clear"></div>
	</div>
</div>
<!-- End Container -->
</body>
</html>