{if $show_price==0}
	{$col_width=[5, 15, 60, 10, 10]}
{else}
	{$col_width=[5, 15, 50, 9, 9, 12]}
{/if}
<table width="100%">
    {$ship_to_info = $MOInfoList->GetOrdHeaderData($order_result, $products_result.0, "ship_to")}
    <tr bgcolor="#F0F0F0">
        <td colspan="3"><b>Order Details</b></td>
		<td colspan="{3+$show_price}"><b>Ship To Address</b></td>
	</tr>
    <tr>
        <td colspan="2"><b>ORDER ID</b></td>
        <td><b>{$order_result.order_id}</b></td>
        <td><b>Company</b></td>
        <td colspan="{2+$show_price}">{$ship_to_info.company}</td>
	</tr>
    <tr>
        <td colspan="2">Customer Name</td>
        <td>{$order_result.customer_name}</td>
        <td><b>Name</b></td>
        <td colspan="{2+$show_price}">{$ship_to_info.name}</td>
	</tr>
    <tr>
        <td colspan="2">JOB ID</td>
        <td>{$order_result.jobid} {if $order_result.jobid==""} - {/if}</td>
        <td><b>Email</b></td>
        <td colspan="{2+$show_price}">{$ship_to_info.email}</td>
	</tr>
    <tr>
        <td colspan="2">Branch</td>
        <td>{$order_result.branch} - {$order_result.branch_code}
	        {if $order_result.branch_num != 0} &nbsp; [ <b>#</b>: {$order_result.branch_num} ]{/if}
        </td>
        <td><b>Phone</b></td>
        <td colspan="{2+$show_price}">{$ship_to_info.phone}</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
        <td><b>Address</b></td>
        <td colspan="{2+$show_price}">{$ship_to_info.address}</td>
    </tr>

	<tr bgcolor="#F0F0F0">
		<td align="center" width="8%"><b>#</b></td>
		<td width="17%"><b>Model #</b></td>
		<td colspan="2" width="45%"><b>Description</b></td>
		<td align="center" width="9%"><b>UOM</b></td>
		<td align="center" width="9%"><b>Qty</b></td>
{if $show_price==1}
		<td align="center" width="12%"><b>Product Cost</b></td>
{/if}
	</tr>
    {$prd_material_total = 0}

	{$uom_except_arr = ["SF"=>"squarefeet", "LF"=>"linealfeet", "EA"=>""]}

	{$count_check = 1}
	{$item_no = 1}

{if !empty($products_result)}
    {foreach from=$products_result key=key item=value}
        {assign var="pricing_model" value=trim("`$value.remode_orderitem_pricingmodel`")}

        {assign var="lineitem_rows" value=1}
        {if isset($order_subitems_count[trim($pricing_model)])}
            {assign var="lineitem_rows" value=$order_subitems_count[trim($pricing_model)]+1}
        {/if}

		<tr>
            <td align="center" rowspan="{$lineitem_rows}">{$item_no++}</td>
			<td rowspan="{$lineitem_rows}">{$pricing_model} {if $pricing_model|trim==""}-{/if}</td>
			<td {if $lineitem_rows > 1} colspan="{4+$show_price}" {else} colspan="2" {/if}>
				{if $lineitem_rows > 1}
					{$value.description}
				{else}
					<b>{$value.description}</b>
                {/if}

                {if $value.prd_options!=""}<br/>{$value.prd_options}{/if}
                {if $value.comments!=""}<br/>Comments: {$value.comments}{/if}

				{if $lineitem_rows>1}
                    <span style="float:right; font-weight:normal;">[ UOM:{$value.uom}, QTY: {$value.pgen_matqty} ]</span>
                {/if}
            </td>
            {if $lineitem_rows==1}
                <td align="center"><b>{$value.uom}</b></td>
                <td align="center"><b>{$value.pgen_matqty}</b></td>
{if $show_price==1}
                <td align="right"><b>$ {number_format($value.pgen_matqty*$value.productcost,2)}</b> &nbsp;</td>
				{$prd_material_total = $prd_material_total+$value.pgen_matqty*$value.productcost}
{/if}
            {/if}
       </tr>

        {foreach from=$order_subquery key=key2 item=value2}
			{if trim($value.remode_orderitem_pricingmodel) == trim($value2.pricingmodel)}

        	{$current_item_string = "`$value.orderitems_id`_`$value2.id`"}
			{if (isset($ordersubitem_id) && in_array($current_item_string, $ordersubitem_id)) || !isset($ordersubitem_id)}

                {$subMatMass = $MOInfoList->SubMatMass($value, $value2)}
				{$submat_uom =  $subMatMass.uom}
                {$subitem_qty = $subMatMass.qty}
                {$subitem_cost = $subMatMass.cost}
    
                <tr>    
                {if $subitem_qty>0}
                    <td colspan="2"><b>{trim($value2.Item)}</b></td>
                    <td align="center"><b>{$submat_uom}</b></td>
                    <td align="center"><b>{$subitem_qty}</b></td>
{if $show_price==1}
                    <td align="right"><b>$ {number_format($subitem_cost, 2)}</b> &nbsp;</td>
                    {$prd_material_total = $prd_material_total+$subMatMass.cost}
{/if}
                {/if}
                </tr>       
			{/if}
			{/if}
		{/foreach}
	{/foreach}
{/if}
	
    {$mitem_ct=0}
    {if $misc_count > 0}
        {foreach from=$misc_result key=key1 item=valuem}
            {if $mitem_ct==0}
            <tr bgcolor="#F0F0F0">
                <td colspan="{6+$show_price}"><b>Miscellaneous items</b></td>
            </tr>
            {/if}
            <tr>
                <td align="center">{1+$mitem_ct++}</td>
                <td>{$valuem.item_name}</td>
                <td colspan="2">{$valuem.category} - {$valuem.description}</td>
                <td align="center">{$valuem.uom}</td>
                <td align="center">{$valuem.qty}</td>
{if $show_price==1}
                <td align="right">${number_format($valuem.qty*$valuem.material,2)} &nbsp;</td>
                {$prd_material_total = $prd_material_total+$valuem.qty*$valuem.material}
{/if}
            </tr>
        {/foreach}
    {/if}
{if $show_price==1}
    <tr bgcolor="#f9f9f9">
        <td colspan="{5+$show_price}" align="right"><b>Total Cost &nbsp;:</b></td>
        <td align="right"><b>$ {number_format($prd_material_total, 2)}</b> &nbsp;</td>
    </tr>
{/if}
</table>
