{include file='menu.tpl'}
<div style="clear:both"></div>

	{if $order_has_dealer && ($smarty.session[$APPSESVAR|cat:"_adminuser"].role eq 'dealer' || $smarty.session[$APPSESVAR|cat:"_adminuser"].role eq 'branchadmin')}
		{include file='order_status/dealer_view.tpl'}
    {else}
    	{include file='order_status/admin_view.tpl'}
	{/if}

<div id="revision_history_show" style="width: 80%; margin:0 10% 30px; display:none;">
<table class="orders_wrapper" style="margin:0 auto;">
	{if $revision_result!=""}
		<tr id="item_title">
			<td width="6%" align="center"><b>#</b></td>
			<td width="64%"><b>Comments</b></td>
			<td width="14%"><b>Posted by</b></td>
			<td width="16%" align="center"><b>Date</b></td>
		</tr>
		
		{foreach from=$revision_result key=key item=value}
		<tr>
			<td align="center">{$key+1}</td>
			<td>{if $value.category != ""}<b>{$value.category}:</b> {/if}{$value.comments}</td>
			<td>{$value.user_name}</td>
			<td align="center">{$value.created_at|date_format:"%b %d, %Y"}</td>
		</tr>
		{/foreach}
	{else}
		<tr id="item_title">
			<td align="center">No revisions for this order</td>
		</tr>
	{/if}
</table>
</div>

<div id="install_order_status_show" style="width: 74%; margin:0 13%; display:none;">
<table align="center" style="width:100%;" cellspacing="10">
    <tr style="height:40px; vertical-align:top; color:#777;">
	    <th style="width:14%; text-align:center;">Order Sent</th>
		{$insstate_count = 0}
        {foreach $ins_statusnames_res as $status_names}
   	        <th style="width:14%; text-align:center;">{$status_names}</th>
        {/foreach}
    </tr>

{if $installer_status_result.sent_installer != ""}
    {* foreach $installer_status_result as $value *}
	{assign var=value value=$installer_status_result}
        <tr style="height:50px;">
			{if $value.sent_installer=="" ||  $value.sent_installer==0 ||  $value.sent_installer==1}
                <td class="io_no-st" align="center">-</td>
            {else if $value.IOS == ""}
	            <td class="io_st_current" align="center">{$value.sent_installer|date_format}</td>
            {else}
	            <td class="io_st_passed" align="center">{$value.sent_installer|date_format}</td>
            {/if}


            {for $key=0; $key < count($ins_statusnames_res); $key++}
            	{if isset($ins_status.$key) && $ins_status.$key != ""}
					{assign var=status_split value="_"|explode:$ins_status.$key}
{if $status_split[0]=="Installation Problem"} {$prob_class = "problem_report"} {else} {$prob_class = ""} {/if}
                    {if $ins_status.$key==end($ins_status)}
                        <td class="io_st_current {$prob_class}" align="center">{$status_split[1]|date_format}</td>
                    {else}
                        <td class="io_st_passed {$prob_class}" align="center">{$status_split[1]|date_format}</td>
                    {/if}
            	{else}
	        	    <td class="io_no-st" align="center">-</td>
                {/if}
            {/for}
        </tr>
    {* /foreach *}
{else}
        <tr style="height:40px;">
            <td align="center" style="color:#F88421;" colspan="{count($statusnames_res)+2}">Install Order not yet generated !</td>
        </tr>
{/if}

</table>
</div>

<div id="shipping_information" style="width:400px; margin:auto; height:auto; min-height:180px; display:none;">
	<fieldset>
    	<legend style="display:inline !important;"><strong>Shipping Information</strong></legend>
    	<table width="100%" cellpadding="8" style="margin-top:10px;" >
            <tr>
                <td width="35%"><strong>Carrier Name</strong></td>
                <td width="65%"><span id="cname" class="ship_info"></span></td>
            </tr>
             <tr>
                <td><strong>Tracking No</strong></td>
                <td><span id="carrier_no" class="ship_info"></span></td>
            </tr>
            <tr>
                <td><strong>Contact No</strong></td>
                <td><span id="pname" class="ship_info"></span></td>
            </tr>
            <tr>
                <td><strong>Shipping Cost</strong></td>
                <td><span id="shipping_cost" class="ship_info"></span></td>
            </tr>
            <tr>
                <td><strong>Date</strong></td>
                <td><span id="date" class="ship_info"></span></td>
            </tr>
            <tr>
                <td><strong>ETA</strong></td>
                <td><span id="eta_date" class="ship_info"></span></td>
            </tr>
        </table>
	</fieldset>
</div>

<input type="hidden" name="order_id" id="order_id" value="{$smarty.get.id}" />
    

<div id="get_receipt_info" style="width:500px; margin:auto; height:auto; min-height:180px; display:none;">
	<fieldset>
    	<legend style="display:inline !important;"><strong>Delivery Information</strong></legend>
		<form method="post">
    	<table width="100%" style="margin-top:10px;">
            <tr>
                <td width="25%"><strong>Received By</strong></td>
                <td width="75%"><input type="text" name="get_rec_name" id="get_rec_name" style="width:98%;" value="{$user_fullname}" /></td>
            </tr>
             <tr>
                <td><strong>Received On</strong></td>
                <td><input type="text" name="get_rec_date" id="get_rec_date" style="width:98%;" /></td>
            </tr>
            <tr>
                <td><strong>Comments</strong></td>
                <td><input type="text" name="get_rec_comment" id="get_rec_comment" style="width:98%"/></td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                <div style="border-bottom:dashed 1px #ccc; width:100%; height:15px; margin-bottom:10px;">&nbsp;</div>
				<label id="error_msg" style="width:99%; color:#F00; padding:5px 0; margin-right:20px;"></label>
                <input type="hidden" id="processing_manf" value="" />
                <input type="reset" class="adminbtn ipclear" value="clear" />
                <button type="button" class="adminbtn" id="receipt_info_submit">Submit</button>
                </td>
            </tr>
        </table>
        </form>
	</fieldset>
</div>

<div id="delivery_information" style="width:400px; margin:auto; height:auto; min-height:150px; display:none;">
	<fieldset>
    	<legend style="display:inline !important;"><strong>Receipt Information</strong></legend>
	    	<table width="100%" cellpadding="8" style="margin-top:10px;" >
                <tr>
                    <td width="30%"><strong>Received By</strong></td>
                    <td width="70%"><span id="put_rec_person"></span></td>
                </tr>
                <tr>
                    <td width="30%"><strong>Received On</strong></td>
                    <td width="70%"><span id="put_rec_date"></span></td>
                </tr>
                <tr>
                    <td width="30%"><strong>Comments</strong></td>
                    <td width="70%"><span id="put_rec_comment"></span></td>
                </tr>
        </table>
	</fieldset>
</div>


<div id="problem_information" style="width:400px; margin:auto; height:auto; min-height:150px; display:none;">
	<fieldset>
    	<legend style="display:inline !important;"><strong>Installation Problem Description</strong></legend>
	    	<table width="100%" cellpadding="8" style="margin-top:10px;" >
                <tr>
                    <td width="100%" height="80" valign="top">
					{if $installer_status_result[0]['IPD']!=""}
                        {$installation_problem = "_"|explode:$installer_status_result[0]['IPD']}
                        {$installation_problem[0]}
					{/if}
                    </td>
                </tr>
        </table>
	</fieldset>
</div>