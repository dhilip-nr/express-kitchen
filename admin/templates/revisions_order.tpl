{include file='menu.tpl'}

<div style="clear:both"></div>
<div style="clear:both"></div>
<table class="orders_wrapper" cellspacing="0" id="material_order_table" name="material_order_table">
		<tr>
			<td colspan="6">
				ORDER ID : <b>{$br_order_id}</b>
			</td>
		</tr>
		{if $revision_result!=""}
		<tr id="item_title">
			<td width="6%" align="center">
				<b>#</b>
			</td>
			<td width="64%">
				<b>Comments</b>
			</td>
			<td width="14%">
				<b>Posted by</b>
			</td>
			<td width="16%" align="center">
				<b>Date</b>
			</td>
		</tr>
		
		{$i=1}
		{foreach from=$revision_result key=key item=value}

		<tr>
			<td align="center">
				{$i++}
			</td>
			<td>
	            {if $value.category != ""}<b>{$value.category}:</b> {/if}
				{$value.comments}
			</td>
			<td>
				{$value.user_name}
			</td>
			<td>
				{$value.created_at}
			</td>
		</tr>
		
		{/foreach}
		{else}
		<tr id="item_title">
			<td align="center">No revisions for this order</td>
		</tr>
		{/if}
</table>