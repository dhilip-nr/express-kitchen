<?php

if(!isset($_SESSION)){ session_start(); }

require_once('../libs/Smarty.class.php');
require_once('../includes/consts.php');
require_once('../includes/dbconnect.php');
require_once('../includes/config.phpmailer.php');
require_once('../includes/functions.php');
require_once('admin_main_menu.php');
require_once('admin_functions.php');

$smarty = new Smarty;
$smarty->debugging = false;
$smarty->caching = false;
$smarty->cache_lifetime = 0;

$db = $pg_dbobj = new Database(HOSTNAME, HOSTUSER, HOSTPASS, HOSTDB, true);
$fn = new AdminFunctions;
$amm = new AdminMainMenu;

define("ROOT", $appConstData[CW_ENV]);


define("PHPGRID_AUTOCONNECT", 1);
define("PHPGRID_DBTYPE", "mysqli");
define("PHPGRID_DBHOST", HOSTNAME);
define("PHPGRID_DBUSER", HOSTUSER);
define("PHPGRID_DBPASS", HOSTPASS);
define("PHPGRID_DBNAME", HOSTDB);
?>