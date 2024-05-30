<?php
class SoExport extends AdminFunctions {
	function exportDataToFile($order_id, $mfg, $show_price, $expType="xls") {
		global $smarty;
		$db = $this->db_obj;

		$so_qry = $this->gco_dyn("SoQry", "so_query.php", "includes/ord_select_stt/");
		$orderinfoqry = $db->query($so_qry->orderInfo($order_id));
		$productinfoqry = $db->query($so_qry->productInfo($order_id));

		$orderinfores = $db->fetch_assoc_single($orderinfoqry);
		$soinfores = $this->gco_dyn("SOInfoList", "so_info.php", "includes/order_data/");
		$productinfores = $soinfores->GenProductArr($db->fetch_assoc($productinfoqry));

		if($expType=="xml"){
			$orderinfores['branch_admin_email'] = $this->GetBranchAdmins($orderinfores['branch_id'], 'so_cc', 'str');
		}

		$smarty->assign('order_result', $orderinfores);
		$smarty->assign('products_result', $productinfores);

		$smarty->assign('misc_result', $soinfores->GetMiscItems($orderinfores['job_order_id']));
		$smarty->assign('show_price', $show_price);

		if($expType=="xls"){
			return $smarty->fetch("soExcelData.tpl");
		} else if($expType=="xml"){
//			return $smarty->fetch("soXmlData.tpl");
			return $this->formatBrXmlData($smarty->fetch("soXmlData.tpl"));
		}
	}
}
?>