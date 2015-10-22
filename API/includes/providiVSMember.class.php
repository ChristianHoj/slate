<?php

class providiVSMember extends providiObject {
	private $_distributor_hash = "Put your secret message here, it\'ll be use as hash parameter";
	function _getDefaultTimeout() {
		return 10800; // 3 * 60 * 60;
	}
	function _setBaseTable() {
		$this->_sTableName = 'customer_info';
	}


	function getVSMemberchecksum($sCustomerID , $sVU=null) {
		return md5(sprintf('%svoressundhed.dk%s' , $sCustomerID , $sVU));
	}
	function getProvidiDistributorChecksum($sReference) {
		$sQuery = sprintf(' SELECT concat(Reference,username,password) result FROM da_reference WHERE Reference = "%s" LIMIT 1',$sReference);
		$sHash = $this->_oDB->getVar($sQuery);
		return md5( sprintf('%s%s', $sHash , $this->_distributor_hash));
	}
	function getKRMemberChecksum($sCustomerID , $sVU=null) {
		return md5('kalorieregnskab' . $aGET['vu'] . $aGET['customerID'] . '.dk' )	;
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
		$sAuthorizeURL = 'http://www.kalorieregnskab.dk/login.php';
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