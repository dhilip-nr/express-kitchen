{*
<ul id="orders_menu" class="print_order">
    <li><a href="sales_order.html?id={$smarty.request.id}" {if $page == "sales_order"} class="active" {/if}>sales Order</a></li>
    <li><a href="material_order.html?id={$smarty.request.id}" {if $page == "material_order"} class="active" {/if}>Material Order</a></li>
    {if array_sum($reorder_mats_count)>0}
      <li>
        <a href="reordered_materials.html?id={$smarty.request.id}" {if $page=="reordered_materials"}class="active"{/if}>Re-Ordered Material</a>
      </li>
    {/if}
    <li><a href="install_order.html?id={$smarty.request.id}" {if $page == "install_order"} class="active" {/if}>Install Order</a></li>
	{if $smarty.session[$APPSESVAR|cat:"_user"].ma==1 || in_array($smarty.session[$APPSESVAR|cat:"_user"].role, ['superadmin', 'divisionalmanager'])}
		<li><a href="margin_details.html?id={$smarty.request.id}" {if $page == "margin_details"} class="active" {/if}>Margin Review</a></li>
	{/if}
	<li><a href="order_status.html?id={$smarty.request.id}" {if $page == "order_status"} class="active" {/if}>Order Status</a></li>
    <li><a href="docs.html?id={$smarty.request.id}" {if $page == "docs"} class="active" {/if}>Documents</a></li>
</ul>
*}