<?php
/* Smarty version 4.3.2, created on 2024-05-01 19:15:58
  from 'D:\Program Files\xampp\htdocs\hdi\designer-kitchen\admin\templates\popups\misc_item.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_663278ceeb8580_92122904',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a94143724305a83df8a9e09f24900c0c6af4361f' => 
    array (
      0 => 'D:\\Program Files\\xampp\\htdocs\\hdi\\designer-kitchen\\admin\\templates\\popups\\misc_item.tpl',
      1 => 1707218495,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_663278ceeb8580_92122904 (Smarty_Internal_Template $_smarty_tpl) {
?><style>
#misc_order_items .ilabel{
	font-size:12px;
	text-transform:uppercase;
	color:#666;
	letter-spacing:1px;
}
#misc_order_items input, #misc_order_items select{
	border:solid 1px #ccc;
}
</style>


<div id="misc_order_items" style="width:500px; margin:auto; height:auto; min-height:180px; display:none;">
    <form action="" name="add_misc_item" id="add_misc_item" method="post">
	<fieldset>
    	<legend style="display:inline !important;"> <strong>Miscellaneous Order Items</strong> </legend>
    	<table width="100%" cellspacing="10" style="margin-top:10px;">
			<label id="error_msg" style="float:left; width:99%; color:#F00; padding:5px 0; text-align:center; background:#FCF393; border-radius:5px; display:none;"></label>			
            <tr>
                <td width="25%" class="ilabel">Item name</td>
                <td colspan="3" width="75%"><input type="text" name="item_name" id="item_name" style="width:98%;" /></td>
            </tr>
			<tr>
                <td width="25%" class="ilabel">Description</td>
                <td colspan="3" width="75%"><input type="text" name="description" id="description" style="width:98%;" /></td>
            </tr>

            <tr>
                <td width="25%" class="ilabel">UOM</td>
                <td width="25%">
                    <select type="text" name="uom" id="uom" style="width:90%">
	                    <option value="EA">EA</option>
	                    <option value="SF">SF</option>
	                    <option value="LF">LF</option>
	                    <option value="LF">CA</option>
	                    <option value="LF">Bag</option>
                    </select>
                </td>
                <td class="ilabel">Quantity</td>
                <td><input type="text" name="qty" id="qty" style="width:85%"/></td>
            </tr>
            <tr>
                <td width="25%" class="ilabel">Retail $ </td>
                <td width="25%">
                	<input type="text" name="retail_price" id="retail_price" style="width:80%" />

					<input type="hidden" name="material_cost" id="material_cost" value="0" />
                    <input type="hidden" name="labor_cost" id="labor_cost" value="0" />
                </td>
                <td class="ilabel" align="right"></td>
                <td align="right"></td>
            </tr>

            <tr>
                <td class="ilabel">Vendor</td>
                <td>
					<input type="text" name="vendor" id="vendor" style="width:80%" />
                </td>
                <td class="ilabel" align="right"></td>
                <td align="right"></td>

            </tr>
			<tr>
			 <td class="ilabel">Category</td>
                <td>
                   <select name="category" id="category">
				   <option value="">Select</option>
				   <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['category_query_result']->value, 'value', false, 'key');
$_smarty_tpl->tpl_vars['value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->do_else = false;
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
</option>
					<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<option value="Others">Others</option>
				   </select>
                </td>
			</tr>
			<tr>
                <td class="ilabel">Approved by</td>
                <td colspan="2"><input type="text" name="approved_by" id="approved_by" style="width:98%;" /></td>
            </tr>
            <tr>
                <td colspan="4">
                <div style="border-bottom:dashed 1px #ccc; width:100%; height:15px; margin-bottom:10px;">&nbsp;</div>
				<input type="hidden" name="misc_orderid" id="misc_orderid" value="<?php echo $_smarty_tpl->tpl_vars['order_result']->value['job_order_id'];?>
">
				<input type='hidden' name='misc_process_id' id='misc_process_id' value='' />
				<input type='hidden' name='misc_by_ins' id='misc_by_ins' value='' />
                <input type="button" class="adminbtn delete_misc" value="Delete Item" style="float:left; display:none;"/>
                <input type="button" class="adminbtn" name="misc_submit" id="misc_submit" value="add item" style="float:right;" />
                <input type="reset" class="adminbtn ipclear" value="clear" style="float:right; margin-right:10px;" />
                </td>
            </tr>
        </table>
	</fieldset>
    </form>
</div>

<div id="sendmailcopy" style="display:none;">
<form name="sendmailcopy_frm" id="sendmailcopy_frm" method="post" enctype="multipart/form-data">
    <center>
	<fieldset style="width:575px;">
    	<legend style="display:inline !important;"><strong><?php echo $_smarty_tpl->tpl_vars['mailcopydata']->value['title'];?>
</strong></legend>
    	<table width="100%">
            <tr>
                <td><strong>Subject</strong></td>
                <td>
                    <input type="text" name="copymail_subject" id="copymail_subject" size="70" value="<?php echo $_smarty_tpl->tpl_vars['mailcopydata']->value['subject'];?>
" readonly class="empty_mail_field" style="border:0px;" />
                </td>
            </tr>
            <tr>
                <td><strong>To</strong></td>
                <td>
                    <input type="text" name="copymail_to" id="copymail_to" size="70" value="" class="empty_mail_field" /> 
                </td>
            </tr>
            <tr>
                <td><strong>CC</strong></td>
                <td>
                    <input type="text" class="empty_mail_field" name="copymail_cc" value="<?php echo $_smarty_tpl->tpl_vars['branch_admin']->value;?>
" id="copymail_cc" size="70" />
                </td>
            </tr>
			<tr>
                <td><strong>Message</strong></td>
                <td>
                    <textarea class="empty_mail_field" name="copymail_message" id="copymail_message" size="70" style="height: 70px;" /></textarea>
                </td>
            </tr>

            <tr style="display: none;">
                <td><b>Include Price</b></td>
                <td align="left">
                    <input type="radio" name="with_price" value="1" checked /> Yes
                    <input type="radio" name="with_price" value="0" /> No
                </td>
            </tr>
            <tr style="display: none;">
                <td align="right">
                	<input type="checkbox" name="include_repnotes" id="include_repnotes" value="material_repnotes">
					<textarea name="order_repnotes" id="order_repnotes" style="display:none;"><?php echo $_smarty_tpl->tpl_vars['order_result']->value['comments'];?>
</textarea>
                </td>
                <td align="left">Include Sales-Rep entered notes.</td>
            </tr>
			<tr style="display: none;">
                <td><strong>Attachments</strong></td>
                <td class="td_add_file" style="height:10px;" align="left">
                	<input type="file" name="attach_file_name[]" />
					<input type="button" style="background:url(../images/actions/add.png); height:24px; width:24px; border:none;" class="addtype_file" />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
	                <div style="border-bottom:dashed 1px #ccc; width:100%; height:15px; margin-bottom:10px;">&nbsp;</div>
                    <input type="button" name="cancel_ordercopy" id="cancel_ordercopy" class="adminbtn" value="clear" />
                    <input type="submit" name="send_ordercopy" id="send_ordercopy" class="adminbtn" value="Send" />
                    <input type="hidden" name="sending_vendor" id="sending_vendor" value="" />
                    <input type="hidden" name="action" value="send_ordercopy" />
                </td>
            </tr>
        </table>
	</fieldset>
	</center>
</form>
</div>

<div id="orderdesign" style="display:none;">
	<?php if ($_smarty_tpl->tpl_vars['mailcopydata']->value['has_design'] != '') {?>
		<img style="width: 100%;" src="<?php echo $_smarty_tpl->tpl_vars['mailcopydata']->value['has_design'];?>
" alt="Your Design"/>
	<?php } else { ?>
		<img style="width: 100%;" src="../images/not_available.jpg" alt="No Design Available"/>
	<?php }?>
</div>

<div id="exportDataOption" style="display:none;">
	<fieldset style="width:350px;">
    	<legend style="display:inline !important;"><strong>Export Order to File</strong></legend>
        <table style="width:100%; margin-top:10px;" cellpadding="10">
            <tr>
                <td><b>Export Type</b></td>
                <td>
                    <input type="radio" name="export_type" value="excel" checked /> EXCEL
                    <input type="radio" name="export_type" value="xml" /> XML
                </td>
            </tr>
            <tr>
                <td><b>Include Price</b></td>
                <td>
                    <input type="radio" name="with_price" value="1" /> Yes
                    <input type="radio" name="with_price" value="0" checked /> No
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                   	<input type="hidden" name="order_id" value="<?php echo $_GET['id'];?>
" />
                    <input type="hidden" name="action" value="export_data" />
                   	<input type="hidden" name="mfg" id="mfg" />
                    <input type="button"  id="do_export" value="export" class="adminbtn" />
                </td>
            </tr>
        </table>
    </fieldset>
</div><?php }
}
