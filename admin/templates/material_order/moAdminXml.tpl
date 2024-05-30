<?xml version="1.0" encoding="UTF-8"?>
<material_order>
    <customer_details>
        <tag name="first_name" value="{htmlentities($order_result.customer_fname)}" />
        <tag name="last_name" value="{htmlentities($order_result.customer_lname)}" />
        <tag name="email" value="{htmlentities($order_result.customer_email)}" />
        <tag name="phone" value="{htmlentities($order_result.customer_telephone)}" />
        <tag name="address" value="{htmlentities($order_result.customer_address)}" />
        <tag name="city" value="{htmlentities($order_result.customer_city)}" />
        <tag name="state" value="{htmlentities($order_result.customer_state)}" />
        <tag name="zipcode" value="{htmlentities($order_result.customer_zipcode)}" />
    </customer_details>
    {$ship_to_info = $MOInfoList->GetOrdHeaderData($order_result, $products_result.0, "ship_to")}
    <ship_to>
		<tag name="company" value="{htmlentities($ship_to_info.company)}" />
        <tag name="name" value="{htmlentities($ship_to_info.name)}" />
        <tag name="email" value="{htmlentities($ship_to_info.email)}" />
        <tag name="phone" value="{htmlentities($ship_to_info.phone)}" />
	</ship_to>
    <mail_details>
        <tag name="mail_to" value="{htmlentities($order_result.branch_admin_email)}" />
        <tag name="mail_cc" value="" />
    </mail_details>

    <item_details>
    {$i=1}
    {$order_total = 0}
    {if !empty($products_result)}
    {foreach $products_result as $rows1}
        {$orderitems_description = htmlentities($rows1.description)}
        {$orderitems_description = str_replace('&deg;', '', $orderitems_description)}
        {$orderitems_description = str_replace('&Acirc;', '', $orderitems_description)}
    	{if isset($order_subitems_count[trim($rows1.remode_orderitem_pricingmodel)])}
	    	{$mat_row_count = $order_subitems_count[trim($rows1.remode_orderitem_pricingmodel)]}
        {else}
	    	{$mat_row_count = 0}
        {/if}

        <item number="{$i++}">
            <tag name="name" value="{htmlentities($rows1.item_name)}" />
            <tag name="description" value="{$orderitems_description}" />
            <tag name="options" value="{htmlentities($rows1.prd_options)}" />
            <tag name="comments" value="{htmlentities($rows1.comments)}" />
            <tag name="uom" value="{htmlentities($rows1.uom)}" />
		{if $show_price==1}
        {if $mat_row_count > 0}					
            <tag name="cost" value="0.00" />
        {else}
            <tag name="cost" value="{htmlentities($rows1.productcost)}" />
            {$order_total = $order_total+$rows1.productcost}
        {/if}
        {/if}  
        {if $mat_row_count>0}
            {$si = 1}
            {foreach $order_subquery as $subitemrow}
                {if trim($rows1.remode_orderitem_pricingmodel)==trim($subitemrow.pricingmodel)}
                    {$subMatMass = $MOInfoList->SubMatMass($rows1, $subitemrow)}
                    {$submat_uom = $subMatMass.uom}
                    {$submat_qty = $subMatMass.qty}
                    {$submat_cost = $subMatMass.cost}

                    <material number="{$si++}">
                        <tag name="name" value="{htmlentities($subitemrow.Item)}" />
                        <tag name="uom" value="{htmlentities($submat_uom)}" />
                        <tag name="quantity" value="{htmlentities($submat_qty)}" />
					{if $show_price==1}
                        <tag name="cost" value="{htmlentities($submat_cost)}" />
                        {$order_total = $order_total+$submat_cost}
					{/if}
                    </material>
                {/if}
            {/foreach}
        {/if}
	    </item>
    {/foreach}
    {/if}

    {if !empty($misc_result)}
        {foreach $misc_result as $miscitem}
            <item number="{$i++}">
                <tag name="name" value="{htmlentities($miscitem.item_name)}" />
                <tag name="description" value="{htmlentities($miscitem.category)} - {htmlentities($miscitem.description)}" />
                <tag name="quantity" value="{$miscitem.qty}" />
                <tag name="uom" value="{htmlentities($miscitem.uom)}" />
			{if $show_price==1}
                <tag name="unit" value="{$miscitem.material}" />
	            {$order_total = $order_total+($miscitem.material*$miscitem.qty)}
            {/if}
            </item>
        {/foreach}
    {/if}
	</item_details>

    {if $order_result.sent_material|trim!=""}
        {$sent_material_all_arr = $order_result.sent_material|explode:","}    
        {$sent_material_key = array_search($products_result.0.manufacturer, $sent_material_all_arr)}
        {$sent_material_date = $sent_material_all_arr.$sent_material_key|explode:"_"}
        {if isset($sent_material_date[1])} {$material_sent_date=date_format(date_create($sent_material_date[1]), "d-M-Y H:i")} {else} {$material_sent_date="-"} {/if}
	{else}    
	    {$material_sent_date="-"} 
    {/if}

    <order_summary>
        <tag name="order_id" value="{htmlentities($order_result["order_id"])}" />
        <tag name="branch_number" value ="{htmlentities($order_result["branch_num"])}"/>
        <tag name="mo_placed_on" value="{$material_sent_date}" />
        <tag name="number_of_products" value="{count($products_result)}" />
		{if $show_price==1}
        <tag name="total_cost" value="{number_format($order_total, 2)}" />
		{/if}
    </order_summary>
</material_order>
