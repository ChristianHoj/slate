<?php  // æ all files must be saved as UTF8


class providiHotel extends providiObject {
	function _setBaseTable() {
		$this->_sTableName = 'hotel';
		$this->_sTableCharset = 'latin1';
	}

	function loadFromHotelCode($sShortName) {
		$oDB = $this->_oDB;
		$this->prepareCharset();
		$sQuery = sprintf(' SELECT * FROM %s WHERE shortname = "%s" LIMIT 1 ' , $this->_sTableName , $oDB->esc($sShortName));
		$sClass = get_class($this);
		$oObj = $oDB->getObject($sQuery , $sClass , array($this->_oDB));
		if(empty($oObj)) {
			return false;
		}
		$this->merge($oObj);
		if($this->isUTF8Table()) {
			$this->toUTF8();
		}
		return $this->hotelid;	
	
	}

}


class providiEvent extends providiObject {
	function _setBaseTable() {
		$this->_sTableName = 'dato_fm1_fm2';
		$this->_sTableCharset = 'latin1';
	}
	protected function getProvidiIDConstant() {
		return 10000;
	}
	function getProvidiEventID() {
		return empty($this->id)?null:$this->id + $this->getProvidiIDConstant();
	}


	function getType($sType=null) {
		if(empty($sType)) {
			$sType = $this->type;
		}

		$aTheCodes = $this->getAvailableEventCodes();
		if(isset($aTheCodes[ strtolower($sType) ])) {
			return strtolower($sType);
		}
		reset($aTheCodes);
		while(list($sKey, $sValue) = each($aTheCodes)) {
			if(strtolower($sValue) == strtolower($sType)) {
				return $sKey;
			}		
		}
		return '';
	}
	static function getAvailableEventCodes() {
		$aTheCodes = array(
			'intro' => 'SC Introduktionsaften'
			, 'startup' => 'providi_opstartsmode'
			, 'sales_training' => 'providi_kundesalgstraening'
			, 'seminar' => 'Org'
			, 'vs_club_evening' => 'Klubaften'
			, 'test' => 'Test æ, æwith æDanish Char æ'
		);
		return $aTheCodes;
	}
	function getOldEventCode($sNewCode) {
		$aTheCodes = providiEvent::getAvailableEventCodes();
		if(isset($aTheCodes[ $sNewCode])) {
			return $aTheCodes[ $sNewCode];
		}
		return false;
	}
	function getNewEventCode($sOldCode) {
		$aTheCodes = providiEvent::getAvailableEventCodes();
		$aTheCodes = array_flip($aTheCodes);
		if(isset($aTheCodes[ $sOldCode])) {
			return $aTheCodes[ $sOldCode];
		}
		return false;
	}



	### accessors & mutators 
	function getEventType() {
		return $this->type;
	}
	function setEventType($sNewValue,$bCheckConsistency = true) {
		if($bCheckConsistency) {
			$sNewValue = providiEvent::getOldEventCode($sNewValue);
			if(empty($sNewValue)) {
				throw new providiBadRequestException('Invalid event type ' , 10101);
			}
			
		}
		return $this->type = $sNewValue;
	}
	function getCity() {
		return $this->sted;
	}
	function setCity($sNewValue) {
		return $this->sted = $sNewValue;
	}
	function getMeetingStartTime() {
		return $this->city_time;
	}
	function setMeetingStartTime($sNewValue) {
		return $this->city_time = $sNewValue;
	}
	function getMeetingSession() {
		return $this->getMeetingSessionFrom();
	}

	function getMeetingSessionFrom() {
		return $this->meeting_session;
	}
	function setMeetingSessionFrom($sNewValue) {
		return $this->meeting_session;
	}

	function getMeetingSessionTo() {
		return $this->fra;
	}
	function setMeetingSessionTo($sNewValue) {
		return $this->fra = providiDateTime($sNewValue , 'SQL');
	}

	function getHotel() {
		return $this->meeting_hotel;
	}
	function setHotel($sNewValue , $bCheckConsistency=true) {
		if($bCheckConsistency) {
			$oDB = $this->_oDB;
			$oHotel = new providiHotel($oDB);
			if(!$oHotel->loadFromHotelCode($sNewValue)) {
				throw new providiBadRequestException('Invalid meeting hotel code' , 10102);
			}
		
		}
		return $this->meeting_hotel = $sNewValue;
	}

	function setMeetingSessionFromString($sDateTimeText) {
		$sTempDate = providiTrimSpaces($sDateTimeText);

		//var_dump($sTempDate);
		
		$oDate = providiDateTime($sDateTimeText ,'object' , constant('PROVIDILIB_USE_ORIGINAL_TIMEZONE'));
		$sRawDate = providiDateTime($sDateTimeText ,'SQL');
		// if neccessary (timezone was included) , change to the local time using setTimeZone() 
		/* CANT BE USED 
		if(in_array($sPos , array('+', '-'))) {
			$sTZ = substr($sTempDate , -6 );
			$oTZ = new DateTimeZone($sTZ);;
			$oDate->setTimeZone($oTZ);			
		} 
		*/
		$oTZ = $oDate->getTimezone();
		@$oDate->setTimeZone($oTZ);			


		$this->setMeetingStartTime($oDate->format('H:i'));
		
		$this->meeting_session =  $sRawDate;
		$this->dato = $oDate->format('Y-m-d');

		return $this->meeting_session;
	}
	function getMaximumSeats() {
		return $this->max_seat ;
	}
	function setMaximumSeats($sNewValue) {
		return $this->max_seat = intval($sNewValue);
	}

	function getComments(){
		return $this->comments;
	}
	function setComments($sNewValue){
		return $this->comments = $sNewValue;
	}


}

?>