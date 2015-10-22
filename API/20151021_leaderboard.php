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

	if(empty($_GET['period']) || count($_GET['period']) == 0) {
		throw new providiBadRequestException('Invalid request parameter - period expected' , 5103);
	}

	$oResponse->id = $oAuth->providiID;
	$oResponse->type = 'leaderboards';

	require_once './includes/providiStatistic.class.php';

	$oStat = new providiStatistic($oDB);

	$oResponse->attributes = $oStat->VSmemberLeaderboard($aGET['period'] , @$aGET['country']);

} catch (Exception $e) {
	providiJSONErrorHandler($oResponse , $e);
}

if(isset($_GET['debug'])) {
	print '<PRE>';
	print_r($oResponse);	
}
providiJSONResponse($oResponse);

?>