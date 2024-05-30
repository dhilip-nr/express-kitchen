<?php
$has_mod_access = ($_SESSION[APPSESVAR.'_user']['role']=="superadmin" || $_SESSION[APPSESVAR.'_user']['role']=="admin"? true: false);

$grid2 = new jqgrid();

$cols2 = array();

$col2 = array();
$col2["title"] = "#";
$col2["name"] = "id";
$col2["hidden"] = true;
$cols2[] = $col2;  

$col2 = array();
$col2["title"] = "Name";
$col2["name"] = "name";
$col2["search"] = true;
$col2["editable"] = false;
$col2["editrules"] = array( "required"=>true);
$col2["width"] = "150";
$cols2[] = $col2;         

if($has_mod_access){
$col2 = array();
$col2["title"] = "Material Cost ($)";
$col2["name"] = "material";
$col2["search"] = true;
$col2["editable"] = true;
$col2["editrules"] = array( "required"=>true);
$col2["align"] = "right";
$col2["width"] = "80";
$cols2[] = $col2;

$col2 = array();
$col2["title"] = "Labor Cost ($)";
$col2["name"] = "labor";
$col2["search"] = true;
$col2["editable"] = true;
$col2["editrules"] = array( "required"=>true);
$col2["align"] = "right";
$col2["width"] = "80";
$cols2[] = $col2;

$col2 = array();
$col2["title"] = "Margin (%)";
$col2["name"] = "margin";
$col2["search"] = true;
$col2["editable"] = true;
$col2["editrules"] = array("required"=>true);
$col2["align"] = "right";
$col2["width"] = "60";
$cols2[] = $col2;
}
$col2 = array();
$col2["title"] = "Retail ($)";
$col2["name"] = "retail";
$col2["search"] = true;
$col2["editable"] = false;
$col2["align"] = "right";
$col2["width"] = "80";
$cols2[] = $col2;

$col2 = array();
$col2["title"] = "Active";
$col2["name"] = "active";
$col2["search"] = false;
$col2["editable"] = true;
$col2["formatter"]="select";
$col2["edittype"] = "select";
$col2["editoptions"] = array("value"=>'1:Yes;0:No', "defaultValue"=>$row['active']);
$col2["align"] = "center";
$col2["width"] = "50";
$cols2[] = $col2;

if($has_mod_access) {
	$col2 = array();
	$col2["title"] = "Action";
	$col2["name"] = "act";
	$col2["align"] = "center";
	$col2["width"] = "100";
	$cols2[] = $col2;
}

$e7 = array();
$e7["on_update"] = array("update_table", null,false);
$grid2->set_events($e7);

$opt2["reloadedit"] = true; // force reload
$opt2["autowidth"] = true;
$opt2["toolbar"] = "bottom";
$opt2["edit_options"]['width']='400';
$grid2->set_options($opt2);

$grid2->set_actions(array(
	"add" => false,
	"edit"=> $has_mod_access,
	"delete"=>false,
//	"rowactions"=>true,
//	"export"=>true,
//	"autofilter" => true,
	"search" => false
));

$com_id=$_SESSION[APPSESVAR.'_user']['co_id'];
$grid2->select_command = "SELECT price.id, prd.name, price.material, price.labor, price.margin, price.retail, price.active
FROM remode_company_prices price
LEFT JOIN catalog_products prd on prd.id=price.product_id
WHERE prd.item_group='Add Ons' AND prd.active=1 AND price.company_id='$com_id'";

$grid2->set_columns($cols2);         
$addons = $grid2->render("remode_company_prices"); 


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