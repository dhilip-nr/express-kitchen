{if $smarty.session[$APPSESVAR|cat:"_adminuser"].role != 'superadmin'}
    <div style=" width:60%; margin: 80px auto; background:#fbf4b5; border: #cfcfcf solid 1px; text-align:center; padding:10px 20px; border-radius:5px;">
    {if $order_count[0]>0}
        {if $order_count[1]==0}{$order_count[1]="no"}{/if}
		Orders waiting for your review : <b>{$order_count[0]}</b> new order(s).
{*
        Orders waiting for an review : There is/are <b>{$order_count[1]}</b> new order(s) found for today and totally <b>{$order_count[0]}</b> orders were in queue.
        {if $order_count[1]>0}<br/>&nbsp;<br/><b>New Order(s):</b> {$order_count[2]} {/if}
*}
    {else}
        No new orders were found.
    {/if}
    </div>
{else}
    <div style="width:100%; height:100px; margin:0; border:0;"></div>
{/if}

<ul id="orders_menu" style="margin:100px auto 200px;">
{if $smarty.session[$APPSESVAR|cat:"_adminuser"].role=="superadmin" && $manage_catelog}
{*
    <li>
        <a href="{$root}catalog/manage_catalog.html" title="Manage Product Catalog" class="active" style="font-size:14px;">Catalog</a>
	</li>
    <li>
        <a href="{$root}dashboard/" title="Manage Orders" class="active" style="font-size:14px;">Dashboard</a>
	</li>
*}
{else if $smarty.session[$APPSESVAR|cat:"_adminuser"].role=="admin"}
    <li class="dashboard">
        <a href="{$root}dashboard/" title="Manage Orders" class="active" style="font-size:14px;">Dashboard</a>
	</li>
{/if}

    <li class="orders">
        <a href="{$root}view_order.html" title="Manage Orders" class="active">Orders</a>
	</li>
</ul>
