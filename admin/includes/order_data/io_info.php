<?php
class IOInfoList extends AdminFunctions{
	function GenProductArr($productinfores){
		$db = $this->db_obj;

		$catqry = $db->query("select id, options from remode_category where options!=''");
		$catres = []; // $db->fetch_assoc($catqry);
		$optres = array();
		foreach ($catres as $catopt){
			$optqry= $db->query("select id, name, db_name from remode_options where id IN (".$catopt['options'].") order by sort_order");
			$optres[$catopt['id']] = $db->fetch_assoc($optqry);
		}

		$opt_arr = array('width', 'depth', 'height', 'doorwidth', 'squarefeet', 'linealfeet', 'fieldsqfeet', 'accentsqfeet', 'bullnoselnfeet', 'inside_corners', 'outside_corners', 'seams');
		$uom_except_arr = array("SF"=>"squarefeet", "LF"=>"linealfeet", "EA"=>"");


		if(!empty($productinfores))
			foreach($productinfores as $key=>$value){
				if ($value['uom'] == "SF" || $value['uom'] == "LF") {
					if ($value['orderinguom'] == "EA" && is_numeric($value['orderingincrement']) && $value['orderingincrement'] > 0)
						$mat_qty = $value['quantity']*ceil($value['slfeet']/$value['orderingincrement']);
					else
						$mat_qty = $value['quantity']*$value['slfeet'];
					$qty_of_uom = $value['quantity']*$value['slfeet'];
				} else {
					$qty_of_uom = $value['quantity'];
					$mat_qty = $value['quantity'];
				}
/*
				if ($value['cat_id']==41){
					$qty_of_uom = $value['quantity']*$value['slfeet'];
					$mat_qty = $value['quantity']*$value['slfeet'];
				}
*/	
				$productinfores[$key]['pgen_qty'] = $qty_of_uom;
				$productinfores[$key]['pgen_matqty'] = $mat_qty;
				$pgen_options = "";	
	
	
				if (!empty($optres) && $value['cat_id']!=6){
					foreach ($optres[$value['cat_id']] as $catopt){
						preg_match("/".$catopt['db_name']."#(.*?)#".$catopt['db_name']."/", $value['minmax'], $minmax);
						preg_match("/".$catopt['db_name']."#(.*?)#".$catopt['db_name']."/", $value['options'], $matches);
		
						if (isset($matches[1]) && $uom_except_arr[$value['uom']]!=$catopt['db_name']){
	
							if (in_array($catopt['db_name'], $opt_arr) && isset($minmax[1]) && $minmax[1]!=""){
								if ($catopt['db_name'] == "fieldsqfeet")
									$productinfores[$key]['fsf'] = $matches[1];
								else if ($catopt['db_name'] == "accentsqfeet")
									$productinfores[$key]['alf'] = $matches[1];
								else if ($catopt['db_name'] == "bullnoselnfeet")
									$productinfores[$key]['blf'] = $matches[1];
		
								if ($catopt['db_name'] == "linealfeet")
									$productinfores[$key]['linealfeet'] = $matches[1];
/*		
								if ($value['cat_id']==45){
									if ($catopt['db_name'] == "inside_corners")
										$productinfores[$key]['icp_num'] = $matches[1];
									else if ($catopt['db_name'] == "outside_corners")
										$productinfores[$key]['ocp_num'] = $matches[1];
									else if ($catopt['db_name'] == "seams")
										$productinfores[$key]['seam_num'] = $matches[1];
								}
*/
							}
	
							$pgen_options .= "<br>".$catopt['name'].": ".$matches[1];
						}
					}
				}
				$productinfores[$key]['laborcost'] = $value['laborcost']*$qty_of_uom;
				$productinfores[$key]['prd_options'] = trim($pgen_options, "<br>");
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

		if ($value['manufacturer']=='MSI'){
			if ($value2['fab']=="FT"){
				$submat_uom = $value2['sold_by'];
				$subitem_qty = ceil((($value['slfeet']/$value2['quantity'])+($value['slfeet']/$value2['quantity'])*$wastage)/$value2['unit_per_pack']);
			} else if ($value2['fab']=="FSF" || $value2['fab']=="ALF" || $value2['fab']=="BLF") {
				$submat_uom = $value2['sold_by'];
				if ($value2['fab']=="BLF") $wastage = 0.25;
			
				if ($value2['fab']=="BLF") {
					$subitem_qty = ceil(($msi_slf*$value2['quantity']/$value2['unit_per_pack'])+($msi_slf*$value2['quantity']*$wastage/$value2['unit_per_pack']));
				} else {
					$subitem_qty = ceil((($msi_slf/$value2['quantity'])+($msi_slf/$value2['quantity'])*$wastage)/$value2['unit_per_pack']);
				}
			} else {
				$subitem_qty = ceil(trim($value2['quantity'] * $submat_qty));
			}
		} else if ($value['cat_id']==45) {
			if ($value2['fab']=="IC")
				$submat_qty = $icp_num;
			else if ($value2['fab']=="OC")
				$submat_qty = $ocp_num;
			else if ($value2['fab']=="S")
				$submat_qty = $seam_num;

			$subitem_qty = ceil(trim($value2['quantity'] * $submat_qty));		
		} else if (in_array($value['cat_id'], array(24,86,87,88,89,90))){
			if ($value2['UOM']=="SF" || $value2['UOM']=="LF"){
				if ($value['cat_id']==24) $submat_qty=$linealfeet*12;
				else $submat_qty = ceil($value['slfeet']+$value['slfeet']*$wastage);
			} else {
				if ($value2['unit_per_pack']==0) $value2['unit_per_pack']=1;
				$submat_qty = $value['slfeet']/$value2['unit_per_pack'];
			}
			$subitem_qty = ceil(trim($value2['quantity'] * $submat_qty));
		} else {
			$subitem_qty = ceil(trim($value2['quantity'] * $submat_qty));
		}

		return array("uom"=>$submat_uom, "qty"=>$subitem_qty);
	}

	function SubLabMass($value, $value2){
        if (trim($value2['lab_fab']) == "BNT" && $value['manufacturer']=="MSI")
            $labqty_of_uom = $value['blf'];
        else if ($value2['uom'] == "SF" || $value2['uom'] == "LF")
            $labqty_of_uom = $value['slfeet']*$value['quantity'];
        else
            $labqty_of_uom = $value['quantity'];

		$qty = $value2['quantity']*$labqty_of_uom;
		$cost = $value2['cost']*$qty;

		return array("uom"=>$value2['uom'], "qty"=>$qty, "cost"=>$cost);
	}

	function GetMiscItems($order_id){
		$db = $this->db_obj;

		$misc_item_query = $db->query("SELECT * FROM `remode_orderitems_miscs` WHERE order_id='".$order_id."' AND is_approved!=0");		
		$misc_count = $db->num_rows($misc_item_query);
		$misc_result = $db->fetch_assoc($misc_item_query);

		return array($misc_count, $misc_result);
	}
}
?>