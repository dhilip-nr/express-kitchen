<?php

require_once("includes/config.php");

ini_set('display_errors', 0);
error_reporting(0);

if(!isset($_SESSION[APPSESVAR.'_user'])){
	redirect("../login");
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

// include and create object
include("jqgrid/inc/jqgrid_dist.php");

ini_set('display_errors', 0);
// error_reporting(E_ALL);

$lookup_tab = isset($_GET['page'])?$_GET['page']: 'base_cabinets';
$display_tab_data = "";
switch($lookup_tab){
	case "base_cabinets":
		include("tabs/base_cabinets.php");
		$display_tab_data = $base_cabinets;
	break;
	case "sink_cabinets":
		include("tabs/sink_cabinets.php");
		$display_tab_data = $sink_cabinets;
	break;
	case "wall_cabinets":
		include("tabs/wall_cabinets.php");
		$display_tab_data = $wall_cabinets;
	break;
	case "tall_cabinets":
		include("tabs/tall_cabinets.php");
		$display_tab_data = $tall_cabinets;
	break;
	case "addons":
		include("tabs/addons.php");
		$display_tab_data = $addons;
	break;

}

?>



















<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
    <title>Designer 3D - Admin | ReMap Inc.</title>
	<link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/demo_1/style.css" />

  </head>
  <body>
    <div class="container-scroller">
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile border-bottom">
            <a href="#" class="nav-link flex-column">
              <div class="nav-profile-image">
                <img src="../../images/logo.png" alt="profile" />
              </div>
              <div class="nav-profile-text d-flex ml-0 mb-3 flex-column">
                <span class="font-weight-semibold mb-1 mt-2 text-center">3D Designer | Admin</span>
				<span style="text-align: center;">( <?=$_SESSION[APPSESVAR."_user"]["co_name"]?> )</span>
              </div>
            </a>
			
            <a class="nav-link change-workspace" href="../../workspace">
              <span class="menu-title">Back to Workspace</span>
            </a>

          </li>
<!--
          <li class="nav-item pt-3">
            <a class="nav-link d-block" href="index.html">
              <img class="sidebar-brand-logo" src="assets/images/logo.svg" alt="" />
              <img class="sidebar-brand-logomini" src="assets/images/logo-mini.svg" alt="" />
              <div class="small font-weight-light pt-1">Responsive Dashboard</div>
            </a>
          </li>
-->
          <li class="nav-item">
            <a class="nav-link" href="../orders">
              <i class="mdi mdi-format-list-bulleted menu-icon"></i>
              <span class="menu-title">Orders</span>
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="#">
              <i class="mdi mdi-table-large menu-icon"></i>
              <span class="menu-title">Catalog</span>
            </a>
          </li>

<?php if ($_SESSION[APPSESVAR."_user"]['role']=="admin" || $_SESSION[APPSESVAR."_user"]['role']=="superadmin"){ ?>
          <li class="nav-item">
            <a class="nav-link" href="../global_margin">
              <i class="mdi mdi-currency-usd menu-icon"></i>
              <span class="menu-title">Global Margin</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../users">
              <i class="mdi mdi-playlist-edit menu-icon"></i>
              <span class="menu-title">USERS</span>
            </a>
          </li>
<?php } ?>
        </ul>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
  
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
          <div class="navbar-menu-wrapper d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
              <span class="mdi mdi-menu"></span>
            </button>
            <ul class="navbar-nav">
				<li class="nav-item d-none d-lg-block">
					Products Catalog
				</li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
              <li class="nav-item nav-logout d-none d-lg-block">
                <a class="nav-link" href="../../logout">
				  <i class="mdi mdi-logout"></i>
                </a>
              </li>
            </ul>
          </div>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper pb-0">

            <!-- first row starts here -->
            <div class="row">
				


















				



				<link rel="stylesheet" type="text/css" media="screen" href="jqgrid/js/jqgrid/css/ui.jqgrid.css?v<?=$appConstData["app_version"]?>"></link>
				<link rel="stylesheet" type="text/css" media="screen" href="jqgrid/js/themes/remap/jquery-ui.custom.css?v<?=$appConstData["app_version"]?>"></link>
				<link href="../../css/admin.css" rel="stylesheet" type="text/css">

				<script src="jqgrid/js/jquery.min.js?v<?=$appConstData["app_version"]?>" type="text/javascript"></script>
				<script src="jqgrid/js/jqgrid/js/i18n/grid.locale-en.js?v<?=$appConstData["app_version"]?>" type="text/javascript"></script>
				<script src="jqgrid/js/jqgrid/js/jquery.jqGrid.min.js?v<?=$appConstData["app_version"]?>" type="text/javascript"></script>	
				<script src="jqgrid/js/themes/jquery-ui.custom.min.js?v<?=$appConstData["app_version"]?>" type="text/javascript"></script>


				<div class="container" style="width: 94%; max-width: 94%; margin-bottom: 40px;">

				<?php
				$lookup_page_array = array(
					array("title"=>"Base Cabinets", "link"=>"base_cabinets", "for_mgt"=>1),
					array("title"=>"Sink Cabinets", "link"=>"sink_cabinets", "for_mgt"=>1),
					array("title"=>"Wall Cabinets", "link"=>"wall_cabinets", "for_mgt"=>1),
					array("title"=>"Tall Cabinets", "link"=>"tall_cabinets", "for_mgt"=>1),
					array("title"=>"Add Ons", "link"=>"addons", "for_mgt"=>1)
				);
				?>

					<ul class="tabs">
				<?php
					foreach($lookup_page_array as $lpages){
						$is_active_link = "";
						if($lookup_tab==$lpages['link']){
							$active_page_title = $lpages['title'];
							$is_active_link = " class='active'";
						}
						$is_active_link = ($lookup_tab==$lpages['link'])? " class='tab-link current'" : " class='tab-link'";
						echo '<li'.$is_active_link.'><a href="index.php?page='.$lpages['link'].'">'.$lpages['title'].'</a></li>';
					}
				?>
					</ul>
					<div class="tab_links" id="content">
						<?=$display_tab_data?>
					</div>
				</div>




























	

            </div>

          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
<!--
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © bootstrapdash.com 2020</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap dashboard templates</a> from Bootstrapdash.com</span>
            </div>

            <div>
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block"> Distributed By: <a href="https://themewagon.com/" target="_blank">Themewagon</a></span>
            </div>
          </footer>
-->
        </div>
      </div>
    </div>
  </body>
</html>



