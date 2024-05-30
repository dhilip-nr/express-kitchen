/*   Adding text editor for mail content - Begins   */
bkLib.onDomLoaded(function(){
	new nicEditor({
		buttonList : ['fontSize', 'bold', 'italic', 'underline', 'forecolor', 'link', 'left', 'center', 'right', 'justify', 'strikeThrough', 'ol', 'ul'],
		iconsPath : root_path+'../images/nicEditorIcons.gif',
		maxHeight : 100
	}).panelInstance('mail_secondary_content');
});
/*   Adding text editor for mail content - Ends   */

$(document).ready(function(){

	if($("#is_items_found").val()=="No Items"){
		return false;
	}

	var listItems = $(".ul_count").children().length;

	//for store installer mapping
	var org_mail_content = $("#mail_secondary_content").val();
	var installer_mode = $("select#ship_to").val();
	var store_num_exists = $("input#store_id_exists").val();
	var installer_id_exists = $("input#installer_id_exists").val();
	var ins_exists_type = $("input#installer_id_exists").attr("data-instype");
	var sent_material =  $.trim($("input#sent_material").val());
	var sent_installer =  $.trim($("input#sent_installer").val());

	if(installer_mode=="store") {
		pick_installerby(installer_mode);
		$("select.store_select").val(store_num_exists);
	} else if(installer_mode=="installer") {
		pick_installerby(installer_mode);
		$("select.store_select").val(installer_id_exists);
	}

	if(sent_material!="" || sent_installer!=''){
		$("select#ship_to").prop('disabled', true);
		$("select.store_select").prop('disabled', true);
	}
	
	$("tr.each_vendor_table").hide();
	$("tr.tab0").show();
	var vendor_email = $.trim($("tr.tab0").find(".vendor_email").text());
	$("#mail_to").val(vendor_email);

	$("#change_view_act").click(function(){
		$.ajax({
			type: "POST",
			url: root_path+"ajax_process.php",
			data: {"ajax_mode" : "change_mo_view", "view" : $("#change_view_dd").val()},
			async: false,
			beforeSend: function(){
				$("#loader").show();
			},
			success: function(data){
				location.href = window.location;
			}
		});
	});

	$("#materialxml").click(function(){
		var li_active = $("li.manufacutur_active").text();
	  //alert(li_active);
		if (jQuery("#materialxml").is(":checked")) {
	   $("form.material_order_mailfrm").append('<input type="hidden" name="man_name" id="man_name" value="'+li_active+'">');
		} else {
			$("input#man_name").remove();
		}
	});

	$("#sendmail").click(function(e){
		var mail_vendor = $("li.manufacutur_active b").attr("data-alias");
		$("#sendmailcopy_frm #sending_vendor").val(mail_vendor);
		$.fancybox({
			href: "#sendmailcopy",
			maxWidth	: 700,
			maxHeight	: 600,
		});
	});

	$("#mail_cancel").click(function(e){ 	/* this is for fancybox window close */
		e.preventDefault();
		$.fancybox.close();
		$("input#man_name").remove();
        $("#materialxml").prop('checked', false);	
	});

	$("button.edit_item").click(function(e){
		e.preventDefault();
		$("#edit_material_item input#oitem_id").val($(this).parents("tr").find('[name="orderitem[id]"]').val());
		$("#edit_material_item select#oitem_mfg").val($("li.each_vendor.manufacutur_active").find("b").text());		

		$("#edit_material_item tr:eq(0) td:eq(0) span").text($(this).parents("tr").find("td:eq(1)").text().trim());
		$("#edit_material_item tr:eq(1) td:eq(0) span").html($(this).parents("tr").find("td:eq(2)").html().trim());

		$.fancybox({
			href: "#edit_material_item",
			maxWidth	: 700,
			maxHeight	: 600,
		});
	});

	$("#switch_oi_mfg").click(function(){
		var dataset = {
			"ajax_mode":"switch_oi_mfg",
			"order_id": $("input#order_id").val(),
			"item": {"id":$("#edit_material_item input#oitem_id").val(), "sku":$("#edit_material_item tr:eq(0) td:eq(0) span").text()},
			"mfg": {"f":$("li.each_vendor.manufacutur_active").find("b").text(), "t":$("#edit_material_item select#oitem_mfg").val()}
		}
		if($("li.each_vendor.manufacutur_active").find("b").attr("data-alias")==dataset.mfg){
			$(".fancybox-close").trigger("click");
		} else {
			$.ajax({
				type:"POST",
				url: root_path+"ajax_process.php",
				data:dataset,
				success:function(data){
					if(data=="success")
						location.reload();
					else
						$("#edit_material_item legend+span").html("Material Order already Placed for '"+dataset.mfg.t+"'.<br>So, unable to switch to this vendor !").show();
				}
			});
		}
	});

	$("#export_order_todata").click(function(e){
		$("#exportDataOption #mfg").val($("li.each_vendor.manufacutur_active").find("b").attr("data-alias"));
		$("#exportDataOption input[name=with_price][value=1]").prop("checked", true);
		$.fancybox({
			href: "#exportDataOption",
			maxWidth	: 700,
			maxHeight	: 600,
		});
	});

	$("#exportDataOption #do_export").click(function(e){
		$('a.fancybox-close').trigger('click');

		var order_id = $("#exportDataOption input[name=order_id]").val();
		var export_type = $("#exportDataOption input[name=export_type]:checked").val();
		var with_price = $("#exportDataOption input[name=with_price]:checked").val();
		var mfg = $("#exportDataOption input[name=mfg]").val();

		$.ajax({
			type:"POST",
			url: root_path+"ajax_process.php",
			data:{"ajax_mode":"export_to_file", "order_id":order_id, "order_type":"MO", "export_type":export_type, "with_price":with_price, "mfg":mfg},
//			dataType:"json",
			async:false,
			success:function(data){
				window.open(root_path+"order_export.php?id="+data);
			}
		});
	});

	$("select#ship_to").change(function(){
		pick_installerby(this.value);
	});

	$("select#store_select").change(function(){
		var st_add = $("select#ship_to").val();
		update_installer_details(st_add);
	});

	$("#cancel").click(function(){   /* this is for material order window close */
		window.close();
	});
	
	var active_mfg_classes = $("li.manufacutur_active").attr("class").split(" ");
	if($.inArray("sent_order", active_mfg_classes)==-1){
		$("input#reorder").hide();
		$("input#placeorder").show();
	} else {
		$("input#reorder").show();
		$("input#placeorder").hide();
	}
	
	$("li.each_vendor").bind("click", function(){
		$("tr.each_vendor_table").hide();
		var root_class = $(this).attr("class");
		var add_active_class ="";
		add_active_class = " manufacutur_active";
		$("li.each_vendor").removeClass(add_active_class);
		$(this).addClass(add_active_class);
		var root_class_1 = root_class.split(" ");
		var vendor_email = "";
		var vendor_email = $.trim($("tr."+root_class_1[0]+"").find(".vendor_email").text());
		$("#mail_to").val(vendor_email);
		$("tr."+root_class_1[0]+"").show();

		if($(this).children("b").text()=='CSD'){
			$("td#shipto_pick_show").hide();
			$("td#shipto_pick_hide").show();
		} else {
			$("td#shipto_pick_show").show();
			$("td#shipto_pick_hide").hide();
		}

		$("table.orders_wrapper tr .select_item").hide();
		$("table.orders_wrapper tr .select_single_item").hide();

		var active_mfg_classes = $("li.manufacutur_active").attr("class").split(" ");
		if($.inArray("sent_order", active_mfg_classes)==-1){
			$("input#reorder").hide();
			$("input#placeorder").show();
		} else {
			$("input#reorder").show();
			$("input#placeorder").hide();
		}

		$("#place_reorder").hide();
	});
	
	$("form.material_order_mailfrm").submit(function(){
		$("#sending_vendor").val($("li.each_vendor.manufacutur_active").find("b").attr("data-alias"));
		return true;
	});
	
	$(".addtype_file").click(function(e){
		var form_id = $(this).closest("form").attr("id");
	    $("#"+form_id+" td.td_add_file").append('<div><input type=\"file\" width=\"200px;\" name=\"attach_file_name[]\" /><label class=\"label_type_file\"><input type="button" style="background:url(../images/actions/remove.png); margin:4px; width:24px; height:24px; border:none;" width="24" height="24" class=\"removetype_file\" /></label></div>');
    });
	
	$(document).on("click", ".removetype_file", function() {
		$(this).closest("div").remove();
	});

	
	$(".print").click(function(){
		$(".print_order").hide();
		document.title = "Material Order - "+$("li.manufacutur_active").text()+" - "+$.urlParam("id");
		window.print();
		$("ul.print_order").show();
		$("#vendor_tabs.print_order").show();
		$("ol.print_order").show();
		$("footer.print_order").show();
		$(this).parents("tr").show();
	});


	//For store to installer mapping getting store no and saving to db through ajax
	function pick_installerby(ship_to_val){
		$("select#store_select").html("");
		var ship_to = ship_to_val;
		var branch_id = $("#branch_id").val();
		var bro_order_id = $("input#order_id").val();

		if(typeof branch_id === "undefined"){
			return false;
		}

		if(ship_to == 'branch'){
			update_installer_details(ship_to);
		}else if(ship_to == 'store'){
			$.ajax({
				type: "POST",
				url: root_path+"ajax_process.php",
				data: {"ajax_mode" : "get_store_no", "ship_to" : ship_to, "order_id" : bro_order_id, "branch_id" : branch_id},
				dataType: "json",
				async: false,
				beforeSend: function(){
					$("#loader").show();
				},
				success: function(data){			   
					if(data.result == "no_store") {
						alert("No store available for the selected branch");
						$("select#ship_to").val('branch');
						return false;
					} else if(data.result == "false") {
						alert("Please try after sometimes...");
						return false;
					}				   
					$("select#store_select").append("<option value=''>Select store</option>").prop('disabled', false).show();
					$.each(data.result, function(i,item){
						$("select#store_select").append("<option value='"+item.store_num+"'>"+item.store_num+"</option>");
					});
				},
				complete: function(){
					$("#loader").hide();
				}
			});	
		} else if(ship_to == 'installer'){
			$.ajax({
				type: "POST",
				url: root_path+"ajax_process.php",
				data: {"ajax_mode" : "get_installers", "ship_to" : ship_to, "order_id" : bro_order_id, "branch_id" : branch_id},
				dataType: "json",
				async: false,
				beforeSend: function(){
					$("#loader").show();
				},
				success: function(data){
					$("select#store_select").append("<option value=''>Select installer</option>").show();
					$.each(data, function(i,item){
						$("select#store_select").append("<option value='"+item.id+"'>"+item.installer+"</option>");
					});
				},
				complete: function(){
					$("#loader").hide();
				}
			});	
		}

	}


	function update_installer_details(ship_to_val){
		//var root = $("select#store_select");
		var selectedid = $("select#store_select").val();	
		var bro_order_id = $("input#order_id").val();
		var ajax_data = "";

		if(selectedid!="" && typeof bro_order_id!="undefined"){
			if(ship_to_val == "store"){
				ajax_data = {"ajax_mode" : "get_shipto_store", "store_id" : selectedid, "order_id" : bro_order_id};
			}else if(ship_to_val == "installer"){
				ajax_data = {"ajax_mode" : "get_shipto_installer", "installer_id" : selectedid, "order_id" : bro_order_id};
			}else if(ship_to_val == "branch"){
				ajax_data = {"ajax_mode" : "clear_store_no", "order_id" : bro_order_id};
				$("select#store_select").hide();
			}

			$.ajax({
				type: "POST",
				url: root_path+"ajax_process.php",
				data: ajax_data,
				dataType: "json",
				async: false,
				beforeSend: function(){
					$("#loader").show();
				},
				success: function(data){
					$("#mail_secondary_content").html(data.ship_to);
					$("div.nicEdit-main").html(data.ship_to);
					
					$(".shipto_inst_name").html(data.installer.groupname);
					$(".shipto_inst_contact").html(data.installer.firstname+" "+data.installer.lastname);
					$(".shipto_inst_email").html(data.installer.email);
					$(".shipto_inst_phone").html(data.installer.officephone);
					$(".shipto_inst_address").html(data.installer.officeaddress+", "+data.installer.city+", "+data.installer.state+" "+data.installer.zipcode);
				},
				complete: function(){
					$("#loader").hide();
				}
			});
		}
	}
	
	//	For quantity update starts ----------
	$(".change_qty").click(function(){
		$(this).parents("tr").children("td.non_editable_row").hide();
		$(this).parents("tr").children("td.editable_row").show();

		$(this).parents("tr").children("td").find("button.change_qty").hide();
		$(this).parents("tr").children("td").find("button.update_qty").show();
	});
	//	For quantity update ends ----------
	
	//	For re-order item starts ----------
	$(".reorder").click(function(){
		$(this).hide();
		$("#place_reorder").show();
		var show_tbltabs = $("li.manufacutur_active").attr("class").split(" ");
		$(this).parents('table.orders_wrapper').find("tr."+show_tbltabs[0]+" .select_item").show();
		$(this).parents('table.orders_wrapper').find("tr."+show_tbltabs[0]+" .select_single_item").show();
	});

/*
// To set Zero price for reordered items - starts
	$(".select_item").click(function(){
		if($(this).is(':checked')) {
			$(this).parent("td").parent("tr").find('td:last').html('<b>$0.00</b>');
		} else {
			$(this).parent("td").parent("tr").find('td:last').html('');
			var old_cost = $(this).parent("td").parent("tr").find('td:last').attr("data-oldcost");
			$(this).parent("td").parent("tr").find('td:last').html('<b>$'+old_cost+'</b>');
			
		}
	});
// To set Zero price for reordered items - ends
*/
	var ordersubitem_id = [];
	var order_mainitem_id = [];


	$(".placeorder").click(function(e){
		var sending_vendor = $("li.manufacutur_active b").attr("data-alias");
		$("#material_order_mailfrm #sending_vendor").val(sending_vendor);

		var placeorderval = this.value;
		var orders_sent = $("li.manufacutur_active").hasClass("sent_order");
		var confirm_sent = true;
		if(orders_sent && placeorderval=="Place Order"){
			confirm_sent = confirm("This material order has placed already. Do you want to place the order again?");
		}

	/*
		$("input.select_item").click(function(){
			$(this).attr("checked", false);
		});
	*/
		//	For re-order item starts ----------
		$("form.material_order_mailfrm").find('input#ordersubitem_id').remove();
		$("form.material_order_mailfrm").find('input#order_mainitem_id').remove();

		var show_tbltabs = $("li.manufacutur_active").attr("class").split(" ");
		ordersubitem_id = [];
		order_mainitem_id = [];
		
		$('table.orders_wrapper').find("tr."+show_tbltabs[0]+" .select_item").each(function(i, value){
			if($(this).is(':checked')){
				ordersubitem_id.push($(this).val());
			}
		});
		$('table.orders_wrapper').find("tr."+show_tbltabs[0]+" .select_single_item").each(function(i, value){
			if($(this).is(':checked')){
				order_mainitem_id.push($(this).val());
			}
		});

		var mail_subject = $("#material_order_mailfrm #mail_subject").attr("data-content");
		if(placeorderval!="Place Order" && (ordersubitem_id.length>0 || order_mainitem_id.length>0)){
			$("#material_order_mailfrm legend strong").text("Material Re-Order Mail");
			$("#mail_subject").val("Material Re-Order "+mail_subject);
			$("tr#reason_content_wrapper").show();
		} else {
			$("#material_order_mailfrm legend strong").text("Material Order Mail");
			$("#mail_subject").val("Material Order "+mail_subject);
			$("tr#reason_content_wrapper").hide();
		}

		if(ordersubitem_id!=""){
			$("form.material_order_mailfrm").find('input#ajax_mode').after("<input type='hidden' name='ordersubitem_id' id='ordersubitem_id' value= '"+ordersubitem_id+"'/>");
		} else if(order_mainitem_id!=""){
			$("form.material_order_mailfrm").find('input#ajax_mode').after("<input type='hidden' name='order_mainitem_id' id='order_mainitem_id' value= '"+order_mainitem_id+"'/>");
		}
		//	For re-order item ends ----------

		if(confirm_sent == true){
			var mail_vendor = $("li.manufacutur_active b").attr("data-alias");
			$("#mail_container #sending_vendor").val(mail_vendor);

			var total_mail_cc = $("#original_mail_cc").val();
			var tab = $("li.manufacutur_active").attr("class").split(" ");
			var manufactur_emailcc = $("."+tab[0]).find("input.manufacturer_emailcc").val();		 
			if(manufactur_emailcc != "") {
				$('#mail_cc').val((total_mail_cc + "," + manufactur_emailcc).replace(/(^\s*,)|(,\s*$)/g, ''));
			} else {
				$('#mail_cc').val(total_mail_cc);
			}
			$.fancybox({
				maxWidth	: 800,
				maxHeight	: 600,
				href: "#mail_container",
				fitToView	: false,
				width		: '610px',
				height		: 'auto',
				autoSize	: false,
				closeClick	: false,
				showCloseButton: false,
				hideOnOverlayClick: false,
				hideOnContentClick: false,
				openEffect	: 'none',
				closeEffect	: 'none'
			});
		}
		
	});	


	// Material mail preview statrs ----------
	$("#preview").click(function(e){

		$("#material_mail_preview").html('<center><img src="'+root_path+'../scripts/fancybox/fancybox_loading.gif" style="margin-top:10%;" /></center>').css("height","auto");

		$.fancybox({
			maxWidth	: 900,
			maxHeight	: 500,
			href: "#material_mail_preview",
			width		: '880px',
			height		: 'auto',
			autoSize	: false,
			closeClick	: false,
			showCloseButton: false,
			hideOnOverlayClick: false,
			hideOnContentClick: false,
			openEffect	: 'none',
			closeEffect	: 'none',
//			afterClose: function() {
//				$(".placeorder").trigger("click");
//			}
		});

//		if(ordersubitem_id!="" || order_mainitem_id!=""){

			var sending_vendor = $("li.manufacutur_active b").attr("data-alias");
			var bro_order_id = $("input#order_id").val();
			var reason = $("select#reason_content").val();
			var order_id ="";
			if(typeof bro_order_id!="undefined"){
				order_id = bro_order_id.substring(2, bro_order_id.length);
			}

			$.ajax({
			   type: "POST",
			   url: "ordermail_preview.php",
			   data: {"ajax_mode" : "mo_mail_preview", "ordersubitem_id" : ordersubitem_id, "order_mainitem_id" : order_mainitem_id, "order_id" : order_id, "sending_vendor" : sending_vendor, "reason" : reason},
			   dataType: "json",
			   async: false,
			   success: function(data){
					var shipto_content = $("div.nicEdit-main").html();
					if($("#include_repnotes").is(":checked")){
						shipto_content = shipto_content+"<br><br><b>Rep entered order notes</b><br>"+$("#order_repnotes").val()+"<br><br>";
					}

					$("#material_mail_preview").html('<fieldset style="width:95%; padding:15px 2%; margin:auto;"><legend style="display:inline !important;"><strong>Preview Email - Material Order</strong></legend>'+shipto_content+data+'</fieldset>');
			   }
			});	
//		}
/*
		else{
		$("#html_content_mail").find(".s_tr").remove();
		var preview_mail_content = $("#html_content_mail").html();
		var shipto_content = $("div.nicEdit-main").html();
		$("#material_mail_preview").html('<fieldset style="width:95%; padding:15px 2%; margin:auto;"><legend style="display:inline !important;"><strong>Preview Email - Material Order</strong></legend>'+shipto_content+preview_mail_content+'</fieldset>');
		}
*/
	});
	// Material mail preview ends ----------


	$("#mail_option").click(function(){
		if($("#reason_content_wrapper").is(":visible") && $("#reason_content").val()==""){
			$("#reason_content").css("border","#F00 solid 1px").focus();
			return false;
		}

	});

	// added for misc order items starts
	$("#add_misc, .edit_misc").click(function(e){
		$(".delete_misc").hide();
		$("label#error_msg").hide();
		$("input#misc_submit").val("add item");
		$('#add_misc_item').trigger("reset");

		if($(this).val()=="Edit"){
			$(".delete_misc").show();
			$("input#misc_submit").val("update");
			var miscid = $(this).parents('tr').find('.misc_itemid').val();
			var sys_order_id = $("#sys_order_id").val();

			$.ajax({
				type:"POST",
				url: root_path+"ajax_process.php",
				data:{"ajax_mode":"get_miscdata", "miscid":miscid, "sys_order_id":sys_order_id},
				dataType:"json",
				async:false,
				success:function(data){
					$("#item_name").val(data.result[0]['item_name']);
					$("#description").val(data.result[0]['description']);
					$("#uom").val(data.result[0]['uom']);
					$("#qty").val(data.result[0]['qty']);
					$("#vendor").val(data.result[0]['vendor']);
					$("#category").val(data.result[0]['category']);
					$("#approved_by").val(data.result[0]['approved_by']);
					$("#material_cost").val(data.result[0]['material']);
					$("#labor_cost").val(data.result[0]['labor']);
					$("#retail_price").val(data.result[0]['retail']);
					$("#misc_process_id").val(miscid);
				}
			});
		}		

		var root = this;
		e.preventDefault();
		$.fancybox({
			maxWidth	: 600,
			maxHeight	: 600,
			href: "#misc_order_items"
		});

	});

	$("#misc_submit").click(function(){
		var verify_data = true;
		var misc_data = {
			"order_id": $("#misc_orderid").val(),
			"item_name": $("#item_name").val(),
			"uom": $("#uom").val(),
			"qty": $("#qty").val(),
			"category": $("#category").val(),
			"material_cost": $("#material_cost").val(),
			"labor_cost": $("#labor_cost").val(),
			"retail_price": $("#retail_price").val(),
			"approved_by": $("#approved_by").val(),
			"vendor": $("#vendor").val(),
			"description": $("#description").val(),
			"order_type": "admin_portal"
		};

		if($("input#misc_submit").val() == "update"){
			misc_data["misc_id"] = $("#misc_process_id").val();
			misc_data["flag"] = "edit";
		}

		$.each(misc_data, function(index, miscval){
			if($.trim(miscval)==""){
				verify_data = false;
			}
		});

		if(verify_data){
			$.ajax({
				type: "POST",
				url: root_path+"ajax_process.php",
				data: {"ajax_mode" : "add_misc_item", "misc_data" : misc_data},
				async: false,
				success: function(data){
					location.reload();
			   }
			});
		} else {
			$("label#error_msg").text("Please enter all the fields").show();
			return false;
		}
	});

	$(".delete_misc").click(function(){
		if(confirm("Delete Miscellaneous Item?")){
			var misc_item_id = $("#misc_process_id").val();
			$.ajax({
				type: "POST",
				url: root_path+"ajax_process.php",
				data: {"ajax_mode" : "del_misc_item", "misc_item_id" : misc_item_id},
				async: false,
				success: function(data){
					location.reload();
				}
			});
		}
	});
	// added for misc order items ends
	
	/* Ack Dealer */
	$("#ack_dealer").click(function(){
		$.ajax({
			type: "POST",
			url: root_path+"ajax_process.php",
			data: {"ajax_mode" : "ack_dealer","order_id" : $('input#order_id').val()},
			success: function(data){
//				if(data=='success')
					location.reload();
			}
		});
	});

});
