<?php


require_once dirname(__FILE__) . '/qualifications/DK.qualification2015.php';
global $s2015TempTableName , $a2015QRules , $oDB;
global $aQualificationList;
$aQualificationList = array();



$nNumbersShown = 10;
reset($a2015QRules);
$aSelected = array();
while(list($sRuleName,  $sRuleCode) = each($a2015QRules)) {
	$aSelected[] =  sprintf(' %s %s ' , $sRuleCode , $sRuleName);
}

$aExtraWhere = array(
	'no_jan_madsen' => ' medlid != "22133045" '
);

$sQuery = sprintf(' SELECT medlid , MAX(name) name , %s  ,   (%s) points' , implode(' , ' ,$aSelected) ,  implode(' + ' ,array_values($a2015QRules)) );
$sQuery .= sprintf('  FROM  %s WHERE %s GROUP BY medlid ORDER BY points DESC  ,  IF(last_cus > last_vp_date , last_cus , last_vp_date) DESC , last_meet_id DESC LIMIT %d ' ,$s2015TempTableName , implode(' AND ' ,$aExtraWhere) , $nNumbersShown);

$aList = $oDB->query($sQuery);
for($i=0;$i<count($aList);$i++) {
	$oDis = new stdClass();
	$oDis->position = $i+1;
	$oDis->name = providiTrimSpaces($aList[$i]->name);
	$oDis->points = $aList[$i]->points;

	$oTempDis = new ProvidiDistributor($oDB);
	$oTempDis->loadFromProvidiID( $aList[$i]->medlid );
	$oDis->image = $oTempDis->getProfileImageURL();
	
	$aQualificationList[] = $oDis ;
}

?>