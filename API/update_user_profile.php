<?php // æ

require_once './includes/initialize.php';
global $oDB;
$oResponse = new stdClass();

if($_SERVER['HTTP_HOST'] =='127.0.0.1') {
	include './inc.local.authorize.php';
}


//$aGET = $_POST;
$aGET = providiPostBody();

if(empty($aGET['token']) && !empty($_GET['token'])) {
	$aGET['token'] = $_GET['token'];
}
if(empty($aGET['userId']) && !empty($_GET['userId'])) {
	$aGET['userId'] = $_GET['userId'];
}

try {

	if(empty($aGET['token'])) {
		throw new providiUnauthorizeException('Invalid request parameter - token' , 5100);
	}
	if(empty($aGET['userId'])) {
		throw new providiUnauthorizeException('Invalid request parameter - userId' , 5101);
	}


	$oAuth = ProvidiAuthentication::loadFromAuthToken($oDB , $aGET['token']);
	if($oAuth->providiID != $aGET['userId']) {
		throw new providiUnauthorizeException('Account mismatched - userId' , 5102);
	}

	$oDis = new providiDistributor($oDB);
	$oDis->loadFromProvidiID($aGET['userId']);

	$bDirty = false;

	if(!empty($aGET['accountType'])) {
		$oDis->setAccountType($aGET['accountType']);
		$bDirty = true;
	}
	if(!empty($aGET['address'])) {
		$oDis->setAddress($aGET['address']);
		$bDirty = true;
	}
	if(!empty($aGET['city'])) {
		$oDis->setCity($aGET['city']);
		$bDirty = true;
	}
	if(!empty($aGET['company_name'])) {
		$oDis->setCompanyName($aGET['company_name']);
		$bDirty = true;
	}
	if(!empty($aGET['zipcode'])) {
		$oDis->setPostNr($aGET['zipcode']);
		$bDirty = true;
	}
	if(!empty($aGET['partner_name'])) {
		$oDis->setPartnerName($aGET['partner_name']);
		$bDirty = true;
	}
	if(!empty($aGET['partner_email'])) {
		$oDis->setPartnerEmail($aGET['partner_email']);
		$bDirty = true;
	}
	if(!empty($aGET['partner_skype_id'])) {
		$oDis->setPartnerSkypeID($aGET['partner_skype_id']);
		$bDirty = true;
	}
	if(!empty($aGET['phone'])) {
		$oDis->setTelephone($aGET['phone']);
		$bDirty = true;
	}
	if(!empty($aGET['skype_id'])) {
		$oDis->setSkypeID($aGET['skype_id']);
		$bDirty = true;
	}
	$aNames = array();
	if(!empty($aGET['first_name'])) {
		$aNames[] = $aGET['first_name'];
	}
	if(!empty($aGET['last_name'])) {
		$aNames[] = $aGET['last_name'];
	}
	if(count($aNames) > 0) {
		$sName = ucwords(strtolower(implode(' ', $aNames)));
		$oDis->setName($sName);
		$bDirty = true;
	}

	$aNames = array();
	if(!empty($aGET['recruit_firstname'])) {
		$aNames[] = $aGET['recruit_firstname'];
	}
	if(!empty($aGET['recruit_lastname'])) {
		$aNames[] = $aGET['recruit_lastname'];
	}
	if(count($aNames) > 0) {
		$sName = ucwords(strtolower(implode(' ', $aNames)));
		$oDis->setSponsor($sName);
		$bDirty = true;
	}

	// 2015-12-11
	if(!empty($aGET['country'])) {
		$oDis->setCountry($aGET['country']);
		$bDirty = true;
	}



	if(!empty($aGET['reference_code'])) {
		$oDis->setReferenceCode($aGET['reference_code']);
		$bDirty = true;
	}

	if(!empty($aGET['vs_name'])) {
		$oDis->setSelfCustomerAccountName($aGET['vs_name']);
		$bDirty = true;
	}

	// 2015-12-11 , only update if has the "webpackage"
	if($oDis->hasWebPackage) {
		if(!empty($aGET['paypal_email'])) {
			$oDis->setPaypalEmail($aGET['paypal_email']);
			$bDirty = true;
		}
		if(!empty($aGET['shipping_cost'])) {
			$oDis->setCustomShippingCost($aGET['shipping_cost']);
			$bDirty = true;
		}
		if(!empty($aGET['quickpay_api_key'])) {
			$oDis->setQuickpayAPIKey($aGET['quickpay_api_key']);
			$bDirty = true;
		}
		if(!empty($aGET['quickpay_merchant_id'])) {
			$oDis->setQuickpayMerchantID($aGET['quickpay_merchant_id']);
			$bDirty = true;
		}

	}

	if($bDirty) {
		$oDis->save();
	}


	$oData = new stdClass();
	$oData->type = 'update_user_profile';
	$oData->id = $oAuth->providiID;
	$oAtt = new stdClass();
	$oAtt->status = 'OK';

	$oData->attributes = $oAtt;
	// // [=>CBH] fixed on 2015-10-28 , wrong response format
	//$oResponse = $oData;
	$oResponse->data = $oData;





} catch (Exception $e) {
	providiJSONErrorHandler($oResponse , $e);
}

if(isset($_GET['debug'])) {
	print '<PRE>';
	print_r($oResponse);
}

providiJSONResponse($oResponse);
/*

{
  "data": {
    "type": "update_user_profile",
    "id": "",
    "attributes": {
      "status": "ok"
    }
  }
}
*/
?>
