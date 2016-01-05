<?php

require_once './includes/initialize.php';
global $oDB;
$oResponse = new stdClass();

if($_SERVER['HTTP_HOST'] =='127.0.0.1') {
	include './inc.local.authorize.php';
}

$aGET = $_GET;

try {


	if(empty($aGET['token'])) {
		throw new providiUnauthorizeException('Invalid request parameter - token' , 5100);
	}
	if(empty($aGET['userId'])) {
		throw new providiUnauthorizeException('Invalid request parameter - userId' , 5101);
	}

	$oAuth = ProvidiAuthentication::loadFromAuthToken($oDB , $aGET['token']);
	if($oAuth->providiID != $aGET['userId']) {
		throw new providiUnauthorizeException('Account mismatched - userId' , 5102);
	}

	if(empty($_GET['token_age'])) {
		throw new providiUnauthorizeException('Invalid requst parameter - token_age' , 5107);
	}

	$nVU = time();
	$nVU += intval($nVU);

	$oData = new stdClass();
	$oData->id = $aGET['userId'];
	$oData->type = 'secret_hash';
	$oAtt = new stdClass();
	$oAtt->hash = getProvidiAuthorizeHash( $aGET['userId'], $nVU);
	$oAtt->medlid = $aGET['userId'];
	$oAtt->vu = $nVU;
	$oData->attributes  = $oAtt;


	$oResponse->data = $oData;

} catch (Exception $e) {
	providiJSONErrorHandler($oResponse , $e);
}

if(isset($_GET['debug'])) {
	print '<PRE>';
	print_r($oResponse);	
}

providiJSONResponse($oResponse);


if(!function_exists('getProvidiAuthorizeHash')) {
	function getProvidiAuthorizeHash($sMedlid , $nVU=null){
		if(empty($nVU)) {
			$nVU = time() + (60 * 60 * 3); 
		}
		return md5(sprintf('%s Avengers @ssemble! %s 2015' ,  $sMedlid , $sVU));
	}
}


?>