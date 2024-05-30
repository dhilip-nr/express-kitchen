$(document).ready(function(){

/*   Adding text editor for mail content - Begins   
	new nicEditor({
		buttonList : ['fontSize', 'bold', 'italic', 'underline', 'forecolor', 'link', 'left', 'center', 'right', 'justify', 'strikeThrough', 'ol', 'ul'],
		iconsPath : root_path+'../images/nicEditorIcons.gif',
		maxHeight : 100
	}).panelInstance('revord_comments').panelInstance('cancelord_comments');
/*   Adding text editor for mail content - Ends   */

	$("#yourdesign").click(function(e){
		$.fancybox({
			href: "#orderdesign",
			maxWidth    : 700,
			maxHeight   : 600,
		});
	});
	$(".ipclear").click(function(e){
		$(".nicEdit-main").html("");
		$("#error_msg").html("");
	});

	$("#sendmail").click(function(e){
		$.fancybox({
			href: "#sendmailcopy",
			maxWidth	: 700,
			maxHeight	: 600,
		});
	});

	$("#export_order_todata").click(function(e){
//		$("#exportDataOption input[name=with_price][value=1]").prop("checked", true);
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
			data:{"ajax_mode":"export_to_file", "order_id":order_id, "order_type":"SO", "export_type":export_type, "with_price":with_price, "mfg":mfg},
//			dataType:"json",
			async:false,
			success:function(data){
				window.open(root_path+"order_export.php?id="+data);
			}
		});
	});

	$("#revise").click(function(e){
		var root = this;
		e.preventDefault();
		$.fancybox({
			maxWidth	: 800,
			maxHeight	: 600,
			href: "#revision_comments",
			fitToView	: false,
			width		: '600px',
//			width		: '410px',
			height		: 'auto',
			autoSize	: false,
			closeClick	: false,
			showCloseButton: false,
			hideOnOverlayClick: false,
			hideOnContentClick: false,
			openEffect	: 'none',
			closeEffect	: 'none'
		});
	});

	$("#cancelorderbtn").click(function(e){
		var root = this;
		e.preventDefault();
		$.fancybox({
			maxWidth	: 800,
			maxHeight	: 600,
			href: "#cancelorder_modal",
			fitToView	: false,
			width		: '600px',
//			width		: '410px',
			height		: 'auto',
			autoSize	: false,
			closeClick	: false,
			showCloseButton: false,
			hideOnOverlayClick: false,
			hideOnContentClick: false,
			openEffect	: 'none',
			closeEffect	: 'none'
		});
	});

	$("form#cancel_order").submit(function(e){
		var isvalid = true;
		if($("#cancel_order #action").val()=="Cancel Order") {
			var rev_comments = $.trim($("#cancel_order div.nicEdit-main").text());
			if(rev_comments == ""){
				$("#cancel_order #error_msg").html("Please provide reason to cancel this order!");
				$("#cancel_order div.nicEdit-main").focus();
				isvalid = false;
			}
		}
		return isvalid;
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
	
	$(".cancel").click(function(){
		window.close();
	});
	
	$("form#sales_order_frm").submit(function(e){
		var isvalid = true; 
		if($("#sales_order_frm #action").val()=="Save")	{
			var job_id = $("input#job_id").val();
			if(job_id === ""){
				alert("Enter  JOB ID  to save the order!");
				$("input#job_id").focus();
				isvalid = false;
			}
		}
		return isvalid;
	});

	$("form#revision").submit(function(e){
		var isvalid = true;
		if($("#revision #action").val()=="Revise Order") {
			var rev_comments = $.trim($("#revision div.nicEdit-main").text());
			if(rev_comments == ""){
				$("#revision #error_msg").html("Please provide comments to sent back the order for revision!");
				$("#revision div.nicEdit-main").focus();
				isvalid = false;
			}
		}
		return isvalid;
	});

	$('#convert_order').click(function(){
		$.ajax({
			type: "POST",
			url: root_path+"ajax_process.php",
			data: {"ajax_mode" : "convert_order", "order_id" : $("input[name=order_id").val()},
			beforeSend: function(){
				$("#loader").show();
			},
			success: function(data){
				if(data=="success")
					location.reload();
			}
		});
	});

	$(".addtype_file").click(function(e){
		var form_id = $(this).closest("form").attr("id");
	    $("#"+form_id+" td.td_add_file").append('<div><input type=\"file\" width=\"200px;\" name=\"attach_file_name[]\" /><label class=\"label_type_file\"><input type="button" style="background:url(../images/actions/remove.png); margin:4px; width:24px; height:24px; border:none;" width="24" height="24" class=\"removetype_file\" /></label></div>');
    });
	
	$(document).on("click", ".removetype_file", function() {
		$(this).closest("div").remove();
	});

});

function print_content() {
	$(".print_order").hide();
	document.title = "Sales Order - "+$.urlParam("id");
	window.print();
	$(".print_order").show();
}