<?php

if(isset($_REQUEST["action"])) {
	$ord_action = $_REQUEST["action"];

	if($ord_action == "send_ordercopy"){

		include("email_data/so_email.php");
		$mail_content = $_POST['copymail_message']."<br><br>".$mail_content;

		$mail_to=array("name"=>$_POST['copymail_to'], "email"=>$_POST['copymail_to']);
		$mail_subject=$_POST['copymail_subject'];
		$mail_cc=$_POST['copymail_cc'];
		$mailBCCto=array();
		$fsmn_others="";

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
		$fsmn_others = array("rmattm"=>1, "oet"=>"sales", "afp"=>0, "bro_id"=>$order_id);

		$fn->FnSentMailNotification($mail_to, $mail_subject, $mail_content, $mailCCto, $mailBCCto, $attach_file, $fsmn_others);

	} else if($ord_action == "Confirm Order"){
		$db->query("UPDATE remode_orders SET status = 'confirmed' where id = '".$order_id."'");
	} else if($ord_action == "Revise Order"){
		$rev_comments = $_REQUEST['revord_comments'];
		$sys_order_id = $_REQUEST['sys_order_id'];
		$returnby =  $_REQUEST['returnby'];
		$rep_name = $_REQUEST['rep_name'];

		$db->query("UPDATE remode_orders SET status = 'revision', disc_sur_amt = '0' where id = '".$order_id."'");
		$db->query("insert into remode_order_revisions (order_id, category, comments, posted_by) values('".$sys_order_id."', 'Initiated Revision', '".$rev_comments."', '".$returnby."')");

		$mail_to=$_REQUEST['mail_to'];
		$mail_subject=$_REQUEST['mail_subject'];
		$mail_cc=$_REQUEST['mail_cc'];
		$mail_bcc=$_REQUEST['mail_bcc'];

		$attach_arr = count($_FILES["attach_file_name"]["name"]);
		$attach_file = array();
		
		for($i=0; $i < $attach_arr; $i++) {
			if($_FILES["attach_file_name"]["name"][$i] != "") {
				$randstr = rand(5, 10000);
				if(move_uploaded_file($_FILES['attach_file_name']['tmp_name'][$i], "uploads/".$randstr."_".$_FILES['attach_file_name']['name'][$i])){
					$attach_file[] = $randstr."_".$_FILES["attach_file_name"]["name"][$i];
				}
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

		$fsmn_others = array("rmattm"=>1, "oet"=>"sales", "afp"=>0, "bro_id"=>$order_id);
		$fn->FnSentMailNotification($mail_to, $mail_subject, $rev_comments, $mailCCto, $mailBCCto, $attach_file, $fsmn_others);

	}else if($ord_action == "Cancel Order"){
		$rev_comments = $_REQUEST['cancelord_comments'];
		$sys_order_id = $_REQUEST['sys_order_id'];
		$returnby =  $_REQUEST['returnby'];
		$rep_name = $_REQUEST['rep_name'];

		$db->query("UPDATE remode_orders SET status = 'canceled' where id = '".$order_id."'");
		$db->query("insert into remode_order_revisions (order_id, comments, posted_by) values('".$sys_order_id."', '".$rev_comments."', '".$returnby."')");

		$mail_to=$_REQUEST['mail_to'];
		$mail_subject=$_REQUEST['mail_subject'];
		$mail_cc=$_REQUEST['mail_cc'];
		$mail_bcc=$_REQUEST['mail_bcc'];

		$attach_arr = count($_FILES["attach_file_name"]["name"]);
		$attach_file = array();
		
		for($i=0; $i < $attach_arr; $i++) {
			if($_FILES["attach_file_name"]["name"][$i] != "") {
				$randstr = rand(5, 10000);
				if(move_uploaded_file($_FILES['attach_file_name']['tmp_name'][$i], "uploads/".$randstr."_".$_FILES['attach_file_name']['name'][$i])){
					$attach_file[] = $randstr."_".$_FILES["attach_file_name"]["name"][$i];
				}
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

		$fsmn_others = array("rmattm"=>1, "oet"=>"sales", "afp"=>0, "bro_id"=>$order_id);
		$fn->FnSentMailNotification($mail_to, $mail_subject, $rev_comments, $mailCCto, $mailBCCto, $attach_file, $fsmn_others);

	} else if($ord_action == "save_job_id"){
		$job_id = $_REQUEST['job_id'];
		$db->query("UPDATE remode_orders SET jobid = '".$job_id."' where id = '".$order_id."'");
		$fn->redirect(ROOT."material_order.html?id=".$_GET['id']);		
		exit;
	}
}
?>