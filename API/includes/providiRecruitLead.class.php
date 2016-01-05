<?php


class ProvidiRecruitLead extends ProvidiObject{
	function _setBaseTable() {
		$this->_sTableName = 'emner';
		$this->_sTableCharset = 'latin1';
	}
	
	function getName() {
		return $this->navn;
	}
	function setName($sNewValue) {
		return $this->navn = $sNewValue;
	}
	function getTelephone() {
		return $this->telefon;
	}
	function setTelephone($sNewValue) {
		return $this->telefon = $sNewValue;
	}
	function getOrigin() {
		return $this->site;
	}
	function setOrigin($sNewValue) {
		return $this->site = $sNewValue;
	}
	function getAssignedDate() {
		return $this->assigned_on;
	}
	function setAssignedDate($sNewValue) {
		return $this->assigned_on = $sNewValue;
	}
	function getMessage() {
		return $this->besked;
	}
	function setMessage($sNewValue) {
		return $this->besked = $sNewValue;
	}
	function getOwner() {
		if(in_array('medlid' , $this->_aValidFields)) {
			return $this->medlid;
		}
		return $this->providiID;		
	}
	function setOwner($sNewValue) {
		if(in_array('medlid' , $this->_aValidFields)) {
			$this->medlid = $sNewValue;
			return $this->medlid ;
		}
		$this->providiID = $sNewValue;		
		return $this->providiID;
	}



	function getEmail() {
		return $this->email;
	}
	function setEmail($sNewValue) {
		return $this->email = $sNewValue;
	}
	function getAge() {
		return $this->alder;
	}
	function setAge($sNewValue) {
		return $this->alder = $sNewValue;
	}
	function getZipCode() {
		return $this->postnr;
	}
	function setZipCode($sNewValue) {
		return $this->postnr = $sNewValue;
	}
	function getExpectedEarnings() {
		return $this->expected_income;
	}
	function setExpectedEarnings($sNewValue) {
		return $this->expected_income = $sNewValue;
	}



	static function _getEvaluationCode($sText) {
		return ProvidiLead::_getEvaluationText($sText , 'get_code');
	}

	static function _getEvaluationText($sText, $sMode='get_text') {
		$sReturn = '';
		if(empty($sText)) {
			return $sReturn;
		}
		
		$sText = ProvidiTrimSpaces($sText);
		$aReturn = array(
			'not_interested' => 'Ikke interesseret'
			, 'XX_NOT_TAKEN' => 'Ikke truffet'
			, 'no_money' => 'Ingen penge lige nu'
			, 'XX_JOINED' => 'Meldt ind'   // AKA joined????
			, 'XX_DOES_NOT_THAT_HAVE_TURNED' => 'Mener ikke at have henvendt sig'
			, 'never_asked_for_contact' => 'Svaret nej til at ville kontaktes'
			, 'non_existing' => 'Tlf. nr. eller person eksisterer ikke'
		);
		if($sMode == 'raw') {
			return $aReturn;
		}
		if($sMode == 'get_code') {
			$aTemp = array();
			reset($aReturn);
			while(list($sKey,$sValue) = each($aReturn)) {
				if($sText == $sValue) {
					return $sKey;
				}
				$aTemp[  providiTrimSpaces($sValue) ] = $sKey;			
			}		
		} else {
			if(isset($aReturn[ $sText ])) {
				return $aReturn[ $sText ];
			}
		
		}

		return $sReturn;
	}



	static function _extractQCodes($sText , &$expectedLostWeight  , &$sSeriousness ) {
		if(stristr($sText , '_QC1 :')) {
			ProvidiLead::_extractQCformat($sText , $expectedLostWeight  , $sSeriousness );
			// was in predefined format , no custom
			return '';
		}
		return $sText;
	}
	static function tidyUpEmnerRow($oEmneIdealvaegtRow, $bConvertTimezone =true) {
		$oRow = &$oEmneIdealvaegtRow;
		$oReturn = new stdClass();

		$oReturn->id = $oRow->id;
		$oReturn->email = strtolower(providiTrimSpaces( $oRow->email ));
		$oReturn->name = $oRow->navn;
		$oReturn->name = ucwords(strtolower(providiTrimSpaces($oReturn->name)));

		$oReturn->age = $oRow->alder;
		
		$oReturn->expected_earnings = $oRow->expected_income;
		$oReturn->interest_date = $oRow->free_hours;

		$aTemp = array();
		if(!empty($oRow->reasons)) {
			$aTemp[] = $oRow->reasons;
		}
		$oReturn->message = implode("\n" , $aTemp);

		$oReturn->order = null;
		//$oReturn->origin = $oRow-> ????

		$aTemp = array();
		if(!empty($oRow->telefon)) {
			$aTemp[] = providiTrimSpaces($oRow->telefon);
		}
		if(!empty($oRow->telefon2) && $oRow->telefon != $oRow->telefon2 ) {
			$aTemp[] = providiTrimSpaces($oRow->telefon2);
		}

		$oReturn->phone = implode(', ', $aTemp);
		$oReturn->zipcode = $oRow->postnr;

		if($bConvertTimezone) {
			$oReturn->lead_assigned_date = providiResponseDateTime($oRow->assigned_on);
		} else {
			$oReturn->lead_assigned_date = $oRow->assigned_on;
		}

		return $oReturn;	
	}





	function setLeadName($sNewValue) {
		$sNewValue = ucwords(strtolower(ProvidiTrimSpaces($sNewValue)));
		$aTemp = explode(' ',$sNewValue);
		switch(count($aTemp)) {
			case 2		:	// name and last name  , save to specific fields
								if(in_array('medlid' , $this->_aValidFields)) {
									$this->navn = '';
									$this->fornavn = $aTemp[0];
									$this->efternavn = $aTemp[1];
								} else {
									$this->leadName = $sNewValue;
								}
								break;
			default		:	;
								// single , save to 
								if(in_array('medlid' , $this->_aValidFields)) {
									$this->navn = $sNewValue;
									$this->fornavn = '';
									$this->efternavn = '';
								} else {
									$this->leadName = $sNewValue;
								}
								break;
		
		}
	
	}



}

class ProvidiRecruitLeadList extends ProvidiList{

	//function getList($sOwner , $sFromDate=null , $sToDate=null , $aMode=null , $nLimit = 200) {
	function getList($aOptions , $aMode=null , $nLimit = 200) {
		$oDB = $this->_oDB;
		$aWhere = array();
		if(isset($aOptions['userId'])) {
			$aWhere['medlid'] = sprintf('medlid = "%s" ' , $oDB->esc($aOptions['userId']));
		}
		if(!empty($aOptions['from_date'])  && !empty($aOptions['to_date'])) {
			$aOptions['from_date'] = providiDateTime($aOptions['from_date'], 'SQL');
			$aOptions['to_date'] = providiDateTime($aOptions['to_date'], 'SQL');
			$aWhere['between_date'] = sprintf(' assigned_on BETWEEN "%s" AND "%s" ',  $oDB->esc($aOptions['from_date']),  $oDB->esc($aOptions['to_date']));
		} else {
			if(!empty($aOptions['from_date'])) {			
				$aOptions['from_date'] = providiDateTime($aOptions['from_date'], 'SQL');
				$aWhere['from_date'] = sprintf(' assigned_on >= "%s" ' , $oDB->esc($aOptions['from_date']));
			}
			if(!empty($aOptions['to_date'])) {			
				$aOptions['to_date'] = providiDateTime($aOptions['to_date'], 'SQL');
				$aWhere['to_date'] = sprintf(' assigned_on <= "%s" ' , $oDB->esc($aOptions['to_date']));
			}				
		}

		$aMode = null;
		if(!empty($aOptions['mode'])) {
			$aMode = array();
			if(!is_array($aOptions['mode'])) {
				$aMode[] = $aOptions['mode'];
			} else {
				reset($aOptions['mode']);
				while(list($sKey, $sValue) = each($aOptions['mode'])) {
					$aMode[] = $sValue;
				}
			
			}			
		}


		$sQuery = sprintf(' SELECT id FROM emner WHERE %s LIMIT %d'   , implode(' AND ', $aWhere) , $nLimit);
		if(isset($_GET['debug'])) {
			print $sQuery;
		}
		$aList = $oDB->Query($sQuery);
		$aReturn = array();

		for($i=0;$i<count($aList);$i++) {
			$oLead = new providiRecruitLead($oDB , $aList[$i]->id);
			$aReturn[] = $oLead;		
		}
		return $aReturn;	
	}




}


?>