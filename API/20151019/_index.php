<?php
error_reporting(E_ALL);

require_once './config.php';
require_once './includes/providiAPI.lib.php';
require_once './includes/providiAPI.php';
require_once './includes/providiAuthToken.class.php';
global $oDB;


/*
$aResults = $oDB->getHash(' SHOW TABLES');

//$aResults = $oDB->getHash(' SELECT username , password FROM da_reference LIMIT 10 ');
$aResults = $oDB->getRow(' SELECT username , password FROM da_reference LIMIT 10 ');

print '<PRE>';
var_dump($aResults);
print_r($oDB);

$n = $oDB->numRows();
var_dump($n);
*/


$oAuth = new ProvidiAuthToken($oDB );
$oAuth->load(45);


print '<PRE>';
print_r($oAuth);exit;


$oResult = providiAuthentication::Login('inge_sarfelt' , '45815513');
var_dump($oResult);


?>