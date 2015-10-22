<?php


define('PROVIDI_ALL_AUTH_TOKEN_LENGTH', 16);
define('PROVIDI_ALL_AUTH_TOKEN_AGE', '+3 HOUR');
define('PROVIDI_ALL_AUTH_TOKEN_EXTEND_EXPIRE', true);



class ProvidiAuthToken extends ProvidiObject {
	function _setBaseTable() {
		$this->_sTableName = 'providi_auth_tokens';
	}

	function load($nID) {
		throw new Exception('load() was Disabled in ' . __CLASS__ , 501);	
	}
}


class ProvidiAuthentication {
	static private function _checkDebt($sRef) {
	
	}


	static function Login($sUsername , $sPassword ,$sRegionCode, $bCheckDebt=false) {
		global $oDB;

		$oRegion = providiRegion($sRegionCode);


		$bCleanupExpire = true;
		if($bCleanupExpire) {
			$sQuery = ' DELETE FROM providi_auth_tokens WHERE expiredOn < NOW() ';
			$oDB->query($sQuery);
		}


		$sQuery = sprintf(' SELECT * FROM da_reference WHERE username = "%s" AND password = "%s" ' ,  $oDB->esc($sUsername) , $oDB->esc($sPassword));
		$aDA = $oDB->getRow($sQuery);
		if(count($aDA) == 0) {
			return false;
		}

		if($bCheckDebt) {
			if(!ProvidiAuthentication::_checkDebt($aDA['Reference'])) {
				throw new Exception('Debt alert' , 1);
			}
		}



		$sToken = ProvidiAuthentication::_generateAuthToken();
		global $aProvidiConfigs;


		$sImage = providiGetDistributorImageURL($aDA['image']);
		

		$aInsert = array(
			'authToken' => $oDB->esc($sToken)
			, 'providiID' => $oDB->esc($aDA['Reference'])

			, 'username' => $oDB->esc($aDA['username'])
			, 'fullName' => $oDB->esc(ucwords(strtolower($aDA['Navn'])))
			, 'partnerName' => $oDB->esc(ucwords(strtolower($aDA['partner_navn'])))
			, 'email' => $oDB->esc(strtolower($aDA['Email']))
			, 'profileImage' => $oDB->esc($sImage)
			, 'accountType' => $oDB->esc('distributor')

			, 'leadershipRank' => $oDB->esc($aDA['leadershipRank'])
			, 'earningRank' => $oDB->esc($aDA['earningRank'])
			, 'sponsorID' => $oDB->esc($aDA['sponsor_id'])
			, 'sponsorName' => $oDB->esc(ucwords(strtolower($aDA['sponsor'])))

			/*, 'language' => 'danish'
			, 'region' => 'danish'
			*/
			, 'regionCode' => 'DK'
			, 'regionTimezone' =>'+01:00'
			, 'regionTimezoneCode' => 'Europe/Copenhagen'

			, 'downlines' => ''
			, 'timesUsed' => 0 
			, 'createdOn' => date('Y-m-d H:i:s')
			, 'expiredOn' => date('Y-m-d H:i:s', strtotime(constant('PROVIDI_ALL_AUTH_TOKEN_AGE')))
			, 'remoteIP' => $oDB->esc($_SERVER['REMOTE_ADDR'])
		);

		$sQuery = sprintf(' INSERT INTO providi_auth_tokens(%s) VALUES("%s") ' , implode(', ', array_keys($aInsert)) , implode('", "', $aInsert));
		$oDB->query($sQuery);
		
		return ProvidiAuthentication::loadFromAuthToken($oDB , $sToken);
		
	}
	static private function _generateAuthToken() {
		return bin2hex(openssl_random_pseudo_bytes(constant('PROVIDI_ALL_AUTH_TOKEN_LENGTH')));
	}


	static function loadFromAuthToken($oDB , $sAuthToken) {

		$aWhere = array(
			'authToken' => sprintf(' authToken = "%s" ', $oDB->esc($sAuthToken) )
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
?>