<?xml version="1.0" encoding="UTF-8"?>
<sales_order>
    <customer_details>
        <tag name="customer_name" value="{htmlentities($order_result.customer_name)}" />
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
    
{if $products_result gt 0}
    <item_details>
    {foreach $products_result as $key=>$product}
        <item number="{$key+1}">
            <tag name="model#" value="{htmlentities($product.pricing_model)}" />
            <tag name="name" value="{htmlentities($product.item_name)}" />
            <tag name="description" value="{htmlspecialchars($product.description)}" />
            <tag name="options" value="{htmlentities($product.prd_options)}" />
            <tag name="uom" value="{htmlentities($product.uom)}" />
            <tag name="quantity" value="{htmlentities($product.prd_qty)}" />
            {if $show_price==1}
            <tag name="price" value="{htmlentities($product.prd_price)}" />
            {/if}
        </item>
    {/foreach}
    </item_details>
{/if}
    {if !empty($misc_result[1])}
    <miscellaneous_items>
        {foreach $misc_result[1] as $key=>$miscitem}
        <item number="{$key+1}">
            <tag name="model#" value="" />
            <tag name="name" value="{htmlentities($miscitem.item_name)}" />
            <tag name="description" value="{htmlentities($miscitem.description)}" />
            <tag name="options" value="" />
            <tag name="uom" value="{htmlentities($miscitem.uom)}" />
            <tag name="quantity" value="{$miscitem.qty}" />
            {if $show_price==1}
            <tag name="price" value="{$miscitem.retail}" />
            {/if}
        </item>
        {/foreach}
    </miscellaneous_items>
    {/if}

    <order_summary>
        <tag name="order_id" value="{htmlentities($order_result.order_id)}" />
        <tag name="job_id" value="{htmlentities($order_result.jobid)}" />
        <tag name="lead_id" value="{htmlentities($order_result.lead_id)}" />
        <tag name="rep_name" value="{htmlentities($order_result.repname)}" />          
        <tag name="customer_id" value="{htmlentities($order_result.customer_id)}" />

    {if $show_price==1}
      	{$promo_amount = $order_result.promo_amt}

        {$permit_amount = ($order_result.total_amount + $order_result.admin_fee + $order_result.lt_amt + $order_result.lfwp_amt + $order_result.gen_con - $promo_amount - $order_result.apd_amt)* $order_result.permit_percent / 100}
        {if $permit_amount>$order_result.permit_max} {$permit_amount=$order_result.permit_max} {/if}

        <tag name="total_amount" value="{number_format($order_result.total_amount, 2)}" />
        <tag name="promo_discount" value="{number_format($promo_amount, 2)}" />
        <tag name="adnl_discount" value="{number_format($order_result.apd_amt, 2)}" />
        <tag name="admin_fee" value="{number_format($order_result.admin_fee, 2)}" />
        <tag name="leadtest_fee" value="{number_format($order_result.lt_amt, 2)}" />
        <tag name="lfwp_fee" value="{number_format($order_result.lfwp_amt, 2)}" />
        <tag name="general_con_amount" value="{number_format($order_result.gen_con, 2)}" />
        <tag name="permit_fee" value="{number_format($permit_amount, 2)}" />
        <tag name="surcharge_amt" value="{number_format($order_result.disc_sur_amt, 2)}" />
        <tag name="net_amount" value="{number_format($order_result.net_amount-$order_result.disc_sur_amt, 2)}" />
    {/if}
	</order_summary>
</sales_order>