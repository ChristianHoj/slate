<? 

global $aUTFReplaceItem;
$aUTFReplaceItem= array(
	'`'=> "'"
	, '´'=> "'"
	, '’' => "'"
);
	
function js_utf8_encode($data) {	//
	global $aUTFReplaceItem;
	if (is_array($data))	{
		foreach($data as $a => $b) {
			if (is_array($data[$a])) {
				$data[$a] = js_utf8_encode($data[$a]);
			} elseif(is_object($data[$a])) {
				$data[$a] = js_utf8_encode($data[$a]);
			} else {
//				$b = str_replace(array_keys($aUTFReplaceItem)  , array_values($aUTFReplaceItem) , $b);
				$data[$a] = iconv("ISO-8859-1","utf-8",$b);
			}
		}
	} elseif( is_object($data)) {	
		foreach($data as $a => $b) {
			if (is_array($data->$a)) {
				$data->$a = js_utf8_encode($data->$a);
			} elseif(is_object($data->$a)) {
				$data->$a = js_utf8_encode($data->$a);				
			} else {
//				$b = str_replace(array_keys($aUTFReplaceItem)  , array_values($aUTFReplaceItem) , $b);
				$data->$a = iconv("ISO-8859-1","utf-8",$b);
			}
		}
	} else {

//		$data = str_replace(array_keys($aUTFReplaceItem)  , array_values($aUTFReplaceItem) , $data);
		$data =iconv("ISO-8859-1","utf-8",$data);
	}
	return $data;
}


function js_utf8_decode($data) {	//
	global $aUTFReplaceItem;
	if (is_array($data))	{
		foreach($data as $a => $b) {
			if (is_array($data[$a])) {
				$data[$a] = js_utf8_decode($data[$a]);
			} elseif(is_object($data[$a])) {
				$data[$a] = js_utf8_decode($data[$a]);
			} else {
//				$b = str_replace(array_keys($aUTFReplaceItem)  , array_values($aUTFReplaceItem) , $b);
				$data[$a] = iconv("utf-8","ISO-8859-1//IGNORE",$b);
			}
		}
	} elseif( is_object($data)) {	
		foreach($data as $a => $b) {
			if (is_array($data->$a)) {
				$data->$a = js_utf8_decode($data->$a);
			} elseif(is_object($data->$a)) {
				$data->$a = js_utf8_decode($data->$a);				
			} else {
//				$data = str_replace(array_keys($aUTFReplaceItem)  , array_values($aUTFReplaceItem) , $data);
				$data->$a = iconv("utf-8","ISO-8859-1//IGNORE",$b);
			}
		}
	} else {
		$data = str_replace(array_keys($aUTFReplaceItem)  , array_values($aUTFReplaceItem) , $data);
		$data =iconv("utf-8","ISO-8859-1//IGNORE",$data);
	}
	return $data;
}

?>