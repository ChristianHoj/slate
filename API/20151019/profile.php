<?php

require_once './includes/initialize.php';
global $oDB;
$oResponse = new stdClass();

if($_SERVER['HTTP_HOST'] =='127.0.0.1') {
	$_GET['token'] = 'cb12a5517d6694aa07a33819186eb79963a37df6113d915e5dfad5b23536b13c';
	$_GET['userId'] = '22121124';
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

	$oData = new stdClass();
	$oData->type = 'user';
	$oData->id = $oAuth->providiID;

	$oAtt = new stdClass();
	$oAtt->imageUrl = $oAuth->profileImage;
	$oAtt->accountType = 'distributor';
	$oAtt->name = $oAuth->fullName;
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
/*
{
  "data": {
    "type": "user",
    "id": "SC000XXXXXXX",
    "attributes": {
      "imageUrl": "http://example.com/default-avatar.jpg",
      "accountType": 0,
      "name": "Gabriel Muresan"
    }
  }
}*/
?>