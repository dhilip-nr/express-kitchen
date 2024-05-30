{if $show_price==0}
	{$col_width=[5, 15, 60, 10, 10]}
{else}
	{$col_width=[5, 15, 50, 9, 9, 12]}
{/if}
<table cellpadding="5">
    <tr>
      <td colspan="2">ORDER ID :</td><td><b>{$order_result['order_id']}</b></td>
      <td colspan="{3+$show_price}" bgcolor="#F0F0F0"><b>Customer Details</b></td>
    </tr>
    <tr>
      <td colspan="2">JOB ID : </td><td align="left">{$order_result.jobid}{if $order_result.jobid==""}-{/if}</td>
      <td><strong>Name</strong></td>
      <td colspan="{2+$show_price}">{$order_result.customer_name}</td>
    </tr>
    <tr>
      <td colspan="2">LEAD ID : </td><td align="left">{$order_result.lead_id}{if $order_result.lead_id==""}-{/if}</td>
      <td><strong>Email</strong></td>
      <td colspan="{2+$show_price}">{$order_result.customer_email}</td>
    </tr>
    <tr>
      <td colspan="2">Cust. ID : </td><td>{$order_result.customer_id}</td>
      <td><strong>Tel #</strong></td>
      <td colspan="{2+$show_price}">{$order_result.customer_telephone}</td>
    </tr>
    <tr>
      <td colspan="2">Rep. Name : </td><td>{$order_result.repname}</td>
      <td><strong>Address</strong></td>
      <td colspan="{2+$show_price}">{$order_result.customer_address}, {$order_result.customer_city}, {$order_result.customer_state} {$order_result.customer_zipcode}</td>
    </tr>
  <tr>
    <td width="{$col_width.0}%" align="center" bgcolor="#F0F0F0"><b>#</b></td>
    <td width="{$col_width.1}%" bgcolor="#F0F0F0"><b>Pricing Model #</b></td>
    <td width="{$col_width.2}%" colspan="2" bgcolor="#F0F0F0"><b>Description</b></td>
    <td width="{$col_width.3}%" align="center" bgcolor="#F0F0F0"><b>UOM</b></td>
    <td width="{$col_width.4}%" align="center" bgcolor="#F0F0F0"><b>Qty</b></td>
{if $show_price==1}
    <td width="{$col_width.5}%" align="center" bgcolor="#F0F0F0"><b>Price</b></td>
{/if}
  </tr>

{if $products_result gt 0}
  {$i=1}
  {foreach from=$products_result key=key item=value}
  <tr>
    <td align="center">{$i++}</td>
    <td>{$value.pricing_model} {if $value.pricing_model|trim==""}-{/if}</td>
    <td colspan="2">{$value.description} {if $value.prd_options!=""}| {$value.prd_options}{/if}</td>
    <td align="center">{$value.uom}</td>
    <td align="center">{$value.prd_qty}</td>
{if $show_price==1}
    <td align="right">${number_format($value.prd_price,2)}</td>
{/if}
  </tr>
  {/foreach} 
{/if}
  
  <!-- Misc item display starts --> 
  {if $misc_result.0 > 0}
  <tr>
    <td colspan="{6+$show_price}" bgcolor="#F0F0F0"><b>Miscellaneous items</b></td>
  </tr>
  {foreach from=$misc_result.1 key=key item=value}
  <tr>
    <td align="center">{$key+1}</td>
    <td>{$value.item_name}</td>
    <td colspan="2">{$value.description}</td>
    <td align="center">{$value.uom}</td>
    <td align="center">{$value.qty}</td>
{if $show_price==1}
    <td align="right">${number_format($value.qty*$value.retail,2)}</td>
{/if}
  </tr>
  {/foreach}	
  {/if} 
  <!-- Misc item display ends --> 
{if $show_price==1}
  <tr>
    <td colspan="{5+$show_price}" align="right" bgcolor="#F0F0F0"><b>Grand Total :</b></td>
    <td align="right" bgcolor="#F0F0F0"> ${number_format((float)$order_result.total_amount,2)} </td>
  </tr>


  <tr>
    {if $order_result.promo_type=="percent"}
	<td colspan="{5+$show_price}" align="right"><b>Promo ( {$order_result.promo_percent} % ) :</b></td>
    {else}
    <td colspan="{5+$show_price}" align="right"> <b>Promo ( $ ) :</b></td>
    {/if}
	{$promo_amount = $order_result.promo_amt}
    <td colspan="1" align="right"> ${number_format((float)$promo_amount,2)} </td>
  </tr>

{if $order_result.apd_amt gt 0}
  <tr>
    <td colspan="{5+$show_price}" align="right"><b>Adnl. Discount :</b></td>
    <td colspan="1" align="right"> ${number_format((float)$order_result.apd_amt,2)} </td>
  </tr>
{/if}
{if $order_result.admin_fee gt 0}
  <tr>
    <td colspan="{5+$show_price}" align="right"><b>Administrative Fees :</b></td>
    <td colspan="1" align="right"> ${number_format((float)$order_result.admin_fee,2)} </td>
  </tr>
{/if}

  <tr>
    <td colspan="{5+$show_price}" align="right"><b>Lead Test Fee :</b></td>
    <td colspan="1" align="right"> ${number_format((float)$order_result.lt_amt, 2)} </td>
  </tr>
  <tr>
    <td colspan="{5+$show_price}" align="right"><b>Lead Free Work Practice :</b></td>
    <td colspan="1" align="right"> ${number_format((float)$order_result.lfwp_amt, 2)} </td>
  </tr>
{*
  <tr>
    <td colspan="{5+$show_price}" align="right"><b>General Constructions:</b></td>
    <td colspan="1" align="right"> ${number_format((float)$order_result.gen_con, 2)} </td>
  </tr>
*}
  <tr>
    <td colspan="{5+$show_price}" align="right"><b>Permit :</b></td>
    <td colspan="1" align="right"> {$permit_amt = ($order_result.total_amount + $order_result.admin_fee + $order_result.lt_amt + $order_result.lfwp_amt - $promo_amount - $order_result.apd_amt) * $order_result.permit_percent / 100}
      {if $permit_amt>$order_result.permit_max} {$permit_amt=$order_result.permit_max} {/if}
      ${number_format((float)$permit_amt,2)} </td>
  </tr>

  {if $order_result.disc_sur_amt > 0}
  <tr>
    <td colspan="{5+$show_price}" align="right"><b>Adjustment Amount:</b></td>
  	<td colspan="1" align="right"> - ${number_format((float)$order_result.disc_sur_amt, 2)} </td>
  </tr>
  {/if}

  <tr>
    <td colspan="{5+$show_price}" align="right"><b>Net Amount :</b></td>
    <td colspan="1" align="right"> ${number_format((float)($order_result.net_amount-$order_result.disc_sur_amt), 2)} </td>
  </tr>
{/if}
</table>
