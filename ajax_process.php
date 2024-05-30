<?php
require_once('includes/config.php');

$action = isset($_REQUEST['action'])? $_REQUEST['action']:"";

switch ($action) {
	case 'get_json_data':
		$cid = $_SESSION[APPSESVAR.'_user']['co_id'];
		$file = (isset($_REQUEST['file'])? $_REQUEST['file']:"");
		
		if($file != "catalog-items.json"){
			$res = json_decode(file_get_contents("json/".$file), true);
			echo json_encode($res);
			exit;
		} else {
			$res_arr = ["items"=>[]];

			$cat_qry = "SELECT * FROM categories WHERE active=1 ORDER BY sort_order, name";
			$cat_res = $db->fetch_assoc($db->query($cat_qry));

//			$prd_qry = "SELECT *  FROM catalog_products where active=1 ORDER BY item_group, name";
			$prd_qry = "SELECT
				prd.name, prd.image, prd.model, prd.sku, prd.def_sizes, prd.item_group, prd.measurements, prd.uom, prd.type_code, price.material, price.labor, price.margin, price.retail
				FROM remode_company_prices price
				LEFT JOIN catalog_products prd on prd.id=price.product_id
				WHERE price.active=1 AND price.company_id='$cid' AND prd.is_primary=1
				ORDER BY prd.item_group, prd.name";
			$prd_res = $db->fetch_assoc($db->query($prd_qry));
			// echo'<pre>';
			// print_r($prd_res);
			// exit;
			foreach($cat_res as $k=>$row){
				$grp_item = [
					"name" => $row['name'],
					"default" => ($k==0? 1: 0),
					"items" => []
				];

				if(!empty($prd_res)){
					foreach($prd_res as $prow){
						if($row['name'] == $prow['item_group']) {
							$grp_item["items"][] = [
								"name" => $prow['name'],
								"image" => $prow['image'],
								"model" => $prow['model'],
								"sku" => $prow['sku'],
								"def_sizes" => json_decode($prow['def_sizes']),
								"measurements" => json_decode($prow['measurements']),
								"price" => $prow['retail'],
								"uom" => $prow['uom'],
								"is_default" => 0,
								"status" => 1,
								"type" => $prow['type_code'],
								"margin_per" => $prow['margin'],
								"material_cost" => $prow['material'],
								"labor_cost" => $prow['labor']
							];
						}
					}
				}

				$res_arr["items"][] = $grp_item;
			}

			echo json_encode($res_arr, JSON_PRETTY_PRINT);
			exit;
		}
	break;

	case 'catalog_data':
		$cid = $_SESSION[APPSESVAR.'_user']['co_id'];
		$file = "json/".$cid."/catalog-items.json";
		
		$res = json_decode(file_get_contents($file), true);
		echo json_encode($res[0]);
		exit;
	break;

	case 'colors':
		$file = (isset($_REQUEST['file'])? $_REQUEST['file']:"");
		
		if($file == "colors.json"){
			$res = json_decode(file_get_contents("json/".$file), true);
			echo json_encode($res);
			exit;
		}
		
	break;
		 
	case 'getDesign':
		$userid = $_SESSION[APPSESVAR.'_user']['id'];
		$prd_qry = "SELECT * from remode_user_designs WHERE userid=$userid AND status = 'active' and type='mydesign'";

		$prd_res = $db->fetch_assoc($db->query($prd_qry));
		echo json_encode($prd_res, JSON_PRETTY_PRINT);
		exit;
	break;

	case 'getDraft_Des':
		$userid = $_SESSION[APPSESVAR.'_user']['id'];
		$prd_qry = "SELECT * from remode_user_designs WHERE userid=$userid AND status = 'active' and type='draft'";

		$prd_res = $db->fetch_assoc($db->query($prd_qry));
		echo json_encode($prd_res, JSON_PRETTY_PRINT);
		exit;
	break;
	case 'get_price':
		$com_id = $_SESSION[APPSESVAR.'_user']['co_id'];
		$params= json_decode($_REQUEST['params']);
		$width=count(explode(' ',$params->width))>1?formatNumbers($params->width,'width'):$params->width;
		$height=count(explode(' ',$params->height))>1?formatNumbers($params->height,'height'):$params->height;
		$prd_qry = "SELECT cp.sku,rcp.retail as retail FROM catalog_products cp LEFT JOIN remode_company_prices rcp ON rcp.product_id=cp.id AND rcp.company_id=$com_id WHERE width=$width AND height = $height AND name= '".$params->name."' " ;
		$prd_res = $db->fetch_assoc($db->query($prd_qry));
		echo json_encode($prd_res, JSON_PRETTY_PRINT);
		exit;
	break;
	case 'deleteDesign':
		$id = $_REQUEST['id'];
		$prd_res = $db->query("DELETE FROM remode_user_designs WHERE id=$id");
		if($prd_res)
			echo json_encode(["status" => true], JSON_PRETTY_PRINT);
		else
			echo json_encode(["status" => false], JSON_PRETTY_PRINT);
		exit;
	break;		

	case 'getMaterial':
		$mat_qry=$db->query("SELECT * FROM remode_materials WHERE status=1" );
		$prd_res = $db->fetch_assoc($mat_qry);
		echo json_encode($prd_res, JSON_PRETTY_PRINT);
		exit;
	break;

	case 'getAddons':
		$mat_qry=$db->query("SELECT * FROM remode_addons_item WHERE status=1" );
		$prd_res = $db->fetch_assoc($mat_qry);
		echo json_encode($prd_res, JSON_PRETTY_PRINT);
		exit;
	break;

	case 'getHardwares':
		$mat_qry=$db->query("SELECT * FROM remode_hardware_items WHERE status=1" );
		$prd_res = $db->fetch_assoc($mat_qry);
		echo json_encode($prd_res, JSON_PRETTY_PRINT);
		exit;
	break;

	case 'getDoorStyle':
		$mat_id = (isset($_REQUEST['file'])? $_REQUEST['file']:"");
		$style_qry = $db->query("SELECT * FROM remode_door_style WHERE status=1 AND mat_id=$mat_id ORDER BY prod_name" );
		$prd_res = $db->fetch_assoc($style_qry);
		echo json_encode($prd_res, JSON_PRETTY_PRINT);
		exit;

	case 'getDoorColor':
		$prod_id = (isset($_REQUEST['file'])? $_REQUEST['file']:"");
		$like="%".$prod_id."%";
		$style_qry = $db->query("SELECT * FROM remode_door_colors WHERE color_like LIKE '$like' ORDER BY color_name" );
		$prd_res = $db->fetch_assoc($style_qry);
		echo json_encode($prd_res, JSON_PRETTY_PRINT);
		exit;

	case 'getHandleColors':
		$pull_id = (isset($_REQUEST['file'])? $_REQUEST['file']:"");
		$color_qry = $db->query("SELECT * FROM remode_pulls_items WHERE status=1 AND pull_id=$pull_id" );
		$prd_res = $db->fetch_assoc($color_qry);
		echo json_encode($prd_res, JSON_PRETTY_PRINT);
	exit;
	case 'getHandleFinish':
		$handle_qry=$db->query("SELECT * FROM remode_pulls_finish WHERE status=1 ORDER BY item_name");
		$prd_res = $db->fetch_assoc($handle_qry);
		echo json_encode($prd_res, JSON_PRETTY_PRINT);
		exit;
	break;


}
function formatNumbers($num,$type){
	// $num = "27 1/2";
	$split_num=(explode(" ",$num));
	$whole_num=$split_num[0];
	$dec_num=count($split_num)>1?(fractodec($split_num[1])):0;
	$tot = $whole_num+$dec_num;
	if($type=='width'){
		if($tot>24 && $tot<27)
		$tot=27;
		else if($tot>27 && $tot<30)
		$tot=30;
		else if($tot>30 && $tot<33)
		$tot=33;
		else if($tot>33 && $tot<36)
		$tot=36;
	}
	else if($type=='height' && $tot!=34.5){
		if($tot>30 && $tot<36)
		$tot=36;
		if($tot>36 && $tot<42)
		$tot=42;
	}
return $tot;
}
function fractodec($fraction){
	$numbers=explode("/",$fraction);
	return round($numbers[0]/$numbers[1],6);
}
?>
