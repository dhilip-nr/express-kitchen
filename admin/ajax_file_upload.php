<?php
include("includes/config.php");
$order_id = $_REQUEST["ordid"];
$attach_arr = $_FILES["attachments"];
$attach_file = array();
$attached_files_res = array();

for($i=0; $i<count($attach_arr["name"]); $i++) {
	if($attach_arr["name"][$i] != "") {
//		$randstr = date("d").'-'.rand(5, 10000);
		$randstr = rand(5, 10000);
		$ext = pathinfo($attach_arr["name"][$i], PATHINFO_EXTENSION);
//		$filename = ORD_PREFIX.$order_id."ADM_".$randstr.'.'.$ext;
		$filename = ORD_PREFIX.$order_id."_ADM".$randstr."_".pathinfo($attach_arr["name"][$i], PATHINFO_FILENAME).".".$ext;

		if(move_uploaded_file($attach_arr["tmp_name"][$i], "../uploads/".$filename)) {
			$attach_file[] = $filename;
		}
	}
}

$attached_files = implode(",", $attach_file);
$order_query = $db->query("select adm_docs from remode_order_attachments where order_id='".$order_id."'");
$fetch_query = $db->fetch_assoc_single($order_query);

if($fetch_query['adm_docs']=="") {
	$db->query("UPDATE remode_order_attachments SET adm_docs='".$attached_files."' WHERE order_id='".$order_id."'");
	$attached_files_res = $attach_file;
} else {
	$db->query("UPDATE remode_order_attachments SET adm_docs=concat(adm_docs,',".$attached_files."') WHERE order_id='".$order_id."'");
	$attached_files_res = array_merge(explode(",", $fetch_query['adm_docs']),$attach_file);
}

echo json_encode($attached_files_res);

?>