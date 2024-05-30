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

	$("#sendmail").click(function(e){
		$.fancybox({
			href: "#sendmailcopy",
			maxWidth	: 700,
			maxHeight	: 600,
		});
	});

	$("#export_order_todata").click(function(e){
		$("#exportDataOption input[name=with_price][value=1]").prop("checked", true);
		$.fancybox({
			href: "#exportDataOption",
			maxWidth	: 700,
			maxHeight	: 600,
		});
	});

	$("#exportDataOption #do_export").click(function(e){
		var order_id = $("#exportDataOption input[name=order_id]").val();
		var export_type = $("#exportDataOption input[name=export_type]:checked").val();
		var with_price = $("#exportDataOption input[name=with_price]:checked").val();
		var mfg = $("#exportDataOption input[name=mfg]").val();

		$('a.fancybox-close').trigger('click');

		$.ajax({
			type:"POST",
			url: root_path+"ajax_process.php",
			data:{"ajax_mode":"export_to_file", "order_id":order_id, "order_type":"IO", "export_type":export_type, "with_price":with_price, "mfg":mfg},
//			dataType:"json",
			async:false,
			success:function(data){
				window.open(root_path+"order_export.php?id="+data);
			}
		});
	});

	$(".preview_file").click(function(e){
		var imag_name = $(this).text();
		$("div#file_preview_wrap").html("");
		$("div#file_preview_wrap").append("<center><img src='../uploads/"+imag_name+"' alt='Uploaded Images' style='height:500px;'></center>");
		var root = this;
		e.preventDefault();

		$.fancybox({
			href: "#file_preview_wrap",
			maxWidth	: 700,
			maxHeight	: 600,
		});
	});

	$(".placeorder").click(function(e){
		var installer_sent = $("#installer_sent").val();
		var confirm_sent = true;
		if(installer_sent!=""){
			confirm_sent = confirm("This installer order has placed already. Do you want to place the order again?");
		}
		if(confirm_sent == true){
			var root = this;
			e.preventDefault();
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

	/* this is for fancybox window close */
	$("#mail_cancel").click(function(e){
		e.preventDefault();
		$.fancybox.close();	
	});
	
	/* this is for material order window close */
	$("#cancel").click(function(){
		window.close();
	});
	
	
	$(document).on("click","input.placeorder",function(){
		var root_placeorder = $(this);
		var main_mail_content = $.trim(root_placeorder.parents("table").html());
		$("#html_content_mail").append('<table cellpadding="5" cellspacing="0"  style="width:100%; border-collapse:collapse; border:solid 1px #ccc; min-width:650px;">'+main_mail_content+'</table>');
		return false;
	});
	
	$("form.install_order_mailfrm").submit(function(){
		$("div.fancybox-overlay").hide();
		$("#loader").show();

		return true;
	});
	
	$(".addtype_file").click(function(e){
		var form_id = $(this).closest("form").attr("id");
	    $("#"+form_id+" td.td_add_file").append('<div><input type=\"file\" width=\"200px;\" name=\"attach_file_name[]\" /><label class=\"label_type_file\"><input type="button" style="background:url(../images/actions/remove.png); margin:4px; width:24px; height:24px; border:none;" width="24" height="24" class=\"removetype_file\" /></label></div>');
    });
	
	$(document).on("click", ".removetype_file", function() {
		$(this).closest("div").remove();
	});

	 
	//	For quantity update starts ----------
	$(".change_qty").click(function(){
		$(this).parents("tr").children("td.non_editable_row").hide();
		$(this).parents("tr").children("td.editable_row").show();

		$(this).parents("tr").children("td").find("button.change_qty").hide();
		$(this).parents("tr").children("td").find("button.update_qty").show();
		$(this).parents("tr").children("td").find("button.delete_adnlitem").show();
	});

	$(".update_qty").click(function(){
		$("#loader").show();
	});
	//	For quantity update ends ----------

	// Additional Labor actions - Starts
	$(".delete_adnlitem").click(function(){
		if(confirm("Delete Adnl. Labor Item?")){
			var adnl_item_id = $(this).val();
			var order_id = $("#order_id").val();
			$.ajax({
				type: "POST",
				url: root_path+"ajax_process.php",
				data: {"ajax_mode" : "del_adnl_item", "order_id":order_id, "adnl_item_id" : adnl_item_id},
				async: false,
				beforeSend: function(){
					$("#loader").show();
				},
				success: function(data){
					location.reload();
				}
			});
		}
	});
	// Additional Labor actions - Ends


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
					$("#misc_by_ins").val(data.result[0]['by_ip']);
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
				beforeSend: function(){
					$("div.fancybox-overlay").hide();
					$("#loader").show();
				},
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
			var misc_by_ins = $("#misc_by_ins").val();

			$.ajax({
				type: "POST",
				url: root_path+"ajax_process.php",
				data: {"ajax_mode" : "del_misc_item", "misc_item_id" : misc_item_id, "misc_by_ins" : misc_by_ins},
				async: false,
				beforeSend: function(){
					$("div.fancybox-overlay").hide();
					$("#loader").show();
				},
				success: function(data){
					location.reload();
				}
			});
		}
	});
	// added for misc order items ends
	

	// Approve or Refuse order item changes made by installers - Starts	
	$("#approve_inschanges").click(function(e){
		if(confirm("Please make sure, do you want to approve these changes ?")){
			$("#loader").show();
		} else { return false; }
	});
	
	if($("input#installer_moditems_cnt").val()==0){
		$("div#ins_notification").text("Notify the installer that changes has been refused!");
	}
	
	$("#notify_changes_refused").click(function(e){
		if(confirm("Please make sure, do you want to send an notification of the changes refused ?")){
			$("#loader").show();
		} else { return false; }
	});
	// Approve or Refuse order item changes made by installers - Ends





/*
	$("input#export_to_excel").click(function(){
		var order_id = $("#order_id").val();
		$.ajax({
			type: "POST",
			url: root_path+"ajax_process.php",
			data: {"ajax_mode" : "export_to_excel", "order_id":order_id},
			async: false,
			beforeSend: function(){
				$("#loader").show();
			},
			success: function(data){
				window.open(root_path+"force_file_download.php?order_info="+data);
			}
		});
	});
*/

	
});

function print_content(){
	$(".print_order").hide();
	document.title = "Install Order - "+$.urlParam("id");
	window.print();
	$(".print_order").show();
}