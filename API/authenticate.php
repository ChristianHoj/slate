<?php // all the scripts should be saved as UTF8 // æ
@session_start();

require_once './includes/initialize.php';
global $oDB;

/* $aPOST = $_POST;*/

$aPOST = providiPostBody();
$oResponse = new stdClass();


if($_SERVER['HTTP_HOST']=='127.0.0.1') {
	include 'inc.local.authorize.php';
}

try {

/*	$a = $oDB->isLatin();
	var_dump($a);
	$a = $oDB->isUTF8();
	var_dump($a);
	$oDB->setUTF8();
	$a = $oDB->isLatin();
	var_dump($a);
	$a = $oDB->isUTF8();
	var_dump($a);
	
	exit;
*/
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

	$oAuthToken = providiAuthentication::Login($oDB , $aPOST['user'] , $aPOST['pass'] ,@$aPOST['country']);

	if(empty($oAuthToken)) {
		throw new providiUnauthorizeException('Invalid username or password - pass expected' , 5002);
	}

	$oData = new stdClass();
	$oData->type = 'user';
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

	if($_SERVER['HTTP_HOST'] == '127.0.0.1') {
		$_SESSION['__local_auth'] = $oAtt->auth_token;
	}

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