<?php
//error_reporting(0);
require_once("includes/config.php");

if(!isset($_SESSION[APPSESVAR.'_user']['un'])){
	header('HTTP/1.1 401 Unauthorized');
	exit;
}

$aColumns = array( 'order_id', 'jobid', 'branch', 'cust_name', 'cust_phone', 'total_amount', 'total_prdcost', 'total_lbrcost', 'margin_cost', 'margin_percent', 'created_date', 'status', '1', 'created_by', 'concat("<a class=\'flow_link\' href=\'sales_order?id=", order_id, "\' target=\'_blank\'>View Details</a>") as action', 'sent_installer', 'sent_material', 'repname', 'comments', 'dealer_mat_status' );

$sWhere = " WHERE 1=1 ";

if($_SESSION[APPSESVAR.'_user']['co_id']!=""){
	$sWhere = " WHERE company_id='".$_SESSION[APPSESVAR.'_user']['co_id']."' ";
}

/*   Filter orders with customers only   */
$view=isset($_REQUEST['view']) ? $_REQUEST['view'] : "";
/*
if($view=='null' || $view==''){
	$sWhere .= " AND status<>'quote' ";
}
*/
$view_arr = explode(",",$view);
if(in_array("bycompany",$view_arr)){
	$company = explode(",", $_REQUEST['company']);
	$sWhere .= " AND company IN('".implode("','", $company)."')";
}

if(in_array("bybranch",$view_arr)){
	$branch = explode(",", $_REQUEST['branch']);
	$sWhere .= " AND branch IN('".implode("','", $branch)."')";
} else {
	if($_SESSION[APPSESVAR.'_user']['branch'] != 0){
		$user_branch_qry = $db->query("select group_concat(name) branches from remode_branch_master where id in (".$_SESSION[APPSESVAR.'_user']['branch'].") and id!=".$fn->isTestBranch());
		$user_branch = $db->fetch_assoc_single($user_branch_qry);
		$user_branch = explode(",", $user_branch['branches']);
		$sWhere .= " AND branch IN('" .implode("','", $user_branch) ."') ";
	} else{
		if($_SESSION[APPSESVAR.'_user']['branch']!=""){
			$branch_by = " id IN (".$_SESSION[APPSESVAR.'_user']['branch'].") and ";
		} else if($_SESSION[APPSESVAR.'_user']['co_id']!=""){
			$branch_by = " id IN (".$_SESSION[APPSESVAR.'_user']['branch'].") and ";
		}

//		$company_branch = $db->fetch_assoc_single($db->query("select id branch_id, name branch from remode_branch_master WHERE ".$branch_by." status='1' order by name"));
//		$sWhere .= " AND branch IN ('".$company_branch['branch']."') ";
	}
}


if(in_array("bystatus",$view_arr)){
	$status = explode(",", $_REQUEST['status']);
	$sWhere .= " AND status IN('".implode("','", $status)."')";
}

/*
if(in_array("bystatus",$view_arr)){
	$status=$_REQUEST['status'];
	$sWhere.= " AND status='".$status."'";
}
*/
if(in_array("bwdates",$view_arr)){
	$ftdates=explode("<->", $_REQUEST['bwdates']);
	$sWhere.= " AND created_date between '".$ftdates[0]."' and '".$ftdates[1]."'";
}

if(in_array("withcusts", $view_arr) || in_array("null", $view_arr)){
	$sWhere.= " AND cust_name!=''";
}
/*   Filter orders with customers only   */



/*   Search Condition Begins   */

	/* individual colum search - starts */
		$whereFields = "";
		foreach ($_GET['columns'] as $key=>$searchCol) {
			if ( isset($searchCol['searchable']) && $searchCol['searchable'] == "true" && $searchCol['search']['value']!='') {
				$whereFields .= "`".$aColumns[$searchCol['data']]."` LIKE '%".$db->real_escape($searchCol['search']['value'])."%' OR ";
			}
		}
		$whereFields = substr_replace($whereFields, "", -4);
		if(trim($whereFields)!='')
			$sWhere .= ' AND ('.$whereFields.')';
	/* individual colum search - ends */

	/* Multi-colum search - Starts */
	if ( isset($_GET['search']) && $_GET['search']['value'] != '' ) {
		$whereFields = "";
		foreach ($_GET['columns'] as $key=>$searchCol) {
			if ( isset($searchCol['searchable']) && $searchCol['searchable'] == "true" ) {
				$whereFields .= "`".$aColumns[$searchCol['data']]."` LIKE '%".$db->real_escape($_GET['search']['value'])."%' OR ";
			}
		}
		$whereFields = substr_replace($whereFields, "", -4);
		if(trim($whereFields)!='')
			$sWhere .= ' AND ('.$whereFields.')';
	}
	/* Multi-colum search - Ends */
/*   Search Condition Ends   */

$sOrder = "";
if(isset($_GET['order'])) {
	$sOrder = "ORDER BY  ";
	foreach($_GET['order'] as $orderbycol){
		$sOrder .= "`".$aColumns[ intval($orderbycol['column'])]."` ".($orderbycol['dir']==='asc' ? 'asc' : 'desc') .", ";
	}
	
	$sOrder = substr_replace( $sOrder, "", -2 );
	if ($sOrder == "ORDER BY") {
		$sOrder = "";
	}
}

$sLimit = "";
if ( isset( $_GET['start'] ) && $_GET['length'] != '-1' ){
	$sLimit = " LIMIT " .intval($_GET['start']) .", " .intval($_GET['length']);
}

	$sTable="remode_ocbu_view";
//echo "<pre>";
//print_r($_SESSION[APPSESVAR.'_admincompany']);
	$orderqry = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere
		$sOrder";

		$order_status_master = $fn->getStatusNames();

		$orderres="";
		$num_rows = $db->num_rows($db->query($orderqry));
		if($num_rows>0){		
			$orderres = $db->fetch_row($db->query($orderqry." ".$sLimit));
			foreach($orderres as $key=>$order_res){
				if($order_res[13]!=""){
					$array_replacements = array(13=>"<a title='".$order_res[17]."'>".$order_res[13]."</a>");
					$orderres[$key] = array_replace($orderres[$key], $array_replacements);
				}

				if($order_res[11]=="submitted" && $order_res[19]!=""){
					$array_replacements = array(11=>"MO Received");
					if($_SESSION[APPSESVAR.'_user']['role']=="dealer" && $order_res[19]!=""){
						$array_replacements = array(11=>"MO Placed");
					}
					$orderres[$key] = array_replace($orderres[$key], $array_replacements);
				}

				if(in_array($order_res[11], array_keys($order_status_master))){
					$array_replacements = array(11=>$order_status_master[$order_res[11]]);
					$orderres[$key] = array_replace($orderres[$key], $array_replacements);
				}
				
				$comments_data = trim(str_replace("<br>", "", $order_res[18]));
				if($comments_data!=""){
					$array_replacements = array(12=>"<a href='#' class='show_comments'>show</a>");
					$orderres[$key] = array_replace($orderres[$key], $array_replacements);
				} else {
					$array_replacements = array(12=>"-");
					$orderres[$key] = array_replace($orderres[$key], $array_replacements);
				}
			}
		}

		$output = array(
			"draw" => intval($_REQUEST['draw']),
			"recordsTotal" => $num_rows,
			"recordsFiltered" => $num_rows,
			"data" => $orderres
		);
		echo json_encode($output);

?>
