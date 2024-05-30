<?php

require_once("includes/config.php");

define("PHPGRID_AUTOCONNECT", 1);
define("PHPGRID_DBTYPE", "mysqli");
define("PHPGRID_DBHOST", HOSTNAME);
define("PHPGRID_DBUSER", HOSTUSER);
define("PHPGRID_DBPASS", HOSTPASS);
define("PHPGRID_DBNAME", HOSTDB);

/*if((isset($_SESSION[APPSESVAR.'_adminuserrole'])) && ($_SESSION[APPSESVAR.'_adminuserrole'] == "superadmin")) {
	$add_edit_delete_permission = true;
} else {
	$add_edit_delete_permission = false;
}
*/
// include and create object
if((isset($_REQUEST['issue_submit'])) &&($_REQUEST['issue_submit'] !="")){	
	
	$count = isset($_REQUEST['issue_track_check'])?count($_REQUEST['issue_track_check']):"";
	for($i=0;$i<$count;$i++){
		
		global $db;
		$db->query("UPDATE remode_issue_track SET issue_status ='".$_REQUEST['issue_status'][$i]."' where id ='".$_REQUEST['issue_track_check'][$i]."'") or die(mysql_error());
		if($_REQUEST['issue_status'][$i] =="Completed")
		{
			//mail send when status is completed
			global $mail;
			$mail->From = 'web@usremodelers.com';
			if(CW_ENV=="production"){
				$mail->FromName = 'TheHomeDepot';
				$mail->AddAddress($mail_to['email'], $mail_to['name']);			// Add a recipient
				$mail->AddReplyTo('ruby.roopesh@gmail.com','');
			} else {
				$mail->FromName = 'THD BR-Test';
				$mail->AddAddress('ruby.roopesh@gmail.com','');
			}
			$mail->IsHTML(true);                                  // Set email format to HTML
			$mail->Subject = "Issue(s) Completed";
			$content = "<table border='1' cellspacing='0' cellpadding='5'>";
			$content.= "<tr><th>Issued By</th>";
			$content.= "<th>Issue Info</th>";
			$content.= "<th>Issue Date</th>";
			$content.= "<th>Issue Status</th></tr>";
			
			$query_issues = $db->query("SELECT name,issue_info,issue_date,issue_status from remode_issue_track WHERE id ='".$_REQUEST['issue_track_check'][$i]."'");
$fetch_issues_res = $db->fetch_assoc($query_issues);
			foreach($fetch_issues_res as $fetch_issues)
			{
				$content.= "<tr><td>".$fetch_issues['name']."</td>";
				$content.= "<td>".$fetch_issues['issue_info']."</td>";
				$content.= "<td>".$fetch_issues['issue_date']."</td>";
				$content.= "<td>".$fetch_issues['issue_status']."</td></tr>";
			}
			$content.="</table>";
			$mail_content = $content;
			$mail->Body   = $mail_content;
			if(!$mail->Send()) 
			{
				echo 'Message was not sent.';
				echo 'Mailer error: ' . $mail->ErrorInfo;
				exit;
			} 
			else 
			{
				return true;
			}
		}
	}
}

include("../includes/jqgrid/inc/jqgrid_dist.php");
	
	$g = new jqgrid();

	//Customized columns ...
	$col 	= array();
	$cols 	= array();

	$col["title"] = "#"; // caption of column
	$col["name"] = "id"; // grid column name, must be exactly same as returned column-name from sql (tablefield or field-alias)
	$col["width"] = "2";
	$col["hidden"] = false; // hide column by default
	$cols[] = $col;  
	
	$col = array();
	$col["title"] = "Check"; // caption of column
	$col["name"] = "check_to_complete"; // grid column name, must be exactly same as returned column-name from sql (tablefield or field-alias)
	$col["search"] = false;
	$col["editable"] = false;
	$col["width"] = "2";
	$cols[] = $col;
	
	
	$col = array();
	$col["title"] = "Name"; // caption of column
	$col["name"] = "name"; // grid column name, must be exactly same as returned column-name from sql (tablefield or field-alias)
	$col["search"] = true;

	$col["editable"] = true;
	$col["editrules"] = array( "required"=>true);
	$col["width"] = "10";
	
	$col["hidden"] = false; // hide column by default
	$col["show"] = array("list"=>true,"edit"=>false,"add"=>true);
	$cols[] = $col;

	$col = array();
	$col["title"] = "Issue Info"; // caption of column
	$col["name"] = "issue_info"; // grid column name, must be exactly same as returned column-name from sql (tablefield or field-alias)
	$col["search"] = true;
	$col["editable"] = true;
	$col["edittype"] = "textarea"; 
	$col["editrules"] = array( "required"=>true);
	$col["width"] = "15";
	$col["hidden"] = false; // hide column by default
	$col["align"] = "center";
	$col["show"] = array("list"=>true,"edit"=>false,"add"=>true);
	$cols[] = $col;

	$col = array();
	$col["title"] = "Issue Date"; // caption of column
	$col["name"] = "issue_date"; // grid column name, must be exactly same as returned column-name from sql (tablefield or field-alias)
	$col["search"] = true;
	$col["editable"] = true;
	$col["editrules"] = array( "required"=>true);
	//$col["show"] = array("list"=>true,"edit"=>false,"add"=>true);
	$col["formatter"] = "date";
    $col["formatoptions"] = array("srcformat"=>'Y-m-d',"newformat"=>'m/d/Y');
    $col["datefmt"] = "Y-m-d";
	$col["width"] = "10";
	$col["hidden"] = false; // hide column by default
	$cols[] = $col;

	
	$col = array();
	$col["title"] = "Issue Status"; // caption of column
	$col["name"] = "issue_status"; // grid column name, must be exactly same as returned column-name from sql (tablefield or field-alias)
	$col["search"] = true;
	$col["editable"] = true;
	$col["editrules"] = array( "required"=>true);
	$col["width"] = "10";
	$col["edittype"] = "select";
	$col["editrules"] = array( "required"=>true);
	$col["editoptions"] = array("value"=>"Pending:Pending;In-Progress:In-Progress;Waiting for Approval:Waiting for Approval;Completed:Completed");
	$col["hidden"] = false; // hide column by default
	$cols[] = $col;

	$grid = array();
	// set few params for grid
	$grid["caption"] = "Issues Tracking";
	$grid["sortname"] = 'id';
	$grid["sortorder"] = "asc";
	$grid["multiselect"] = false; 
	//$grid["height"] = true;	
	//$grid["autowidth"] = true;
    $grid["autofit"] = true;
    $grid['width'] = 1024;
    $grid["add_options"] = array('width'=>'420');
    //$grid["edit_options"] = array('width'=>'420');
    $grid["form"]["position"] = "center";

	// export PDF file params
	$grid["export"] = array("filename"=>"HDI - Bath Remodeling - Issue Tracking", "heading"=>"HDI - Bath Remodeling - Issue Tracking", "orientation"=>"landscape", "paper"=>"a4"); 
	// for excel, sheet header
	$grid["export"]["sheetname"] = "Issue Tracking";

	// export filtered data or all data
	$grid["export"]["range"] = "filtered"; // or "all" 
	
	$g->set_options($grid);
	# Customization of Action column width and other properties
	$col = array();
	$col["title"] = "Action";
	$col["name"] = "act";
	$col["hidden"] = true;
	$col["width"] = "10";
	$cols[] = $col; 
	
	

	$g->set_actions(array(	
						"add"=>true, // allow/disallow add
						"inlineadd"=>false, // will allow adding new row, without dialog box
						"edit"=>false, // allow/disallow edit
						"delete"=>true, // allow/disallow delete
						"rowactions"=>true, // show/hide row wise edit/del/save option
						"export_excel"=>false, // show/hide export to excel option - must set export xlsx params
						"export_pdf"=>false, // show/hide export to pdf option - must set pdf params
						"autofilter" => true // show/hide autofilter for search 						
					) 
				);


	// params are array(<function-name>,<class-object> or <null-if-global-func>,<continue-default-operation>)
	// if you pass last argument as true, functions will act as a data filter, and insert/update will be performed by grid
	$e = array();
	$e["on_insert"] = array("add_issue", null, false);
	$e["on_update"] = array("update_issue", null, false);
	$e["on_delete"] = array("delete_issue", null, false); 
	//$e["on_data_display"] = array("product_var_link", null, true);
	$g->set_events($e); 
		
	function filter_survey($data)
    {
    	foreach($data["params"] as &$d)
    	{
			//Pending:Pending;In-Progress:In-Progress;Waiting for Aproval:Waiting for Aproval;Completed:Completed
			$d["issue_status"] = $d["issue_status"]==1?"Pending":"Pending";
    	}
    }

    function update_issue($data)
    {
    	$id	= $data["id"];
        $issue_status        = mysql_real_escape_string($data["params"]["issue_status"]); 

        mysql_query("UPDATE remode_issue_track SET issue_status = '".$issue_status."' where id = '".$id."'") or die(mysql_error());
		
    }


    function add_issue($data)
    {
		global $db;
    	$id	= $data["id"];
        $name        	= mysql_real_escape_string($data["params"]["name"]); 
        $issue_info     = mysql_real_escape_string($data["params"]["issue_info"]); 
        $issue_date     = mysql_real_escape_string($data["params"]["issue_date"]); 
        $issue_date     = ($issue_date=='')?'':date('Y-m-d',strtotime($issue_date));
        $issue_status   = mysql_real_escape_string($data["params"]["issue_status"]); 
		
		$db->query("INSERT INTO remode_issue_track SET name = '".$name."', issue_info = '".$issue_info."', issue_status = '".$issue_status."', issue_date = '".$issue_date."', created_date = now()") or die(mysql_error());
		$current_id = mysql_insert_id(); 
		$check = '<input type="checkbox" class="issue_track_check" name="issue_track_check[]" id="issue_track_check_'.$current_id.'" value="'.$current_id.'" />';
		$check = mysql_real_escape_string($check);
		$db->query("UPDATE remode_issue_track SET check_to_complete = '".$check."' where id = '".$current_id."'") or die(mysql_error());
	
		global $mail;
		$mail->From = 'web@usremodelers.com';
		if(CW_ENV=="production"){
			$mail->FromName = 'TheHomeDepot';
			$mail->AddAddress($mail_to['email'], $mail_to['name']);			// Add a recipient
			$mail->AddReplyTo('ruby.roopesh@gmail.com','');
		} else {
			$mail->FromName = 'THD BR-Test';
			$mail->AddAddress('ruby.roopesh@gmail.com','');
		}
		$mail->IsHTML(true);                                  // Set email format to HTML
		$mail->Subject = "Issue(s) Info from client";
		$content = "<table border='1' cellspacing='0' cellpadding='5'>";
		$content.= "<tr><th colspan='2'>Issue Details</th></tr>";
		$content.= "<tr><th>Issued By :</th><td>".$name."</td></tr>";
		$content.= "<tr><th>Issue Info :</th><td>".$issue_info."</td></tr>";
		$content.= "<tr><th>Issue Date :</th><td>".$issue_date."</td></tr></table>";
		$mail_content = $content;
		echo $mail_content;
		exit;
		$mail->Body   = $mail_content;
		if(!$mail->Send()) 
		{
			echo 'Message was not sent.';
			echo 'Mailer error: ' . $mail->ErrorInfo;
			exit;
		} 
		else 
		{
			return true;
		}
    } 
    function delete_issue($data)
    {
		global $db;
		$id	= $data["id"];
		$db->query("DELETE FROM `remode_issue_track` WHERE id ='".$id."' ") or die(mysql_error());
    }
	$g->select_command = "SELECT `id`, `name`, `issue_info`, `issue_date`, `completion_date`, `issue_status`, `check_to_complete`  FROM `remode_issue_track`";
	
	$g->set_columns($cols);         


    // render grid
    $issues = $g->render("issues");

	$fav_logo = APPLOGO;
	if (isset($_SESSION[APPSESVAR.'_admincompany']['icon']) && $_SESSION[APPSESVAR.'_admincompany']['icon']!=""){
		$fav_logo = $_SESSION[APPSESVAR.'_admincompany']['icon'];
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Task / Issue |  Bath Remodeling</title>
    <!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="icon" href="<?php echo ROOT."../images/company/".$fav_logo.".ico"; ?>" type="image/x-icon"/>
	<link rel="stylesheet" type="text/css" media="screen" href="../includes/jqgrid/js/themes/ui-lightness/jquery-ui.custom.css?v<?=$appConstData["app_version"]?>"></link>	
    <link rel="stylesheet" type="text/css" media="screen" href="../includes/jqgrid/js/jqgrid/css/ui.jqgrid.css?v<?=$appConstData["app_version"]?>"></link>
    <link href="styles/base.css?v<?=$appConstData["app_version"]?>" rel="stylesheet" type="text/css" />
    <script src="../includes/jqgrid/js/jquery.min.js?v<?=$appConstData["app_version"]?>" type="text/javascript"></script>
    <script src="../includes/jqgrid/js/jqgrid/js/i18n/grid.locale-en.js?v<?=$appConstData["app_version"]?>" type="text/javascript"></script>
    <script src="../includes/jqgrid/js/jqgrid/js/jquery.jqGrid.min.js?v<?=$appConstData["app_version"]?>" type="text/javascript"></script>	
    <script src="../includes/jqgrid/js/themes/jquery-ui.custom.min.js?v<?=$appConstData["app_version"]?>" type="text/javascript"></script>

<style>
.catalog_submenu{
	width:auto;
	height:auto;
	position:absolute;
	margin: 0 0 0 -10px;
	display:none;
	z-index:2;
	background:#fff;
	padding:3px;
	box-shadow:0 0 10px 2px #ccc;
}
.catalog_submenu li{
	width:90%;
	border:0;
	border-bottom: solid 1px #eee;
	padding:3px 5%;
	text-align:left;
}
.catalog_submenu li a{
	color:#333;
}
#catalog_main:hover > ul{
	display: block;
}
</style>
</head>
<body>

<header>
    <a href="#" title="Logo" style="float:left;"><img id="thd_logo" src="../images/company/<?=$fav_logo?>.jpg" alt="" /></a>
    <ol>
        <li style="background:#F88421 url(<?=ROOT?>../images/help-icon.png) no-repeat 5px 0px;">
        	<a id="help-fancybox" href="http://facelifters.com/helpdesk/indexAll.php?project=32&customer=1" style="color:#fff; padding-left:18px;"/>HELP</a>
        </li>
        <li>Welcome <b><?php echo $_SESSION[APPSESVAR.'_adminuser']['un']; ?></b> [ <a href="<?php echo ROOT."logout.html" ?>">logout</a> ]</li>
    </ol>
	<?=$amm->adminMainNavMenu()?>
</header>
<div class="content" style="float:left; min-width:950px; width:100%; min-height:495px; margin:20px auto 20px;" align="center">
<form name="issue_form" id="issue_form" method="post">
	<?php echo $issues; ?>
	<button type="submit" name="issue_submit" id="issue_submit" value="issue_submit" class="adminbtn" style="float:right; margin: 10px 12% 0 0;">Save Changes</button>

</form>
</div>
<footer>
	<div class="copy">Copyright @ <?php echo date("Y") ?> | All rights reserved.</div>
	<div class="credit">
    <a style="cursor:pointer;">Version <?=$appConstData["app_version"]?></a>
    | Powered by <a href="http://www.nathanresearch.com">Nathan Research Inc.</a></div>
</footer>

<script type="text/javascript">

$(document).ready(function(){
	$(document).on("click", ".issue_track_check", function(){
		var id = ($(this).val());
		//alert($('#issues').editRow(id,true).html());
		if ($("#issue_track_check_"+id+"").is(":checked")) {
			
			$('#issues').editRow(id,true);
			$(this).parent().next().show();
			$( "#"+id+"_issue_status" ).attr( "name", "issue_status[]" );
			var issue_name_val = $("#"+id+"_name").val();
			var issue_name = $(this).parents("tr.jqgrow").find("td:eq(2)").text(issue_name_val);
			var issue_info_val = $("#"+id+"_issue_info").val();
			var issue_info = $(this).parents("tr.jqgrow").find("td:eq(3)").text(issue_info_val);
			var issue_date_val = $("#"+id+"_issue_date").val();
			var issue_date = $(this).parents("tr.jqgrow").find("td:eq(4)").text(issue_date_val);
			$("#"+id+"_issue_date").css("width","85%");		
		}
		else{
			$('#issues').restoreRow(id); 
			$(this).parent().prev().show();
		}
	});
	//$("#issue_submit").click(function(){
		//alert($(".issue_track_check").val());
	//});
});
 </script>
</body>
</html>