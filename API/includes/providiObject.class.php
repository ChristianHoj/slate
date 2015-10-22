<?php 

abstract class ProvidiObject  {
	var $_oDB;
	var $_sTableName , $_aValidFields;
	function __construct($oDB=null) {
		$this->_oDB = $oDB;
		$this->_setBaseTable();
		// $this->_getValidFields();		
	}

	private function _getValidFields() {
		global $oDB;
		$this->_aValidFields = array();
		$sQuery = sprintf(' DESC %s ' , $this->_sTableName);
		$this->_aValidFields = $this->_oDB->getColumn($sQuery);	
	}

	abstract function _setBaseTable();


	function merge($aParam) {
		if(empty($this->_aValidFields)) {
			$this->_getValidFields();
		}
		reset($aParam);
		while(list($sKey,$sValue) = each($aParam)) {
			if(in_array($sKey , $this->_aValidFields)) {
				$this->$sKey = $sValue;
			}
		}
		return $this->id;	
	}

	function load($nID) {
		$sQuery = sprintf(' SELECT * FROM %s WHERE id = "%s" LIMIT 1 ' , $this->_sTableName , $nID);
		$sClass = get_class($this);
		$oObj = $this->_oDB->getObject($sQuery , $sClass , array($this->_oDB));

		$this->merge($oObj);
		return $this->id;	
	}

	function save() {
		if(empty($this->_aValidFields)) {
			$this->_getValidFields();
		}


		if(empty($this->id)) {
			$aInsert = array();
			reset($this);
			while(list($sKey, $sValue) = each($this)) {
				if(substr($sKey , 0 , 1) === '-') {
					continue;
				}
				if(in_array($sKey , $this->_aValidFields)) {
					$aInsert[ $sKey ] = $this->_oDB->esc($sValue);
				}			
			}
			$sQuery = sprintf(' INSERT INTO %s(%s) VALUES("%s") ' , $this->_sTableName , implode(', ', $aInsert) , implode('", "', array_values($aInsert)));
		} else {
			$aUpdate = array();
			reset($this);
			while(list($sKey, $sValue) = each($this)) {
				if($sKey == 'id') {
					continue;
				}
				if(substr($sKey , 0 , 1) === '-') {
					continue;
				}
				if(in_array($sKey , $this->_aValidFields)) {
					$aUpdate[ $sKey ] = sprintf(' %s = "%s" ' , $sKey , $this->_oDB->esc($sValue));
				}			
			}
			$sQuery = sprintf(' UPDATE %s SET %s WHERE id = "%d" LIMIT 1 ' , $this->_sTableName , implode(', ', $aUpdate) , $this->_oDB->esc($this->id));

		}
		$this->_oDB->query($sQuery);
		if(empty($this->id)){
			$this->id = $this->_oDB->oMysql->insertID();
		}

		return $this->id;
	}

}

abstract class ProvidiList  {
	var $_oDB;
	function __construct($oDB=null) {
		$this->_oDB = $oDB;
	}
}

?>