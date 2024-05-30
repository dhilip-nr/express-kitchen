{if $show_price==0}
	{$col_width=[5, 15, 60, 10, 10]}
{else}
	{$col_width=[5, 15, 50, 9, 9, 12]}
{/if}

	{$i=1}
	{$uom_except_arr = ["SF"=>"squarefeet", "LF"=>"linealfeet", "EA"=>""]}
    {$manfacturer_dup = ""}
	{$installer_email = $order_result.installer_email}
    {$installer_grandtotal = 0}

	<table cellspacing="0" cellpadding="5" style="border:solid 1px #ccc; width:100%; border-collapse:collapse;">
		<tr>
			<td colspan="{5+$show_price}">
				ORDER ID : <b>{$order_result.order_id}</b>
                | JOB ID : <b>{$order_result.jobid} {if $order_result.jobid==""} - {/if}</b>
                <span style="float:right;" id="jobid">Sent On : <b>{if $order_result.sent_installer==""} - {else} {$order_result.sent_installer|date_format} {/if}</b></span>
			</td>
		</tr>        
		<tr>
		<td colspan="{5+$show_price}">
					<table cellpadding="5" style="width:45%; float:left; border-collapse:collapse; border:solid 1px #ccc;">
						<tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;">

                                    <td colspan="2">
                                        <b>Customer Details</b>
                                    </td>
                                </tr>
								<tr style="border:dashed 1px #ccc;">
									<td width="25%">
										<b>Name</b>

									</td>
									<td width="75%">
										{$order_result.customer_name}
									</td>
								</tr>
								<tr style="border:dashed 1px #ccc;">
									<td>
										<b>Email</b>
									</td>
									<td>
										{$order_result.customer_email}
									</td>
								</tr>
								<tr style="border:dashed 1px #ccc;">
									<td>
										<b>Phone</b>
									</td>
									<td>
										{$order_result.customer_telephone}
									</td>
								</tr>
								<tr style="border:dashed 1px #ccc;">
									<td valign="top">
										<b>Address</b>
									</td>
									<td>
										{$order_result.customer_address}, {$order_result.customer_city}, {$order_result.customer_state} {$order_result.customer_zipcode}
									</td>
								</tr>
					</table>
					<table cellpadding="5" style="width:45%; float:right; border-collapse:collapse; border:solid 1px #ccc;">
						<tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
							<td colspan="2">
								<b>Installer Details</b>
                                <span style="float:right;">[ <b>vendor #</b>: 
                                {if $order_result.vendor_num|trim==''} - {else} {$order_result.vendor_num} {/if} ]
                                </span>
							</td>
						</tr>
						<tr style="border:dashed 1px #ccc;">
							<td width="25%">
								<b>Company</b>
							</td>
							<td width="75%">{$order_result.installer_company}{if $order_result.installer_company==""}-{/if}</td>
						</tr>
						<tr style="border:dashed 1px #ccc;">
							<td>
								<b>Name</b>
							</td>
							<td>
                            	{$order_result.installer_firstname} {$order_result.installer_lastname}
                                {if $order_result.installer_firstname=="" && $order_result.installer_lastname==""}-{/if}
                            </td>
						</tr>
						<tr style="border:dashed 1px #ccc;">
							<td>
								<b>Email</b>
							</td>
							<td>
								{$installer_email}{if $installer_email==""}-{/if}
							</td>
						</tr>
						<tr style="border:dashed 1px #ccc;">
							<td>
								<b>Phone</b>
							</td>
							<td>
								{$order_result.installer_officephone}{if $order_result.installer_officephone==""}-{/if}
								<b style="color:#F68423">/</b> {$order_result.installer_mobile}{if $order_result.installer_mobile==""}-{/if}
							</td>
						</tr>
						<tr style="border:dashed 1px #ccc;">
							<td>
								<b>Address</b>
							</td>
							<td>
								{$order_result.installer_officeaddress}{if $order_result.installer_officeaddress==""}-{/if},
   								{$order_result.installer_city}{if $order_result.installer_city==""}-{/if},
								{$order_result.installer_state}{if $order_result.installer_state==""}-{/if}
								{$order_result.installer_zipcode}{if $order_result.installer_zipcode==""}-{/if}
							</td>
						</tr>
					</table>
		</td>
		</tr>




		<tr id="item_title" bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
			<td width="3%" align="center">
				<b>#</b>
			</td>
			<td width="17%">
				<b>Model #</b>
			</td>
			<td width="55%">
				<b>Description</b>
			</td>
			<td width="8%" align="center">
				<b>UOM</b>
			</td>
			<td width="7%" align="center">
				<b>Qty</b>
			</td>
		{if $show_price==1}
			<td width="10%" align="center">
				<b>Labor Cost</b>
			</td>
		{/if}
		</tr>
		{$i=1}
{if $products_result gt 0}
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
                <td colspan="{5+$show_price}">
                    <b>{$value.manufacturer}</b>
                </td>
            </tr>
			{/if}
            {$manfacturer_dup = $value.manufacturer}
    
            {$highlight_oi_changes=""}
            {if $value.by_ip=='1' && $value.is_approved=='1'}
                {$highlight_oi_changes = 'background:#fefed0;'}
                {$installer_moditems_cnt = $installer_moditems_cnt+1}
            {/if}


		<tr style="{$highlight_oi_changes}">
			<td align="center" rowspan="{$lineitem_rows}" style="border:solid 1px #ccc; border-top:none; border-left:none; border-bottom:dashed 1px #ccc;">{$i++}</td>
			<td rowspan="{$lineitem_rows}" style="border:solid 1px #ccc; border-top:none; border-left:none; border-bottom:dashed 1px #ccc;">
				{$product_sku} {if $product_sku|trim==""} - {/if}
			</td>
			<td {if $lineitem_rows>1} colspan="{3+$show_price}" {/if} style="border-bottom:dashed 1px #ccc; border-left:none; border-right:none;">

				{if $lineitem_rows>1}
                	{$value.description}
				{else}
                	<b>{$value.description}</b>
				{/if}
{*
                {if $value.cat_id!=6}
					{foreach $catoptions[$value.cat_id] as $catopt}
                        {strbwstrs spattern=$catopt.db_name epattern=$catopt.db_name symbol="#" subject=$value.options}
                        {if isset($matches.1) && $uom_except_arr[$value.uom]!=$catopt.db_name}
                            <br/> {$catopt.name|upper}: {$matches.1}
                        {/if}
                    {/foreach}
                {/if}
*}
                {if $lineitem_rows>1}
                	<span style="float:right">[ UOM:{$value.uom}, QTY:{$value.pgen_matqty} ]</span>
                {/if}

				{$material_details_table = ""}
                {if isset($material_lineitems)}
					{capture name='matCapture' assign='material_details_table'}
                    {assign var="head" value=1}
                    {assign var="mat_table" value="close"}
    
                    {foreach from=$material_lineitems key=key3 item=value3}
                        {if trim($pricing_model) == trim($value3.pricingmodel)}
                            {if $head == 1}            
                                <table cellpadding="5" style="font-weight:normal; margin:5px 0; width:100%; border:dashed 1px #ccc;  border-collapse:collapse;">
                                <tr bgcolor="#F0F0F0" style="border:dashed 1px #ccc;">
                                    <td colspan="3">Material Details</td>
                                </tr>
                                {assign var="mat_table" value="opened"}
                                {assign var="head" value=2} 
                            {/if}
    
                            <tr style="border:dashed 1px #ccc;">	
                            <!-- For Material Material Fix for Qty, UOM - Starts -->
                                {$subMatMass = $IOInfoList->SubMatMass($value, $value3)}
                                {$submat_uom =  $subMatMass.uom}
                                {$subitem_qty = $subMatMass.qty}
                            
                                {if $subitem_qty>0}
                                    {$subitem_qty = $subitem_qty}                        
                                    <td style="width:45%">{trim($value3.Item)}</td>
                                    <td align="center" style="width:25%">UOM: {$submat_uom}</td>
                                    <td align="center" style="width:30%">Quantity: {$subitem_qty}</td>
                                {/if}                        
                            <!-- For Material Material Fix for Qty, UOM - Ends -->
                            </tr>                      
                        {/if}
                    {/foreach} 
                    {if $mat_table=="opened"}
                        </table>
                    {/if}
					{/capture}
                {/if}

				{$material_details_table}
			</td>
            {if $lineitem_rows==1}
                <td align="center" style="border:solid 1px #ccc; border-right:none; border-top:none; border-bottom:dashed 1px #ccc;"><b>{$value.uom}</b></td>
                <td align="center" style="border:solid 1px #ccc; border-top:none; border-bottom:dashed 1px #ccc;"><b>{$value.pgen_qty}</b></td>
			{if $show_price==1}
                <td align="right" style="border-bottom:dashed 1px #ccc;">
                	<b>$ {number_format((float)$install_total,2)}</b>
                </td>
				{$installer_grandtotal = $installer_grandtotal+$install_total}
			{/if}
            {/if}
		</tr>
        {if isset($order_subquery)}
            {foreach from=$order_subquery key=key2 item=value2}
                {if trim($pricing_model) == trim($value2.pricingmodel)}
            <tr style="border:dashed 1px #ccc; {$highlight_oi_changes}">
    
                {$subLabMass = $IOInfoList->SubLabMass($value, $value2)}
                {$lab_uom =  $subLabMass.uom}
                {$lab_qty = $subLabMass.qty}
                {$lab_cost = $lab_qty*$value2.cost}
        
                {if $lab_qty>0}
                    <td align="left" style="font-weight:bold; border:dashed 1px #ccc; border-left:none; border-top: none;">{trim($value2.category)} - {trim($value2.labor_code)} - {trim($value2.description)} </td>
                    <td align="center" style="font-weight:bold; border:dashed 1px #ccc; border-left:none; border-top: none;"> {$lab_uom} </td>
                    <td align="center" style="font-weight:bold; border:dashed 1px #ccc; border-left:none; border-top: none;"> {number_format($lab_qty, 2)} </td>
		{if $show_price==1}
                    <td align="right" style="font-weight:bold; border-bottom:dashed 1px #ccc;">$ {number_format($lab_cost, 2)}</td>
                    {$installer_grandtotal = $installer_grandtotal+$lab_cost}
		{/if}
                {/if}
            </tr>   
                {/if}
            {/foreach}
        {/if}
	{/foreach}
{/if}



		<!-- Misc item display starts -->
		{if $misc_count > 0}
			<tr bgcolor="#F0F0F0">
				<td colspan="{5+$show_price}"><b>Miscellaneous items</b></td>
			</tr>
            {foreach from = $misc_result key=key item=value}
            {$highlight_mi_changes=""}
            {if $value.by_ip=='1' && $value.is_approved=='1'}
                {$highlight_mi_changes = 'style="background:#fefed0;"'}
                {$installer_moditems_cnt = $installer_moditems_cnt+1}
            {/if}
            <tr {$highlight_mi_changes}>
                <td align="center" style="border:dashed 1px #ccc; border-top:none; border-left:none;">{$key+1}</td>
                <td style="border:dashed 1px #ccc; border-top:none; border-left:none;">{$value.item_name}</td>
                <td style="border:dashed 1px #ccc; border-top:none; border-left:none;"><b>{$value.vendor}</b> - {$value.description}</td>
                <td align="center" style="border:dashed 1px #ccc; border-top:none; border-left:none;">{$value.uom}</td>
                <td align="center" style="border:dashed 1px #ccc; border-top:none; border-left:none;">{$value.qty}</td>
		{if $show_price==1}
                <td align="right" style="border-bottom:dashed 1px #ccc;">$ {number_format($value.qty*$value.labor, 2)}</td>
		{/if}
            </tr>
			{$installer_grandtotal = $installer_grandtotal+$value.qty*$value.labor}
            {/foreach}	
		{/if}
		<!-- Misc item display ends -->

	<!-- Installer Damaged item display - starts -->
        {if $damage_installer_result != ""}
            <tr bgcolor="#F0F0F0"><td colspan="6"><b>Installer Damaged Items</b></td></tr>
        {/if}

        {$damage_ct=0}
        {if $damage_count > 0}
            {foreach from = $damage_installer_result key=key1 item=valued}            
                <tr>
                    <td align="center"> {1+$damage_ct++}</td>
                    <td>{$valued.pricingmodel} </td>
                    <td>{$valued.item}</td>
                    <td align="center">{trim($valued.uom)}</td>
                    <td align="center">{$valued.qty}</td>
		{if $show_price==1}
                    <td align="right">- $ {number_format($valued.qty*$valued.cost,2)}</td>
		{/if}
                </tr>
                {$installer_grandtotal = $installer_grandtotal-$valued.qty*$valued.cost}
            {/foreach}
        {/if}
	<!-- Installer Damaged item display - ends -->

		<!-- LeadTest and LFWP fee - starts -->
		{if $order_result.lt_amt!=0 || $order_result.lfwp_amt!=0}
			{$i=1}
            <tr bgcolor="#f9f9f9">
                <td colspan="{5+$show_price}"><b>Others</b></td>
            </tr>
			{if $order_result.lt_amt=0}
            <tr>
                <td align="center" style="border-bottom:dashed 1px #ccc;">{$i++}</td>
                <td align="left" colspan="2" style="font-weight:bold; border-bottom:dashed 1px #ccc;">Lead Test</td>
                <td align="center" style="font-weight:bold; border-bottom:dashed 1px #ccc;"> EA </td>
                <td align="center" style="font-weight:bold; border-bottom:dashed 1px #ccc;"> 1 </td>
		{if $show_price==1}
                <td align="right" style="font-weight:bold; border-bottom:dashed 1px #ccc;">$ {number_format((float)$order_result.lt_amt, 2)}</td>
		{/if}
            </tr>
			{/if}
			{if $order_result.lfwp_amt!=0}
            <tr>	
                <td align="center" style="border:dashed 1px #ccc; border-top:none; border-left:none;">{$i++}</td>
                <td style="font-weight:bold; border:dashed 1px #ccc; border-top:none; border-left:none;" align="left" colspan="2">Lead Free Work Practice</td>
                <td align="center" style="font-weight:bold; border:dashed 1px #ccc; border-top:none; border-left:none;"> EA </td>
                <td align="center" style="font-weight:bold; border:dashed 1px #ccc; border-top:none; border-left:none;"> 1 </td>
			{if $show_price==1}
                <td align="right" style="font-weight:bold; border-bottom:dashed 1px #ccc;">$ {number_format((float)$order_result.lfwp_amt, 2)}</td>
			{/if}
            </tr>
			{/if}
            {$installer_grandtotal = $installer_grandtotal+$order_result.lt_amt+$order_result.lfwp_amt}
        {/if}
		<!-- LeadTest and LFWP fee - ends -->

        <tr>
            <td align="center" style="border:dashed 1px #ccc; border-top:none; border-left:none;">{$i++}</td>
            <td colspan="2" style="border:dashed 1px #ccc; border-top:none; border-left:none;"><b>Permit Fee</b></td>
            <td align="center" style="font-weight:bold; border:dashed 1px #ccc; border-top:none; border-left:none;"> EA </td>
            <td align="center" style="font-weight:bold; border:dashed 1px #ccc; border-top:none; border-left:none;"> 1 </td>
		{if $show_price==1}
            <td style="border-left:0; border-right:0;" align="right">
				{$promo_amount = $order_result.promo_amt}
{$permit_amount = ($order_result.total_amount+$order_result.admin_fee+$order_result.lt_amt+$order_result.lfwp_amt-$promo_amount)*$order_result.permit_percent/100}
                {if $permit_amount>$order_result.permit_max} {$permit_amount=$order_result.permit_max} {/if}
                $ {number_format($permit_amount, 2)}
			</td>
		{/if}
        </tr>
		{if $show_price==1}
        <tr bgcolor="#f9f9f9">
            <td colspan="5" align="right" style="border-right:0;"><b>Total Cost &nbsp;:</b></td>
            <td style="border-left:0; border-right:0;" align="right">
				<b>$ {number_format($installer_grandtotal+$permit_amount, 2)}</b>
			</td>
        </tr>
		{/if}
</table>
