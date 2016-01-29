<?php // all the scripts should be saved as UTF8 // æ

require_once './includes/initialize.php';
global $oDB , $oAuth;
$oResponse = new stdClass();


$aGET = providiPostBody();

/*if($_SERVER['HTTP_HOST'] =='127.0.0.1' && count($_POST) == 0) {
	$_POST['adminId'] = 'admin';
	$_POST['token'] = 'ADM3f9fa498c7bd352fd98f57589f7f19be';

	$_POST['event_type'] = 'seminar';
	$_POST['meeting_session_from'] = '2015-09-28T00:00:00+00:00';
	$_POST['meeting_session_to'] = '2015-09-28T12:00:00+00:00';
	$_POST['event_hotel'] = 'Falconer';
	$_POST['event_city'] = 'København';

	$_POST['max_seat'] = 20;
	$_POST['debug'] = 1;
	$aGET = $_POST;
}
*/

try {

	$oAdminAuth = ProvidiAdminAuthentication::loadFromAuthToken($oDB , $aGET['token']);



	if($oAdminAuth->providiID != $aGET['adminId']) {
		throw new providiUnauthorizeException('Account mismatched - adminId' , 15102);
	}

	// check neccesary parameter
	$aAllowedType = array('intro', 'startup', 'sales_training', 'seminar', 'vs_club_evening' ,'test');
	if(empty($aGET['event_type']) || !in_array($aGET['event_type'] , $aAllowedType)) {
		throw new providiBadRequestException ('Invalid request parameter , type' ,  16001);
	}
	/* removed on 20160106
	if(empty($aGET['event_hotel'])) {
		throw new providiBadRequestException ('Invalid request parameter , event_hotel' ,  16002);
	}
	*/
	if(empty($aGET['meeting_session_from'])) {
		throw new providiBadRequestException ('Invalid request parameter , meeting_session_from' ,  16003);
	}
	if(empty($aGET['event_city'])) {
		throw new providiBadRequestException ('Invalid request parameter , event_city' ,  16004);
	}

	require_once './includes/providiEvent.class.php';
	$oEvent = new providiEvent($oDB);

	$oEvent->setEventType($aGET['event_type']);
	$oEvent->setCity($aGET['event_city']);
	$oEvent->setMeetingSessionFromString($aGET['meeting_session_from']);

	// 20160106 , event hotel is now optional , use the dummy one in table hotel
	if(empty($aGET['event_hotel'])) {
		$aGET['event_hotel'] = 'no_info';
	}

	$oEvent->setHotel($aGET['event_hotel']);

	if(!empty($aGET['meeting_session_to'])) {
		$oEvent->setMeetingSessionTo($aGET['meeting_session_to']);
	}

	if(isset($aGET['max_seat'])) {
		$oEvent->setMaximumSeats($aGET['max_seat']);
	}

	$sComment = sprintf('created by %s[%s] on %s', basename(__FILE__), $oAdminAuth->getProvidiID() , date('Y-m-d H:i:s'));

	$oEvent->setComments($sComment);
	$oEvent->save();


	$oData = new stdClass();
	$oData->id = sprintf('%d', $oEvent->getID());
	$oData->type = 'event';

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