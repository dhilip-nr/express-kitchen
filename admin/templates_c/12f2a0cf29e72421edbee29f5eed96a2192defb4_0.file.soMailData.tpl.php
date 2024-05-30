<?php
/* Smarty version 4.3.2, created on 2023-12-16 03:34:12
  from 'D:\Program Files\xampp\htdocs\hdi\designer-global\admin\templates\soMailData.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_657d0ca40f3734_76751727',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '12f2a0cf29e72421edbee29f5eed96a2192defb4' => 
    array (
      0 => 'D:\\Program Files\\xampp\\htdocs\\hdi\\designer-global\\admin\\templates\\soMailData.tpl',
      1 => 1702319309,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_657d0ca40f3734_76751727 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['show_price']->value == 0) {?>
	<?php $_smarty_tpl->_assignInScope('col_width', array(5,15,60,10,10));
} else { ?>
	<?php $_smarty_tpl->_assignInScope('col_width', array(5,15,50,9,9,12));
}?>
<table cellspacing="0" cellpadding="5" style="border:solid 1px #b4b4b4; width:100%; border-collapse:collapse;">
  <tr>
    <td colspan="<?php echo 5+$_smarty_tpl->tpl_vars['show_price']->value;?>
">
      <table cellspacing="0" cellpadding="5" style="border:0; width:100%; border-collapse:collapse;">
        <tr>
          <td width="15%">ORDER ID :</td><td width="25%"><b><?php echo $_smarty_tpl->tpl_vars['order_result']->value['order_id'];?>
</b></td>
          <td colspan="2" bgcolor="#F0F0F0" style="border:solid 1px #ccc;">&nbsp;&nbsp;&nbsp; <b>Customer Details</b></td>
        </tr>
        <tr>
          <td>JOB ID : </td><td><b><?php echo $_smarty_tpl->tpl_vars['order_result']->value['jobid'];
if ($_smarty_tpl->tpl_vars['order_result']->value['jobid'] == '') {?>-<?php }?></b></td>
          <td width="25%" style="border:solid 1px #ccc; border-right:none;">&nbsp;&nbsp;&nbsp; Name</td>
          <td width="35%" style="border:solid 1px #ccc; border-left:none;"><?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_name'];?>
</td>
        </tr>
        <tr>
          <td>LEAD ID : </td><td><b><?php echo $_smarty_tpl->tpl_vars['order_result']->value['lead_id'];
if ($_smarty_tpl->tpl_vars['order_result']->value['lead_id'] == '') {?>-<?php }?></b></td>
          <td style="border:solid 1px #ccc; border-right:none;">&nbsp;&nbsp;&nbsp; Email</td>
          <td style="border:solid 1px #ccc; border-left:none;"><?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_email'];?>
</td>
        </tr>
        <tr>
          <td>Cust. ID : </td><td><b><?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_id'];?>
</b></td>
          <td style="border:solid 1px #ccc; border-right:none;">&nbsp;&nbsp;&nbsp; Tel #</td>
          <td style="border:solid 1px #ccc; border-left:none;"><?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_telephone'];?>
</td>
        </tr>
        <tr>
          <td>Rep. Name : </td><td><b><?php echo $_smarty_tpl->tpl_vars['order_result']->value['repname'];?>
</b></td>
          <td style="border:solid 1px #ccc; border-right:none;">&nbsp;&nbsp;&nbsp; Address</td>
          <td style="border:solid 1px #ccc; border-left:none;"><?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_address'];?>
, <?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_city'];?>
, <?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_state'];?>
 <?php echo $_smarty_tpl->tpl_vars['order_result']->value['customer_zipcode'];?>
</td>
        </tr>
      </table></td>
  </tr>
  <tr bgcolor="#F0F0F0">
    <td width="<?php echo $_smarty_tpl->tpl_vars['col_width']->value[0];?>
%" align="center"><b>#</b></td>
    <td width="<?php echo $_smarty_tpl->tpl_vars['col_width']->value[1];?>
%"><b>Pricing Model #</b></td>
    <td width="<?php echo $_smarty_tpl->tpl_vars['col_width']->value[2];?>
%"><b>Description</b></td>
    <td width="<?php echo $_smarty_tpl->tpl_vars['col_width']->value[3];?>
%" align="center"><b>UOM</b></td>
    <td width="<?php echo $_smarty_tpl->tpl_vars['col_width']->value[4];?>
%" align="center"><b>Qty</b></td>
<?php if ($_smarty_tpl->tpl_vars['show_price']->value == 1) {?>
    <td width="<?php echo $_smarty_tpl->tpl_vars['col_width']->value[5];?>
%" align="center"><b>Price</b></td>
<?php }?>
  </tr>

<?php $_smarty_tpl->_assignInScope('total_amount', 0);
if ($_smarty_tpl->tpl_vars['products_result']->value > 0) {?>
  <?php $_smarty_tpl->_assignInScope('i', 1);?>
  <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['products_result']->value, 'value', false, 'key');
$_smarty_tpl->tpl_vars['value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->do_else = false;
?>
  <tr>
    <td style="border:#ccc solid 1px;" align="center"><?php echo $_smarty_tpl->tpl_vars['i']->value++;?>
</td>
    <td style="border:#ccc solid 1px;"><?php echo $_smarty_tpl->tpl_vars['value']->value['pricing_model'];?>
 <?php if (trim($_smarty_tpl->tpl_vars['value']->value['pricing_model']) == '') {?>-<?php }?></td>
    <td style="border:#ccc solid 1px;"><?php echo $_smarty_tpl->tpl_vars['value']->value['description'];?>
 <?php if ($_smarty_tpl->tpl_vars['value']->value['prd_options'] != '') {?>| <?php echo $_smarty_tpl->tpl_vars['value']->value['prd_options'];
}?></td>
    <td style="border:#ccc solid 1px;" align="center"><?php echo $_smarty_tpl->tpl_vars['value']->value['uom'];?>
</td>
    <td style="border:#ccc solid 1px;" align="center"><?php echo $_smarty_tpl->tpl_vars['value']->value['prd_qty'];?>
</td>
<?php if ($_smarty_tpl->tpl_vars['show_price']->value == 1) {?>
    <td style="border:#ccc solid 1px;" align="right">$<?php echo number_format($_smarty_tpl->tpl_vars['value']->value['prd_price'],2);?>
</td>
	<?php $_smarty_tpl->_assignInScope('total_amount', $_smarty_tpl->tpl_vars['total_amount']->value+$_smarty_tpl->tpl_vars['value']->value['prd_price']);
}?>
  </tr>
  <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?> 
<?php }?>

  <!-- Misc item display starts --> 
  <?php if ($_smarty_tpl->tpl_vars['misc_result']->value[0] > 0) {?>
  <tr bgcolor="#F0F0F0">
    <td colspan="<?php echo 5+$_smarty_tpl->tpl_vars['show_price']->value;?>
"><b>Miscellaneous items</b></td>
  </tr>

  <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['misc_result']->value[1], 'value', false, 'key');
$_smarty_tpl->tpl_vars['value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->do_else = false;
?>
  <tr>
    <td style="border:#ccc solid 1px;" align="center"><?php echo $_smarty_tpl->tpl_vars['key']->value+1;?>
</td>
    <td style="border:#ccc solid 1px;"><?php echo $_smarty_tpl->tpl_vars['value']->value['item_name'];?>
</td>
    <td style="border:#ccc solid 1px;"><?php echo $_smarty_tpl->tpl_vars['value']->value['description'];?>
</td>
    <td style="border:#ccc solid 1px;" align="center"><?php echo $_smarty_tpl->tpl_vars['value']->value['uom'];?>
</td>
    <td style="border:#ccc solid 1px;" align="center"><?php echo $_smarty_tpl->tpl_vars['value']->value['qty'];?>
</td>
<?php if ($_smarty_tpl->tpl_vars['show_price']->value == 1) {?>
    <td style="border:#ccc solid 1px;" align="right">$<?php echo number_format($_smarty_tpl->tpl_vars['value']->value['qty']*$_smarty_tpl->tpl_vars['value']->value['retail'],2);?>
</td>
<?php }?>
  </tr>
  <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>	
  <?php }?> 
  <!-- Misc item display ends --> 

<?php if ($_smarty_tpl->tpl_vars['show_price']->value == 1) {?>
  <tr bgcolor="#F0F0F0">
    <td colspan="<?php echo 4+$_smarty_tpl->tpl_vars['show_price']->value;?>
" align="right"><b>Grand Total :</b></td>
    <td colspan="1" align="right"> $<?php echo number_format((float)$_smarty_tpl->tpl_vars['total_amount']->value,2);?>
 </td>
  </tr>
<!--
  <tr bgcolor="#F0F0F0">
    <td colspan="<?php echo 4+$_smarty_tpl->tpl_vars['show_price']->value;?>
" align="right"><b>Grand Total :</b></td>
    <td colspan="1" align="right"> $<?php echo number_format((float)$_smarty_tpl->tpl_vars['order_result']->value['total_amount'],2);?>
 </td>
  </tr>
  <tr>
    <?php if ($_smarty_tpl->tpl_vars['order_result']->value['promo_type'] == "percent") {?>
        <td colspan="5" align="right"><b>Promo ( <?php echo $_smarty_tpl->tpl_vars['order_result']->value['promo_percent'];?>
 % ) :</b></td>
    <?php } else { ?>
        <td colspan="5" align="right"> <b>Promo ( $ ) :</b></td>
    <?php }?>
		<?php $_smarty_tpl->_assignInScope('promo_amount', $_smarty_tpl->tpl_vars['order_result']->value['promo_amt']);?>
        <td colspan="1" align="right">  $<?php echo number_format((float)$_smarty_tpl->tpl_vars['promo_amount']->value,2);?>
 </td>
  </tr>

<?php if ($_smarty_tpl->tpl_vars['order_result']->value['apd_amt'] > 0) {?>
  <tr>
    <td colspan="5" align="right"><b>Adnl. Discount :</b></td>
    <td colspan="1" align="right"> $<?php echo number_format((float)$_smarty_tpl->tpl_vars['order_result']->value['apd_amt'],2);?>
 </td>
  </tr>
<?php }
if ($_smarty_tpl->tpl_vars['order_result']->value['admin_fee'] > 0) {?>
  <tr>
    <td colspan="5" align="right"><b>Administrative Fees :</b></td>
    <td colspan="1" align="right"> $<?php echo number_format((float)$_smarty_tpl->tpl_vars['order_result']->value['admin_fee'],2);?>
 </td>
  </tr>
<?php }?>

  <tr>
    <td colspan="5" align="right"><b>Lead Test Fee :</b></td>
    <td colspan="1" align="right"> $<?php echo number_format((float)$_smarty_tpl->tpl_vars['order_result']->value['lt_amt'],2);?>
 </td>
  </tr>
  <tr>
    <td colspan="5" align="right"><b>Lead Free Work Practice :</b></td>
    <td colspan="1" align="right"> $<?php echo number_format((float)$_smarty_tpl->tpl_vars['order_result']->value['lfwp_amt'],2);?>
 </td>
  </tr>
  <tr>
    <td colspan="5" align="right"><b>Permit :</b></td>
    <td colspan="1" align="right"> <?php $_smarty_tpl->_assignInScope('permit_amt', ($_smarty_tpl->tpl_vars['order_result']->value['total_amount']+$_smarty_tpl->tpl_vars['order_result']->value['admin_fee']+$_smarty_tpl->tpl_vars['order_result']->value['lt_amt']+$_smarty_tpl->tpl_vars['order_result']->value['lfwp_amt']-$_smarty_tpl->tpl_vars['promo_amount']->value-$_smarty_tpl->tpl_vars['order_result']->value['apd_amt'])*$_smarty_tpl->tpl_vars['order_result']->value['permit_percent']/100);?>
      <?php if ($_smarty_tpl->tpl_vars['permit_amt']->value > $_smarty_tpl->tpl_vars['order_result']->value['permit_max']) {?> <?php $_smarty_tpl->_assignInScope('permit_amt', $_smarty_tpl->tpl_vars['order_result']->value['permit_max']);?> <?php }?>
      $<?php echo number_format((float)$_smarty_tpl->tpl_vars['permit_amt']->value,2);?>
 </td>
  </tr>
  <?php if ($_smarty_tpl->tpl_vars['order_result']->value['disc_sur_amt'] > 0) {?>
  <tr>
    <td colspan="5" align="right"><b>Adjustment Amount :</b></td>
  	<td colspan="1" align="right"> - $<?php echo number_format((float)$_smarty_tpl->tpl_vars['order_result']->value['disc_sur_amt'],2);?>
 </td>
  </tr>
  <?php }?>

  <tr>
    <td colspan="5" align="right"><b>Net Amount :</b></td>
    <td colspan="1" align="right"> $<?php echo number_format((float)($_smarty_tpl->tpl_vars['order_result']->value['net_amount']-$_smarty_tpl->tpl_vars['order_result']->value['disc_sur_amt']),2);?>
 </td>
  </tr>
<?php }?>
-->
  </table>
<?php }
}
