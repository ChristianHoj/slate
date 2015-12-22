<?php // all the scripts should be saved as UTF8 // æ

class providiVSMember extends providiObject {
	//private $_distributor_hash = "Put your secret message here, it\'ll be use as hash parameter";
	protected function _getDistributorSecretHash() {
		return "Put your secret message here, it\'ll be use as hash parameter";
	}
	protected function _getDefaultTimeout() {
		return 10800; // 3 * 60 * 60;
	}
	function _setBaseTable() {
		$this->_sTableName = 'customer_info';
		$this->_sTableCharset = 'latin1';
	}

	function getCustomerID() {
		return $this->customerID;
	}
	function getAddress() {
		return $this->address;
	}
	function setAddress($sNewValue) {
		return $this->address = $sNewValue;
	}
	function getName() {
		return $this->name;
	}
	function setName($sNewValue) {
		return $this->name = $sNewValue;
	}
	function getTelephone() {
		return $this->getPhone();
	}
	function setTelephone($sNewValue) {
		return $this->setPhone($sNewValue);
	}
	function getUsername() {
		return $this->username;
	}
	function setUsername($sNewValue) {
		return $this->username = $sNewValue;
	}
	function getLastTimeVisited() {
		return $this->lastTimeVisited;
	}
	function setLastTimeVisited($sNewValue) {
		return $this->lastTimeVisited = $sNewValue;
	}
	function getSignupDate() {
		return $this->signupDate;
	}
	function setSignupDate($sNewValue) {
		return $this->signupDate = $sNewValue;
	}
	function getPhone() {
		return $this->phone;
	}
	function setPhone($sNewValue) {
		return $this->phone = $sNewValue;
	}
	function getOriginalDistributorID() {
		return $this->originalDistributor;
	}
	function setOriginalDistributorID($sNewValue) {
		return $this->originalDistributor = $sNewValue;
	}
	function getOriginalDistributorName() {
		return $this->originalDistributorName;
	}
	function setOriginalDistributorName($sNewValue) {
		return $this->originalDistributorName = $sNewValue;
	}

	function getCostAnalysisScore() {
		return $this->kostanalyse_score;
	}
	function setCostAnalysisScore($sNewValue) {
		return $this->kostanalyse_score = $sNewValue;
	}

	function getUnpaidMonths() {
		$oDB = $this->_oDB;
		$sQuery = sprintf(' SELECT DATE(rdato) last_due , DATE(rdato) + INTERVAL 90 DAY , amount , rstatus , qptransactid ,TIMESTAMPDIFF( MONTH , max(rdato) , NOW()) + 1 month_owed  FROM abonnementer  WHERE medlid = "%s"  AND fakturanr > 0 AND qptransactid > 0  AND rstatus = 0 AND DATE(rdato)  < CURDATE()  GROUP BY medlid LIMIT 1' , $this->getCustomerID() );
		$oDebtRow = $oDB->getObject($sQuery);
		if(empty($oDebtRow)) {
			return 0;
		}
		if($oDebtRow->month_owed > 3) {
			return 3;
		}
		return $oDebtRow->month_owed;
	}

	function getMealPrice() {
		$oDB = $this->_oDB;
		$sQuery = sprintf(' SELECT Q12 costAnalyseScore FROM vs_kostanalyse WHERE userID = "%s" ' , $this->getCustomerID() );
		$oCA = $oDB->getObject($sQuery);

		if(empty($oCA)) {
			return 0;
		}
		return $oCA->costAnalyseScore;
		
	}
	


	function getVSMemberchecksum($sCustomerID , $sVU=null) {
		return md5(sprintf('%svoressundhed.dk%s' , $sCustomerID , $sVU));
	}
	function getProvidiDistributorChecksum($sReference) {
		$sQuery = sprintf(' SELECT concat(Reference,username,password) result FROM da_reference WHERE Reference = "%s" LIMIT 1',$sReference);
		$sHash = $this->_oDB->getVar($sQuery);
		return md5( sprintf('%s%s', $sHash , $this->_getDistributorSecretHash() ));
	}
	function getKRMemberChecksum($sCustomerID , $sVU=null) {
		//return md5('kalorieregnskab' . $aGET['vu'] . $aGET['customerID'] . '.dk' )	;
		return md5('kalorieregnskab' . $sVU . $sCustomerID . '.dk' )	;
	}




	
	// log in as distributor to edit other member's info
	function getDistributorAuthorizedLink($sProvidiID , $sVU=null , $sRedirect=null) {
		// FORMAT = reference='.$sRefDis .'&amp;hash='.$url_hash.'&amp;redirect='.$url_encode; 
		$sAuthorizeURL = 'http://www.voressundhed.dk/distributor/login.php';
		if(empty($sVU)) {
			$sVU = time() + providiVSMember::_getDefaultTimeout();
		}
		$aGET = array(
			'reference' => $sProvidiID
			,'vu' => $sVU
			, 'hash' => $this->getProvidiDistributorChecksum($sProvidiID)
		);
		if(!empty($sRedirect)) {
			$aGET['redirect'] = urlencode($sRedirect);
		}
		$sURL = sprintf('%s?%s' , $sAuthorizeURL , http_build_query($aGET));		
		return $sURL;
	}

	// login as customer herself
	function getVSMemberAuthorizedLink($sCustomerID , $sVU=null , $sRedirect=null) {
		// FORMAT http://www.voressundhed.dk/distributor/customer_login.php?customerID=221211240099&vu=1445230624&hash=6ffe293c9be441f25a273bc5cbc8c626
		$sAuthorizeURL = 'http://www.voressundhed.dk/distributor/customer_login.php';
		if(empty($sVU)) {
			$sVU = time() + providiVSMember::_getDefaultTimeout();
		}
		$aGET = array(
			'customerID' => $sCustomerID
			, 'vu' => $sVU 
			, 'hash' => $this->getVSMemberchecksum($sCustomerID , $sVU)
		);
		if(!empty($sRedirect)) {
			$aGET['redirect'] = urlencode($sRedirect);
		}

		$sURL = sprintf('%s?%s' , $sAuthorizeURL , http_build_query($aGET));		
		return $sURL;
	}


	function getVSMemberKRAuthorizedLink($sCustomerID , $sVU=null , $sRedirect=null) {
		//    "kalorie_login": "http://www.kalorieregnskab.dk/login.php?customerID=313214470001&vu=1444061739&hash=d966c33806b0f28a7d2bbbe",		

		if(stristr($this->customer_privilege , 'K') === false) {
			return '';
		}
		//$sAuthorizeURL = 'http://www.kalorieregnskab.dk/login.php';
		$sAuthorizeURL = 'http://regnskab.voressundhed.dk/login.php';
		if(empty($sVU)) {
			$sVU = time() + providiVSMember::_getDefaultTimeout();
		}
		$aGET = array(
			'customerID' => $sCustomerID
			, 'vu' => $sVU 
			, 'hash' => $this->getKRMemberChecksum($sCustomerID , $sVU)
		);
		if(!empty($sRedirect)) {
			$aGET['redirect'] = urlencode($sRedirect);
		}

		$sURL = sprintf('%s?%s' , $sAuthorizeURL , http_build_query($aGET));		
		return $sURL;

	}
	


}


class providiVSMemberList extends providiList {	
	function getList($aOptions) {
		$aWhere = array(
			'customerID' => ' customerID != "" ' 
			, 'not_self' => ' customerID NOT LIKE "%0000" '
		);

		if(isset($aOptions['providiID'])) {
			$aWhere['currentDistributor'] = sprintf(' currentDistributor = "%s" ' , $this->_oDB->esc($aOptions['providiID']));
		}
		if(isset($aOptions['non_expire'])) {
			$aWhere['validUntil'] = sprintf(' (validUntil = "0000-00-00" OR DATE(validUntil) >= CURDATE() ) ') ;
		}

		$aSelect = array();
		switch(@$aOptions['mode']) {
			case 'compact'		:	$aSelect = array(
											'id' , 'customerID' , 'name' ,'phone' , 'username' 
										); 
										break;
			default				:	$aSelect[] =  ' customer_info.* ';		
		}
		$sSelect = implode(', ',  $aSelect);


		switch(@$aOptions['order_by']) {
			default	: $sOrderBy =  ' ';		
		}
		$nLimit = 100;
		if(!empty($aOptions['limit'])) {
			$nLimit = $aOptions['limit'];
		}


		$sQuery = sprintf(' SELECT %s FROM customer_info WHERE %s  %s LIMIT %d ', $sSelect , implode(' AND ', $aWhere) , $sorderBy , $nLimit );
		if(isset($_GET['debug'])) {
			print $sQuery . '<hr />';
		}
		return $this->_oDB->query($sQuery);
	
	}

}

?>