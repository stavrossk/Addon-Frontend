<?php
/**
 * This file contains the main configuration as simple array
 */
$configuration = array(
	'database' => array(
		'username'	=> '',
		'password'	=> '',
		'name'		=> '',
		'server'	=> '',	
	),
	'repository' => array(
		'version' => 'eden',
		'importUrl' => 'http://mirrors.xbmc.org/addons/eden/addons.xml',
		'statsUrl' => 'http://mirrors.xbmc.org/addons/addons_simplestats.xml',
	)
);

require_once('/etc/xbmc/php-include/addons/private/configuration.php');
?>