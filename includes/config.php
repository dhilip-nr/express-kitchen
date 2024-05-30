<?php

if(!isset($_SESSION)){ session_start(); }

require 'libs/Smarty.class.php';
require_once('consts.php');
require_once('functions.php');
require_once('dbconnect.php');

error_reporting(0);

$smarty = new Smarty;
// $smarty->force_compile = true;
// $smarty->debugging = true;
$smarty->caching = true;
$smarty->cache_lifetime = 120;

$db = new Database(HOSTNAME, HOSTUSER, HOSTPASS, HOSTDB, true);
$fn = new Functions;

define("ROOT", $appConstData[CW_ENV]);

?>
