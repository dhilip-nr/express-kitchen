<?xml version="1.0" encoding="UTF-8"?>
    <install_order>
        <customer_details>
            <tag name="first_name" value="{htmlentities($order_result.customer_fname)}" />
            <tag name="last_name" value="{htmlentities($order_result.customer_lname)}" />
            <tag name="phone" value="{htmlentities($order_result.customer_telephone)}" />
            <tag name="email" value="{htmlentities($order_result.customer_email)}" />
            <tag name="address" value="{htmlentities($order_result.customer_address)}" />
            <tag name="city" value="{htmlentities($order_result.customer_city)}" />
            <tag name="state" value="{htmlentities($order_result.customer_state)}" />
            <tag name="zipcode" value="{htmlentities($order_result.customer_zipcode)}" />
        </customer_details>
        <mail_details>
            <tag name="mail_to" value="{htmlentities($order_result.branch_admin_email)}" />
            <tag name="mail_cc" value="" />
        </mail_details>        
        <installer_details>
            <tag name="material_shipped_to_installer" value="{htmlentities($order_result.installer_company)}" />
            <tag name="first_name" value="{htmlentities(trim($order_result.installer_firstname))}" />
            <tag name="last_name" value="{htmlentities(trim($order_result.installer_lastname))}" />
            <tag name="phone" value="{htmlentities($order_result.installer_mobile)}" />
            <tag name="officephone" value="{htmlentities($order_result.installer_officephone)}" />
            <tag name="email" value="{htmlentities($order_result.installer_email)}" />
            <tag name="address" value="{htmlentities($order_result.installer_officeaddress)}" />
            <tag name="city" value="{htmlentities($order_result.installer_city)}" />
            <tag name="state" value="{htmlentities($order_result.installer_state)}" />
            <tag name="zipcode" value="{htmlentities($order_result.installer_zipcode)}" />
        </installer_details>
		{$k=1}
		{$last_manufacturer_name = ""}
		{$j=1}
		{$order_total=0}

		{$manufacturer_arr = []}
		{foreach $products_result as $prditems}
			{$manufacturer_arr[] = $prditems.manufacturer}
		{/foreach}

		{$manufacturer_arr = array_values(array_unique($manufacturer_arr))}
{if $products_result gt 0}
        {for $i=0; $i < count($manufacturer_arr); $i++}
			{$manufacturer_name = $manufacturer_arr.$i}
  {if in_array($smarty.session[$APPSESVAR|cat:"_adminuser"].role, ['branchadmin','dealer'])}
			<item_details manufacture="RDI">
  {else}
			<item_details manufacture="{htmlentities($manufacturer_name)}">
			{$j = 1}
  {/if}		

			{foreach $products_result as $productitems}
				{if $productitems.manufacturer == $manufacturer_name}
	
				{$pricing_model = htmlentities($productitems.remode_orderitem_pricingmodel)}
                {$product_options = $productitems.prd_options|replace:"<br>":" | "}
	
				<item number="{$j++}">
					<tag name="model#" value="{$pricing_model}" />
					<tag name="name" value="{htmlentities($productitems.item_name)}" />
					<tag name="description" value="{htmlspecialchars($productitems.description)}" />
					<tag name="options" value="{htmlentities($product_options)}" />
					<tag name="uom" value="{htmlentities($productitems.uom)}" />
					<tag name="quantity" value="{$productitems.pgen_qty}" />
				{$li = 1}
				{foreach $order_subquery as $laboritems}
					{if trim($pricing_model) == trim($laboritems.pricingmodel)}
                        {$subLabMass = $IOInfoList->SubLabMass($productitems, $laboritems)}
                        {$lab_uom =  $subLabMass.uom}
                        {$lab_qty = $subLabMass.qty}
                        {$lab_cost = $lab_qty*$laboritems.cost}
	
						{if $lab_qty!=0}
						<labor number="{$li++}">
							<tag name="name" value="{htmlentities($laboritems.category)} - {htmlentities($laboritems.labor_code)}" />
							<tag name="description" value="{htmlentities($laboritems.description)}" />
							<tag name="uom" value="{htmlentities($lab_uom)}" />
							<tag name="quantity" value="{$lab_qty}" />
							{if $show_price==1}
							<tag name="unit_cost" value="{number_format($lab_cost, 2)}" />
							{$order_total = $order_total+$lab_cost}
							{/if}
							</labor>
						{/if}
					{/if}
				{/foreach}
	
				{if $show_price==1 && $li==1}
					<tag name="unit_cost" value="{htmlentities($productitems.laborcost)}" />
					{$order_total = $order_total+($productitems.laborcost*$productitems.pgen_qty)}
				{/if}
	
				</item>
				{/if}
			{/foreach}

			</item_details>
		{/for}
{/if}
		{if $misc_count>0}
			<miscellaneous_items>
			{foreach $misc_result as $key=>$miscitem}
				<item number="{$key+1}">
					<tag name="model#" value="" />
					<tag name="name" value="{htmlentities($miscitem.item_name)}" />
					<tag name="description" value="{htmlentities($miscitem.category)} - {htmlentities($miscitem.description)}" />
					<tag name="options" value="" />
					<tag name="uom" value="{htmlentities($miscitem.uom)}" />
					<tag name="quantity" value="{$miscitem.qty}" />
				{if $show_price==1}
					<tag name="unit_cost" value="{$miscitem.labor}" />
					{$order_total = $order_total+($miscitem.labor*$miscitem.qty)}
				{/if}
                 </item>
			{/foreach}
			</miscellaneous_items>
		{/if}

		{$promo_amount = $order_result.promo_amt}
		{$permit_amount = ($order_result.total_amount+$order_result.admin_fee+$order_result.lt_amt+$order_result.lfwp_amt-$promo_amount)*$order_result.permit_percent/100}
		{if $permit_amount>$order_result.permit_max} {$permit_amount = $order_result.permit_max} {/if}

		{$order_total = $order_total+$order_result.lt_amt+$order_result.lfwp_amt+$permit_amount}

	<order_summary>
        <tag name="order_id" value="{htmlentities($order_result.order_id)}" />
        <tag name="job_id" value="{htmlentities($order_result.jobid)}" />
        <tag name="store_number" value="{htmlentities($order_result.store_num)}" />
        <tag name="contract_date" value="{date_format(date_create($order_result.sent_installer), "d-M-Y")}" />
        <tag name="number_of_manufacturers" value="{count($manufacturer_arr)}" />
        <tag name="number_of_products" value="{htmlentities($order_result.total_product)}" />
		{if $show_price==1}
        <tag name="leadtest_fee" value="{$order_result.lt_amt}" />
        <tag name="lfwp_fee" value="{$order_result.lfwp_amt}" />
        <tag name="permit_fee" value="{number_format($permit_amount, 2)}" />
        <tag name="total_cost" value="{number_format($order_total, 2)}" />
		{/if}
    </order_summary>
</install_order>