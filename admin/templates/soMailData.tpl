{if $show_price==0}
	{$col_width=[5, 15, 60, 10, 10]}
{else}
	{$col_width=[5, 15, 50, 9, 9, 12]}
{/if}
<table cellspacing="0" cellpadding="5" style="border:solid 1px #b4b4b4; width:100%; border-collapse:collapse;">
  <tr>
    <td colspan="{5+$show_price}">
      <table cellspacing="0" cellpadding="5" style="border:0; width:100%; border-collapse:collapse;">
        <tr>
          <td width="15%">ORDER ID :</td><td width="25%"><b>{$order_result['order_id']}</b></td>
          <td colspan="2" bgcolor="#F0F0F0" style="border:solid 1px #ccc;">&nbsp;&nbsp;&nbsp; <b>Customer Details</b></td>
        </tr>
        <tr>
          <td>JOB ID : </td><td><b>{$order_result.jobid}{if $order_result.jobid==""}-{/if}</b></td>
          <td width="25%" style="border:solid 1px #ccc; border-right:none;">&nbsp;&nbsp;&nbsp; Name</td>
          <td width="35%" style="border:solid 1px #ccc; border-left:none;">{$order_result.customer_name}</td>
        </tr>
        <tr>
          <td>LEAD ID : </td><td><b>{$order_result.lead_id}{if $order_result.lead_id==""}-{/if}</b></td>
          <td style="border:solid 1px #ccc; border-right:none;">&nbsp;&nbsp;&nbsp; Email</td>
          <td style="border:solid 1px #ccc; border-left:none;">{$order_result.customer_email}</td>
        </tr>
        <tr>
          <td>Cust. ID : </td><td><b>{$order_result.customer_id}</b></td>
          <td style="border:solid 1px #ccc; border-right:none;">&nbsp;&nbsp;&nbsp; Tel #</td>
          <td style="border:solid 1px #ccc; border-left:none;">{$order_result.customer_telephone}</td>
        </tr>
        <tr>
          <td>Rep. Name : </td><td><b>{$order_result.repname}</b></td>
          <td style="border:solid 1px #ccc; border-right:none;">&nbsp;&nbsp;&nbsp; Address</td>
          <td style="border:solid 1px #ccc; border-left:none;">{$order_result.customer_address}, {$order_result.customer_city}, {$order_result.customer_state} {$order_result.customer_zipcode}</td>
        </tr>
      </table></td>
  </tr>
  <tr bgcolor="#F0F0F0">
    <td width="{$col_width.0}%" align="center"><b>#</b></td>
    <td width="{$col_width.1}%"><b>Pricing Model #</b></td>
    <td width="{$col_width.2}%"><b>Description</b></td>
    <td width="{$col_width.3}%" align="center"><b>UOM</b></td>
    <td width="{$col_width.4}%" align="center"><b>Qty</b></td>
{if $show_price == 1}
    <td width="{$col_width.5}%" align="center"><b>Price</b></td>
{/if}
  </tr>

{$total_amount = 0}
{if $products_result gt 0}
  {$i=1}
  {foreach from=$products_result key=key item=value}
  <tr>
    <td style="border:#ccc solid 1px;" align="center">{$i++}</td>
    <td style="border:#ccc solid 1px;">{$value.pricing_model} {if $value.pricing_model|trim==""}-{/if}</td>
    <td style="border:#ccc solid 1px;">{$value.description} {if $value.prd_options!=""}| {$value.prd_options}{/if}</td>
    <td style="border:#ccc solid 1px;" align="center">{$value.uom}</td>
    <td style="border:#ccc solid 1px;" align="center">{$value.prd_qty}</td>
{if $show_price == 1}
    <td style="border:#ccc solid 1px;" align="right">${number_format($value.prd_price,2)}</td>
	{$total_amount = $total_amount + $value.prd_price}
{/if}
  </tr>
  {/foreach} 
{/if}

  <!-- Misc item display starts --> 
  {if $misc_result.0 > 0}
  <tr bgcolor="#F0F0F0">
    <td colspan="{5+$show_price}"><b>Miscellaneous items</b></td>
  </tr>

  {foreach from=$misc_result.1 key=key item=value}
  <tr>
    <td style="border:#ccc solid 1px;" align="center">{$key+1}</td>
    <td style="border:#ccc solid 1px;">{$value.item_name}</td>
    <td style="border:#ccc solid 1px;">{$value.description}</td>
    <td style="border:#ccc solid 1px;" align="center">{$value.uom}</td>
    <td style="border:#ccc solid 1px;" align="center">{$value.qty}</td>
{if $show_price==1}
    <td style="border:#ccc solid 1px;" align="right">${number_format($value.qty*$value.retail,2)}</td>
{/if}
  </tr>
  {/foreach}	
  {/if} 
  <!-- Misc item display ends --> 

{if $show_price==1}
  <tr bgcolor="#F0F0F0">
    <td colspan="{4+$show_price}" align="right"><b>Grand Total :</b></td>
    <td colspan="1" align="right"> ${number_format((float)$total_amount,2)} </td>
  </tr>
<!--
  <tr bgcolor="#F0F0F0">
    <td colspan="{4+$show_price}" align="right"><b>Grand Total :</b></td>
    <td colspan="1" align="right"> ${number_format((float)$order_result.total_amount,2)} </td>
  </tr>
  <tr>
    {if $order_result.promo_type=="percent"}
        <td colspan="5" align="right"><b>Promo ( {$order_result.promo_percent} % ) :</b></td>
    {else}
        <td colspan="5" align="right"> <b>Promo ( $ ) :</b></td>
    {/if}
		{$promo_amount = $order_result.promo_amt}
        <td colspan="1" align="right">  ${number_format((float)$promo_amount,2)} </td>
  </tr>

{if $order_result.apd_amt gt 0}
  <tr>
    <td colspan="5" align="right"><b>Adnl. Discount :</b></td>
    <td colspan="1" align="right"> ${number_format((float)$order_result.apd_amt,2)} </td>
  </tr>
{/if}
{if $order_result.admin_fee gt 0}
  <tr>
    <td colspan="5" align="right"><b>Administrative Fees :</b></td>
    <td colspan="1" align="right"> ${number_format((float)$order_result.admin_fee,2)} </td>
  </tr>
{/if}

  <tr>
    <td colspan="5" align="right"><b>Lead Test Fee :</b></td>
    <td colspan="1" align="right"> ${number_format((float)$order_result.lt_amt, 2)} </td>
  </tr>
  <tr>
    <td colspan="5" align="right"><b>Lead Free Work Practice :</b></td>
    <td colspan="1" align="right"> ${number_format((float)$order_result.lfwp_amt, 2)} </td>
  </tr>
{*
  <tr>
    <td colspan="5" align="right"><b>General Constructions :</b></td>
    <td colspan="1" align="right"> ${number_format((float)$order_result.gen_con, 2)} </td>
  </tr>
*}
  <tr>
    <td colspan="5" align="right"><b>Permit :</b></td>
    <td colspan="1" align="right"> {$permit_amt = ($order_result.total_amount + $order_result.admin_fee + $order_result.lt_amt + $order_result.lfwp_amt - $promo_amount - $order_result.apd_amt) * $order_result.permit_percent / 100}
      {if $permit_amt>$order_result.permit_max} {$permit_amt=$order_result.permit_max} {/if}
      ${number_format((float)$permit_amt,2)} </td>
  </tr>
  {if $order_result.disc_sur_amt > 0}
  <tr>
    <td colspan="5" align="right"><b>Adjustment Amount :</b></td>
  	<td colspan="1" align="right"> - ${number_format((float)$order_result.disc_sur_amt, 2)} </td>
  </tr>
  {/if}

  <tr>
    <td colspan="5" align="right"><b>Net Amount :</b></td>
    <td colspan="1" align="right"> ${number_format((float)($order_result.net_amount-$order_result.disc_sur_amt), 2)} </td>
  </tr>
{/if}
-->
  {* if $order_result.attached_files!=""}
  <tr bgcolor="#F0F0F0">
    <td colspan="6"><b>Sales Rep uploaded files</b></td>
  </tr>
  <tr>
    <td colspan="6"> {$attached_files= ","|explode:$order_result.attached_files}
      <ul style="width:100%; display:block;">
        {foreach $attached_files as $key=>$attachments}
        {if $attachments|trim!=""}
        <li style="display:inline-block; margin:0 15px 10px 0;"><b style="color:#F88421; border:solid 1px; padding:2px 6px; border-radius:15px;">{$key+1}</b> <a class="preview_file" href="../uploads/{$attachments}" target="_blank" style="text-decoration: underline;">{$attachments}</a></li>
        {/if}
        {/foreach}
      </ul></td>
  </tr>
  {/if *}
</table>
