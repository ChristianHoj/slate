<?php // // æ

define('PROVIDI_ADMIN_EMAIL' , 'providi.devlog@gmail.com');
define('PROVIDI_ISO8601_DATETIME_FORMAT', 'Y-m-d\TH:i:sP');

define('PROVIDILIB_USE_ORIGINAL_TIMEZONE' , -1);

class providiUnauthorizeException extends Exception {
}
class providiBadRequestException extends Exception {
}
class providiMethodNotAllowedException extends Exception {
}






function providiRegion($sRegionCode) {
	global $oDB;
	$aWhere = array(
		'regionCode' => sprintf(' regionCode = "%s" ', $oDB->esc($sRegionCode))
		, 'isActive' => ' isActive = 1 '
		, 'deletedOn' => 'deletedOn = "0000-00-00" '
	);
	$sQuery = sprintf(' SELECT id , regionCode , regionDescription , regionTimezone FROM providi_region_timezones WHERE %s ' , implode(' AND ',$aWhere));
	$oProvidiTZ = $oDB->getObject($sQuery);

	if(empty($oProvidiTZ)) {
		throw new providiBadRequestException(sprintf('Invalid region code - %s' , $sRegionCode) , 101);
	}
	return $oProvidiTZ;
}
function providiGetDefaultRegion() {
	$oProvidiTZ = new stdClass();
	$oProvidiTZ->regionCode = 'DK';
	$oProvidiTZ->regionDescription = 'Europe/Copenhagen';
	$oProvidiTZ->regionTimezone = '+01:00';
	return $oProvidiTZ;
}

function providiTrimSpaces($sString) {
	$sString = trim($sString);
	$sString = str_ireplace('  ', ' ', $sString);
	return $sString;
}

function providiDateTime($sDateText,$sMode='text' , $sTZ=null) {

	$sDateText = providiTrimSpaces($sDateText );
	$oDate = new DateTime($sDateText);

	// convert to Denmark TZ +1.0

	if($sTZ !== constant('PROVIDILIB_USE_ORIGINAL_TIMEZONE')) {
		if(is_null($sTZ)) {
			$sTZ = 'Europe/Copenhagen';
		}
		$oTZ = new DateTimeZone($sTZ);
		$oDate->setTimeZone($oTZ);
	}


	if($sMode == 'text') {
		return $oDate->format(constant('PROVIDI_ISO8601_DATETIME_FORMAT'));
	}
	if(strtoupper($sMode) == 'SQL') {
		return $oDate->format('Y-m-d H:i:s');
	}
	return $oDate;
}

/// not finished yet, maybe need to implement the timezone stuff
function providiResponseDateTime($sDateText) {
	providiGetCurretUserrTimezone();
	return providiDateTime($sDateText , 'text' , providiGetCurretUserrTimezone());
}
function providiGetCurretUserrTimezone() {
	global $oAuth;

	if(!empty($oAuth)) {
		$sTZ = $oAuth->regionTimezoneCode;
	}

	if(empty($sTZ)) {
		$sTZ = 'Europe/Copenhagen';
	}
	return $sTZ;
}


function providiMail($sTo, $sSubject , $sContent , $sHeader=null) {
	if($_SERVER['HTTP_HOST']=='127.0.0.1') {
		$sRand = sprintf('%05d', rand(1, 99999));
		//$sFileName = sprintf('%s_%s_%s.html' , date('YmdHis') , substr($sSubject , 0 , 10) , $sRand);
		$sFileName = sprintf('%s_%s.html' , date('YmdHis') , $sRand);
		file_put_contents("c:\\_email\\" . $sFileName , $sTo . '<br /><br />' . $sSubject . '<br /><br />'  . $sContent . '<br /><br />' . $sHeader);
		return true;
	}
	if(is_null($sHeader)) {
		$sHaeder = 'MIME-Version: 1.0' . "\n";
		$sHaeder .= 'Content-type: text/html; charset=UTF-8' . "\n";
	}
	return @mail($sTo, $sSubject , $sContent , $sHeader);

}
function providiDie($sText) {
	die($sText);
}



function providiNotifyDie( $sMessage , $sSubject  , $sFile ,$eException=null) {
	$sTo = constant('PROVIDI_ADMIN_EMAIL');
	if(empty($sFile)) {
		$sFileName = basename($_SERVER['REQUEST_URI']);
		$sFileName = substr($sFileName , 0 , strpos($sFileName , '?'));
		if(strlen($sFileName) >= 20) {
			$sFileName = substr($sFileName , 0 , 20);
		}
		$sFile = sprintf('[req] %s' , $sFileName);
	}
	if(empty($sSubject)) {
		$sSubject = sprintf('%s has generated the notify on %s ', $sFile , date('Y-m-d H:i:s'));
	} else {
		$sSubject = sprintf('[notify] %s on %s ' , $sSubject , date('Y-m-d H:i:s'));
	}

	ob_start();
	print '<PRE>';
	echo $sMessage . '<br /><br />';
	print_r($eException);

	printf('<p align="right">generated by %s on %s</p>' , __FILE__ , date('Y-m-d H:i:s'));
	providiMail($sTo, $sSubject , ob_get_clean());

	$sDisplayText = $sMessage;
	if(!empty($sException)) {
		$sDisplayText  = sprintf('Fatal Error [%s] : %s ', $eException->getCode()  , $eException->getMessage());
	}

	providiDie($sDisplayText);
}




function providiJSONErrorHandler(&$oResponse , $e)  {
	$oResponse = new stdClass();
	$oException = new stdClass();
	// CBH: Place error message in message attribute instead of status since it might be overwritten
	$oException->message = sprintf('%d %s',$e->getCode() , $e->getMessage());
	if(isset($_REQUEST['debug'])) {
		print '<PRE>';
		var_dump($oResponse);
		var_dump($e);
	}
	if(is_a($e,'providiUnauthorizeException')){
		$oException->status = sprintf('401 Unauthorized');
	}
	if(is_a($e,'providiBadRequestException')){
		$oException->status = sprintf('400 Bad Request');
	}

	// CBH: Allow errors to be returned
	header("Content-Type: Application/JSON;charset=utf-8");
	header("Access-Control-Allow-Origin: *");
	$oResponse->errors = array($oException);
	return $oResponse;

}
function providiJSONResponse($oResponse) {
	header("Content-Type: Application/JSON;charset=utf-8");
	header("Access-Control-Allow-Origin: *");
	$sResponse = json_encode($oResponse);
	if($sResponse === false ) {
		die('500 Internal Server Error');
	}
	echo $sResponse;
	//echo json_encode(js_utf8_encode($oResponse));
}
function providiGetDistributorImageURL($sText) {
		global $aProvidiConfigs;
		if(empty($sText)) {
			$sImage = $aProvidiConfigs['URL_live_site'] . 'images_forhandlere/na.png';
		} else {
			$sImage = sprintf('%simages_forhandlere/%s', $aProvidiConfigs['URL_live_site'] , $sText);
		}
		return $sImage;
}

function providiGetDistributorInfo($sProvidiID) {
	global $oDB;
	$sQuery = sprintf(' SELECT Reference providiID , Navn name , partner_navn partnerName , Email email , image , leadershipRank , earningRank , sponsor , sponsor_id sponsorID , username FROM da_reference WHERE Reference = "%s" LIMIT 1 ' ,  $oDB->esc($sProvidiID));
	$oRow = $oDB->getObject($sQuery , 'providiBlank');

	if(empty($oRow)) {
		return false;
	}
	$oRow->image = providiGetDistributorImageURL($oRow->image);
	return $oRow;

}


function providiToDanishDate($sTheDate,$sSepBy=null,$bTrimLeadingZero=false) {
	if(is_null($sSepBy)) {
		$sSepBy = '/';
	}
	$sTheDate = str_replace( array(' ' , '-' , '/', ':') , array('','','','') , $sTheDate);
	$sMonth = substr($sTheDate, 4 , 2);
	if(substr($sMonth, 0,1) == '0' && $bTrimLeadingZero) {
		$sMonth = substr($sMonth , 1 , 1);
	}
	$sDay = substr($sTheDate, 6 , 2);
	if(substr($sDay, 0,1) == '0' && $bTrimLeadingZero) {
		$sDay = substr($sDay , 1 , 1);
	}

	return sprintf('%s%s%s%s%s' , $sDay , $sSepBy, $sMonth , $sSepBy, substr($sTheDate, 0 , 4));

}
function providiToSystemDate($sTheDate,$sSepBy=null,$bTrimLeadingZero=false) {
	if(is_null($sSepBy)) {
		$sSepBy = '-';
	}
	$sTheDate = str_replace( array(' ' , '-' , '/', ':') , array('','','','') , $sTheDate);
	$sMonth = substr($sTheDate, 2 , 2);
	if(substr($sMonth, 0,1) == '0' && $bTrimLeadingZero) {
		$sMonth = substr($sMonth , 1 , 1);
	}
	$sDay = substr($sTheDate, 0 , 2);
	if(substr($sDay, 0,1) == '0' && $bTrimLeadingZero) {
		$sDay = substr($sDay , 1 , 1);
	}

	return sprintf('%s%s%s%s%s' , substr($sTheDate, 4 , 4) , $sSepBy, $sMonth  , $sSepBy , $sDay );
}

function getProvidiAuthorizeHash($sMedlid , $nVU=null){
	if(empty($nVU)) {
		$nVU = time() + (60 * 60 * 3);
	}
	return md5(sprintf('%s Avengers @ssemble! %s 2015' ,  $sMedlid , $sVU));
}

function providiCheckEmptyString($sTheString) {
	if($sTheString == '""') {
		return '';
	}
	return $sTheString;
}

function providiPostBody() {
	$s = @file_get_contents('php://input');
	$aPOST = array();
	parse_str($s , $aPOST);
	return $aPOST;
}


function providiGetCurrentAuthID() {
	global $oAuth;

	if(isset($oAuth)) {
		return $oAuth->getProvidiID();
	}
	return '-';
}

function providiISOtoUTF($sTheText) {
	return providiISO2UTF($sTheText);
}
function providiISO2UTF($sTheText) {
	return utf8_encode($sTheText);
}
function providiUTFtoISO($sTheText) {
	return providiUTF2ISO($sTheText);
}
function providiUTF2ISO($sTheText) {
	return utf8_decode($sTheText);
}

?>
