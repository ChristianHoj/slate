<?php // all the scripts should be saved as UTF8 // æ

require_once './includes/initialize.php';
global $oDB , $oAuth;
$oResponse = new stdClass();

if($_SERVER['HTTP_HOST'] =='127.0.0.1') {
	include 'inc.local.authorize.php';
}

$aGET = $_GET;

try {
	


	$oAuth = ProvidiAuthentication::loadFromAuthToken($oDB , $aGET['token']);
	if($oAuth->providiID != $aGET['userId']) {
		throw new providiUnauthorizeException('Account mismatched - userId' , 5102);
	}


	require_once './includes/providiVSMember.class.php';

	$aOptions = array(
		'providiID' => $aGET['userId']
		, 'non_expire' => true
		, 'mode' => 'compact'
	);

	$oList = new providiVSMemberList($oDB);
	$aList = $oList->getList($aOptions);



	$nVU = mktime() + (60 * 60 * 3);

	$aData = array();

	for($i=0;$i<count($aList);$i++) {
		$oVS = new providiVSMember($oDB , $aList[$i]->id);


		$oTheVS = new stdClass();
		$oTheVS->type = 'vs_members';
		$oTheVS->id = $oVS->id;


		$oAtt = new stdClass();
		
		$oAtt->address =  $oVS->getAddress();
		$oAtt->name = $oVS->getName();
		$oAtt->phone = $oVS->getPhone();
        $oAtt->username = $oVS->getUsername();		
		$oAtt->latest_login = providiDateTime($oVS->getLastTimeVisited(), 'text');
		$oAtt->sign_up_date = providiDateTime($oVS->getSignupDate(), 'text');
		$sMP = $oVS->getMealPrice();
		if(empty($sMP)) {
			$sMP = "";
		}
		$oAtt->meal_price = $sMP;
		$oAtt->original_distributor = $oVS->getOriginalDistributorName();
		$oAtt->points = $oVS->getCostAnalysisScore();
		$oAtt->unpaid_months = $oVS->getUnpaidMonths();


		//  NOT USING, but maybe neccessary later
		// $aList[$i] ->distributor_authorized_link =  $oVS->getDistributorAuthorizedLink($oVS->currentDistributor);
		// Christian's vs_login_link = member_authorized_link
		$oAtt->vs_login_link =  $oVS->getVSMemberAuthorizedLink($oVS->customerID);

		$sRedirect = 'http://www.voressundhed.dk/personificeret/update.before_after_ny.php?custRef=' . $oVS->customerID;
		$oAtt->new_target_link =  $oVS->getVSMemberAuthorizedLink($oVS->customerID , null , $sRedirect);		

		$sOldTarget = sprintf('http://providi.eu/sc.support_links/editCust.php?id=%s&refer=%s' , $oVS->id , $oVS->customerID);
		if($_SERVER['HTTP_HOST'] == '127.0.0.1') {
			$sOldTarget = sprintf('http://127.0.0.1/providi.eu/sc.support_links/editCust.php?id=%s&refer=%s' , $oVS->id , $oVS->customerID);
		}
//		$aList[$i] ->old_target = sprintf('http://providi.eu/sc.support_links/editCust.php?id=%s&refer=%s' , $oVS->id , $oVS->customerID); = sprintf('http://providi.eu/sc.support_links/editCust.php?id=%s&refer=%s' , $oVS->id , $oVS->customerID);

		$aThisGET = array(
			'medlid' => $oVS->currentDistributor
			, 'vu' => $nVU

		);
		$aThisGET['hash'] = getProvidiAuthorizeHash($aThisGET['medlid'] , $aThisGET['vu']);
		$aThisGET['redirect'] = $sOldTarget;
		$sAuthURL = 'http://providi.eu/external/authorize.php';
		if($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
			$sAuthURL = 'http://127.0.0.1/providi.eu/external/authorize.php';		
		}
		$oAtt->old_target_link = sprintf('%s?%s' , $sAuthURL , http_build_query($aThisGET));
		$oAtt ->kalorie_login = $oVS->getVSMemberKRAuthorizedLink($oVS->customerID);

		$oAtt->customerID = $oVS->getCustomerID();
		// 2015-11-05
		$oTheVS->attributes = $oAtt;
		$aData[] = $oTheVS;
	
	}

	
	$oResponse->data = $aData;


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
    "type": "leads",
    "id": 1,
    "attributes": {
      "leads": [
        {
          "email": "candidate@email.com",
          "id": 123456,
          "lead_type": "own",
          "message": "Some explanation of why weight loss is desired.",
          "name": "Candi Date",
          "order": 1,
          "origin": "idealvaegt.dk",
          "phone": "12345678",
          "serious": "yes",
          "status": "signed_up",
          "weight_loss": "10-15",
          "zipcode": "9235"
        },
        {
          "email": "candidate2@email.com",
          "id": 7654432,
          "lead_type": "bonus",
          "message": "Text from questionnaire",
          "name": "Will Buyer",
          "order": 2,
          "origin": "idealvaegt.dk",
          "phone": "87654321",
          "serious": "maybe",
          "status": "",
          "weight_loss": "2-5",
          "zipcode": "54329"
        }
      ]
    }
  }
} */
?>