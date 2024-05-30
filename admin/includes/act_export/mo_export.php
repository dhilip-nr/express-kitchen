<?php
class MoExport extends AdminFunctions {
	function exportDataToFile($order_id, $mfg, $show_price, $expType="xls", $mo_admin_view=false) {
		$db = $this->db_obj;
		global $br_fldsfromopt, $smarty;

		$opt_arr = array('width', 'depth', 'height', 'doorwidth', 'squarefeet', 'linealfeet', 'fieldsqfeet', 'accentsqfeet', 'bullnoselnfeet', 'inside_corners', 'outside_corners', 'seams');
		$smarty->assign("opt_arr", $opt_arr);

		$dealers_mat_vendor = [];
		if (in_array($_SESSION[APPSESVAR."_adminuser"]["role"], ['dealer', 'branchadmin'])){
			$dealers_mat_vendor = $db->fetch_assoc_single($db->query("select `group` company, `contact` name, `email`, `phone`, concat(address,', ',city,', ',state,' ',zipcode) address from remode_vendors where alias='RDI'"));
			if(!is_array($dealers_mat_vendor))
				$dealers_mat_vendor = ["company"=>"", "name"=>"", "email"=>"", "phone"=>"", "address"=>""];
		}
		$smarty->assign("dealers_mat_vendor", $dealers_mat_vendor);

		$smarty->assign("optexcepts", $br_fldsfromopt);
	
		$mo_qry = $this->gco_dyn("MoQry", "mo_query.php", "includes/ord_select_stt/");
		$orderinfoqry = $db->query($mo_qry->orderInfo($order_id));
		$productinfoqry = $db->query($mo_qry->productInfo($order_id, $mfg));
	
		$moinfores = $this->gco_dyn("MOInfoList", "mo_info.php", "includes/order_data/");
		$orderinfores = $db->fetch_assoc_single($orderinfoqry);		
		$productinfores = $moinfores->GenProductArr($db->fetch_assoc($productinfoqry));
	
		$smarty->assign("MOInfoList", $moinfores);

		if($expType=="xml"){
			$orderinfores['branch_admin_email'] = $this->GetBranchAdmins($orderinfores['branchid'], 'io_cc', 'str');
		}
	
		$smarty->assign('order_result', $orderinfores);
		$smarty->assign('products_result', $productinfores);
	
		$order_res_count = $db->num_rows($productinfoqry);
		$smarty->assign('order_res_count', $order_res_count);
	
		if($order_res_count!='0'){
	
			$subItemQuery= $db->query("select 
				rom.id, trim(pricingmodel) pricingmodel, Item, quantity, UOM, sku, fab, cost, sold_by, unit_per_pack from remode_orderitems_material rom
				inner join remode_orders ro on ro.order_id=rom.order_id
				WHERE ro.id='".$order_id."' 
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
	
	
		$misc_item_query = $db->query($mo_qry->miscInfo($orderinfores['job_order_id'], $mfg));
	
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

		$smarty->assign('show_price', $show_price);

		if($expType=="xls"){
			if($mo_admin_view)
				return $smarty->fetch("material_order/moAdminExcel.tpl");
			else
				return $smarty->fetch("material_order/moDealerExcel.tpl");
//		if($expType=="pdf")
//			return $smarty->fetch("ioPdfData.tpl");
		} else {
			if($mo_admin_view)
				return $this->formatBrXmlData($smarty->fetch("material_order/moAdminXml.tpl"));
			else
				return $this->formatBrXmlData($smarty->fetch("material_order/moDealerXml.tpl"));
		}

	}
}
?>