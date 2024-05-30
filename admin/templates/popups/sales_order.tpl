
<div style="width:600px; margin:auto; display:none; height:auto; min-height:180px;" id="revision_comments">
    <form action="" name="revision" id="revision" method="post" enctype="multipart/form-data">
	<fieldset>
	
    	<legend style="display:inline !important;"><strong>Sent back for Revision</strong></legend>

    	<table width="100%">
            <tr>
                <td>
                    <strong>Subject</strong>
                </td>
                <td>
                    <input type="text" name="mail_subject" id="mail_subject" size="70" value="Revise Order # {$order_result.order_id} - ReMAP Configurator" readonly style="border:0px; background:none;" />
                </td>
            </tr>
            <tr>
                <td>
                    <strong>To</strong>
                </td>
                <td>
                	<input type="text" name="mail_to[email]" id="mail_to_email" size="70" style="border:0px; background:none;" value="{$rep_info.email}" /> 
	                <input type="hidden" name="mail_to[name]" id="mail_to_name" value="{$rep_info.fullname}" /> 
                </td>
            </tr>
            <tr>
                <td>
                    <strong>CC</strong>
                </td>
                <td>
                    <input type="text" class="empty_mail_field" name="mail_cc" id="mail_cc" value="{$branch_admin}" size="70" />
                </td>
            </tr>
            <tr>
                <td>
                    <strong>BCC</strong>
                </td>
                <td>
                    <input type="text" class="empty_mail_field" name="mail_bcc" id="mail_bcc" size="70" />
                </td>
            </tr>
			<tr>
                <td>
                    <strong>Comment</strong>
                </td>
                <td>
                    <textarea class="empty_mail_field" name="revord_comments" id="revord_comments" size="70" style="height:70px;" /></textarea>
                </td>
            </tr>
			<tr>
                <td>
                    <strong>Attachment</strong>
                </td>
                <td class="td_add_file" style="height:10px;">
                    <input type="file" name="attach_file_name[]" />
					<input type="button" style="background:url(../images/actions/add.png); height:24px; width:24px; border:none;" class="addtype_file" />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <div style="border-bottom:dashed 1px #ccc; width:100%; height:15px; margin-bottom:10px;">&nbsp;</div>
				<label id="error_msg" style="width:99%; color:#F00; padding:5px 0;"></label>

                <input type="submit" class="adminbtn" value="submit" style="float:right" />
                <input type="reset" class="adminbtn ipclear" value="clear" style="float:right" />
        
                <input type="hidden" name="rep_name" id="rep_name" value="{$order_result.rep_name}" />
                <input type="hidden" name="returnby" id="returnby" value="{$smarty.session[$APPSESVAR|cat:'_adminuser']['un']}" />
                <input type="hidden" name="sys_order_id" id="sys_order_id" value="{$order_result.job_order_id}" />
                <input type="hidden" name="action" id="action" value="Revise Order" />
                </td>
            </tr>
        </table>
	</fieldset>
    </form>
</div>

<div style="width:600px; margin:auto; display:none; height:auto; min-height:180px;" id="cancelorder_modal">
    <form action="" name="cancel_order" id="cancel_order" method="post" enctype="multipart/form-data">
	<fieldset>
	
    	<legend style="display:inline !important;"><strong>Order Cancellation</strong></legend>

    	<table width="100%">
            <tr>
                <td>
                    <strong>Subject</strong>
                </td>
                <td>
                    <input type="text" name="mail_subject" id="mail_subject" size="70" value="Cancel Order # {$order_result.order_id} - ReMAP Configurator" readonly style="border:0px; background:none;" />
                </td>
            </tr>
            <tr>
                <td>
                    <strong>To</strong>
                </td>
                <td>
                	<input type="text" name="mail_to[email]" id="mail_to_email" size="70" style="border:0px; background:none;" value="{$rep_info.email}" /> 
	                <input type="hidden" name="mail_to[name]" id="mail_to_name" value="{$rep_info.fullname}" /> 
                </td>
            </tr>
            <tr>
                <td>
                    <strong>CC</strong>
                </td>
                <td>
                    <input type="text" class="empty_mail_field" name="mail_cc" id="mail_cc" value="{$branch_admin}" size="70" />
                </td>
            </tr>
            <tr>
                <td>
                    <strong>BCC</strong>
                </td>
                <td>
                    <input type="text" class="empty_mail_field" name="mail_bcc" id="mail_bcc" size="70" />
                </td>
            </tr>
			<tr>
                <td>
                    <strong>Comment</strong>
                </td>
                <td>
                    <textarea class="empty_mail_field" name="cancelord_comments" id="cancelord_comments" size="70" style="height:70px;" /></textarea>
                </td>
            </tr>
			<tr>
                <td>
                    <strong>Attachment</strong>
                </td>
                <td class="td_add_file" style="height:10px;">
                	<input type="file" name="attach_file_name[]" />
					<input type="button" style="background:url(../images/actions/add.png); height:24px; width:24px; border:none;" class="addtype_file" />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <div style="border-bottom:dashed 1px #ccc; width:100%; height:15px; margin-bottom:10px;">&nbsp;</div>
				<label id="error_msg" style="width:99%; color:#F00; padding:5px 0;"></label>

                <input type="submit" class="adminbtn" value="submit" style="float:right" />
                <input type="reset" class="adminbtn ipclear" value="clear" style="float:right" />
        
                <input type="hidden" name="rep_name" id="rep_name" value="{$order_result.rep_name}" />
                <input type="hidden" name="returnby" id="returnby" value="{$smarty.session[$APPSESVAR|cat:'_adminuser']['un']}" />
                <input type="hidden" name="sys_order_id" id="sys_order_id" value="{$order_result.job_order_id}" />
                <input type="hidden" name="action" id="action" value="Cancel Order" />
                </td>
            </tr>
        </table>
	</fieldset>
    </form>
</div>
