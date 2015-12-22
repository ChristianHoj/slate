<?php  // all the scripts should be saved as UTF8 // æ

abstract class ProvidiObject  {
	var $_oDB;
	var $_sTableName , $_aValidFields;
	var $_bNeedUTF8Conversion = false;
	var $_sTableCharset = false;
	function __construct($oDB=null , $nLoadID=null) {
		$this->_oDB = $oDB;
		$this->_setBaseTable();
		// $this->_getValidFields();		
		if(!is_null($nLoadID)) {
			$this->load($nLoadID);
		}
	}

	private function _getValidFields() {
		global $oDB;
		$this->_aValidFields = array();
		$sQuery = sprintf(' DESC %s ' , $this->_sTableName);
		$this->_aValidFields = $this->_oDB->getColumn($sQuery);	
	}

	function getID() {
		return $this->id;
	}

	abstract function _setBaseTable();


	function getTableCharset() {
		return empty($this->_sTableCharset) ? 'UTF8' : $this->_sTableCharset;
	}
	function isLatinTable() {
		return $this->getTableCharset() == 'latin1' ;	
	}
	function isUTF8Table() {
		return $this->getTableCharset() == 'utf8' ;	
	}


	function merge($aParam) {
		if(empty($this->_aValidFields)) {
			$this->_getValidFields();
		}
		@reset($aParam);
		while(list($sKey,$sValue) = @each($aParam)) {
			if(in_array($sKey , $this->_aValidFields)) {
				$this->$sKey = $sValue;
			}
		}
		return $this->id;	
	}

	function prepareCharset($sCharset=null) {

		if(is_null($sCharset)) {
			$sCharset = $this->getTableCharset();;
		}
		if(empty($sCharset)) {
			$sCharset = 'UTF8';
		}
		if($sCharset == 'UTF8') {
			if(!$this->_oDB->isUTF8()) {
				$this->_oDB->setUTF8();
			}
		} else {
			if(!$this->_oDB->isLatin()) {
				$this->_oDB->setLatin();
			}			

		}

	}

	function load($nID) {
		$this->prepareCharset();
		$sQuery = sprintf(' SELECT * FROM %s WHERE id = "%s" LIMIT 1 ' , $this->_sTableName , $nID);
		$sClass = get_class($this);
		$oObj = $this->_oDB->getObject($sQuery , $sClass , array($this->_oDB));
		$this->merge($oObj);

		if(!$this->isUTF8Table()) {
			$this->toUTF8();
		} 
		return $this->id;	
	}

	function toUTF8(&$aInput=null) {
		if(!is_null($aInput)) {
			if(!is_array($aInput) && !is_object($aInput)) {
				throw new providiBadRequestException('Invalid parameter for toUTF8() , array or object expected' , 714);
			}
			$sMode = is_array($aInput)?'array':'object';
			reset($aInput);
			while(list($sKey,$sValue) = each($aInput)) {
				if(substr($sKey , 0 , 1 ) == '_') {
					continue;
				}
				if(is_array($sValue) || is_object($sValue)) {
					continue;
				}
				if($sMode == 'array') {
					$aInput[ $sKey ] = providiISO2UTF($sValue);
				} else {
					$aInput->$sKey = providiISO2UTF($sValue);					
				}			
			}
			return;
		}
		reset($this);
		while(list($sKey,$sValue) = each($this)) {
			if(substr($sKey , 0 , 1 ) == '_') {
				continue;
			}
			if(is_array($sValue) || is_object($sValue)) {
				continue;
			}
			$this->$sKey = providiISO2UTF($sValue);
		}
	}
	function toISO(&$aInput=null) {
		if(!is_null($aInput)) {
			if(!is_array($aInput) && !is_object($aInput)) {
				throw new providiBadRequestException('Invalid parameter for toUTF8() , array or object expected' , 714);
			}
			$sMode = is_array($aInput)?'array':'object';
			reset($aInput);
			while(list($sKey,$sValue) = each($aInput)) {
				if(substr($sKey , 0 , 1 ) == '_') {
					continue;
				}
				if(is_array($sValue) || is_object($sValue)) {
					continue;
				}
				if($sMode == 'array') {
					$aInput[ $sKey ] = providiUTF2ISO($sValue);
				} else {
					$aInput->$sKey = providiUTF2ISO($sValue);					
				}			
			}
			return;
		}
		reset($this);
		while(list($sKey,$sValue) = each($this)) {
			if(substr($sKey , 0 , 1 ) == '_') {
				continue;
			}
			if(is_array($sValue) || is_object($sValue)) {
				continue;
			}
			$this->$sKey = providiUTF2ISO($sValue);
		}
	}
	function save() {
		if(empty($this->_aValidFields)) {
			$this->_getValidFields();
		}
		if(!empty($this->_bNeedUTF8Conversion)) {
			$this->toISO();
		}


		if(empty($this->id)) {
			$aInsert = array();
			reset($this);
			while(list($sKey, $sValue) = each($this)) {
				if(substr($sKey , 0 , 1) === '_') {
					continue;
				}
				if(in_array($sKey , $this->_aValidFields)) {
					if($sValue == '""') {
						$sValue = '';
					}
					$aInsert[ $sKey ] = $this->_oDB->esc($sValue);
				}			
			}
			$sQuery = sprintf(' INSERT INTO %s(%s) VALUES("%s") ' , $this->_sTableName , implode(', ', array_keys($aInsert)) , implode('", "', array_values($aInsert)));
		} else {
			$aUpdate = array();
			reset($this);
			while(list($sKey, $sValue) = each($this)) {
				if($sKey == 'id') {
					continue;
				}
				if(substr($sKey , 0 , 1) === '_') {
					continue;
				}
				if(in_array($sKey , $this->_aValidFields)) {
					if($sValue == '""') {
						$sValue = '';
					}
					$aUpdate[ $sKey ] = sprintf(' %s = "%s" ' , $sKey , $this->_oDB->esc($sValue));
				}			
			}
			$sQuery = sprintf(' UPDATE %s SET %s WHERE id = "%d" LIMIT 1 ' , $this->_sTableName , implode(', ', $aUpdate) , $this->_oDB->esc($this->id));

		}


		if(!empty($this->_bNeedUTF8Conversion)) {
			if(!$this->_oDB->isLatin()) {
				$this->_oDB->setLatin();
			}			
		} else {
			if(!$this->_oDB->isUTF8()) {
				$this->_oDB->setUTF8();
			}
		}


		$this->_oDB->query($sQuery);
		if(empty($this->id)){
			$this->id = $this->_oDB->insertID();
		}

		return $this->id;
	}

}

class providiBlankObject extends providiObject {
	function _setBaseTable() {
		$this->_sBaseTable = '_';
	}
}

abstract class ProvidiList  {
	var $_oDB;
	function __construct($oDB=null) {
		$this->_oDB = $oDB;
	}
}

?>