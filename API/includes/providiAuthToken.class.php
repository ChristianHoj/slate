<?php // all the scripts should be saved as UTF8 // æ


define('PROVIDI_ALL_AUTH_TOKEN_LENGTH', 16);
define('PROVIDI_ALL_AUTH_TOKEN_AGE', '+3 HOUR');
define('PROVIDI_ALL_AUTH_TOKEN_EXTEND_EXPIRE', true);



class ProvidiAuthToken extends ProvidiObject {
	function _setBaseTable() {
		$this->_sTableName = 'providi_auth_tokens';
		$this->_bNeedUTF8Conversion = false;
		$this->_sTableCharset = 'UTF8';
	}

	function load($nID) {
		throw new Exception('load() was Disabled in ' . __CLASS__ , 501);	
	}
	function getProvidiID() {
		return @$this->providiID;
	}
	function setProvidiID($sNewValue) {
		return $this->providiID = $sNewValue;
	}

	function getAuthToken() {
		return $this->authToken;
	}
	function setAuthToken($sNewValue) {
		return $this->authToken = $sNewValue;
	}
	function getUsername() {
		return $this->username;
	}
	function setUsername($sNewValue) {
		return $this->username = $sNewValue;
	}
	function getFullName() {
		return $this->fullName;
	}
	function setFullName($sNewValue) {
		return $this->fullName = $sNewValue;
	}
	function getPartnerName() {
		return $this->partnerName;
	}
	function setPartnerName($sNewValue) {
		return $this->partnerName = $sNewValue;
	}
	function getEmail() {
		return $this->email;
	}
	function setEmail($sNewValue) {
		return $this->email = $sNewValue;
	}
	function getLeadershipRank() {
		return $this->leadershipRank;
	}
	function setLeadershipRank($sNewValue) {
		return $this->leadershipRank = $sNewValue;
	}
	function getEarningRank() {
		return $this->earningRank;
	}
	function setEarningRank($sNewValue) {
		return $this->earningRank = $sNewValue;
	}
	function getRegionCode() {
		return $this->regionCode;
	}
	function setRegionCode($sNewValue) {
		return $this->regionCode = $sNewValue;
	}
	function getRegionTimezone() {
		return $this->regionTimezone;
	}
	function setRegionTimezone($sNewValue) {
		return $this->regionTimezone = $sNewValue;
	}
	function getRegionTimezoneCode() {
		return $this->regionTimezoneCode;
	}
	function setRegionTimezoneCode($sNewValue) {
		return $this->regionTimezoneCode = $sNewValue;
	}

	function getSponsorID() {
		return $this->sponsorID;
	}
	function setSponsorID($sNewValue) {
		return $this->sponsorID = $sNewValue;
	}
	function getSponsorName() {
		return $this->sponsorName;
	}
	function setSponsorName($sNewValue) {
		return $this->sponsorName  = $sNewValue;
	}
	function getAccountType() {
		return $this->accountType;
	}
	function setAccountType($sNewValue) {
		return $this->accountType = $sNewValue;
	}
	function getProfileImage() {
		return $this->profileImage;
	}
	function setProfileImage($sNewValue) {
		return $this->profileImage = $sNewValue;
	}
	function getCreatedOn() {
		return $this->createdOn;
	}
	function setCreatedOn($sNewValue) {
		return $this->createdOn = $sNewValue;
	}
	function getExpiredOn() {
		return $this->expiredOn;
	}
	function setExpiredOn($sNewValue) {
		return $this->expiredOn = $sNewValue;
	}
	function getRemoteIP() {
		return $this->remoteIP;
	}
	function setRemoteIP($sNewValue) {
		return $this->remoteIP = $sNewValue;
	}

}


class ProvidiAuthentication {
	static protected function _checkDebt($sRef) {
	
	}

	static function Login($oTheDB , $sUsername , $sPassword ,$sRegionCode, $bCheckDebt=false) {
		$oDB = $oTheDB;
		$oRegion = providiRegion($sRegionCode);

		$bCleanupExpire = true;
		if($bCleanupExpire) {
			$sQuery = ' DELETE FROM providi_auth_tokens WHERE expiredOn < NOW() ';
			$oDB->query($sQuery);
		}


		$sQuery = sprintf(' SELECT Reference FROM da_reference WHERE username = "%s" AND password = "%s" ' ,  $oDB->esc($sUsername) , $oDB->esc($sPassword));
		$aDA = $oDB->getRow($sQuery);
		if(count($aDA) == 0) {
			return false;
		}
		$oDis = new providiDistributor($oDB);
		$oDis->loadFromProvidiID($aDA['Reference']);
		

		if($bCheckDebt) {
			if(!ProvidiAuthentication::_checkDebt($oDis->getProvidiID())) {
				throw new Exception('Debt alert' , 1);
			}
		}



		$sToken = ProvidiAuthentication::_generateAuthToken();
		global $aProvidiConfigs;


		$sImage = providiGetDistributorImageURL($oDis->getImage());
		

		$aInsert = array(
			'authToken' => $sToken
			, 'providiID' => $oDis->getProvidiID()

			, 'username' => $oDis->getUsername()
			, 'fullName' => $oDis->getName()
			, 'partnerName' => $oDis->getPartnerName()
			, 'email' => $oDis->getEmail() 
			, 'profileImage' => $oDis->getImage()
			, 'accountType' => 'distributor'

			, 'leadershipRank' => $oDis->getLeadershipRank() 
			, 'earningRank' => $oDis->getEarningRank()
			, 'sponsorID' => $oDis->getSponsorID()
			, 'sponsorName' => $oDis->getSponsorName()
			, 'regionCode' => 'DK'
			, 'regionTimezone' =>'+01:00'
			, 'regionTimezoneCode' => 'Europe/Copenhagen'

			, 'downlines' => ''
			, 'timesUsed' => 0 
			, 'createdOn' => date('Y-m-d H:i:s')
			, 'expiredOn' => date('Y-m-d H:i:s', strtotime(constant('PROVIDI_ALL_AUTH_TOKEN_AGE')))
			, 'remoteIP' => $_SERVER['REMOTE_ADDR']
		);

		$oToken = new providiAuthToken($oDB);
		$oToken->merge($aInsert);
		$oToken->save();
		
		return ProvidiAuthentication::loadFromAuthToken($oDB , $sToken);
		
	}
	static protected function _generateAuthToken() {
		return bin2hex(openssl_random_pseudo_bytes(constant('PROVIDI_ALL_AUTH_TOKEN_LENGTH')));
	}


	static function loadFromAuthToken($oDB , $sAuthToken) {
		$aWhere = array(
			'authToken' => sprintf(' authToken = "%s" ', $oDB->esc($sAuthToken) )
			, 'isAdminToken' => 'isAdminToken = "" ' 
		);
		$sQuery = sprintf(' SELECT * FROM providi_auth_tokens WHERE %s ' , implode(' AND ',$aWhere));
		$oToken = $oDB->getObject($sQuery , 'providiAuthToken' , array($oDB));
				

		if(empty($oToken)) {
			throw new Exception('Invalid username or password' , 101);
		}

		$sExpire = strtotime($oToken->expiredOn);
		if(time() > $sExpire) {
			throw new Exception('Auth Token was expired' , 102);
		}

		if(constant('PROVIDI_ALL_AUTH_TOKEN_EXTEND_EXPIRE')) {
			$oToken->expiredOn = date('Y-m-d H:i:s', strtotime(constant('PROVIDI_ALL_AUTH_TOKEN_AGE') ));
		}
		$oToken->timesUsed++;
		$oToken->save();
		return $oToken;
	
	}

}


class ProvidiAdminAuthentication extends ProvidiAuthentication {
	static function Login($oTheDB , $sUsername , $sPassword ,$sRegionCode, $bCheckDebt=false) {
		$oDB = $oTheDB;
		$oRegion = providiRegion($sRegionCode);

		$sFixedUsername = 'providiAdmin';
		$sFixedPassword = 'Pr@v!d!nimda75';
		if($sUsername != $sFixedUsername && $sPassword != $sFixedPassword) {
			return false;
		}

		$sToken = 'ADM' . ProvidiAuthentication::_generateAuthToken();
		global $aProvidiConfigs;


		$aInsert = array(
			'authToken' => $sToken
			, 'providiID' => 'admin'

			, 'username' => 'admin'
			, 'fullName' => 'providi admin'
			, 'email' => 'admin@providi.eu'
			, 'accountType' => 'admin'
			, 'sponsorID' => '22121124'
			, 'sponsorName' => 'Allan Sarfelt'

			/*, 'language' => 'danish'
			, 'region' => 'danish'
			*/
			, 'regionCode' => 'DK'
			, 'regionTimezone' =>'+01:00'
			, 'regionTimezoneCode' => 'Europe/Copenhagen'

			, 'downlines' => ''
			, 'timesUsed' => 0 
			, 'createdOn' => date('Y-m-d H:i:s')
			, 'expiredOn' => '2029-12-31'
			, 'remoteIP' => $oDB->esc($_SERVER['REMOTE_ADDR'])
			, 'isAdminToken' => 1 
		);

		$oToken = new providiAuthToken($oDB);
		$oToken->merge($aInsert);
		$oToken->save();
		
		return ProvidiAdminAuthentication::loadFromAuthToken($oDB , $sToken);
		
	}
	static function loadFromAuthToken($oDB , $sAuthToken) {
		$aWhere = array(
			'authToken' => sprintf(' authToken = "%s" ', $oDB->esc($sAuthToken) )
			, 'isAdminToken' => 'isAdminToken = "1" ' 
		);
		$sQuery = sprintf(' SELECT * FROM providi_auth_tokens WHERE %s ' , implode(' AND ',$aWhere));
		$oToken = $oDB->getObject($sQuery , 'providiAuthToken' , array($oDB));
				

		if(empty($oToken)) {
			throw new Exception('Invalid username or password' , 101);
		}

		$oToken->timesUsed++;
		$oToken->save();
		return $oToken;
	
	}


}
?>