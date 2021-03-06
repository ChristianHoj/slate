<?php // æ


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
	### [CBH] 2015-11-05
	### $oAtt->name = $oDis->getName();
	$sName = $oDis->getName();
	$aNames = explode(' ', providiTrimspaces($sName));
	$oAtt->first_name = $aNames[0];
	$oAtt->last_name = '';
	if(count($aNames) > 0) {
		$oAtt->last_name = $aNames[ count($aNames) - 1 ];
	}	
	// added on 2015-11-24
	$oAtt->id = $oDis->getProvidiID();


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

	### [CBH] 2015-1105
	$oAtt->phone = $oDis->getTelephone();

	$oAtt->reference_code = $oDis->getReferenceCode(); 
	$oAtt->shipping_cost = $oDis->getCustomShippingCost();

	$oAtt->skype_id = $oDis->getSkypeID();
	// removed on 2015-11-09
//	$oAtt->id = $oDis->getProvidiID();
	$oAtt->zipcode = $oDis->getPostNr();
	

	$oAtt->vs_link = $oDis->getVSSelfAccountLink(); // 'VS - XXXXXXX';
	$oAtt->vs_name = $oDis->getSelfCustomerAccountName(); // 'VS - XXXXXXX';

	$oAtt->paypal_email = $oDis->getPaypalEmail();

	$oAtt->quickpay_api_key = $oDis->getQuickpayAPIKey(); 
	$oAtt->quickpay_merchant_id = $oDis->getQuickpayMerchantID();


	### 2015-11-20
	$oAtt->has_webpackage = $oDis->hasWebPackage() ? "true":"false";


//	providiGetDistributorInfo

	$oData = new stdClass();
	$oData->type = 'user';
	$oData->id = $oAuth->getProvidiID();

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

?>