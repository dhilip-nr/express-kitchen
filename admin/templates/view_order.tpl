    <link rel="stylesheet" type="text/css" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">

    <link rel="stylesheet" type="text/css" href="assets/css/demo_1/style.css" />

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
				<span style="text-align: center;">( {$smarty.session[$APPSESVAR|cat:"_user"].co_name} )</span>
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
            <div class="row">
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				


<script language="javascript" src="{$root}scripts/datatable/js/jquery.dataTables.min.js?v{$app_version}"></script>
<script language="javascript" src="{$root}scripts/datatable/js/TableTools.min.js?v{$app_version}"></script>
<script type="text/javascript" src="{$root}scripts/datatable/js/ZeroClipboard.min.js?v{$app_version}"></script>

<link rel="stylesheet" type="text/css" href="{$root}scripts/datatable/css/dataTable.min.css?v{$app_version}">

<script type="text/javascript" src="scripts/fancybox/jquery.mousewheel-3.0.6.pack.js?v{$app_version}"></script>

<link href="styles/datepicker.css?v{$app_version}" rel="stylesheet" type="text/css" />
<script language="javascript" src="scripts/datepicker.js?v{$app_version}"></script>

<!--for multi dropdown list box-->
<link rel="stylesheet" type="text/css" href="styles/jquery.multiselect.css?v{$app_version}" />
<link rel="stylesheet" type="text/css" href="styles/style_dropdown.css?v{$app_version}" />
<link rel="stylesheet" type="text/css" href="styles/jquery-ui.css?v{$app_version}" />
<link href="../css/admin.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="scripts/jquery-ui/jquery-ui.min.js?v{$app_version}"></script>
<script type="text/javascript" src="scripts/jquery.multiselect.min.js?v{$app_version}"></script>
<script type="text/javascript">
$(function(){
	$("select#other_filters").multiselect({
		multiple: false,
		minWidth: 80,
		height: 'auto',
		header: false,
		selectedList: 1
	});
	$("select.company_list").multiselect({
		minWidth: 180,
		noneSelectedText: 'select company',
		selectedText: '# company(s) selected'
	});
	$("select.branch_list").multiselect({
		minWidth: 180,
		noneSelectedText: 'select branch',
		selectedText: '# branch(es) selected'
	});
	$("select.status_list").multiselect({
		minWidth: 180,
		noneSelectedText: 'select status',
		selectedText: '# status(es) selected',
	});
	
/*
	$(".mdi-menu").click(function(){
		$(".sidebar").show();
	});
*/
});
</script>
<link rel="stylesheet" type="text/css" href="styles/view_orders.css?v{$app_version}" />
{if in_array($smarty.session[$APPSESVAR|cat:"_user"].role , ['dealer', 'branchadmin'])}
	<script language="javascript" src="scripts/view_order_dealer.js?v{$app_version}"></script>
{else if ($smarty.session[$APPSESVAR|cat:"_user"].role eq 'admin' && $smarty.session[$APPSESVAR|cat:"_user"].ma==1) || in_array($smarty.session[$APPSESVAR|cat:"_user"].role, ['superadmin', 'divisionalmanager'])}
	<script language="javascript" src="scripts/view_order_superadmin.js?v{$app_version}"></script>
{else}
	<script language="javascript" src="scripts/view_order.js?v{$app_version}"></script>
{/if}

<div id="view_order">
{*
    <form>
        <ul class="order_filterhead">
            <li>
                {if isset($smarty.get.view)} {$view=$smarty.get.view} {else} {$view=""} {/if}
                {$view_arr = explode(",",$view)}
                <select name="other_filters" id="other_filters">
                    {foreach $filter_arr as $key=>$filter_opt}
                        <option value="{$key}" {if in_array($key, $view_arr)} selected="selected" {/if}>{$filter_opt}</option>
                    {/foreach}
                </select>
            </li>
		{if $smarty.session[$APPSESVAR|cat:"_user"].role eq 'superadmin' && $smarty.session[$APPSESVAR|cat:"_user"].co_id eq ''}
            <li>
                {if isset($smarty.get.company)} {$company=$smarty.get.company} {else} {$company=""} {/if}
                {$multi_company = explode(",",$company)}
                <select name="company_list" id="company_list" class="company_list" multiple="multiple">
                    {foreach $company_arr as $company_list}
                        <option value="{$company_list.alias}" {if in_array($company_list.alias, $multi_company)} selected="selected" {/if}>{$company_list.name}</option>
                    {/foreach}
                </select>
            </li>
		{/if}
            <li>
                {if isset($smarty.get.branch)} {$branch=$smarty.get.branch} {else} {$branch=""} {/if}
                {$multi_branch = explode(",",$branch)}
                <select name="branch_list" id="branch_list" class="branch_list" multiple="multiple">
                    {foreach $branch_arr as $key=>$branch_list}
                        <option value="{$branch_list.branch}" {if in_array($branch_list.branch, $multi_branch)} selected="selected" {/if}>{$branch_list.branch}</option>
                    {/foreach}
                </select>
            </li>
            <li>	
                {if isset($smarty.get.status)} {$status=$smarty.get.status} {else} {$status=""} {/if}
                {$multi_status = explode(",",$status)}
                <select name="status_list" id="status_list" class="status_list" multiple="multiple">
                       {foreach $status_arr as $key=>$value}
                        <option value="{$key}" {if in_array($key, $multi_status)}  selected="selected" {/if}>{$value}</option>
                    {/foreach}
                </select>
            </li>
            <li>
                {if isset($smarty.get.bwdates)} {$bwdates=$smarty.get.bwdates} {else} {$bwdates=""} {/if}
                {if $bwdates!=""} {$bwdate=explode("<->",$bwdates)} {/if} 
                    <input type="text" name="date_from" id="date_from" class="tpinput" value="{if $bwdates != ""}{$bwdate[0]}{/if}" readonly="readonly" />
                    &nbsp;to&nbsp;
                    <input type="text" name="date_to" id="date_to" class="tpinput" value="{if $bwdates != ""}{$bwdate[1]}{/if}" readonly="readonly" />
            </li>
            <li>
                <input type="button" name="branchfilter" value="go" class="orderfilter" />
            </li>
        </ul>
    </form>
*}
    <fieldset style="border:thin solid #CCC; margin: 0 auto; width:96%; padding: 20px;">
        <div style="clear:both;">&nbsp;</div>

        <table cellpadding="0" cellspacing="0" border="0" class="display" style="padding:3px 5px; border-bottom:1px solid #aaa; text-align:left;" id="order_table" name="order_table">
            <thead>
                <tr style="border:#ccc solid 1px;">
                    <th width="6%" align="center">Order #</th>
                    <th width="6%">Job id #</th>
                    <th width="8%">Branch</th>
                    <th width="10%">Cust. Name</th>
                    <th width="10%">Cust. Phone</th>
                    
                    <th width="7%" align="right">Sale Amt.&nbsp;($)</th>
                    <th width="6%" align="right">Mat.Cost&nbsp;($)</th>
                    <th width="6%" align="right">Ins.Cost&nbsp;($)</th>
                    <th width="6%" align="right">Margin&nbsp;($)</th>
                    <th width="5%" align="right">Margin&nbsp;(%)</th>
                    
                    <th width="5%" align="center">Date</th>
                    <th width="5%" align="center">Status</th>
                    <th width="6%">Comments</th>
                    <th width="10%">Created By</th>
                    <th width="6%" align="center">Action</th>
                </tr>
            </thead>
        </table>
    </fieldset>
</div>				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				

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
