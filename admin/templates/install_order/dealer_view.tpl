	{$i=1}
	{$uom_except_arr = ["SF"=>"squarefeet", "LF"=>"linealfeet", "EA"=>""]}
    {$manfacturer_dup = ""}
	{$installer_email = $order_result.installer_email}
    {$installer_grandtotal = 0}
		
	<table class="orders_wrapper" cellspacing="0" id="material_order_table" name="material_order_table">
		<tr style="border:solid 1px #ccc;">
			<td colspan="7">
				ORDER ID : <b>{$order_result.order_id}</b>
                | JOB ID : <b>{$order_result.jobid} {if $order_result.jobid==""} - {/if}</b>
                <span style="float:right;" id="jobid">Sent On : <b>{if $order_result.sent_installer==""} - {else} {$order_result.sent_installer|date_format} {/if}</b></span>
{*
                <span style="float:right;" id="jobid">JOB ID : <b>{$order_result.jobid} {if $order_result.jobid==""} - {/if}</b></span>
*}
			</td>
		</tr>
		{$j=1}

		<input type="hidden" name="installer_sent" id="installer_sent" value="{$order_result.sent_installer}" />
        
		<tr style="border:solid 1px #ccc;">
		<td colspan="7">
					<table class="customer_info" cellpadding="5" style="width:45%; float:left; border-collapse:collapse; border:solid 1px #ccc;">
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
					<table class="customer_info" cellpadding="5" style="width:45%; float:right; border-collapse:collapse; border:solid 1px #ccc;">
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
{if $order_result.installer_firstname=="" && $order_result.installer_lastname=="" && $installer_email==""}
		<tr>
			<td align="center" colspan="7"><b>Installer not assigned</b><br />

choose an installer/store for the branch on material order tab
to proceed with this install order.</td>
		</tr>
{else}
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
			<td width="10%" align="center" class="rm_price">
				<b>Labor Cost</b>
			</td>
            <td align="center" width="6%" align="center">
                <img src="{$root}../images/actions/action-settings.png" width="30" />
            </td>
		</tr>
{if $products_result gt 0}
		{foreach from=$products_result key=key item=value}
            {assign var="product_sku" value=trim("`$value.remode_orderitem_pricingmodel`")}
            {assign var="install_total" value="`$value.laborcost`"}
            {assign var="pricing_model" value=trim("`$value.remode_orderitem_pricingmodel`")}
            {assign var="lineitem_rows" value=1}
            {if isset($order_subitems_count[$pricing_model])}
	            {assign var="lineitem_rows" value=$order_subitems_count[trim($pricing_model)]+1}
            {/if}

			{$i=1}

    {$highlight_oi_changes=""}
    {if $value.by_ip=='1' && $value.is_approved=='1'}
        {$highlight_oi_changes = 'background:#fefed0;'}
        {$installer_moditems_cnt = $installer_moditems_cnt+1}
    {/if}

<form name="orderitem_update" class="orderitem_update" method="post" onsubmit="return validate_update('row{$key}');">
		<tr style="border:dashed 1px #ccc; {$highlight_oi_changes}">
			<td align="center" rowspan="{$lineitem_rows}">
				{$i++}
			</td>
			<td rowspan="{$lineitem_rows}">
				{$product_sku} {if $product_sku|trim==""} - {/if}
			</td>


			<td {if $lineitem_rows>1} colspan="4" {/if} class="non_editable_row">

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
                                <table class="customer_info" cellpadding="5" style="font-weight:normal; margin:5px 0; width:100%; border:dashed 1px #ccc;">
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
                <td align="center" class="non_editable_row"><b>{$value.uom}</b></td>
                <td align="center" class="non_editable_row"><b>{$value.pgen_qty}</b></td>
                <td align="right" class="non_editable_row rm_price"><b>$ {number_format((float)$install_total,2)}</b></td> 
				{$installer_grandtotal = $installer_grandtotal+$install_total}
            {/if}



			<td class="editable_row" style="font-weight:bold;">
				{$value.description}

       			{assign var="enable_edit" value=false}

                {if $value.cat_id!=6}
                    <input name="ext_options" value="{$value.options}" type="hidden" />
					{foreach $catoptions[$value.cat_id] as $catopt}
						{strbwstrobj spattern=$catopt.db_name epattern=$catopt.db_name symbol="#" subject=$value.minmax var="minmax"}
                        {strbwstrs spattern=$catopt.db_name epattern=$catopt.db_name symbol="#" subject=$value.options}
                        {* ----- To hide uom fields in options ----- *}
						{if isset($matches.1) && $uom_except_arr[$value.uom]!=$catopt.db_name}
                            <br/> {$catopt.name|upper}: 
                            {if in_array($catopt.db_name, $opt_arr) && $minmax.1!=""}
                                {$enable_edit=true}

                                <input name="opt_desc[{$catopt.db_name}]" value="{$catopt.name}" type="hidden" />
                                <input name="ext_data[{$catopt.db_name}]" value="{$matches.1}" type="hidden" />
                                <input name="new_data[{$catopt.db_name}]" value="{$matches.1}" type="text" class="ipeditable" data-minmax="{$minmax.1}" data-optnm="{$catopt.name}" data-row="row{$key}" />
                            {else}
                                {$matches.1}
                            {/if}
                        {/if}
                    {/foreach}
                {/if}
                
				{$material_details_table}
			</td>
            <td align="center" class="editable_row" colspan=3><b>{$value.uom}</b> 
                <input type="hidden" name="orderitem[id]" value="{$value.orderitems_id}"/>
                <input type="hidden" name="orderitem[job_order_id]" value="{$order_result.job_order_id}"/>
                <input type="hidden" name="orderitem[pricingmodel]" value="{$product_sku}"/>
                <input type="hidden" name="orderitem[cat_id]" value="{$value.cat_id}"/>
                <input type="hidden" name="ext_qsf" value="{$value.old_qsf}" />
                <input type="hidden" name="ext_uom" value="{$value.uom}" />
                <input type="hidden" name="ext_quantity" value="{$value.pgen_qty}" />
{if $value.uom == "SF" || $value.uom == "LF" || $value.cat_id==6}
                <input type="text" name="new_quantity" value="{$value.quantity*$value.slfeet}" maxlength="3" class="ipeditable_qty" />
{else}
                <input type="text" name="new_quantity" value="{$value.quantity}" class="ipeditable_qty ipunedit_qty" readonly />
{/if}
            </td>



            <td rowspan="{$lineitem_rows}" style="text-align:center;">
            {if $enable_edit || $value.uom!="EA" || $value.cat_id==6}
                <button type="button" name="change_qty" id="change_qty" class="change_qty" data-isvalid="">Change</button>
            {else}
	            <b>-</b>
            {/if}
                <button type="submit" name="update_qty" id="update_qty" class="update_qty" style="display:none;" value="{$j}">Update</button>
            {if $value.cat_id==6}
	            <br /><br />
                <button type="submit" name="delete_adnlitem" id="delete_adnlitem" class="delete_adnlitem" style="display:none;" value="{$value.orderitems_id}">Delete</button>
			{/if}

            </td>
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
                    <td style="font-weight:bold;" align="left">
                    	{if trim($value2.category)} {trim($value2.category)} - {/if}
                        {trim($value2.labor_code)}
                        {if trim($value2.description)} - {trim($value2.description)}{/if}
                    </td>
                    <td style="font-weight:bold;" align="center"> {$lab_uom} </td>
                    <td style="font-weight:bold;" align="center"> {number_format($lab_qty, 2)} </td>
                    <td style="font-weight:bold;" align="right">$ {number_format($lab_cost, 2)}</td>
                    {$installer_grandtotal = $installer_grandtotal+$lab_cost}
                {/if}
            </tr>   
                {/if}
            {/foreach}
        {/if}
</form>

		{$count_of_result = count($order_result)}
		{$total = $j++}
	{/foreach}
{/if}

		<!-- LeadTest and LFWP fee - starts -->
		{if $order_result.lt_amt!=0 || $order_result.lfwp_amt!=0}
			{$i=1}
            <tr bgcolor="#f9f9f9" style="border:solid 1px #ddd;">
                <td colspan="7"><b>Lead Fee</b></td>
            </tr>
			{if $order_result.lt_amt!=0}
            <tr style="border:dashed 1px #ccc;">	
                <td align="left">{$i++}</td>
                <td style="font-weight:bold;" align="left" colspan="2">Lead Test</td>
                <td style="font-weight:bold;" align="center"> EA </td>
                <td style="font-weight:bold;" align="center"> 1 </td>
                <td style="font-weight:bold;" align="right">$ {number_format((float)$order_result.lt_amt, 2)}</td>
				{if $order_result.lfwp_amt!=0}
					<td rowspan="2">&nbsp;</td>
				{else}
					<td>&nbsp;</td>
				{/if}
            </tr>
			{/if}
			{if $order_result.lfwp_amt!=0}
            <tr style="border:dashed 1px #ccc;">	
                <td align="left">{$i}</td>
                <td style="font-weight:bold;" align="left" colspan="2">Lead Free Work Practice</td>
                <td style="font-weight:bold;" align="center"> EA </td>
                <td style="font-weight:bold;" align="center"> 1 </td>
                <td style="font-weight:bold;" align="right">$ {number_format((float)$order_result.lfwp_amt, 2)}</td>
            </tr>
			{/if}
            {$installer_grandtotal = $installer_grandtotal+$order_result.lt_amt+$order_result.lfwp_amt}
        {/if}
		<!-- LeadTest and LFWP fee - ends -->


		<!-- Misc item display starts -->
		{if $misc_count > 0}
			<tr bgcolor="#F0F0F0">
				<td colspan="7"><b>Miscellaneous items</b></td>
			</tr>
            {foreach from = $misc_result key=key item=value}
            {$highlight_mi_changes=""}
            {if $value.by_ip=='1' && $value.is_approved=='1'}
                {$highlight_mi_changes = 'style="background:#fefed0;"'}
                {$installer_moditems_cnt = $installer_moditems_cnt+1}
            {/if}
            <tr {$highlight_mi_changes}>
                <td>{$key+1}</td>
                <td>{$value.item_name}</td>
                <td><b>{$value.vendor}</b> - {$value.description}</td>
                <td align="center">{$value.uom}</td>
                <td align="center">{$value.qty}</td>
                <td align="right">$ {number_format($value.qty*$value.labor, 2)}</td>
                <td align="center">
	                <input type="button" class="edit_misc" value="Edit" />
                    <input type="hidden" name="misc_itemid" class="misc_itemid" value="{$value.id}"/>
                </td>
            </tr>
			{$installer_grandtotal = $installer_grandtotal+$value.qty*$value.labor}
            {/foreach}	
		{/if}
		<!-- Misc item display ends -->

	<!-- Installer Damaged item display - starts -->
        {if $damage_installer_result != ""}
            <tr bgcolor="#F0F0F0" class="each_damage_table"><td colspan="7"><b>Installer Damaged Items</b></td></tr>
        {/if}

        {$damage_ct=0}
        {if $damage_count > 0}
            {foreach from = $damage_installer_result key=key1 item=valued}            
                <tr class="each_damage_table">
                    <td align="center"> {1+$damage_ct++}</td>
                    <td>{$valued.pricingmodel} </td>
                    <td>{$valued.item}</td>
                    <td align="center">{trim($valued.uom)}</td>
                    <td align="center">{$valued.qty}</td>
                    <td align="right">- $ {number_format($valued.qty*$valued.cost,2)}</td>
                    <td align="center">&nbsp;</td>
                </tr>
                {$installer_grandtotal = $installer_grandtotal-$valued.qty*$valued.cost}
            {/foreach}
        {/if}
	<!-- Installer Damaged item display - ends -->



        <tr>
            <td colspan="5" align="right" style="border-right:0;"><b>Permit &nbsp;:</b></td>
            <td style="border-left:0; border-right:0;" align="right">
				{$promo_amount = $order_result.promo_amt}
{$permit_amount = ($order_result.total_amount+$order_result.admin_fee+$order_result.lt_amt+$order_result.lfwp_amt-$promo_amount)*$order_result.permit_percent/100}
                {if $permit_amount>$order_result.permit_max} {$permit_amount=$order_result.permit_max} {/if}
                $ {number_format($permit_amount, 2)}
			</td>
            <td style="border-left:0;">&nbsp;</td>
        </tr>

        <tr bgcolor="#f9f9f9">
            <td colspan="5" align="right" style="border-right:0;"><b>Total Cost &nbsp;:</b></td>
            <td style="border-left:0; border-right:0;" align="right">
				<b>$ {number_format($installer_grandtotal+$permit_amount, 2)}</b>
			</td>
            <td style="border-left:0;">&nbsp;</td>
        </tr>

        {if $order_result.order_attachments!=""}
             <tr bgcolor="#F0F0F0">
                <td colspan="7">
                    <b>Sales Rep uploaded files</b>
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    {$attached_files= ","|explode:$order_result.order_attachments}
                    <ul style="width:100%; display:block;">
                    {foreach $attached_files as $key=>$attachments}
						{if $attachments|trim!=""}
                        <li style="display:inline-block; margin:0 15px 10px 0;"><b style="color:#F88421; border:solid 1px; padding:2px 6px; border-radius:15px;">{$key+1}</b> <a class="preview_file" href="../uploads/{$attachments}" target="_blank" style="text-decoration: underline;">{$attachments}</a></li>
						{/if}
                    {/foreach}
                    </ul>
                </td>
            </tr>
        {/if}


		{if $order_result.order_status=='wtg-approval' || $order_result.order_status=='install-problem' || (count($products_result)>0 && in_array($order_result.order_status, $unedit_ord) && $order_result.customer_name != "")}
            <tr class="s_tr print_order">
            <td colspan="7">
            <center>
            <form name="rectify_ip" method="post">
                <input type="hidden" name="sys_order_id" id="sys_order_id" value="{$order_result.job_order_id}" />
                <input type="hidden" name="order_id" id="order_id" value="{$order_result.order_id}" />
                <input type="button" name="print" id="print" class="print adminbtn" value="Print" onclick="print_content();" />

				{if strpos($order_result.export_access, "AIO")!==false}
                <input type="button" id="export_order_todata" class="adminbtn" value="Export" />
				{/if}

                <input type="button" name="sendmail" id="sendmail" class="sendmail adminbtn" value="Send Mail" />
                {if $order_result.order_status!='revision' && $order_result.order_status!='canceled'}
                {* <input type="button" id="add_misc" class="adminbtn" value="Add Miscs Item" /> *}
                <input type="button" name="placeorder" id="placeorder" class="placeorder adminbtn active" value="Place Order" />
                {/if}
                
                {if $order_result.order_status=='install-problem'}
                <input type="submit" name="resolved_ip" class="adminbtn" value="Resolved IP" />
                {else if $order_result.order_status=='wtg-approval'}
                    {if $installer_moditems_cnt==0}
                    <input type="hidden" id="installer_moditems_cnt" value="{$installer_moditems_cnt}" />
                    <input type="hidden" name="installer_username" value="{$order_result.on_change}" />
                    <input type="submit" name="notify_changes_refused" id="notify_changes_refused" class="adminbtn" value="Notify Changes Declined" />
                    {else}
                    <input type="hidden" name="installer_username" value="{$order_result.on_change}" />
                    <input type="submit" name="approve_inschanges" id="approve_inschanges" class="adminbtn" value="Approve Order Changes" />
                    {/if}
                {/if}
            </form>
			</center>
			</td>
			</tr>
		{/if}                
	{/if}
	</table>
