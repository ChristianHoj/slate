<?php // æ

require_once './includes/initialize.php';
global $oDB , $oAuth;
$oResponse = new stdClass();

if($_SERVER['HTTP_HOST'] =='127.0.0.1') {
	include './inc.local.authorize.php';
}

$aGET = $_GET;

try {

	if(empty($aGET['sponsor_id'])) {
		throw new providiBadRequestException('Invalid request parameter - sponsor_id' , 770);
	} 


	

	$oSponsor = new ProvidiDistributor($oDB);
	// may generate exception 5010 - Cannot find user with providiID - 2212112400
	$oData = new stdClass();
	$oData->type = 'sponsor';
	$oData->id = "";
	$oAtt = new stdClass();

	try {
	$oSponsor->loadFromProvidiID($aGET['sponsor_id']);
	} catch(Exception $e) {
		if($e->getCode() != 5010) {
			throw $e;
		}
	}

	if($oSponsor->isActive()) {
		$oData->type = 'sponsor';
		$oData->id = $oSponsor->getProvidiID();
		

		$oAtt->sponsorId = $oSponsor->getProvidiID();
		$oAtt->sponsor_name = ucwords(strtolower($oSponsor->getName()));
		$oAtt->partner_name = ucwords(strtolower($oSponsor->getPartnerName()));

	/*	$oAtt->upline_sup_id = ''; 
		$oAtt->upline_sup = ''; 
		$oAtt->upline_wt = '';
		$oAtt->upline_wt_id = '';
		$oAtt->upline_get = '';
		$oAtt->upline_get_id = '';
		$oAtt->upline_mill_id = '';
		$oAtt->upline_mill = '';		
	*/
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
/*
{
  "errors": [
    {
      "status": "422",
      "source": { "pointer": "/data/attributes/first-name" },
      "title":  "Invalid Attribute",
      "detail": "First name must contain at least three characters."
    }
  ]
}
*/
?>