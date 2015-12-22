<?php  // all the scripts should be saved as UTF8 // æ



global $aProvidiConfigs;

$aProvidiConfigs = array();

$aProvidiConfigs['DB_host'] = 'localhost';
$aProvidiConfigs['DB_username'] = 'root';
$aProvidiConfigs['DB_password'] = 'root123';
$aProvidiConfigs['DB_dbname'] = 'local';


$aProvidiConfigs['URL_live_site'] = 'http://127.0.0.1/providi.eu/';
$aProvidiConfigs['URL_profile_image_path'] = $aProvidiConfigs['URL_live_site'] . 'images_forhandlere/';

$aProvidiConfigs['DISTRIBUTOR_SHOP_default_shipping_cost'] = 99;

$aProvidiConfigs['VS_SELF_ACCOUNT_authenticate_url'] = 'http://127.0.0.1/voressundhed.dk/voressundhed/scsupport/';

?>