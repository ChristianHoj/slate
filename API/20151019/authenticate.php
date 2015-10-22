<?php

require_once './includes/initialize.php';
global $oDB;

$aPOST = $_POST;
$oResponse = new stdClass();


if($_SERVER['HTTP_HOST']=='127.0.0.1') {
	$aPOST['user'] = 'inge_sarfelt';
	$aPOST['pass'] = '45815513';
}

try {


	if(empty($aPOST['user'])) {
		throw new providiUnauthorizeException('Invalid request parameter - user expected' , 5000);
	}
	if(empty($aPOST['pass'])) {
		throw new providiUnauthorizeException('Invalid request parameter - pass expected' , 5001);
	}

	$oAuthToken = providiAuthentication::Login($aPOST['user'] , $aPOST['pass']);

	if(empty($oAuthToken)) {
		throw new providiUnauthorizeException('Invalid username or password - pass expected' , 5002);
	}

	$oData = new stdClass();
	$oData->type = 'user';
	$oData->id = $oAuthToken->providiID;
	$oAtt = new stdClass();
	$oAtt->username  = $oAuthToken->username;
	$sName = str_replace('  ', ' ', $oAuthToken->fullName);
	$aNames = explode(' ', $sName);
	
	$oAtt->fist_name  = $aNames[0];
	$oAtt->last_name  = $aNames[ count($aNames) - 1];
	$oAtt->email = $oAuthToken->email;
	$oAtt->auth_token = $oAuthToken->authToken;

	$oData->attribute = $oAtt;
	$oResponse->data = $oData;



} catch (Exception $e) {
	providiJSONErrorHandler($oResponse , $e);
}

if(isset($_GET['debug'])) {
	print '<PRE>';
	print_r($oResponse);	
}

providiJSONResponse($oResponse);
?>