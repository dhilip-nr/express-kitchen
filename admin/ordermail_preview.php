<?php
require_once("includes/config.php");
$mail_template = $_POST['ajax_mode'];
$own_damage = array("Wrong Item ordered", "Installer Damaged the item");

//echo "<pre>"; print_r($_POST); echo "</pre>"; exit; 

switch($mail_template){	
	case 'mo_mail_preview':
		$order_id = $_POST['order_id'];
	
		$opt_arr = array('width', 'depth', 'height', 'doorwidth', 'squarefeet', 'linealfeet', 'fieldsqfeet', 'accentsqfeet', 'bullnoselnfeet', 'inside_corners', 'outside_corners', 'seams');
		$smarty->assign("opt_arr", $opt_arr);
	
	// To get Category wise options --
		$optres = array();
/*
		$catqry = $db->query("select id, options from remode_category where options!=''");
		$catres = $db->fetch_assoc($catqry);
		foreach ($catres as $catopt){
			$optqry= $db->query("select id, name, db_name from remode_options where id IN (".$catopt['options'].") order by sort_order");
			$optres[$catopt['id']] = $db->fetch_assoc($optqry);
		}
*/
		$smarty->assign("catoptions", $optres);
	// -- To get Category wise options

		$dealers_mat_vendor = [];
		if (in_array($_SESSION[APPSESVAR."_adminuser"]["role"], ['dealer', 'branchadmin'])){
			$dealers_mat_vendor = $db->fetch_assoc_single($db->query("select `group` company, `contact` name, `email`, `phone`, concat(address,', ',city,', ',state,' ',zipcode)address`from remode_vendors where alias='RDI'"));
			if(!is_array($dealers_mat_vendor))
				$dealers_mat_vendor = ["company"=>"", "name"=>"", "email"=>"", "phone"=>"", "address"=>""];
		}
		$smarty->assign("dealers_mat_vendor", $dealers_mat_vendor);
	
		$smarty->assign("optexcepts", $br_fldsfromopt);
	
		$mo_qry = $fn->gco_dyn("MoQry", "mo_query.php", "includes/ord_select_stt/");
		$orderinfoqry = $db->query($mo_qry->orderInfo($order_id));
		if (!in_array($_SESSION[APPSESVAR."_adminuser"]["role"], ['dealer', 'branchadmin']))
			$productinfoqry = $db->query($mo_qry->productInfo($order_id, $_POST["sending_vendor"]));
		else
			$productinfoqry = $db->query($mo_qry->productInfo($order_id));
	
		$moinfores = $fn->gco_dyn("MOInfoList", "mo_info.php", "includes/order_data/");
		$orderinfores = $db->fetch_assoc_single($orderinfoqry);		
		$productinfores = $moinfores->GenProductArr($db->fetch_assoc($productinfoqry));
	
		$smarty->assign("MOInfoList", $moinfores);
	
		$smarty->assign('order_result', $orderinfores);
		$smarty->assign('products_result', $productinfores);
	
		$order_res_count = $db->num_rows($productinfoqry);
		$smarty->assign('order_res_count', $order_res_count);
	
		if($order_res_count!='0'){        
	
	
		// Re-Order history update -----
			$where_orderfilter = "";
			if(isset($_POST['ordersubitem_id'])){
				$explode_suborder_item_id = explode(",", $_POST['ordersubitem_id']);
				$suborder_item_id = array();
				foreach($explode_suborder_item_id as $si_value){
					$suborder_item_exp = explode("_", $si_value);
					$suborder_item_id[] = $suborder_item_exp[1];
				}
		
				$where_orderfilter = " AND rom.id in (".implode(",", $suborder_item_id).")";
			} else if(isset($_POST['order_mainitem_id'])){	
				$explode_mainorder_item_id = explode(",", $_POST['order_mainitem_id']);
				$filter_order_mainitem_id = implode("','", $explode_mainorder_item_id);
				$where_orderfilter = " AND rom.pricingmodel in ('".$filter_order_mainitem_id."')";
			}
		// ----- Re-Order history update
	
			$subItemQuery= $db->query("select 
				rom.id, trim(pricingmodel) pricingmodel, Item, quantity, UOM, sku, fab, cost, sold_by, unit_per_pack from remode_orderitems_material rom
				inner join remode_orders ro on ro.order_id=rom.order_id
				WHERE ro.id='".$order_id."' ".$where_orderfilter."
				ORDER BY pricingmodel asc");
	
			$subItemQueryRes = $db->fetch_assoc($subItemQuery);
			$smarty->assign('order_subquery', $subItemQueryRes);
	
			$ordersubitems_countmap = array();
			if(count($subItemQueryRes[0])>0){
				foreach($subItemQueryRes as $subItem_Res){
					$ordersubitems_countmap[] = $subItem_Res['pricingmodel'];
				}
			}
			$ordersubitems_countmap = array_count_values($ordersubitems_countmap);
	
			$smarty->assign('order_subitems_count', $ordersubitems_countmap);
		}
		// get material items ends ---
	
	
		if(isset($_POST['ordersubitem_id']) || isset($_POST['order_mainitem_id'])){
			$misc_item_query = "";
		} else {
			if (!in_array($_SESSION[APPSESVAR."_adminuser"]["role"], ['dealer', 'branchadmin']))
				$misc_item_query = $db->query($mo_qry->miscInfo($orderinfores['job_order_id'], $_POST["sending_vendor"]));
			else
				$misc_item_query = $db->query($mo_qry->miscInfo($orderinfores['job_order_id']));
		}
	
		$misc_count = $db->num_rows($misc_item_query);
		$misc_result = $db->fetch_assoc($misc_item_query);
	
		$smarty->assign('misc_count',$misc_count);
		$smarty->assign('misc_result',$misc_result);
		
		$vendor_list_query = $db->query('SELECT distinct `alias`, `group` FROM `remode_vendors`');
		$vendor_query_result = $db->fetch_assoc($vendor_list_query);
		$smarty->assign('vendor_query_result', $vendor_query_result);
		
		// for misc item starts
		$category_query = $db->query('select id, name from remode_category where id in("1","2","3","4","5","6")');
		$category_query_result = $db->fetch_assoc($category_query);
		$smarty->assign('category_query_result', $category_query_result);
	
	// Re-Order history update -----
	
		if(isset($_POST['ordersubitem_id'])){
			$explode_suborder_item_id = explode(",", $_POST['ordersubitem_id']);
	
			$ordersubitem_id = array();
			$suborder_item_id = array();
			$suborder_item_cost = array();
			foreach($explode_suborder_item_id as $si_value){
				$suborder_item_exp = explode("_", $si_value);
				$suborder_item_id[] = $suborder_item_exp[1];
				$suborder_item_cost[$suborder_item_exp[1]] = $suborder_item_exp[2];
				$ordersubitem_id[] = $suborder_item_exp[0]."_".$suborder_item_exp[1];
			}
			$implode_suborder_item_id = implode("','", $suborder_item_id);
	
			$smarty->assign('ordersubitem_id', $ordersubitem_id);
			$own_damagedmat_cost = 0;
	
			if($_POST['reason_content']=='Wrong Item ordered' || $_POST['reason_content']=='Installer Damaged the item'){
				$own_damagedmat_cost = 1;
			}
	
			$smarty->assign('own_damagedmat_cost', $own_damagedmat_cost);
	
			$supplement_query = $db->query("select rom.id, Item, trim(pricingmodel) pricingmodel, count(pricingmodel) material_count
				from remode_orderitems_material rom
				inner join remode_orders ro on ro.order_id=rom.order_id
				WHERE ro.id='".$order_id."' and rom.id in ('".$implode_suborder_item_id."')
				group by rom.pricingmodel");
			$fetch_supplement_res = $db->fetch_array($supplement_query);
	
			$items_model=array();
			$reorder_insert_qry = array();
	
			foreach($fetch_supplement_res as $matsub_data){
				$items_model[] = $matsub_data['Item'];
				$mat_uq_cost = explode("-", $suborder_item_cost[$matsub_data['id']]);
				$reorder_insert_qry[] = "(".$_POST['job_order_id'].", '".$matsub_data['Item']."', '".$matsub_data['pricingmodel']."', '".$_POST['sending_vendor']."', '".$mat_uq_cost[0]."', '".$mat_uq_cost[1]."', '".$mat_uq_cost[2]."', '".$_POST['reason_content']."', NOW(), ".$own_damagedmat_cost.")";
			}
	
			$db->query("INSERT INTO remode_reordered_materials (order_id, item, pricingmodel, vendor, uom, qty, cost, reason, created_on, cover_cost) VALUES ".implode(",", $reorder_insert_qry));
	
			if($_POST['reason_content']=='Wrong Item ordered' || $_POST['reason_content']=='Installer Damaged the item'){
				$ordItemCost = $fn->getCurrentItemCost($order_id);
				$db->query("update remode_orders set total_prdcost='".$ordItemCost['material']."', total_lbrcost='".$ordItemCost['labor']."', total_amount='".$ordItemCost['retail']."', net_amount='".$ordItemCost['netamount']."', margin_cost=net_amount-total_prdcost-total_lbrcost where id='".$order_id."'");
			}
			
			$implode_items_model = implode(", ", $items_model);
			$comments = "Reorder sent for <b>".$_POST['sending_vendor']."</b> material supplements <b>".$implode_items_model."</b> Due to ".$_POST['reason_content'];
	
			$db->query("insert into remode_order_revisions set order_id = '".$_POST['job_order_id']."',
						category ='Re-ordered',
						comments ='".$comments."',
						posted_by = '".$_SESSION[APPSESVAR.'_adminuser']['un']."',
						created_at =now()");
	
		} else if(isset($_POST['order_mainitem_id'])){
		
			$explode_mainorder_item_id = explode(",", $_POST['order_mainitem_id']);
			$smarty->assign('order_mainitem_id', $explode_mainorder_item_id);
			$implode_order_mainitem_id = implode("','",$explode_mainorder_item_id);
			
			$remode_orderitems_query = $db->query("select pricingmodel,description from remode_orderitems where id in('".$implode_order_mainitem_id."')");
			$pricing_model_revi = array();
	
			$fetch_orderitem_res = $db->fetch_array($remode_orderitems_query);
			foreach($fetch_orderitem_res as $fetch_orderitem_query){
				if($fetch_orderitem_query['pricingmodel']!=""){
					$pricing_model_revi[] = $fetch_orderitem_query['pricingmodel'];
				}
				else{	
					$pricing_model_revi[] = $fetch_orderitem_query['description'];
				}
			}
			$implode_description_revi = implode(",",$pricing_model_revi);
			$comments = "Reorder sent for <b>".$_POST['sending_vendor']."</b> materials <b>".$implode_description_revi."</b> Due to ".$_POST['reason_content'];
	
			$db->query("insert into remode_order_revisions set order_id = '".$_POST['job_order_id']."',
						category ='Re-ordered',
						comments ='".$comments."',
						posted_by = '".$_SESSION[APPSESVAR.'_adminuser']['un']."',
						created_at =now()");
	
		}
	
	// ----- Re-Order history update
		$with_price = (isset($_POST['with_price'])? $_POST['with_price']:1);
		$smarty->assign("show_price", $with_price);
		$template_name = "";
		if($_SESSION[APPSESVAR.'_adminuser']['un']=='dealer')
			$template_name = "material_order/moDealerMail.tpl";
		else
			$template_name = "material_order/moAdminMail.tpl";
		
		$MaterialOrderData = $smarty->fetch($template_name);

		$json_result = json_encode($MaterialOrderData);
		echo $json_result;
	break;
}
?>