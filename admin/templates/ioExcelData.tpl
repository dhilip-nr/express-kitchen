{if $show_price==0}
	{$col_width=[5, 15, 60, 10, 10]}
{else}
	{$col_width=[5, 15, 50, 9, 9, 12]}
{/if}

<table cellpadding="5">
  <tr>
    <td colspan="3"> ORDER ID : <b>{$order_result['order_id']}</b> | JOB ID : <b>{$order_result['jobid']} {if $order_result['jobid']==""} - {/if}</b></td>
    <td colspan="{3+$show_price}" align="right"> Sent On : <b>{if $order_result.sent_installer==""} - {else} {$order_result.sent_installer|date_format} {/if}</b></td>
  </tr>
  <tr bgcolor="#F0F0F0">
    <td colspan="3"><b>Customer Details</b></td>
    <td colspan="{3+$show_price}"><b>Installer Details</b> [ <b>vendor #</b>: {if $order_result['vendor_num']==''}-{else}{$order_result['vendor_num']}{/if} ]</td>
  </tr>
  <tr>
    <td colspan="2"><b>Name</b></td>
    <td>{$order_result['customer_name']}</td>
    <td width="20%"><b>Company</b></td>
    <td colspan="{2+$show_price}">{$order_result['installer_company']}{if $order_result['installer_company']==""}-{/if}</td>
  </tr>
  <tr>
    <td colspan="2"><b>Email</b></td>
    <td>{$order_result['customer_email']}</td>
    <td><b>Name</b></td>
    <td colspan="{2+$show_price}"> {$order_result['installer_firstname']} {$order_result['installer_lastname']}
      {if $order_result['installer_firstname']=="" && $order_result['installer_lastname']==""}-{/if}</td>
  </tr>
  <tr>
    <td colspan="2"><b>Phone</b></td>
    <td>{$order_result['customer_telephone']}</td>
    <td><b>Email</b></td>
    <td colspan="{2+$show_price}">{$order_result['installer_email']}{if $order_result['installer_email']==""}-{/if}</td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><b>Address</b></td>
    <td> {$order_result['customer_address']}, {$order_result['customer_city']}, {$order_result['customer_state']} {$order_result['customer_zipcode']} </td>
    <td><b>Phone</b></td>
    <td colspan="{2+$show_price}"> {$order_result['installer_officephone']}{if $order_result['installer_officephone']==""}-{/if} <b>/</b> {$order_result['installer_mobile']}{if $order_result['installer_mobile']==""}-{/if} </td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
    <td><b>Address</b></td>
    <td colspan="{2+$show_price}"> {$order_result['installer_officeaddress']}{if $order_result['installer_officeaddress']==""}-{/if},
      {$order_result['installer_city']}{if $order_result['installer_city']==""}-{/if},
      {$order_result['installer_state']}{if $order_result['installer_state']==""}-{/if}
      {$order_result['installer_zipcode']}{if $order_result['installer_zipcode']==""}-{/if} </td>
  </tr>
  <tr bgcolor="#F0F0F0">
    <td width="{$col_width.0}%" align="center"><b>#</b></td>
    <td width="{$col_width.1}%"><b>Model #</b></td>
    <td width="{$col_width.2}%" colspan="2"><b>Description</b></td>
    <td width="{$col_width.3}%" align="center"><b>UOM</b></td>
    <td width="{$col_width.4}%" align="center"><b>Qty</b></td>
{if $show_price==1}
    <td width="{$col_width.5}%" align="center"><b>Product Cost</b></td>
{/if}
  </tr>
{$uom_except_arr = ["SF"=>"squarefeet", "LF"=>"linealfeet", "EA"=>""]}
{$manfacturer_dup = ""}
{$installer_grandtotal = 0}
{if $products_result gt 0}
  {$i=1}
  {foreach from=$products_result key=key item=value}
  {assign var="product_sku" value=trim("`$value.remode_orderitem_pricingmodel`")}
  {assign var="install_total" value="`$value.laborcost`"}
  {assign var="pricing_model" value=trim("`$value.remode_orderitem_pricingmodel`")}
  {assign var="lineitem_rows" value=1}
  {if isset($order_subitems_count[$pricing_model])}
  {assign var="lineitem_rows" value=$order_subitems_count[trim($pricing_model)]+1}
  {/if}
  
  {if $manfacturer_dup != $value.manufacturer && !in_array($smarty.session[$APPSESVAR|cat:"_adminuser"].role, ['branchadmin','dealer'])}
  {$i=1}
  <tr bgcolor="#f9f9f9">
    <td colspan="{6+$show_price}"><b>{$value.manufacturer}</b></td>
  </tr>
  {/if}
  {$manfacturer_dup = $value.manufacturer}
  
  {$highlight_oi_changes=""}
  {if $value.by_ip=='1' && $value.is_approved=='1'}
  {$highlight_oi_changes = 'background:#fefed0;'}
  {$installer_moditems_cnt = $installer_moditems_cnt+1}
  {/if}
  <tr> 
  <td align="center" rowspan="{$lineitem_rows}" valign="top">{$i++}</td>
  <td rowspan="{$lineitem_rows}" valign="top"> {$product_sku} {if $product_sku|trim==""} - {/if} </td>
  <td {if $lineitem_rows>1} colspan="{4+$show_price}"{else} colspan="2"{/if}>
  {if $lineitem_rows>1}
  {$value.description}
  {else} <b>{$value.description}</b> {/if}
{*
  {if $value.cat_id!=6}
  {foreach $catoptions[$value.cat_id] as $catopt}
  {strbwstrs spattern=$catopt.db_name epattern=$catopt.db_name symbol="#" subject=$value.options}
  {if isset($matches.1) && $uom_except_arr[$value.uom]!=$catopt.db_name} <br/>
  {$catopt.name|upper}: {$matches.1}
  {/if}
  {/foreach}
  {/if}
*}
  {if $lineitem_rows>1} <span style="float:right">[ UOM:{$value.uom}, QTY:{$value.pgen_matqty} ]</span> {/if}
  {if isset($material_lineitems)}
  {assign var="head" value=1}
  {assign var="mat_table" value="close"}
  {foreach from=$material_lineitems key=key3 item=value3}
  {if trim($pricing_model) == trim($value3.pricingmodel)}
  {if $head == 1}
  <table width="100%" cellpadding="5">
    <tr bgcolor="#F0F0F0">
      <td{if $lineitem_rows>1} colspan="5"{else} colspan="3"{/if}>Material Details</td>
    </tr>
    {assign var="mat_table" value="opened"}
    {assign var="head" value=2} 
    {/if}
    <tr> {$subMatMass = $IOInfoList->SubMatMass($value, $value3)}
      {$submat_uom =  $subMatMass.uom}
      {$subitem_qty = $subMatMass.qty}
      
      {if $subitem_qty>0}
      {$subitem_qty = $subitem_qty}
      <td{if $lineitem_rows>1} colspan="2"{/if} width="60%"> &nbsp; {trim($value3.Item)}
      </td>
      <td{if $lineitem_rows>1} colspan="{1+$show_price}"{/if} align="center" width="24%">UOM: {$submat_uom}
      </td>
      <td width="16%">QTY: {$subitem_qty}</td>
      {/if} </tr>
    {/if}
    {/foreach} 
    {if $mat_table=="opened"}
    </table>
    {/if}
  {/if}
  </td>
  
  {if $lineitem_rows==1}
    <td align="center"><b>{$value.uom}</b></td>
    <td align="center"><b>{number_format($value.pgen_qty,2)}</b></td>
{if $show_price==1}
    <td align="right"><b>$ {number_format((float)$install_total,2)}</b></td>
    {$installer_grandtotal = $installer_grandtotal+$install_total}
{/if}
    {/if} </tr>
  {if isset($order_subquery)}
  {foreach from=$order_subquery key=key2 item=value2}
  {if trim($pricing_model) == trim($value2.pricingmodel)}
  <tr>
    {$subLabMass = $IOInfoList->SubLabMass($value, $value2)}
    {$lab_uom =  $subLabMass.uom}
    {$lab_qty = $subLabMass.qty}
    {$lab_cost = $lab_qty*$value2.cost}
    {if $lab_qty>0}
    <td align="left" colspan="2"><b>{trim($value2.category)} - {trim($value2.labor_code)} - {trim($value2.description)}</b></td>
    <td align="center"><b>{$lab_uom}</b></td>
    <td align="center"><b>{number_format($lab_qty, 2)}</b></td>
{if $show_price==1}
    <td align="right"><b>$ {number_format($lab_cost, 2)}</b></td>
    {$installer_grandtotal = $installer_grandtotal+$lab_cost}
{/if}
    {/if} </tr>
  {/if}
  {/foreach}
  {/if}
  {/foreach}
{/if}
  {if $misc_count > 0}
  <tr bgcolor="#F0F0F0">
    <td colspan="{6+$show_price}"><b>Miscellaneous items</b></td>
  </tr>
  {foreach from = $misc_result key=key item=value}
  {$highlight_mi_changes=""}
  {if $value.by_ip=='1' && $value.is_approved=='1'}
  {$highlight_mi_changes = 'style="background:#fefed0;"'}
  {$installer_moditems_cnt = $installer_moditems_cnt+1}
  {/if}
  <tr {$highlight_mi_changes}>
    <td align="center">{$key+1}</td>
    <td>{$value.item_name}</td>
    <td colspan="2"><b>{$value.vendor}</b> - {$value.description}</td>
    <td align="center">{$value.uom}</td>
    <td align="center">{number_format($value.qty,2)}</td>
{if $show_price==1}
    <td align="right">$ {number_format($value.qty*$value.labor, 2)}</td>
{/if}
  </tr>
  {$installer_grandtotal = $installer_grandtotal+$value.qty*$value.labor}
  {/foreach}	
  {/if}

  {if !empty($damage_installer_result)}
  <tr bgcolor="#F0F0F0">
    <td colspan="{6+$show_price}"><b>Installer Damaged Items</b></td>
  </tr>
  {/if}

  {if !empty($damage_installer_result)}
  {foreach from = $damage_installer_result key=key1 item=valued}
  <tr>
    <td align="center">{1+key1}</td>
    <td>{$valued.pricingmodel} </td>
    <td colspan="2">{$valued.item}</td>
    <td align="center">{trim($valued.uom)}</td>
    <td align="center">{$valued.qty}</td>
{if $show_price==1}
    <td align="right">- $ {number_format($valued.qty*$valued.cost,2)}</td>
{/if}
  </tr>
  {$installer_grandtotal = $installer_grandtotal-$valued.qty*$valued.cost}
  {/foreach}
  {/if}



{$i=1}
<tr bgcolor="#f0f0f0">
<td colspan="{6+$show_price}"><b>Others</b></td>
</tr>
{if $order_result.lt_amt!=0}
<tr>
    <td align="center">{$i++}</td>
    <td align="left" colspan="3">Lead Test</td>
    <td align="center">EA</td>
    <td align="center">{number_format(1,2)}</td>
    {if $show_price==1}
    <td align="right">$ {number_format((float)$order_result.lt_amt, 2)}</td>
    {/if}
</tr>
{/if}
{if $order_result.lfwp_amt!=0}
<tr>
<td align="center">{$i++}</td>
<td align="left" colspan="3">Lead Free Work Practice</td>
<td align="center"> EA </td>
<td align="center"> {number_format(1,2)} </td>
{if $show_price==1}
<td align="right">$ {number_format((float)$order_result.lfwp_amt, 2)}</td>
{/if}
</tr>
{/if}
{$installer_grandtotal = $installer_grandtotal+$order_result.lt_amt+$order_result.lfwp_amt}

  <tr>
	<td align="center">{$i}</td>
    <td colspan="3">Permit Fee</td>
    <td align="center">EA</td>
    <td align="center">1.00</td>
{if $show_price==1}
    <td align="right"> {$promo_amount = $order_result.promo_amt}
      {$permit_amount = ($order_result.total_amount+$order_result.admin_fee+$order_result.lt_amt+$order_result.lfwp_amt-$promo_amount)*$order_result.permit_percent/100}
      {if $permit_amount>$order_result.permit_max} {$permit_amount=$order_result.permit_max} {/if}
      $ {number_format($permit_amount, 2)} </td>
{/if}
  </tr>
{if $show_price==1}
  <tr bgcolor="#f9f9f9">
    <td colspan="{5+$show_price}" align="right"><b>Total Cost &nbsp;:</b></td>
    <td align="right"><b>$ {number_format($installer_grandtotal+$permit_amount, 2)}</b></td>
  </tr>
{/if}
</table>