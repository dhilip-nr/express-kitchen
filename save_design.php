<?php
if(!isset($_SESSION)){ session_start(); }

require_once('includes/consts.php');
require_once('includes/dbconnect.php');
$db = new Database(HOSTNAME, HOSTUSER, HOSTPASS, HOSTDB, true);

$root_path='saved_designs/my-design/';
$userid = $_SESSION[APPSESVAR.'_user']['id'];
$myFile = time().mt_rand(10,99);
$stringData = json_decode($_POST["jsondata"], true);
$img = $_POST['imagedata']; // Your data 'data:image/png;base64,AAAFBfj42Pj4';
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$imagedata = base64_decode($img);
$action_type=$_POST['type'];
if($action_type=='draft' || $action_type=='auto-save'){
    // $draft_name=trim($_SESSION[APPSESVAR.'_user']['id'].'-'.$_SESSION[APPSESVAR.'_user']['name'].'-'.$_SESSION[APPSESVAR.'_user']['co_id']);
    $draft_name=$_SESSION[APPSESVAR.'_user']['id'];

    $myFile= $draft_name;
    $root_path='saved_designs/draft-designs/';
    if($action_type=='auto-save')
        $root_path='saved_designs/auto-save/';  
    if($action_type=='draft')
        $qry=$db->query("DELETE FROM remode_user_designs WHERE image_path='$myFile' AND type='$action_type' ");
}

file_put_contents($root_path.'images/'.$myFile.'.png',$imagedata);
file_put_contents($root_path.'data/'.$myFile.'.json',$stringData);


if($action_type=='draft' || $action_type=='mydesign') {
	$qry=$db->query("INSERT INTO remode_user_designs (id, userid, image_path, json_path, status, type) VALUES (NULL, '$userid', '$myFile', '$myFile', 'active', '$action_type')");
	if($qry)
		echo json_encode(["info" => $userid."-".$myFile, "action" => $action_type]);
} else
	echo json_encode(["info" => $userid."-0", "action" => $action_type]);
?>