{include file='menu.tpl'}

<div style="clear:both"></div>

{if $order_result.order_status=="wtg-approval"}
    <div id="ins_notification" style="background: #fbf4b5; border: 1px dashed #cfcfcf; border-radius: 5px; line-height: 50px; margin: 20px auto; max-width: 1000px; text-align: center; width: 96%; color:#666; font-weight:bold;">The changes made by installer are waiting for an approval.</div>
{else if $order_result.order_status=="revision"}
    <div id="ins_notification" style="background: #fbf4b5; border: 1px dashed #cfcfcf; border-radius: 5px; line-height: 50px; margin: 20px auto; max-width: 1000px; text-align: center; width: 96%; color:#666; font-weight:bold;">This order is in REVISION state.</div>
{else if $order_result.order_status=="canceled"}
    <div id="ins_notification" style="background: #FB8763; border: 1px dashed #cfcfcf; border-radius: 5px; line-height: 50px; margin: 20px auto; max-width: 1000px; text-align: center; width: 96%; color:#fff; font-weight:bold;">This order was canceled.</div>
{/if}

<div id="material_order">
{$installer_moditems_cnt = 0}

{if $products_result gt 0 || $misc_count > 0}
	{if in_array($smarty.session[$APPSESVAR|cat:"_adminuser"].role, ['branchadmin','dealer'])}
		{include file='install_order/dealer_view.tpl'}
    {else}
    	{include file='install_order/admin_view.tpl'}
	{/if}

    {$branch_admin_email = array_merge(
	    explode(',', $order_result['branch_admin_email']),
    	explode(',', $order_result['branch_installer_email_cc']|cat:','|cat:$order_result['installer_email_cc'])
    )}

	{$branch_admin_email = array_unique(array_filter($branch_admin_email))}
    {$branch_admin = implode(',', $branch_admin_email)|strtolower}

	{if trim($order_result.comments)!='' && trim($order_result.comments)!='<br>'}
        <div class="orders_wrapper" style="border:solid 1px #ccc;">
            <fieldset style="border: 0px none; padding: 15px 10px; text-align:justify;">
                <b align="center" style="display:block; text-align:center; margin-bottom:10px">Instructions / Comments</b>
                {$order_result.comments}
            </fieldset>
        </div>
    {/if}

{else}
	<div align="center" style="padding:50px;">No Items for this order</div>
{/if}

</div>




{include file="popups/install_order.tpl"}

<!-- Manage Misc order items -->
{include file="popups/misc_item.tpl"}

<!-- View Images -->
<div id="file_preview_wrap" style="margin:auto; min-width:200px; min-height:180px; display:none;"></div>
