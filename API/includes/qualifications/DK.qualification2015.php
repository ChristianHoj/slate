<?php 
###################################################################################
### includes/qualification2014.php
### site				:	sc-support.dk
### description		:   prepare the list of those distributors who get SC points
###							SC Score may calculated from
###						A.) #new_customer_made  : 1 SC point
###							### customer of the distributor who signed up after the [qualification_start] and not "0000" customers will be 1 SC point rewarded
###							### duration : valid through qualification period

###						B.) #VP : 1 SC point / 500 approved points , per month
###							## in table [qualification_vp] where we kept all the VP uploaded by distributor ( each month ) and approval result by Jan & Jan 
###							## we selected the most-recent-approved record for each month as a total point for that month , for each 500 , distributors got 1 SC point


###						C.) #direct_upline , 1 SC point , maximum 10 SC point
###							## the direct upline sponsor of the "new distributor" (signed up between [new_distributor_start] and [new_distributor_end]) will get 1 for each customer made by "new distributor"
###							## however , the maximum points they may get is 10 ( throughout qualification session )


###						D.) #org points , vary from 2 up to 5 SC points
###							## distributor whom join the org meeting (count from table [org2] only) valid through [org_session_start] and [org_session_end] will get 
###									(1-3 meetings) 2 SC points for each meeting
###									(4-6 meetings) 3 SC points for the latter meeting ( 1-3 got 2 SC points each, 4-6 got 3 SC points each)
###									(7-9 meetings) 4 SC points for the latter meeting ( 1-3 got 2 SC points each, 4-6 got 3 SC points each , 7-9 got 4 SC point each)
###									(10-12 meetings) 5 SC points for the latter meeting ( 1-3 got 2 SC points each, 4-6 got 3 SC points each, 7-9 got 4 SC point each, 10-12 got 5 SC point each)
###

###						configurable parameters
###							$s2015QualificationSession ( default = '2014-05-31' )  determine which session we're dealing with 
###							$s2015UseTempTable ( default = ' TEMPORARY ' ,  can be left '' for debugging )
###							$s2015TempTableName ( default = '_qualification2014' ) 
###							$b2015UseNiceName ( default = true )	: instead of taking name from [da_reference] , use from [refkunde] which are more "nice"
###

###
###						runtime configuration
###							make a contstant name 'QUALIFICATION_2015_REQUIRE_DETAIL' or steup $_GET['2014_qualification_detail'] to make the script fully execute the detail plan
###################################################################################


#####################################################################################
### configurable
#####################################################################################
// $s2015CustomerSignupFrom = when we start counting the customer , defaut is 2011-06-01
// $s2015CustomerSignupTo = when we stop

global $oDB;
global $s2015QualificationSession , $s2015TempTableName , $a2015QRules , $b2015UseNiceName , $aNiceName , $nNumbersofHighlighted , $aQ2015Con , $aNew2014Dis , $sDaAllTableName;
global $sSponsorTable ;
### 2014-02-04
global $s2015SponsorPointFrom , $s2015SponsorPointTo;

if(!defined('MAXIMUM_NEWSUP_POINT')) {
	define('MAXIMUM_NEWSUP_POINT' , 10); //  points for supervisor
}

$aQ2015Con = array();

$b2015UseNiceName = true;
$s2015QualificationSession = '2015-12-31'; // FIXED , DO NOT CHANGE THIS UNTIL NEXT YEAR
//$s2015QualificationSession = '2014-01-31 11:00:00'; 

//$s2015CustomerSignupFrom  = '2013-07-01';
### 2014-01-31//$s2015CustomerSignupTo  = '2014-06-30';  // quick stop
$s2015CustomerSignupFrom  = '2015-05-01 00:00:00';
$s2015CustomerSignupTo  = '2015-12-31 23:59:59' ;// wrong option :( , no longer use this '2014-01-31 12:00:00';

$s2015OrgMeetingFrom = '2015-05-01';
$s2015OrgMeetingTo = '2015-12-31';

$s2015DirectSponsorFrom = '2015-05-01';
$s2015DirectSponsorTo = '2015-12-31';


$s2015webpakkeSponsorFrom = '2015-05-01';
$s2015webpakkeSponsorTo = '2015-12-31';

$s2015VPFrom = '2015-05-01';
$s2015VPTo = '2015-12-31';
$f2015SCpointsPer500VP = 1 ; // 1 SC points per 500 VP

$nMaximumSponsorPoints = 10; // default = 10
$nTogetherSponsorPoints = 10; // default = 10 , Toscana + Thailand mustbe <= this number

$s2015SponsorPointFrom = '2013-07-01 00:00:00';
$s2015SponsorPointTo = '2014-01-31 12:00:00';


$sDaAllTableName = 'tmp_2015_da_all';  // use


$sSponsorTable = 'tmp2015_sponsor_bonus';

// 2015-09-04
global $sAugustNewRuleFrom;
$sAugustNewRuleFrom = '2015-08-24';


$s2015UseTemporary = ''; // ' TEMPORARY ';
if($_SERVER['HTTP_HOST']=='127.0.0.1' || false) {
	echo ' <h1 style="color:red;">Disable TEMPORARY OPTION</h1>';
	$s2015UseTemporary = ' ';
}


/*$s2015WebpakkeCfrom = '2012-06-01';
$s2015WebpakkeCto = '2014-05-31';
*/
// six month  before starting new campagne
$s2015WebpakkeCfrom = '2013-01-01';
$s2015WebpakkeCto = '2016-12-31';



// local debug , ok to levae that as it is 
if($_SERVER['HTTP_HOST']=='127.0.0.1') {

}
$s2015TempTableName = '_tmp2015_qualifications';
$s2015TempDetailTableName = '_tmp2015_qualifications_detail';
$aLogs = array();

$a2015QRules = array(
	'scp_cus' =>  ' SUM(IFNULL(customers,0)) '					// A.) Customers Signedup , 1 customer = 1 sc point
	, 'scp_meets' => ' SUM(IFNULL(meeting_points,0)) '		// B.) steps are calculated already
	// , 'scp_vp' => ' SUM(vp_points) '					// C.) for every 500 VP = 1 sc point , already multiplied
	// , 'scp_sponsor' => ' SUM(sponsor_point) '	// D). 1 sponsor_point = 1 SCpoint  , MAXIMUM 10 
	// , 'scp_newsup' => ' SUM(newsup_point) '		// E).  // NOT YET USING
	, 'direct_sponsor' => ' SUM(IFNULL(direct_sponsor,0)) '
	, 'sponsor_webpakke' => ' SUM(IFNULL(webpakker_points,0))'
	, 'professional_rank' => ' SUM(IFNULL(professional_rank,0)) ' 
	, 'new_dis_webpakke' => ' SUM(IFNULL(new_dis_webpakke,0)) '	// NEW distributor who sign up for webpakken BEFORE $sAugustNewRuleFrom
);


/*require_once '../../gslib.php';
DB_Connect();
*/
global $aNew2014Dis;
$aNew2014Dis = array();
$sQuery = ' SELECT medlid FROM abonnementordrer WHERE fakturanr > 0 AND amount >= 140 AND ordretype IN ("webpakkeC" ,"webpakkeBC") GROUP BY medlid ';
$sQuery .= sprintf(' HAVING MIN(DATE(mdato))  BETWEEN "%s" AND "%s" ', $s2015WebpakkeCfrom  , 	$s2015WebpakkeCto );
$aLogs[] = ' *** ' . $sQuery;
$aNew2014Dis = $oDB->getColumn($sQuery);
$aNewProvidiDis = _getNewProvidiDistributor2015();

######################################
// 2015-10-05 , make a copy of info in $aQ2015Con here for external reference
$aTheCons = array();
#####################################
### A.)
#####################################
$aWhere = array(
	'id_skip' => ' id >= 42000'   
	, 'no_iv' => ' currentDistributor NOT LIKE "IV%" ' 
	, 'no_sk' => ' currentDistributor NOT LIKE "SK%" ' 
	, 'no_sundslank' => ' currentDistributor NOT LIKE "SS%" ' 
	, 'no_empty_no_self' => ' customerID != ""  AND customerID NOT LIKE "%0000" '  
	, 'signup_from' => sprintf(' signupDate BETWEEN "%s" AND "%s" ' , $s2015CustomerSignupFrom , $s2015CustomerSignupTo)
);
$aTheCons['customer'] = $aWhere;
#####################################
### B.)
#####################################

$aWhere = array(
	'skip_id' => ' id >= 15107 '
	, 'meeting_session' => sprintf( 'DATE(meeting_session) BETWEEN "%s" AND "%s" ' , $s2015OrgMeetingFrom , $s2015OrgMeetingTo)

	, 'fremmoedt' => ' fremmoedt = "Ja" '
	, 'purchase_within_3_days' => ' DATE(createdOn) <= former_meeting_session + INTERVAL 3 DAY' 
);
$aTheCons['org'] = $aWhere;

$aWhere = array(
	'skip_id' => ' id >= 1525 '
	, 'approve_only' => ' YEAR(approved_on) > 0 AND YEAR(deleted_on) = 0 '
	, 'is_active' => 'is_active = 1'
	, 'record_month' => sprintf('record_month BETWEEN "%s" AND "%s" ' , $s2015VPFrom , $s2015VPTo)
);
$aTheCons['vp'] = $aWhere;

$aWhere = array(
				' customerID != "" AND customerID NOT LIKE "%0000" ' 
				// , sprintf(' DATE(signUpDate) BETWEEN "%s" AND "%s" ' , $s2015CustomerSignupFrom , $s2015CustomerSignupTo)
				### 2014-02-04 , use the new variables $s2015SponsorPointFrom , $s2015SponsorPointTo
				, sprintf(' signUpDate BETWEEN "%s" AND "%s" ' , $s2015SponsorPointFrom , $s2015SponsorPointTo)
				, sprintf(' originalDistributor IN ("%s") ' , implode('","', $aNew2014Dis))
			);
$aTheCons['sponsor'] = $aWhere;

$aWhere = array(
	'signupDate' => sprintf(' g_date BETWEEN "%s" AND "%s" ' , $s2015DirectSponsorFrom , $s2015DirectSponsorTo)
	, 'accountType' => 'accountType IN ("providi" , "sc-support") ' // MAY need to remove
);
$aTheCons['direct_sponsor'] = $aWhere;

$aWhere = array(
	'earningRank' => ' earningRank = "professional" '
	, 'username' => ' username != "" AND password != ""  '
);

$aTheCons['professional_rank'] = $aWhere;



$aWhere = array(
	'medlid' => sprintf(' medlid IN ("%s") ' , implode('", "', $aNewProvidiDis))
	, 'fakturanr' => ' fakturanr > 10 '
	, 'ordreType' => ' ordretype IN ("providi_webpakken" , "webpakke_advance") '
);
// ndw = new distributor webpakke
$aTheCons['ndw'] = $aWhere;


$aQ2015Con = $aTheCons;






######################################



$bDoCreateTable = false; // default = true
$bDoCreateDetailTable  = false;

if(defined('QUALIFICATION_2015_REQUIRE_DETAIL') || isset($_GET['2015_qualification_detail']) || isset($_GET['force_reload']) || isset($_GET['force_create'])) {
	$bDoCreateDetailTable  = true;
/*	$sQuery = ' TRUNCATE TABLE providi_cached_tables ';
	mysql_query($sQuery) or die(mysql_error());
*/
}

if(!$bDoCreateDetailTable) {
	$aWhere = array(
		'tableName' =>  sprintf(' tableName = "%s" ' , $s2015TempTableName)
		, 'notExpired' => ' expiredOn > NOW() ' 
	);
	$sQuery = sprintf(' SELECT * FROM providi_cached_tables WHERE %s ' , implode(' AND'  ,$aWhere));
	$oDB->query($sQuery);
	$nOK = $oDB->numRows() > 0;
	/*
	$oRS = mysql_query($sQuery) or die(mysql_error());
	$nOK = mysql_num_rows($oRS) > 0;
	*/
}

if(!$nOK || $bDoCreateDetailTable || isset($_GET['force_create']) || isset($_GET['force_reload'])) {
	$bDoCreateTable = true;

	$aInsert = array(
		'tableName' => $oDB->esc($s2015TempTableName)	
		, 'createdOn' => date('Y-m-d H:i:s', mktime())
		, 'expiredOn' => date('Y-m-d H:i:s', strtotime(' +3 HOUR' ))
	);
	$sQuery = sprintf(' INSERT INTO providi_cached_tables(%s) VALUES("%s")' , implode(', ', array_keys($aInsert)) , implode('", "', array_values($aInsert)));
	$oDB->query($sQuery);

	$aInsert['tableName'] = $oDB->esc($s2015TempDetailTableName)	;
	$sQuery = sprintf(' INSERT INTO providi_cached_tables(%s) VALUES("%s")' , implode(', ', array_keys($aInsert)) , implode('", "', array_values($aInsert)));
	$oDB->query($sQuery);

	if($_SERVER['HTTP_HOST'] == '127.0.0.1') {
		printf(' record %s has been inserted <br />' , $s2015TempTableName);
		printf(' record %s has been inserted <br />' , $s2015TempDetailTableName);
	}
	
} else {

	if($_SERVER['HTTP_HOST'] == '127.0.0.1') {
		printf(' record %s has been skipped<br />' , $s2015TempTableName);
		printf(' record %s has been skipped <br />' , $s2015TempDetailTableName);
	}

}


if($bDoCreateTable)  {
	$sQuery = sprintf(' DROP %s TABLE IF EXISTS %s ' , $s2015UseTemporary , $s2015TempTableName);
	$aLogs[] = $sQuery;
	$oDB->query($sQuery);



	$aLogs[] = '<strong>A. ===============================================</strong>';
	$aLogs[] = '<strong style="color:red;">#new_customer_made</strong>';
	//  A.) customer
	$aWhere = array(
		'id_skip' => ' id >= 42000'   
		, 'no_iv' => ' currentDistributor NOT LIKE "IV%" ' 
		// 2014-01-23 , removing SK result from the list
		, 'no_sk' => ' currentDistributor NOT LIKE "SK%" ' 
		// 2014-06-03 , removing SS result from the list
		, 'no_sundslank' => ' currentDistributor NOT LIKE "SS%" ' 

		, 'no_empty_no_self' => ' customerID != ""  AND customerID NOT LIKE "%0000" '  
		// ### 2014-01-31 , 'signup_from' => sprintf(' DATE(signupDate) BETWEEN "%s" AND "%s" ' , $s2015CustomerSignupFrom , $s2015CustomerSignupTo)
		, 'signup_from' => sprintf(' signupDate BETWEEN "%s" AND "%s" ' , $s2015CustomerSignupFrom , $s2015CustomerSignupTo)
	);
	if($_SERVER['HTTP_HOST']=='127.0.0.1') {
		unset($aWhere['id_skip']);
		echo '<h1 style="color:red;">Disable customer_info,  ID skipping</h1>';
	}

	


	$aQ2015Con['customer'] = $aWhere;

	// create temporary table base on 1st condition 
	// TEMPORARY ?
	// $sQuery = sprintf(' CREATE TEMPORARY TABLE  %s AS ' , $s2015TempTableName);
	$sQuery = sprintf(' CREATE %s TABLE  %s AS ' ,$s2015UseTemporary , $s2015TempTableName);
	// 2013-09-09 , start to givethe extra 1 vs points for customer who sign up durring "2013-09-07 09:00:00" AND "2013-09-30 23:59:59"
	// original line	$sQuery .= sprintf(' SELECT "new_customer_made" code , originalDistributor medlid , RPAD(MAX(originalDistributorName),50," ")   name , COUNT(*) customers , 0 meetings , 0 meeting_points , 0 vp , 0000.0 vp_points ,  0 sponsor_point , MAX(signupDate) last_cus , 0 last_meet_id , DATE("0000-00-00") last_vp_date ,  0 newsup_point , "0000-00-00" newsup_session '); 
	// 2013-10-01 , change the due date from 2013-09-30 23:59:59 to 2013-10-05 04:00:00"
	// $sQuery .= sprintf(' SELECT "new_customer_made" code , originalDistributor medlid , RPAD(MAX(originalDistributorName),50," ")   name , COUNT(*) count_customer , SUM(IF( signupDate BETWEEN "2013-09-07 09:00:00" AND "2013-10-05 04:00:00" , 2 , 1)) customers , 0 meetings , 0 meeting_points , 0 vp , 0000.0 vp_points ,  0 sponsor_point , MAX(signupDate) last_cus , 0 last_meet_id , DATE("0000-00-00") last_vp_date ,  0 newsup_point , "0000-00-00" newsup_session '); 
	// 2015-09-04 add more fields :(
	// $sQuery .= sprintf(' SELECT "new_customer_made" code , originalDistributor medlid , RPAD(MAX(originalDistributorName),50," ")   name , COUNT(*) count_customer , SUM(IF( signupDate BETWEEN "2013-09-07 09:00:00" AND "2013-10-05 04:00:00" , 2 , 1)) customers , 0 meetings , 0 meeting_points , 0 vp , 0000.0 vp_points ,  0 sponsor_point , MAX(signupDate) last_cus , 0 last_meet_id , DATE("0000-00-00") last_vp_date ,  0 newsup_point , "0000-00-00" newsup_session , 0 direct_sponsor , "0000-00-00" last_direct_sponsor  , 0 webpakker , 0 webpakker_points , "0000-00-00" last_webpakkers '); 
	$sQuery .= sprintf(' SELECT "new_customer_made" code , originalDistributor medlid , RPAD(MAX(originalDistributorName),50," ")   name , COUNT(*) count_customer , SUM(IF( signupDate BETWEEN "2013-09-07 09:00:00" AND "2013-10-05 04:00:00" , 2 , 1)) customers , 0 meetings , 0 meeting_points , 0 vp , 0000.0 vp_points ,  0 sponsor_point , MAX(signupDate) last_cus , 0 last_meet_id , DATE("0000-00-00") last_vp_date ,  0 newsup_point , "0000-00-00" newsup_session , 0 raw_direct_sponsor , 0 direct_sponsor , "0000-00-00" last_direct_sponsor  , 0 webpakker , 0 webpakker_points , "0000-00-00" last_webpakkers '); 
	$sQuery .= ' , 0 professional_rank , 0 new_dis_webpakke ';
	$sQuery .= sprintf(' FROM customer_info WHERE %s GROUP BY  originalDistributor ' , implode(' AND ', $aWhere));

	$aLogs[] = $sQuery;
	$oDB->query($sQuery);



	if($bDoCreateDetailTable) { 
		$sQuery = sprintf(' DROP %s TABLE IF EXISTS %s ', $s2015UseTemporary ,$s2015TempDetailTableName);
		$aLogs[] = $sQuery;
		$oDB->query($sQuery);

	
		$sSubQuery = sprintf(' CREATE %s TABLE  %s AS ' , $s2015UseTemporary , $s2015TempDetailTableName);
		// 2013-09-09 , extra 1 point for customer who signed up within period
		// original line $sSubQuery .= sprintf(' SELECT originalDistributor medlid , LAST_DAY(signupDate) session , RPAD("",50,"z") sorting  , 0 raw_vp ,  0 raw_sponsor , RPAD(MAX(originalDistributorName),50," ")   name , COUNT(*) customers , 0 meetings , 0 meeting_points , 0 vp , 0000.0 vp_points , 000 raw_sponsor_point , 000 deducted_point , 000 sponsor_point , MAX(signupDate) last_cus , 0 last_meet_id , DATE("0000-00-00") last_vp_date '); 
		$sSubQuery .= sprintf(' SELECT "new_customer_made" code , originalDistributor medlid , LAST_DAY(signupDate) session , RPAD("",50,"z") sorting  , 0 raw_vp ,  0 raw_sponsor , RPAD(MAX(originalDistributorName),50," ")   name , COUNT(*) count_customer , SUM(IF( signupDate BETWEEN "2013-09-07 09:00:00" AND "2013-10-05 04:00:00" , 2 , 1)) customers , 0 meetings , 0 meeting_points , 0 vp , 0000.0 vp_points , 000 raw_sponsor_point , 000 deducted_point , 000 sponsor_point , MAX(signupDate) last_cus , 0 last_meet_id , DATE("0000-00-00") last_vp_date , 0 raw_direct_sponsor ,  0 direct_sponsor , "0000-00-00" last_direct_sponsor , 0 webpakker , 0 webpakker_points , "0000-00-00" last_webpakkers '); 
		$sSubQuery .= ' , 0 professional_rank , 0 new_dis_webpakke ';
		
		$sSubQuery .= ' , DATE("0000-00-00") newsup_session , 0 newsup_point ';
		$sSubQuery .= sprintf(' FROM customer_info WHERE %s GROUP BY  originalDistributor , LAST_DAY(signupDate) ' , implode(' AND ', $aWhere));

		$aLogs[] = ' sub_detail ';	
		$aLogs[] = $sSubQuery;

		//$oSubRS = mysql_query($sSubQuery) or die(mysql_error() . __LINE__);
		$oDB->query($sSubQuery);

	}

	

	
	$aLogs[] = '<strong>B. ===============================================</strong>';
	$aLogs[] = '<strong style="color:red;">Organization Meetings</strong>';
	// B.) org meetings 
	// ALSO USE INSERT .. SELECT FOR 

	// currrently skip the org_ny _forh :D
	$aWhere = array(
		'skip_id' => ' id >= 15107 '
		, 'meeting_session' => sprintf( 'DATE(meeting_session) BETWEEN "%s" AND "%s" ' , $s2015OrgMeetingFrom , $s2015OrgMeetingTo)

		, 'fremmoedt' => ' fremmoedt = "Ja" '
		, 'purchase_within_3_days' => ' DATE(createdOn) <= former_meeting_session + INTERVAL 3 DAY' 
	);
	$aQ2015Con['org'] = $aWhere;


	// condition changed on 2015-07-09
//	$sExtraOrgRule = ' CASE  WHEN COUNT(DISTINCT meeting_session) <= 3 THEN COUNT(DISTINCT meeting_session) * 2  WHEN COUNT(DISTINCT meeting_session) <= 6 THEN (3 * (COUNT(DISTINCT meeting_session) - 3)) + 6 WHEN COUNT(DISTINCT meeting_session) <= 9 THEN (4 * (COUNT(DISTINCT meeting_session) - 6)) + 15 WHEN COUNT(DISTINCT meeting_session) <= 12 THEN (5 * (COUNT(DISTINCT meeting_session) - 9)) + 27  END '  ;

	
	$sExtraOrgRule = ' CASE  COUNT(DISTINCT meeting_session) WHEN  1 THEN 1   WHEN 2 THEN 3  WHEN 3 THEN 6 WHEN 4 THEN 10 WHEN 5 THEN 15 WHEN 6 THEN 21 ELSE 0 END '  ;
	$sQuery = sprintf(' INSERT INTO %s(code , medlid , meetings  ,meeting_points , name , last_meet_id , last_cus , last_vp_date) ' , $s2015TempTableName);
	$sQuery .= sprintf(' SELECT "org" , medlid , COUNT(DISTINCT meeting_session) meeting_joins , %s sc_org_points , MAX(navn) name , id , DATE("0000-00-00") , DATE("0000-00-00")  FROM org2 WHERE  %s  GROUP BY medlid ' , $sExtraOrgRule , implode(' AND ' ,$aWhere)) ;
	$aLogs[] = $sQuery;

	/*$oRS = mysql_query($sQuery) or die(mysql_error() . '<hr />' . $sQuery . '<hr />' . __LINE__);*/
	$oDB->query($sQuery);



	if($bDoCreateDetailTable) { 
		/*
		$sSubQuery = sprintf(' INSERT INTO %s(code , medlid , session , meetings ,meeting_points , name , last_meet_id , last_cus , last_vp_date) ' , $s2015TempDetailTableName);
		$sSubQuery .= sprintf(' SELECT "org" code , medlid , LAST_DAY(meeting_session) session , COUNT(DISTINCT meeting_session) meeting_joins , %s sc_org_joins , MAX(navn) name , id , DATE("0000-00-00") , DATE("0000-00-00")  FROM org2 WHERE  %s  GROUP BY medlid , LAST_DAY(meeting_session) ' , ' 0 ', implode(' AND ' ,$aWhere)) ;
		*/
		

		$sSubQuery = sprintf(' SELECT "org" code , medlid , LAST_DAY(meeting_session) session , COUNT(DISTINCT medlid) meetings , MAX(navn) name FROM org2 WHERE  %s  GROUP BY medlid , LAST_DAY(meeting_session) ORDER BY medlid , LAST_DAY(meeting_session) ' , implode(' AND ' ,$aWhere)) ;

		$aLogs[] = ' sub_detail ';	
		$aLogs[] = $sSubQuery;
		//$oSubRS = mysql_query($sSubQuery) or die(mysql_error() . '???' .  __LINE__);
		$aList = $oDB->query($sSubQuery);
/*
		$sSubQuery = sprintf(' SELECT "org" code , medlid , COUNT(DISTINCT meeting_session) meetings , MAX(navn) name , id last_meet_id  FROM org2 WHERE  %s  GROUP BY medlid ORDER BY medlid  '  , implode(' AND ' ,$aWhere)) ;
		$aLogs[] = $sSubQuery;
		$oRS = mysql_query($sSubQuery) or die(mysql_error() . '<hr />' . $sSubQuery . '<hr />' . __LINE__);
*/

		// manually calculated & insert
		$nTheMedlid = null;
		$nTheOrgPoints = null;
		//while($aRow = mysql_fetch_assoc($oSubRS)) {
		while( list($sUnusedKey , $oRow) =  each($aList)) {
			if($nTheMedlid != $oRow->medlid) {
				$oRow->meeting_points = $nTheOrgPoints = 1;
				$nTheMedlid = $oRow->medlid;
			} else {
				switch($nTheOrgPoints + 1) {
					case 2 :		$oRow->meeting_points = $nTheOrgPoints = 2; break;
					case 3 :		$oRow->meeting_points = $nTheOrgPoints = 3; break;
					case 4 :		$oRow->meeting_points = $nTheOrgPoints = 4; break;
					case 5 :		$oRow->meeting_points = $nTheOrgPoints = 5; break;
					case 6 :		$oRow->meeting_points = $nTheOrgPoints = 6; break;				
				}
				reset($oRow);
			}

			$aRow = array();
			while(list($sKey,$sValue) = each($oRow)) {
				$aRow[ $sKey ] = $oDB->esc($sValue);
			}			
		
			$sQuery = sprintf(' INSERT INTO %s(%s) VALUES("%s") ' , $s2015TempDetailTableName , implode(', ', array_keys($aRow)) , implode('", "', array_values($aRow)));
			$oDB->query($sQuery);

		}

//		print $sSubQuery;exit;

	}




// 2015-09-04 STILL OK

	if(!empty($a2015QRules['scp_vp'])) {
	
		$aLogs[] = '<strong>C. ===============================================</strong>';
		$aLogs[] = '<strong style="color:red;">Approved VP</strong>';

		// C.) approved VP ,
		// **** NOTE : i didn't put the date condition here since it somehow make sense to update the VP you may get after the last due
		// CHANGE THE WAY THE TABLE WORKS , WE'LL USE ONLY THE ACTIVE RECORD
		$aWhere = array(
			'skip_id' => ' id >= 1525 '
			, 'approve_only' => ' YEAR(approved_on) > 0 AND YEAR(deleted_on) = 0 '
			, 'is_active' => 'is_active = 1'
			// , 'qualification_session' => sprintf(' qualification_session = "%s" ' , $s2015QualificationSession)
			// qualification cannot be used since the 2012-06-30 and 2014-05-31 has the linked period
			// use the date instead

			, 'record_month' => sprintf('record_month BETWEEN "%s" AND "%s" ' , $s2015VPFrom , $s2015VPTo)
			
		);
		$aQ2015Con['vp'] = $aWhere;

		$sQuery = sprintf(' INSERT INTO %s(code , medlid , vp , vp_points , last_vp_date , last_cus , last_meet_id ) ' , $s2015TempTableName);

		// fixed on 2011-07-30 , VP calculated per each approved month -*-
		$sQuery .= sprintf('SELECT "approved_vp" , medlid , SUM( FLOOR(vp / 500) ) vps ,  SUM( FLOOR(vp / 500) ) * %0.2f vp_points , MAX(record_month) , DATE("0000-00-00")  , 0 FROM qualification_vp  WHERE  %s  GROUP BY medlid  ' , $f2015SCpointsPer500VP , implode(' AND ' , $aWhere));
		$aLogs[] = $sQuery;
		$oDB->query($sQuery);
		$aLogs[] = $oDB->affectedRows();


		if($bDoCreateDetailTable) { 
			$sSubQuery = sprintf(' INSERT INTO %s(code, medlid , session , raw_vp , vp , vp_points , last_vp_date , last_cus , last_meet_id ) ' , $s2015TempDetailTableName);
			$sSubQuery .= sprintf('SELECT "vp" code , medlid , last_day(record_month) , MAX(vp) vp , SUM( FLOOR(vp / 500) ) vps , SUM( FLOOR(vp / 500) ) * %0.2f  vp_points ,MAX(record_month) , DATE("0000-00-00")  , 0 FROM qualification_vp  WHERE  %s  GROUP BY medlid , last_day(record_month) ' , $f2015SCpointsPer500VP ,  implode(' AND ' , $aWhere));

			$aLogs[] = ' sub_detail ';	
			$aLogs[] = $sSubQuery;
			$oDB->query($sSubQuery);

		}
	}

	if(!empty($a2015QRules['scp_sponsor'])) {  // START [HUGE-IF-001]


			$aLogs[] = '<strong>D. ===============================================</strong>';
			$aLogs[] = '<strong style="color:red;">Adjusted Sponsor Point</strong>';

			// D.) the big one -*-
			// first , take the list of distributor who just bought  webpakkeC between period


			if(!empty($_GET['debug']) && $_GET['debug'] =='new_dis') {
				print '<PRE>';
				print_r($aNew2014Dis);exit;
			}

			// now $aNew2014Dis contains list of "newDistributors"
			// next , let's find out how many customers each one may have 

			$aQ2015Con['sponsor'] =  $aWhere = array(
				' customerID != "" AND customerID NOT LIKE "%0000" ' 
				// , sprintf(' DATE(signUpDate) BETWEEN "%s" AND "%s" ' , $s2015CustomerSignupFrom , $s2015CustomerSignupTo)
				### 2014-02-04 , use the new variables $s2015SponsorPointFrom , $s2015SponsorPointTo
				, sprintf(' signUpDate BETWEEN "%s" AND "%s" ' , $s2015SponsorPointFrom , $s2015SponsorPointTo)
				, sprintf(' originalDistributor IN ("%s") ' , implode('","', $aNew2014Dis))
			);

			$sQuery = sprintf(' DROP TABLE IF EXISTS  %s' , $sSponsorTable);
			$aLogs[] = $sQuery;
			$oDB->query($sQuery);

			$sQuery = sprintf(' CREATE TABLE %s AS  ' ,$sSponsorTable);
			$sQuery .= sprintf(' SELECT  originalDistributor medlid , IF(COUNT(DISTINCT customerID)   > %s , %s , COUNT(DISTINCT customerID)) customers , 000 deducted , 000 real_sponsor_point   ' , $nMaximumSponsorPoints , $nMaximumSponsorPoints ); 
			$sQuery .= sprintf(' FROM  customer_info WHERE   %s ' , implode(' AND ' ,$aWhere));
			$sQuery .= ' GROUP BY originalDistributor ';
			$aLogs[] = $sQuery;
			//mysql_query($sQuery) or die(mysql_error() . ' line ' . __LINE__);
			$oDB->query($sQuery);


			##########################################################################################
			### 2012-07-16 , fix about the SC sponsor point those given to distributor on Toscana , shouldn't be given again on Thailand qualification
			##########################################################################################

			$bRemoveToscanaPoint = false; // default = true

			if($bRemoveToscanaPoint) {
				$sQuery = sprintf(' INSERT INTO %s(medlid,deducted) VALUES   ' , $sSponsorTable);
				$sToscanaDeductQuery = sprintf(' INSERT INTO tmp2015_sponsor_bonus_detail   (medlid,deducted) VALUES   ' );

					
					$sTemp = ' ( "3508200462" , "10" ), ( "35Y0000125" , "10" ) ';

					$sQuery .= $sTemp;
					$sToscanaDeductQuery .= $sTemp;

					//mysql_query($sQuery) or die(mysql_error() . ' line ' . __LINE__);
					$oDB->query($sQuery);
					$aLogs[] = $sQuery;
							
			}


			// 2012 -07-13  report that the quitted downline customers should be counted as well !
			############  [BUG FIX] 
			/*$sQuery = sprintf(' CREATE TEMPORARY TABLE %s AS SELECT da_reference.* , "da_reference" source FROM da_reference UNION ALL  SELECT da_reference_deleted2.* , "da_reference_deleted2" source FROM da_reference_deleted2 ' , $sDaAllTableName);
			$aLogs[] = '<i>' . $sQuery . '</i>';
			mysql_query($sQuery) or die(mysql_error() . ' line ' . __LINE__);*/

			// 2013-08-06 report of slow query, try do insert twice instead
			$sQuery = sprintf(' DROP TABLE IF EXISTS  %s ' , $sDaAllTableName);
			$aLogs[] = '<i>' . $sQuery . '</i>';
			$oDB->query($sQuery);

			$sQuery = sprintf(' CREATE TABLE %s AS SELECT da_reference.* , "da_reference" source FROM da_reference ' , $sDaAllTableName);
			$aLogs[] = '<i>' . $sQuery . '</i>';
			$oDB->query($sQuery);

			$sQuery = sprintf('SELECT Reference FROM %s ' , $sDaAllTableName);
			/*$oRS = mysql_query($sQuery) or die(mysql_error() . '????');
			$aUsedDA = array();
			while($aRow= mysql_fetch_assoc($oRS)) {
				$aUsedDA[] = $oDB->esc($aRow['Reference']);
			}*/
			$aUsedDA = $oDB->getColumn($sQuery);


			$sQuery = sprintf(' INSERT INTO %s SELECT da_reference_deleted2.* , "da_reference_deleted2" source FROM da_reference_deleted2  WHERE Reference NOT IN ("%s")' , $sDaAllTableName , implode('", "', $aUsedDA));
			$aLogs[] = '<i>' . $sQuery . '</i>';
			$oDB->query($sQuery);
			//mysql_query($sQuery) or die(mysql_error() . ' line ' . __LINE__);




			// 2012-07-16 : we need to modify the number of SC points given before summing up!

			$sQuery = ' DROP TABLE IF EXISTS  tmp_sponsor_final_2015  ';
			$aLogs[] = '<i>' . $sQuery . '</i>';
			//mysql_query($sQuery) or die(mysql_error() . ' line ' . __LINE__);
			$oDB->query($sQuery);


			$sQuery = ' CREATE TABLE tmp_sponsor_final_2015 AS ';
			$sQuery .= sprintf(' SELECT medlid , IF( LEAST(customers, %d - SUM(deducted) ) <0 , 0 , LEAST(customers, %d - SUM(deducted) )) sponsor_point  FROM %s GROUP BY medlid ' , $nTogetherSponsorPoints , $nTogetherSponsorPoints ,$sSponsorTable );
			$aLogs[] = '<i>' . $sQuery . '</i>';

			//mysql_query($sQuery) or die(mysql_error() . ' line ' . __LINE__);
			$oDB->query($sQuery);


			// now LEFT JOIN back to da_reference, to get the sponsor_id , and insert into main table 
			$sQuery = sprintf(' INSERT INTO %s (code , medlid , sponsor_point , last_cus , last_vp_date ) '  ,$s2015TempTableName);
		//	ORG $sQuery .= sprintf(' SELECT sponsor_id , SUM(customers) sponsor_point  , DATE("0000-00-00") , DATE("0000-00-00") FROM  %s t LEFT JOIN da_reference  da ON t.medlid = da.Reference GROUP BY sponsor_id ' , $sSponsorTable , $sDaAllTableName);

		// 	ORG $sQuery .= sprintf(' SELECT "adjusted_sponsor_point" , sponsor_id ,  IF( LEAST(customers, %d - SUM(deducted) ) <0 , 0 , LEAST(customers, %d - SUM(deducted) )) sponsor_point , DATE("0000-00-00") , DATE("0000-00-00")  FROM  %s t LEFT JOIN %s da ON t.medlid = da.Reference WHERE sponsor_id IS NOT NULL GROUP BY sponsor_id ' , $nTogetherSponsorPoints , $nTogetherSponsorPoints ,$sSponsorTable , $sDaAllTableName);
			$sQuery .= sprintf(' SELECT "adjusted_sponsor_point" , sponsor_id ,  SUM(sponsor_point) sponsor_point , DATE("0000-00-00") , DATE("0000-00-00")  FROM  tmp_sponsor_final_2015 t LEFT JOIN %s da ON t.medlid = da.Reference WHERE sponsor_id IS NOT NULL GROUP BY sponsor_id ' , $sDaAllTableName);

			$aLogs[] = $sQuery;
			//mysql_query($sQuery) or die(mysql_error() . ' line ' . __LINE__);
			$oDB->query($sQuery);


			if($bDoCreateDetailTable) { 
				/*
				$sSubQuery = ' CREATE TEMPORARY TABLE tmp2014_sponsor_bonus_detail  AS  ';
				$sSubQuery .= sprintf(' SELECT  COUNT(DISTINCT customerID)  customers , originalDistributor medlid , LAST_DAY(signupDate) session ' ); 
				$sSubQuery .= ' FROM customer_info WHERE  customerID != "" AND customerID NOT LIKE "%0000" ';
				$sSubQuery .= sprintf(' AND DATE(signUpDate) BETWEEN "%s" AND "%s" ' , $s2015CustomerSignupFrom , $s2015CustomerSignupTo);
				$sSubQuery .= sprintf(' AND originalDistributor IN ("%s") ' , implode('","', $aNew2014Dis));
				$sSubQuery .= ' GROUP BY originalDistributor , LAST_DAY(signupDate) ';
				*/

				### 2012-07-16 add extra fields

				$sSubQuery = ' DROP TABLE IF EXISTS tmp2014_sponsor_bonus_detail  ';
				$aLogs[] = $sSubQuery;
				$oDB->query($sSubQuery);

				$sSubQuery = ' CREATE TABLE tmp2014_sponsor_bonus_detail  AS  ';
				$sSubQuery .= sprintf(' SELECT  COUNT(DISTINCT customerID)  customers , 000 deducted , originalDistributor medlid , LAST_DAY(signupDate) session , GROUP_CONCAT(customerID) signup_list ' ); 
				$sSubQuery .= ' FROM customer_info WHERE  customerID != "" AND customerID NOT LIKE "%0000" ';
				### 2014-02-04
				### $sSubQuery .= sprintf(' AND DATE(signUpDate) BETWEEN "%s" AND "%s" ' , $s2015CustomerSignupFrom , $s2015CustomerSignupTo);
				$sSubQuery .= sprintf(' AND signUpDate BETWEEN "%s" AND "%s" ' , $s2015SponsorPointFrom , $s2015SponsorPointTo);
				$sSubQuery .= sprintf(' AND originalDistributor IN ("%s") ' , implode('","', $aNew2014Dis));
				$sSubQuery .= ' GROUP BY originalDistributor , LAST_DAY(signupDate) ';

				$aLogs[] = ' sub_detail ';	
				$aLogs[] = $sSubQuery;

				$oDB->query($sSubQuery);

		###########################################################################
		### 2012-07-16 [PATCH]
		###########################################################################
				////  NO NEED TO PUT HERE ????????????? mysql_query($sToscanaDeductQuery) or die(mysql_error());



				// now LEFT JOIN back to da_reference, to get the sponsor_id , and insert into _tmp_qualifications
				// **** NOTE , since distributor may quit , use JOIN instead of LEFT JOIN
				// NOTE "raw_sponsor" always be equal to  "sponsor_point" , since the maximum will be treat in total , no per each month
				$sSubQuery = sprintf(' INSERT INTO %s (medlid , session , raw_sponsor , sponsor_point , last_cus , last_vp_date ) '  ,$s2015TempDetailTableName);
				$sSubQuery .= sprintf(' SELECT sponsor_id , session , SUM(customers) raw_sponsor  , SUM(customers)  sponsor_point  ') ;
				
		//		

			//	$sSubQuery .=  sprintf(' IF( LEAST(customers, %d - SUM(deducted) ) <0 , 0 , LEAST(customers, %d - SUM(deducted) )) sponsor_point  ' ,  )
					
				$sSubQuery .= sprintf(', DATE("0000-00-00") , DATE("0000-00-00") FROM  tmp2014_sponsor_bonus_detail  t JOIN %s  da ON t.medlid = da.Reference GROUP BY sponsor_id , session ' ,$sDaAllTableName); 

				$aLogs[] = ' sub_detail ';	
				$aLogs[] = $sSubQuery;
				$oDB->query($sSubQuery);
				

				// now updating sorting , base on main temp table
				global $a2015QRules;

				reset($a2015QRules);
				$aSelected = array();
				while(list($sRuleName,  $sRuleCode) = each($a2015QRules)) {
					$aSelected[] =  sprintf(' %s %s ' , $sRuleCode , $sRuleName);
				}

				$sSubQuery = sprintf(' SELECT %s , medlid , MAX(name) name , %s  , IF(last_cus > last_vp_date , last_cus , last_vp_date)  dates ' , implode(' , ' ,$aSelected) ,  implode(' + ' ,array_values($a2015QRules)) );
				$sSubQuery .= sprintf('  FROM %s  GROUP BY medlid  ' ,$s2015TempTableName) ;
				$aLogs[] = $sSubQuery;
				
				$bAsArray = true;
				$aList = $oDB->query($sSubQuery , null , $bAsArray);
				

				$aLogs[] = sprintf(' D) => %d rows selected ', count($aList));
				$aLogs[] = '============================================';

				while( list($sIndex, $aRow) = each($aList)) {

					$nPoints = 0;

					if($aRow[3] > $nMaximumSponsorPoints ){ // Rule.D , never greater than  $nMaximumSponsorPoints
						$aRow[3] = $nMaximumSponsorPoints;			
					}


					$aDetail = array();			
					for($k=0;$k<count($a2015QRules);$k++) {
						$aDetail[] = intval($aRow[$k]); 
						$nPoints += $aRow[$k];
					}
					$aLogs[] = implode(' , ', $aDetail);

					$sUpdateQuery = sprintf(' UPDATE %s SET sorting = "%06d_%s" WHERE medlid = "%s" ' ,  $s2015TempDetailTableName , $nPoints , $aRow['dates'] , $aRow['medlid']) ;
					$aLogs[] = $sUpdateQuery;
					$oDB->query($sUpdateQuery);
					//mysql_query($sUpdateQuery) or die(mysql_error() . ' line ' . __LINE__);
				
				}

				$aLogs[] = '============================================';

			}

	} // END [HUGE-IF-001]

	$aLogs[] = '<strong>E. ===============================================</strong>';
	$aLogs[] = '<strong style="color:red;">Extra New Sup Points</strong>';

	// supervisor for 2014 list added on 2012-09-12
	$aNewSup = array(
		// '35XXXXXXXX' => array('name' => 'Eva & Niels Mark' , 'session' =>  '2011-06-30' , 'points' => 10)
	);




	reset($aNewSup);
	$aSubLog = array();
	$aSubSubLog = array();
	while(list($sKey,$aRow)= each($aNewSup)) {

			// overwriting $sKey with the medlid for duplicate medlid
			if(!empty($aRow['medlid'])) {
				$sKey = $aRow['medlid'];
			}

			$nPoints = empty($aRow['points'])?constant('MAXIMUM_NEWSUP_POINT'):$aRow['points'];
		
			$sQuery = sprintf(' INSERT INTO %s (code , medlid , name , newsup_point ,newsup_session)  VALUES("Extra New Sup Point" , "%s" , "%s" ,"%s" , "%s") '  ,$s2015TempTableName , $sKey , $aRow['name'] , $nPoints  ,$aRow['session']);
			$aSubLog[] = $sQuery;

			//mysql_query($sQuery) or die(mysql_error() . ' line ' . __LINE__);
			$oDB->query($sQuery);


			if($bDoCreateDetailTable) {
				$aTemp = array(
					'medlid' => $sKey
					, 'name' => $aRow['name']
					, 'session'  => $aRow['session'] // use existing session
					, 'newsup_point' => $nPoints // constant('MAXIMUM_NEWSUP_POINT')
				);
				$sQuery = sprintf(' INSERT INTO %s (%s) VALUES("%s") ' , $s2015TempDetailTableName , implode(",", array_keys($aTemp)) , implode('","', array_values($aTemp)) );
				//mysql_query($sQuery) or die(mysql_error() . ' line ' . __LINE__);
				$oDB->query($sQuery);
				$aSubSubLog[] = $sQuery;
			}
	}

	$aLogs[] = implode($aSubLog , ';<br />');
	$aLogs[] = ' detail <br />' . implode($aSubSubLog , ';<br />');


	// R3 :for every new signup distributor after 2015-05-01 , get 1 vp
	$aWhere = array(
		'signupDate' => sprintf(' g_date BETWEEN "%s" AND "%s" ' , $s2015DirectSponsorFrom , $s2015DirectSponsorTo)
		, 'accountType' => 'accountType IN ("providi" , "sc-support") ' // MAY need to remove
	);
	$aQ2015Con['direct_sponsor'] = $aWhere;

	$aLogs[] = '<strong>F. ===============================================</strong>';
	$aLogs[] = '<span style="color:red;font-weight:bold;">Direct Sponsor</span>';
	$aLogs[] = '<strong style="color:brown;"> updtaed on 2015-09-04 ,  change +1 to +3 from those downline who signed up after 2015-08-24</strong>';

	$sQuery = sprintf(' INSERT INTO %s(code , medlid , raw_direct_sponsor , direct_sponsor , last_direct_sponsor  , name ) ' , $s2015TempTableName);
//	2015-09-04 , additional rules
//	$sQuery .= sprintf(' SELECT "direct_sponsor" , sponsor_id medlid , COUNT(DISTINCT Reference) direct_sponsor , MAX(g_date) last_direct_sponsor , MAX(sponsor) name FROM da_reference WHERE %s  GROUP BY sponsor_id ' , implode(' AND ' ,$aWhere)) ;

	$sQuery .= sprintf(' SELECT "direct_sponsor" , sponsor_id medlid , COUNT(DISTINCT Reference) raw_direct_sponsor ,   SUM(IF(g_date>= "%s" , 3 , 1)) direct_sponsor , MAX(g_date) last_direct_sponsor , MAX(sponsor) name FROM da_reference WHERE %s  GROUP BY sponsor_id ' , $sAugustNewRuleFrom , implode(' AND ' ,$aWhere)) ;

	$aLogs[] = $sQuery;
	//mysql_query($sQuery) or die(mysql_error() . '<hr />' . __LINE__);
	$oDB->query($sQuery);

	if($bDoCreateDetailTable) { 
		$aLogs[] = ' sub_detail ';
		/* $sSubQuery = sprintf(' INSERT INTO %s(code , medlid , session  ,direct_sponsor , last_direct_sponsor , name) ' , $s2015TempDetailTableName);
		$sSubQuery .= sprintf(' SELECT "direct_sponsor" , sponsor_id medlid , LAST_DAY(g_date) session , COUNT(DISTINCT Reference) direct_sponsor  , MAX(g_date) last_direct_sponsor , MAX(sponsor) name  FROM da_reference WHERE  %s  GROUP BY sponsor_id , LAST_DAY(g_date) ' , implode(' AND ' ,$aWhere)) ;
		*/
		$sSubQuery = sprintf(' INSERT INTO %s(code , medlid , session  , raw_direct_sponsor , direct_sponsor , last_direct_sponsor , name) ' , $s2015TempDetailTableName);
		$sSubQuery .= sprintf(' SELECT "direct_sponsor" , sponsor_id medlid , LAST_DAY(g_date) session , COUNT(DISTINCT Reference) direct_sponsor ,  SUM(IF(g_date>= "%s" , 3 , 1)) direct_sponsor  , MAX(g_date) last_direct_sponsor , MAX(sponsor) name  FROM da_reference WHERE  %s  GROUP BY sponsor_id , LAST_DAY(g_date) ' , $sAugustNewRuleFrom , implode(' AND ' ,$aWhere)) ;
		
		$aLogs[] = $sSubQuery;

		// mysql_query($sSubQuery) or die(mysql_error() . '<hr />' . $sSubQuery);
		$oDB->query($sSubQuery);
	}





if(!empty($a2015QRules['sponsor_webpakke'])) {
	$aLogs[] = '<strong>G. ===============================================</strong>';
	$aLogs[] = '<strong style="color:red;">Sponsor Webpakke</strong>';
	$aLogs[] = '<strong style="color:brown;">updated , those downline who buy webpakke after 2015-08-24 , got +5 points in stead of +3 </strong>';
	$aWhere = array(
		'ordreType	'=> ' ordretype IN ("providi_webpakken" ) ' 
		, 'fakturan' => '  fakturanr > 0  ' 
		, 'medlid' => ' medlid != "0" '
	);
	$aHaving = array(
		'validDate' => sprintf(' DATE(webpakkeDate) BETWEEN "%s" AND "%s"  '  , $s2015webpakkeSponsorFrom , $s2015webpakkeSponsorTo)
	);

	$sQuery = ' DROP TABLE IF EXISTS TEMP_spw ';
	$aLogs[] = $sQuery;
	$oDB->query($sQuery);
	//mysql_query($sQuery) or die(mysql_error() . __LINE__);

	
	// this can be used as detail already
	$sQuery = sprintf(' SELECT MIN(mdato) webpakkeDate , MAX(abonnementer.Navn) name, MAX(da_reference.sponsor) dis_name , medlid webpakker , MAX(itemType) itemType , MAX(sponsor_id) medlid FROM abonnementer LEFT JOIN da_reference ON abonnementer.medlid = da_reference.Reference WHERE %s GROUP BY abonnementer.medlid HAVING %s ' , implode(' AND ' ,$aWhere) , implode(' AND ', $aHaving));
	$sQuery = ' CREATE TABLE  TEMP_spw AS ' . $sQuery;
	$aLogs[] = $sQuery;
	$oDB->query($sQuery);
	// mysql_query($sQuery) or die(mysql_error() . __LINE__);


/*	$sQuery = ' SELECT "webpakkes" code , medlid , MAX(name) name , COUNT(webpakker)webpakker , COUNT(webpakker) * 3 webpakker_points , MAX(webpakkeDate) last_webpakkers  '
					. ' 	FROM TEMP_spw  GROUP BY medlid  ';
*/
	// 2015-09-04  , adding August condition

	$sQuery = sprintf(' SELECT "webpakkes" code , medlid , MAX(dis_name) name , COUNT(webpakker)webpakker , SUM(  IF( DATE(webpakkeDate) >= "%s" , 5 , 3 ))  webpakker_points , MAX(webpakkeDate) last_webpakkers  ' , $sAugustNewRuleFrom)
					. ' 	FROM TEMP_spw  GROUP BY medlid  ';
	$sQuery = sprintf('INSERT INTO %s(code , medlid , name , webpakker , webpakker_points , last_webpakkers) ' , $s2015TempTableName)  . $sQuery;
	$aLogs[] = $sQuery;
	
	//$oRS = mysql_query($sQuery) or die(mysql_error() . __LINE__);
	// echo $sQuery . '<hr />' . mysql_num_rows($oRS);
	$oDB->query($sQuery);


	if($bDoCreateDetailTable) { 
		/*$sSubQuery = ' SELECT "webpakkes" code , medlid , MAX(name) name , COUNT(webpakker)webpakker , COUNT(webpakker) * 3 webpakker_points , DATE(webpakkeDate) session , MAX(webpakkeDate) last_webpakkers  '
					. ' 	FROM TEMP_spw  GROUP BY medlid  ';
		*/
		/*
		$sSubQuery = sprintf(' INSERT INTO %s( medlid , session  ,name , webpakker , webpakker_points) ' , $s2015TempDetailTableName) 
					. ' SELECT medlid , LAST_DAY(webpakkeDate) session , MAX(name) name , COUNT(webpakker) webpakker  , COUNT(webpakker) * 3 webpakker_points FROM  TEMP_spw  GROUP BY medlid , LAST_DAY(webpakkeDate) ';
		*/
		// August Condition

		$sSubQuery = sprintf(' INSERT INTO %s( medlid , session  ,name , webpakker , webpakker_points) ' , $s2015TempDetailTableName) 
					// . sprintf(' SELECT medlid , LAST_DAY(webpakkeDate) session , MAX(name) name , COUNT(webpakker) webpakker  , SUM( IF(DATE(webpakkeDate) >= "%s" , 5 , 3 )) webpakker_points FROM  TEMP_spw  GROUP BY medlid , LAST_DAY(webpakkeDate) ',$sAugustNewRuleFrom) ;

					. sprintf(' SELECT medlid , LAST_DAY(webpakkeDate) session , MAX(dis_name) name , COUNT(webpakker) webpakker  , SUM( IF(DATE(webpakkeDate) >= "%s" , 5 , 3 )) webpakker_points FROM  TEMP_spw  GROUP BY medlid , LAST_DAY(webpakkeDate) ',$sAugustNewRuleFrom) ;

		$aLogs[] = ' sub_detail ';	
		$aLogs[] = $sSubQuery;

		//$oSubRS = mysql_query($sSubQuery) or die(mysql_error() . __LINE__);
		$oDB->query($sSubQuery);

	}
}
### H. Professional Rank #######
if(!empty($a2015QRules['professional_rank'])) {
	$aLogs[] = '<strong>H. ===============================================</strong>';
	$aLogs[] = '<strong style="color:red;">Professional Rank</strong>';
	$aWhere = array(
		'earningRank' => ' earningRank = "professional" '
		, 'username' => ' username != "" AND password != ""  '
	);

	$aQ2015Con['professional_rank'] = $aWhere;
	$sQuery = sprintf(' INSERT INTO %s(code , medlid , name , professional_rank) ' , $s2015TempTableName);
	$sQuery .= sprintf(' SELECT "professional_rank" code , Reference medlid  , Navn name, 25 professional_rank FROM da_reference WHERE %s ' , implode(' AND ', $aWhere));
	$aLogs[] = $sQuery;
	//mysql_query($sQuery) or die(mysql_error() . '<hr />' . $sQuery);
	$oDB->query($sQuery);

	if($bDoCreateDetailTable) {
		$sSubQuery = sprintf(' INSERT INTO %s(code , medlid , session , name , professional_rank) ' , $s2015TempDetailTableName);
		$sSubQuery .= sprintf(' SELECT "professional_rank" code , Reference medlid  , LAST_DAY(CURDATE()) session , Navn name, 25 professional_rank FROM da_reference WHERE %s ' , implode(' AND ', $aWhere));
		$aLogs[] = $sSubQuery;
		$oDB->query($sSubQuery);
		// mysql_query($sSubQuery) or die(mysql_error() . '<hr />' . $sSubQuery);
	
	}
}
### H. Professional Rank #######

// 2015-09-04
### I. New Distribuor Webpakker #######
if(!empty($a2015QRules['new_dis_webpakke'])) {
	$aLogs[] = '<strong>I. ===============================================</strong>';
	$aLogs[] = '<strong style="color:red;">New Distributor Webpakke</strong>';
	$aLogs[] = '<strong style="color:brown;">... aded on 2015-09-04</strong>';


	// now $aNewProvidiDis , contains list of the "new" distributor

	if(count($aNewProvidiDis) >  0) {
		$aWhere = array(
			'medlid' => sprintf(' medlid IN ("%s") ' , implode('", "', $aNewProvidiDis))
			, 'fakturanr' => ' fakturanr > 10 '
			, 'ordreType' => ' ordretype IN ("providi_webpakken" , "webpakke_advance") '
		);
		// ndw = new distributor webpakke
		$aQ2015Con['ndw'] = $aWhere;

		$sQuery = sprintf(' SELECT "new_dis_webpakke" code , 5 new_dis_webpakke , medlid , MAX(Navn) name FROM abonnementordrer WHERE %s  GROUP BY medlid HAVING DATE(MIN(mdato)) >= "2015-01-01" ' , implode(' AND ' ,$aWhere));
		$sQuery = sprintf(' INSERT INTO %s(code , new_dis_webpakke , medlid , name ) %s ', $s2015TempTableName , $sQuery);
		$aLogs[] = $sQuery;
		//mysql_query($sQuery) or die(mysql_error());
		$oDB->query($sQuery);

		if($bDoCreateDetailTable) { 
			$sQuery = sprintf(' SELECT "new_dis_webpakke" code , 5 new_dis_webpakke , medlid , MAX(Navn) name ,  LAST_DAY(MIN(mdato)) FROM abonnementordrer WHERE %s  GROUP BY medlid HAVING DATE(MIN(mdato)) >= "2015-01-01" ' , implode(' AND ' ,$aWhere));
			$sQuery = sprintf(' INSERT INTO %s(code , new_dis_webpakke , medlid , name , session ) %s ', $s2015TempDetailTableName , $sQuery);
			$aLogs[] = 'sub_detail';
			$aLogs[] = $sQuery;
			//mysql_query($sQuery) or die(mysql_error());
			$oDB->query($sQuery);

		}
			
	}

}

### I. New Distribuor Webpakker #######

$aNiceName = array();
if($b2015UseNiceName) {
	// select the target info
	$sQuery = sprintf(' SELECT DISTINCT medlid FROM %s ' , $s2015TempTableName);
	/*$oRS = mysql_query($sQuery) or die(mysql_error() . ' line ' . __LINE__);
	$aTemp = array();
	while($aRow = mysql_fetch_assoc($oRS)) {
		$aTemp[] = $aRow['medlid'];
	}*/
	$aTemp = $oDB->getCOlumn($sQuery);

	//$sQuery = sprintf(' SELECT Navn name , hbl_id medlid FROM  refkunde WHERE hbl_id IN ("%s") ' , implode('","', $aTemp));
	$sQuery = sprintf(' SELECT Navn name , Reference medlid FROM  da_reference WHERE Reference  IN ("%s") ' , implode('","', $aTemp));
	/*$oRS = mysql_query($sQuery) or die(mysql_error() . ' line ' . __LINE__);
	// fecth the nice name into $aNiceName
	while($aRow = mysql_fetch_assoc($oRS)) {
		$aNiceName[ sprintf('%s',strtolower($aRow['medlid'])) ] = $aRow['name'];
	}*/

	$oRow = $oDB->getObject($sQuery);
	if(!empty($oRow)) {
		$aNiceName[ sprintf('%s',strtolower($oRow->medlid)) ] = $oRow->name;
	}


} // end if nicename


} // end bDoCreateTable










// now make the to make display name more "nice" , use the name from refkunde -*-



//var_dump($aNiceName);



function q2015_getOrgPoints($nTimes ,$sMode='cumulative') {

	if($nTimes >= 6) {
		return $sMode=='cumulative' ? 21  : 6;
	} else if($nTimes == 5) {
		return $sMode=='cumulative' ? 15  : 5;
	} else if($nTimes == 4) {
		return $sMode=='cumulative' ? 10  : 4;
	} else if($nTimes == 3) {
		return $sMode=='cumulative' ? 6  : 3;
	} else if($nTimes == 2) {
		return $sMode=='cumulative' ? 3  : 2;
	} else if($nTimes == 1) {
		return $sMode=='cumulative' ? 1  : 1;
	} 
	return 0;
	/*
	if($nTimes <= 3) {
		return $sMode=='cumulative' ? $nTimes * 2 : 2;
	} else if($nTimes <= 6) {
		return $sMode=='cumulative' ? (($nTimes - 3) * 3) + 6: 3;
	} else if($nTimes <= 9) {
		return $sMode=='cumulative' ? (($nTimes - 6) * 4) + 15:4;
	} else if($nTimes <= 12) {
		return $sMode=='cumulative' ? (($nTimes - 9) * 5) + 27:5;
	}
	*/
	return 0;

}


function _getNewProvidiDistributor2015() {
	global $oDB;

	$aWhere = array(
		'g_date' => ' g_date >= "2015-01-01" '
	);
	$sQuery = sprintf(' SELECT Reference FROM da_reference WHERE %s ' , implode(' AND ' ,$aWhere));
	return $oDB->getColumn($sQuery);
}



?>