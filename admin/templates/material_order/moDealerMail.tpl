{if $show_price==0}
	{$col_width=[5, 15, 60, 10, 10]}
{else}
	{$col_width=[5, 15, 50, 9, 9, 12]}
{/if}
<table cellspacing="0" cellpadding="5" style="border:solid 1px #b4b4b4; width:100%; border-collapse:collapse;">
    <tr style="border:solid 1px #ccc;">
        <td colspan="{5+$show_price}">
            ORDER ID : <b>{$order_result.order_id}</b>
            <span style="float:right;">JOB ID : <b>{$order_result.jobid} {if $order_result.jobid==""} - {/if}</b></span>
        </td>
    </tr>

    <tr>
        <td colspan="{5+$show_price}" width="100%" style="border-top:0;">
            <table cellpadding="5" style="width:45%; float:right; border-collapse:collapse; border:solid 1px #ccc; clear:both;">
                <tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
                    <td colspan="2"><b>Ship To Address</b></td>
                </tr>
                {$ship_to_info = $MOInfoList->GetOrdHeaderData($order_result, $products_result.0, "ship_to")}
                <tr style="border:dashed 1px #ccc;">
                    <td width="25%"><b>Company</b></td>
                    <td width="75%">
                        {$ship_to_info.company}
                    </td>
                </tr>
                <tr style="border:dashed 1px #ccc;">
                    <td width="25%"><b>Name</b></td>
                    <td width="75%">
                        {$ship_to_info.name}
                    </td>
                </tr>
                <tr style="border:dashed 1px #ccc;">
                    <td><b>Email</b></td>
                    <td>{$ship_to_info.email}</td>
                </tr>
                <tr style="border:dashed 1px #ccc;">
                    <td><b>Phone</b></td>
                    <td>{$ship_to_info.phone}</td>
                </tr>
                <tr style="border:dashed 1px #ccc;">
                    <td><b>Address</b></td>
                    <td>{$ship_to_info.address}</td>
                </tr>
            </table>

            <table cellpadding="5" style="width:45%; float:left; border-collapse:collapse; border:solid 1px #ccc; margin-bottom:10px;">
                <tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;"><td><b>Customer Name</b></td></tr>
                <tr style="border:dashed 1px #ccc;"><td>{$order_result.customer_name}</td></tr>
            </table>

            <table cellpadding="5" style="width:45%; float:left; border-collapse:collapse; border:solid 1px #ccc; margin-bottom:10px;">
                <tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;"><td><b>Branch Details</b></td></tr>
                <tr style="border:dashed 1px #ccc;">
                    <td>{$order_result.branch} - {$order_result.branch_code}
                    {if $order_result.branch_num != 0}
                        &nbsp; [ <b>#</b>: {$order_result.branch_num} ]
                    {/if}
                    </td>
                </tr>
            </table>
        </td>
	</tr>

	<tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
		<td width="3%" align="center"><b>#</b></td>
		<td width="15%"><b>Model #</b></td>
		<td width="50%"><b>Description</b></td>
		<td width="8%" align="center"><b>UOM</b></td>
		<td width="7%" align="center"><b>Qty</b></td>
{if $show_price==1}
		<td width="10%" align="center"><b>Product Cost</b></td>
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
            <td align="center" rowspan="{$lineitem_rows}" style="border-bottom:dashed 1px #ccc">{$item_no++}</td>
			<td rowspan="{$lineitem_rows}" style="border:dashed 1px #ccc; border-top: none;">{$pricing_model} {if $pricing_model|trim==""}-{/if}</td>
			<td {if $lineitem_rows > 1} colspan="{3+$show_price}" {/if} style="border:dashed 1px #ccc; border-top: none; border-left: none; border-right: none;">
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
                <td align="center" style="border:dashed 1px #ccc; border-top: none;"><b>{$value.uom}</b></td>
                <td align="center" style="border:dashed 1px #ccc; border-top: none; border-left: none;"><b>{$value.pgen_matqty}</b></td>
{if $show_price==1}
                <td align="right" style="border:dashed 1px #ccc; border-top: none; border-left: none; border-right: none;"><b>$ {number_format($value.pgen_matqty*$value.productcost,2)}</b> &nbsp;</td>
				{$prd_material_total = $prd_material_total+$value.pgen_matqty*$value.productcost}
{/if}
            {/if}
       </tr>
		{if !empty($order_subquery)}
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
                    <td style="border:dashed 1px #ccc; border-top: none; border-left: none;"><b>{trim($value2.Item)}</b></td>
                    <td align="center" style="border:dashed 1px #ccc; border-top: none; border-left: none;"><b>{$submat_uom}</b></td>
                    <td align="center" style="border:dashed 1px #ccc; border-top: none; border-left: none;"><b>{$subitem_qty}</b></td>
{if $show_price==1}
                    <td align="right" style="border:dashed 1px #ccc; border-top: none; border-left: none; border-right: none;"><b>$ {number_format($subitem_cost, 2)}</b> &nbsp;</td>
                    {$prd_material_total = $prd_material_total+$subMatMass.cost}
{/if}
                {/if}
                </tr>       
			{/if}
			{/if}
		{/foreach}
		{/if}
	{/foreach}
{/if}
	
    {$mitem_ct=0}
    {if $misc_count > 0}
        {foreach from=$misc_result key=key1 item=valuem}
            {if $mitem_ct==0}
            <tr bgcolor="#F0F0F0">
                <td colspan="{5+$show_price}"><b>Miscellaneous items</b></td>
            </tr>
            {/if}
            <tr>
                <td align="center" style="border:dashed 1px #ccc; border-top: none; border-left: none;">{1+$mitem_ct++}</td>
                <td style="border:dashed 1px #ccc; border-top: none; border-left: none;">{$valuem.item_name}</td>
                <td style="border:dashed 1px #ccc; border-top: none; border-left: none;">{$valuem.category} - {$valuem.description}</td>
                <td align="center" style="border:dashed 1px #ccc; border-top: none; border-left: none;">{$valuem.uom}</td>
                <td align="center" style="border:dashed 1px #ccc; border-top: none; border-left: none;">{$valuem.qty}</td>
{if $show_price==1}
                <td align="center" style="border:dashed 1px #ccc; border-top: none; border-left: none; border-right: none;">${number_format($valuem.qty*$valuem.material,2)}</td>
                {$prd_material_total = $prd_material_total+$valuem.qty*$valuem.material}
{/if}
            </tr>
        {/foreach}
    {/if}
{if $show_price==1}
    <tr bgcolor="#f9f9f9">
        <td colspan="{4+$show_price}" align="right" style="border-right:0;"><b>Total Cost &nbsp;:</b></td>
        <td style="border-left:0; border-right:0;" align="right"><b>$ {number_format($prd_material_total, 2)}</b> &nbsp;</td>
    </tr>
{/if}
</table>
