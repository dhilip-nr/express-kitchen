    <div class="container-scroller">
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile border-bottom">
            <a href="#" class="nav-link flex-column">
              <div class="nav-profile-image">
                <img src="../images/logo.png" alt="profile" />
              </div>
              <div class="nav-profile-text d-flex ml-0 mb-3 flex-column">
                <span class="font-weight-semibold mb-1 mt-2 text-center">3D Designer | Admin</span>
              </div>
            </a>
			
            <a class="nav-link change-workspace" href="../workspace">
              <span class="menu-title">Back to Workspace</span>
            </a>

          </li>
<!--
          <li class="nav-item pt-3">
            <a class="nav-link d-block" href="index.html">
              <img class="sidebar-brand-logo" src="assets/images/logo.svg" alt="" />
              <img class="sidebar-brand-logomini" src="assets/images/logo-mini.svg" alt="" />
              <div class="small font-weight-light pt-1">Responsive Dashboard</div>
            </a>
          </li>
-->
          <li class="nav-item active">
            <a class="nav-link" href="#">
              <i class="mdi mdi-format-list-bulleted menu-icon"></i>
              <span class="menu-title">Orders</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="catalog">
              <i class="mdi mdi-table-large menu-icon"></i>
              <span class="menu-title">Catalog</span>
            </a>
          </li>
		 {if $smarty.session[$APPSESVAR|cat:"_user"].role=="admin" || $smarty.session[$APPSESVAR|cat:"_user"].role=="superadmin"}
          <li class="nav-item">
            <a class="nav-link" href="global_margin">
              <i class="mdi mdi-currency-usd menu-icon"></i>
              <span class="menu-title">Global Margin</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="users">
              <i class="mdi mdi-playlist-edit menu-icon"></i>
              <span class="menu-title">USERS</span>
            </a>
          </li>
		  {/if}
        </ul>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
  
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
          <div class="navbar-menu-wrapper d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
              <span class="mdi mdi-menu"></span>
            </button>

            <ul class="navbar-nav">
				<li class="nav-item d-none d-lg-block">
					Orders
				</li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
              <li class="nav-item nav-logout d-none d-lg-block">
                <a class="nav-link" href="../logout">
				  <i class="mdi mdi-logout"></i>
                </a>
              </li>
            </ul>
          </div>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper pb-0">
            <div class="page-header flex-wrap">

            </div>
            <!-- first row starts here -->
<ul class="breadcrumb"><li><a href="orders">HOME</a></li><li class="splitter"> | </li><li>{$order_result['order_id']}</li></ul>
            <div class="row">
				

<style>
.breadcrumb {
	width: 100%;
	list-style: none;	
	margin-top: -1.5rem;
}
.breadcrumb li{
	display: inline-block;
	margin: 0 5px;
}
.breadcrumb li.splitter{
	transform: rotate(10deg);
}
</style>

{if $order_result.order_status=="wtg-approval"}
    <div id="ins_notification" style="background: #fbf4b5; border: 1px dashed #cfcfcf; border-radius: 5px; line-height: 50px; margin: 20px auto; max-width: 1000px; text-align: center; width: 96%; color:#666; font-weight:bold;">The changes made by installer are waiting for an approval.</div>
{else if $order_result.order_status=="revision"}
    <div id="ins_notification" style="background: #fbf4b5; border: 1px dashed #cfcfcf; border-radius: 5px; line-height: 50px; margin: 20px auto; max-width: 1000px; text-align: center; width: 96%; color:#666; font-weight:bold;">This order is in REVISION state.</div>
{else if $order_result.order_status=="canceled"}
    <div id="ins_notification" style="background: #FB8763; border: 1px dashed #cfcfcf; border-radius: 5px; line-height: 50px; margin: 20px auto; max-width: 1000px; text-align: center; width: 96%; color:#fff; font-weight:bold;">This order was canceled.</div>
{/if}

<div id="sales_order" class="print_content" style="margin: 0 auto; min-width: 920px;">

{if $products_result != "" || $misc_result.0 > 0}
{$manfacturer_dup=""}
<form name="sales_order_frm" id="sales_order_frm" method="POST" action="">
<table class="orders_wrapper" cellspacing="0" id="material_order_table" name="material_order_table">
		<tr>
			<td colspan="4">
				ORDER ID : <b>{$order_result['order_id']}</b>
{*
                {if $test_branch==$order_result['branch_id']}
	 				<input type="button" id="convert_order" class="adminbtn active" value="Mark as Actial Order" style="float:right" />
                {else}
	 				<input type="button" id="convert_order" class="adminbtn" value="Mark as Test Order" style="float:right" />
                {/if}
*}
            </td>
			
			<td colspan="2" style="text-align: center;">
				<a href="#" id="yourdesign" class="urdesign">Your Design</a>
			</td>
		</tr>
		<tr>
		<td colspan="6">
{*
					<table class="lead_info" style="margin-bottom:10px; border:0">
						<tr>
                            <td width="40%">
                            	<strong>JOB ID</strong> &nbsp;&nbsp; 
                                <label>
                                    {if $order_result.jobid}{$order_result.jobid}{else}-{/if} &nbsp;&nbsp;&nbsp;
                                    <input type="button" name="edit_btn" onclick="$(this).parent('label').hide().next('label').show();" class="edit_btn print_order" />
                                </label>
                                <label style="display:none">
                                    <input type="text" name="job_id" id="job_id" value="{$order_result.jobid}" />
                                    <input type="submit" onclick="this.form.action.value=this.value" class="apply_btn print_order" value="save_job_id" />
                                    <input type="button" name="cancel_btn" onclick="$(this).parent('label').hide().prev('label').show();" class="cancel_btn print_order" />
                                </label>
                            </td>
                            <td width="30%">
                            	<strong>LEAD ID</strong> &nbsp;&nbsp; 
                            	<span class="lead_id">{$order_result.lead_id}{if $order_result.lead_id==""}-{/if}</span>
                            </td>
                            <td width="30%" align="right">
                            	<strong>Rep Name</strong> &nbsp;&nbsp; 
                            	<span class="lead_id">{$order_result.repname}{if $order_result.repname==""}-{/if}</span>
                            </td>
                        </tr>
					</table>
*}
					<table class="customer_info">
                        <tr bgcolor="#F0F0F0">
                            <td colspan="4">
                                <b>Customer Details</b>
                            </td>
                        </tr>

                        <tr>
                            <td width="18%">
                            	<strong>Cust. ID</strong>
                            </td>
                            <td width="35%">
                            	<span class="customer_id">{$order_result.customer_id}</span>
                            </td>
                            <td>
                            	<strong>Email</strong>
                            </td>
                            <td>
                            	<span class="email">{$order_result.customer_email}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            	<strong>Name</strong>
                            </td>
                            <td>
                            	<span class="name">{$order_result.customer_name}</span>
                            </td>
                            <td>
                            	<strong>Tel #</strong>
                            </td>
                            <td>
                            	<span class="telephone">{$order_result.customer_telephone}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="13%">
                            	<strong>Address</strong>
                            </td>
                            <td colspan=3>
                            	<span class="address">
									{$order_result.customer_address}, {$order_result.customer_city},
									{$order_result.customer_state} {$order_result.customer_zipcode}
								</span>
                            </td>
                        </tr>
					</table>
		</td>
		</tr>
		<tr id="item_title">
			<td>
				<b>#</b>
			</td>
			<td>
				<b>Pricing Model #</b>
			</td>
			<td>
				<b>Description</b>
			</td>
			<td>
				<b>UOM</b>
			</td>
			<td align="center">
				<b>Qty</b>
			</td>
			<td align="center">
				<b>Price</b>
			</td>
		</tr>

		{$i=1}
		{$page_total = 0}
        {if !empty($products_result)}
			{foreach from=$products_result key=key item=value}
			<tr class="so-desc-table">
				<td>{$i++}</td>
				<td>{$value.pricing_model}   {if $value.pricing_model|trim==""}-{/if}</td>
				<td>
					{$value.description} - {$value.category}
					{if $value.dimension!=""}
						<br>
						{assign var="product_dimension" value=($value.dimension|json_decode:1)}
						{foreach from=$product_dimension key=okey item=ovalue}

							{* $okey|ucfirst}: {$fn->decToFracPlain($ovalue)} {if array_key_last($product_dimension) != $okey} | {/if *}
							{$okey|ucfirst}: {$ovalue|decToFracPlain} {if count($product_dimension) && key(array_keys($product_dimension)) != $okey} | {/if}
						{/foreach}
					{/if}
          {if $value.options!="" &&  $value.category!='Add Ons'}
           
          <br>
            {assign var="product_option" value=($value.options|json_decode:1)}
            {foreach from=$product_option key=okey item=ovalue}
            <h4 style="font-size:15px;text-decoration:underline;margin-bottom: 2px;">{strtr($okey,'_','-')|ucfirst}</h4>
            {assign var="option_conf" value=($ovalue)}
              {foreach from=$option_conf key=ckey item=cvalue}
        
              {$ckey|ucfirst} : {$cvalue} | 
            
              {/foreach}
            {/foreach}
          {/if}
				</td>
				<td align="center">{$value.uom}</td>
				<td align="center">{$value.prd_qty}</td>
				<td align="right">${number_format($value.prd_price,2)}</td>
				{$page_total = $page_total+$value.prd_price}
			</tr>
			{/foreach}
        {/if}
		{$promo_amount = 0}
        {if $order_result.promo_amt > 0}
		<tr>
			<td colspan="5" align="right">
				<b>Grand Total :</b>
			</td>
			<td colspan="1" align="right">
			{if $order_result.total_amount>0}
                ${number_format((float)$order_result.total_amount,2)}
			{else}
				${number_format((float)$page_total,2)}
			{/if}
			</td>
		</tr>

        <tr>
		{if $order_result.promo_type=="percent"}
            <td colspan="5" align="right">
                <b>Promo ( {$order_result.promo_percent} % ) :</b>
            </td>
		{else}
            <td colspan="5" align="right">
                <b>Promo ( $ ) :</b>
            </td>
		{/if}

            <td colspan="1" align="right">
                {$promo_amount = $order_result.promo_amt}
                ${number_format((float)$promo_amount,2)}
            </td>
		</tr>
		{/if}
{*
{if $order_result.apd_amt gt 0}
		<tr>
			<td colspan="5" align="right">
				<b>Adnl. Discount :</b>
			</td>
			<td colspan="1" align="right">
				${number_format((float)$order_result.apd_amt,2)}
			</td>
		</tr>
{/if}
{if $order_result.admin_fee gt 0}
		<tr>
			<td colspan="5" align="right">
				<b>Administrative Fees :</b>
			</td>
			<td colspan="1" align="right">
                ${number_format((float)$order_result.admin_fee,2)}
			</td>
		</tr>
{/if}

		<tr>
			<td colspan="5" align="right">
				<b>Lead Test Fee :</b>
			</td>
			<td colspan="1" align="right">
                ${number_format((float)$order_result.lt_amt, 2)}
			</td>
		</tr>

		<tr>
			<td colspan="5" align="right">
				<b>Lead Free Work Practice :</b>
			</td>
			<td colspan="1" align="right">
                ${number_format((float)$order_result.lfwp_amt, 2)}
			</td>
		</tr>

		<tr>
			<td colspan="5" align="right">
				<b>General Constructions:</b>
			</td>
			<td colspan="1" align="right">
                ${number_format((float)$order_result.gen_con, 2)}
			</td>
		</tr>

		<tr>
			<td colspan="5" align="right">
				<b>Permit :</b>
			</td>
			<td colspan="1" align="right">
            	{$permit_amt = ($order_result.total_amount + $order_result.admin_fee + $order_result.lt_amt + $order_result.lfwp_amt + $order_result.gen_con - $promo_amount - $order_result.apd_amt) * $order_result.permit_percent / 100}
                {if $permit_amt>$order_result.permit_max} {$permit_amt=$order_result.permit_max} {/if}
                ${number_format((float)$permit_amt,2)}
			</td>
		</tr>

		{if $order_result.disc_sur_amt > 0}
		<tr>
			<td colspan="5" align="right">
				<b>Adjustment Amount:</b>
			</td>
			<td colspan="1" align="right">
                - ${number_format((float)$order_result.disc_sur_amt, 2)}
			</td>
		</tr>
		{/if}

		<tr>
			<td colspan="5" align="right">
				<b>Net Amount :</b>
			</td>
			<td colspan="1" align="right">
                {$net_amount = $order_result.total_amount+$order_result.admin_fee+$order_result.lt_amt+$order_result.lfwp_amt+$order_result.gen_con+$permit_amt-$promo_amount-$order_result.apd_amt-$order_result.disc_sur_amt}
                ${number_format($net_amount, 2)}
			</td>
		</tr>
*}

		<tr>
			<td colspan="5" align="right">
				<b>Net Amount :</b>
			</td>
			<td colspan="1" align="right">
                {$net_amount = $order_result.total_amount-$promo_amount}
                ${number_format($net_amount, 2)}
			</td>
		</tr>

        {if $order_result.attached_files!=""}
             <tr bgcolor="#F0F0F0">
                <td colspan="6">
                    <b>Sales Rep uploaded files</b>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    {$attached_files= ","|explode:$order_result.attached_files}
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
		<tr class="s_tr print_order">
            <td colspan="6">
                <center>
					<input type="button" id="sendmail" class="adminbtn" value="Send Mail" />
                </center>
            </td>
		</tr>
{*		
	<input type="hidden" name="action" id="action" />
	{if in_array($order_result.order_status, $unedit_ord) || $order_result.order_status=="install problem"}
		<tr class="s_tr print_order">
            <td colspan="6">
                <center>
                <input type="button" name="print" id="print" class="print adminbtn" value="Print" onclick="print_content();" />
				{if strpos($order_result.export_access, "ASO")!==false}
                <input type="button" id="export_order_todata" class="adminbtn" value="Export" />
				{/if}
			{if $order_result.order_status!='revision' && $order_result.order_status!='canceled'}
                <input type="button" id="sendmail" class="adminbtn" value="Send Mail" />
                <b>(OR)</b> 
                <input type="button" id="revise" class="adminbtn" value="Revise Order" />
                <input type="button" id="cancelorderbtn" class="adminbtn" value="Cancel Order" />
			{/if}
                </center>
            </td>
		</tr>
    {else}
		<tr class="s_tr print_order">
            <td colspan="6">
                <center>
					<input type="button" name="print" id="print" class="print adminbtn" value="Print" onclick="print_content();" />
                </center>
            </td>
		</tr>
	{/if}
*}
{$branch_admin_arr = ','|explode:$installer_email}
{foreach $bradminres as $bradmin_list}
	{$branch_admin_arr[] = $bradmin_list}
{/foreach}
{$branch_admin = array_unique(array_filter($branch_admin_arr))}
{$branch_admin = strtolower(implode(",",$branch_admin))}
</table>
</form>

	{if trim($order_result.comments)!='' && trim($order_result.comments)!='<br>'}
        <div class="orders_wrapper" style="border:solid 1px #ccc;">
            <fieldset style="border: 0px none; padding: 15px 10px; text-align:justify;">
                <b align="center" style="display:block; text-align:center; margin-bottom:10px">Instructions / Comments</b>
                {$order_result.comments}
            </fieldset>
        </div>
    {/if}

{else}
	<div align="center" style="padding:50px;">No Items for this order</div>
</div>
{/if}



            </div>

          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
<!--
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © bootstrapdash.com 2020</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap dashboard templates</a> from Bootstrapdash.com</span>
            </div>

            <div>
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block"> Distributed By: <a href="https://themewagon.com/" target="_blank">Themewagon</a></span>
            </div>
          </footer>
-->
        </div>
      </div>
    </div>
{include file="popups/misc_item.tpl"}

