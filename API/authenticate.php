<?php

require_once './includes/initialize.php';
global $oDB;

$aPOST = $_POST;
$oResponse = new stdClass();


if($_SERVER['HTTP_HOST']=='127.0.0.1') {
	include 'inc.local.authorize.php';
}

try {


	if(empty($aPOST['user'])) {
		throw new providiUnauthorizeException('Invalid request parameter - user expected' , 5000);
	}
	if(empty($aPOST['pass'])) {
		throw new providiUnauthorizeException('Invalid request parameter - pass expected' , 5001);
	}

	##################################
	### default option????
	##################################
	if(empty($aPOST['country'])) {
		$aPOST['country'] = 'DK';
	}

	$oAuthToken = providiAuthentication::Login($aPOST['user'] , $aPOST['pass'] ,@$aPOST['country']);

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
	//
	// // [=>CBH] fixed fis_name -> first_name on 2015-10-28
	$oAtt->first_name  = $aNames[0];
	$oAtt->last_name  = $aNames[ count($aNames) - 1];
	$oAtt->email = $oAuthToken->email;
	$oAtt->auth_token = $oAuthToken->authToken;

	$oData->attributes = $oAtt;
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