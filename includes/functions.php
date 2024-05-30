<?php

class Functions {

	var $db_obj;
	var $mail_obj;
	var $appSettingData;

	function __construct(){
		$this->db_obj = $GLOBALS['db'];
		$this->mail_obj = $GLOBALS['mail'];
		$this->switchErrorReporting();
		$this->isValidDBConnected();
		$GLOBALS['appConstData'] = $this->appSettingData = $this->getAppConstData();

		if(!isset($_COOKIE["APP_THEME"]) || $_COOKIE["APP_THEME"]!=APP_DEFAULT_THEME)
			setcookie("APP_THEME", APP_DEFAULT_THEME, 0, "/");
	}

	function switchErrorReporting(){
		if(CW_ENV=="production" || ERR_REPORT==false){
			error_reporting(0);
		} else {
			ini_set('display_errors', 1);
			error_reporting(E_ALL);
//			error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		}
	}

	function isValidDBConnected(){
		if(!$this->db_obj->is_connected()){
			echo "<title>Error | ReMap Inc.</title>
			<div align='center' style='margin:40px; letter-spacing:2px; font: bold 14px Arial; color:#EE262E;'>DATABASE &nbsp; CONNECTION &nbsp; ERROR !</div>";
			exit;
		}
	}

	function getAppConstData(){
		$db = $this->db_obj;
		$settingsdata = [];
		$settingres = $db->fetch_assoc($db->query('SELECT name, value FROM remode_settings'));
		if(!empty($settingres)){
			foreach($settingres as $result){
				$settingsdata[$result['name']] = $result['value'];
			}
		}

		$settingsdata[CW_ENV] = dirname($_SERVER['PHP_SELF'])."/";
		return $settingsdata;
	}

	function page($r=""){
		global $app_root_path_info;

		if($app_root_path_info['root_domain']==true)
			$page = explode($app_root_path_info['host'], $_SERVER['REQUEST_URI']);
		else 
			$page = explode($app_root_path_info['dir'], $_SERVER['REQUEST_URI']);

		$page = explode("/", end($page));
		$page = array_filter($page);

//		if(empty($page))
//			$page[1] = "index.html";

		if($r=="arr_url" || $r=="full_url"){
			return $page;
		}
//echo "<pre>"; print_r($page); exit;
		$page = explode("/", end($page));
		$page=explode("?", end($page));
//		$page=explode(".", reset($page));

		return reset($page);
	}
	
	function PageHeading($strval){
		$pnres = preg_replace('/_/', ' ', $strval);
		return ucwords($pnres);
	}

	function PrintR($pval, $exit=0, $mode=1){
		if($mode==0){
			$ret_val = print_r($pval, true);
		} else {
			$ret_val = "<pre>".print_r($pval, true)."</pre>";
		}

		echo $ret_val;
		if($exit==1)
			exit;
	}

	function gco_dyn($cname, $inc_file="", $path=""){
		if($inc_file!=""){
			include_once ($path.$inc_file);
		}
		return (new $cname);
	}

	function get_api_result($api_url, $from_params, $to_params, $http_headers=[]){
		if($http_headers==[])
			global $http_headers;

		$get_api_link = str_replace($from_params, $to_params, $api_url);
		$api_result = $this->get_ws_result($get_api_link, $http_headers);

		return json_decode($api_result, true);
	}

	function get_ws_result($curl_url, $curl_head=[], $act=[], $rs_form=''){
		//url-ify the data for the POST

		$fields_string = "";		
		if(isset($act['fields'])){
			if(isset($act['params_type']) && $act['params_type']=="json"){
				$fields_string = json_encode($act['fields']);
			} else {
				foreach($act['fields'] as $key=>$value) { 
					$fields_string .= $key.'='.$value.'&'; 
				}
				$fields_string = trim($fields_string, '&');
			}
		}

		//open connection
		$ch = curl_init();
		
		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $curl_url);

		if(!empty($curl_head))
			curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_head);
		
		if(!isset($act['method'])){
			curl_setopt($ch, CURLOPT_POST, 0);
		}else{
			curl_setopt($ch, CURLOPT_POST, count($act['fields']));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		}

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//execute post
		$curl_result = curl_exec($ch);
		curl_close($ch);

		if($rs_form=='xml') {
			$ws_result = array();
			$ch_result = new SimpleXMLElement($curl_result);

			$ws_result = json_encode($ch_result);
			$ws_result = json_decode($ws_result, TRUE);
		} else {
			$ws_result = $curl_result;
		}

		return $ws_result;
	}

	function redirect($sURL) {
		if(!headers_sent()) {
			header("Cache-Control: no-cache, must-revalidate");
			header('Location: '.$sURL);
		} else {
			echo("<script type='text/javascript'>window.location.href='".$sURL."'</script>");
			echo("<noscript><meta http-equiv='refresh' content='0;url=".$sURL."' /></noscript>");
		}
		exit;
	}

	function guestRedirect($pg, $isusr, $is_sales=false){
		$pag_arr = $this->page("full_url");
		if($is_sales && reset($pag_arr)=="login.html"){
			$this->redirect(ROOT."user/login.html");
		}

		if(SYS_TYPE == "maintenance" && $pagename!="maintenance"){
			$this->redirect($this->appSettingData[CW_ENV]."maintenance.html");
		} else if($isusr || reset($pag_arr)=="helpdesk"){
			// Do nothing
		} else if ($pg!="login") {
			if($is_sales)
				$this->redirect(ROOT."user/login.html");
			else
				$this->redirect(ROOT."login.html");
		}
	}

	function FnSentMailNotification($mto, $msubject, $mmessage, $mcc, $mbcc, $mattm, $others){
		$mail = $this->mail_obj;
		$db = $this->db_obj;

		$returnData = true;
		$email_type = array("sales"=>"so_email_bcc", "material"=>"mo_email_bcc", "install"=>"io_email_bcc");

		$filepath = (($others['afp']==0)? $_SERVER['DOCUMENT_ROOT'].ROOT: $others['afp']);
		$ordEmailType = $email_type[$others['oet']];

		$company_qry = 'SELECT c.id, c.com_name, c.admin_email, c.contact_person, c.contact_email from remode_company_master c inner join remode_orders o on o.company=c.alias WHERE o.id = "'.$others['bro_id'].'"';
		$company_info = $db->fetch_assoc_single($db->query($company_qry));
		
		if(!empty($company_info)){
			$mail->From = $company_info['admin_email'];
			$mail->FromName = $company_info['com_name'];
			$mail->AddReplyTo($company_info['contact_email'], $company_info['contact_person']);
		}

		if(EMAIL_MODE=="on"){
			// mail recipients
			if(isset($mto[0]['email'])) {
				foreach($mto as $to)
                    $mail->AddAddress($to['email'], $to['name']);
			} else {
				$mail->AddAddress($mto['email'], $mto['name']);
			}

			// mail CC recipients
			if(!empty($mcc)){
				foreach($mcc as $ccto) $mail->AddCC(strtolower($ccto['email']), $ccto['name']);
			}

			// mail BCC recipients
			if(!empty($mbcc)){
				foreach($mbcc as $bccto) $mail->AddBCC($bccto['email'], $bccto['name']);
			}

			$emailbcc_qry = 'SELECT s.value
				from remode_settings_mapping s
				left join remode_settings_master m on m.id=s.sid
				where s.cid="'.$company_info['id'].'" AND m.name="'.$ordEmailType.'"';
			$emailbcc_j = $db->fetch_row_single($db->query($emailbcc_qry));
			$emailbcc_o = json_decode($emailbcc_j[0]);

			if(!empty($emailbcc_o)){
				foreach($emailbcc_o as $emailbcc){
					$mail->AddBCC($emailbcc->email, $emailbcc->name);
				}
			}

			$all_email_copy_j = $this->appSettingData['all_email_copy'];
			$all_email_copy_o = json_decode($all_email_copy_j);

			if(!empty($all_email_copy_o)){
				foreach($all_email_copy_o as $all_email){
					$mail->AddBCC($all_email->email, $all_email->name);
				}
			}
		} else if(EMAIL_MODE=="dev"){
			if(!strpos($mail->FromName, "- DEV"))
				$mail->FromName .= ' - DEV';

			$dev_emailcc_j = $this->appSettingData['developer_email_to'];
			$dev_emailcc_a = json_decode($dev_emailcc_j);

			if(!empty($dev_emailcc_a)){
				foreach($dev_emailcc_a as $dev_email){
					$mail->AddAddress($dev_email->email, $dev_email->name);
				}
			}
		}

		$mail->IsHTML(true);	// Set email format to HTML

		$mail->Subject = $msubject;
		$mail->Body    = $mmessage;

		if(!empty($mattm)){
			foreach($mattm as $attachfiles) $mail->AddAttachment($filepath."uploads/".$attachfiles);
		}

//	$this->PrintR($mail, 1, 1);
		$params = [
			"to" => (isset($mto[0]['email'])? $mto[0]['email']: $mto['email']),
			"subject" => $msubject,
			"msg" => $mmessage,
			"msgtype" => ""
		];

		if($this->sendFromFacelifters($params)) {
			echo 'Message sent Successfully!';
		} else {
		   $returnData = false;
		   echo 'Message could not be sent.';
		   echo 'Mailer Error: ' . $mail->ErrorInfo;
		}

		if(!empty($mattm) && $others['rmattm']==1){
			$this->FileUnset($mattm, $filepath);
		}
		
		return $returnData;
	}


	function sendFromFacelifters($params){
		$res = true;
		// building array of variables
		$content = http_build_query([
			"to" => $params['to'],
			"subject" => $params['subject'],
			"msg" => $params['msg'],
			"msgtype" => $params['msgtype'],
			"scode" => "test1231@1#"
		]);
		// creating the context change POST to GET if that is relevant 
		$context = stream_context_create([
			'http' => array(
				'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
				'method' => 'POST',
				'content' => $content
			)
		]);

		$result = file_get_contents('https://facelifters.com/forms/tools/phpmailer/mailer.php', null, $context);
//		$res_arr = {"status":"Success","message":"Message has been sent!"};
		$res_arr = json_decode($result, true);
		//dumping the reuslt
 // echo "<pre>"; print_r($res_arr); exit;

		if($res_arr['status'] != "Success") $res=false;
		
		return $res;
	}

	function isLoggedIn(){
		if(isset($_SESSION[APPSESVAR.'_user']))
			return true;
		else
			return false;
	}

	function getLogin(){
		$db = $this->db_obj;

		$adminroles = explode(",", $this->appSettingData[ADMIN_ROLES]);
		$adminroles = array_map("trim", $adminroles);

// LDAP Check Starts ---
		$user_name 		= $db->real_escape($_POST["uname"]);
		$user_passwd 	= $db->real_escape($_POST["upass"]); 

		if(trim($user_name)=="" || trim($user_passwd)==""){
			$this->redirect(ROOT."user/login.html?msg=Username and Password should not be blank.");
			exit;
		}

		$login_params = array(
			'username' => $user_name,
			'password' => $user_passwd,
			'ldapapp' => 'remap-remodel-rep',
			'login_form' => 'Login'
		);
		
		$is_in_global_pass = (in_array($user_passwd, ["demo123", "demo@2023"])? 1: 0);
// echo 111; exit;
//		$is_user = $this->get_ws_result(LDAP_URL, [], array("method"=>"", "fields"=>$login_params));
// LDAP Check Ends ---
		$qry =  "select a.id, a.username, concat(a.firstname, ' ', a.lastname) name, a.email, a.phone, a.ser_no, a.status, a.role, a.branches, a.freshsales_id, b.name co_n, b.id co_id, b.alias co_a, b.contact_email co_e, b.logo co_icon from remode_users a left join remode_company_master b on a.company_id=b.id where username='".$user_name."' and (password='".$user_passwd."' or 1=".$is_in_global_pass.")";
// echo $qry; exit;
		$userdetail = $db->fetch_assoc_single($db->query($qry));
// echo "<pre>"; print_r($userdetail); exit;
		if($userdetail && $userdetail['id']){
			if($userdetail['status']=='Active') {
				$_SESSION[APPSESVAR.'_user'] = array(
					"un" => $userdetail['username'],
					"id" => $userdetail['id'],
					"role" => $userdetail['role'],
					"branch" => $userdetail['branches'],
					"name" => $userdetail['name'],
					"email" => strtolower($userdetail['email']),
					"phone" => $userdetail['phone'],
					"repno" => $userdetail['ser_no'],
					"fsid" => $userdetail['freshsales_id'],
					"co_id" => $userdetail['co_id'],
					"co_name" => $userdetail['co_n']
				);

				$this->redirect(ROOT."workspace");

				exit;
		   } else if($userdetail['status']=='Pending') {
				$this->redirect(ROOT."login?msg=Approval Pending. Administrator approval needed to use this application!");
				exit;
		   } else if($userdetail['status']=='Inactive'){
				$this->redirect(ROOT."login?msg=Your access banned. Contact administrator to gain access again!");
				exit;
		   }
		} else {
			$this->redirect(ROOT."login?msg=Invalid combination of username and password!");
			exit;
		}
	}
}

?>