<?php

// For Material-Reorder Starts ---
$ord_action = (isset($_POST["action"])?$_POST["action"]:"");

if($ord_action == "send_ordercopy"){
	include("includes/email_data/mo_email.php");

	$mail_to=array("name"=>$_POST['copymail_to'], "email"=>$_POST['copymail_to']);
	$mail_subject=$_POST['copymail_subject'];
	$mail_cc=$_POST['copymail_cc'];
	$mailBCCto=array();
	$fsmn_others="";

	if(isset($_POST['include_repnotes'])){
		$mail_content = $_POST['copymail_message']."<br><br><b>Rep entered order notes:</b><br>".$_POST['order_repnotes']."<br>".$mail_content;
	} else {
		$mail_content = $_POST['copymail_message']."<br>".$mail_content;
	}

	$mailCCto = array();
	foreach(explode(",", $mail_cc) as $ccto){
		$mailCCto[] = array("name"=>$ccto, "email"=>$ccto);
	}

	$attach_file = array();
	$attach_arr = count($_FILES["attach_file_name"]["name"]);
	
	for($i=0; $i < $attach_arr; $i++) {
		if($_FILES["attach_file_name"]["name"][$i] != "") {
			$randstr = rand(5, 10000);
			if(move_uploaded_file($_FILES['attach_file_name']['tmp_name'][$i], "uploads/".$randstr."_".$_FILES['attach_file_name']['name'][$i])){
			}
			$attach_file[] = $randstr."_".$_FILES["attach_file_name"]["name"][$i];
		}
	}
	$fsmn_others = array("rmattm"=>1, "oet"=>"material", "afp"=>0, "bro_id"=>$order_id);

	$fn->FnSentMailNotification($mail_to, $mail_subject, $mail_content, $mailCCto, $mailBCCto, $attach_file, $fsmn_others);
}
// For Material-Reorder Ends ---



if((isset($_REQUEST['mail_option'])) && ($_REQUEST['mail_option'] == "Send") || isset($_POST['ordersubitem_id']) || isset($_POST['order_mainitem_id'])) {

	include("includes/email_data/mo_email.php");
	$content = $mail_content;

	$mail_subject			= trim( $_REQUEST['mail_subject'] );	
	$mail_to 				= array("name"=>$_REQUEST['mail_to'], "email"=>trim($_REQUEST['mail_to']));
	$mail_cc				= trim( $_REQUEST['mail_cc'] );	
	$mail_bcc				= trim( $_REQUEST['mail_bcc'] );
	$sending_vendor = $_REQUEST['sending_vendor'];
	$sent_material =  trim($_REQUEST['sent_material'],',');
	$reason_content = $_POST['reason_content'];

	$mail_secondary_content = trim( $_REQUEST['mail_secondary_content'] );
	if(isset($_POST['include_repnotes'])){
		$mail_secondary_content .= "<br><br><b>Rep entered order notes:</b><br>".$_POST['order_repnotes'];
	}

    $checked = isset($_REQUEST['materialxml'])? $_REQUEST['materialxml'] : "";
	$attach_file = array();

	if($checked == "material_xmlcontent") {
        $manufacture= isset($_REQUEST['man_name']) ? trim($_REQUEST['man_name']) : "";
		$xml_filename = "IO #".ORD_PREFIX.$order_id.".xml";

		$mo_export = $fn->gco_dyn("MoExport", "mo_export.php", "includes/act_export/");
		$xml_file_content = $mo_export->exportDataToFile($order_id, $manufacture, 1, "xml", $mo_admin_view);
		$fn->createFile($xml_file_content, "uploads/", $xml_filename);

        $attach_file[] = $xml_filename;
	}

	$attach_arr = count($_FILES["attach_file_name"]["name"]);
	
	for($i=0; $i < $attach_arr; $i++) {
        if($_FILES["attach_file_name"]["name"][$i] != "") {
            $randstr = rand(5, 10000);
            if(move_uploaded_file($_FILES['attach_file_name']['tmp_name'][$i], "uploads/".$randstr."_".$_FILES['attach_file_name']['name'][$i])){
            } else {
//				die("error: upload a file");
            }
            $attach_file[] = $randstr."_".$_FILES["attach_file_name"]["name"][$i];
        }
	}

    $mailCCto = array();
    foreach(explode(",", $mail_cc) as $ccto){
        $mailCCto[] = array("name"=>$ccto, "email"=>$ccto);
    }
    $mailBCCto = array();
    foreach(explode(",", $mail_bcc) as $bccto){
        $mailBCCto[] = array("name"=>$bccto, "email"=>$bccto);
    }


    $mail_content = $mail_secondary_content."<div style='margin:15px 0 10px; width:100%;'><b>MESSAGE :</b><br />&nbsp;<br />";
	if(trim($reason_content) != ""){
	    $mail_content .= "Reason: ".$reason_content."<br />&nbsp;<br />";
	}
    $mail_content .= $content."</div>";

	$fsmn_others = array("rmattm"=>1, "oet"=>"material", "afp"=>0, "bro_id"=>$order_id);

	$mail_sent_y = $fn->FnSentMailNotification($mail_to, $mail_subject, $mail_content, $mailCCto, $mailBCCto, $attach_file, $fsmn_others);

	if($mail_sent_y || CW_ENV!="production") {
        $order_update_qry = "";

		$sending_vendor_wtime = $sending_vendor."_".date('Y-m-d H:i:s');

		if(!$mo_admin_view || in_array($_SESSION[APPSESVAR.'_adminuser']['role'], ['dealer', 'branchadmin'])){
			$order_update_qry = "update remode_orders set dealer_mat_status='".$_SESSION[APPSESVAR.'_adminuser']['un']."_".date('Y-m-d H:i:s')."' where id =".preg_replace('/'.ORD_PREFIX.'/i', '', $_REQUEST['id']);
		}else{
			if(strpos($sent_material, $sending_vendor) !== false){
				$explode_sent_orders = explode(",", $sent_material);
				foreach($explode_sent_orders as $i=>$sm_val){
					if(strpos($sm_val, $sending_vendor) !== false){
						unset($explode_sent_orders[$i]);
					}
				}
	
				$explode_sent_orders = array_filter($explode_sent_orders);
	
				$implode_sent_orders = implode(",", $explode_sent_orders);
				$implode_sent_orders==""? $implode_sent_orders=$sending_vendor_wtime : $implode_sent_orders.= ",".$sending_vendor_wtime;
				
				$order_update_qry = "update remode_orders set sent_material='".$implode_sent_orders."' where id =".preg_replace('/'.ORD_PREFIX.'/i', '', $_REQUEST['id']);
			} else {
				$implode_sent_orders = ($sent_material==""? $sending_vendor_wtime : $sent_material.",".$sending_vendor_wtime);
				$order_update_qry = "update remode_orders set sent_material='".$implode_sent_orders."' where id =".preg_replace('/'.ORD_PREFIX.'/i', '', $_REQUEST['id']);
			}

		    $db->query("update remode_order_status set DMS=NOW() where order_id =".preg_replace('/'.ORD_PREFIX.'/i', '', $_REQUEST['id'])." and TRIM(DMS)=''");
		}

	    $db->query($order_update_qry);

		echo '<script language="javascript">';
	    echo 'alert("Mail sent successfully");';
		echo 'window.location = "'.ROOT.'material_order.html?id='.$_REQUEST['id'].'";';
		echo '</script>';
	} else {
		echo '<script language="javascript">';
		echo 'alert("Mail sending fails. Please try again.");';
		echo '</script>';
	}

}


//	For quantity update starts -----
if(isset($_POST['update_qty']) && $_POST['update_qty'] != ""){
	$uqty_id = $_POST['update_qty'];
	$prd_options = isset($_POST["ext_options"])? $_POST["ext_options"]:"";
	$ext_data = isset($_POST["ext_data"])? $_POST["ext_data"]:array();
	$new_data = isset($_POST["new_data"])? $_POST["new_data"]:array();
	$opt_desc = isset($_POST["opt_desc"])? $_POST["opt_desc"]:array();
	$ufield = array("EA"=>"quantity", "SF"=>"slfeet", "LF"=>"slfeet");
	$upfields= ($ext_data!="")? array_intersect_assoc($ext_data, $new_data):"";
	$update_ror = false;
	$updatecomments = "";
	$upflields="";

	if($_POST['ext_quantity']!=$_POST['new_quantity']){
		$updatecomments = "Updated ".$_POST["ext_uom"].": ".$_POST['ext_quantity']." to ".$_POST['new_quantity'].", ";
	}
	
	if($new_data!=""){
		foreach($new_data as $key=>$new_val){
			if($new_data[$key]!=$ext_data[$key]){
				$extopt_val = $key."#".$ext_data[$key]."#".$key;
				$newopt_val = $key."#".$new_val."#".$key;
				$prd_options = str_replace($extopt_val, $newopt_val, $prd_options);
				$updatecomments .= "Updated ".$opt_desc[$key]." ".$ext_data[$key]." to ".$new_data[$key].", ";
			}
		}
	}

	//	Change UOM and Qty for Wall surround Tile (Fields and Accent tiles)
	if(isset($new_data['fieldsqfeet'])){
		$_POST["ext_uom"] ='SF';
		$_POST['new_quantity'] = $new_data['fieldsqfeet']+$new_data['accentsqfeet'];
	}
	//	Ends - Change UOM and Qty for Wall surround Tile (Fields and Accent tiles)

	if($_POST['ext_quantity']!=$_POST['new_quantity'] || count($upfields)!=count($new_data)){
		if($_POST['orderitem']['cat_id']==45){
			$_POST['new_quantity']=ceil($new_data['linealfeet']/8);
		}
		$db->query("update remode_orderitems SET ".$ufield[$_POST["ext_uom"]]." ='".$_POST['new_quantity']."', options='".$prd_options."' WHERE id ='".$_POST['orderitem']['id']."'");
		$update_ror = true;
	}

	if($update_ror){
		$ordItemCost = $fn->getCurrentItemCost($order_id);
		$db->query("update remode_orders set total_prdcost='".$ordItemCost['material']."', total_lbrcost='".$ordItemCost['labor']."', total_amount='".$ordItemCost['retail']."', net_amount='".$ordItemCost['netamount']."', margin_cost=net_amount-total_prdcost-total_lbrcost where id='".$order_id."'");

		$updatecomments = "<b>".$_POST['orderitem']['pricingmodel']."</b>: ".trim(trim($updatecomments),",");
		$db->query("insert remode_order_revisions SET comments ='".$updatecomments."', item_id = ".$_POST['orderitem']['id'].", order_id=".$_POST['orderitem']['job_order_id'].", posted_by='".$_SESSION[APPSESVAR.'_adminuser']['un']."'");
	}
}
//	For quantity update ends -----

?>