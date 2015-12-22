<?php // æ

require_once './includes/initialize.php';
global $oDB;
$oResponse = new stdClass();


if($_SERVER['HTTP_HOST'] =='127.0.0.1') {
	include 'inc.local.authorize.php';
}

$aGET = $_GET;

try {

	$oAuth = ProvidiAuthentication::loadFromAuthToken($oDB , $aGET['token']);
	if($oAuth->providiID != $aGET['userId']) {
		throw new providiUnauthorizeException('Account mismatched - userId' , 5102);
	}


	$aData = array();

	$sCountry = @strtoupper($aGET['country']);
	if(in_array($sCountry , array('DK'))) {
		/*$oMeeting = new stdClass();
		$oMeeting->id = 2015;
		$oMeeting->type = 'promotion';
		$oMA = new stdClass();
		$oMA->title = 'Cruise fra New York 2016';
		$oMeeting->attributes = $oMA;
		$oAtt->data[] =  $oMeeting;			*/

		$oMeeting = new stdClass();
		$oMeeting->id = '2015';
		$oMeeting->type = 'promotion';
		$oMA = new stdClass();
		$oMA->title = 'Cruise fra New York 2016';
		$oMeeting->attributes = $oMA;
		$aData[] = $oMeeting;

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
  "data": [{
    "id": 0,
    "type": "promotion",
    "attributes": {
      "title": "Cruise fra New York 2016"
    }
  },
  {
    "id": 1,
    "type": "promotion",
    "attributes": {
      "title": "Rejse til Thailand 2016"
    }
  }]
}
 */
?>