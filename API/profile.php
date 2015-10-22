<?php

require_once './includes/initialize.php';
global $oDB;
$oResponse = new stdClass();

if($_SERVER['HTTP_HOST'] =='127.0.0.1') {
	include './inc.local.authorize.php';
}

$aGET = $_GET;

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


	$sSponsor = $oDis->getSponsor();
	$aTempSponsor = explode(' ' , providiTrimSpaces($sSponsor));


	$oAtt = new stdClass();

	$oAtt->accountType = $oDis->getAccountType();
	$oAtt->name = $oDis->getName();
	$oAtt->address = $oDis->getAddress();
	$oAtt->city = $oDis->getCity();

	$oAtt->country = $oDis->getCountry();
	$oAtt->imageUrl = $oDis->getProfileImageURL();

	$oAtt->recruit_firstname = $aTempSponsor[0];
	$oAtt->recruit_lastname = count($aTempSponsor) == 1 ? '':$aTempSponsor[count($aTempSponsor) - 1];

	$oAtt->partner_name = $oDis->getPartnerName();
	$oAtt->partner_email = $oDis->getPartnerEmail();
	$oAtt->partner_skype_id = $oDis->getPartnerSkypeID();

	$oAtt->company_name = $oDis->getCompany();

	$oAtt->reference_code = $oDis->getReferenceCode(); 
	$oAtt->shipping_cost = $oDis->getCustomShippingCost();

	$oAtt->skype_id = $oDis->getSkypeID();
	$oAtt->id = $oDis->getProvidiID();
	$oAtt->zipcode = $oDis->getPostNr();
	

	$oAtt->vs_link = $oDis->getVSSelfAccountLink(); // 'VS - XXXXXXX';
	$oAtt->vs_name = $oDis->getSelfCustomerAccountName(); // 'VS - XXXXXXX';

	$oAtt->paypal_email = $oDis->getPaypalEmail();

	$oAtt->quickpay_api_key = $oDis->getQuickpayAPIKey(); 
	$oAtt->quickpay_merchant_id = $oDis->getQuickpayMerchantID();


//	providiGetDistributorInfo

	$oData = new stdClass();
	$oData->type = 'user';
	$oData->id = $oAuth->providiID;

	$oData->attributes = $oAtt;
	$oResponse = $oData;





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
    "type": "user",
    "id": "SC000XXXXXXX",
    "attributes": {
      "accountType": 0,
      "address": "Main Street 14",
      "city": "Lovkotsk",
      "company_name": "My Health Company",
      "country": "Slovenia",
      "imageUrl": "http://example.com/default-avatar.jpg",
      "name": "Gabriel Muresan",
      "partner_email": "partner@email.com",
      "partner_name": "Partner Muresan",
      "partner_skype_id": "partner_skype",
      "paypal_email": "mypaypal@email.com",
      "phone": "98765432",
      "quickpay_api_key": "klusfiuysbf74oha4bfauua42",
      "quickpay_merchant_id": 765234776,
      "recruit_firstname": "Recruiter",
      "recruit_lastname": "Muresan",
      "reference_code": 765384,
      "shipping_cost": 80,
      "skype_id": "seller_skype",
      "vs_link": "http://www.voressundhed.dk/?customerID=12345678",
      "vs_name": "My Vores Sundhed Name",
      "zipcode": 9230
    }
  }
}
*/
?>