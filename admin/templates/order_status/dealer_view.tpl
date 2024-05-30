<ul id="status_menu">
    <li {if $ordstatus_overview.sent_material=="" && $ordstatus_overview.status!="revision"} class="base_st_current" {else}  class="base_st_passed" {/if}>
    	<b>Created</b>{$ordstatus_overview.created_at|date_format}</li>
{if $revision_result!=""}
    <li id="revision_history" class="link_cursor {if $ordstatus_overview.status=='revision'}base_st_current{else}base_st_passed{/if}">
    <b>Revision</b>click to expand
    <div class="active_base_status"></div>
    </li>
{/if}
    <li id="material_order_status" class="link_cursor {if $ordstatus_overview.dealer_mat_status|date_format!='' && $ordstatus_overview.sent_installer==""}base_st_current{elseif $ordstatus_overview.sent_material!=''}base_st_passed{/if}">
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
	    <th style="width:12%; text-align:center;"></th>
	    <th style="width:16%; text-align:center;">Order Sent</th>
		{$matstate_count = 0}
        {foreach $statusnames_res as $status_names}
			{if $status_names.br_group=="material" && $status_names.type|strpos:"admin"|strlen}
{*$status_names.name} - {$status_names.type} = {$status_names.type|strpos:"admin" *}
			{$matstate_count = $matstate_count+1}
            <th style="width:16%; text-align:center;">{$status_names.name}</th>
			{/if}
        {/foreach}
		<th style="width:14%;">&nbsp;</th>
    </tr>

<tr style="height:40px;">
	{$material_dealer_result = $material_status_result.0}
    <td align="center"></td>
    {$vendor_arr = $mat_order_status_result.sent_material|trim:","}
    {$vendor_arr = ","|explode:$vendor_arr}

    {if $ordstatus_overview.dealer_mat_status!=""}
        {$dealer_mostatus = explode("_", $ordstatus_overview.dealer_mat_status)}
        <td class="mo_st_passed" align="center">{$dealer_mostatus.1|date_format}</td>
    {else}
        <td class="mo_no-st" align="center">-</td>
    {/if}

    {assign var=mat_status value=","|explode:{$mat_order_status_result.DMS}}
    {for $key=0; $key < $matstate_count; $key++}
        {if isset($mat_status.$key) && $mat_status.$key!=""}
            <td class="{if $mat_status.$key==end($mat_status)} mo_st_current {else} mo_st_passed {/if}" align="center">{$mat_status.$key|date_format}</td>
        {else}
            <td class="mo_no-st" align="center">-</td>
        {/if}
    {/for}

    <td>&nbsp;</td>
</tr>
</table>
</div>
