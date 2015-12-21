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

	$oData = new stdClass();
	$aPos = array();
	$oData->id = $aGET['promotion'];
	$oData->type = 'promotion_score';

	$sCountry = @strtoupper($aGET['country']);
	if(in_array($sCountry , array('DK'))) {

		if($aGET['promotion'] == 2015) {
			global $aQualificationList;
			require_once './includes/inc.qualification.dk.2015.php';
			/*$oPos = new stdClass();
			$oPos->promotion_id = $oAtt->id;
			$oPos->positions = $aQualificationList;
			$oAtt->attributes = $oPos;					
			*/

			$aTemp = $aQualificationList;
			$oProm = new stdClass();
			$oProm->id = $aGET['id'];
			$oProm->type = promotion_score;

			
			for($i=0;$i<count($aTemp);$i++) {
				$oP = $aTemp[$i];

				$oPos = new stdClass();
				$oPos->position = $oP->position;
				$oPos->name = $oP->name;
				$oPos->points = $oP->points;
				$oPos->image = $oP->image;

				$aPos[] = $oPos;			
			}

			
		} // end 2015
	}

	$oAtt = new stdClass();
	$oAtt->positions = $aPos;
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