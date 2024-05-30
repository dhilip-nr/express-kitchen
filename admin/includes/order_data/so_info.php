<?php
class SOInfoList extends AdminFunctions{
	function GenProductArr($productinfores){
		$db = $this->db_obj;

/*
		$catqry = $db->query("select id, options from remode_category where options!=''");
		$catres = $db->fetch_assoc($catqry);
		$catoptions  = array();

		foreach ($catres as $catopt){
			$optqry = $db->query("select id, name, db_name from remode_options where id IN (".$catopt['options'].") order by sort_order");
			$catoptions[$catopt['id']]= $db->fetch_assoc($optqry);
		}
*/
		$catres  = array();
		$catoptions  = array();


		if(!empty($productinfores))
			foreach($productinfores as $key=>$value){
				$pgen_pricingmodel = $value['pricingmodel'];
	
				$productinfores[$key]['pricing_model'] = trim($pgen_pricingmodel);
				$pgen_options = "";
	
				if (trim($value['cat_id'])!="" && $value['cat_id']!=6){
					foreach ($catoptions[$value['cat_id']] as $catopt){
						preg_match("/".$catopt['db_name']."#(.*?)#".$catopt['db_name']."/", $value['options'], $matches);
						if (isset($matches[1]) && (!in_array($catopt['db_name'], array("squarefeet","linealfeet")) || !in_array($value['uom'], array("SF","LF"))))
							$pgen_options .= " | ".strtoupper($catopt['name']).": ".$matches[1];
					}
				}
	
				$productinfores[$key]['prd_options'] = trim($pgen_options, " | ");
	
				if ($value['uom'] == "SF" || $value['uom'] == "LF"){
					$pgen_qty = $value['quantity']*$value['slfeet'];
				} else {
					$pgen_qty = $value['quantity'];
				}
	
				$productinfores[$key]['prd_qty'] = $pgen_qty;
	
				$pgen_price = ($value['price'] * $value['slfeet'] * $value['quantity']) + $value['adnl_price'] + $value['other_price'];
				$productinfores[$key]['prd_price'] = $pgen_price;
			}

		return $productinfores;
	}

	function GetMiscItems($order_id){
		$db = $this->db_obj;

		$misc_item_query = $db->query("SELECT * FROM `remode_orderitems_miscs` WHERE order_id='".$order_id."' AND is_approved!=0");		
		$misc_count = $db->num_rows($misc_item_query);
		$misc_result = $db->fetch_assoc($misc_item_query);

		return array($misc_count, $misc_result);
	}
	
	function GetInsEmail($installer_id){
		$db = $this->db_obj;

		$installer_cc_qry = $db->query("select concat(email,',',email_cc) as email from remode_installers where id=".$installer_id);
		$installer_email = $db->fetch_assoc_single($installer_cc_qry);

		return $installer_email;
	}
}
?>