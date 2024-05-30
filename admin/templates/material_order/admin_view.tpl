
<style>
.edit_item{
	background:url(../images/actions/edit.png) no-repeat top center;
	border:0;
	text-indent:100px;
	overflow:hidden;
	width:25px;
	height:25px;
	cursor:pointer;
}
.edit_item:hover{
	background:url(../images/actions/edit.png) no-repeat bottom center;
}
</style>

	<table class="orders_wrapper" cellspacing="0" id="material_order_table" name="material_order_table" style="margin-bottom:0; border:solid 1px #b4b4b4; border-bottom:none;">
			<tr style="border:solid 1px #ccc;" id="order_header">
				<td colspan="6">
					ORDER ID : <b>{$order_result.order_id}</b>
	                <span style="float:right;">JOB ID : <b>{$order_result.jobid} {if $order_result.jobid==""} - {/if}</b></span>
				</td>
			</tr>

			<tr id="vendor_tabs" class="print_order">
			<td style="width:100%;" colspan="4">
			<ul>
			{$tab_nav=0}
			{$manfacturer_navdup=""}
			{$j=1}

			{foreach from=$mat_mfg_arr key=key item=value}
				{$manufacutur_active = ""}
				{if $tab_nav==0}
					{$manufacutur_active = " manufacutur_active"}
				{/if}

		        {if $order_result.sent_material|strstr:$value.0}
			        {assign var="matord_sent" value="sent_order"}
		        {else}
			        {assign var="matord_sent" value=""}
		        {/if}
		        
				{if $manfacturer_navdup != $value.0}
				<li class="tab{$tab_nav++} each_vendor {$manufacutur_active} {$matord_sent}">
                	<b data-alias="{$value.0}">{$value.1}</b>
{*
                	<b data-alias="{if $value.1=="UNKNOWN"}{$value.1}{else}{$value.0}{/if}">{$value.1}</b>
*}
                </li>
				{/if}
				{$manfacturer_navdup = $value.0}
			{/foreach}

			</ul>
			</td>
			</tr>
            <tr>
				<td colspan="7" width="100%" style="border-top:0; border-bottom:0;">
                <table class="customer_info" cellpadding="5" style="width:45%; float:left; border-collapse:collapse; border:solid 1px #ccc;">
                    <tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
                        <td colspan="2"><b>Customer Details</b></td>
                    </tr>
                    <tr>
                        <td width="25%"><b>Name</b></td>
                        <td width="75%">
                            {$order_result.customer_name}
                            {$customer_name = $order_result.customer_name}
                        </td>
                    </tr>
                </table>

                <table class="customer_info s_tr" cellpadding="5" style="width:45%; float:right; border-collapse:collapse; border:solid 1px #ccc;">
                    <tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
                         <td><b>Ship To (pick installer by)</b></td>
                    </tr>
                    <tr>
                        {if $mat_mfg_arr.0=="CSD"}
                            {$hide_cds_shipto = [" display:none;", " display:block;"]}
                        {else}
                            {$hide_cds_shipto = [" display:block;", " display:none;"]}
                        {/if}
                        <td id="shipto_pick_show" style="border:none;{$hide_cds_shipto.0}">
                            {$disabled = true}
                            {if in_array($smarty.session[$APPSESVAR|cat:"_adminuser"].role, ["superadmin","divisionalmanager"])}
                                {$disabled = false}
                            {/if}
							{$ship_to_array = ["branch"=>"Branch", "installer"=>"Installer"]}
                            <select name="ship_to" id="ship_to">
                            	{foreach $ship_to_array as $stkey=>$stvalue}
	                                <option value="{$stkey}" {if $order_result.ins_mode==$stkey} selected="selected"{/if} {if $stkey=="installer" && $disabled} disabled="disabled"{/if}>{$stvalue}</option>
								{/foreach}
                            </select>
    
                            <input type="hidden" name="branch_id" id="branch_id" value="{$order_result.branchid}"/>
                            <input type="hidden" name="store_id_exists" id="store_id_exists" value="{$order_result.store_num}"/>
                            <input type="hidden" name="installer_id_exists" id="installer_id_exists" data-instype="{$order_result.ins_type}" value="{$order_result.installer_id}"/>
    
                            <select name="store_select" id="store_select" class="store_select" style="display:none;" {$disabled}></select>
                        </td>
                        <td id="shipto_pick_hide" style="border:none;{$hide_cds_shipto.1}">- N/A -</td>
                    </tr>
                </table>
                </td>
            </tr>
	</table>

	<table class="orders_wrapper" cellspacing="0" id="material_order_table" name="material_order_table" style="margin:0 auto; border-top:0;">

    {$prd_material_total = []}
    {$misc_material_total = []}

	{$uom_except_arr = ["SF"=>"squarefeet", "LF"=>"linealfeet", "EA"=>""]}
    {$manfacturer_dup=""}
	{$norepeat_manufacturer =""}
    {$j=1}
    {$item_no=1}
    {$r=1}
    {$tab=0}
	{$tabct=0}
	{$count_check = 1}
{if !empty($products_result)}
    {foreach from=$products_result key=key item=value}
        {assign var="pricing_model" value=trim("`$value.remode_orderitem_pricingmodel`")}
        {assign var="material_total" value="`$value.productcost`"}
        {assign var="lineitem_rows" value=1}
        {if isset($order_subitems_count[trim($pricing_model)])}
            {assign var="lineitem_rows" value=$order_subitems_count[trim($pricing_model)]+1}
        {/if}

        {if $manfacturer_dup != $value.manufacturer_alias}
			{if !isset($prd_material_total[$value.manufacturer_alias])}{$prd_material_total[$value.manufacturer_alias] = 0}{/if}
			{if $item_no!=1}
			    {$item_no=1}
				{$tab = $tab+1}
			{/if}

		<tr class="tab{$tab} each_vendor_table">
			<td colspan="7" width="100%" style="border-top:0;">
                <table class="customer_info" cellpadding="5" style="width:45%; float:left; border-collapse:collapse; border:solid 1px #ccc; clear:both;">
                    <tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
                        <td colspan="2"><b>Ship To Address</b></td>
                    </tr>
					{$ship_to_info = $MOInfoList->GetOrdHeaderData($order_result, $value, "ship_to")}
                    <tr style="border:dashed 1px #ccc;">
                        <td width="25%"><b>Company</b></td>
                        <td width="75%" class="shipto_inst_name">
                            {$ship_to_info.company}
                        </td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td width="25%"><b>Name</b></td>
                        <td width="75%" class="shipto_inst_contact">
                            {$ship_to_info.name}
                        </td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td><b>Email</b></td>
                        <td class="shipto_inst_email">{$ship_to_info.email}</td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td><b>Phone</b></td>
                        <td class="shipto_inst_phone">{$ship_to_info.phone}</td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td><b>Address</b></td>
                        <td class="shipto_inst_address">
                            {$ship_to_info.address}
                        </td>
                    </tr>
                </table>

                <table class="customer_info" cellpadding="5" style="width:45%; float:right; border-collapse:collapse; border:solid 1px #ccc;">
                    <tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
                        <td colspan="2"><b>Vendor Details</b></td>
                    </tr>

					{$vendor_info = $MOInfoList->GetOrdHeaderData($order_result, $value, "vendor")}

                    <tr style="border:dashed 1px #ccc;">
                        <td width="25%"><b>Company</b></td>
                        <td class="vendor_company" style="display:none;">{$vendor_info.company}</td>
						<td width="75%">{$vendor_info.detail}</td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td width="25%"><b>Name</b></td>
                        <td width="75%" class="vendor_name">{$vendor_info.name}</td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td><b>Email</b></td>
                        <td class="vendor_email">{$vendor_info.email}</td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td><b>Phone</b></td>
                        <td class="vendor_phone">{$vendor_info.phone}</td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td><b>Address</b></td>
                        <td class="vendor_address">{$vendor_info.address}</td>
                    </tr>
                </table>
			</td>
	</tr>

	<tr id="item_title" class="tab{$tab} each_vendor_table" bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
		<td width="3%" align="center"><b>#</b></td>
		<td width="15%"><b>Model #</b></td>
		<td width="50%"><b>Description</b></td>
		<td align="center" width="8%" align="center"><b>UOM</b></td>
		<td align="center" width="7%" align="center"><b>Qty</b></td>
		<td align="center" width="10%" align="center"><b>Product Cost</b></td>
		<td class="s_tr" align="center" width="6%" align="center">
	        <img src="{$root}../images/actions/action-settings.png" width="30" />
		</td>
	</tr>
	{/if}


<form name="orderitem_update" class="orderitem_update" method="post" onsubmit="return validate_update('row{$key}');">

		<tr class="tab{$tab} each_vendor_table" style="border:dashed 1px #ccc;">
            <td align="center" rowspan="{$lineitem_rows}">
                <input type="hidden" class="manufacturer_emailcc"  value= "{$value.manufacturer_emailcc}"/>
				{if $lineitem_rows == "1"}
					<input type="checkbox" name="select_single_item{$count_check}" class="select_single_item s_tr" id="select_single_item{$count_check++}" value="{$value.orderitems_id}" style="display:none;"/>
				{/if}
				{$item_no++}
			</td>


			<td rowspan="{$lineitem_rows}">{$pricing_model} {if $pricing_model|trim==""}-{/if}</td>


			<td {if $lineitem_rows > 1} colspan="4" {/if} class="non_editable_row">
				{if $lineitem_rows > 1}
					{$value.description}
				{else}
					<b>{$value.description}</b>
                {/if}

				{$options_build = []}
                {if !empty($value.prd_options)}
					{foreach $value.prd_options as $k=>$v}
						{if trim($v)!=""}
							{$options_build[] = ucwords("`$k`: `$v`")}
						{/if}
					{/foreach}
					{if !empty($options_build)}
						<br/>{implode(" | ", $options_build)}
					{/if}
				{/if}
                {if $value.comments!=""}<br/>Comments: {$value.comments}{/if}

				{if $lineitem_rows>1}
                    <span style="float:right; font-weight:normal;">[ UOM:{$value.uom}, QTY: {$value.pgen_matqty} ]</span>
                {/if}
            </td>
            {if $lineitem_rows==1}
                <td align="center" class="non_editable_row"><b>{$value.uom}</b></td>
                <td align="center" class="non_editable_row"><b>{$value.pgen_matqty}</b></td>
                <td align="right" class="non_editable_row"><b>$ {number_format($material_total,2)}</b> &nbsp;</td>
				{$prd_material_total[$value.manufacturer_alias] = $prd_material_total[$value.manufacturer_alias]+$material_total}
            {/if}

            
			<td class="editable_row" {if $lineitem_rows==1} style="font-weight:bold;" {/if}>
				{$value.description}

				{assign var="enable_edit" value=false}
                {if $value.cat_id!=6}
                    <input name="ext_options" value="{$value.options}" type="hidden" />
                    {foreach $catoptions[$value.cat_id] as $catopt}
						{strbwstrobj spattern=$catopt.db_name epattern=$catopt.db_name symbol="#" subject=$value.minmax var="minmax"}
						{strbwstrs spattern=$catopt.db_name epattern=$catopt.db_name symbol="#" subject=$value.options}

                        {if isset($matches.1) && $uom_except_arr[$value.uom]!=$catopt.db_name}
							<br/> {$catopt.name|upper}:
                                {if in_array($catopt.db_name, $opt_arr) && $minmax[1]!=""}
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
            </td>


            <td colspan="3" align="center" class="editable_row input_quantity" style="border-left:0; border-right:0;">
		            <b>{$value.uom}</b> 
                    <input type="hidden" name="orderitem[id]" value="{$value.orderitems_id}"/>
                    <input type="hidden" name="orderitem[job_order_id]" value="{$order_result.job_order_id}"/>
                    <input type="hidden" name="orderitem[pricingmodel]" value="{$pricing_model}"/>
                    <input type="hidden" name="orderitem[cat_id]" value="{$value.cat_id}"/>
                    <input type="hidden" name="ext_uom" value="{$value.uom}" />
                    <input type="hidden" name="ext_quantity" value="{$value.pgen_qty}" />

			{if $value.uom == "SF" || $value.uom == "LF"}
                <input type="text" name="new_quantity" value="{$value.pgen_qty}" maxlength="3" class="ipeditable_qty" />
			{else}
                <input type="text" name="new_quantity" value="{$value.pgen_qty}" class="ipeditable_qty ipunedit_qty" readonly />
			{/if}
			</td>

            <td class="s_tr" rowspan="{$lineitem_rows}" style="text-align:center;">


	        {if !$order_result.sent_material|strstr:$value.manufacturer_alias}
	            <button class="edit_item" data-mat_id="{$value.manufacturer_alias}">edit</button>
{*
            {else if $enable_edit || $value.uom!="EA"}
                <button type="button" name="change_qty" id="change_qty" class="change_qty" data-isvalid="">Change</button>
*}
            {else}
	            <b>-</b>
            {/if}
                <button type="submit" name="update_qty" id="update_qty" class="update_qty" style="display:none;" value="{$j}">Update</button>
            </td>           
       </tr>

        {foreach from=$order_subquery key=key2 item=value2}

			{if trim($value.remode_orderitem_pricingmodel) == trim($value2.pricingmodel)}

                {$subMatMass = $MOInfoList->SubMatMass($value, $value2)}
				{$submat_uom =  $subMatMass.uom}
                {$subitem_qty = $subMatMass.qty}
                {$subitem_cost = $subMatMass.cost}
    
            <tr class="tab{$tab} each_vendor_table">

            {if $subitem_qty>0}
                <td>
                    <input type="checkbox" name="select_item{$count_check}" class="select_item s_tr" id="select_item{$count_check++}" value="{$value.orderitems_id}_{$value2.id}_{$submat_uom}-{$subitem_qty}-{$subitem_cost}" style="display:none;"/>
                    <b>{trim($value2.Item)}</b>
                </td>
                <td align="center"> <b>{$submat_uom}</b> </td>
                <td align="center"><b>{$subitem_qty}</b> </td>
                <td align="right" data-oldcost="{number_format($subitem_cost, 2)}"><b>$ {number_format($subitem_cost, 2)}</b> &nbsp;</td>
            {/if}

			</tr>       
	{$prd_material_total[$value.manufacturer_alias] = $prd_material_total[$value.manufacturer_alias]+$subitem_cost}
         {/if}
        {/foreach}
</form>
		
		{$manfacturer_dup = $value.manufacturer_alias} 
    {/foreach}
{/if}
	{$no_duplicate_manufa = ""}
	
    {foreach from=$mat_mfg_arr key=key item=value}
	{if $no_duplicate_manufa!=$value.0}
		{if !isset($misc_material_total[$value.0])}{$misc_material_total[$value.0] = 0}{/if}	
	<!-- Misc item display starts -->
		
		{$mitem_ct=0}
		{if $misc_count > 0}
		{foreach from=$misc_result key=key1 item=valuem}
		{if $valuem.vendor == $value.0}
		
			{if $mitem_ct==0}

{if !in_array($valuem.vendor, $mat_product_mfg)}
		<tr class="tab{$tabct} each_vendor_table">


			<td colspan="7" width="100%" style="border-top:0;">
                <table class="customer_info" cellpadding="5" style="width:45%; float:left; border-collapse:collapse; border:solid 1px #ccc; clear:both;">
                    <tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
                        <td colspan="2"><b>Ship To Address</b></td>
                    </tr>
					{$ship_to_info = $MOInfoList->GetOrdHeaderData($order_result, $valuem, "ship_to")}
                    <tr style="border:dashed 1px #ccc;">
                        <td width="25%"><b>Company</b></td>
                        <td width="75%" class="shipto_inst_name">
                            {$ship_to_info.company}
                        </td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td width="25%"><b>Name</b></td>
                        <td width="75%" class="shipto_inst_contact">
                            {$ship_to_info.name}
                        </td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td><b>Email</b></td>
                        <td class="shipto_inst_email">{$ship_to_info.email}</td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td><b>Phone</b></td>
                        <td class="shipto_inst_phone">{$ship_to_info.phone}</td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td><b>Address</b></td>
                        <td class="shipto_inst_address">
                            {$ship_to_info.address}
                        </td>
                    </tr>
                </table>

                <table class="customer_info" cellpadding="5" style="width:45%; float:right; border-collapse:collapse; border:solid 1px #ccc;">
                    <tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
                        <td colspan="2"><b>Vendor Details</b></td>
                    </tr>

					{$vendor_info = $MOInfoList->GetOrdHeaderData($order_result, $valuem, "vendor")}

                    <tr style="border:dashed 1px #ccc;">
                        <td width="25%"><b>Company</b></td>
                        <td width="75%" class="shipto_inst_name">{$vendor_info.company}</td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td width="25%"><b>Name</b></td>
                        <td width="75%" class="shipto_inst_contact">{$vendor_info.name}</td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td><b>Email</b></td>
                        <td class="shipto_inst_email">{$vendor_info.email}</td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td><b>Phone</b></td>
                        <td class="shipto_inst_phone">{$vendor_info.phone}</td>
                    </tr>
                    <tr style="border:dashed 1px #ccc;">
                        <td><b>Address</b></td>
                        <td class="shipto_inst_address">{$vendor_info.address}</td>
                    </tr>
                </table>
			</td>



	</tr>
	<tr id="item_title" class="tab{$tabct} each_vendor_table" bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
		<td width="3%" align="center"><b>#</b></td>
		<td width="15%"><b>Model #</b></td>
		<td width="50%"><b>Description</b></td>
		<td align="center" width="8%" align="center"><b>UOM</b></td>
		<td align="center" width="7%" align="center"><b>Qty</b></td>
		<td align="center" width="10%" align="center"><b>Product Cost</b></td>
		<td class="s_tr" align="center" width="6%" align="center">
	        <img src="{$root}../images/actions/action-settings.png" width="30" />
		</td>
	</tr>
{/if}



			<tr bgcolor="#F0F0F0" class="tab{$tabct} each_vendor_table">
				<td colspan="7"><b>Miscellaneous items</b></td>
			</tr>
			{/if}
            <tr class="tab{$tabct} each_vendor_table">
                <td align="center">{1+$mitem_ct++}</td>
                <td>{$valuem.item_name}</td>
                <td>{$valuem.category} - {$valuem.description}</td>
                <td align="center">{$valuem.uom}</td>
                <td align="center">{$valuem.qty}</td>
                <td align="center">${number_format($valuem.qty*$valuem.material,2)}</td>
				{$misc_material_total[$valuem.vendor] = $misc_material_total[$valuem.vendor]+$valuem.qty*$valuem.material}
                <td align="center">
	                <input type="button" class="edit_misc" value="Edit" />
                    <input type="hidden" name="misc_itemid" class="misc_itemid" value="{$valuem.id}"/>
                </td>
            </tr>
			{$no_duplicate_manufa = $valuem.vendor}
		{/if}
		
		
		{/foreach}
		{$tabct = $tabct+1}
		{/if}
		<!-- Misc item display ends -->		
		{/if}

	{/foreach}
	


    {$material_grand_total = 0}
    {$tab = 0}
    {foreach from=$mat_mfg_arr item=value}
		{if !isset($prd_material_total[$value.0])} {$prd_material_total[$value.0]=0} {/if}
	    {$material_grand_total = $prd_material_total[$value.0]+$misc_material_total[$value.0]}
        <tr class="tab{$tab++} each_vendor_table" bgcolor="#f9f9f9">
            <td colspan="5" align="right" style="border-right:0;"><b>Total Cost &nbsp;:</b></td>
            <td style="border-left:0; border-right:0;" align="right"><b>$ {number_format($material_grand_total, 2)}</b></td>
            <td style="border-left:0;">&nbsp;</td>
        </tr>
	{/foreach}
	
		{if in_array($order_result.order_status, $unedit_ord) && $order_result.customer_name != ""}
            <tr class="s_tr print_order">
                <td colspan="7" align="center">
	                <input type="hidden" name="sys_order_id" id="sys_order_id" value="{$order_result.job_order_id}" />
                    <input type="button" name="print" id="print" class="print adminbtn" value="Print" />

                    {if strpos($order_result.export_access, "AMO")!==false}
                    <input type="button" id="export_order_todata" class="adminbtn" value="Export" />
                    {/if}

                {$hide_reorder = ''}
				{if $order_result.order_status!='revision' && $order_result.order_status!='canceled'}
                    <input type="button" name="sendmail" id="sendmail" class="sendmail adminbtn" value="Send Mail" />
				{if !$order_result['has_dealer'] || $order_result.dealer_mat_status|date_format}
					{* <input type="button" id="add_misc" class="adminbtn" value="Add Miscs Item" /> *}
                    <input type="button" name="reorder" id="reorder" class="adminbtn reorder" value="Enable Re-Order" {$hide_reorder} />
                    <input type="button" name="place_reorder" id="place_reorder" class="adminbtn placeorder" value="Send Re-Order" style="display:none;" />
                    <input type="button" name="placeorder" id="placeorder" class="placeorder adminbtn active" value="Place Order" />
				{/if}
    	        {/if}
                </td>
            </tr>
		{/if}

    <input type="hidden" name="order_id" id="order_id" value="{$order_result.order_id}">

	</table>
    
<div id="edit_material_item" style="display:none;">
	<fieldset style="width:500px;">
    	<legend style="display:inline !important;"><strong>Change Material Vendor</strong></legend>
        <span style="background: #f7eca3; float:left; padding: 10px 2%; width:96%; text-align: center; display: none;"></span>
	    <form name="switch_mat_item" id="switch_mat_item" method="post">
        <table style="width:100%; margin-top:10px;" cellpadding="5">
            <tr>
                <td colspan="2"><b>Item : </b> <span></span></td>
            </tr>
            <tr>
                <td colspan="2"><b>Description : </b> <span style="display: block; margin: 10px 0;"></span></td>
            </tr>
            <tr>
                <td width="30%"><b>Vendor</b></td>
                <td width="70%">
                	<select id="oitem_mfg" id="oitem_mfg" style="width:60%;">
                        {foreach from=$vendor_query_result item=value}
                            <option value="{$value.vendor}">{$value.vendor}</option>
                        {/foreach}
	                </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                	<input type="hidden" id="oitem_id" name="oitem_id" />
                	<input type="hidden" id="oitem_orderid" name="oitem_orderid" />
                	<button type="button" class="adminbtn" name="switch_oi_mfg" id="switch_oi_mfg">switch vendor</button>
                </td>
            </tr>
        </table>
    	</form>
    </fieldset>
</div>