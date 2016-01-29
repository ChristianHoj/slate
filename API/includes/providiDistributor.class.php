<?php // all the scripts should be saved as UTF8 // æ


class providiDistributor extends providiObject {
	function _setBaseTable() {
		$this->_sTableName = 'da_reference';
		$this->_bNeedUTF8Conversion = true;
		$this->_sTableCharset = 'latin1';
	}

	function loadFromProvidiID($sProvidiID) {
		global $oDB;
		$this->prepareCharset();
		$sQuery = sprintf(' SELECT * FROM da_reference WHERE Reference = "%s" ' , $oDB->esc($sProvidiID));
		$oDis = $oDB->getObject($sQuery);
		if(empty($oDis)) {
			//throw new providiMethodNotAllowedException('Cannot find user with providiID - ' . $sProvidiID , 5010);
			throw new providiBadRequestException('Cannot find user with providiID - ' . $sProvidiID , 5010);			
		}

		$this->merge($oDis);
		if(!empty($this->_bNeedUTF8Conversion)) {
			$this->toUTF8();		
		}
		return $this;
	}
	function getName() {
		return $this->Navn;
	}
	function setName($sNewValue) {
		$this->Navn = $sNewValue;
		return $this->Navn;
	}
	function getAddress() {
		return $this->adresse;
	}
	function setAddress($sNewValue) {
		$this->adresse = $sNewValue;
		return $this->adresse;
	}
	function getCity() {
		return $this->bynavn;
	}
	function setCity($sNewValue) {
		$this->bynavn = $sNewValue;
		return $this->bynavn;
	}
	function getCountry() {
		return $this->land;
	}
	function setCountry($sNewValue) {
		$this->land = $sNewValue;
		return $this->land;
	}
	function getImage() {
		return  $this->image;
	}
	function setImage($sNewValue) {
		return  $this->image = $sNewValue;
	}
	function getUsername() {
		return $this->username;
	}
	function setUsername($sNewValue) {
		return $this->username = $sNewValue;
	}
	function getPassword() {
		return $this->password;
	}
	function setPassword($sNewValue) {
		return $this->password = $sNewValue;
	}
	function getEmail() {
		return $this->Email;
	}
	function setEmail($sNewValue) {
		return $this->Email = $sNewValue;
	}

	function getProfileImageURL() {
		global $aProvidiConfigs;
		$sTheImage = $this->image;
		if(empty($sTheImage)) {
			$sTheImage = 'inge.jpg';
		}
		$sImageURL = sprintf('%s%s' , $aProvidiConfigs['URL_profile_image_path'] , $sTheImage);
		return $sImageURL;	
	}

	function getSponsorID() {
		return $this->sponsor_id;
	}
	function getSponsorName() {
		return $this->getSponsor();
	}

	function getSponsor() {
		return $this->sponsor;
	}
	function getPartnerName() {
		return $this->partner_navn;
	}
	function getPartnerEmail() {
		return $this->partners_email;
	}
	function getPartnerSkypeID() {
		return $this->partner_skypenavn;
	}
	function getSkypeID() {
		return $this->skypenavn;
	}
	function getPostNr() {
		return $this->postnr;
	}

	function getProvidiID() {
		return $this->Reference;
	}
	function getCompany() {
		return $this->firma;
	}
	function getTelephone() {
		return $this->Telefon;
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




	function getReferenceCode() {
		if(empty($this->_aRef)) {
			$this->_loadFromReference();
		}
		return @$this->_aRef['Reference'];
	}
	function setReferenceCode($sNewValue) {
		if(empty($this->_aRef)) {
			$this->_loadFromReference();
		}
		return @$this->_aRef['Reference'] = $sNewValue;
	}

	function getCustomShippingCost() {
		if(empty($this->_aRef)) {
			$this->_loadFromReference();
		}
		$nCustomShippingCost = @$this->_aRef['custom_shipping_cost'];
		if(empty($nCustomShippingCost) || $nCustomShippingCost <= 0) {
			global $aProvidiConfigs;
			$nCustomShippingCost = $aProvidiConfigs['DISTRIBUTOR_SHOP_default_shipping_cost'];
		
		}
		return $nCustomShippingCost;
	
	}
	function getSelfCustomerAccountName() {
		if(empty($this->_aSelfAccount)) {
			$this->_loadFromSelfCustomerAccount();
		}
		return @$this->_aSelfAccount['name'];
	}
	function getSelfCustomerAccountCustomerID() {
		if(empty($this->_aSelfAccount)) {
			$this->_loadFromSelfCustomerAccount();
		}
		return @$this->_aSelfAccount['customerID'];
	}



	function _loadFromReference($sProvidiID=null) {
		$bNeedUTF8Conversion = true;
		if(empty($sProvidiID)) {
			$sProvidiID = $this->getProvidiID();
		}
		if(empty($sProvidiID)) {
			throw new providiMethodNotAllowedException('Cannot load from empty providiID ' , 771);
		}

		$aWhere = array(
			'hbl_id' => sprintf(' hbl_id = "%s" ' , $this->_oDB->esc($sProvidiID))
		);
		$sQuery = sprintf(' SELECT * FROM refkunde WHERE %s  ' , implode(' AND ' ,$aWhere));
		$aRef = $this->_oDB->getRow($sQuery);
		
		if($bNeedUTF8Conversion) {
			$this->toUTF8($aRef);  
 		}
		$this->_aRef = $aRef;
		return $aRef;	
	}


	function _loadFromSelfCustomerAccount($sCustomerID = null) {
		
		if(empty($sCustomerID)) {
			$sCustomerID = $this->getProvidiID() . '0000';		
		}
		if(empty($sCustomerID)) {
			throw new providiMethodNotAllowedException('Cannot load from empty customerID ' , 772);
		}

		$sQuery = sprintf(' SELECT * FROM customer_info WHERE customerID = "%s" ' , $sCustomerID);
		$this->_aSelfAccount = $this->_oDB->getRow($sQuery);
		$bNeedUTF8Conversion = true;
		if($bNeedUTF8Conversion) {
			$this->toUTF8($this->_aSelfAccount);  
 		}
		return $this->_aSelfAccount;	
	}

	function getAccountType() {
		return $this->accountType;
	}


	private function checksumVoressundhedV2($sCustomerID , $sVU=null) {
		return md5(sprintf('%svoressundhed.dk%s' , $sCustomerID , $sVU));
	}

	function getVSSelfAccountLink($sCustomerID=null , $sVU=null) {

		if(empty($sCustomerID)) {
			$sCustomerID  = $this->getSelfCustomerAccountCustomerID();
		}

		if(empty($sCustomerID)) {
			throw new providiUnauthorizeException('Self account not found, please contact admin', 7011);
		}

		// the very same to center.providi.php VoressundhedSelfLink
		if(is_null($sVU)) {
			$sVU = mktime() + ( 60 * 60 * 2); // 2 hours
		}
		$aLinks = array(
			sprintf('customerID=%s' ,  $this->_oDB->esc($sCustomerID))
			, sprintf('vu=%s' , $this->_oDB->esc($sVU))
		);
		$aLinks[] = sprintf('hash=%s' , $this->checksumVoressundhedV2($sCustomerID , $sVU));
		global $aProvidiConfigs;
		$sSite = $aProvidiConfigs['VS_SELF_ACCOUNT_authenticate_url'];
		return sprintf('%s?%s' , $sSite , implode('&',$aLinks));

	}

	function getPaypalEmail() {
		if(empty($this->_aRefPaypal)) {
			$this->_loadFromRefkundePaypal();
		}
		return @$this->_aRefPaypal['paypal_email'];
	}
	function getQuickpayAPIKey() {
		if(empty($this->_aRefPBS)) {
			$this->_loadFromRefkundePBS();
		}
		return @$this->_aRefPBS['pbs_md5secret'];
	}
	function getQuickpayMerchantID() {
		if(empty($this->_aRefPBS)) {
			$this->_loadFromRefkundePBS();
		}
		return @$this->_aRefPBS['pbs_merchantid'];
	}
		
	



	function _loadFromRefkundePaypal($sProvidiID=null) {
		if(empty($sProvidiID)) {
			$sProvidiID = $this->getProvidiID();
		}
		if(empty($sProvidiID)) {
			throw new providiMethodNotAllowedException('Cannot load from empty providiID ' , 772);
		}

		$aWhere = array(
			'hbl_id' => sprintf(' hbl_id = "%s" ' , $this->_oDB->esc($sProvidiID))
			, 'status' => ' status = 1 '
		);
		$sQuery = sprintf(' SELECT * FROM refkunde_paypal WHERE %s  ' , implode(' AND ' ,$aWhere));
		$aRef = $this->_oDB->getRow($sQuery);
		$bNeedUTF8Conversion = true;
		if($bNeedUTF8Conversion) {
			$this->toUTF8($this->_aRef);  
 		}
		
		$this->_aRefPaypal = $aRef;

		return $aRef;	
	}
	function _loadFromRefkundePBS($sProvidiID=null) {
		if(empty($sProvidiID)) {
			$sProvidiID = $this->getProvidiID();
		}
		if(empty($sProvidiID)) {
			throw new providiMethodNotAllowedException('Cannot load from empty providiID ' , 773);
		}

		$aWhere = array(
			'hbl_id' => sprintf(' hbl_id = "%s" ' , $this->_oDB->esc($sProvidiID))
			, 'status' => ' status = 1 '
		);
		$sQuery = sprintf(' SELECT * FROM refkunde_pbs WHERE %s  ' , implode(' AND ' ,$aWhere));
		$aRef = $this->_oDB->getRow($sQuery);
		$bNeedUTF8Conversion = true;
		if($bNeedUTF8Conversion) {
			$this->toUTF8($this->_aSelfAccount);  
 		}

		$this->_aRefPBS = $aRef;
		return $aRef;	
	}




	// 2015-11-18
	function setAccountType($sType) {
		return $this->accountType = $sType;
	}
	function setCompanyName($sCompanyName) {
		return $this->firma = $sCompanyName;
	}
	function setPostNr($sNewValue) {
		return $this->postnr = $sNewValue;
	}
	function setPartnerName($sNewValue) {
		return $this->partner_navn = $sNewValue;
	}
	function setPartnerEmail($sNewValue) {
		return $this->partners_email = $sNewValue;
	}
	function setPartnerSkypeID($sNewValue) {
		return $this->partner_skypenavn = $sNewValue;
	}
	function setSkypeID($sNewValue) {
		return $this->skypenavn = $sNewValue;
	}
	function setTelephone($sNewValue) {
		return $this->Telefon = $sNewValue;
	}
	function setSponsor($sNewValue) {
		return $this->sponsor = $sNewValue;
	}
	function setSelfCustomerAccountName($sNewValue) {
		if(empty($this->_aSelfAccount)) {
			$this->_loadFromSelfCustomerAccount();
		}
		if(empty($this->_aSelfAccount)) {
			throw new providiBadRequestException('Cannot locate VS member info for the distributor' , 705);
		}
		return @$this->_aSelfAccount['name'] = $sNewValue;
	}
	function setPaypalEmail($sNewValue ,$bNecessary=false) {
		if(empty($this->_aRefPaypal)) {
			$this->_loadFromRefkundePaypal();
		}
		if(empty($this->_aRefPaypal)) {
			if($bNecessary) {
				throw new providiBadRequestException('Cannot locate Paypal info for the distributor' , 703);
			} else {
				return false;
			}
		}
		return @$this->_aRefPaypal['paypal_email'] = $sNewValue;
	}
	function setCustomShippingCost($sNewValue,$bNecessary=false) {
		if(empty($this->_aRef)) {
			$this->_loadFromReference();
		}
		if(empty($this->_aRef)) {
			if($bNecessary) {
				throw new providiBadRequestException('Cannot locate shop info for the distributor' , 704);
			} else {
				return false;
			}
		}
		return $this->_aRef['custom_shipping_cost'] = $sNewValue;
	}

	function setQuickpayAPIKey($sNewValue,$bNecessary=false) {
		if(empty($this->_aRefPBS)) {
			$this->_loadFromRefkundePBS();
		}
		if(empty($this->_aRefPBS)) {
			if($bNecessary) {
				throw new providiBadRequestException('Cannot locate PBS info for the distributor' , 701);
			} else {
				return false;
			}
		}
		$this->_aRefPBS['pbs_md5secret'] = $sNewValue;		
		return @$this->_aRefPBS['pbs_md5secret'];
	}

	function setQuickpayMerchantID($sNewValue,$bNecessary=false) {
		if(empty($this->_aRefPBS)) {
			$this->_loadFromRefkundePBS();
		}
		if(empty($this->_aRefPBS)) {
			if($bNecessary) {
				throw new providiBadRequestException('Cannot locate PBS info for the distributor' , 702);
			} else {
				return false;
			}
		}
		return $this->_aRefPBS['pbs_merchantid'] = $sNewValue;
	}


	function save() {
		
		$this->_saveDaReference();
		$this->_saveRefkunde();
		$this->_saveRefPBS();
		$this->_saveRefPaypal();
		
	}

	function _saveRefkunde() {
		if(empty($this->_aRef)) {
			return false;
		}
		$aUpdates = array();
		$bNeedISOConversion = true;
		if($bNeedISOConversion) {
			$this->toISO($this->_aRef);
		}

		reset($this->_aRef);
		while(list($sKey,$sValue) = each($this->_aRef)) {
			if($sKey == 'hbl_id') {
				continue;
			}
			$sValue = providiCheckEmptyString($sValue);
			$aUpdates[ $sKey ] = sprintf(' `%s` = "%s" ' , $sKey , $this->_oDB->esc($sValue));
		}
		if(count($aUpdates) > 0) {
			$sQuery = sprintf(' UPDATE `refkunde` SET %s WHERE `hbl_id` = "%s" LIMIT 1' , implode(', ', $aUpdates) , $this->_oDB->esc($this->Reference));
			$this->_oDB->query($sQuery);
			return $this->_oDB->affectedRows() > 0 ;
		}
		return false;
	
	}

	function _saveRefPBS() {
		// not set, nothing to do
		if(empty($this->_aRefPBS)) {
			return false;
		}
		$aUpdates = array();
		$bNeedISOConversion = true;
		if($bNeedISOConversion) {
			$this->toISO($this->_aRefPBS);
		}

		reset($this->_aRefPBS);
		while(list($sKey,$sValue) = each($this->_aRefPBS)) {
			if($sKey == 'hbl_id') {
				continue;
			}
			$sValue = providiCheckEmptyString($sValue);
			$aUpdates[ $sKey ] = sprintf(' `%s` = "%s" ' , $sKey , $this->_oDB->esc($sValue));
		}
		
		if(count($aUpdates) > 0) {
			$aUpdates['updated_on'] = sprintf(' `updated_on` = NOW() ');
			$sQuery = sprintf(' UPDATE `refkunde_pbs` SET %s WHERE `hbl_id` = "%s" LIMIT 1' , implode(', ', $aUpdates) , $this->_oDB->esc($this->Reference));
			$this->_oDB->query($sQuery);
			return $this->_oDB->affectedRows() > 0 ;
		}
		return false;	
	}
	function _saveRefPaypal() {
		// not set, nothing to do
		if(empty($this->_aRefPaypal)) {
			return false;
		}
		$aUpdates = array();
		$bNeedISOConversion = true;
		if($bNeedISOConversion) {
			$this->toISO($this->_aRefPaypal);
		}

		reset($this->_aRefPaypal);
		while(list($sKey,$sValue) = each($this->_aRefPaypal)) {
			if($sKey == 'hbl_id') {
				continue;
			}
			$sValue = providiCheckEmptyString($sValue);
			$aUpdates[ $sKey ] = sprintf(' `%s` = "%s" ' , $sKey , $this->_oDB->esc($sValue));
		}
		
		if(count($aUpdates) > 0) {
			$aUpdates['updated_on'] = sprintf(' `updated_on` = NOW() ');
			$sQuery = sprintf(' UPDATE `refkunde_paypal` SET %s WHERE `hbl_id` = "%s" LIMIT 1' , implode(', ', $aUpdates) , $this->_oDB->esc($this->Reference));
			$this->_oDB->query($sQuery);
			return $this->_oDB->affectedRows() > 0 ;
		}
		return false;	
	}

	function _saveDaReference() {

		if(empty($this->Reference)) {
			throw new providiBadRequestException('Cannot save empty reference ' , 710);
		}
		$aUpdates = array();
		$bNeedISOConversion = true;
		if($bNeedISOConversion) {
			$this->toISO();
		}
		reset($this);
		while(list($sKey,$sValue) = each($this)) {
			if($sKey == 'Reference') {
				continue;
			}
			if(in_array($sKey, $this->_aValidFields)) {
				$sValue = providiCheckEmptyString($sValue);
				$aUpdates[ $sKey ]  = sprintf(' `%s` = "%s" ' , $sKey , $this->_oDB->esc( $sValue ));
			}		
		}
		if(count($aUpdates) > 0 ) {
			$sQuery = sprintf(' UPDATE da_reference SET %s WHERE Reference = "%s" LIMIT 1 ', implode(', ', $aUpdates)  , $this->_oDB->esc($this->Reference));
			$this->_oDB->query($sQuery);

			return $this->_oDB->affectedRows() > 0;		
		}
		return false;	
	}

	function hasWebPackage() {
		return !empty($this->image);
	}

	// 2016-01-07
	function isActive() {
		if(empty($this->username) || empty($this->password)) {
			return false;
		}
		return true;
	
	}


}


?>