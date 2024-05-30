<?php

define("CW_ENV", "local");
define("ERR_REPORT", true);
define("APP_DEFAULT_THEME", "");
define("ADMIN_ROLES", "admin_roles");
define("APPSESVAR", "designer");
define("EMAIL_MODE", "dev");	// on, off, dev

define("ORD_PREFIX", "SO");
define("CID_PREFIX", "CUS");

// define("PRICE_BOOK", "PB1");
define("MNG_CATALOG", true);	// true, false
/*
define("ADMIN_ROLES", "admin_roles");
define("UE_ORDERS", "unediatable_orders");
define("DISCOUNT", "promo_discount");
define("ADNLDISCOUNT", "adnl_discount");
define("APP_DEFAULT_THEME", "rdi");
*/

$app_info = getFilePath();
$app_root_path_info = array("root_domain"=>false, "dir"=>$app_info['base_dir'], "host"=> $app_info['host']);





function getFilePath(){
	$app_info = [];

	$app_info['url_scheme'] = $_SERVER['REQUEST_SCHEME'];
	$app_info['host'] = $_SERVER['HTTP_HOST'];
	$app_info['port'] = $_SERVER['SERVER_PORT'];

	$self_url = $app_info['url_scheme']."://".$app_info['host'].($app_info['port']==80? "": ":".$app_info['port']).$_SERVER['REQUEST_URI'];
	
	$app_info['base_dir'] = basename(dirname(__DIR__));
	$app_info['base_url'] = explode(("/".$app_info['base_dir']), $self_url)[0]."/".$app_info['base_dir'];

	return $app_info;
}

?>
