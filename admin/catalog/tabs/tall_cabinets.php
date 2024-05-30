<?php

$has_mod_access = ($_SESSION[APPSESVAR.'_user']['role']=="superadmin" || $_SESSION[APPSESVAR.'_user']['role']=="admin"? true: false);
$grid3 = new jqgrid();

$cols3 = array();

$col3 = array();
$col3["title"] = "#";
$col3["name"] = "id";
$col3["hidden"] = true;
$cols3[] = $col3;  

$col3 = array();
$col3["title"] = "Name";
$col3["name"] = "name";
$col3["search"] = true;
$col3["editable"] = false;
$col3["editrules"] = array( "required"=>true);
$col3["width"] = "150";
$cols3[] = $col3;         
if($has_mod_access) {
$col3 = array();
$col3["title"] = "Material Cost ($)";
$col3["name"] = "material";
$col3["search"] = true;
$col3["editable"] = true;
$col3["editrules"] = array( "required"=>true);
$col3["align"] = "right";
$col3["width"] = "80";
$cols3[] = $col3;

$col3 = array();
$col3["title"] = "Labor Cost ($)";
$col3["name"] = "labor";
$col3["search"] = true;
$col3["editable"] = true;
$col3["editrules"] = array( "required"=>true);
$col3["align"] = "right";
$col3["width"] = "80";
$cols3[] = $col3;

$col3 = array();
$col3["title"] = "Margin (%)";
$col3["name"] = "margin";
$col3["search"] = true;
$col3["editable"] = true;
$col3["editrules"] = array("required"=>true);
$col3["align"] = "right";
$col3["width"] = "60";
$cols3[] = $col3;
}
$col3 = array();
$col3["title"] = "Retail ($)";
$col3["name"] = "retail";
$col3["search"] = true;
$col3["editable"] = false;
$col3["align"] = "right";
$col3["width"] = "80";
$cols3[] = $col3;

$col3 = array();
$col3["title"] = "Active";
$col3["name"] = "active";
$col3["search"] = false;
$col3["editable"] = true;
$col3["formatter"]="select";
$col3["edittype"] = "select";
$col3["editoptions"] = array("value"=>'1:Yes;0:No', "defaultValue"=>$row['active']);
$col3["align"] = "center";
$col3["width"] = "50";
$cols3[] = $col3;

if($has_mod_access) {
	$col3 = array();
	$col3["title"] = "Action";
	$col3["name"] = "act";
	$col3["align"] = "center";
	$col3["width"] = "100";
	$cols3[] = $col3;
}

$e3 = array();
$e3["on_update"] = array("update_table", null,false);
$grid3->set_events($e3);

$opt3["reloadedit"] = true;
$opt3["autowidth"] = true;
$opt3["toolbar"] = "bottom";
$opt3["edit_options"]['width']='400';
$grid3->set_options($opt3);

$grid3->set_actions(array(
	"add"=> false,
	"edit"=>$has_mod_access,
	"delete"=>false,
//	"rowactions"=>true,
//	"export"=>true,
//	"autofilter" => true,
	"search" => false
));


$com_id=$_SESSION[APPSESVAR.'_user']['co_id'];
$grid3->select_command = "SELECT price.id, CONCAT(prd.name,' (W: ',prd.width,' In.)') name, price.material, price.labor, price.margin, price.retail, price.active
FROM remode_company_prices price
LEFT JOIN catalog_products prd on prd.id=price.product_id
WHERE prd.item_group='Tall Cabinets' AND prd.active=1 AND price.company_id='$com_id'";


$grid3->set_columns($cols3);         
$tall_cabinets = $grid3->render("remode_company_prices"); 


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