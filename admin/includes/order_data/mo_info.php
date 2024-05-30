<?php
class MOInfoList extends AdminFunctions{

	function GetOrdHeaderData($ord_info, $prd_info, $type="cust"){
		$db = $this->db_obj;

		$return_data = array();
//		$customer_info = $vendor_info = $shipto_info = $billto_info = "";

		switch($type){
			case "cust":
			break;

			case "ship_to":
				global $appConstData;
				$appSettings = explode(",", $appConstData['custom_shipto']);

				if (in_array($prd_info['manufacturer'], $appSettings)){
					$return_data = array(
						"company" => " - ",
						"name" => $ord_info['customer_name'],
						"email" => $ord_info['customer_email'],
						"phone" => $ord_info['customer_telephone'],
						"address" => $ord_info['customer_address'].", ".$ord_info['customer_city'].", ".$ord_info['customer_state']." ".$ord_info['customer_zipcode']
					);
				} else {
					$return_data = array(
						"company" => $ord_info['inst_company'],
						"name" => $ord_info['inst_firstname']." ".$ord_info['inst_lastname'],
						"email" => $ord_info['inst_email'],
						"phone" => $ord_info['inst_phone'],
						"address" => $ord_info['inst_address'].", ".$ord_info['inst_city'].", ".$ord_info['inst_state']." ".$ord_info['inst_zipcode']
					);
				}
			break;
			
			case "vendor":
				$bill_to = $db->fetch_assoc_single($db->query("select mat_bill from remode_company_master where alias='".$ord_info['company']."'"));

				if ($bill_to['mat_bill'] == "self"){
					$return_data = array(
						"company" => $ord_info['inst_company'],
						"detail" => $ord_info['inst_company'],
						"name" => $ord_info['inst_firstname']." ".$ord_info['inst_lastname'],
						"email" => $ord_info['inst_email'],
						"phone" => $ord_info['inst_phone'],
						"address" => $ord_info['inst_address'].", ".$ord_info['inst_city'].", ".$ord_info['inst_state']." ".$ord_info['inst_zipcode']
					);
				} else {
					$return_data = array(
						"company" => $prd_info['manufacturer'],
						"detail" => $prd_info['manufacturer_detail'],
						"name" => $prd_info['manufacturer_contact'],
						"email" => $prd_info['manufacturer_email'],
						"phone" => $prd_info['manufacturer_phone'],
						"address" => $prd_info['manufacturer_address']
					);
				}
			break;
			
		}
		return $return_data;

	}

	function GenProductArr($productinfores){
		$db = $this->db_obj;

		$catqry = $db->query("select id, options from remode_category where options!=''");
		$catres = array(); //$db->fetch_assoc($catqry);
		$optres = array();
		foreach ($catres as $catopt){
			$optqry= $db->query("select id, name, db_name from remode_options where id IN (".$catopt['options'].") order by sort_order");
			$optres[$catopt['id']] = $db->fetch_assoc($optqry);
		}

		$opt_arr = array('width', 'depth', 'height', 'doorwidth', 'squarefeet', 'linealfeet', 'fieldsqfeet', 'accentsqfeet', 'bullnoselnfeet', 'inside_corners', 'outside_corners', 'seams');
		$uom_except_arr = array("SF"=>"squarefeet", "LF"=>"linealfeet", "EA"=>"");

		if(!empty($productinfores))
			foreach ($productinfores as $key=>$value){
		
				if (in_array($value['uom'], array("SF", "LF"))){
					if ($value['orderinguom'] == "EA" && is_numeric($value['orderingincrement']) && $value['orderingincrement'] > 0){
						$mat_qty = $value['quantity']*ceil($value['slfeet']/$value['orderingincrement']);
					} else {
						$mat_qty = $value['quantity']*$value['slfeet'];
					}	
					$qty_of_uom = $value['quantity']*$value['slfeet'];
				} else {
					$qty_of_uom = $mat_qty = $value['quantity'];
				}
		
				$productinfores[$key]['pgen_qty'] = $qty_of_uom;
				$productinfores[$key]['pgen_matqty'] = $mat_qty;
		
				$options_array = [];
				if (trim($value['options'])!=""){
					$options_array = json_decode($value['options'], true);
				}
				$productinfores[$key]['productcost'] = $value['productcost']*$qty_of_uom;
				$productinfores[$key]['prd_options'] = $options_array;
			}

		return $productinfores;
	}

	function SubMatMass($value, $value2){
		$msi_slf = 0;
		$submat_qty = 0;

		if (trim($value2['UOM'])=="Bag")
			$submat_qty = $value['quantity']*$value['slfeet'];
		else if ($value2['fab']=="FSF")
			$msi_slf = $value['quantity']*$value['fsf'];
		else if ($value2['fab']=="ALF")
			$msi_slf = $value['quantity']*$value['alf'];
		else if ($value2['fab']=="BLF")
			$msi_slf = $value['quantity']*$value['blf'];
		else
			$submat_qty = $value['pgen_matqty'];

		$wastage = 0.15;
		$submat_uom = $value2['UOM'];
		$cost = $value2['cost'];

		if ($value['manufacturer_alias']=='MSI'){
			if ($value2['fab']=="FT"){
				$submat_uom = $value2['sold_by'];
				$subitem_qty = ceil((($value['slfeet']/$value2['quantity'])+($value['slfeet']/$value2['quantity'])*$wastage)/$value2['unit_per_pack']);
				$subitem_cost = $cost*$subitem_qty*$value2['unit_per_pack'];
			} else if ($value2['fab']=="FSF" || $value2['fab']=="ALF" || $value2['fab']=="BLF") {
				$submat_uom = $value2['sold_by'];
				if ($value2['fab']=="BLF") $wastage = 0.25;
			
				if ($value2['fab']=="BLF") {
					$subitem_qty = ceil(($msi_slf*$value2['quantity']/$value2['unit_per_pack'])+($msi_slf*$value2['quantity']*$wastage/$value2['unit_per_pack']));
					$subitem_cost = $cost*$subitem_qty;
				} else {
					$subitem_qty = ceil((($msi_slf/$value2['quantity'])+($msi_slf/$value2['quantity'])*$wastage)/$value2['unit_per_pack']);
					$subitem_cost = $cost*$subitem_qty*$value2['unit_per_pack'];
				}
			} else {
				$subitem_qty = ceil(trim($value2['quantity'] * $submat_qty));
				$subitem_cost = $cost * $subitem_qty;
			}
		} else if ($value['cat_id']==45) {
			if ($value2['fab']=="IC")
				$submat_qty = $icp_num;
			else if ($value2['fab']=="OC")
				$submat_qty = $ocp_num;
			else if ($value2['fab']=="S")
				$submat_qty = $seam_num;
		
			$subitem_qty = ceil(trim($value2['quantity'] * $submat_qty));
			$subitem_cost = $cost * $subitem_qty;
		
		} else if (in_array($value['cat_id'], array(24,86,87,88,89,90))){
			if ($value2['UOM']=="SF" || $value2['UOM']=="LF"){
				if ($value['cat_id']==24) $submat_qty=$linealfeet*12;
				else $submat_qty = ceil($value['slfeet']+$value['slfeet']*$wastage);
			} else {
				if ($value2['unit_per_pack']==0) $value2['unit_per_pack']=1;
				$submat_qty = $value['slfeet']/$value2['unit_per_pack'];
			}
			$subitem_qty = ceil(trim($value2['quantity'] * $submat_qty));
			$subitem_cost = $cost * $subitem_qty;
		} else {
			$subitem_qty = ceil(trim($value2['quantity'] * $submat_qty));
			$subitem_cost = $value2['cost'] * $subitem_qty;
		}

		return array("uom"=>$submat_uom, "qty"=>$subitem_qty, "cost"=>$subitem_cost);
	}

}
?>