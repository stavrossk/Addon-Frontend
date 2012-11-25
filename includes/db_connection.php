<?php 
require_once("ez_sql_core.php");
require_once("ez_sql_mysql.php");

if (isset($configuration['database'])) {
	$db = new ezSQL_mysql($configuration['database']['username'], $configuration['database']['password'], $configuration['database']['name'], $configuration['database']['server']);
}
?>