<?php
/* Smarty version 4.3.2, created on 2023-12-19 15:58:21
  from 'D:\Program Files\xampp\htdocs\hdi\designer-global\admin\templates\users.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_6581af8d49ca60_88645788',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3df3bb0411a6b04164c0ce0d6bd487201c2f467f' => 
    array (
      0 => 'D:\\Program Files\\xampp\\htdocs\\hdi\\designer-global\\admin\\templates\\users.tpl',
      1 => 1701687421,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6581af8d49ca60_88645788 (Smarty_Internal_Template $_smarty_tpl) {
?>    <link rel="stylesheet" type="text/css" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/demo_1/style.css" />
	<link rel="stylesheet" type="text/css" href="../css/admin.css">

	<link rel="stylesheet" type="text/css" media="screen" href="catalog/jqgrid/js/jqgrid/css/ui.jqgrid.css?v<?php echo $_smarty_tpl->tpl_vars['appConstData']->value['app_version'];?>
"></link>
	<link rel="stylesheet" type="text/css" media="screen" href="catalog/jqgrid/js/themes/remap/jquery-ui.custom.css?v<?php echo $_smarty_tpl->tpl_vars['appConstData']->value['app_version'];?>
"></link>

    <div class="container-scroller">
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile border-bottom">
            <a href="#" class="nav-link flex-column">
              <div class="nav-profile-image">
                <img src="../images/logo.png" alt="profile" />
              </div>
              <div class="nav-profile-text d-flex ml-0 mb-3 flex-column">
                <span class="font-weight-semibold mb-1 mt-2 text-center">3D Designer | Admin</span>
				<span style="text-align: center;">( <?php echo $_SESSION[($_smarty_tpl->tpl_vars['APPSESVAR']->value).("_user")]['co_name'];?>
 )</span>
              </div>
            </a>
			
            <a class="nav-link change-workspace" href="../workspace">
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
            <a class="nav-link" href="orders">
              <i class="mdi mdi-format-list-bulleted menu-icon"></i>
              <span class="menu-title">Orders</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="catalog">
              <i class="mdi mdi-table-large menu-icon"></i>
              <span class="menu-title">Catalog</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="global_margin">
              <i class="mdi mdi-currency-usd menu-icon"></i>
              <span class="menu-title">Global Margin</span>
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="users">
              <i class="mdi mdi-playlist-edit menu-icon"></i>
              <span class="menu-title">USERS</span>
            </a>
          </li>
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
					Manage Users
				</li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
              <li class="nav-item nav-logout d-none d-lg-block">
                <a class="nav-link" href="../logout">
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

				<div class="container" style="width: 94%; max-width: 94%; margin-bottom: 40px;">
					<ul class="tabs">
						<li class="tab-link current"><a href="#">Users</a></li>
					</ul>
					<div class="tab_links" id="content">
					<?php echo $_smarty_tpl->tpl_vars['display_tab_data']->value;?>

					</div>
				</div>

            </div>
          </div>
        </div>
      </div>
    </div>

	<?php echo '<script'; ?>
 src="catalog/jqgrid/js/jquery.min.js?v<?php echo '<?'; ?>
=$appConstData["app_version"]<?php echo '?>'; ?>
" type="text/javascript"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="catalog/jqgrid/js/jqgrid/js/i18n/grid.locale-en.js?v<?php echo $_smarty_tpl->tpl_vars['appConstData']->value['app_version'];?>
" type="text/javascript"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="catalog/jqgrid/js/jqgrid/js/jquery.jqGrid.min.js?v<?php echo $_smarty_tpl->tpl_vars['appConstData']->value['app_version'];?>
" type="text/javascript"><?php echo '</script'; ?>
>	
	<?php echo '<script'; ?>
 src="catalog/jqgrid/js/themes/jquery-ui.custom.min.js?v<?php echo $_smarty_tpl->tpl_vars['appConstData']->value['app_version'];?>
" type="text/javascript"><?php echo '</script'; ?>
>

	<?php echo '<script'; ?>
 type="text/javascript">
		function toggleEdit(mode) {
			if(mode=="save"){
				$("#margin_form [name=percent]").val($("#set-margin .save [name=margin]").val());
				$("#margin_form").submit();
			} else if(mode=="edit"){
				$("#set-margin .view").addClass("hidden");
				$("#set-margin .save").removeClass("hidden");
			} else if(mode=="cancel"){
				$("#set-margin .save").addClass("hidden");
				$("#set-margin .view").removeClass("hidden");
			}
		}
	<?php echo '</script'; ?>
>
	<?php }
}
