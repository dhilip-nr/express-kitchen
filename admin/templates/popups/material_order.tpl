<div class="mail_content" style="display:none;"></div>
<div style="display:none;">
<div id="mail_container">
<form name="material_order_mailfrm" id="material_order_mailfrm" class="material_order_mailfrm" action="#" method="post" enctype="multipart/form-data">
<div class="job_content" id="job_content_mail" style="display:none; width:600px;">
<!-- job detail will be filled here -->
</div>
<div class="html_content" id="html_content_mail" style="display:none; width:620px;">
<!-- material sheet will be filled here -->
</div>
<center>
	<fieldset style="width:575px;">
	
    	<legend style="display:inline !important;"><strong>Material Order Mail</strong></legend>
    	<table width="100%">
            <tr>
                <td>
                    <strong>Subject</strong>
					<textarea class="xml_content" name="xml_content" id="xml_content_mail_attachment" style="display:none !important;"></textarea>
					<input type="text" id="xml_filename" name="xml_filename" value="" style="display:none !important;" />
                </td>
                <td>
                    <input type="text" name="mail_subject" id="mail_subject" size="70" data-content="# {$order_result.order_id} - Job ID #{$order_result.jobid} - Customer Name : {$order_result.customer_name} - ReMAP Configurator" value="" readonly class="empty_mail_field" style="border:0px;" />
                </td>
            </tr>
            <tr>
                <td>
                    <strong>To</strong>
                </td>
                <td><input type="text" name="mail_to" id="mail_to" size="70" value="" class="empty_mail_field" style="border:0px;" /></td>
            </tr>
            <tr>
                <td>
                    <strong>CC</strong>
                </td>
                <td>
                    <input type="text" class="empty_mail_field" name="mail_cc" id="mail_cc" value="" size="70" />
					<input type="hidden"  name="original_mail_cc" id="original_mail_cc" value="{$branch_admin}" />
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
			<tr id="reason_content_wrapper" style="display:none;">
                <td>
                    <strong>Reason</strong> <span style="color:#F00;">*</span>
                </td>
                <td>
					{$ro_reason_arr = ["Item missing", "Item Damaged during shipping", "Damaged Item received", "Wrong item shipped", "Wrong Item ordered", "Installer Damaged the item"]}
                    <select class="empty_mail_field" name="reason_content" id="reason_content" style="width:100%;">
                        <option value="">- select reason -</option>
                        {foreach $ro_reason_arr as $ro_reason}
	                        <option value="{$ro_reason}">{$ro_reason}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
			<tr>
                <td>
                    <strong>Message</strong>
                </td>
                <td align="left">
					<textarea class="empty_mail_field" name="mail_secondary_content" id="mail_secondary_content" size="70" style="height:70px;" />{$ship_to}</textarea>
                </td>
            </tr>
			<tr>
                <td valign="top">
                    <strong>Attachments</strong>
                </td>
                <td class="td_add_file" style="height:10px;" align="left">
                    <input type="file" name="attach_file_name[]" />
					<input type="button" style="background:url(../images/actions/add.png); height:24px; width:24px; border:none;" class="addtype_file" />
                </td>
            </tr>
            <tr>
                <td align="right">
                	<input type="checkbox" name="include_repnotes" id="include_repnotes" value="material_repnotes">
					<textarea name="order_repnotes" id="order_repnotes" style="display:none;">{$order_result.comments}</textarea>
                </td>
                <td align="left">Include Sales-Rep entered notes.</td>
            </tr>
            <tr>
                <td align="right"><input type="checkbox" name="materialxml" id="materialxml" value="material_xmlcontent"></td>
                <td align="left">Send Xml content along with the mail.</td>
            </tr>
            <tr>
                <td colspan="2">
					<br /><br /><strong style="color:#FA5902; font-size:12px;">Note: Material Order will be sent as the content.</strong>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">

                <div style="border-bottom:dashed 1px #ccc; width:100%; height:15px; margin-bottom:10px;">&nbsp;</div>
                	<input type="hidden" name="ajax_mode" id="ajax_mode" value="material_lookup_mail" />
                	<input type="hidden" name="job_order_id" id="job_order_id" value="{$order_result.job_order_id}" />
					<input type="button" name="preview" id="preview" class="adminbtn" value="preview" />
                    <input type="submit" name="mail_option" id="mail_option" class="mail_option adminbtn" value="Send" />
                    <input type="button" name="cancel" id="mail_cancel" class="adminbtn" value="cancel" />
                </td>
            </tr>
			<tr>
			<td colspan="2"><textarea name="main_mail_content" id="main_mail_content" style="display:none;" ></textarea><td>
			</tr>
			<tr>
				<td>
				<input type="hidden" name="sent_material" id="sent_material" value="{$order_result.sent_material}" />
				<input type="hidden" name="sent_installer" id="sent_installer" value="{$order_result.sent_installer}" />
				<input type="hidden" name="mail_job_id" id="mail_job_id" />
				<input type="hidden" name="sending_vendor" id="sending_vendor" />
				</td>
			</tr>
        </table>
		
    </fieldset>
    </center>
</form>
</div>
</div>