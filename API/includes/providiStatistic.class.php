<?php



class providiStatistic extends providiList{

	static function getMysqlAliasFromText($sText) {
		$sText = str_replace(' ', '-', $sText);
		$aText = explode('-', $sText);
		for($i=0;$i<count($aText);$i++) {
			$aText[ $i ] = ucwords(strtolower($aText[$i]));
		}
		return implode('', $aText);

	}
	static function getPeriodFromMode($sModeText, $sTheDate=null) {

		if(is_null($sTheDate)) {
			$sTheDate = time();
		} else {
			// $sTheDate = strtotime($sTheDate);
			// this providiDateTime() will converted into server time format
			$sTheDate = providiDateTime($sTheDate , 'SQL');

		}
		/*if(date('Y',$sTheDate) <1995) {
			throw new Exception('Invalid date parameter', 21);
		}*/

		switch($sModeText)	{
			case 'this-week'		:
											$sFromDate = strtotime('monday this week' , $sTheDate);
											$sToDate = strtotime('+6 DAY' , $sFromDate);
											return array('fromDate' => date('Y-m-d', $sFromDate) , 'toDate' => date('Y-m-d',$sToDate) , 'alias' => 'thisWeek');
			case 'last-week'		:	;
			case 'previous-week'	:	$sFromDate = strtotime('monday last week' , $sTheDate);
											$sToDate = strtotime('+6 DAY' , $sFromDate);
											return array('fromDate' => date('Y-m-d', $sFromDate) , 'toDate' => date('Y-m-d',$sToDate) , 'alias' => 'previousWeek');
			case 'last-30-days'	:	$sToDate = strtotime( date('Y-m-d' , $sTheDate));
											$sFromDate = strtotime('-30 DAY' , $sToDate);
											return array('fromDate' => date('Y-m-d', $sFromDate) , 'toDate' => date('Y-m-d',$sToDate) , 'alias' => 'last30Days');
		}
		return null;
	}

	function VSmemberLeaderboard($aRawMode=null , $sRegionCode = null, $nLimit = 10) {
		$oDB = $this->_oDB;
		$oReturn = new stdClass();

		if(is_null($aRawMode)) {
			$aRawMode = array(
				'this-week' , 'previous-week', 'last-30-days'
			);
		}

		$aModes = array();
		reset($aRawMode);
		$aWhere = array(
			'quickJump' => ' id >= 28000 '
			, 'notEmpty' => ' customerID != ""  '
			, 'notSelf' => 'customerID NOT LIKE "%0000"  '
			, 'onlyVS' => ' originalDistributor NOT LIKE "IV%" AND originalDistributor NOT LIKE "SK%" AND originalDistributor NOT LIKE "SS%" '
			, 'sameSource' => ' currentDistributor = originalDistributor  '
		);


		// while for each mode
		while(list($sUnusedKey, $sMode) = each($aRawMode)) {
			$aModeConfig = providiStatistic::getPeriodFromMode($sMode);
			if(empty($aModeConfig)) {
				continue;
			}

			$oNode = new stdClass();
			$oPeriod = new stdClass();
			$oPeriod->from  = providiDateTime($aModeConfig['fromDate']);
			$oPeriod->until  = providiDateTime($aModeConfig['toDate']);
			$sAlias = $aModeConfig['alias'];

			$oNode->period = $oPeriod;
			$oNode->positions = array();

			// only denmark get this rule , the rest return empty list
			if(strtoupper($sRegionCode) == 'DK') {
				$aWhere['signupDate'] =  sprintf(' DATE(signupDate) BETWEEN "%s" AND "%s" ' , $oDB->esc($aModeConfig['fromDate']), $oDB->esc($aModeConfig['toDate']));
				$sQuery = sprintf(' SELECT currentDistributor providiID , COUNT(*) recs FROM customer_info WHERE %s GROUP BY currentDistributor ORDER BY recs DESC , MAX(signupDate) DESC LIMIT %d ' , implode(' AND ',$aWhere), $nLimit);
				$aList = $oDB->query($sQuery);
			} else {
				$aList = array();
			}

			for($i=0;$i<count($aList);$i++) {
				$oPos = new stdClass();
				$oPos->position = $i+1;
				$oPos->newMembers = $aList[$i]->recs;
				$oDA = providiGetDistributorInfo($aList[$i]->providiID);
				$oPos->name = $oDA->name;
				$oPos->image = $oDA->image;
				$oNode->positions[] = $oPos;
			}

			$oReturn->$sAlias = $oNode;
		} // END while for each mode

		return $oReturn;
	}
/*
SELECT currentDistributor , COUNT(*) recs FROM customer_info WHERE id >= 28000 AND customerID != ""
	AND currentDistributor = originalDistributor
	AND DATE(signupDate) BETWEEN "2015-09-21" AND "2015-09-27" AND originalDistributor NOT LIKE "IV%" AND originalDistributor NOT LIKE "SK%" AND originalDistributor NOT LIKE "SS%" AND customerID NOT LIKE "%0000" GROUP BY currentDistributor ORDER BY recs DESC , MAX(signupDate) DESC LIMIT 10
*/

}


?>
