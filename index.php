<?php

require_once('includes/config.php');

$smarty->assign("version", "Current Version 1.24");
$smarty->assign("timestamp", time());

if(isset($_POST['login'])){
	$fn->getLogin();
}

/*
function getFilePath(){
	$app_info = [];

	$app_info['url_scheme'] = $_SERVER['REQUEST_SCHEME'];
	$app_info['host'] = $_SERVER['HTTP_HOST'];
	$app_info['port'] = $_SERVER['SERVER_PORT'];

	$self_url = $app_info['url_scheme']."://".$app_info['host'].($app_info['port']==80? "": ":".$app_info['port']).$_SERVER['REQUEST_URI'];
	
	$app_info['base_dir'] = basename(dirname(__DIR__));
	$app_info['base_url'] = explode(("/".$app_info['base_dir']), $self_url)[0]."/".$app_info['base_dir'];

	return $app_info;
}
*/
$app_info = getFilePath();
$app_root_path_info = array("root_domain"=>false, "dir"=>$app_info['base_dir'], "host"=> $app_info['host']);


//$page_arr=$fn->page('arr_url');
//$page_case = trim(end($page_arr));
$page_case = $fn->page('');
// $name = trim($_SESSION[APPSESVAR.'_user']['id'].'-'.$_SESSION[APPSESVAR.'_user']['name'].'-'.$_SESSION[APPSESVAR.'_user']['co_id']);
if(isset($_SESSION[APPSESVAR.'_user']))
{$name = $_SESSION[APPSESVAR.'_user']['id'];

echo "<script>var DraftDesignName = '$name';</script>";}

/*
echo "<pre>";
print_r($_SESSION[APPSESVAR.'_user']);
echo "</pre>";
*/
/*
$is_loggedin = $fn->isLoggedIn();
// echo 111; echo "--".$is_loggedin."--"; exit;
if($is_loggedin){
	if($page_case=="login")
		$fn->redirect(ROOT."workspace");exit;
} else {
	if($page_case!="login")
		$fn->redirect(ROOT."login");exit;
}
*/

switch($page_case){
	case "login":
		$is_loggedin = $fn->isLoggedIn();
		if($is_loggedin){
			$fn->redirect(ROOT."workspace");
			exit;
		}

		$smarty->display("login.tpl");
		exit;
	break;

	case "workspace":
		$is_loggedin = $fn->isLoggedIn();
		if(!$is_loggedin){
			$fn->redirect(ROOT."login");
			exit;
		}

		$smarty->display("workspace.tpl");
		exit;
	break;
/*
	case "admin":
		$is_loggedin = $fn->isLoggedIn();
		if(!$is_loggedin){
			$fn->redirect(ROOT."login");
			exit;
		}


		$mode = isset($_GET['mode'])? $_GET['mode']: "";
		$group = isset($_GET['group'])? $_GET['group']: "";

		$smarty->assign('group', $group);

		if($mode=="edit")
			$smarty->display("admin-edit.tpl");
		else
			$smarty->display("admin.tpl");

		exit;
	break;

	case "admin-datatable":
		$is_loggedin = $fn->isLoggedIn();
		if(!$is_loggedin){
			$fn->redirect(ROOT."login");
			exit;
		}

		$smarty->display("datatable.tpl");
		exit;
	break;
*/
	case "logout":
		unset($_SESSION[APPSESVAR.'_user']);

		$fn->redirect(ROOT."login");
		exit;
	break;

	default:
		$fn->redirect(ROOT."login");
		exit;
	break;
}

// $smarty->display("index.tpl");

?>
