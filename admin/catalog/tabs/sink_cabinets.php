<?php
$has_mod_access = ($_SESSION[APPSESVAR.'_user']['role']=="superadmin" || $_SESSION[APPSESVAR.'_user']['role']=="admin"? true: false);

$grid1 = new jqgrid();

$cols1 = array();

$col1 = array();
$col1["title"] = "#";
$col1["name"] = "id";
$col1["hidden"] = true;
$cols1[] = $col1;  

$col1 = array();
$col1["title"] = "Name";
$col1["name"] = "name";
$col1["search"] = true;
$col1["editable"] = false;
$col1["editrules"] = array( "required"=>true);
$col1["width"] = "150";
$cols1[] = $col1;         
if($has_mod_access){
$col1 = array();
$col1["title"] = "Material Cost ($)";
$col1["name"] = "material";
$col1["search"] = true;
$col1["editable"] = true;
$col1["editrules"] = array( "required"=>true);
$col1["align"] = "right";
$col1["width"] = "80";
$cols1[] = $col1;

$col1 = array();
$col1["title"] = "Labor Cost ($)";
$col1["name"] = "labor";
$col1["search"] = true;
$col1["editable"] = true;
$col1["editrules"] = array( "required"=>true);
$col1["align"] = "right";
$col1["width"] = "80";
$cols1[] = $col1;

$col1 = array();
$col1["title"] = "Margin (%)";
$col1["name"] = "margin";
$col1["search"] = true;
$col1["editable"] = true;
$col1["editrules"] = array("required"=>true);
$col1["align"] = "right";
$col1["width"] = "60";
$cols1[] = $col1;
}
$col1 = array();
$col1["title"] = "Retail ($)";
$col1["name"] = "retail";
$col1["search"] = true;
$col1["editable"] = false;
$col1["align"] = "right";
$col1["width"] = "80";
$cols1[] = $col1;

$col1 = array();
$col1["title"] = "Active";
$col1["name"] = "active";
$col1["search"] = false;
$col1["editable"] = true;
$col1["formatter"]="select";
$col1["edittype"] = "select";
$col1["editoptions"] = array("value"=>'1:Yes;0:No', "defaultValue"=>$row['active']);
$col1["align"] = "center";
$col1["width"] = "50";
$cols1[] = $col1;

$has_mod_access = ($_SESSION[APPSESVAR.'_user']['role']=="superadmin" || $_SESSION[APPSESVAR.'_user']['role']=="admin"? true: false);
if($has_mod_access) {
	$col1 = array();
	$col1["title"] = "Action";
	$col1["name"] = "act";
	$col1["align"] = "center";
	$col1["width"] = "100";
	$cols1[] = $col1;
}

$e1 = array();
//$e1["on_insert"] = array("add_users", null, false);
$e1["on_update"] = array("update_table", null,false);
//$e1["on_delete"] = array("delete_users", null, false); 
//$e1["on_data_display"] = array("filter_user_branches", null, true);
$grid1->set_events($e1);

$opt1["reloadedit"] = true; // force reload
$opt1["autowidth"] = true;
$opt1["toolbar"] = "bottom";
$opt1["edit_options"]['width']='400';
$grid1->set_options($opt1);

$grid1->set_actions(array(
	"add"=>false,
	"edit"=>$has_mod_access,
	"delete"=>false,
//	"rowactions"=>true,
//	"export"=>true,
//	"autofilter" => true,
	"search" => false
));

$com_id=$_SESSION[APPSESVAR.'_user']['co_id'];
$grid1->select_command = "SELECT price.id, CONCAT(prd.name,' (W: ',prd.width,' In.)') name, price.material, price.labor, price.margin, price.retail, price.active
FROM remode_company_prices price
LEFT JOIN catalog_products prd on prd.id=price.product_id
WHERE prd.item_group='Sink Cabinets' AND prd.active=1 AND price.company_id='$com_id'";

$grid1->set_columns($cols1);         
$sink_cabinets = $grid1->render("remode_company_prices"); 


function update_table($data){
	global $pg_dbobj;

	$id	= $pg_dbobj->real_escape($data["id"]);
	$active = $pg_dbobj->real_escape($data["params"]["active"]);

	$material = $pg_dbobj->real_escape($data["params"]["material"]);
	$labor = $pg_dbobj->real_escape($data["params"]["labor"]);
	$margin = $pg_dbobj->real_escape($data["params"]["margin"]);
	$retail = (($material+$labor)/(1-$margin/100));

	$pg_dbobj->query("UPDATE `remode_company_prices` SET material = '$material', labor = '$labor', margin = '$margin', retail = '$retail', active = '$active' WHERE id = '$id'");
}





?>