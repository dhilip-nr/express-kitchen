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

switch($ajax_mode) {
	case "unique_vendor_email":
		$return_res = array();
		$email = trim($_POST['email']);
		$vendor_id = isset($_POST['vendor_id'])?$_POST['vendor_id']:"";
		$return_res["result"] = "false";
		if($email!="") {
			$result = $pg_dbobj->query("SELECT * FROM remode_vendors WHERE email='".$email."' and id !='".$vendor_id."'");
			if($pg_dbobj->num_rows($result)==0) {
				$return_res["result"] = "true";
			}			
		} else {
			$return_res["result"] = "true";					
		}
$return_res["result"] = "true";
		$json_result = json_encode($return_res);
		echo $json_result;
	break;

	case "branch_user_validation":	
		$return_res 			= array();
		$branch_id 	= trim($_POST['branch_id']);	
		$user_id	= trim($_POST['user_id']);	
		$return_res["result"] = "false";					
		if($branch_id!="") {
			$result = $pg_dbobj->query("SELECT * FROM remode_users WHERE id='".$user_id."' and branch_id = '".$branch_id."'");
			if($pg_dbobj->num_rows($result)==0) {
				$return_res["result"] = "true";
			}
		} else {
			$return_res["result"] = "true";					
		}
		$json_result = json_encode($return_res);
		echo $json_result;
	break;

	case "customer_validation":
		$email 	= trim($_POST['email']);
		$id = trim($_POST['id']);
		$return_res["result"] = "false";		
		if($id!="") {
			$result = $pg_dbobj->query("SELECT * FROM `remode_customers` WHERE email = '".$email."' and id not in ('".$id."')");
			if($pg_dbobj->num_rows($result)==0) {
				$return_res["result"] = "true";
			}
		} else if($email!="") {
			$result = $pg_dbobj->query("SELECT * FROM remode_customers WHERE email = '".$email."'");
			if($pg_dbobj->num_rows($result)==0) {
				$return_res["result"] = "true";
			}
		} 
		else {
			$return_res["result"] = "true";
		}
		$json_result = json_encode($return_res);
		echo $json_result;
	break;

	case "load_email_emplate":
		$co = $_POST["codata"];
		$et = $_POST["etdata"];
		$arr_placeholder = array('{{CUSTNAME}}', '{{ORD_ITEMS}}', '{{CO_NAME}}', '{{DEALERNAME}}');
		$arr_placeval = array('<input class="neMarker" type="button" value="CUSTOMER NAME">',
			'<input class="neMarker" type="button" value="ORDER ITEMS">',
			'<input class="neMarker" type="button" value="COMPANY NAME">',
			'<input class="neMarker" type="button" value="DEALER NAME">');

		$result = $pg_dbobj->fetch_row_single($pg_dbobj->query("SELECT value FROM `remode_settings_mapping` WHERE cid = '".$co."' and sid = '".$et."'"));
		$email_message = str_replace($arr_placeholder, $arr_placeval, $result[0]);

		$json_result = json_encode(array("val"=>$email_message));
		echo $json_result;
	break;

	case "save_email_emplate":
		$co = $_POST["codata"];
		$et = $_POST["etdata"];
		$dt = stripslashes($_POST["ntpl_data"]);

		$arr_placeholder = array('{{CUSTNAME}}', '{{ORD_ITEMS}}', '{{CO_NAME}}', '{{DEALERNAME}}');
		$arr_placeval = array('<input value="CUSTOMER NAME">',
			'<input value="ORDER ITEMS">',
			'<input value="COMPANY NAME">',
			'<input value="DEALER NAME">');

		$email_message = str_replace($arr_placeval, $arr_placeholder, $dt);

		$pg_dbobj->query("UPDATE `remode_settings_mapping` set value='".$pg_dbobj->real_escape($email_message)."' WHERE cid='".$co."' and sid='".$et."'");
		echo json_encode(array("val"=>"success"));
	break;
	
}
?>