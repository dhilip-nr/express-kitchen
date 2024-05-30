{include file='menu.tpl'}
<div style="clear:both"></div>

{if $order_result.order_status=="wtg-approval"}
    <div id="ins_notification" style="background: #fbf4b5; border: 1px dashed #cfcfcf; border-radius: 5px; line-height: 50px; margin: 20px auto; max-width: 1000px; text-align: center; width: 96%; color:#666; font-weight:bold;">The changes made by installer are waiting for an approval.</div>
{else if $order_result.order_status=="revision"}
    <div id="ins_notification" style="background: #fbf4b5; border: 1px dashed #cfcfcf; border-radius: 5px; line-height: 50px; margin: 20px auto; max-width: 1000px; text-align: center; width: 96%; color:#666; font-weight:bold;">This order is in REVISION state.</div>
{else if $order_result.order_status=="canceled"}
    <div id="ins_notification" style="background: #FB8763; border: 1px dashed #cfcfcf; border-radius: 5px; line-height: 50px; margin: 20px auto; max-width: 1000px; text-align: center; width: 96%; color:#fff; font-weight:bold;">This order was canceled.</div>
{/if}

{if $order_result.order_status=="material-hold"}
	{$material_hold_arr = explode(",", $order_result.material_hold)}
    <div id="ins_notification" style="background: #FB8763; border: 1px dashed #cfcfcf; border-radius: 5px; line-height: 50px; margin: 20px auto; max-width: 1000px; text-align: center; width: 96%; color:#fff; font-weight:bold;">Materials '{implode("', '", $material_hold_arr)}' on Hold.</div>
{/if}
{if $order_result['has_dealer'] && $mo_admin_view && in_array($smarty.session[$APPSESVAR|cat:"_adminuser"].role, ['admin', 'superadmin']) && $order_result.dealer_mat_status!=""}
	{$dealer_mat_order_status = explode("_", $order_result.dealer_mat_status)}
	{if !isset($dealer_mat_order_status.2)}
    <div id="ins_notification" style="background: #fbf4b5; border: 1px dashed #cfcfcf; border-radius: 5px; line-height: 50px; margin: 20px auto; max-width: 1000px; text-align: center; width: 96%; color:#666; font-weight:bold;">Received material order from Dealer <button class="adminbtn" id="ack_dealer">( click to acknowledge )</button></div>
	{/if}
{/if}

<style>
#change_view_container{
	width:96%; height:40px; overflow:hidden; margin:20px auto -10px; text-align:right; max-width:1000px; font-size:10px; letter-spacing:1px;">
}
#change_view_act {
	background: #F88421 url(../images/actions/apply.png) no-repeat scroll 5px 9px / 28% auto;
    border: 0 none;
    border-radius: 0 5px 5px 0;
    color: #fff;
    float: right;
    font-size: 11px;
    height: 34px;
    padding: 6px 8px;
	padding-left:25px;
	display:none;
	cursor:pointer;
}
#change_view_act.active {
	display:block;
}
</style>
<div id="material_order">
{if $products_result gt 0 || $misc_count > 0}
	{$userinfo = $smarty.session[$APPSESVAR|cat:"_adminuser"]}

	{if $order_result['has_dealer'] && !in_array($userinfo.role, ['dealer','branchadmin'])}
    	<div id="change_view_container" style="width:96%; height:40px; overflow:hidden; margin:20px auto -10px; text-align:right; max-width:1000px; font-size:10px; letter-spacing:1px;">
        <button id="change_view_act">APPLY</button>
        <label style="border:solid 1px #ccc; padding:5px; float:right; border-radius:5px 0 0 5px; padding:3px 10px; background:#eee; height:26px;">
        VIEW &nbsp; <select id="change_view_dd" onChange="$('#change_view_act').toggleClass('active');" style="border:#ccc solid 1px;">
	        <option value="admin" {if isset($userinfo.view) && $userinfo.view=='admin'} selected="selected"{/if} style="padding:3px 5px;">Admin</option>
	        <option value="dealer"{if isset($userinfo.view) && $userinfo.view=='dealer'} selected="selected"{/if} style="padding:3px 5px;">Dealer</option>
        </select>
        </label>
	    </div>
	{/if}

	{if $mo_admin_view}
    	{include file='material_order/admin_view.tpl'}
    {else}
		{include file='material_order/dealer_view.tpl'}
	{/if}

	{if trim($order_result.comments)!='' && trim($order_result.comments)!='<br>'}
        <div class="orders_wrapper" style="border:solid 1px #ccc;">
            <fieldset style="border: 0px none; padding: 15px 10px; text-align:justify;">
                <b align="center" style="display:block; text-align:center; margin-bottom:10px">Instructions / Comments</b>
                {$order_result.comments}
            </fieldset>
        </div>
    {/if}

	<div style="clear:both; height:30px;"></div>

    {$branch_admin_email = array_merge(
	    explode(',', $order_result['branch_admin_email']),
    	explode(',', $order_result['branch_material_order_cc'])
    )}

	{$branch_admin_email = array_unique(array_filter($branch_admin_email))}
    {$branch_admin = implode(',', $branch_admin_email)|strtolower}

	{$is_items_found = "Items Found"}

{else}
	<div align="center" style="padding:50px;">No material items found for this order!</div>
	{$is_items_found = "No Items"}
{/if}
	<input type="hidden" name="is_items_found" id="is_items_found" value="{$is_items_found}">
</div>





{include file="popups/material_order.tpl"}

<!-- Manage Misc order items -->
{include file="popups/misc_item.tpl"}

<!-- Preview Order -->
<div style="width:880px; margin:auto; display:none; height:auto; min-height:180px;" id="material_mail_preview">
	<center><img src="{$root}../scripts/fancybox/fancybox_loading.gif" style="margin-top:10%;" /></center>
</div>