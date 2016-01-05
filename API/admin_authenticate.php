<?php // æ

require_once './includes/initialize.php';
global $oDB;

$aPOST = providiPostBody();
$oResponse = new stdClass();


if($_SERVER['HTTP_HOST']=='127.0.0.1') {
	include 'inc.local.authorize.php';
}

try {


	if(empty($aPOST['user'])) {
		throw new providiUnauthorizeException('Invalid request parameter - user expected' , 15000);
	}
	if(empty($aPOST['pass'])) {
		throw new providiUnauthorizeException('Invalid request parameter - pass expected' , 15001);
	}

	##################################
	### default option????
	##################################
	if(empty($aPOST['country'])) {
		$aPOST['country'] = 'DK';
	}

	$oAuthToken = providiAdminAuthentication::Login($oDB , $aPOST['user'] , $aPOST['pass'] ,@$aPOST['country']);

	if(empty($oAuthToken)) {
		throw new providiUnauthorizeException('Invalid username or password - pass expected' , 15002);
	}

	$oData = new stdClass();
	$oData->type = 'admin';
	$oData->id = $oAuthToken->getProvidiID();
	$oAtt = new stdClass();
	$oAtt->username  = $oAuthToken->getUsername();
	$sName = str_replace('  ', ' ', $oAuthToken->getFullName());
	$aNames = explode(' ', $sName);
	//
	// // [=>CBH] fixed fis_name -> first_name on 2015-10-28
	$oAtt->first_name  = $aNames[0];
	$oAtt->last_name  = $aNames[ count($aNames) - 1];
	$oAtt->email = $oAuthToken->getEmail();
	$oAtt->auth_token = $oAuthToken->getAuthToken();

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