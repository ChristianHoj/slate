<?php

require_once './includes/initialize.php';
global $oDB , $oAuth;
$oResponse = new stdClass();

if($_SERVER['HTTP_HOST'] =='127.0.0.1') {
	include './inc.local.authorize.php';
}

$aGET = $_GET;

try {


	$oAuth = ProvidiAuthentication::loadFromAuthToken($oDB , $aGET['token']);
	if($oAuth->providiID != $aGET['userId']) {
		throw new providiUnauthorizeException('Account mismatched - userId' , 5102);
	}

	if(empty($aGET['from_date']) && empty($aGET['to_date'])) {
		$aGET['from_date'] = date('Y-m-d' , strtotime(' -7 DAY '));
		$aGET['to_date'] = date('Y-m-d');
	} else {
		if(!empty($aGET['from_date'])) {
			$aGET['from_date'] = providiDateTime($aGET['from_date'] , 'SQL');
		}
		if(!empty($aGET['to_date'])) {
			$aGET['to_date'] = providiDateTime($aGET['to_date'] , 'SQL');
		}
	
	
	}


	require_once './includes/providiLead.class.php';

	$oLeadList = new providiLeadList($oDB);


	$oAtt = new stdClass;
	$oAtt->leads = $oLeadList->getList($aGET['userId'] , @$aGET['from_date'] , @$aGET['to_date'] , @$aGET['mode']);



//	providiGetDistributorInfo

	$oData = new stdClass();
	$oData->type = 'leads';
	$oData->id = $oAuth->providiID;
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
    "type": "leads",
    "id": 1,
    "attributes": {
      "leads": [
        {
          "email": "candidate@email.com",
          "id": 123456,
          "lead_type": "own",
          "message": "Some explanation of why weight loss is desired.",
          "name": "Candi Date",
          "order": 1,
          "origin": "idealvaegt.dk",
          "phone": "12345678",
          "serious": "yes",
          "status": "signed_up",
          "weight_loss": "10-15",
          "zipcode": "9235"
        },
        {
          "email": "candidate2@email.com",
          "id": 7654432,
          "lead_type": "bonus",
          "message": "Text from questionnaire",
          "name": "Will Buyer",
          "order": 2,
          "origin": "idealvaegt.dk",
          "phone": "87654321",
          "serious": "maybe",
          "status": "",
          "weight_loss": "2-5",
          "zipcode": "54329"
        }
      ]
    }
  }
} */
?>