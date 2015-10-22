<?php

require_once './includes/initialize.php';
global $oDB;
$oResponse = new stdClass();

if($_SERVER['HTTP_HOST'] =='127.0.0.1') {
	include './inc.local.authorize.php';
}





print '<PRE>';
try { 

$sDate = providiDateTime('2015-09-28T13:49:16+02:00' , 'text' , 'Europe/Copenhagen');
var_dump($sDate);

} catch(Exception $e) {

	var_dump($e);
}

?>