<?php

$ord_action = (isset($_POST["action"])?$_POST["action"]:"");

if($ord_action == "send_ordercopy") {
	include("email_data/io_email.php");

	$mail_to=array("name"=>$_POST['copymail_to'], "email"=>$_POST['copymail_to']);
	$mail_subject=$_POST['copymail_subject'];
	$mail_cc=$_POST['copymail_cc'];
	$mailBCCto="";
	$fsmn_others="";
	$mail_content = $_POST['copymail_message']."<br>".$mail_content;

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
	$fsmn_others = array("rmattm"=>1, "oet"=>"install", "afp"=>0, "bro_id"=>$order_id);
	$fn->FnSentMailNotification($mail_to, $mail_subject, $mail_content, $mailCCto, $mailBCCto, $attach_file, $fsmn_others);
}

if((isset($_REQUEST["mail_option"])) && ($_REQUEST["mail_option"] == "Send")) {

//	include("email_data/io_email.php");
	$content = ""; //$mail_content;

	$mail_subject	= trim( $_POST['mail_subject'] );	
	$mail_to 		= array("name"=>$_REQUEST['mail_to'], "email"=>trim($_REQUEST['mail_to']));
	$mail_cc		= trim( $_POST['mail_cc'] );	
	$mail_bcc		= trim( $_POST['mail_bcc'] );
	$mail_secondary_content = trim( $_POST['mail_secondary_content'] );
	$attach_file		= array();    
	$order_attachments = $_REQUEST['order_attachments'];
	$attach_email = isset($_REQUEST['attach_email'])?$_REQUEST['attach_email']:"";
	$checked = isset($_REQUEST['installxml'])?$_REQUEST['installxml']:"";
/*
   	if($checked == "install_xmlcontent") {
		$xml_filename = "IO #".ORD_PREFIX.$order_id.".xml";
		$io_export = $fn->gco_dyn("IoExport", "io_export.php", "includes/act_export/");
		$xml_file_content = $io_export->exportDataToFile($order_id, "", 1, "xml");
		$fn->createFile($xml_file_content, "uploads/", $xml_filename);
		$attach_file[] = $xml_filename;
	}
*/
	$attach_arr = count($_FILES["attach_file_name"]["name"]);
	for($i=0; $i < $attach_arr; $i++) {
		if($_FILES["attach_file_name"]["name"][$i] != "") {
			$randstr = rand(5, 10000);
			if(move_uploaded_file($_FILES["attach_file_name"]["tmp_name"][$i], "uploads/".$randstr."_".$_FILES["attach_file_name"]["name"][$i])) {
	        }
			$attach_file[] = $randstr."_".$_FILES["attach_file_name"]["name"][$i];
		}
	}

	if($order_attachments!=""){
		$sorder_attachments = explode(",", trim($order_attachments, ","));
        foreach($sorder_attachments as $order_attfiles) {
            if(copy("../uploads/".$order_attfiles, "uploads/".$order_attfiles)) {
	            $attach_file[] = $order_attfiles;
            }
        }
	}

/*
	if ($attach_email=="customer_order"){
		$emailmessage = $fn->getCustomerEmailContent(preg_replace('/'.ORD_PREFIX.'/i', '', $_REQUEST['id']));

		require('../includes/pdf/html2fpdf.php');
		$pdf=new HTML2FPDF();
		$pdf->AddPage();
		$pdf->WriteHTML($emailmessage);

		$file_name= "Order #".$_REQUEST['id']."_".time().".pdf";

		$pdf->Output("uploads/".$file_name);
		$attach_file[] = $file_name;
	}
*/
    $mailCCto = array();
    foreach(explode(",", $mail_cc) as $ccto){
        $mailCCto[] = array("name"=>$ccto, "email"=>$ccto);
    }
    $mailBCCto = array();
    foreach(explode(",", $mail_bcc) as $bccto){
        $mailBCCto[] = array("name"=>$bccto, "email"=>$bccto);
    }

    $mail_content = $mail_secondary_content;
    $mail_content .= "<div style='margin:15px 0 10px; width:100%;'><b>MESSAGE :</b><br />&nbsp;<br />".$content."</div>";

	$fsmn_others = array("rmattm"=>1, "oet"=>"install", "afp"=>0, "bro_id"=>$order_id);
	$mail_sent_y = $fn->FnSentMailNotification($mail_to, $mail_subject, $mail_content, $mailCCto, $mailBCCto, $attach_file, $fsmn_others);

	if($mail_sent_y || CW_ENV!="production") {
		$db->query("update remode_orders set sent_installer='".date('Y-m-d H:i:s')."' where id=".preg_replace('/'.ORD_PREFIX.'/i', '', $_REQUEST['id'])." and (sent_installer='' or  sent_installer IS NULL)");
		echo '<script language="javascript">';
		echo 'alert("Install Order Placed Successfully");';
		echo 'window.location = "'.ROOT.'install_order.html?id='.$_REQUEST['id'].'";';
		echo '</script>';
	} else {
		echo '<script language="javascript">';
		echo 'alert("Failed to place Install Order. Please try again.");';
		echo '</script>';
	}
}

	
if(isset($_POST['resolved_ip']) && $_POST['resolved_ip'] != ""){
	$db->query("update remode_orders SET status='submitted' where order_id='".$_POST['sys_order_id']."'");
	$db->query("update remode_order_status SET IOS=concat(IOS,',Problem Resolved_".date('Y-m-d H:i:s')."') where order_id='".$order_id."'");
	$db->query("INSERT INTO remode_order_revisions (order_id, category, comments, posted_by, origin) VALUES('".$_POST['sys_order_id']."', 'Resolved', 'Installation Problem', '".$_SESSION[APPSESVAR.'_adminuser']['un']."', '')");
 	$fsmn_others = array("rmattm"=>1, "oet"=>"install", "afp"=>0, "bro_id"=>$order_id);
	$fn->FnSentMailNotification($mail_to, $mail_subject, $mail_content, $mailCCto, $mailBCCto, $attach_file, $fsmn_others);
} else if(isset($_POST['approve_inschanges']) && $_POST['approve_inschanges'] != ""){
	$db->query("update remode_orders SET status='submitted', on_change='' where order_id='".$_POST['sys_order_id']."'");
	$db->query("INSERT INTO remode_order_revisions (order_id, category, comments, posted_by, origin) VALUES('".$_POST['sys_order_id']."', 'Approved', 'Changes made by installers were approved', '".$_SESSION[APPSESVAR.'_adminuser']['un']."', '')");
	$fn->apvInsChangesMail($order_id, $_POST['sys_order_id'], $_POST['installer_username'], "approved");
} else if(isset($_POST['notify_changes_refused']) && $_POST['notify_changes_refused'] != ""){
	$db->query("update remode_orders SET status='submitted', on_change='' where order_id='".$_POST['sys_order_id']."'");
	$db->query("INSERT INTO remode_order_revisions (order_id, category, comments, posted_by, origin) VALUES('".$_POST['sys_order_id']."', 'Declined', 'Changes made by installers were declined', '".$_SESSION[APPSESVAR.'_adminuser']['un']."', '')");
	$fn->apvInsChangesMail($order_id, $_POST['sys_order_id'], $_POST['installer_username'], "declined");
}


//	For quantity update starts -----
if(isset($_POST['update_qty']) && $_POST['update_qty'] != ""){
/*
echo "<pre>";
print_r($_POST);
echo "</pre>";
exit;
*/
	$uqty_id = $_POST['update_qty'];
	$prd_options =  isset($_POST["ext_options"])? $_POST["ext_options"]:"";
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

	$old_qsf_fld = '';
	$old_qsf_data = $ufield[$_POST["ext_uom"]]."_".$_POST['new_quantity']."_".implode("_", $new_data);
	if($_POST['ext_qsf'] == $old_qsf_data){
		$old_qsf_fld = ", old_qsf =  '".$old_qsf_data."', is_approved=2";
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

// echo "<pre>"; print_r($_POST); echo "</pre>"; exit;

	if($_POST['ext_quantity']!=$_POST['new_quantity'] || count($upfields)!=count($new_data)){
		$walltile_upstr = "";
		if($_POST['orderitem']['cat_id']==41){
			$walltile_upstr = ", slfeet=".($new_data['fieldsqfeet']+$new_data['accentsqfeet']);
		}

		if($_POST['orderitem']['cat_id']==45){
			$_POST['new_quantity']=ceil($new_data['linealfeet']/8);
		}

		$db->query("update remode_orderitems SET ".$ufield[$_POST["ext_uom"]]." ='".$_POST['new_quantity']."'".$walltile_upstr.", options='".$prd_options."'".$old_qsf_fld." WHERE id ='".$_POST['orderitem']['id']."';");
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