<?php
/* Smarty version 4.3.2, created on 2024-05-19 21:34:57
  from 'D:\Program Files\xampp\htdocs\hdi\designer-kitchen\admin\templates\sales_order.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_664a5461549f54_03466788',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9932ce1f2ee73e7bf303dc1fdb61d97e278a8529' => 
    array (
      0 => 'D:\\Program Files\\xampp\\htdocs\\hdi\\designer-kitchen\\admin\\templates\\sales_order.tpl',
      1 => 1716147296,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:popups/misc_item.tpl' => 1,
  ),
),false)) {
function content_664a5461549f54_03466788 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'D:\\ProgramFiles\\xampp\\htdocs\\hdi\\designer-kitchen\\libs\\plugins\\modifier.explode.php','function'=>'smarty_modifier_explode',),));
?>
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
          <li class="nav-item active">
            <a class="nav-link" href="#">
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
		 <?php if ($_SESSION[($_smarty_tpl->tpl_vars['APPSESVAR']->value).("_user")]['role'] == "admin" || $_SESSION[($_smarty_tpl->tpl_vars['APPSESVAR']->value).("_user")]['role'] == "superadmin") {?>
          <li class="nav-item">
            <a class="nav-link" href="global_margin">
              <i class="mdi mdi-currency-usd menu-icon"></i>
              <span class="menu-title">Global Margin</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="users">
              <i class="mdi mdi-playlist-edit menu-icon"></i>
              <span class="menu-title">USERS</span>
            </a>
          </li>
		  <?php }?>
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
					Orders
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
            <div class="page-header flex-wrap">

            </div>
            <!-- first row starts here -->
<ul class="breadcrumb"><li><a href="orders">HOME</a></li><li class="splitter"> | </li><li><?php echo $_smarty_tpl->tpl_vars['order_result']->value['order_id'];?>
</li></ul>
            <div class="row">
				

<style>
.breadcrumb {
	width: 100%;
	list-style: none;	
	margin-top: -1.5rem;
}
.breadcrumb li{
	display: inline-block;
	margin: 0 5px;
}
.breadcrumb li.splitter{
	transform: rotate(10deg);
}
</style>

<?php if ($_smarty_tpl->tpl_vars['order_result']->value['order_status'] == "wtg-approval") {?>
    <div id="ins_notification" style="background: #fbf4b5; border: 1px dashed #cfcfcf; border-radius: 5px; line-height: 50px; margin: 20px auto; max-width: 1000px; text-align: center; width: 96%; color:#666; font-weight:bold;">The changes made by installer are waiting for an approval.</div>
<?php } elseif ($_smarty_tpl->tpl_vars['order_result']->value['order_status'] == "revision") {?>
    <div id="ins_notification" style="background: #fbf4b5; border: 1px dashed #cfcfcf; border-radius: 5px; line-height: 50px; margin: 20px auto; max-width: 1000px; text-align: center; width: 96%; color:#666; font-weight:bold;">This order is in REVISION state.</div>
<?php } elseif ($_smarty_tpl->tpl_vars['order_result']->value['order_status'] == "canceled") {?>
    <div id="ins_notification" style="background: #FB8763; border: 1px dashed #cfcfcf; border-radius: 5px; line-height: 50px; margin: 20px auto; max-width: 1000px; text-align: center; width: 96%; color:#fff; font-weight:bold;">This order was canceled.</div>
<?php }?>

<div id="sales_order" class="print_content" style="margin: 0 auto; min-width: 920px;">

<?php if ($_smarty_tpl->tpl_vars['products_result']->value != '' || $_smarty_tpl->tpl_vars['misc_result']->value[0] > 0) {
$_smarty_tpl->_assignInScope('manfacturer_dup', '');?>
<form name="sales_order_frm" id="sales_order_frm" method="POST" action="">
<table class="orders_wrapper" cellspacing="0" id="material_order_table" name="material_order_table">
		<tr>
			<td colspan="4">
				ORDER ID : <b><?php echo $_smarty_tpl->tpl_vars['order_result']->value['order_id'];?>
</b>
            </td>
			
			<td colspan="2" style="text-align: center;">
				<a href="#" id="yourdesign" class="urdesign">Your Design</a>
			</td>
		</tr>
		<tr>
		<td colspan="6">
					<table class="customer_info">
                        <tr bgcolor="#F0F0F0">
                            <td colspan="4">
                                <b>Customer Details</b>
                            </td>
                        </tr>

                        <tr>
                            <td width="18%">
                            	<strong>Cust. ID</strong>
                            </td>
                            <td width="35%">
                            	<span class="customer_id"><?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_id'];?>
</span>
                            </td>
                            <td>
                            	<strong>Email</strong>
                            </td>
                            <td>
                            	<span class="email"><?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_email'];?>
</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            	<strong>Name</strong>
                            </td>
                            <td>
                            	<span class="name"><?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_name'];?>
</span>
                            </td>
                            <td>
                            	<strong>Tel #</strong>
                            </td>
                            <td>
                            	<span class="telephone"><?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_telephone'];?>
</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="13%">
                            	<strong>Address</strong>
                            </td>
                            <td colspan=3>
                            	<span class="address">
									<?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_address'];?>
, <?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_city'];?>
,
									<?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_state'];?>
 <?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_zipcode'];?>

								</span>
                            </td>
                        </tr>
					</table>
		</td>
		</tr>
		<tr id="item_title">
			<td>
				<b>#</b>
			</td>
			<td>
				<b>Pricing Model #</b>
			</td>
			<td>
				<b>Description</b>
			</td>
			<td>
				<b>UOM</b>
			</td>
			<td align="center">
				<b>Qty</b>
			</td>
			<td align="center">
				<b>Price</b>
			</td>
		</tr>

		<?php $_smarty_tpl->_assignInScope('i', 1);?>
		<?php $_smarty_tpl->_assignInScope('page_total', 0);?>
        <?php if (!empty($_smarty_tpl->tpl_vars['products_result']->value)) {?>
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['products_result']->value, 'value', false, 'key');
$_smarty_tpl->tpl_vars['value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->do_else = false;
?>
			<tr class="so-desc-table">
				<td><?php echo $_smarty_tpl->tpl_vars['i']->value++;?>
</td>
				<td><?php echo $_smarty_tpl->tpl_vars['value']->value['pricing_model'];?>
   <?php if (trim($_smarty_tpl->tpl_vars['value']->value['pricing_model']) == '') {?>-<?php }?></td>
				<td>
					<?php echo $_smarty_tpl->tpl_vars['value']->value['description'];?>
 - <?php echo $_smarty_tpl->tpl_vars['value']->value['category'];?>

					<?php if ($_smarty_tpl->tpl_vars['value']->value['dimension'] != '') {?>
						<br>
						<?php $_smarty_tpl->_assignInScope('product_dimension', (json_decode($_smarty_tpl->tpl_vars['value']->value['dimension'],1)));?>
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product_dimension']->value, 'ovalue', false, 'okey');
$_smarty_tpl->tpl_vars['ovalue']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['okey']->value => $_smarty_tpl->tpl_vars['ovalue']->value) {
$_smarty_tpl->tpl_vars['ovalue']->do_else = false;
?>
														<?php echo ucfirst($_smarty_tpl->tpl_vars['okey']->value);?>
: <?php echo $_smarty_tpl->tpl_vars['admin_fn']->value->decToFracPlain($_smarty_tpl->tpl_vars['ovalue']->value/2.54);?>
 In. <?php if (count($_smarty_tpl->tpl_vars['product_dimension']->value) && array_key_last($_smarty_tpl->tpl_vars['product_dimension']->value) != $_smarty_tpl->tpl_vars['okey']->value) {?> | <?php }?>
						<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					<?php }?>
          <?php if ($_smarty_tpl->tpl_vars['value']->value['options'] != '') {?>
            <?php $_smarty_tpl->_assignInScope('product_option', (json_decode($_smarty_tpl->tpl_vars['value']->value['options'],1)));?>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['product_option']->value, 'ovalue', false, 'okey');
$_smarty_tpl->tpl_vars['ovalue']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['okey']->value => $_smarty_tpl->tpl_vars['ovalue']->value) {
$_smarty_tpl->tpl_vars['ovalue']->do_else = false;
?>
            <br>[<b><?php echo ucfirst(strtr($_smarty_tpl->tpl_vars['okey']->value,'_','-'));?>
</b>] -  
            <?php $_smarty_tpl->_assignInScope('option_conf', ($_smarty_tpl->tpl_vars['ovalue']->value));?>
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['option_conf']->value, 'cvalue', false, 'ckey');
$_smarty_tpl->tpl_vars['cvalue']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['ckey']->value => $_smarty_tpl->tpl_vars['cvalue']->value) {
$_smarty_tpl->tpl_vars['cvalue']->do_else = false;
?>
				<?php echo ucfirst($_smarty_tpl->tpl_vars['ckey']->value);?>
 : <?php echo $_smarty_tpl->tpl_vars['cvalue']->value;?>
 <?php if (count($_smarty_tpl->tpl_vars['option_conf']->value) && array_key_last($_smarty_tpl->tpl_vars['option_conf']->value) != $_smarty_tpl->tpl_vars['ckey']->value) {?> | <?php }?>
              <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
          <?php }?>
				</td>
				<td align="center"><?php echo $_smarty_tpl->tpl_vars['value']->value['uom'];?>
</td>
				<td align="center"><?php echo $_smarty_tpl->tpl_vars['value']->value['prd_qty'];?>
</td>
				<td align="right">$<?php echo number_format($_smarty_tpl->tpl_vars['value']->value['prd_price'],2);?>
</td>
				<?php $_smarty_tpl->_assignInScope('page_total', $_smarty_tpl->tpl_vars['page_total']->value+$_smarty_tpl->tpl_vars['value']->value['prd_price']);?>
			</tr>
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <?php }?>
		<?php $_smarty_tpl->_assignInScope('promo_amount', 0);?>
        <?php if ($_smarty_tpl->tpl_vars['order_result']->value['promo_amt'] > 0) {?>
		<tr>
			<td colspan="5" align="right">
				<b>Grand Total :</b>
			</td>
			<td colspan="1" align="right">
			<?php if ($_smarty_tpl->tpl_vars['order_result']->value['total_amount'] > 0) {?>
                $<?php echo number_format((float)$_smarty_tpl->tpl_vars['order_result']->value['total_amount'],2);?>

			<?php } else { ?>
				$<?php echo number_format((float)$_smarty_tpl->tpl_vars['page_total']->value,2);?>

			<?php }?>
			</td>
		</tr>

        <tr>
		<?php if ($_smarty_tpl->tpl_vars['order_result']->value['promo_type'] == "percent") {?>
            <td colspan="5" align="right">
                <b>Promo ( <?php echo $_smarty_tpl->tpl_vars['order_result']->value['promo_percent'];?>
 % ) :</b>
            </td>
		<?php } else { ?>
            <td colspan="5" align="right">
                <b>Promo ( $ ) :</b>
            </td>
		<?php }?>

            <td colspan="1" align="right">
                <?php $_smarty_tpl->_assignInScope('promo_amount', $_smarty_tpl->tpl_vars['order_result']->value['promo_amt']);?>
                $<?php echo number_format((float)$_smarty_tpl->tpl_vars['promo_amount']->value,2);?>

            </td>
		</tr>
		<?php }?>

		<tr>
			<td colspan="5" align="right">
				<b>Net Amount :</b>
			</td>
			<td colspan="1" align="right">
                <?php $_smarty_tpl->_assignInScope('net_amount', $_smarty_tpl->tpl_vars['order_result']->value['total_amount']-$_smarty_tpl->tpl_vars['promo_amount']->value);?>
                $<?php echo number_format($_smarty_tpl->tpl_vars['net_amount']->value,2);?>

			</td>
		</tr>

        <?php if ($_smarty_tpl->tpl_vars['order_result']->value['attached_files'] != '') {?>
             <tr bgcolor="#F0F0F0">
                <td colspan="6">
                    <b>Sales Rep uploaded files</b>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <?php $_smarty_tpl->_assignInScope('attached_files', smarty_modifier_explode(",",$_smarty_tpl->tpl_vars['order_result']->value['attached_files']));?>
                    <ul style="width:100%; display:block;">
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['attached_files']->value, 'attachments', false, 'key');
$_smarty_tpl->tpl_vars['attachments']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['attachments']->value) {
$_smarty_tpl->tpl_vars['attachments']->do_else = false;
?>
						<?php if (trim($_smarty_tpl->tpl_vars['attachments']->value) != '') {?>
                        <li style="display:inline-block; margin:0 15px 10px 0;"><b style="color:#F88421; border:solid 1px; padding:2px 6px; border-radius:15px;"><?php echo $_smarty_tpl->tpl_vars['key']->value+1;?>
</b> <a class="preview_file" href="../uploads/<?php echo $_smarty_tpl->tpl_vars['attachments']->value;?>
" target="_blank" style="text-decoration: underline;"><?php echo $_smarty_tpl->tpl_vars['attachments']->value;?>
</a></li>
						<?php }?>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    </ul>
                </td>
            </tr>
        <?php }?>
		<tr class="s_tr print_order">
            <td colspan="6">
                <center>
					<input type="button" id="sendmail" class="adminbtn" value="Send Mail" />
                </center>
            </td>
		</tr>
<?php $_smarty_tpl->_assignInScope('branch_admin_arr', smarty_modifier_explode(',',$_smarty_tpl->tpl_vars['installer_email']->value));
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['bradminres']->value, 'bradmin_list');
$_smarty_tpl->tpl_vars['bradmin_list']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['bradmin_list']->value) {
$_smarty_tpl->tpl_vars['bradmin_list']->do_else = false;
?>
	<?php $_tmp_array = isset($_smarty_tpl->tpl_vars['branch_admin_arr']) ? $_smarty_tpl->tpl_vars['branch_admin_arr']->value : array();
if (!(is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess)) {
settype($_tmp_array, 'array');
}
$_tmp_array[] = $_smarty_tpl->tpl_vars['bradmin_list']->value;
$_smarty_tpl->_assignInScope('branch_admin_arr', $_tmp_array);
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
$_smarty_tpl->_assignInScope('branch_admin', array_unique(array_filter($_smarty_tpl->tpl_vars['branch_admin_arr']->value)));
$_smarty_tpl->_assignInScope('branch_admin', strtolower(implode(",",$_smarty_tpl->tpl_vars['branch_admin']->value)));?>
</table>
</form>

	<?php if (trim($_smarty_tpl->tpl_vars['order_result']->value['comments']) != '' && trim($_smarty_tpl->tpl_vars['order_result']->value['comments']) != '<br>') {?>
        <div class="orders_wrapper" style="border:solid 1px #ccc;">
            <fieldset style="border: 0px none; padding: 15px 10px; text-align:justify;">
                <b align="center" style="display:block; text-align:center; margin-bottom:10px">Instructions / Comments</b>
                <?php echo $_smarty_tpl->tpl_vars['order_result']->value['comments'];?>

            </fieldset>
        </div>
    <?php }?>

<?php } else { ?>
	<div align="center" style="padding:50px;">No Items for this order</div>
</div>
<?php }?>



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
<?php $_smarty_tpl->_subTemplateRender("file:popups/misc_item.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
