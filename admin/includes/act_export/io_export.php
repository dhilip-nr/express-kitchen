<?php
class IoExport extends AdminFunctions {
	function exportDataToFile($order_id, $mfg, $show_price, $expType="xls") {
		$db = $this->db_obj;
		global $br_fldsfromopt, $smarty;
		$smarty->assign('APPSESVAR', APPSESVAR);

		$smarty->assign('opt_arr', array('width', 'depth', 'height', 'doorwidth', 'squarefeet', 'linealfeet', 'fieldsqfeet', 'accentsqfeet', 'bullnoselnfeet', 'inside_corners', 'outside_corners', 'seams'));

		$smarty->assign("potexcepts", $br_fldsfromopt);

		$io_qry = $this->gco_dyn("IoQry", "io_query.php", "includes/ord_select_stt/");
		$orderinfoqry = $db->query($io_qry->orderInfo($order_id));
		$productinfoqry = $db->query($io_qry->productInfo($order_id));

		$ioinfores = $this->gco_dyn("IOInfoList", "io_info.php", "includes/order_data/");
		$orderinfores = $db->fetch_assoc_single($orderinfoqry);
		$productinfores = $ioinfores->GenProductArr($db->fetch_assoc($productinfoqry));
		$smarty->assign("IOInfoList", $ioinfores);

		if($expType=="xml"){
			$orderinfores['branch_admin_email'] = $this->GetBranchAdmins($orderinfores['branchid'], 'io_cc', 'str');
		}
		$smarty->assign('order_result', $orderinfores);
		$smarty->assign('products_result', $productinfores);

		// get labor items start ---
		$subItemQuery= $db->query("select 
		rol.*, rol.pricing_model pricingmodel
		from remode_orderitems_labor rol 
		inner join remode_orders ro on ro.order_id=rol.order_id
		where ro.id = '".$order_id."'
		order by rol.pricing_model");

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
		$damage_installer_result = array();
		$damage_installer_qry = $db->query("select item, pricingmodel, uom, qty, cost from remode_reordered_materials where reason='Installer Damaged the item' and order_id = ".$orderinfores['job_order_id']);

		$damage_installer_result = $db->fetch_assoc($damage_installer_qry);
		$smarty->assign('damage_installer_result', $damage_installer_result);
		// installer damaged items - ends

		$smarty->assign('show_price', $show_price);

		if($expType=="xls")
			return $smarty->fetch("ioExcelData.tpl");
//		if($expType=="pdf")
//			return $smarty->fetch("ioPdfData.tpl");
		else 
//			return $smarty->fetch("ioXmlData.tpl");
			return $this->formatBrXmlData($smarty->fetch("ioXmlData.tpl"));			
	}
}
?>