<ul id="status_menu">
    <li {if $ordstatus_overview.sent_material=="" && $ordstatus_overview.status!="revision"} class="base_st_current" {else}  class="base_st_passed" {/if}>
    	<b>Created</b>{$ordstatus_overview.created_at|date_format}</li>
{if $revision_result!=""}
    <li id="revision_history" class="link_cursor {if $ordstatus_overview.status=='revision'}base_st_current{else}base_st_passed{/if}">
    <b>Revision</b>click to expand
    <div class="active_base_status"></div>
    </li>
{/if}
    <li id="material_order_status" class="link_cursor {if $ordstatus_overview.sent_material!='' && $ordstatus_overview.sent_installer==""}base_st_current{elseif $ordstatus_overview.sent_material!=''}base_st_passed{/if}">
    <b>Material Order</b>click to expand
    <div class="active_base_status"></div>
    </li>
    <li id="install_order_status" class="link_cursor {if $ordstatus_overview.sent_installer!="" && $ordstatus_overview.completed_at==""}base_st_current{elseif $ordstatus_overview.sent_installer!=""}base_st_passed{/if}">
    <b>Install Order</b>click to expand
    <div class="active_base_status"></div>
    </li>
{if $ordstatus_overview.completed_at|date_format!=""}
    <li class="base_st_current"><b>Completed</b>{$ordstatus_overview.completed_at|date_format}</li>
{else}
    <li><b>Completed</b>-</li>
{/if}
</ul>

<div id="material_order_status_show" style="width: 80%; margin:0 10% 30px; display:none;">
<table align="center" style="width:100%;" cellspacing="10">
    <tr style="height:40px; vertical-align:top; color:#777;">
	    <th style="width:20%; text-align:center;">Vendor \ Status</th>
	    <th style="width:16%; text-align:center;">Order Sent</th>
		{$matstate_count = 0}
        {foreach $statusnames_res as $status_names}
			{if $status_names.br_group=="material" && $status_names.type|strpos:"admin"|strlen}
{*$status_names.name} - {$status_names.type} = {$status_names.type|strpos:"admin" *}
			{$matstate_count = $matstate_count+1}
            <th style="width:16%; text-align:center;">{$status_names.name}</th>
			{/if}
        {/foreach}
		<th style="width:5%;">&nbsp;</th>
    </tr>

{if $material_status_count>0}
    {foreach $material_status_result as $value}
{if $value.manufacturer!="Installer"}
        <tr title="{$value.manufacturer}" data-alias="{$value.manufacturer_alias}" style="height:40px;">
            {if $value.shipping_info!=""}
                <input type="hidden" name="shipping_info" class="shipping_info" value="{$value.shipping_info|rawurlencode}">
                <input type="hidden" name="delivery_info" class="delivery_info" value="{$value.delivery_info|rawurlencode}">
            {/if}

            <td align="center">{$value.manufacturer}</td>
			{$vendor_arr = $value.sent_material|trim:","}
			{$vendor_arr = ","|explode:$vendor_arr}
			{$mat_sent_on=""}
            {foreach $vendor_arr as $val1}
				{$vendor_arr = "_"|explode:$val1}
	            {if isset($vendor_arr[1])}
                    {$mat_sent_on[$vendor_arr[0]] = $vendor_arr[1]}
	            {else}
                    {$mat_sent_on[$vendor_arr[0]] = ""}
                {/if}
            {/foreach}

			{if !isset($mat_sent_on[$value.manufacturer_alias])}
                <td class="mo_no-st" align="center">-</td>
            {else if $value.{$value.manufacturer_alias} == ""}
                <td class="mo_st_current" align="center">{$mat_sent_on[$value.manufacturer_alias]|date_format}</td>
            {else}
                <td class="mo_st_passed" align="center">{$mat_sent_on[$value.manufacturer_alias]|date_format}</td>
            {/if}

			{assign var=mat_status value=","|explode:{$value.{$value.manufacturer_alias}}}
            {for $key=0; $key < $matstate_count; $key++}
				{$scheduled_info = ""}

	            {if isset($mat_status.$key) && $mat_status.$key!=""}
	                {assign var=status_split value="_"|explode:$mat_status.$key}                
					<td class="{if $mat_status.$key==end($mat_status)} mo_st_current {else} mo_st_passed {/if} {if $status_split[0]=="Shipped"} shipment_details{else if $status_split[0]=="Delivered"} delivery_details{/if}" align="center">{$status_split[1]|date_format}</td>
            	{else}
					{if end($mat_status)|strpos:"Shipped"|strlen}
		        	    <td class="mo_no-st update_matreceived" align="center">click to update</td>
					{else}
		        	    <td class="mo_no-st" align="center">-</td>
                    {/if}
                {/if}
                
            {/for}

            {if isset($ven_order_schedules.{$value.manufacturer_alias})}
                <td title="Dispatch: {$ven_order_schedules.{$value.manufacturer_alias}.0|date_format}, Shipping: {$ven_order_schedules.{$value.manufacturer_alias}.1|date_format}"><span class="scheduled_info active">&nbsp;</span></td>
            {else}
                <td title="No Schedules !"><span class="scheduled_info">&nbsp;</span></td>
            {/if}
        </tr>
{/if}
    {/foreach}
{else}
        <tr style="height:40px;">
            <td align="center" style="color:#F88421;" colspan="{count($statusnames_res)+2}">No material order generated yet !</td>
        </tr>
{/if}
</table>
</div>
