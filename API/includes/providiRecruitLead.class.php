<?php


class ProvidiRecruitLead extends ProvidiObject{
	function _setBaseTable() {
		$this->_sTableName = 'emner';
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

	static function NO_extractQCformat($sText , &$expectedLostWeight  , &$sSeriousness ) {
		$aTexts = explode("\n" , $sText);
		for($i=0;$i<count($aTexts);$i++) {
			$sThisLine = $aTexts[$i];
			if(stristr($sThisLine , '_QC1' ) !== false ) {

				if( stristr($sThisLine , 'ja') !== false) {
					$sSeriousness = 'yes';
				}
				if( stristr($sThisLine , 'nej') !== false) {
					$sSeriousness = 'no';
				}			
			}
			if(stristr($sThisLine , '_QC2' ) !== false ) {
				$expectedLostWeight = providiTrimSpaces( str_replace(' _QC2 : ', '', $sThisLine));
			}		
		}
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



	function getOwner() {
		if(in_array('medlid' , $this->_aValidFields)) {
			return $this->medlid;
		}
		return $this->providiID;		
	}
	function setOwner($sNewValue) {
		if(in_array('medlid' , $this->_aValidFields)) {
			$this->medlid = $sNewValue;
		}
		$this->providiID = $sNewValue;		
		return true;
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

class ProvidiRecruitLeadList {

	function getList($sOwner , $sFromDate=null , $sToDate=null , $aMode=null , $nLimit = 200) {
		global $oDB;
		$aWhere = array(
			'medlid' => sprintf('medlid = "%s" ' , $oDB->esc($sOwner))
		);

		if(!empty($sFromDate) && !empty($sToDate)) {		
			/*$sFromDate = providiDateTime($sFromDate , 'SQL');
			$sToDate = providiDateTime($sToDate , 'SQL');
			*/

			$aWhere['between_date'] = sprintf(' assigned_on BETWEEN "%s" AND "%s" ',  $oDB->esc($sFromDate),  $oDB->esc($sToDate));
		} else {
			if(!empty($sFromDate)) {			
				$aWhere['from_date'] = sprintf(' assigned_on >= "%s" ' , $oDB->esc($sFromDate));
			}
			if(!empty($sToDate)) {			
				$aWhere['to_date'] = sprintf(' assigned_on <= "%s" ' , $oDB->esc($sToDate));
			}		
		}

		/*

		if(!empty($aMode)) {
			if(!is_array($aMode)) {
				$aMode = array($aMode);
			}
			$aAllowedType = array('bonus' , 'own');
			$aTemp = array();

			reset($aMode);
			while(list($sUnusedKey , $sMode) = each($aMode)) {
				if(in_array($sMode, $aAllowedType)) {				
					if($sMode == 'bonus') {
						$aTemp[] = ' wheel = "bonusemne" ' ;
					}
					if($sMode == 'own') {
						$aTemp[] = ' wheel != "bonusemne" ' ;
					}
				}
			}

			if(count($aTemp) > 0) {
				$aWhere['mode'] = sprintf(' ( %s ) ' , implode(' OR ' , $aTemp));
			}
		
		}
		*/



		$sQuery = sprintf(' SELECT * FROM emner WHERE %s LIMIT %d'   , implode(' AND ', $aWhere) , $nLimit);
		if(isset($_GET['debug'])) {
			print $sQuery;
		}
		$aList = $oDB->Query($sQuery);

		for($i=0;$i<count($aList);$i++) {
			$aList[$i] = ProvidiRecruitLead::tidyUpEmnerRow($aList[$i]);
		
		}
		return $aList;	
	}




}


?>