<?php // æ all files must be saved as UTF8


abstract class providiEventAttendee extends providiObject {
	function _getNewCodePrefix() {
		return 10000;
	}

	abstract function setAddress($sTheValue);
	abstract function getAddress();

	abstract function setCity($sTheValue);
	abstract function getCity();
	abstract function setEmail($sTheValue);
	abstract function getEmail();
	abstract function setInvitedBy($sTheValue);
	abstract function getInvitedBy();
	abstract function setName($sTheValue);
	abstract function getName();
	abstract function setPhone($sTheValue);
	abstract function getPhone();
	abstract function setAttendeeType($sTheValue);
	abstract function getAttendeeType();
	abstract function setZipCode($sTheValue);
	abstract function getZipCode();
	abstract function setEventID($sTheValue);
	abstract function getEventID();
	abstract function setProvidiEventID($sTheValue);
	abstract function getProvidiEventID();
	abstract function setHost($sTheValue);
	abstract function getHost();
	abstract function setGuestContactVia($sTheValue);
	abstract function getGuestContactVia();

	abstract function setUserID($sTheValue);
	abstract function getUserID();

	abstract function setMeetingID($sTheValue);
	abstract function getMeetingID();

	function setTelephone($sTheValue) {
		return $this->setPhone($sTheValue);
	}
	function getTelephone() {
		return $this->getPhone();
	}

}

class providiSeminarAttendee extends providiEventAttendee {
	function _setBaseTable() {
		$this->_sTableName = 'org2';
		$this->_sTableCharset = 'latin1';
	}

	function getAddress() {
		return '';
	}
	function setAddress($sNewValue) {
		return '';
	}
	 function setCity($sTheValue) {
		 return $this->bynavn = $sTheValue;	 
	 }
	 function getCity() {
		 return $this->bynavn;
	 }
	 function setEmail($sTheValue) {
		return '';
	 }
	 function getEmail() {
		return '';
	 }
	 function setInvitedBy($sTheValue) {
		return '';
	 }
	 function getInvitedBy() {
		return '';
	 }
	 function setName($sTheValue) {
		return $this->navn = $sTheValue;
	 }
	 function getName() {
		return $this->navn;
	 }
	 function setPhone($sTheValue) {
		return '';
	 }
	 function getPhone() {
		return '';
	 }
	 function setAttendeeType($sTheValue) {
		return '';
	 }
	 function getAttendeeType() {
		return '';
	 }
	 function setZipCode($sTheValue) {
		return $this->postnr = $sTheValue;
	 }
	 function getZipCode() {
		 return $this->postnr;
	 }
	 function setEventID($sTheValue) {
		return $this->id = $sTheValue;
	 }
	 function getEventID() {
		return $this->id;
	 }
	 function setProvidiEventID($sTheValue) {
		return '';
	 }
	 function getProvidiEventID() {
		return $this->id + $this->_getNewCodePrefix();
	 }
	 function setHost($sTheValue) {
		return '';
	 }
	 function getHost() {
		return '';
	 }

	 function setType($sTheValue) {
		return $this->status = $sTheValue;
	 }
	 function getType() {
		return $this->status;
	 }

	 function setGuestContactVia($sTheValue) {
		return '';
	 }
	 function getGuestContactVia() {
		return '';
	 }
	 function setProvidiID($sNewValue) {
		 return $this->medlid = $sNewValue;
	 }
	 function getProvidiID() {
		return $this->medlid;
	 }

	 function setMeeting($oMeeting) {
		 $sMS = $oMeeting->getMeetingSession();
		 $this->meeting_session = $sMS;
		 $this->meetingID = $oMeeting->id;
		 $this->providiMeetingID = $oMeeting->id + $this->_getNewCodePrefix();		 		 
	 }
	 function getMeetingID() {
		 $this->meetingID;
	 }
	 function setMeetingID($sValue) {
		 $this->meetingID = $sTheValue;
		 return $this->meetingID;

	 }
	 function save() {
		 if(empty($this->sponsor_id)) {
			$oMe = new providiDistributor($this->_oDB);
			$oMe->loadFromProvidiID($this->getProvidiID());


			$this->sponsor_id = $oMe->getSponsorID();
			$this->sponsor = $oMe->getSponsor();
		 }

		 if(empty($this->getID())) {
			$this->createdOn = providiDateTime(date('Y-m-d H:i:s'), 'SQL');
			$this->createdBy = providiGetCurrentAuthID();
			if(empty($this->comments)) {
				$this->comments = sprintf("\ncreated by %s" , __FILE__);
			} else {
				$this->comments .= sprintf("\ncreated by %s" , __FILE__);
			}
			
		 }

		 if(empty($this->meetingID)) {
			 $this->_getMeetingIDFromSession('org', $this->meeting_session);
		 }

		 $this->mdato = $this->_getMeetingSessionText($this->meetingID);
		 $this->former_meeting_session = $this->_getFormerMeetingSession($this->meetingID , $this->meeting_session);

		 if(empty($this->status)) {
			$this->status = 'bhip_distributor';
		 }
		 parent::save();	 
	 }


	 function _getFormerMeetingSession($nMeetingID) {
		 $oM = new providiEvent($this->_oDB);
		 $oM->load($nMeetingID);

		 $sQuery = sprintf(' SELECT meeting_session FROM dato_fm1_fm2 WHERE type = "org" AND meeting_session < "%s" ORDER BY meeting_session DESC LIMIT 1 ' , $oM->getMeetingSession());
		 return $this->_oDB->getVar($sQuery);
		 
	 }
	 ################################################
	 ### _getMeetingSessionText(), such disgrace to programming history, let it rot on this database
	 ################################################

	 function _getMeetingSessionText($nMeetingID) {
		 $oM = new providiEvent($this->_oDB);
		 $oM->load($nMeetingID);

		 $aMonths = array('', "januar","februar","marts","april","maj","juni","juli","august","september","oktober","november","december");

		 $sDate = substr($oM->fra , 8 , 2);
		 if(substr($sDate,0,1) == '0') {
			 $sDate = substr($sDate,1,1 );
		 }

		 $aDates = array( $sDate );
		 if(substr($oM->dato,0,4) != '0000') {
			 $sDate = substr($oM->dato , 8 , 2);
			 if(substr($sDate,0,1) == '0') {
				 $sDate = substr($sDate,1,1 );
			 }
			$aDates[] = $sDate;
		 }
		 if(substr($oM->fra, 5 , 2)  != substr($oM->dato , 5 , 2)) {
			 $nMonth = substr($oM->fra,5,2 );
			 if(substr($nMonth, 0 , 1) == '0') {
				$nMonth = substr($nMonth, 1 , 1);
			 }
			 $nMonthTo = substr($oM->dato,5,2 );
			 if(substr($nMonthTo, 0 , 1) == '0') {
				$nMonthTo = substr($nMonthTo, 1 , 1);
			 }
			return sprintf('%s.%s - %s.%s %s' , $aDates[0], $aMonths[ $nMonth ] , $aDates[1] , $aMonths[ $nMonthTo ] , substr($oM->fra,0,4));		 
		 }
		 $nMonth = substr($oM->fra,5,2 );
		 if(substr($nMonth, 0 , 1) == '0') {
			$nMonth = substr($nMonth, 1 , 1);
		 }
		return sprintf('%s. %s %s' , implode('-',$aDates), $aMonths[ $nMonth ] , substr($oM->fra,0,4));
	 }

	 function _getMeetingIDFromSession($sType, $sSession) {
		 // first , attempt with full datetime of meeting_session
		 $sQuery = sprintf(' SELECT id FROM dato_fm1_fm2 WHERE type = "%s" AND meeting_session = "%s" ' , $sType , $sSession);
		 $nID = $this->_oDB->getVar($sQuery);
		 if(!empty($nID)) {
			return $nID;
		 }
		 $sQuery = sprintf(' SELECT id FROM dato_fm1_fm2 WHERE type = "%s" AND DATE(meeting_session) = "%s" ' , $sType , $sSession);
		 $nID = $this->_oDB->getVar($sQuery);
		 if(!empty($nID)) {
			return $nID;
		 }
		 throw new providiBadRequestException( sprintf('cannot find meeting with type = %s , session = %s' , $sType , $sSession) , 5301);

	 }

	 function getUserID() {
		return $this->medlid;
	 }
	 function setUserID($sTheValue) {
		return $this->medlid = $sTheValue;
	 }
}





class providiNewTableAttendee extends providiEventAttendee {
	function _setBaseTable() {
		$this->_sTableName = 'providi_booked_meetings';
		$this->_sTableCharset = 'latin1';
	}

	function getAddress() {
		return '';
	}
	function setAddress($sNewValue) {
		return '';
	}

	 function setCity($sTheValue) {
		 return '';	 
	 }
	 function getCity() {
		 return '';
	 }
	 function setEmail($sTheValue) {
		return $this->bookerEmail = $sTheValue;
	 }
	 function getEmail() {
		return $this->bookerEmail;
	 }
	 function setInvitedBy($sTheValue) {
		return '';
	 }
	 function getInvitedBy() {
		return '';
	 }
	 function setName($sTheValue) {
		return $this->bookerName = $sTheValue;
	 }
	 function getName() {
		return $this->bookerName;
	 }
	 function setPhone($sTheValue) {
		return $this->bookerPhone = $sTheValue;
	 }
	 function getPhone() {
		return $this->bookerPhone;
	 }
	 function setAttendeeType($sTheValue) {
		return '';
	 }
	 function getAttendeeType() {
		return '';
	 }
	 function setZipCode($sTheValue) {
		return $this->postnr = $sTheValue;
	 }
	 function getZipCode() {
		 return $this->postnr;
	 }
	 function setEventID($sTheValue) {
		return $this->id = $sTheValue;
	 }
	 function getEventID() {
		return $this->id;
	 }
	 function setProvidiEventID($sTheValue) {
		return '';
	 }
	 function getProvidiEventID() {
		return $this->id + $this->_getNewCodePrefix();
	 }
	 function setHost($sTheValue) {
		return '';
	 }
	 function getHost() {
		return '';
	 }

	 function setType($sTheValue) {
		return $this->bookerType = $sTheValue;
	 }
	 function getType() {
		return $this->bookerType;
	 }

	 function setGuestContactVia($sTheValue) {
		return '';
	 }
	 function getGuestContactVia() {
		return '';
	 }
	 function setProvidiID($sNewValue) {
		 return $this->providiID = $sNewValue;
	 }
	 function getProvidiID() {
		return $this->providiID;
	 }


	 function setUserID($sNewValue) {
		 return $this->providiID = $sNewValue;	 
	 }
	 function getUserID() {
		 return $this->providiID;	 
	 }

	 function setMeeting($oMeeting) {
		 $sMS = $oMeeting->getMeetingSession();
		 $this->meetingSession = providiDateTime($sMS, 'SQL');
		 $this->meetingID = $oMeeting->id;
		 // will be skipped automatically if the field weren't there
		 $this->providiMeetingID = $oMeeting->id + $this->_getNewCodePrefix();
		 $this->meetingType = $oMeeting->getType();
		 $this->meetingCity = $oMeeting->getCity();
	 }

	 function save() {

		 if(empty($this->id)) {
			$this->createdOn = providiDateTime(date('Y-m-d H:i:s'), 'SQL');
			$this->createdBy = providiGetCurrentAuthID();
			if(empty($this->comments)) {
				$this->comments = sprintf("\ncreated by %s on %s" , __FILE__, date('Y-m-d H:i:s'));
			} else {
				$this->comments .= sprintf("\ncreated by %s on %s" , __FILE__ , date('Y-m-d H:i:s'));
			}			
		 }

		 if(empty($this->bookerEarningRank)) {
			 $oBooker = new providiDistributor($this->_oDB);
			 $oBooker->loadFromProvidiID($this->getProvidiID());
		 
			$this->bookerEarningRank = $oBooker->getEarningRank();
		 }


		 if(empty($this->bookerLeadershipRank)) {
			if(empty($oBooker)) {
				$oBooker = new providiDistributor($this->_oDB);
				 $oBooker->loadFromProvidiID($this->getProvidiID());
			}
			$this->bookerLeadershipRank = $oBooker->getleadershipRank();
		 }
			 

		if(empty($this->getType())) {
			$this->setType('self');
		}
		$this->bookingScript = basename($_SERVER['REQUEST_URI']);

		parent::save();
	 
	 }

	function setMeetingID($sTheValue) {
		return $this->meetingID = $sTheValue;
	}
	function getMeetingID() {
		return $this->meetingID;
	}
}

class providiStartupAttendee extends providiNewTableAttendee {
	function save() {
		// overwrite for now
	    $this->meetingType = 'providi_opstartsmode';
		parent::save();
	}
}
class providiCustomerSalesAttendee extends providiNewTableAttendee {
	function save() {
		// overwrite for now
	    $this->meetingType = 'providi_kundesalgstraening';
		parent::save();
	}
}


class providiIntroductionAttendee extends providiEventAttendee {

	function _setBaseTable() {
		$this->_sTableName = 'SC_booked_meetings';
		$this->_sTableCharset = 'latin1';
	}

	 function setProvidiID($sNewValue) {
		 return $this->hbl_id = $sNewValue;
	 }
	 function getProvidiID() {
		 return $this->hbl_id;
	 }

	function setAddress($sTheValue) {
		return null;
	}
	function getAddress() {
		return null;
	}
	function setCity($sTheValue) {
		return null;
	}
	function getCity() {
		return null;
	}
	function setEmail($sTheValue) {
		return $this->guest_email = $sTheValue;  
	}
	function getEmail() {
		return $this->guest_email;
	}
	function setInvitedBy($sTheValue) {
		return $this->inviter_name = $sTheValue;
	}
	function getInvitedBy() {
		return $this->inviter_name ;
	}
	function setName($sTheValue) {
		return $this->guest_name  = $sTheValue;
	}
	function getName() {
		return $this->guest_name;
	}
	function setPhone($sTheValue) {
		return null;
	}
	function getPhone() {
		return null;
	}
	function setAttendeeType($sTheValue) {
		return $this->guest_status = $sTheValue;
	}
	function getAttendeeType() {
		return $this->guest_status;
	}
	function setZipCode($sTheValue) {
		return null;
	}
	function getZipCode() {
		return null;
	}
	function setEventID($sTheValue) {
		return $this->dato_id = $sTheValue;
	}
	function getEventID() {
		return $this->dato_id;
	}
	function setProvidiEventID($sTheValue) {
		return null;
	}
	function getProvidiEventID() {
		return null;
	}
	function setHost($sTheValue) {
		return $this->contact_person = $sTheValue;
	}
	function getHost() {
		return $this->contact_person;
	}
	function setGuestContactVia($sTheValue) {
		return $this->guest_source = $sTheValue;
	}
	function getGuestContactVia() {
		return $this->guest_source;
	}

	function setUserID($sTheValue) {
		return $this->hbl_id = $sTheValue;
	}
	function getUserID() {
		return $this->hbl_id;
	}

	function setMeetingID($sTheValue) {
		return $this->dato_id = $sTheValue;
	}
	function getMeetingID() {
		return $this->dato_id;
	}
	function getType() {
		return $this->guest_status;
	}
	function setType($sNewValue) {
		return $this->guest_status = $sNewValue;
	}

	function setMeeting($oMeeting) {
		$this->dato_id = $oMeeting->getID();
		$this->meeting_city = $oMeeting->getCity();
		$this->meeting_session = $oMeeting->getMeetingSession();
	}

	function save() {
		
		if(empty($this->getType())) {
			$this->setType('SC Introduktionsaften');
		}


		// handling the missing fields
		$oUser = new providiDistributor($this->_oDB);
		$oUser->loadFromProvidiID($this->getUserID());
		if(empty($this->inviter_name)) {
			$this->inviter_name = $oUser->getName();			
		}
		if(empty($this->inviter_email)) {
			$this->inviter_email = $oUser->getEmail();			
		}
		if(empty($this->inviter_status)) {
			$this->inviter_status = 'providi distributor';
		}

		if(empty($this->booked_on)) {
			$this->booked_on = date('Y-m-d H:i:s');
			$sTheText = sprintf('created by %s on %s' , $_SERVER['REQUEST_URI'], $this->booked_on);
			$this->comments = $sTheText;
		}
		if(empty($this->meeting_type)) {
			$this->meeting_type = 'SC Introduktionsaften';
		}
		parent::save();
	
	}
}

?>