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
		// [=>CBH]  changed default dates to full date formats
		$aGET['from_date'] = date('Y-m-d 00:00:00' , strtotime(' -7 DAY '));
		$aGET['to_date'] = date('Y-m-d 23:59:59');
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


	$aData = array();
	$aTemp = $oLeadList->getList($aGET['userId'] , @$aGET['from_date'] , @$aGET['to_date'] , @$aGET['mode']);

	
	for($i=0;$i<count($aTemp);$i++) {

		$oL = $aTemp[$i];

		$oTheLead = new stdClass();
		$oTheLead->type = 'leads';
		$oTheLead->id = $oL->id;

		$oAtt = new stdClass();
		$oAtt->email = $oL->email;
		$oAtt->lead_assigned_date = $oL->lead_assigned_date;
		$oAtt->lead_type = $oL->lead_type;
		$oAtt->message = $oL->message;
		$oAtt->name = $oL->name;
		$oAtt->origin = '';
		$oAtt->phone = $oL->phone;
		$oAtt->serious = $oL->serious;
		$oAtt->status = $oL->status;		

		if($oAtt->status == '') {
			$oAtt->status = 'not_contacted';
		}

		$oAtt->weight_loss = $oL->weight_loss;
		$oAtt->zipcode = $oL->zipcode;
		$oTheLead->attributes = $oAtt;

		$aData[] = $oTheLead;
	}


//	providiGetDistributorInfo

/*	$oData->type = 'leads';
	$oData->id = $oAuth->providiID;
	$oData->attributes = $oAtt;
*/	
	$oResponse->data = $aData;


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
  "data": [
    {
      "type": "leads",
      "id": "123456",
      "attributes": {
        "email": "candidate@email.com",
        "lead_assigned_date": "2015-10-27T14:25:16+01:00",
        "lead_type": "own",
        "message": "Some explanation of why weight loss is desired.",
        "name": "Candi Date",
        "origin": "idealvaegt.dk",
        "phone": "12345678",
        "serious": "yes",
        "status": "signed_up",
        "weight_loss": "10-15",
        "zipcode": "9235"
      }
    },
    {
      ...
    }
  ]
}


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