<?php 

if(!isset($_SESSION)){ session_start(); }

require_once('consts.php');
require_once('dbconnect.php');

$db = new Database(HOSTNAME, HOSTUSER, HOSTPASS, HOSTDB, true);

$com_id = $_SESSION[APPSESVAR.'_user']['co_id'];
$cust_info = [];
if(isset($_POST['custinfo'])) {
	// if(is_array($_POST['custinfo']))
		$cust_info = $_POST['custinfo'];
	// else
		// $cust_info = (!is_array($_POST['custinfo'])? json_decode($_POST['custinfo'], 1): []);
}

//echo "<pre>"; print_r($_POST); exit;

if(isset($_POST['action'])){
    if($_POST['action']=='new') {
		$qry=$db->query("insert remode_customers (id, cid, lead_id, name, firstname, lastname, email, telephone, address, city, state, zipcode, origin, created_by, created_at, updated_at) values ( NOT NULL,'$com_id','','".$cust_info['cfname'].""." "."".$cust_info['clname']."','".$cust_info['cfname']."','".$cust_info['clname']."','".$cust_info['cemail']."','".$cust_info['cphone']."','".$cust_info['caddress']."','".$cust_info['ccity']."','".$cust_info['cstate']."','".$cust_info['czip']."','','', NOT NULL, NOT NULL)");
        $customer_id = $db->last_id($qry);

		$cust_data = $db->fetch_assoc_single($db->query("SELECT cs.id FROM remode_customers cs WHERE cs.cid='$com_id' AND cs.id = '".$customer_id."'"));
		createOrder($cust_data);
    } elseif($_POST['action']=='existing') {
		// echo "SELECT cs.id FROM remode_customers cs WHERE cs.cid='$com_id' AND cs.email = '".$cust_info['cemail']."'";exit;
        $cust_data = $db->fetch_assoc_single($db->query("SELECT cs.id FROM remode_customers cs WHERE cs.cid='$com_id' AND cs.email = '".$cust_info['cemail']."'"));

        if($cust_data) {
            createOrder($cust_data);
		} else {
			$obj=new stdClass;
			$obj->cmail=$cust_info['cemail'];
			$obj->status='not-exist';

			echo(json_encode($obj,true));
			exit;
		}
    }

	if($_POST['action']=="validate_customer"){
		$result = ["status" => ""];
 // echo "<pre>"; print_r($cust_info); exit;
		$cust_data = $db->fetch_assoc($db->query("SELECT cs.id FROM remode_customers cs WHERE cs.email = '".$cust_info['cemail']."'"));

		if($cust_data && count($cust_data))
			$result["status"] = "valid";

		echo(json_encode($result,true));
	}

}


 function createOrder($cus_data){
	$db = $GLOBALS['db'];
	$order_items_qry=array();
	$prd_count=0;
	$qty_count = 0;
	$com_info = $_SESSION[APPSESVAR.'_user'];
	$orderid = time().mt_rand(10,99);
	$raw_order_data = (json_decode(json_encode($_POST['orderinfo']),1));
	$sum_info_data = $_POST['suminfo'];
	$order_data = [];		
	foreach($raw_order_data as $key => $data){

		foreach($data as $item){
			$item_idx = (isset($item['sku'])? $item['sku'].trim($item['door_col']," ").trim($item['door_mat']," ").trim($item['door_style']," ").trim($item['drawer_front']," ").trim($item['handle_col']," ").trim($item['corbel']," ").trim($item['molding']," ").trim($item['valance']," ").trim($item['floating_shelf']," ").trim($item['misc_item']," "): "");
			$qty_count++;
			if(isset($order_data[$key][$item_idx]) && 
				($order_data[$key][$item_idx]['sku']==$item['sku']
				&& $order_data[$key][$item_idx]['group']==$item['group']
				&& $order_data[$key][$item_idx]['width']==$item['width']
				&& $order_data[$key][$item_idx]['depth']==$item['depth']
				&& $order_data[$key][$item_idx]['height']==$item['height']
				&& $order_data[$key][$item_idx]['door_col']==$item['door_col']
				&& $order_data[$key][$item_idx]['door_mat']==$item['door_mat']
				&& $order_data[$key][$item_idx]['door_style']==$item['door_style']
				&& $order_data[$key][$item_idx]['drawer_front']==$item['drawer_front']
				&& $order_data[$key][$item_idx]['handle_col']==$item['handle_col']
				&& $order_data[$key][$item_idx]['corbel']==$item['corbel']
				&& $order_data[$key][$item_idx]['molding']==$item['molding']
				&& $order_data[$key][$item_idx]['valance']==$item['valance']
				&& $order_data[$key][$item_idx]['floating_shelf']==$item['floating_shelf']
				&& $order_data[$key][$item_idx]['misc_item']==$item['misc_item']
				)
				){
//				echo '***',$order_data[$key][$item_idx]['door_col'],'-',$item['door_col'],' or ';
				$order_data[$key][$item_idx]['qty'] += 1;
		}else {
				// if(isset($order_data[$key]))
				// echo $order_data[$key][$item_idx]['door_col'],'-',$item['door_col'],' or ';
//				if(isset($order_data[$key][$item_idx]))
//					{echo'<pre>';print_r($order_data[$key][$item_idx]);}
				$order_data[$key][$item_idx]= $item;
				$order_data[$key][$item_idx]['qty'] = 1;
				
				$prd_count++;
			}
			// if(isset($order_data[$key]))
			// echo'<pre>';print_r($order_data[$key]);
		}
	}
	$order_total = $sum_info_data['total']-$sum_info_data['discount'];
	$promo_type = ($sum_info_data['promo_val'] == $sum_info_data['discount']? "flatrate": "percent");
	
	$insorder_qrystr = "INSERT INTO remode_orders (order_id, username, customer_id, branchid, pb_of_branch, company, company_id, app_id, ins_mode, store_num, lead_id, jobid, installer_id, total_product, total_qty, total_prdcost, total_lbrcost, total_amount, admin_fee, lt_lfwp, lt_amt, lfwp_amt, promo_percent, promo_type, promo_amt, apd_percent, apd_amt, adp_max, gen_con, permit_percent, permit_max, net_amount, margin_cost, target_margin, target_value, disc_sur_amt, trip_charge, comments, attachments, revision_comments, sent_material, sent_installer, dealer_mat_status, submitted_by, origin, mat_hold, status, on_change, created_at, completed_at, updated_at) VALUES ('$orderid', '".$com_info['email']."', '".$cus_data['id']."', '0', '', '', '".$com_info['co_id']."', '0', 'branch', '0', '', '', 0, '$prd_count', '$qty_count', '0.00', '0.00', '".$sum_info_data['total']."', '0.00', '0#0', '0.00', '0', '".($promo_type == "percent"? (int)$sum_info_data['promo_val']: 0)."', '".$promo_type."', '".$sum_info_data['discount']."', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '".$order_total."', '0.00', '', '0.00', '0.00', '0.00', '', '', '', '', '', '', '', 'online', '', 'quote', '', NOW(), '0000-00-00 00:00:00', '0000-00-00 00:00:00')";	
	$qry=$db->query($insorder_qrystr);

	$new_order_id = $db->last_id($qry);
	if($new_order_id) {
			// echo'<pre>';print_r($order_data);exit;
		foreach($order_data as $key =>$item){

			foreach($item as $pr){
				$prd_count++;
				$options = json_encode($pr['def_sizes']);
				$dr=json_encode($pr['conf']);
				$qry_order_items=$db->query("insert into remode_orderitems (id, order_id, order_num, name, description, product_id, pvar_id, category_id, group_id, category, quantity, uom, productcost, laborcost, price, margin, adnl_price, other_price, slfeet, dimension, options, minmax, laboruom, created_at, updated_at, pricingmodel, mat_lab, by_ip, is_approved, old_qsf, comments, customer_supplied, manufacturer, vendor_id, image) VALUES (NOT NULL,'$orderid','0','".$pr['name']."','".$pr['name']."','0','0','0','0','$key','".$pr['qty']."','".$pr['uom']."','".$pr['material_cost']."','".$pr['labor_cost']."','".$pr['price']."','0','0','0','1','".$options."','".$dr."','','',NOW(),NOW(),'".$pr['sku']."','0','','1','','','','','0','".$pr['image_url']."')");
			}
		}
	}

	saveDesign($new_order_id);

	$obj=new stdClass;
	$obj->order_id = "SO#".$new_order_id;
	$obj->status='success';
	

    echo( json_encode($obj,true));
}

function saveDesign($myFile){
	$db = $GLOBALS['db'];
	$root_path='../saved_designs/placed-order/';
	$userid = $_SESSION[APPSESVAR.'_user']['id'];
	$stringData = json_decode($_POST['designdata'], true);
	$img = $_POST['imagedata']; // Your data 'data:image/png;base64,AAAFBfj42Pj4';
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$imagedata = base64_decode($img);

	file_put_contents($root_path.'images/'.$myFile.'.png',$imagedata);
	file_put_contents($root_path.'data/'.$myFile.'.json',$stringData);

	$qry=$db->query("INSERT INTO remode_user_designs (id,userid, image_path, json_path, status,type) VALUES (NOT NULL,'$userid','$myFile','$myFile','active','placed-order')");
}


?>
