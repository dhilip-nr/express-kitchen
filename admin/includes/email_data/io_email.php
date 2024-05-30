<?php

	$order_id = str_ireplace(ORD_PREFIX,'',$_GET['id']);

	$opt_arr = array('width', 'depth', 'height', 'doorwidth', 'squarefeet', 'linealfeet', 'fieldsqfeet', 'accentsqfeet', 'bullnoselnfeet', 'inside_corners', 'outside_corners', 'seams');
	$smarty->assign("opt_arr", $opt_arr);


	$io_qry = $fn->gco_dyn("IoQry", "io_query.php", "includes/ord_select_stt/");
	$orderinfoqry = $db->query($io_qry->orderInfo($order_id));
	$productinfoqry = $db->query($io_qry->productInfo($order_id));

	$ioinfores = $fn->gco_dyn("IOInfoList", "io_info.php", "includes/order_data/");
	$orderinfores = $db->fetch_assoc_single($orderinfoqry);
	$productinfores = $ioinfores->GenProductArr($db->fetch_assoc($productinfoqry));
	$smarty->assign("IOInfoList", $ioinfores);
	
	$ins_rate_id = "IR".trim($orderinfores['ins_rate_id']);
	$orderinfores['branch_admin_email'] = $fn->GetBranchAdmins($orderinfores['branchid'], 'io_cc', 'str');
//
	$smarty->assign('order_result', $orderinfores);
	$smarty->assign('products_result', $productinfores);

	$mailcopydata = array(
		"title"=>"Send Email Copy of Install Order",
		"subject"=>"Install Order # ".$orderinfores['order_id']." - THD Bath Remodeling",
		"from"=>"",
		"to"=>""
	);
	$smarty->assign('mailcopydata', $mailcopydata);

	if(!$fn->checkOrderAccess($orderinfores['company'])){
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
	if(count($subItemQueryRes[0])>0){
		foreach($subItemQueryRes as $subItem_Res){
			$ordersubitems_countmap[] = $subItem_Res['pricingmodel'];
		}
	}
	$ordersubitems_countmap = array_count_values($ordersubitems_countmap);
	$smarty->assign('order_subitems_count', $ordersubitems_countmap);

	// get labor items ends ---

	// get materials start ---
	$materialquery= $db->query("select 
	trim(pricingmodel) pricingmodel, Item, quantity, UOM, sku, fab, sold_by, unit_per_pack from remode_orderitems_material rom
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
	
	
	$category_query = $db->query('select id, name from remode_category where id in("1","2","3","4","5","6")');
	$category_query_result = $db->fetch_assoc($category_query);
	$smarty->assign('category_query_result', $category_query_result);
	
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

// ----- Re-Order history update
	$with_price = (isset($_POST['with_price'])? $_POST['with_price']:1);
	$smarty->assign("show_price", $with_price);

	$mail_content = $smarty->fetch("ioMailData.tpl");

?>