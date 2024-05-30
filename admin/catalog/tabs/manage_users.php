<?php
error_reporting(0);
$grid2 = new jqgrid();

$cols2 = array();

$col2 = array();
$col2["title"] = "#";
$col2["name"] = "id";
$col2["hidden"] = true;
$cols2[] = $col2;  

$col2 = array();
$col2["title"] = "First Name";
$col2["name"] = "firstname";
$col2["search"] = true;
$col2["editable"] = true;
$col2["editrules"] = array( "required"=>true);
$col2["width"] = "100";
$cols2[] = $col2;         

$col2 = array();
$col2["title"] = "Last Name";
$col2["name"] = "lastname";
$col2["search"] = true;
$col2["editable"] = true;
$col2["editrules"] = array( "required"=>true);
$col2["width"] = "100";
$cols2[] = $col2;

$col2 = array();
$col2["title"] = "Email";
$col2["name"] = "email";
$col2["search"] = true;
$col2["editable"] = true;
$col2["editrules"] = array( "required"=>true);
$col2["width"] = "150";
$cols2[] = $col2;

$col2 = array();
$col2["title"] = "Role";
$col2["name"] = "role";
$col2["search"] = false;
$col2["editable"] = true;
$col2["formatter"]="select";
$col2["edittype"] = "select";
$col2["editoptions"] = array("value"=>'superadmin:Admin;salesrep:Regular', "defaultValue"=>$row['role']);
$col2["align"] = "center";
$col2["width"] = "80";
$cols2[] = $col2;


$col2 = array();
$col2["title"] = "Active";
$col2["name"] = "status";
$col2["search"] = false;
$col2["editable"] = true;
$col2["formatter"]="select";
$col2["edittype"] = "select";
$col2["editoptions"] = array("value"=>'Active:Yes;Inactive:No', "defaultValue"=>$row['status']);
$col2["align"] = "center";
$col2["width"] = "50";
$cols2[] = $col2;

$col2 = array();
$col2["title"] = "Action";
$col2["name"] = "act";
$col2["align"] = "center";
$col2["width"] = "100";
$cols2[] = $col2;

$e2 = array();
$e2["on_insert"] = array("add_table", null,false);
$e2["on_update"] = array("update_table", null,false);
$grid2->set_events($e2);

$opt2["reloadedit"] = true; // force reload
$opt2["autowidth"] = true;
$opt2["toolbar"] = "bottom";
$opt2["add_options"]['width']='400';
$opt2["edit_options"]['width']='400';
$grid2->set_options($opt2);

$grid2->set_actions(array(
	"add" => ($_SESSION[APPSESVAR.'_user']['co_id'] != 3? true: false),
//	"edit" => ($_SESSION[APPSESVAR.'_user']['co_id'] != 3? true: false),
	"delete" => ($_SESSION[APPSESVAR.'_user']['co_id'] != 3? true: false),
//	"rowactions"=>true,
//	"export"=>true,
//	"autofilter" => true,
	"search" => false
));

$com_id=$_SESSION[APPSESVAR.'_user']['co_id'];
$userid=$_SESSION[APPSESVAR.'_user']['id'];
$grid2->select_command = "SELECT id, firstname, lastname, email, role, status 
FROM remode_users 
WHERE company_id='$com_id' AND id!='$userid'";

$grid2->set_columns($cols2);         
$manage_users = $grid2->render("manage_users"); 


function update_table($data){
	global $pg_dbobj;

	$id	= $pg_dbobj->real_escape($data["id"]);
	$fname = $pg_dbobj->real_escape($data["params"]["firstname"]);
	$lname = $pg_dbobj->real_escape($data["params"]["lastname"]);
	$email = $pg_dbobj->real_escape($data["params"]["email"]);
	$role = $pg_dbobj->real_escape($data["params"]["role"]);
	$status = $pg_dbobj->real_escape($data["params"]["status"]);

	$pg_dbobj->query("UPDATE remode_users SET firstname = '$fname', firstname = '$lname', email = '$email', role = '$role', status = '$status' WHERE id = '$id'");
}

function add_table($data){
	global $pg_dbobj;

	$com_id=$_SESSION[APPSESVAR.'_user']['co_id'];
	$fname = $pg_dbobj->real_escape($data["params"]["firstname"]);
	$lname = $pg_dbobj->real_escape($data["params"]["lastname"]);
	$email = $pg_dbobj->real_escape($data["params"]["email"]);
	$role = $pg_dbobj->real_escape($data["params"]["role"]);
	$status = $pg_dbobj->real_escape($data["params"]["status"]);

	$pg_dbobj->query("INSERT INTO remode_users (username, firstname, lastname, company_id, email, role, status) VALUES ('$email', '$fname', '$lname', '$com_id', '$email', '$role', '$status')");
}





?>