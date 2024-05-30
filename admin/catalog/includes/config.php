<?php

if(!isset($_SESSION)){ session_start(); }

require_once('includes/consts.php');
require_once('../../includes/dbconnect.php');

error_reporting(0);
$db = $pg_dbobj = new Database(HOSTNAME, HOSTUSER, HOSTPASS, HOSTDB, true);

// define("ROOT", $appConstData[CW_ENV]."../");

define("PHPGRID_AUTOCONNECT", 1);
define("PHPGRID_DBTYPE", "mysqli");
define("PHPGRID_DBHOST", HOSTNAME);
define("PHPGRID_DBUSER", HOSTUSER);
define("PHPGRID_DBPASS", HOSTPASS);
define("PHPGRID_DBNAME", HOSTDB);
#define('THEME', 'base');

?>
