<?php
require_once("includes/config.php");

if(!isset($_SESSION[APPSESVAR.'_adminuser']['un'])){
	header('HTTP/1.1 401 Unauthorized');
	exit;
}

if(isset($_POST['ajax_mode'])) {
	$ajax_mode = $_POST['ajax_mode'];
} else {
	$ajax_mode = "";
}

$order_id = (isset($_REQUEST['order_id'])?str_ireplace(ORD_PREFIX,'',$_REQUEST['order_id']):"");
$sys_orderinfo_res = $db->fetch_assoc_single($db->query("select order_id, branchid, company_id, app_id from remode_orders where id='".$order_id."'"));
$sys_order_id = $sys_orderinfo_res['order_id'];
$order_branchid = $sys_orderinfo_res['branchid'];

switch($ajax_mode) {

	case "get_installers":
		$branch_id = $_REQUEST['branch_id'];

		$insid_qry = "SELECT alt_installers FROM remode_installers_branch_map WHERE cid='".$sys_orderinfo_res['company_id']."' and branch_id='".$branch_id."'";
		$insid_res = $db->fetch_assoc_single($db->query($insid_qry));

		$sql_installer = "SELECT id, installer FROM remode_installers 
			WHERE id in (".$insid_res['alt_installers'].") and status='1'
			group by ins_group
			order by installer";
		$installers_res = $db->fetch_assoc($db->query($sql_installer));
		echo json_encode($installers_res);
	break;

	case "insert_disc_amt":
		$new_net_amt = $_REQUEST['net_amt'] - $_REQUEST['disc_sur_amt'];
		$insert_disc_amt = $db->query("UPDATE remode_orders SET disc_sur_amt ='".$_REQUEST['disc_sur_amt']."' WHERE id='".$order_id."'");	
	break;
	
	case "get_shipto_installer":
		$return_res = array();
		$uto_ins_id = $_REQUEST['installer_id'];

		$instQuery = "select ltf, lfwp_amt, ship_to, ins_rate_id from remode_installers where id ='".$uto_ins_id."'";
		$instRow = $db->fetch_assoc_single($db->query($instQuery));

		$db->query("UPDATE remode_orders SET ins_mode='installer', store_num='', installer_id ='".$uto_ins_id."', 
		lt_lfwp_amt = CASE WHEN lt_lfwp LIKE '1#1' THEN '".$instRow['ltf']."#".$instRow['lfwp_amt']."' ELSE WHEN lt_lfwp LIKE '0#1' THEN '0#".$instRow['lfwp_amt']."' ELSE WHEN lt_lfwp LIKE '1#0' THEN '".$instRow['ltf']."#0' ELSE '0#0' END,			
		WHERE id='".$order_id."'");

		if(!empty($instRow)) {
			$installerId = $instRow['ins_rate_id'];
			$orderItemQuery = $db->query("select ro.id, ro.pricingmodel from remode_orderitems ro, remode_orders r where r.order_id = ro.order_id and r.id = '".$order_id."'");
			$orderItemRes = $db->fetch_assoc($orderItemQuery); 
			
			if(!empty($orderItemRes)){
				foreach($orderItemRes as $order_items) {
					$id = $order_items['id'];
					$pm = $order_items['pricingmodel'];                                   
					$pricing_model = trim("$pm");
					if(isset($pricing_model)) {
						$laborQuery = $db->query("select CAST(sum(cost) AS DECIMAL(7,2)) into total 
from (select vlm.pricingmodel, IR".$installerId."*vlm.quantity cost from remode_installer_rates rl, variation_laborcode_map vlm where vlm.labour_code = rl.labor_code and trim(vlm.pricingmodel) = trim(".$pricing_model.")) inn 
group by '".$pricing_model."'");
						$laborTotal = $db->fetch_single($laborQuery);
						if($laborTotal[0] != 0) {
							$db->query("UPDATE remode_orderitems SET laborcost = ".$laborTotal[0]." WHERE id = $id");
						}
					}
				}
			}

			$orderlaborQuery= $db->query("select rol.id, rol.labor_code from remode_orderitems_labor rol, remode_orders r where r.order_id = rol.order_id and r.id = '".$order_id."'");
			$orderlaborRes = $db->fetch_assoc($orderlaborQuery); 
			if(!empty($orderlaborRes)){
				foreach($orderlaborRes as $order_items) {
					$id = $order_items['id'];
					$laborQuery = "select IR".$installerId." from remode_installer_rates where labor_code='".$order_items['labor_code']."'";
					$laborTotal = $db->fetch_row_single($db->query($laborQuery));
					$db->query("UPDATE remode_orderitems_labor SET cost=".$laborTotal[0]." WHERE id = $id");
				}
			}
		}

		$ordItemCost = $fn->getCurrentItemCost($order_id);
		$db->query("update remode_orders set total_prdcost='".$ordItemCost['material']."', total_lbrcost='".$ordItemCost['labor']."', total_amount='".$ordItemCost['retail']."', net_amount='".$ordItemCost['netamount']."', margin_cost=net_amount-total_prdcost-total_lbrcost where id='".$order_id."'");

		$return_res['installer'] = $fn->getInstallerInfo($uto_ins_id);
		$return_res['ship_to'] =  $instRow['ship_to'];

		echo json_encode($return_res);
	break;

	case "get_store_no":
		$return_res = array();
		$branchid = $_REQUEST['branch_id'];
		$sql_branch = $db->query("SELECT branch_code FROM remode_branch_master where id='".$branchid."' ");
		$num_rows = $db->num_rows($sql_branch);
		if($num_rows!="0"){
			$fetch_branch = $db->fetch_assoc_single($sql_branch);
			$branch_code = trim($fetch_branch['branch_code']);
			$sql_inst_storemap = $db->query("SELECT store_num FROM remode_installers_store_map where branch = '".$branch_code."' order by store_num");
			$num_rows_map = $db->num_rows($sql_inst_storemap);
			if($num_rows_map!="0"){
				$store_num = array();
				$fetch_store_res = $db->fetch_assoc($sql_inst_storemap);
				foreach($fetch_store_res as $fetch_store_map){
					$store_num[] = $fetch_store_map;
				}

				$return_res['result'] = $store_num;
			} else {
				$return_res['result'] = "no_store";
			}
		} else {
			$return_res['result']="false";
		}
		$json_result = json_encode($return_res);
		echo $json_result;
	break;
	
	case "get_shipto_store":
		$return_res = array();
		$store_id = $_REQUEST['store_id'];

		if($order_id!=""){
            $instQuery = "select a.id, a.ltf, a.lfwp_amt, a.ship_to, a.ins_rate_id as installer_rate_id from remode_installers a, remode_installers_store_map b where a.id=b.installer_id and b.store_num=".$store_id;
			$instRow = $db->fetch_assoc_single($db->query($instQuery));

			$db->query("UPDATE remode_orders SET ins_mode='store', store_num='".$store_id."', installer_id ='".$instRow['id']."', 
			lt_lfwp_amt = CASE WHEN lt_lfwp LIKE '1#1' THEN '".$instRow['ltf']."#".$instRow['lfwp_amt']."' ELSE WHEN lt_lfwp LIKE '0#1' THEN '0#".$instRow['lfwp_amt']."' ELSE WHEN lt_lfwp LIKE '1#0' THEN '".$instRow['ltf']."#0' ELSE '0#0' END,			
			WHERE id='".$order_id."'");

			if(!empty($instRow)) {
				$installerId =  $instRow['installer_rate_id'];
				$orderItemQuery= $db->query("select ro.id,ro.pricingmodel from remode_orderitems ro,remode_orders r where r.order_id = ro.order_id and r.id = '".$order_id."'");
				$orderItemRes = $db->fetch_assoc($orderItemQuery); 
				
				foreach($orderItemRes as $order_items) {
					$id = $order_items['id'];
					$pm = $order_items['pricingmodel'];                                   
					$pricing_model = trim("$pm");
					if(isset($pricing_model)) {
						$laborQuery = $db->query("select CAST(sum(cost) AS DECIMAL(7,2)) into total 
from (select vlm.pricingmodel, IR".$installerId."*vlm.quantity cost from remode_installer_rates rl, variation_laborcode_map vlm where vlm.labour_code = rl.labor_code and trim(vlm.pricingmodel) = trim(".$pricing_model.")) inn 
group by '".$pricing_model."'");
						$laborTotal = $db->fetch_single($laborQuery);
						if($laborTotal[0] != 0) {
							$db->query("UPDATE remode_orderitems SET laborcost = ".$laborTotal[0]." WHERE id = $id");
						}
					}
				}
				$orderlaborQuery= $db->query("select rol.id, rol.labor_code from remode_orderitems_labor rol, remode_orders r where r.order_id = rol.order_id and r.id = '".$order_id."'");
				$orderlaborRes = $db->fetch_assoc($orderlaborQuery); 
				foreach($orderlaborRes as $order_items) {
					$id = $order_items['id'];
					$laborQuery = "select IR".$installerId." from remode_installer_rates where labor_code='".$order_items['labor_code']."'";
					$laborTotal = $db->fetch_row_single($db->query($laborQuery));
					$db->query("UPDATE remode_orderitems_labor SET cost=".$laborTotal[0]." WHERE id = $id");
				}
			}
		}

		$ordItemCost = $fn->getCurrentItemCost($order_id);
		$db->query("update remode_orders set total_prdcost='".$ordItemCost['material']."', total_lbrcost='".$ordItemCost['labor']."', total_amount='".$ordItemCost['retail']."', net_amount='".$ordItemCost['netamount']."', margin_cost=net_amount-total_prdcost-total_lbrcost where id='".$order_id."'");

		$return_res['installer'] = $fn->getInstallerInfo($instRow['id']);
		$return_res['ship_to'] =  $instRow['ship_to'];

		$json_result = json_encode($return_res);
		echo $json_result;
	break;
	
	case "clear_store_no":
		$return_res = array();
		$return_res['result'] = "false";

		if($order_id!=""){
            $instQuery = "select a.id, a.ltf, a.lfwp_amt, a.ship_to, a.ins_rate_id as installer_rate_id from remode_installers a, remode_installers_branch_map b where a.id=b.installer_id and b.branch_id=".$order_branchid;
			$instRow = $db->fetch_assoc_single($db->query($instQuery));

			$db->query("UPDATE remode_orders SET ins_mode='branch', store_num='', installer_id ='".$instRow['id']."', 
			lt_lfwp_amt = CASE WHEN lt_lfwp LIKE '1#1' THEN '".$instRow['ltf']."#".$instRow['lfwp_amt']."' ELSE WHEN lt_lfwp LIKE '0#1' THEN '0#".$instRow['lfwp_amt']."' ELSE WHEN lt_lfwp LIKE '1#0' THEN '".$instRow['ltf']."#0' ELSE '0#0' END,			
			WHERE id='".$order_id."'");

			if(!empty($instRow)) {
				$installerId = $instRow['installer_rate_id'];
				$orderItemQuery = $db->query("select ro.id, ro.pricingmodel from remode_orderitems ro,remode_orders r where r.order_id = ro.order_id and r.id = '".$order_id."'");
				$orderItemRes = $db->fetch_assoc($orderItemQuery); 
				
				foreach($orderItemRes as $order_items) {
					$id = $order_items['id'];
					$pm = $order_items['pricingmodel'];                                   
					$pricing_model = trim("$pm");
					if(isset($pricing_model)) {
						$laborQuery = $db->query("select CAST(sum(cost) AS DECIMAL(7,2)) into total 
from (select vlm.pricingmodel, IR".$installerId."*vlm.quantity cost from remode_installer_rates rl, variation_laborcode_map vlm where vlm.labour_code = rl.labor_code and trim(vlm.pricingmodel) = trim(".$pricing_model.")) inn 
group by '".$pricing_model."'");
						$laborTotal = $db->fetch_single($laborQuery);
						if($laborTotal[0] != 0) {
							$db->query("UPDATE remode_orderitems SET laborcost = ".$laborTotal[0]." WHERE id = $id");
						}
					}
				}

				$orderlaborQuery= $db->query("select rol.id, rol.labor_code from remode_orderitems_labor rol, remode_orders r where r.order_id = rol.order_id and r.id = '".$order_id."'");
				$orderlaborRes = $db->fetch_assoc($orderlaborQuery); 
				if(!empty($orderlaborRes)){
					foreach($orderlaborRes as $order_items) {
						$id = $order_items['id'];
						$laborQuery = "select IR".$installerId." from remode_installer_rates where labor_code='".$order_items['labor_code']."'";
						$laborTotal = $db->fetch_row_single($db->query($laborQuery));
						$db->query("UPDATE remode_orderitems_labor SET cost=".$laborTotal[0]." WHERE id = $id");
					}
				}
				
			}

			$ordItemCost = $fn->getCurrentItemCost($order_id);
			$db->query("update remode_orders set total_prdcost='".$ordItemCost['material']."', total_lbrcost='".$ordItemCost['labor']."', total_amount='".$ordItemCost['retail']."', net_amount='".$ordItemCost['netamount']."', margin_cost=net_amount-total_prdcost-total_lbrcost where id='".$order_id."'");

			$return_res['installer'] = $fn->getInstallerInfo($instRow['id']);
			$return_res['ship_to'] = $instRow['ship_to'];
		}
		echo json_encode($return_res);
	break;

	case 'del_adnl_item':
		$adnl_id = $_POST['adnl_item_id'];

		$custorderid = $db->fetch_assoc_single($db->query("select id from remode_orders where order_id='".$sys_order_id."'"));
		$adnl_pmodel = $db->fetch_assoc_single($db->query("select pricingmodel, quantity from remode_orderitems where id=".$adnl_id));

		$db->query("update remode_orderitems set is_approved=0 where id='".$adnl_id."' and order_id='".$sys_order_id."'");
		$db->query("delete from remode_orderitems_labor where pricing_model='".$adnl_pmodel['pricingmodel']."' and order_id='".$sys_order_id."'");

		$ordItemCost = $fn->getCurrentItemCost($custorderid['id']);
		$db->query("UPDATE remode_orders set `total_product`=`total_product`-1, `total_qty`=`total_qty`-".$adnl_pmodel['quantity'].", `total_lbrcost`=".$ordItemCost['labor'].", total_amount='".$ordItemCost['retail']."', promo_amt='".$ordItemCost['promoamt']."', net_amount='".$ordItemCost['netamount']."', margin_cost=net_amount-total_prdcost-total_lbrcost where order_id=".$sys_order_id);
	break;

	case "get_miscdata":
		$result_array = array();
		
		$miscid = $_REQUEST['miscid'];
		$sys_order_id = $_REQUEST['sys_order_id'];
		
		$select_misc_data = $db->query("SELECT `id`, `order_id`, `item_name`, `description`, `vendor`, `category`, `uom`, `qty`, `material`, `labor`, `retail`, `approved_by`, `order_type`, `by_ip`, `created_date`, `modified_date` FROM `remode_orderitems_miscs` WHERE `order_id` = '".$sys_order_id."' and `id`='".$miscid."'");
		$fetch_misc_data = $db->fetch_assoc($select_misc_data);
		$result_array['result'] = $fetch_misc_data;
		echo json_encode($result_array);
	break;

	
	case "add_misc_item":
		$misc_data = $_REQUEST['misc_data'];
		($misc_data['qty']=="" || $misc_data['qty']==0)? $qty = 1: $qty = $misc_data['qty'];

		$order_amounts = $db->fetch_assoc_single($db->query("select id from remode_orders where order_id='".$misc_data['order_id']."'"));

		if((isset($misc_data['flag'])) && ($misc_data['flag']=="edit")){
			$oldmisc_qry = $db->query("SELECT qty, material, labor, retail, order_id FROM `remode_orderitems_miscs` WHERE id='".$misc_data['misc_id']."'");
			$oldmisc_res = $db->fetch_assoc_single($oldmisc_qry);		

			$db->query("update remode_orderitems_miscs set item_name ='".$misc_data['item_name']."', order_type='".$misc_data['order_type']."', qty='".$qty."', retail='".$misc_data['retail_price']."', material ='".$misc_data['material_cost']."', labor ='".$misc_data['labor_cost']."', approved_by='".$misc_data['approved_by']."', category='".$misc_data['category']."', vendor='".$misc_data['vendor']."', description='".$misc_data['description']."', uom='".$misc_data['uom']."' where order_id ='".$misc_data['order_id']."' and id='".$misc_data['misc_id']."'");

			$ordItemCost = $fn->getCurrentItemCost($order_amounts['id']);
			$db->query("update remode_orders set total_qty=total_qty+".$qty."-".$oldmisc_res['qty'].", total_prdcost='".$ordItemCost['material']."', total_lbrcost='".$ordItemCost['labor']."', total_amount='".$ordItemCost['retail']."', promo_amt='".$ordItemCost['promoamt']."', net_amount='".$ordItemCost['netamount']."', margin_cost=net_amount-total_prdcost-total_lbrcost where id='".$order_amounts['id']."'");
		}else{
			$db->query("insert into remode_orderitems_miscs set order_id ='".$misc_data['order_id']."', item_name ='".$misc_data['item_name']."', order_type='".$misc_data['order_type']."', qty='".$qty."', retail='".$misc_data['retail_price']."', material ='".$misc_data['material_cost']."', labor ='".$misc_data['labor_cost']."', approved_by='".$misc_data['approved_by']."', category='".$misc_data['category']."', vendor='".$misc_data['vendor']."', description='".$misc_data['description']."', uom='".$misc_data['uom']."', created_date=now()");

			$ordItemCost = $fn->getCurrentItemCost($order_amounts['id']);
			$db->query("update remode_orders set total_product=total_product+1, total_qty=total_qty+".$qty.", total_prdcost='".$ordItemCost['material']."', total_lbrcost='".$ordItemCost['labor']."', total_amount='".$ordItemCost['retail']."', promo_amt='".$ordItemCost['promoamt']."', net_amount='".$ordItemCost['netamount']."', margin_cost=net_amount-total_prdcost-total_lbrcost where id='".$order_amounts['id']."'");
		}
	break;

	case "del_misc_item":
		$misc_item_id= $_REQUEST['misc_item_id'];
		$misc_by_ins = $_REQUEST['misc_by_ins'];

		$select_misc_data = $db->query("SELECT qty, material, labor, retail, order_id FROM `remode_orderitems_miscs` WHERE id = '".$misc_item_id."'");
		$misc_data = $db->fetch_assoc_single($select_misc_data);

		$order_amounts = $db->fetch_assoc_single($db->query("select id from remode_orders where order_id='".$misc_data['order_id']."'"));

		if($misc_by_ins==1){
			$db->query("UPDATE `remode_orderitems_miscs` SET `is_approved`=0 WHERE id='".$misc_item_id."'");	
		} else {
			$db->query("DELETE FROM `remode_orderitems_miscs` WHERE id='".$misc_item_id."'");	
		}

		$ordItemCost = $fn->getCurrentItemCost($order_amounts['id']);
		$db->query("update remode_orders set total_product=total_product-1, total_qty=total_qty-".$misc_data['qty'].", total_prdcost='".$ordItemCost['material']."', total_lbrcost='".$ordItemCost['labor']."', total_amount='".$ordItemCost['retail']."', promo_amt='".$ordItemCost['promoamt']."', net_amount='".$ordItemCost['netamount']."', margin_cost=net_amount-total_prdcost-total_lbrcost where id='".$order_amounts['id']."'");
	break;

	case "update_matreceived":
		$manufacturer = $_REQUEST['manufacturer'];
		$receipt_info = json_encode([
			"receiver" => $_POST['rec_name'],
			"date" => $_POST['rec_date'],
			"comment" => $db->real_escape($_POST['rec_comment']),
			"origin" => "ADMP"
		]);
		
		$active_mfg = $fn->GetMfgField($order_id, $manufacturer);
		$db->query("update remode_order_status set ".$active_mfg."_status=CONCAT(".$active_mfg."_status, ',Delivered_',now()) where order_id='".$order_id."'");
		
		$db->query("update remode_ordermat_attachments set delivery_info = '".$receipt_info."' where order_id='".$order_id."' and vendor='".$manufacturer."';");

		$dms_status = $db->fetch_assoc_single($db->query("select DMS from remode_order_status where order_id ='" .$order_id ."'"));
		$dms_status = explode(",", $dms_status['DMS']);
		if(!isset($dms_status[2])){
			$db->query("update remode_order_status set DMS=CONCAT(DMS, ',', now()) where order_id ='" .$order_id ."'");
		}

		// to insert ap status
		$db->query("insert remode_ap_status (order_id, material, material_status) values ('".$order_id."', '".$manufacturer."', concat('Approved_', now())");
	break;


	case "export_to_file":
		$order_type = $_POST['order_type'];
		$price_flag = $_POST['with_price'];
		$export_type = $_POST['export_type'];
		$mfg = $_POST['mfg'];

		$export_arr = array(
			"SO" => array("cname"=>"SoExport", "cfile"=>"so_export.php"),
			"MO" => array("cname"=>"MoExport", "cfile"=>"mo_export.php"),
			"IO" => array("cname"=>"IoExport", "cfile"=>"io_export.php")
		);
		$ext_arr = array("excel"=>"xls", "xml"=>"xml", "pdf"=>"pdf");

		$class_obj = $fn->gco_dyn($export_arr[$order_type]['cname'], $export_arr[$order_type]['cfile'], getcwd()."/includes/act_export/");

		$mo_admin_view = $fn->hasMoAdminTemplate();
		$mail_content = $class_obj->exportDataToFile($order_id, $mfg, $price_flag, $ext_arr[$export_type], $mo_admin_view);
		$filename = $order_type." #".ORD_PREFIX.$order_id.".".$ext_arr[$export_type];
	
		$fn->createFile($mail_content, "uploads/", $filename);

		echo base64_encode(trim($filename));
	break;

	case "ack_dealer":
	    $sql = "SELECT ru.fullname, ru.user_email FROM remode_orders ro, remode_users ru
				WHERE ro.id = '".$order_id."' AND ro.branchid = ru.branch_id AND ru.role= 'branchadmin'";
		$dealer_query_result = $db->fetch_assoc($db->query($sql));
		foreach($dealer_query_result as $dealer_email){
			$dealer_name = $dealer_email['fullname'];
			$mail_to[] = array('name'=>$dealer_email['fullname'], 'email'=>$dealer_email['user_email']);
		}
		// subject
		$mail_subject = 'Acknowledge of Material Order';
		// mail content
		$com_info_qry = 'SELECT o.id, c.id cid, c.name, c.com_name, c.admin_email 
			from remode_company_master c inner join remode_orders o on o.company=c.alias 
			WHERE o.id = "'.$order_id.'"';
		$company_info = $db->fetch_assoc_single($db->query($com_info_qry));
		$emailTpl_qry = 'SELECT s.value from remode_settings_mapping s left join remode_settings_master m on m.id=s.sid 
			where s.cid="'.$company_info['cid'].'" AND m.name="ack_dealer_emailtpl"';
		$emailTpl = $db->fetch_row_single($db->query($emailTpl_qry));

		$arr_placeholder = array('{{DEALERNAME}}', '{{CO_NAME}}');
		$arr_placeval = array($db->real_escape($dealer_name), $company_info['name']);
		$emailmessage = str_replace($arr_placeholder, $arr_placeval, $emailTpl[0]);

        $ack_res = $fn->FnSentMailNotification($mail_to, $mail_subject, $emailmessage, array(), array(), array(), array());
		if($ack_res){
			$db->query('update remode_orders set dealer_mat_status=concat(dealer_mat_status, "_acknowledged") where id='.$order_id);
			echo "success";
		}
	break;

	case "change_mo_view":
		$_SESSION[APPSESVAR.'_adminuser']['view'] = $_POST['view'];
	break;

	case "switch_oi_mfg":
		$item = $_POST['item'];
		$mfg = $_POST['mfg'];
		$mat_order_status = $db->fetch_assoc_single($db->query("select sent_material from remode_orders where order_id='".$sys_order_id."'"));

		if(trim(strpos($mat_order_status['sent_material'], $mfg['t']."_"))== ""){
			$mfg_stat = $db->query("update remode_orderitems set manufacturer='".$mfg['t']."' where id ='".$item['id']."'");
			$db->query("insert into remode_order_revisions (order_id, category, comments, posted_by) values('".$sys_order_id."', 'Switched Mat. Vendor', 'Changed material vendor of item <b>".$item['sku']."</b> from <b>".$mfg['f']."</b> to <b>".$mfg['t']."</b>.', '".$_SESSION[APPSESVAR.'_adminuser']['un']."')");
			echo "success";
		} else {
			echo "failed";
		}
	break;

	case "convert_order":
		$ro_res = $db->fetch_assoc_single($db->query("SELECT branchid, pb_of_branch FROM remode_orders WHERE id=".$order_id));

		$branchid = ($ro_res['pb_of_branch']==0? $fn->isTestBranch() : $ro_res['pb_of_branch']);
		$pb_of_branch = $ro_res['branchid'];

		$qry = $db->query("UPDATE remode_orders SET branchid = '".$branchid."', pb_of_branch = '".$pb_of_branch."' WHERE id=".$order_id);
		if($qry)
			echo "success";
	break;
}
?>