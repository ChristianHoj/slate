<?php

require_once './includes/initialize.php';
require_once './includes/providiLead.class.php';
global $oDB;
$oResponse = new stdClass();

if($_SERVER['HTTP_HOST'] =='127.0.0.1') {
	include './inc.local.authorize.php';
}


// $aGET = $_GET;
$aGET = providiPostBody();
if(empty($aGET['token']) && !empty($_GET['token'])) {
	$aGET['token'] = $_GET['token'];
}
if(empty($aGET['userId']) && !empty($_GET['userId'])) {
	$aGET['userId'] = $_GET['userId'];
}


try {



##########################
### DEL ME
##########################



	$oAuth = ProvidiAuthentication::loadFromAuthToken($oDB , $aGET['token']);
	if($oAuth->providiID != $aGET['userId']) {
		throw new providiUnauthorizeException('Account mismatched - userId' , 5102);
	}

	if(empty($aGET['from_date']) && empty($aGET['to_date'])) {
		$aGET['from_date'] = date('Y-m-d' , strtotime(' -7 DAY '));
		$aGET['to_date'] = date('Y-m-d');
	}
	

	$oLead = new ProvidiLead($oDB);
	$oLead->load($aGET['leadId']);
	if($oLead->getOwner()  !=  $oAuth->providiID) {
		throw new providiUnauthorizeException('lead owner mismatched' , 5201);
	}


	$bNeedSave = false;
	if(isset($aGET['status'])) {
		if($aGET['status'] == '""') {
			$aGET['status'] = '';
		}

		if($aGET['status'] == '') {
			$aGET['status'] = 'not_contacted';
		}

		$oLead->setLeadEvaluation($aGET['status']);
		$bNeedSave = true;
	}
	if(isset($aGET['lead_name'])) {
		// $oLead->navn = $aGET['lead_name'];	
		$oLead->setName($aGET['lead_name']);
		$bNeedSave = true;
	}

	if(!empty($aGET['lead_telephone']) && empty($aGET['lead_phone'])) {
		$aGET['lead_phone'] = $aGET['lead_telephone'];
	}
	if(isset($aGET['lead_phone'])) {
		//	$oLead->telefon = $aGET['lead_phone'];
		$oLead->setTelephone($aGET['lead_phone']);

		$bNeedSave = true;
	}

	if($bNeedSave) {
		$oLead->save();
	} /* else {
		throw new providiBadRequestException('Invalid request parameter' , 5202);
	}*/




//	providiGetDistributorInfo
	$oData = new stdClass();
	$oData->type = 'update_lead';
// 2015-11-09
//	$oData->id = $oAuth->providiID;
	$oData->id = $oLead->id;

	$oAtt = new stdClass();
	$oAtt->status = 'ok';
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
    "type": "update_lead",
    "id": 123456,
    "attributes": {
      "status": "ok"
    }
  }
}
*/
?>