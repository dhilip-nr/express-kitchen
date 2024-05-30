<?php
//error_reporting(0);
require_once("includes/config.php");

$smarty->assign("root", ROOT);
$smarty->assign("APPSESVAR", APPSESVAR);
$smarty->assign("manage_catelog", MNG_CATALOG);
$smarty->assign("site_name", $appConstData["site_name"]);
$smarty->assign("app_version", $appConstData["app_version"]);
$smarty->assign("unedit_ord", explode(",", $appConstData["unediatable_orders"]));

$pagename=$fn->page();
$pagename=$pagename;

$smarty->assign("page", $pagename);
$smarty->assign("isUser", $fn->isLoggedin());
$smarty->assign("timestamp", time());
$smarty->assign("ord_prefix", ORD_PREFIX);
$smarty->assign("version", "Current Version 1.24");

if(isset($_POST['login'])){
	$fn->getLogin();
}
//$fn->PrintR($_SESSION, 1,1);

//$fn->guestRedirect($pagename, $fn->isLoggedin());
$smarty->assign("message", isset($_GET['msg'])?$_GET['msg']:"");

$company_id_of_api = isset($_SESSION[APPSESVAR.'_user']['co_id'])?$_SESSION[APPSESVAR.'_user']['co_id']:"";

$reordered_material_count = 0;
if(isset($_GET['id']) && $_GET['id'] != "") {
	$br_order_id = $_GET['id'];
	$order_id = str_ireplace(ORD_PREFIX,'',$_GET['id']);
	if($fn->isDealer()){
		$reordered_mat_count = 0;
		$reordered_mat_dealer_count = $db->num_rows($db->query("select a.id from remode_reordered_materials_dealer as a 
		inner join remode_orders as b on a.order_id = b.order_id 
		where b.id=".$order_id));
	} else {
		$reordered_mat_count = $db->num_rows($db->query("select a.id from remode_reordered_materials as a
		inner join remode_orders as b on a.order_id = b.order_id
		where b.id=".$order_id));
		$reordered_mat_dealer_count = $db->num_rows($db->query("select a.id from remode_reordered_materials_dealer as a 
		inner join remode_orders as b on a.order_id = b.order_id 
		where b.id=".$order_id));
	}
	$reordered_material_count = ['a'=>$reordered_mat_count, 'd'=>$reordered_mat_dealer_count];
}
$smarty->assign('reorder_mats_count', $reordered_material_count);

$smarty->assign('userHasAnAccess', true);
$smarty->assign('adminMainNavMenu', $amm->adminMainNavMenu());
// echo $pagename; exit;
switch($pagename){
	case "login":
		if(isset($_SESSION[APPSESVAR.'_user']['un'])){
			$fn->redirect(ROOT."index.html");
		} else {
			$fn->redirect(ROOT."../login");
		}
/*
		$smarty->assign('title', "Login");
		$smarty->assign('pageassets', '<link href="'.ROOT.'styles/login.css?v'.$appConstData["app_version"].'" rel="stylesheet" type="text/css" />');
		$content=$smarty->fetch("login.tpl");
		$smarty->assign('content', $content);
*/
		break;

	case "view_order":
		if(!isset($_SESSION[APPSESVAR.'_user'])){
			$fn->redirect("../login");
		}

		$branch_by = "";
		if($_SESSION[APPSESVAR.'_user']['branch']!=""){
			$branch_by = " id IN (".$_SESSION[APPSESVAR.'_user']['branch'].") and ";
		} 

		$smarty->assign('title', "View Order");
		$smarty->assign('pageassets', '');
		$smarty->assign('filter_arr', array("withcusts"=>"With Customers", "all"=>"Show All Orders"));

		$company_query = $db->query("select alias, name from remode_company_master order by name");
		$smarty->assign('company_arr', $db->fetch_assoc($company_query));

		$brquery = $db->query("select id branch_id, name branch from remode_branch_master WHERE ".$branch_by." status='1' order by name");
		$smarty->assign('branch_arr', $db->fetch_assoc($brquery));
		$smarty->assign('status_arr', $fn->getStatusNames('admin'));

		$content=$smarty->fetch("view_order.tpl");
		$smarty->assign('content', $content);
		break;

	case "global_margin":
		if(!isset($_SESSION[APPSESVAR.'_user'])){
			$fn->redirect("../login");
		}

		if($_SESSION[APPSESVAR.'_user']['role']!="admin" && $_SESSION[APPSESVAR.'_user']['role']!="superadmin"){
			$fn->redirect("orders");
		}

		$branch_by = "";
		if($_SESSION[APPSESVAR.'_user']['branch']!=""){
			$branch_by = " id IN (".$_SESSION[APPSESVAR.'_user']['branch'].") and ";
		} 

		if(isset($_POST["action"]) && $_POST["action"] == "update_margin") {			
			if($_POST["percent"]) {
				$db->query("UPDATE remode_company_master SET margin=".$_POST["percent"]." WHERE id='".$_SESSION[APPSESVAR.'_user']['co_id']."'");
				$db->query("UPDATE remode_company_prices SET margin='".$_POST["percent"]."', retail=((material+labor)/(1-margin/100)) WHERE company_id='".$_SESSION[APPSESVAR.'_user']['co_id']."'");
			}
		}

		$smarty->assign('title', "Global Margin");
		$smarty->assign('pageassets', '');
		$smarty->assign('filter_arr', array("withcusts"=>"With Customers", "all"=>"Show All Orders"));

		$company_query = $db->query("SELECT alias, name, margin FROM remode_company_master WHERE id=".$_SESSION[APPSESVAR.'_user']['co_id']." ORDER BY name");
		$company_res = $db->fetch_assoc_single($company_query);
		$smarty->assign('company_info', $company_res);

		$content=$smarty->fetch("global_margin.tpl");
		$smarty->assign('content', $content);
		break;
		
	case "users":
		if(!isset($_SESSION[APPSESVAR.'_user'])){
			$fn->redirect("../login");
		}

		if($_SESSION[APPSESVAR.'_user']['role']!="admin" && $_SESSION[APPSESVAR.'_user']['role']!="superadmin"){
			$fn->redirect("orders");
		}

		$branch_by = "";
		if($_SESSION[APPSESVAR.'_user']['branch']!=""){
			$branch_by = " id IN (".$_SESSION[APPSESVAR.'_user']['branch'].") and ";
		} 

		include("catalog/jqgrid/inc/jqgrid_dist.php");
		include("catalog/tabs/manage_users.php");
		$smarty->assign('display_tab_data', $manage_users);

		$smarty->assign('title', "Manage Users");
		$content=$smarty->fetch("users.tpl");
		$smarty->assign('content', $content);
		break;

	case "sales_order":
		if(isset($_GET['id']) && $_GET['id'] != ""){
			$order_id = str_ireplace(ORD_PREFIX,'',$_GET['id']);
		}

		require_once("includes/so_actions.php");

		$smarty->assign('title', "Sales Order");
		
		$pageassets='<meta charset="utf-8" />';
		$pageassets.='<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />';

		$pageassets.='<link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">';
		$pageassets.='<link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">';
		$pageassets.='<link rel="stylesheet" href="assets/css/demo_1/style.css" />';

		$pageassets.='<script type="text/javascript" src="scripts/nicEdit.js?v'.$appConstData["app_version"].'"></script>';
		$pageassets.='<script type="text/javascript" src="scripts/sales_order.js?v'.$appConstData["app_version"].'"></script>';
		$pageassets.='<script type="text/javascript" src="scripts/fancybox/jquery.mousewheel-3.0.6.pack.js?v'.$appConstData["app_version"].'"></script>';
		$pageassets.='<link href="'.ROOT.'styles/sales_order.css?v'.$appConstData["app_version"].'" rel="stylesheet" type="text/css" />';
		$pageassets.='<link href="../css/admin.css" rel="stylesheet">';

		$smarty->assign('pageassets', $pageassets);

		$so_qry = $fn->gco_dyn("SoQry", "so_query.php", "includes/ord_select_stt/");

		$orderinfoqry = $db->query($so_qry->orderInfo($order_id));
		$productinfoqry = $db->query($so_qry->productInfo($order_id));

		$orderinfores = $db->fetch_assoc_single($orderinfoqry);
		$soinfores = $fn->gco_dyn("SOInfoList", "so_info.php", "includes/order_data/");
		$productinfores = $soinfores->GenProductArr($db->fetch_assoc($productinfoqry));
		// echo'<pre>';print_r($productinfores);exit;
/*
		$export_access_qry = 'SELECT s.value
			from remode_settings_mapping s
			left join remode_company_master c on s.cid=c.id
			left join remode_settings_master m on m.id=s.sid
			where c.alias="'.$orderinfores['company'].'" AND m.name="export_to_excel"';
		$export_access_res = $db->fetch_row_single($db->query($export_access_qry));
		$orderinfores['export_access'] = $export_access_res[0];
*/
		$orderinfores['export_access'] = 0;

		$smarty->assign('order_result', $orderinfores);
		$smarty->assign('products_result', $productinfores);
		$smarty->assign('test_branch', 0);

		$installer_email = array('email'=>'');
		$smarty->assign('installer_email', $installer_email['email']);

//		$repdetail = $fn->get_ws_result(USERINFO_URL, array('login_name' => $orderinfores['rep_name']), 'xml');
//		$repdetail = $db->fetch_assoc_single($db->query("select fullname, user_email email from remode_users where user_name='".$orderinfores['rep_name']."';"));
//		$smarty->assign('rep_info', $repdetail);
		$smarty->assign('rep_info', []);

//		$smarty->assign('bradminres', $fn->GetBranchAdmins($orderinfores['branch_id'], 'so_cc', 'str'));
		$smarty->assign('bradminres', []);
	
		$vendor_list_query = $db->query('SELECT distinct `alias`,`group` FROM `remode_vendors` where status="1"');
		$vendor_query_result = $db->fetch_assoc($vendor_list_query);
		$smarty->assign('vendor_query_result', $vendor_query_result);

		$has_image = "";
		if(file_exists("../saved_designs/placed-order/images/".str_replace("SO", "", $orderinfores['order_id']).".png")) {
//			$orderinfores['job_order_id'];
			$has_image = "../saved_designs/placed-order/images/".str_replace("SO", "", $orderinfores['order_id']).".png";
		}

		$mailcopydata = array(
			"id"=>$orderinfores['order_id'],
			"title"=>"Send Email Copy of Sales Order",
			"subject"=>"Sales Order # ".$orderinfores['order_id'],
			"from"=>"",
			"to"=>"",
			"has_design"=>$has_image
		);
		$smarty->assign('mailcopydata', $mailcopydata);

//		$cat_result = $fn->get_api_result($catalog_api_urls['rootcat'], "#$1#", $company_id_of_api);
//		$smarty->assign('category_query_result', $cat_result['rootcats']);
		$smarty->assign('category_query_result', []);

		// for misc items
		$smarty->assign('misc_result', $soinfores->GetMiscItems($orderinfores['job_order_id']));
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

		$smarty->assign('admin_fn', $fn);
		$content=$smarty->fetch("sales_order.tpl");
		$smarty->assign('content', $content);
		break;
	
	case "material_order":
		if(isset($_GET['id']) && $_GET['id'] != "") {
			$order_id = str_ireplace(ORD_PREFIX,'',$_GET['id']);
		}

		$mo_admin_view = $fn->hasMoAdminTemplate();
		$smarty->assign('mo_admin_view', $mo_admin_view);

		require_once("includes/mo_actions.php");

		$smarty->assign('title', "Material Order");
		$pageassets='<script type="text/javascript" src="scripts/nicEdit.js?v'.$appConstData["app_version"].'"></script>';
		$pageassets.='<script type="text/javascript" src="scripts/material_order.js?v'.$appConstData["app_version"].'"></script>';
		$pageassets.='<script type="text/javascript" src="../scripts/fancybox/jquery.mousewheel-3.0.6.pack.js?v'.$appConstData["app_version"].'"></script>';
		$pageassets.='<link href="styles/edit_orditems.css?v'.$appConstData["app_version"].'" type="text/css" rel="stylesheet" />';

		$smarty->assign('pageassets', $pageassets);


		$opt_arr = array('width', 'depth', 'height', 'doorwidth', 'squarefeet', 'linealfeet', 'fieldsqfeet', 'accentsqfeet', 'bullnoselnfeet', 'inside_corners', 'outside_corners', 'seams');
		$smarty->assign("opt_arr", $opt_arr);

// To get Category wise options --
		$optres = array();
		$catqry = $db->query("select id, options from remode_category where options!=''");
		$catres = []; // $db->fetch_assoc($catqry);
		foreach ($catres as $catopt){
			$optqry= $db->query("select id, name, db_name from remode_options where id IN (".$catopt['options'].") order by sort_order");
			$optres[$catopt['id']] = $db->fetch_assoc($optqry);
		}
		$smarty->assign("catoptions", $optres);
// -- To get Category wise options

		$optexcepts = $br_fldsfromopt;
		$smarty->assign("optexcepts", $optexcepts);


		$mo_qry = $fn->gco_dyn("MoQry", "mo_query.php", "includes/ord_select_stt/");
		$orderinfoqry = $db->query($mo_qry->orderInfo($order_id));
		$productinfoqry = $db->query($mo_qry->productInfo($order_id));

		$moinfores = $fn->gco_dyn("MOInfoList", "mo_info.php", "includes/order_data/");
		$orderinfores = $db->fetch_assoc_single($orderinfoqry);		
		$productinfores = $moinfores->GenProductArr($db->fetch_assoc($productinfoqry));

		$smarty->assign("MOInfoList", $moinfores);
		$orderinfores['branch_admin_email'] = $fn->GetBranchAdmins($orderinfores['branchid'], 'mo_cc', 'str');

		$export_access_qry = 'SELECT m.name, s.value
			from remode_settings_mapping s
			left join remode_company_master c on s.cid=c.id
			left join remode_settings_master m on m.id=s.sid
			where c.alias="'.$orderinfores['company'].'" AND m.name in ("export_to_excel", "has_dealer")';
		$export_access_res = $db->fetch_assoc($db->query($export_access_qry));

		$settings_mapp = [];
		foreach($export_access_res as $v){
			$settings_mapp[$v['name']] = $v['value'];
		}

		$orderinfores['export_access'] = $settings_mapp['export_to_excel'];
		$orderinfores['has_dealer'] = ((isset($settings_mapp['has_dealer']) && $settings_mapp['has_dealer']=='1')? true: false);

		$smarty->assign('order_result', $orderinfores);
		$smarty->assign('products_result', $productinfores);

		$order_res_count = $db->num_rows($productinfoqry);
        $smarty->assign('order_res_count', $order_res_count);

		$mailcopydata = array(
			"title"=>"Send Email Copy of Material Order",
			"subject"=>"Material Order # ".$orderinfores['order_id']." - ReMAP Configurator",
			"from"=>"",
			"to"=>""
		);
		$smarty->assign('mailcopydata', $mailcopydata);

		if(!$fn->checkOrderAccess($orderinfores['company_id'])){
			$smarty->assign('userHasAnAccess', false);
		}


		// get material items start ---
		$mat_product_mfg = array();
        if($order_res_count!='0'){        
			foreach($productinfores as $row => $order_items){
				$mat_product_mfg[] = array($order_items['manufacturer_alias'], $order_items['manufacturer']);
			}

			$subItemQuery= $db->query("select 
				rom.id, trim(pricingmodel) pricingmodel, Item, quantity, UOM, sku, fab, base_cost, cost, sold_by, unit_per_pack from remode_orderitems_material rom
				inner join remode_orders ro on ro.order_id=rom.order_id
				WHERE ro.id='".$order_id."'
				ORDER BY pricingmodel asc");

			$subItemQueryRes = $db->fetch_assoc($subItemQuery);
			$smarty->assign('order_subquery', $subItemQueryRes);

			$ordersubitems_countmap = array();
			if(!empty($subItemQueryRes) && count($subItemQueryRes[0])>0){
				foreach($subItemQueryRes as $subItem_Res){
					$ordersubitems_countmap[] = $subItem_Res['pricingmodel'];
				}
			}
			$ordersubitems_countmap = array_count_values($ordersubitems_countmap);

			if(isset($_REQUEST['ordersubitem_id']) && $_REQUEST['ordersubitem_id']!=""){
				$explode_subitemid_disp = explode(",", $_REQUEST['ordersubitem_id']);
				$subitem_id_disp = array();
				foreach($explode_subitemid_disp as $si_value_disp){
					$subitem_disp = explode("_", $si_value_disp);
					$subitem_id_disp[] = $subitem_disp[1];
				}
		
				$subItemQuery_disp= $db->query("select 
					trim(pricingmodel) pricingmodel, count(pricingmodel) material_count from remode_orderitems_material rom
					inner join remode_orders ro on ro.order_id=rom.order_id
					WHERE ro.id='".$order_id."' and rom.id in(".implode(",", $subitem_id_disp).")
					GROUP BY pricingmodel ORDER BY pricingmodel asc");
				$subItemRes_disp = $db->fetch_assoc($subItemQuery_disp);
		
				foreach($subItemRes_disp as $subItem_Res){
					$ordersubitems_countmap[$subItem_Res['pricingmodel']] = $subItem_Res['material_count'];
				}
		
			}

			$smarty->assign('order_subitems_count', $ordersubitems_countmap);
		}
		// get material items ends ---

		$smarty->assign('ship_to', $orderinfores['ship_to']);

		$misc_item_query = $db->query($mo_qry->miscInfo($orderinfores['job_order_id']));
		$misc_count = $db->num_rows($misc_item_query);
		$misc_result = $db->fetch_assoc($misc_item_query);

		$smarty->assign('misc_count',$misc_count);
		$smarty->assign('misc_result',$misc_result);

		$mat_miscitem_mfg = array();
		if($misc_count>0){
			foreach($misc_result as $misc_item){
				$mat_miscitem_mfg[] = array($misc_item['vendor'], $misc_item['manufacturer']);
			}
		}

		$mat_mfg_arr = array_map("unserialize", array_unique(array_map("serialize", array_merge_recursive($mat_product_mfg, $mat_miscitem_mfg))));
//		$mat_product_mfg_filtered = array_map("unserialize", array_unique(array_map("serialize", $mat_product_mfg)));
		$mat_product_mfg_filtered = array_unique(array_map('current', $mat_product_mfg));

		$smarty->assign('mat_product_mfg', $mat_product_mfg_filtered);
        $smarty->assign('mat_mfg_arr', $mat_mfg_arr);


		$vendor_list_query = $db->query('SELECT distinct `vendor`, `alias`, `group` FROM `remode_vendors`');
		$vendor_query_result = $db->fetch_assoc($vendor_list_query);
		$smarty->assign('vendor_query_result', $vendor_query_result);
		
		// for misc item starts
		$cat_result = $fn->get_api_result($catalog_api_urls['rootcat'], "#$1#", $company_id_of_api);
		$smarty->assign('category_query_result', $cat_result['rootcats']);

		$content=$smarty->fetch("material_order.tpl");
		$smarty->assign('content', $content);
		break;


	case "reordered_materials":
		if(isset($_POST['ajax-mode'])){
			if($_POST['ajax-mode']=='save_deliveryinfo'){
				$reorder_id = $_REQUEST['checked_id'];

				$delivery_info  =  json_encode([
					"receiver" => $_REQUEST['received_by'],
					"date" => $_REQUEST['received_on'],
					"comment" => $_REQUEST['comments']
				]);

				$db->query("update remode_reordered_materials set delivery_info='".$delivery_info."' where id in (".$reorder_id.")");
			}
			$fn->redirect("reordered_materials.html?id=".$br_order_id);
		}

		$smarty->assign('title', "Re-Ordered Materials");
		$pageassets='';
		$smarty->assign('pageassets',$pageassets);

		$reordered_material_res = ['active'=>'', 'result'=>['admin'=>[], 'dealer'=>[]]];

		if($fn->isDealer()){
			$reordered_material_res['active'] = "dealer";
		} else {
			$reordered_material_res['active'] = "admin";
			$reordered_material_res['result']['admin'] = $db->fetch_assoc($db->query("select a.id, a.item, a.pricingmodel, a.uom, a.qty, a.cost, a.reason, a.created_on, a.shipping_info, a.delivery_info, a.vendor, a.cover_cost, b.id as order_id, b.company as company 
			from remode_reordered_materials as a 
			inner join remode_orders as b on a.order_id = b.order_id 
			where b.id='".$order_id."'
			order by a.vendor, a.reason asc"));
		}
		$reordered_material_res['result']['dealer'] = $db->fetch_assoc($db->query("select a.id, a.item, a.pricingmodel, a.uom, a.qty, a.cost, a.reason, a.created_on, a.shipping_info, a.delivery_info, a.vendor, a.cover_cost, b.id as order_id, b.company as company 
		from remode_reordered_materials_dealer as a
		inner join remode_orders as b on a.order_id = b.order_id 
		where b.id='".$order_id."'
		order by a.vendor, a.reason asc"));

		$smarty->assign('reordered_material_res', $reordered_material_res);

		if(!empty($reordered_material_fetch) && !$fn->checkOrderAccess($reordered_material_fetch[0]['company_id'])){
			$smarty->assign('userHasAnAccess', false);
		}

		$smarty->assign('order_id', $order_id);
		$content=$smarty->fetch("reordered_materials.tpl");
		$smarty->assign('content', $content);
	break;


	case "install_order":
		if(isset($_GET['id']) && $_GET['id'] != "") {
			$order_id = str_ireplace(ORD_PREFIX,'',$_GET['id']);
		}

		require_once("includes/io_actions.php");

		$smarty->assign('opt_arr', array('width', 'depth', 'height', 'doorwidth', 'squarefeet', 'linealfeet', 'fieldsqfeet', 'accentsqfeet', 'bullnoselnfeet', 'inside_corners', 'outside_corners', 'seams'));

		$smarty->assign('title', "Install Order");
		$pageassets='<script type="text/javascript" src="scripts/nicEdit.js?v'.$appConstData["app_version"].'"></script>';
		$pageassets.='<script type="text/javascript" src="scripts/install_order.js?v'.$appConstData["app_version"].'"></script>';
		$pageassets.='<script type="text/javascript" src="../scripts/fancybox/jquery.mousewheel-3.0.6.pack.js?v'.$appConstData["app_version"].'"></script>';
		$pageassets.='<link href="styles/edit_orditems.css?v'.$appConstData["app_version"].'" type="text/css" rel="stylesheet" />';
		$smarty->assign('pageassets', $pageassets);

		$catqry= $db->query("select id, options from remode_category where options!=''");
		$catres= []; // $db->fetch_assoc($catqry);
		$optres="";
		foreach ($catres as $catopt){
			$optqry= $db->query("select id, name, db_name from remode_options where id IN (".$catopt['options'].") order by sort_order");
			$optres[$catopt['id']]= $db->fetch_assoc($optqry);
		}
		$smarty->assign('catoptions', $optres);

		$optexcepts = $br_fldsfromopt;
		$smarty->assign("potexcepts", $optexcepts);

		$io_qry = $fn->gco_dyn("IoQry", "io_query.php", "includes/ord_select_stt/");
		$orderinfoqry = $db->query($io_qry->orderInfo($order_id));
		$productinfoqry = $db->query($io_qry->productInfo($order_id));

		$ioinfores = $fn->gco_dyn("IOInfoList", "io_info.php", "includes/order_data/");
		$orderinfores = $db->fetch_assoc_single($orderinfoqry);
		$productinfores = $ioinfores->GenProductArr($db->fetch_assoc($productinfoqry));
		$smarty->assign("IOInfoList", $ioinfores);
		
		$ins_rate_id = "IR".trim($orderinfores['ins_rate_id']);
		$orderinfores['branch_admin_email'] = $fn->GetBranchAdmins($orderinfores['branchid'], 'io_cc', 'str');

		$export_access_qry = 'SELECT s.value
			from remode_settings_mapping s
			left join remode_company_master c on s.cid=c.id
			left join remode_settings_master m on m.id=s.sid
			where c.alias="'.$orderinfores['company'].'" AND m.name="export_to_excel"';
		$export_access_res = $db->fetch_row_single($db->query($export_access_qry));
		$orderinfores['export_access'] = $export_access_res[0];

		$smarty->assign('order_result', $orderinfores);
		$smarty->assign('products_result', $productinfores);

		$mailcopydata = array(
			"title"=>"Send Email Copy of Install Order",
			"subject"=>"Install Order # ".$orderinfores['order_id']." - ReMAP Configurator",
			"from"=>"",
			"to"=>""
		);
		$smarty->assign('mailcopydata', $mailcopydata);

		if(!$fn->checkOrderAccess($orderinfores['company_id'])){
			$smarty->assign('userHasAnAccess', false);
		}


		// get labor items start ---
		$subItemQuery= $db->query("select 
		rol.*, rol.pricing_model pricingmodel
		from remode_orderitems_labor rol 
		inner join remode_orders ro on ro.order_id=rol.order_id
		where ro.id = '".$order_id."'
		order by rol.pricing_model");

		$subItemQueryRes = $db->fetch_assoc($subItemQuery);

		$pricingmodels = "";
		$smarty->assign('order_subquery', $subItemQueryRes);

		$ordersubitems_countmap = array();
		if(!empty($subItemQueryRes) && count($subItemQueryRes[0])>0){
			foreach($subItemQueryRes as $subItem_Res){
				$ordersubitems_countmap[] = $subItem_Res['pricingmodel'];
			}
		}
		$ordersubitems_countmap = array_count_values($ordersubitems_countmap);
		$smarty->assign('order_subitems_count', $ordersubitems_countmap);

		// get labor items ends ---

		// get materials start ---
		$materialquery= $db->query("select 
		trim(pricingmodel) pricingmodel, (case Item when '' then pricingmodel else '' end) Item, quantity, UOM, sku, fab, sold_by, unit_per_pack from remode_orderitems_material rom
		inner join remode_orders ro on ro.order_id=rom.order_id
		WHERE ro.id='".$order_id."'
		ORDER BY pricingmodel asc");

		if($db->num_rows($materialquery)> 0) {
			$materialsubQuery = $db->fetch_assoc($materialquery);
			$smarty->assign('material_lineitems', $materialsubQuery);
		}
		// get materials ends ---

		// for misc item starts
		$vendor_list_query = $db->query('SELECT distinct `alias`,`group` FROM `remode_vendors`');
		$vendor_query_result = $db->fetch_assoc($vendor_list_query);
		$smarty->assign('vendor_query_result', $vendor_query_result);
		
		$cat_result = $fn->get_api_result($catalog_api_urls['rootcat'], "#$1#", $company_id_of_api);
		$smarty->assign('category_query_result', $cat_result['rootcats']);
		
		$misc_item_query = $db->query("SELECT * FROM `remode_orderitems_miscs` WHERE order_id='".$orderinfores['job_order_id']."' and is_approved!=0");
		$misc_count = $db->num_rows($misc_item_query);
		$misc_result = $db->fetch_assoc($misc_item_query);
		
		$smarty->assign('misc_count',$misc_count);
		$smarty->assign('misc_result',$misc_result);
		// get misc-item ends ---

		// installer damaged items - starts
		$damage_installer_fetch = $db->query("select item,pricingmodel,uom,qty,cost from remode_reordered_materials where reason='Installer Damaged the item' and order_id =".$orderinfores['job_order_id']);
		$damage_installer_result = $db->fetch_array($damage_installer_fetch);
		$damage_count = $db->num_rows($damage_installer_fetch);
		$smarty->assign('damage_count', $damage_count);
		$smarty->assign('damage_installer_result', $damage_installer_result);
		// installer damaged items - ends

		$content=$smarty->fetch("install_order.tpl");
		$smarty->assign('content', $content);
		break;
		  


	case "order_status":

		if(isset($_GET['id']) && $_GET['id'] != "") {
			$br_order_id = $_GET['id'];
			$order_id = str_ireplace(ORD_PREFIX,'',$_GET['id']);
		}

		$smarty->assign('title', "Order Status");
		$pageassets='<script type="text/javascript" src="scripts/order_status.js?v'.$appConstData["app_version"].'"></script>';
		$pageassets.='<link href="'.ROOT.'styles/order_status.css?v'.$appConstData["app_version"].'" rel="stylesheet" type="text/css" />';
		$smarty->assign('pageassets',$pageassets);

		$get_order_id = $db->query("select order_id, created_at, sent_material, sent_installer, dealer_mat_status, completed_at, status, company, company_id from remode_orders where id='".$order_id."'");
		$res_order_id= $db->fetch_assoc_single($get_order_id);
		$smarty->assign('ordstatus_overview', $res_order_id);


		$export_access_qry = 'SELECT m.name, s.value
			from remode_settings_mapping s
			left join remode_company_master c on s.cid=c.id
			left join remode_settings_master m on m.id=s.sid
			where c.alias="'.$res_order_id['company'].'" AND m.name in ("export_to_excel", "has_dealer")';
		$export_access_res = $db->fetch_assoc($db->query($export_access_qry));

		$settings_mapp = [];
		foreach($export_access_res as $v){
			$settings_mapp[$v['name']] = $v['value'];
		}

		$order_has_dealer = ((isset($settings_mapp['has_dealer']) && $settings_mapp['has_dealer']=='1')? true: false);
		$smarty->assign('order_has_dealer', $order_has_dealer);

		if(!$fn->checkOrderAccess($res_order_id['company_id'])){
			$smarty->assign('userHasAnAccess', false);
		}


		$smarty->assign('br_order_id', $br_order_id);
		$orderid = $res_order_id['order_id'];

		$statusnames_qry = $db->query("select name, br_group, type from remode_statusnames where status='1' order by sort_order");
		$statusnames_res = $db->fetch_assoc($statusnames_qry);
		$smarty->assign('statusnames_res', $statusnames_res);

		$smarty->assign('user_fullname', $_SESSION[APPSESVAR.'_adminuser']['name']);

		$exclude_admin_log = "";
		if($fn->isDealer()){
			$exclude_admin_log = " and rev.for_admin!='1'";
		}
		// Revision status
		$revision_query = $db->query("select roi.name product_name, rev.category, rev.comments, rev.created_at, rev.item_id,
			CASE WHEN (rev.origin='ip') THEN concat('I: ', ru.fullname)
			ELSE ru.fullname
			END as user_name
			from remode_order_revisions as rev
			left join remode_users ru on ru.user_name=rev.posted_by
			left join remode_orderitems roi on (roi.order_id=rev.order_id and roi.id=rev.item_id)
			where rev.order_id=".$orderid.$exclude_admin_log." order by rev.id");
		$revision_result = $db->fetch_assoc($revision_query);
		$smarty->assign('revision_result', $revision_result);

		// Matarial status

	if(!$order_has_dealer && !$fn->isDealer()){
		$material_status_query = $db->query("select distinct remode_order_status.*, remode_ordermat_attachments.shipping_info, remode_ordermat_attachments.delivery_info, remode_orders.created_at, remode_orders.sent_material, remode_orders.sent_installer, 
			CASE trim(remode_vendors.alias)
				WHEN (trim(remode_vendors.alias) IS NULL) THEN trim(remode_vendors.alias) ELSE 'UNKNOWN'
			END as manufacturer_alias,
			CASE trim(remode_vendors.group)
				WHEN (trim(remode_vendors.group) IS NULL) THEN trim(remode_vendors.group) ELSE 'UNKNOWN'
			END as manufacturer
			from remode_order_status
			left join remode_orders on remode_order_status.order_id = remode_orders.id
			inner join remode_orderitems on remode_orders.order_id = remode_orderitems.order_id
			left join remode_vendors on remode_vendors.vendor = remode_orderitems.manufacturer
			left join remode_ordermat_attachments on remode_ordermat_attachments.order_id=remode_orders.id and remode_ordermat_attachments.vendor=remode_vendors.alias
			where remode_order_status.order_id='".$order_id."'
			group by remode_vendors.group");

		$smarty->assign('material_status_count', $db->num_rows($material_status_query));
		$material_status_fetch = $db->fetch_assoc($material_status_query);
//		$smarty->assign('material_status_result', $material_status_fetch);



//echo "<pre>"; print_r($material_status_fetch);
		$material_status = [];
		foreach($material_status_fetch as $vendor_attr){
			$new_row = [];
			foreach($vendor_attr as $vkey => $vitem){
				if(strpos($vkey, "mfg")>-1){
					if($vitem == $vendor_attr['manufacturer'])
						$new_row[$vitem] = $vendor_attr[$vkey."_status"];
					else if(!isset($new_row[$vendor_attr['manufacturer']]))
						$new_row[$vendor_attr['manufacturer']] = "";

/*						
					$vkey = str_replace("_status", "", $vkey);
					if(!isset($new_row[$vkey]) && $vitem!="")
						$new_row[$vitem] = $vendor_attr[$vkey."_status"];
*/
				} else
					$new_row[$vkey] = $vitem;
			}
			$material_status[] = $new_row;
		}
//echo "<pre>"; print_r([$material_status]);exit;
		$smarty->assign('material_status_result', $material_status);



//		$vendor_status_query = $db->query("select * remode_order_status where order_id='".$order_id."'");
//		$vendor_status_fetch = $db->fetch_assoc($vendor_status_query);
//echo "<pre>"; print_r($vendor_status_fetch);exit;
//		$smarty->assign('material_status_result', $vendor_status_fetch);
	} else {
		$mat_order_status_query = $db->query("select
			IOS, IPD, DMS, created_at, sent_material, sent_installer
			from remode_order_status
			left join remode_orders on remode_order_status.order_id = remode_orders.id
			where remode_order_status.order_id='".$order_id."'");
		$mat_order_status_fetch = $db->fetch_assoc_single($mat_order_status_query);
		$smarty->assign('mat_order_status_result', $mat_order_status_fetch);

		$material_status_query = $db->query("select distinct remode_order_status.id, remode_order_status.order_id, remode_ordermat_attachments.shipping_info, remode_ordermat_attachments.delivery_info, remode_orders.created_at, 
			CASE trim(remode_vendors.alias)
				WHEN (trim(remode_vendors.alias) IS NULL) THEN trim(remode_vendors.alias) ELSE 'UNKNOWN'
			END as manufacturer_alias,
			CASE trim(remode_vendors.group)
				WHEN (trim(remode_vendors.group) IS NULL) THEN trim(remode_vendors.group) ELSE 'UNKNOWN'
			END as manufacturer
			from remode_order_status
			left join remode_orders on remode_order_status.order_id = remode_orders.id
			inner join remode_orderitems on remode_orders.order_id = remode_orderitems.order_id
			left join remode_vendors on remode_vendors.vendor = remode_orderitems.manufacturer
			left join remode_ordermat_attachments on remode_ordermat_attachments.order_id=remode_orders.id and remode_ordermat_attachments.vendor=remode_vendors.alias
			where remode_order_status.order_id='".$order_id."'
			group by remode_vendors.group");

		$smarty->assign('material_status_count', $db->num_rows($material_status_query));
		$material_status_fetch = $db->fetch_assoc($material_status_query);
//echo "<pre>"; print_r($material_status_fetch);exit;
		$smarty->assign('material_status_result', $material_status_fetch);
	}
		
		$order_schedules_query="select provider, eta, shipping_eta from remode_order_schedules where order_id='".$order_id."' and origin='vendor_portal'";
		$order_schedules_res = $db->fetch_assoc($db->query($order_schedules_query));
		$ven_order_schedules = "";
		if(!empty($order_schedules_res))
			foreach ($order_schedules_res as $mos){
				$ven_order_schedules[$mos['provider']] = array($mos['eta'], $mos['shipping_eta']);
			}
		$smarty->assign('ven_order_schedules', $ven_order_schedules);

		// Install status
		$installer_status_query = $db->query("select distinct remode_order_status.IOS, remode_order_status.IPD, remode_orders.sent_installer
			from remode_order_status
			left join remode_orders on remode_order_status.order_id = remode_orders.id
			where remode_order_status.order_id='".$order_id."'");
		$installer_status_fetch = $db->fetch_assoc_single($installer_status_query);
		$smarty->assign('installer_status_result', $installer_status_fetch);

		$ins_statusnames_res = array();
        foreach ($statusnames_res as $status_names){
			if ($status_names['br_group']=="installer" && strpos($status_names['type'], "admin")!==false){
				$ins_statusnames_res[] = $status_names['name'];
			}
		}

		$ins_status = explode(',', $installer_status_fetch['IOS']);
		foreach($ins_status as $key=>$ins_stat){
			if(strpos($ins_stat, 'Installation Problem') !== false){
				array_splice($ins_statusnames_res, $key, 0, 'Installation Problem');
			}
		}
		$smarty->assign('ins_statusnames_res', $ins_statusnames_res);
		$smarty->assign('ins_status', $ins_status);


		$smarty->assign('order_id', $order_id);
		$content=$smarty->fetch("order_status.tpl");
		$smarty->assign('content', $content);
	break;


	case "logout":
		unset($_SESSION[APPSESVAR.'_user']);
//		unset($_SESSION[APPSESVAR.'_admincompany']);
		$fn->redirect(ROOT."login.html");
	break;

/*
	default:
		$smarty->assign('title', "Home");
		$smarty->assign('pageassets', '<link href="styles/home.css?v'.$appConstData["app_version"].'" rel="stylesheet" type="text/css" />');

		$current_date = date("Y-m-d");
//		$where_branch = " AND company_id='".$_SESSION[APPSESVAR.'_user']['company']."'";
		$smarty->assign('order_count', array(0, 0, 0));


		$content=$smarty->fetch("home.tpl");
		$smarty->assign('content',$content);
		break;
*/

	case "orders":
		if(!isset($_SESSION[APPSESVAR.'_user'])){
			$fn->redirect("../login");
		}

		$branch_by = "";
		// for branch admin

		if($_SESSION[APPSESVAR.'_user']['branch']!=""){
			$branch_by = " id IN (".$_SESSION[APPSESVAR.'_user']['branch'].") and ";
		} 

		$smarty->assign('title', "View Order");
		$smarty->assign('pageassets', '');
		$smarty->assign('filter_arr', array("withcusts"=>"With Customers", "all"=>"Show All Orders"));

		$company_query = $db->query("select alias, name from remode_company_master order by name");
		$smarty->assign('company_arr', $db->fetch_assoc($company_query));

		$brquery = $db->query("select id branch_id, name branch from remode_branch_master WHERE ".$branch_by." status='1' order by name");
		$smarty->assign('branch_arr', $db->fetch_assoc($brquery));
		$smarty->assign('status_arr', $fn->getStatusNames('admin'));

		$content=$smarty->fetch("view_order.tpl");
		$smarty->assign('content', $content);
	break;
	
	default:
		$fn->redirect(ROOT."orders");
	break;

}
$smarty->display("index.tpl");

?>