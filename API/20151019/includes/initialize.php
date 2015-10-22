<?php

if($_SERVER['HTTP_HOST']== '127.0.0.1') {
	require_once './config.local.php';
} else {
	require_once './config.php';
}

require_once './includes/providiAPI.lib.php';
require_once './includes/providiAPI.php';
require_once './includes/providiAuthToken.class.php';


require_once './includes/providiStatistic.class.php';

require_once './includes/js_utf8_encode.php';


?>