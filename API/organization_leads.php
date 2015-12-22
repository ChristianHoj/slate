<?php // æ

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


	$aOptions = array();
	if(empty($aGET['userId'])) {
		throw new providiBadRequestException('Invalid recruit lead owner', 10410);
	}
	$aOptions['userId'] = $aGET['userId'];


	if(empty($aGET['from_date']) && empty($aGET['to_date'])) {
		$aOptions['from_date'] = $aGET['from_date'] = date('Y-m-d' , strtotime(' -7 DAY '));
		$aOptions['to_date'] = $aGET['to_date'] = date('Y-m-d');

		
	} else {	
		if(!empty($aGET['from_date'])) {
			$aOptions['from_date'] = $aGET['from_date'] = providiDateTime($aGET['from_date'] , 'SQL');
		}
		if(!empty($aGET['to_date'])) {
			$aOptions['to_date'] = $aGET['to_date'] = providiDateTime($aGET['to_date'] , 'SQL');
		}	
	}


	require_once './includes/providiRecruitLead.class.php';
	$oRecruitLeadList = new providiRecruitLeadList($oDB);
	

	
		
	$aLeads = $oRecruitLeadList->getList($aOptions);

	$aData = array();
	for($i=0;$i<count($aLeads);$i++) {
		$oLead = $aLeads[$i];

		$oTemp = new stdClass();
		$oTemp->type = 'organization_leads';
		$oTemp->id = $oLead->getID();

		$oAtt = new stdClass();
        $oAtt->age = $oLead->getAge();
		$oAtt->email = $oLead->getEmail();
		$oAtt->expected_earnings = $oLead->getExpectedEarnings();
		$oAtt->lead_assigned_date = providiDateTime($oLead->getAssignedDate() ,'text');
		$oAtt->message = $oLead->getMessage();
		$oAtt->name = $oLead->getName();
		$oAtt->origin = ''; // force blank
		$oAtt->phone = $oLead->getTelephone();
		$oAtt->zipcode = $oLead->getZipCode();
		$oTemp->attributes = $oAtt;
		
		$aData[] = $oTemp;
	}


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