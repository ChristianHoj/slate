<?php  // æ



global $aProvidiConfigs;

$aProvidiConfigs = array();

$aProvidiConfigs['DB_host'] = 'localhost';
$aProvidiConfigs['DB_username'] = 'root';
$aProvidiConfigs['DB_password'] = 'root123';
$aProvidiConfigs['DB_dbname'] = 'local';


$aProvidiConfigs['URL_live_site'] = 'http://providi.eu/';
if($_SERVER['HTTP_HOST']=='127.0.0.1') {
	$aProvidiConfigs['URL_live_site'] = 'http://127.0.0.1/providi.eu/';
}



?>