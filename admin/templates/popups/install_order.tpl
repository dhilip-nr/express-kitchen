<div class="mail_content" style="display:none;"></div>
<div style="display:none;">
<div id="mail_container">
<form name="install_order_mailfrm" id="install_order_mailfrm" class="install_order_mailfrm" action="#" method="post" enctype="multipart/form-data">
<div class="job_content" id="job_content_mail" style="display:none; width:600px;">
<!-- job detail will be filled here -->
</div>
<div class="html_content" id="html_content_mail" style="display:none; width:620px;"><!-- material sheet will be filled here --></div>
    <center>
	<fieldset style="width:575px;">
	
    	<legend style="display:inline !important;"><strong>Install Order Mail</strong></legend>
    	<table width="100%">
            <tr>
                <td>
                    <strong>Subject</strong>
					<textarea class="xml_content" name="xml_content" id="xml_content_mail_attachment" style="display:none !important;"></textarea>
					<input type="text" id="xml_filename" name="xml_filename" value="" style="display:none !important;" />
                </td>
                <td>
                    <input type="text" name="mail_subject" id="mail_subject" size="70" value="Install Order # {$order_result['order_id']} - ReMAP Configurator" readonly class="empty_mail_field" style="border:0px;" /> <!-- In Live use: HDC@msistone.com -->
                </td>
            </tr>
            <tr>
                <td>
                    <strong>To</strong>
                </td>
                <td>
                    <input type="text" name="mail_to" id="mail_to" size="70" value="{$order_result.installer_email}" readonly class="empty_mail_field" style="border:0px;" /> 
                </td>
            </tr>
            <tr>
                <td>
                    <strong>CC</strong>
                </td>
                <td>
                    <input type="text" class="empty_mail_field" name="mail_cc" value="{$branch_admin}" id="mail_cc" size="70" />
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
                    <strong>Message</strong>
                </td>
                <td align="left">
                    <textarea class="empty_mail_field" name="mail_secondary_content" id="mail_secondary_content" size="70" style="height: 70px;" /></textarea>
                </td>
            </tr>
			<tr>
                <td>
                    <strong>Attachments</strong>
                </td>
                <td class="td_add_file" style="height:10px;" align="left">
                        <input type="file" name="attach_file_name[]" />
						<input type="button" style="background:url(../images/actions/add.png); height:24px; width:24px; border:none;" class="addtype_file" />
						<input type="hidden" name="order_attachments" value="{$order_result['order_attachments']}" />
                </td>
            </tr>

            <tr>
                <td align="right"><input type="checkbox" name="attach_email" value="customer_order"></td>
                <td align="left">Attach a customer email.</td>
            </tr>

            <tr>
                <td align="right"><input type="checkbox" name="installxml" value="install_xmlcontent" checked="checked" ></td>
                <td align="left">Send Xml content along with the mail.</td>
            </tr>

            <tr>
                <td colspan="2">
					<br /><br /><strong style="color:#FA5902; font-size:12px;">Note: Install Order will be sent as the content.</strong>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                <div style="border-bottom:dashed 1px #ccc; width:100%; height:15px; margin-bottom:10px;">&nbsp;</div>
                	<input type="hidden" name="ajax_mode" id="ajax_mode" value="material_lookup_mail" />
                    <input type="submit" name="mail_option" id="mail_option" class="mail_option adminbtn" value="Send" />
                    <input type="button" name="cancel" id="mail_cancel" class="adminbtn" value="cancel" />
                </td>
            </tr>
			<tr>
			<td colspan="2"><textarea name="main_mail_content" id="main_mail_content" style="display:none;" ></textarea><td>
			</tr>
			<tr><td><input type="hidden" name="mail_job_id" id="mail_job_id" /></td></tr>
        </table>
		
    </fieldset>
    </center>
</form>
</div>
</div>