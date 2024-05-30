<?php

include('mysqli.php');

switch(CW_ENV){
	/***   Live Connection   ***/

	case "production":
	case "offline":
		$BD_CON=array("DBHN"=>"35.185.117.94", "DBUN"=>"remap", "DBPW"=>"remap1@1#", "DBNM"=>"remap_designer");
	break;

	/***   Dev or Offline Connection  ***/

	case "development":
	case "offline_dev":
		$BD_CON=array("DBHN"=>"35.185.117.94", "DBUN"=>"remap", "DBPW"=>"remap1@1#", "DBNM"=>"remap_designer_qa");
	break;

	/***   Local or Local-Offline Connection   ***/		
	case "local":
	case "offline_local":
		$BD_CON=array("DBHN"=>"localhost", "DBUN"=>"root", "DBPW"=>"", "DBNM"=>"remap_designer");
	break;

	default:
		$BD_CON=array("DBHN"=>"", "DBUN"=>"", "DBPW"=>"", "DBNM"=>"");
	break;
}

define("HOSTNAME", $BD_CON['DBHN']);
define("HOSTUSER", $BD_CON['DBUN']);
define("HOSTPASS", $BD_CON['DBPW']);
define("HOSTDB", $BD_CON['DBNM']);

?>
