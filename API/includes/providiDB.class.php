<?php
class providiBlank extends stdClass {
	function __construct($oIgnored=null) {
	}
}


class ProvidiDB {
	var $oMysql , $oRS , $sQuery , $aQ;
	function esc($sText) {
		return mysqli_real_escape_string($this->oMysql , $sText);
	}
	function __construct() {
		global $aProvidiConfigs;
		$this->aQ = array();
		try {
			$oMysql = @mysqli_connect($aProvidiConfigs['DB_host'] , $aProvidiConfigs['DB_username'], $aProvidiConfigs['DB_password'] ,  $aProvidiConfigs['DB_dbname']);
			if(!is_a($oMysql , 'mysqli')) {
				throw new Exception('Cannot connect to database'   , 1);
			}
			$this->oMysql = $oMysql;
		} catch(Exception $e) {
			providiNotifyDie( 'Cannot connect to database' , 'Error initializing database connection in ' . __FILE__  , __FILE__ , $e );
		}
	}


	function displaySQLErrorAndDie($nErrNo ,$sErrMessage) {
		printf('<div style="width:100%%;color:#000000;font-family:Tahoma;fon-size:14px;padding:10px;background:#a80000;"><strong>SQL error [%d] : %s</strong> <hr />query : <strong>%s<strong> </div>' , $nErrNo , $sErrMessage , $this->sQuery);
		die('');
	}


	function _execute($sQuery) {
		$this->sQuery = $this->aQ[] = $sQuery;
		$oRS = mysqli_query($this->oMysql , $sQuery);
		if(empty($oRS)) {
			$this->displaySQLErrorAndDie(mysqli_errno($this->oMysql) , mysqli_error($this->oMysql));
		}
		$this->oRS = $oRS;
		return $oRS;

	}
	function numRows() {
		return mysqli_num_rows($this->oRS);
	}
	function affectedRows() {
		return mysqli_affected_rows($this->oMysql);
	}
	function insertID() {
		return mysqli_insert_id($this->oMysql);
	}

	function query($sQuery, $sClassName=null , $bFecthAsArray=false) {
		$oRS = $this->_execute($sQuery);
		$aReturn = array();
		if(is_null($sClassName)) {
			$sClassName = 'stdClass';
		}
		if($bFecthAsArray) {
			while($oNode = @mysqli_fetch_array($oRS)) {
				$aReturn[] = $oNode;
			}

		} else {
			while($oNode = @mysqli_fetch_object($oRS, $sClassName)) {
				$aReturn[] = $oNode;
			}

		}
		return $aReturn;
	}
	function getHash($sQuery) {
		$oRS = $this->_execute($sQuery);
		$aReturn = array();
		while($aNode = mysqli_fetch_array($oRS)) {
			$aReturn[ $aNode[0] ] = $aNode[1];
		}
		return $aReturn;
	}
	function getColumn($sQuery) {
		$oRS = $this->_execute($sQuery);
		$aReturn = array();
		while($aNode = mysqli_fetch_array($oRS)) {
			$aReturn[] = $aNode[0];
		}
		return $aReturn;
	}
	function getRow($sQuery) {
		$oRS = $this->_execute($sQuery);
		return @mysqli_fetch_assoc($oRS);
	}
	function getObject($sQuery , $sClassName=null , $aParams=null) {

		if(is_null($aParams)) {
			$aParams = array();
		}

		if(empty($sClassName)) {
			$sClassName = 'providiBlank';
		}


		$oRS = $this->_execute($sQuery);
		return mysqli_fetch_object($oRS , $sClassName, $aParams);
	}

	function getVar($sQuery) {
		$oRS = $this->_execute($sQuery);
		$aReturn = array();
		@$aNode = mysqli_fetch_array($oRS);
		return $aNode[0];
	}

}

?>
