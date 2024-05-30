<?php

define("APPSESVAR", "designer");
define("APPLOGO", "default.jpg");
define("ORD_PREFIX", "SO");
define("CID_PREFIX", "CUS");
define("ERR_REPORT", false);		// true, false
define("SYS_TYPE", "regular");	// regular, maintenance
define("CW_ENV", "local");		// production, development, local
define("EMAIL_MODE", "dev");	// on, off, dev
define("API_VER", "v1");
define("CACHE_CONTROL", "max-age=259200");


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