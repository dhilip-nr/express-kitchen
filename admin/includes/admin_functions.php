<?php

class AdminFunctions extends Functions {

	function isLoggedin(){
		if(isset($_SESSION[APPSESVAR.'_user']['un']))
			return true;
		else
			return false;
	}

	function getLogin() {
		$db = $this->db_obj;

		$settingres = $this->appSettingData[ADMIN_ROLES];
		$adminroles=explode(",", $settingres);
		$adminroles=array_map("trim", $adminroles);


// LDAP check etarts --
		$user_name 		= $db->real_escape($_POST["uname"]);
		$user_passwd 	= $db->real_escape($_POST["upass"]); 

		if(trim($user_name)=="" || trim($user_passwd)==""){
			$this->redirect(ROOT."login.html?msg=Username and Password should not be blank.");
			exit;
		}

		$login_params = array(
			'username' => $user_name,
			'password' => $user_passwd,
			'ldapapp' => 'remap-remodel-admin',
			'login_form' => 'Login'
		);

		$is_user = $this->get_ws_result(LDAP_URL, [], array("method"=>"", "fields"=>$login_params));
// LDAP check ends --

		$qry = $db->query("select id, username, concat(firstname, ' ', lastname) name, email, phone, ser_no, status, role, branches, company_id, ma from remode_users where username='".$user_name."' or email='".$user_name."'");
		$userdetail = $db->fetch_assoc_single($qry);

		if($is_user == "true"){
			if($userdetail['role']=='salesrep') {
				$this->redirect(ROOT."login.html?msg=User not have an permission to access this system");
				exit;
			} else if($userdetail['status']=='Active') {
				$adminuserbranch = empty($userdetail['branch_id'])? 0 : $userdetail['branch_id'];				

				$_SESSION[APPSESVAR.'_user'] = array(
					'un' => $userdetail['username'],
					'id' => $userdetail['id'],
					'role' => $userdetail['role'],
					'name' => $userdetail['name'],
					'company' => $userdetail['company_id'],
					'branch' => $adminuserbranch,
					'ma' => $userdetail['ma']
				);

				$co_qry= $db->query("select id, name, alias, branches, logo, admin_email from remode_company_master where id='".$userdetail['company_id']."'");
				$co_detail= $db->fetch_assoc_single($co_qry);

				if(!empty($co_detail)){
					$_SESSION[APPSESVAR.'_admincompany'] = array(
						'id' => $co_detail['id'],
						'name' => $co_detail['name'],
						'alias' => $co_detail['alias'],
						'branches' => $co_detail['branches'],
						'icon' => $co_detail['logo'],
						'admin_email' => $co_detail['admin_email']
					);
				} else if($_SESSION[APPSESVAR.'_user']['role']!='superadmin'){
					unset($_SESSION[APPSESVAR.'_user']);
					$this->redirect(ROOT."login.html?msg=Un Assigned Provider. Contact administrator to gain allotment");
					exit;
				}

				$this->redirect(ROOT."order_lookup.html");
				exit;
		   } else if($userdetail['status']=='Pending') {
				$this->redirect(ROOT."login.html?msg=Approval Pending. Admin approval needed to use this application");
				exit;
		   } else if($userdetail['status']=='Inactive'){
				$this->redirect(ROOT."login.html?msg=Your access banned. Contact administrator to gain access again");
				exit;
		   }
		} else {			
			if(!empty($userdetail))
				$this->redirect(ROOT."login.html?msg=You do not have access to this application");
			else
				$this->redirect(ROOT."login.html?msg=Invalid combination of username and password.");
			exit;
		}
	}

	function checkOrderAccess($company){
		if($_SESSION[APPSESVAR.'_user']['role']!="superadmin" && $company!=$_SESSION[APPSESVAR.'_user']['company'])
			return false;
		else
			return true;
	}

	function isDealer(){
		if (in_array($_SESSION[APPSESVAR.'_user']['role'], ['dealer','branchadmin']))
			return true;
		else
			return false;
	}

	function hasMoAdminTemplate($has_dealer = false){
		$user = $_SESSION[APPSESVAR.'_user'];
		if (!$has_dealer || (!$this->isDealer() && (!isset($user['view']) || $user['view']=='admin')))
			return true;
		else
			return false;
	}

	function getCustomerEmailContent($orderid){
		$db = $this->db_obj;
		$output[] = '';
		
		$qry2=$db->query('SELECT concat(rc.firstname," ",rc.lastname) as name, co.name company from remode_customers rc inner join remode_orders ro on ro.customer_id=rc.id inner join remode_company_master co on ro.company=co.alias where ro.id='.$orderid);
		$cust_co_info = $db->fetch_assoc_single($qry2);
		
		$qry='SELECT o.id, o.name, o.description, o.slfeet, o.uom, o.quantity, o.category_id, c.group_id FROM remode_orderitems o inner join remode_orders r inner join remode_category c on c.id=o.category_id and r.order_id = o.order_id WHERE r.id = '.$orderid.' order by c.group_id, o.category_id asc';
		$sql = $db->query($qry);

		if ($db->num_rows($sql)>0) {	
			$row = $db->fetch_assoc($sql);
			$output[] = '<table border="1" bordercolor="#cccccc">';
			$groupid="";
			$cnt=1;
			$output[] = '<tr bgcolor="#eeeeee;">';
			$output[] = '<th width="5%">#</th>';
			$output[] = '<th width="70%" align="left">Description</th>';
			$output[] = '<th width="15%">UOM</th>';
			$output[] = '<th width="10%">Qty</th>';
			$output[] = '</tr>';
			
			for($i=0; $i<count($row); $i++) {
				extract($row[$i]);
				if($groupid!=$group_id){
				}

				in_array(trim($uom), array("SF","LF"))? $uom_qty=$slfeet : $uom_qty=$quantity;
				trim($description)!=""? $prd_desc=$description : $prd_desc=$name;

				if($category_id==45){
					$uom_qty = $quantity * ceil($slfeet/8);
				}
				
				$output[] = '<tr>';				
				$output[] = '<td align="center">'.$cnt.'. </td>';
				$output[] = '<td align="left">'.$description.'</td>';
				$output[] = '<td align="center">'.$uom.'</td>';
				$output[] = '<td align="center">'.$uom_qty.'</td>';
				$output[] = '</tr>';
				$groupid=$group_id;
				$cnt++;
			}
			
			$output[] = '</table>';
		} 
		
        $emailmessage =  'Hi <b>'.$cust_co_info['name'].'</b>,<br /><br />';
		$emailmessage.= 'Thank you for choosing '.$cust_co_info['company'].' for your Bath Remodeling project.<br />Your order details are listed below.<br /><br />';
		$emailmessage.= join('',$output);
		$emailmessage.= '<br />Thanks,<br />'.$cust_co_info['company'];

		return $emailmessage;
	}


	function apvInsChangesMail($order_id, $sys_order_id, $ins_user, $actstatus){
		$db = $this->db_obj;
		$ord_branchid = $db->fetch_assoc_single($db->query("select branchid from remode_orders where order_id='".$sys_order_id."'"));

		$mail_to = $db->fetch_assoc_single($db->query("select fullname name, user_email email from remode_users where user_name='".$ins_user."'"));

		// subject
		$subject = 'Approved Changes - Install Order# '.ORD_PREFIX.$order_id;

		// Include orderitems in email - Starts
		if($actstatus == "approved"){
			$emailmessage = 'Products listed below has been approved.<br /><br />';
			$emailmessage .= '<table style="border-collapse: collapse; border: solid 1px #cccccc; color: #444; font-family: Arial, Helvetica, sans-serif; text-align:center; font-size: 12px;" cellpadding="5">';

			$orderitemsqry = $db->query("select roi.pricingmodel, roi.description, roi.slfeet, roi.uom, roi.quantity, roi.category_id, ro.installer_id, ro.store_num, ro.branchid from remode_orderitems roi 
			inner join remode_orders ro on ro.order_id=roi.order_id 
			where roi.by_ip=1 and is_approved=1 and roi.order_id='".$sys_order_id."'");

			$si_no = 1;
			if ($db->num_rows($orderitemsqry)>0) {	
				$orderitemsres = $db->fetch_assoc($orderitemsqry);

				$emailmessage .= '<tr style="background:#eee; text-transform:uppercase;">';
				$emailmessage .= '<th width="5%" style="border-bottom:#b4b4b4;">#</th>';
				$emailmessage .= '<th width="65%" align="left" style="border-bottom:#b4b4b4;">Description</th>';
				$emailmessage .= '<th width="9%" style="border-bottom:#b4b4b4;">UOM</th>';
				$emailmessage .= '<th width="9%" align="center" style="border-bottom:#b4b4b4;">Qty</th>';
				$emailmessage .= '<th width="12%" align="center" style="border-bottom:#b4b4b4;">Labor Cost</th>';
				$emailmessage .= '</tr>';

				foreach($orderitemsres as $orderitems) {
					extract($orderitems);
	
					$uom_qty = (in_array(trim($uom), array("SF","LF"))? ($slfeet*$quantity) : $quantity);
					trim($description)!=""? $prd_desc=$description : $prd_desc=$name;
	
					if($category_id==45){
						$uom_qty = $quantity * ceil($slfeet/8);
					}

					$ins_entryid = $db->fetch_row_single($db->query("select ins_rate_id from remode_installers where id='".$installer_id."'"));
					$labor_res = $db->fetch_row_single($db->query("select sum(rir.quantity*rir.IR".$ins_entryid[0].") 
					from remode_installer_rates rir 
					inner join variation_laborcode_map vlm on rir.labor_code=vlm.labour_code
					where vlm.pricingmodel='".$pricingmodel."'"));
					$laborcost = $labor_res[0];

					$emailmessage .= '<tr>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.($si_no++).'. </td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;" align="left">'.$prd_desc.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.$uom.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.$uom_qty.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;" align="right">$ '.number_format($uom_qty*$laborcost, 2).' &nbsp; </td>';
					$emailmessage .= '</tr>';
				}
			}
	
			$miscitemsqry = $db->query("select item_name, description, uom, qty, labor from remode_orderitems_miscs where by_ip=1 and is_approved=1 and order_id='".$sys_order_id."'");
			if ($db->num_rows($miscitemsqry)>0) {	
				$miscitemsres = $db->fetch_assoc($miscitemsqry);
	
//				$emailmessage .= '<tr style="background:#fafafa; text-transform:uppercase;">';
//				$emailmessage .= '<th colspan="5" style="border-bottom:#b4b4b4; text-align:left; text-indent:15px;">Miscellaneous</th>';
//				$emailmessage .= '</tr>';
				
				foreach($miscitemsres as $orderitems) {
					extract($orderitems);
					trim($description)!=""? $prd_desc=$description : $prd_desc=$name;
	
					$emailmessage .= '<tr>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.($si_no++).'. </td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;" align="left">MISC: '.$prd_desc.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.$uom.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.$qty.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;" align="right">$ '.number_format($qty*$labor, 2).' &nbsp; </td>';
					$emailmessage .= '</tr>';
				}
			}
			$emailmessage .= '</table>';

			$emailmessage .= '<br /><br />And the products listed below has been Declined.<br /><br />';
			$emailmessage .= '<table style="border-collapse: collapse; border: solid 1px #cccccc; color: #444; font-family: Arial, Helvetica, sans-serif; text-align:center; font-size: 12px;" cellpadding="5">';

			$orderitemsqry = $db->query("select roi.pricingmodel, roi.description, roi.slfeet, roi.uom, roi.quantity, roi.category_id, roi.is_approved, roi.old_qsf, ro.installer_id, ro.store_num, ro.branchid from remode_orderitems roi 
			inner join remode_orders ro on ro.order_id=roi.order_id 
			where roi.by_ip=1 and is_approved!=1 and roi.order_id='".$sys_order_id."'");

			$si_no = 1;
			if ($db->num_rows($orderitemsqry)>0) {	
				$orderitemsres = $db->fetch_assoc($orderitemsqry);

				$emailmessage .= '<tr style="background:#eee; text-transform:uppercase;">';
				$emailmessage .= '<th width="5%" style="border-bottom:#b4b4b4;">#</th>';
				$emailmessage .= '<th width="65%" align="left" style="border-bottom:#b4b4b4;">Description</th>';
				$emailmessage .= '<th width="9%" style="border-bottom:#b4b4b4;">UOM</th>';
				$emailmessage .= '<th width="9%" align="center" style="border-bottom:#b4b4b4;">Qty</th>';
				$emailmessage .= '<th width="12%" align="center" style="border-bottom:#b4b4b4;">Labor Cost</th>';
				$emailmessage .= '</tr>';

				foreach($orderitemsres as $orderitems) {
					extract($orderitems);
	
					$uom_qty = (in_array(trim($uom), array("SF","LF"))? ($slfeet*$quantity) : $quantity);
					if($is_approved==2){
						$old_qsf = explode("_", $old_qsf);
						$uom_qty = $old_qsf[1];
					}
					trim($description)!=""? $prd_desc=$description : $prd_desc=$name;
	
					if($category_id==45){
						$uom_qty = $quantity * ceil($slfeet/8);
					}

					$ins_entryid = $db->fetch_row_single($db->query("select ins_rate_id from remode_installers where id='".$installer_id."'"));
					
					$labor_res = $db->fetch_row_single($db->query("select sum(rir.quantity*rir.IR".$ins_entryid[0].") 
					from remode_installer_rates rir 
					inner join variation_laborcode_map vlm on rir.labor_code=vlm.labour_code
					where vlm.pricingmodel='".$pricingmodel."'"));
					$laborcost = $labor_res[0];

					$emailmessage .= '<tr>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.($si_no++).'. </td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;" align="left">'.$prd_desc.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.$uom.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.$uom_qty.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;" align="right">$ '.number_format($uom_qty*$laborcost, 2).' &nbsp; </td>';
					$emailmessage .= '</tr>';
				}
			}
	
			$miscitemsqry = $db->query("select item_name, description, uom, qty, labor from remode_orderitems_miscs where by_ip=1 and is_approved!=1 and order_id='".$sys_order_id."'");
			if ($db->num_rows($miscitemsqry)>0) {	
				$miscitemsres = $db->fetch_assoc($miscitemsqry);
	
//				$emailmessage .= '<tr style="background:#fafafa; text-transform:uppercase;">';
//				$emailmessage .= '<th colspan="5" style="border-bottom:#b4b4b4; text-align:left; text-indent:15px;">Miscellaneous</th>';
//				$emailmessage .= '</tr>';
				
				foreach($miscitemsres as $orderitems) {
					extract($orderitems);
					trim($description)!=""? $prd_desc=$description : $prd_desc=$name;
	
					$emailmessage .= '<tr>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.($si_no++).'. </td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;" align="left">MISC: '.$prd_desc.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.$uom.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.$qty.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;" align="right">$ '.number_format($qty*$labor, 2).' &nbsp; </td>';
					$emailmessage .= '</tr>';
				}
			}
			$emailmessage .= '</table>';
		} else if($actstatus == "declined"){
			$subject = 'Declined Changes - Install Order# '.ORD_PREFIX.$order_id;
			$emailmessage = 'Products listed below has been Declined.<br /><br />';

			$emailmessage .= '<table style="border-collapse: collapse; border: solid 1px #cccccc; color: #444; font-family: Arial, Helvetica, sans-serif; text-align:center; font-size: 12px;" cellpadding="5">';

			$orderitemsqry = $db->query("select roi.pricingmodel, roi.description, roi.slfeet, roi.uom, roi.quantity, roi.category_id, roi.is_approved, roi.old_qsf, ro.installer_id, ro.store_num, ro.branchid from remode_orderitems roi 
			inner join remode_orders ro on ro.order_id=roi.order_id 
			where roi.by_ip=1 and is_approved!=1 and roi.order_id='".$sys_order_id."'");

			$si_no = 1;
			if ($db->num_rows($orderitemsqry)>0) {	
				$orderitemsres = $db->fetch_assoc($orderitemsqry);

				$emailmessage .= '<tr style="background:#eee; text-transform:uppercase;">';
				$emailmessage .= '<th width="5%" style="border-bottom:#b4b4b4;">#</th>';
				$emailmessage .= '<th width="65%" align="left" style="border-bottom:#b4b4b4;">Description</th>';
				$emailmessage .= '<th width="9%" style="border-bottom:#b4b4b4;">UOM</th>';
				$emailmessage .= '<th width="9%" align="center" style="border-bottom:#b4b4b4;">Qty</th>';
				$emailmessage .= '<th width="12%" align="center" style="border-bottom:#b4b4b4;">Labor Cost</th>';
				$emailmessage .= '</tr>';
				
				foreach($orderitemsres as $orderitems) {
					extract($orderitems);
	
					$uom_qty = (in_array(trim($uom), array("SF","LF"))? ($slfeet*$quantity) : $quantity);
					if($is_approved==2){
						$old_qsf = explode("_", $old_qsf);
						$uom_qty = $old_qsf[1];
					}
					trim($description)!=""? $prd_desc=$description : $prd_desc=$name;
	
					if($category_id==45){
						$uom_qty = $quantity * ceil($slfeet/8);
					}

					$ins_entryid = $db->fetch_row_single($db->query("select ins_rate_id from remode_installers where id='".$installer_id."'"));
					
					$labor_res = $db->fetch_row_single($db->query("select sum(rir.quantity*rir.IR".$ins_entryid[0].") 
					from remode_installer_rates rir 
					inner join variation_laborcode_map vlm on rir.labor_code=vlm.labour_code
					where vlm.pricingmodel='".$pricingmodel."'"));
					$laborcost = $labor_res[0];

					$emailmessage .= '<tr>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.($si_no++).'. </td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;" align="left">'.$prd_desc.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.$uom.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.$uom_qty.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;" align="right">$ '.number_format($uom_qty*$laborcost, 2).' &nbsp; </td>';
					$emailmessage .= '</tr>';
				}
			}
	
			$miscitemsqry = $db->query("select item_name, description, uom, qty, labor from remode_orderitems_miscs where by_ip=1 and is_approved!=1 and order_id='".$sys_order_id."'");
			if ($db->num_rows($miscitemsqry)>0) {	
				$miscitemsres = $db->fetch_assoc($miscitemsqry);
	
				foreach($miscitemsres as $orderitems) {
					extract($orderitems);
					trim($description)!=""? $prd_desc=$description : $prd_desc=$name;
	
					$emailmessage .= '<tr>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.($si_no++).'. </td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;" align="left">MISC: '.$prd_desc.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.$uom.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;">'.$qty.'</td>';
					$emailmessage .= '<td style="border-bottom: dashed 1px #ccc;" align="right">$ '.number_format($qty*$labor, 2).' &nbsp; </td>';
					$emailmessage .= '</tr>';
				}
			}
			$emailmessage .= '</table>';
		}
		// Include orderitems in email - Ends

//echo $emailmessage;
//exit;
		$mailCCto = $this->GetBranchAdmins($ord_branchid['branchid'], 1, 'arr');
		$mailBCCto = $attach_file = array();

		$fsmn_others = array("rmattm"=>1, "oet"=>"install", "afp"=>0, "bro_id"=>$order_id);
		$this->FnSentMailNotification($mail_to, $subject, $emailmessage, $mailCCto, $mailBCCto, $attach_file, $fsmn_others);

		// Approved installer added order items
		if($actstatus == "approved") {
			$db->query("update remode_orderitems SET by_ip='', old_qsf='' where order_id='".$sys_order_id."'");
			$db->query("update remode_orderitems_miscs SET by_ip='' where order_id='".$sys_order_id."'");
		}

		$db->query("update remode_orderitems SET by_ip='', old_qsf='', is_approved=1 where order_id='".$sys_order_id."' and is_approved=2");

		$db->query("delete from remode_orderitems where order_id='".$sys_order_id."' and is_approved=0");
		$db->query("delete from remode_orderitems_miscs where order_id='".$sys_order_id."' and is_approved=0");
	}

	
	function getlabourcost_pricingmodel($ins_pbid, $pricing_models){
		$db = $this->db_obj;
        $pricing_query = $db->query("select vlm.pricingmodel, rl.labor_code, rl.category, rl.uom, vlm.quantity, ".$ins_pbid."*vlm.quantity cost, rl.description, rl.lab_fab from remode_installer_rates as rl
		inner join variation_laborcode_map as vlm on vlm.labour_code = rl.labor_code
		where vlm.pricingmodel in ($pricing_models)");
		
		return $pricing_query;
	}
	
	function getInstallerInfo($installer_id){
		$db = $this->db_obj;

		$installer_details = $db->fetch_assoc_single($db->query("select installer, firstname, lastname, email, officephone, officeaddress, city, state, zipcode from remode_installers where id = ".$installer_id));
		return $installer_details;
	}
	
	function getStatusNames($status_group="admin"){
		$db = $this->db_obj;
		$order_status_master = [];
		$status_arr = $db->fetch_assoc($db->query("select name, status_code from remode_statusnames where br_group='".$status_group."' and status='1' order by sort_order"));

		if(!empty($status_arr))
			foreach($status_arr as $v){
				$order_status_master[$v['status_code']] = $v['name'];
			}

		return $order_status_master;
	}

	function decToFracPlain($decimal) {
		if ((float)$decimal) {
		  $integerPart = floor($decimal);
		 $decimalPart = round($decimal - $integerPart,4);
		$denominator = 1;
		  while (($decimalPart*10) % 10 != 0) {
			$decimalPart *= 10;
			$denominator *= 10;
		  }
		  $factor = $this->highestCommonFactor($decimalPart,$denominator);
		  $denominator = $denominator / $factor;
		 $numerator = $decimalPart / $factor;

		  if ($integerPart > 0) {
			if ($numerator)
			  return "$integerPart  $numerator/$denominator";
			else
			  return $integerPart;
		  } else {
			return "$numerator/$denominator";
		  }
		}
	  }
	  
	  function highestCommonFactor($a, $b) {
		if ($b == 0) return $a;
		return $this->highestCommonFactor($b, $a % $b);
	  }
}

?>