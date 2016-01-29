<?php // æ


require_once './includes/initialize.php';
global $oDB;
$oResponse = new stdClass();

if($_SERVER['HTTP_HOST'] =='127.0.0.1') {
	include './inc.local.authorize.php';
}



require_once './includes/providiEvent.class.php';
require_once './includes/providiEventAttendee.class.php';




$aGET = providiPostBody();



try {

	if(empty($aGET['token'])) {
		throw new providiUnauthorizeException('Invalid request parameter - token' , 5100);
	}
	if(empty($aGET['userId'])) {
		throw new providiUnauthorizeException('Invalid request parameter - userId' , 5101);
	}


	if(count($aGET['attendees']) <= 0) {
		throw new providiUnauthorizeException('Invalid request parameter - attendees' , 5102);

	}


	$oAuth = ProvidiAuthentication::loadFromAuthToken($oDB , $aGET['token']);
	if($oAuth->providiID != $aGET['userId']) {
		throw new providiUnauthorizeException('Account mismatched - userId' , 5103);
	}


	$oEvent = new providiEvent($oDB);
	$oEvent->load($aGET['event_id']);

	// Attempt by CBH to fix error: "Fatal error: Can't use method return value in write context in /home/providi/providi.eu/docs/API/event_attend.php on line 49"
	$oEventId = $oEvent->getId();
	if(empty($oEventId)) {
		throw new providiBadRequestException('Invalid event id - ' . $aGET['event_id'],  5104);
	}


	$sMeetingType = $oEvent->getType();

	// looping through attendees
	reset($aGET['attendees']);
	$bFailCount = 0;
	$aAttLogs = array();
	$aSavedIDs = array();
	while(list($sUnusedKey , $aAtt) = each($aGET['attendees'])) {

		switch($sMeetingType) {
			case 'seminar'					:	$oAtt = new providiSeminarAttendee($oDB);  break;
			case 'intro'						:	$oAtt = new providiIntroductionAttendee($oDB); break;
			case 'startup'					:	$oAtt = new providiStartupAttendee($oDB);  break;	;
			case 'sales_training'			:	$oAtt = new providiCustomerSalesAttendee($oDB);  break;	;;
			case 'vs_club_evening'		:	break;
			default							:	throw new providiBadRequestException('Invalid event type ' . $sMeetingType , 301);
		}

		$oAtt->setMeeting($oEvent);

		$bOK = false;
		if(empty($aAtt['email'])) {
			$aAttLogs[] = sprintf(' [%s]  Invalid request parameter - email' , $sUnusedKey);
			$bFailCount++;
			continue;
		} else {

			$oAtt->setEmail($aAtt['email']);
		}

		if(empty($aAtt['name'])) {
			$aAttLogs[] = sprintf(' [%s]  Invalid request parameter - name' , $sUnusedKey);
			$bFailCount++;
			continue;
		} else {
			$oAtt->setName($aAtt['name']);
		}
		if(empty($aGET['userId'])) {
			$aAttLogs[] = sprintf(' [%s]  Invalid request parameter - userId' , $sUnusedKey);
			$bFailCount++;
			continue;
		} else {
			$oAtt->setUserID($aGET['userId']);
		}


		if(!empty($aAtt['address'])) {
			$oAtt->setAddress($aAtt['address']);
		}
		if(!empty($aAtt['city'])) {
			$oAtt->setCity($aAtt['city']);
		}
		if(!empty($aAtt['zipcode'])) {
			$oAtt->setZipCode($aAtt['zipcode']);
		}
		if(!empty($aAtt['guest_contact_via'])) {
			$oAtt->setGuestContactVia($aAtt['guest_contact_via']);
		}
		if(!empty($aAtt['host'])) {
			$oAtt->setHost($aAtt['host']);
		}
		if(!empty($aAtt['invited_by'])) {
			$oAtt->setInvitedBy($aAtt['invited_by']);
		}

		if(!empty($aAtt['phone'])) {
			$oAtt->setPhone($aAtt['phone']);
		}


		if(!empty($aAtt['type'])) {
			$oAtt->setType($aAtt['type']);
		}


		$oAtt->save();
		$aSavedIDs[] = $oAtt->getID();

	}

	$oData = new stdClass();
	$oData->id = $aSavedIDs[0];
	$oData->type = 'event_attend';
	$oAtt = new stdClass();
	$oAtt->status = 'OK';
	$oAtt->providi_event_id = $oEvent->getProvidiEventID();
	$oData->attributes = $oAtt;


	if(isset($aGET['debug'])) {
		$oData->attribut_logs = $aAttLogs;
		$oData->saved_ids = $aSavedIDs;

	}


	$oResponse->data = $oData;

} catch (Exception $e) {
	providiJSONErrorHandler($oResponse , $e);
}

if(isset($aGET['debug'])) {
	print '<PRE>';
	print_r($oResponse);
}

providiJSONResponse($oResponse);
/*

{
  "data": {
    "type": "update_user_profile",
    "id": "",
    "attributes": {
      "status": "ok"
    }
  }
}
*/
?>
