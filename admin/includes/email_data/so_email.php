<?php

		$order_id = str_ireplace(ORD_PREFIX,'',$_GET['id']);

		$so_qry = $fn->gco_dyn("SoQry", "so_query.php", "includes/ord_select_stt/");
		$orderinfoqry = $db->query($so_qry->orderInfo($order_id));
		$productinfoqry = $db->query($so_qry->productInfo($order_id));

		$orderinfores = $db->fetch_assoc_single($orderinfoqry);
		$soinfores = $fn->gco_dyn("SOInfoList", "so_info.php", "includes/order_data/");
		$productinfores = $soinfores->GenProductArr($db->fetch_assoc($productinfoqry));

		$smarty->assign('order_result', $orderinfores);
		$smarty->assign('products_result', $productinfores);

		$vendor_list_query = $db->query('SELECT distinct `alias`,`group` FROM `remode_vendors`');
		$vendor_query_result = $db->fetch_assoc($vendor_list_query);
		$smarty->assign('vendor_query_result', $vendor_query_result);
		
		$category_query = $db->query('select id, name from remode_category where id in("1","2","3","4","5","6")');
		$category_query_result = $db->fetch_assoc($category_query);
		$smarty->assign('category_query_result', $category_query_result);

		// for misc items
		$smarty->assign('misc_result', $soinfores->GetMiscItems($orderinfores['job_order_id']));
		$smarty->assign('show_price', $_POST['with_price']);

		$mail_content = $smarty->fetch("soMailData.tpl");

?>