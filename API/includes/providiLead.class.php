<?php


class ProvidiLead extends ProvidiObject{
	function _setBaseTable() {
		$this->_sTableName = 'emner_idealvaegt';
		$this->_bNeedUTF8Conversion = true;
		$this->_sTableCharset = 'latin1';

	}

	function getMessage() {
		return $this->hvorfor;
	}
	function setMessage($sNewValue) {
		return $this->hvorfor = $sNewValue;
	}
	function getZipCode() {
		return $this->postnr;
	}
	function setZipCode($sNewValue) {
		return $this->postnr = $sNewValue;
	}

	static function _getEvaluationCode($sText) {
		return ProvidiLead::_getEvaluationText($sText , 'get_code');
	}

	static function _getEvaluationText($sText, $sMode='get_text') {
		$sReturn = '';
		if(empty($sText)) {
			return 'not_contacted';
		}
		
		$sText = ProvidiTrimSpaces($sText);
		$aReturn = array(
			'not_interested' => 'Ikke interesseret'
			, 'not_available' => 'Ikke truffet'
			, 'no_money' => 'Ingen penge lige nu'
			, 'signed_up' => 'Meldt ind'   // AKA joined????
			, 'never_asked_for_contact' => 'Mener ikke at have henvendt sig'
			, 'no' => 'Svaret nej til at ville kontaktes'
			, 'non_existing' => 'Tlf. nr. eller person eksisterer ikke'
			, 'no_show' => 'Ikke truffet til den aftalte tid'
			// added on 2015-11-18
			, 'not_contacted' => 'not_contacted'
		);
		if($sMode == 'raw') {
			return $aReturn;
		}
		if($sMode == 'get_code') {
			// 2015-11-13 maybe the sText was already in proper format, just return it! 
			if(isset($aReturn[ $sText ])) {
				return $sText;
			}

			$aTemp = array();
			reset($aReturn);
			while(list($sKey,$sValue) = each($aReturn)) {
				if($sText == $sValue) {
					return $sKey;
				}
				/// $aTemp[  providiTrimSpaces($sValue) ] = $sKey;			
			}	

		} else {
			if(isset($aReturn[ $sText ])) {
				return $aReturn[ $sText ];
			}
		
		}

		return $sReturn;
	}

	static function _extractQCformat($sText , &$expectedLostWeight  , &$sSeriousness ) {
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
	function getLeadEvaluation() {		
		return $this->_getEvaluationCode($this->lead_evaluation);
	}
	function setLeadEvaluation($sNewValue) {
		$sNewValue = $this->_getEvaluationText($sNewValue, 'get_code');
		return $this->lead_evaluation = $sNewValue;
	}
	function getName() {
		$sName = '';
		$sName = $this->navn;
		if(empty($sName)) {
			$aTemp = array();

			if(!empty($this->fornavn)) {
				$aTemp[] = $this->fornavn;
			}
			if(!empty($this->efternavn)) {
				$aTemp[] = $this->efternavn;
			}

			if(count($aTemp) > 0 ) {
				$sName = implode(' ', $aTemp);
			}

		}
		return ucwords(strtolower($sName));
	}
	function setName($sNewValue) {
		$this->navn = $sNewValue;
		$this->fornavn = '';
		$this->efternavn = '';
		return $this->navn;
	}		

	function getTelephone() {
		$sPhone = $this->telefon;
		if(empty($sPhone)) {
			$sPhone = $this->mobil;
		}
		return $sPhone;
	}
	function setTelephone($sNewValue) {
		$this->telefon = $sNewValue;
		$sMob = $this->mobil;
		if($sMob == $sNewValue) {
			$this->mobil = '';
		}
		return $this->telefon;
	}
	function getEmail() {
		return $this->email;
	}
	function setEmail($sNewValue) {
		return $this->email = $sNewValue;
	}
	function getAssignedDate() {
		return $this->assigned_on;
	}
	function setAssignedDate($sNewValue) {
		return $this->assigned_on = $sNewValue;
	}
	function setLeadType($sNewValue) {
		if($sNewValue == 'bonusemne') {
			$sNewValue = 'bonus';
		}
		return $this->wheel = $sNewVaue;
	}
	function getLeadType() {
		if($this->wheel == 'bonusemne') {
			return 'bonus';
		} 
		if($this->wheel == '') {
			return '';
		} 	
		return 'own';
	}

}

class ProvidiLeadList extends ProvidiList{

	function getList($aOptions , $nLimit = 200) {

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

		$sOffset = 0;
		if(!empty($aOptions['offset'])) {
			$sOffset = $aOptions['offset'];
		}
		if(!empty($aOptions['limit'])) {
			$nLimit = $aOptions['limit'];
		}

		$sQuery = sprintf(' SELECT id FROM emner_idealvaegt WHERE %s LIMIT %s , %d'   , implode(' AND ', $aWhere) , $oDB->esc($sOffset) , $oDB->esc($nLimit));
		if(isset($_GET['debug'])) {
			print $sQuery;
		}
		$aTemp = $oDB->Query($sQuery);
		$aList = array();

		for($i=0;$i<count($aTemp);$i++) {
			$oLead = new ProvidiLead($oDB);
			$oLead->load($aTemp[ $i ]->id);
			$aList[ $i] = $oLead;
		}
		return $aList;	
	}


}


?>