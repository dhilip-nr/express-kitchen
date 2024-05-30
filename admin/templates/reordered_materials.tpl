<script language="javascript" src="scripts/datepicker.js?v{$app_version}"></script>
<link href="styles/datepicker.css?v{$app_version}" rel="stylesheet" type="text/css" />

{literal}
<script>
	$(document).ready(function(){
		var date_ipid="";
		$('.tpinput').DatePicker({
			format:'Y-m-d',
			date: $(this).val(),
			current: $(this).val(),
			starts: 1,
			position: 'r',
			onBeforeShow: function(){
				date_ipid=$(this).attr("id");
				var input_date = $(this).val();
				if(input_date=='') {
					var d = new Date();
					input_date = (d.getFullYear()+"/"+(d.getMonth()+1)+"/"+d.getDate());
				}			
				$(this).DatePickerSetDate(input_date, true);
			},
			onChange: function(formated, dates){
				$('#'+date_ipid).val(formated);
				$(".datepicker").hide();
			}
		});

		$("#enable_delivery").click(function(){
			$("td.action_row input").hide();
			$("#make_delivery").show();
			$("input.select_del_item").show().prev("a.no_sd_info").hide();
		});


		$(".select_del_item").click(function(){
			var this_class = $(this).attr("class");
			var checked_array = [];	
			var values = $('input:checkbox:checked.'+this_class).map(function () {
					checked_array.push(this.value);
				}).get();
			$(".checked_id").val(checked_array);
		});
		
		$(".show_trans_info").click(function(e){
			var ship_info = $(this).attr("data-info").split("=>");

			$("#cname").val(ship_info[0]);
			$("#carrier_no").val(ship_info[1]);
			$("#person_name").val(ship_info[2]);
			$("#ship_cost").val(ship_info[3]);
			$("#date_from").val(ship_info[4]);
			$("#eta_date").val(ship_info[5]);
			$("tr.hide_add_action").hide();

			$.fancybox({
				href: "#get_shipping_info",
				maxWidth	: 700,
				maxHeight	: 600
			});
		});

		$("#make_delivery, .show_del_info").click(function(e){
			if($(this).attr("class")=="show_del_info"){
				var ship_info = $(this).attr("data-info").split("=>");

				$("#received_by").val(ship_info[6]);
				$("#received_on").val(ship_info[7]);
				$("#comments").val(ship_info[8]);

				$("#get_delivery_info tr.hide_add_action").hide();
			} else {
				$("#received_by").val("");
				$("#received_on").val("");
				$("#comments").val("");

				$("#get_delivery_info tr.hide_add_action").show();
			}

			$.fancybox({
				href: "#get_delivery_info",
				height		: 'auto',
				maxWidth	: 700,
				maxHeight	: 600
			});
		});

		$("#delivery_info").on("click", "#delivery_info_submit", function(){
			if($("#received_by").val() && $("#received_by").val()){
				$("#delivery_info").submit();
				return false;
			} else {
				$("label#error_msg").text("Please enter all required fields");
				return false;
			}
		});

		$(document).on('click', '#switch_mat_view button', function(){
			var view_type = $(this).attr('data-view').split(',');
			if(view_type[0]=='admin'){
				$(this).text('Switch To '+view_type[1]+' View').attr('data-view','dealer,admin').prev('span').text('Re-Ordered Materials');
			} else {
				$(this).text('Switch To '+view_type[1]+' View').attr('data-view','admin,dealer').prev('span').text(view_type[0].substr(0,1).toUpperCase()+view_type[0].substr(1)+': Re-Ordered Materials');
			}
			$('.orders_wrapper').toggleClass('show hide');
		});
	});
</script>
<style>
	.orders_wrapper.hide{
		display: none;
	}
	.orders_wrapper.show{
		display: table;
	}
	.orders_wrapper th{
		border: #aaa solid 1px;
		border-top:0;
		font-weight:normal;
		padding: 10px;
	}
	.orders_wrapper td{
		border-bottom: 0;
	}
</style>
{/literal}

{include file='menu.tpl'}

<div id="reordered_items_data" style="width:95%; margin:50px auto; height:auto; min-height:150px;">
{foreach $reordered_material_res.result as $key=>$order_mats_arr}
	{$display_class = "hide"}
	{if $key=="admin" && $reordered_material_res.active=="admin"}
        <div id="switch_mat_view" style="width:96%; max-width:1000px; height:25px; margin:0 auto 20px;">
            <span>Re-Ordered Materials</span>
            <button class="adminbtn" style="float:right;" data-view="dealer,admin">Switch To Dealer View</button>
        </div>
		{$display_class = "show"}
    {/if}
    
	{if $key=="dealer" && $reordered_material_res.active=="dealer"}
		{$display_class = "show"}
    {/if}

    <table class="orders_wrapper {$display_class}" cellspacing="0" style="margin:0 auto 50px; width:96%; border:#aaa solid 1px;" data-type="{$key}">
        <tr style="background:#F0F0F0;">
            <th width="8%" align="center"><b>SI #</b></th>
            <th width="50%"><b>Model #</b></th>
            <th width="8%" align="center"><b>UOM</b></th>
            <th width="10%" align="center"><b>QTY</b></th>
            <th width="14%" align="center"><b>Product Cost</b></th>
            <th class="s_tr" align="center" width="10%"><b>Status</b></th>
        </tr>
        
      
    {if !empty($order_mats_arr)}
        {$material_total = 0}
        {$count_check = 1}
        {$show_shipping_action = 0}
        {$show_delivery_action = 0}

        {$vendor_temp = ""}
        {$key = 1}
        {foreach $order_mats_arr as $reord_mats}
            {if $vendor_temp!=$reord_mats.vendor}
                <tr style="background:#F0F0F0;">
                    <td colspan="5">
                        {$reord_mats.vendor}
                    </td>
                    <td style="background:#fff; border:0;">&nbsp;</td>
                </tr>
                {$key = 1}
            {/if}

            {$vendor_temp = $reord_mats.vendor}
            {$material_total = $material_total+$reord_mats.cost*$reord_mats.cover_cost}
            <tr>
                <td align="center">{$key++}</td>
{*
                <td>{$reord_mats.pricingmodel}</td>
*}
                <td>{$reord_mats.item} <span style="float:right; cursor: default;" title="{$reord_mats.created_on|date_format}">[ {$reord_mats.reason} ]</span></td>
                <td align="center">{$reord_mats.uom}</td>
                <td align="center">{number_format($reord_mats.qty, 2)}</td>
                <td align="right">$ {number_format($reord_mats.cost*$reord_mats.cover_cost, 2)}</td>

                <td align="center" style="border:0;">

                {if $reord_mats.shipping_info != ''}
                    {$delivery_info_arr = explode("=>", $reord_mats.shipping_info)}
                    {if isset($delivery_info_arr.6)}
                        <a class="show_del_info" data-info="{$reord_mats.shipping_info}" style="cursor:pointer;">Delivered</a>
                    {else}
                        {$show_delivery_action = $show_delivery_action+1}
                        <a class="show_trans_info no_sd_info" data-info="{$reord_mats.shipping_info}" style="cursor:pointer;">Shipped</a>
                        <input type="checkbox" name="select_del_item" class="select_del_item" value="{$reord_mats.id}" style="display:none;" />
                    {/if}
                {else}
                -
                {/if}
                </td>
            </tr>
          {/foreach}   
            <tr style="font-weight:bold;">
                <td colspan="4" align="right">Total Cost</td>
                <td align="right">$ {number_format($material_total, 2)} </td>
                <td style="border:0;">&nbsp;</td>
            </tr>     

        {if $show_delivery_action}
            <tr>
                <td colspan="6" align="center" class="action_row">
                    <input type="button" id="enable_delivery" class="adminbtn" value="Add Delivery Info" />
                    <input type="button" id="make_delivery" class="adminbtn" value="Save Delivery info" style="display:none" />           
                </td>
            </tr>
        {/if}
	{else}
		<tr><td colspan="6" align="center">No items has been reordered</td></tr>
    {/if} 
    </table>
{/foreach}
</div>

<div id="get_shipping_info" style="width:500px; margin:auto; height:auto; min-height:180px; display:none;">
    <form name="shipping_info" id="shipping_info" method="post" enctype="multipart/form-data">
	<fieldset style="border:#b4b4b4 solid 1px;">
    	<legend style="display:inline !important;"><strong>Shipping Information</strong></legend>
    	<table width="100%" style="margin-top:10px;">
            <tr>
                <td width="30%"><strong>Carrier Name</strong></td>
                <td width="70%"><input type="text" name="cname" id="cname" style="width:98%;" /></td>
            </tr>
             <tr>
                <td><strong>Tracking No</strong></td>
                <td><input type="text" name="carrier_no" id="carrier_no" style="width:98%;" /></td>
            </tr>
            <tr>
                <td><strong>Contact No</strong></td>
                <td><input type="text" name="person_name" id="person_name" style="width:98%"/></td>
            </tr>
            <tr>
                <td><strong>Shipping Cost ($)</strong></td>
                <td><input type="text" name="ship_cost" id="ship_cost" style="width:45%" /></td>
            </tr>
            <tr>
                <td><strong>Date</strong></td>
                <td><input type="text" name="date_from" id="date_from" class="tpinput" style="width:45%" readonly /></td>
            </tr>
            <tr>
                <td><strong>ETA</strong></td>
                <td><input type="text" name="eta_date" id="eta_date" class="tpinput" style="width:45%" readonly /></td>
            </tr>
            <tr class="hide_add_action">
                <td>
                    <strong>Attachment</strong>
                </td>
                <td class="td_add_file" style="height:10px;">
					<input type="file" name="attach_file_name" />
					
                </td>
            </tr>
        </table>
	</fieldset>
    </form>
</div>

<div id="get_delivery_info" style="width:500px; margin:auto; height:auto; min-height:180px; display:none;">
    <form name="delivery_info" id="delivery_info" method="post" enctype="multipart/form-data">
	<fieldset style="border:#b4b4b4 solid 1px;">
    	<legend style="display:inline !important;"><strong>Delivery Information</strong></legend>
    	<table width="100%" style="margin-top:10px;">
            <tr>
                <td width="30%"><strong>Received By</strong></td>
                <td width="70%"><input type="text" name="received_by" id="received_by" style="width:98%;" /></td>
            </tr>
             <tr>
                <td><strong>Received No</strong></td>
                <td><input type="text" name="received_on" id="received_on" style="width:98%;" /></td>
            </tr>
            <tr>
                <td><strong>Comments</strong></td>
                <td><input type="text" name="comments" id="comments" style="width:98%"/></td>
            </tr>
            <tr class="hide_add_action">
                <td colspan="2" align="right">
                <div style="border-bottom:dashed 1px #ccc; width:100%; height:15px; margin-bottom:10px;">&nbsp;</div>
                <input type="hidden" name="checked_id" value="" class="checked_id"/>
				<label id="error_msg" style="width:99%; color:#F00; padding:5px 0; margin-right:20px;"></label>
                <input type="hidden" name="ajax-mode" value="save_deliveryinfo" />
                <input type="reset" class="adminbtn ipclear" value="clear" />
                <button type="button" class="adminbtn" id="delivery_info_submit" value="save_deliveryinfo">Submit</button>
                </td>
            </tr>
        </table>
	</fieldset>
    </form>
</div>