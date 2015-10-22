<?php


class providiDistributor extends providiObject {
	function _setBaseTable() {
		$this->_sTableName = 'da_reference';
	}

	function loadFromProvidiID($sProvidiID) {
		global $oDB;
		$sQuery = sprintf(' SELECT * FROM da_reference WHERE Reference = "%s" ' , $oDB->esc($sProvidiID));
		$oDis = $oDB->getObject($sQuery);
		if(empty($oDis)) {
			throw new providiMethodNotAllowedException('Cannot find user with providiID - ' . $sProvidiID , 5010);
		}
		$this->merge($oDis);
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
	function getProfileImageURL() {
		global $aProvidiConfigs;
		$sTheImage = $this->image;
		if(empty($sTheImage)) {
			$sTheImage = 'inge.jpg';
		}
		$sImageURL = sprintf('%s%s' , $aProvidiConfigs['URL_profile_image_path'] , $sTheImage);
		return $sImageURL;	
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


	function getReferenceCode() {
		if(empty($this->_aRef)) {
			$this->_loadFromReference();
		}
		return @$this->_aRef['Reference'];
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
		if(empty($this->aRefPaypal)) {
			$this->_loadFromRefkundePaypal();
		}
		return @$this->_aRefPaypal['paypal_email'];
	}
	function getQuickpayAPIKey() {
		if(empty($this->aRefPBS)) {
			$this->_loadFromRefkundePBS();
		}
		return @$this->_aRefPBS['pbs_md5secret'];
	}
	function getQuickpayMerchantID() {
		if(empty($this->aRefPBS)) {
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
		$this->_aRefPBS = $aRef;
		return $aRef;	
	}






}


?>