
{php}
    if((isset($_REQUEST["mail_option"])) && ($_REQUEST["mail_option"] == "Send")) {
        global $fn;
        global $db;

		$content = "";
        $mail_subject	= trim( $_POST['mail_subject'] );	
        $mail_to 		= trim( $_POST['mail_to'] );	
        $mail_cc		= trim( $_POST['mail_cc'] );	
        $mail_bcc		= trim( $_POST['mail_bcc'] );
        $mail_secondary_content = trim( $_POST['mail_secondary_content'] );
        $order_attachments = $_POST['select_attach'];

        $attach_file		= array();
    
        foreach($order_attachments as $order_attfiles) {
            if(copy("../uploads/".$order_attfiles, "uploads/".$order_attfiles)) {
                $attach_file[] = $order_attfiles;
            }
        }

        $mail_sent_y = $fn->installer_order_mail($content, $mail_subject, $mail_to, $mail_cc, $mail_bcc, $mail_secondary_content, $attach_file);
       
        if($mail_sent_y) {
            echo '<script language="javascript">';
            echo 'alert("Mail sent successfully");';
            echo 'window.location = root_path+"docs.html?id='.$_REQUEST['id'].'";';
            echo '</script>';
        } else {
            echo '<script language="javascript">';
            echo 'alert("Mail sending fails. Please try again.");';
            echo '</script>';
        }
	}

{/php}
<script type="text/javascript" src="../scripts/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
<script type="text/javascript" src="scripts/jquery.form.js"></script>

<script type="text/javascript">

$(document).ready(function(){

	$(document).on("click", ".preview_file", function(e){
		var imag_name = $(this).attr("href");

		$("div#file_preview_wrap").html("");
		if(imag_name.indexOf(".pdf")!=-1){
			$("div#file_preview_wrap").append('<center><embed src="'+imag_name+'#nameddest=self&page=1&view=FitH,0&zoom=100,0,0" type="application/pdf" width="700" height="600" /></center>');
		} else {
			$("div#file_preview_wrap").append("<center><img src='"+imag_name+"' alt='Uploaded Images' style='max-height:500px; max-width:700px'></center>");
		}

		e.preventDefault();

		$.fancybox({
			href		: "#file_preview_wrap",
			maxWidth	: 700,
			maxHeight	: 600,
		});
	});


	$(".attach_files").change(function(e){
		var val = $(this).val().toLowerCase();
		var regex = new RegExp("(.*?)\.(jpg|jpeg|txt|png|docx|gif|doc|pdf|xml|bmp|ppt|xls)$");
 
		if(!(regex.test(val))) {
			$(this).val('');
			alert('Unsupported file format');
		} 

		if($('.attach_files').size() != "") {
			$("#upload_docs").show();
		}	
	});

	$(document).on("click", "#add_file_row", function() {
	    $("form#attachFiles").append('<div class=\"file_row\"><input type=\"file\" name=\"attachments[]\" class=\"attach_files\" /><input type=\"button\" class=\"remove_file_row\" />');
    });
	
	$(document).on("click", ".remove_file_row", function() {
		$(this).parent("div.file_row").remove();
	});

	
	$("#upload_docs").click(function(){
		$('#attachFiles').ajaxSubmit({
			beforeSubmit: function(){
				$("#loader").show();
			},
			success: function(uploadedfiles) {
				var reload_data = JSON.parse(uploadedfiles);
				var new_list = "";
                $(reload_data).each(function(index, values){
					new_list += '<li><b>'+(index+1)+'</b> <a class="preview_file" href="../uploads/'+values+'">'+values+'</a></li>';
				});
				$("ul#admin_attachments_list").html(new_list);
				$(".attach_files").val("");
				$("#send_docs").show();
				$("#upload_docs").hide();
			},
			complete: function(){
				$("#loader").hide();
			}
		});
	});
    
	$(".send_docs").click(function(e){
		var root = this;
		e.preventDefault();
		$.fancybox({
			maxWidth	: 800,
			maxHeight	: 600,
			href: "#mail_container",
			fitToView	: false,
			width		: '610px',
			height		: 'auto',
		});		
	});

	$("#docs_mail_form").submit(function(){
		mailto = $.trim($(".select_attach").val());
		mail_attachments = $(".select_attach").is(":checked");
		if(mailto=="" || mail_attachments==""){
			$("#error_msg").text("Please fill receiver info and select filles to send");
			return false;
		}
	});

});
	


</script>
<style>
#add_file_row{
	background:url(../images/actions/add.png);
	border:0;
	width:23px;
	height:23px;
}
.remove_file_row{
	background:url(../images/actions/remove.png);
	border:0;
	width:23px;
	height:23px;
}

.attachments_list li{
	display:inline-block; margin:0 15px 10px 0;
}
.attachments_list b{
	color:#F88421; border:solid 1px; padding:2px 6px; border-radius:15px;
}
.attachments_list a{
	text-decoration: underline;
}
.attach_files{
	width:300px; margin:3px 20px;
}
</style>


{include file='menu.tpl'}
<div style="clear:both"></div>

<div id="file_preview_wrap"></div>

<div id="material_order" style="width:75%; margin:auto; max-width:1000px;">
	<table class="orders_wrapper" cellspacing="0" id="docs_table" name="docs_table" style="width:100%;">
      <tr bgcolor="#F0F0F0"><td colspan="2">Admin Uploaded Documents</td></tr>
      <tr>
           <td class="docs" colspan="2">
            <ul style="width:100%; display:block;" class="attachments_list" id="admin_attachments_list">
            {if $attach_docs_result['adm_docs']!=""}			       
                {$attached_files= ","|explode:$attach_docs_result['adm_docs']}
                {foreach $attached_files as $key=>$attachments}
                    {if $attachments|trim!=""}
                    <li><b>{$key+1}</b> <a class="preview_file" href="../uploads/{$attachments}">{$attachments}</a></li>
                    {/if}
                {/foreach}
			{else}
	            <li style="text-align:center; width:100%; margin:15px 0;">No documents uploaded</li>
            {/if}
            </ul>

            </td>
        </tr>
		<tr bgcolor="#F5FBC3">
	        <td width="25%">Choose files to upload </td>
	        <td>
			<form id="attachFiles" method="post" action="ajax_file_upload.php" enctype="multipart/form-data" style="margin: 0;">
                <input type="hidden" name="ordid" id ="ordid" value="{$order_id}" />
                <div class="file_row">
                	<input type="file" name="attachments[]" class="attach_files" /><input type="button" id="add_file_row" />
				</div>
			</form>
			</td>
         </tr>
    </table>
    <div align="center">
        <input type="button" name="upload_docs" id="upload_docs" class="adminbtn" value="Upload" style="display:none" />
		<input type="button" name="send_docs" id="send_docs" class="send_docs adminbtn" value="Send Mail" {if $attach_docs_result['adm_docs']==""} style="display:none;" {/if}/>
    </div>








	<table class="orders_wrapper" cellspacing="0" style="width:52%; float:left;">
      <tr bgcolor="#F0F0F0"><td colspan="2">Install Order Documents</td></tr>
      <tr>
           <td width="30%">Permit:</td>
           <td>{if $attach_docs_result['permit']==""}-
               {else}<a class="preview_file" href="../uploads/{$attach_docs_result['permit']}" style="text-decoration:underline;">{$attach_docs_result['permit']}</a>{/if}</td>
      </tr>
      <tr>
      		<td>Permit Waiver:</td>
            <td>{if $attach_docs_result['permit_waiver']==""}-
                {else}<a class="preview_file" href="../uploads/{$attach_docs_result['permit_waiver']}" style="text-decoration:underline;">{$attach_docs_result['permit_waiver']}</a>{/if}</td>
      </tr>
      <tr>
      		<td>Certificate of Completion:</td>
            <td>{if $attach_docs_result['cmpl_cert']==""}-
                {else}<a class="preview_file" href="../uploads/{$attach_docs_result['cmpl_cert']}" style="text-decoration:underline;">{$attach_docs_result['cmpl_cert']}</a>{/if}</td>
      </tr>
    </table>

	<table class="orders_wrapper" cellspacing="0" style="width:46%; float:right;">
		<tr><td bgcolor="#F0F0F0" width="25%">Comments</td></tr>
        <tr>
            <td width="75%"><div style="width:100%; height:110px; overflow-y:scroll;">{$attach_docs_result['ins_cmt']}</div></td>
        </tr>
    </table>



    

	<table class="orders_wrapper" cellspacing="0" id="docs_table" name="docs_table" style="width:100%;">
      <tr bgcolor="#F0F0F0"><td colspan="2">Installer Uploaded Documents</td></tr>
      <tr>
           <td colspan="2">
            <ul style="width:100%; display:block;" class="attachments_list">
            {if $attach_docs_result['ins_docs']!=""}			       
                {$attached_files= ","|explode:$attach_docs_result['ins_docs']}
                {foreach $attached_files as $key=>$attachments}
                    {if $attachments|trim!=""}
                    <li><b>{$key+1}</b> <a class="preview_file" href="../uploads/{$attachments}">{$attachments}</a></li>
                    {/if}
                {/foreach}
			{else}
	            <li style="text-align:center; width:100%; margin:15px 0;">No documents uploaded</li>
            {/if}
            </ul>
            </td>
        </tr>
    </table>    
</div>
    
   
<div class="mail_content" style="display:none;"></div>
<div style="display:none;">
<div id="mail_container">
<form name="docs_mail_form" id="docs_mail_form" action="#" method="post" enctype="multipart/form-data">
<div class="job_content" id="job_content_mail" style="display:none; width:600px;">
<!-- job detail will be filled here -->
</div>
<div class="html_content" id="html_content_mail" style="display:none; width:620px;"><!-- material sheet will be filled here --></div>
    <center>
	<fieldset style="width:575px;">
	
    	<legend style="display:inline !important;"><strong>Docs Attachment Mail</strong></legend>
    	<table width="100%">
            <tr>
                <td width="30%">
                    <strong>Subject</strong>
                </td>
                <td colspan="2">
                    <input type="text" name="mail_subject" size="70" value="DOCS # {$smarty.const.ORD_PREFIX}{$order_id} - THD Bath Remodeling" readonly style="border:0px; background:none;" /> 
                </td>
            </tr>
            <tr>
                <td>
                    <strong>To</strong>
                </td>
                <td colspan="2">
                    <input type="text" name="mail_to" id="mail_to" style="width:98%;" value="" />
                </td>
            </tr>
            <tr>
                <td>
                    <strong>CC</strong>
                </td>
                <td colspan="2">
                    <input type="text" name="mail_cc" value="" id="mail_cc" style="width:98%;" />
                </td>
            </tr>
            <tr>
                <td>
                    <strong>BCC</strong>
                </td>
                <td colspan="2">
                    <input type="text" name="mail_bcc" id="mail_bcc" style="width:98%;" />
                </td>
            </tr>
			<tr>
                <td>
                    <strong>Message</strong>
                </td>
                <td colspan="2">
                    <textarea name="mail_secondary_content" id="mail_secondary_content" style="width:98%; height:50px;" /></textarea>
                </td>
            </tr>
		{if $attach_docs_result['adm_docs']!=""}
			<tr>
                <td colspan="3">
                    <b>Admin Docs</b><br />

                    {$attached_files= ","|explode:$attach_docs_result['adm_docs']}
                    <ul style="width:100%; display:block;">
                    {foreach $attached_files as $key=>$attachments}
						{if $attachments|trim!=""}
                        <li style="display:inline-block; margin:0 15px 10px 0;"><input type="checkbox" class="select_attach" name="select_attach[]" value="{$attachments}"/><b style="color:#F88421; border:solid 1px; padding:2px 6px; border-radius:15px;">{$key+1}</b> <a class="preview_file" href="../admin/uploads/{$attachments}" target="_blank" style="text-decoration: underline;">{$attachments}</a></li>
						{/if}
                    {/foreach}
                    </ul>
                </td>
            </tr>
            
        {/if}
		{if $attach_docs_result['ins_docs']!=""}
			<tr>
                <td colspan="3">
                    <b>Installer Docs</b><br />

                    {$attached_files= ","|explode:$attach_docs_result['ins_docs']}
                    <ul style="width:100%; display:block;">
                    {foreach $attached_files as $key=>$attachments}
						{if $attachments|trim!=""}
                        <li style="display:inline-block; margin:0 15px 10px 0;"><input type="checkbox" class="select_attach" name="select_attach[]" value="{$attachments}"/><b style="color:#F88421; border:solid 1px; padding:2px 6px; border-radius:15px;">{$key+1}</b> <a class="preview_file" href="../admin/uploads/{$attachments}" target="_blank" style="text-decoration: underline;">{$attachments}</a></li>
						{/if}
                    {/foreach}
                    </ul>
                </td>
            </tr>
            
        {/if}

          <tr>
                <td colspan="2" id="error_msg" style="color:#F00;"></td>
                <td width="30%" align="right">
                    <input type="reset" class="adminbtn" value="clear" />
                	<input type="submit" class="adminbtn" name="mail_option" value="Send" />
                </td>
            </tr>
        </table>
		
    </fieldset>
    </center>
</form>
</div>
</div>
