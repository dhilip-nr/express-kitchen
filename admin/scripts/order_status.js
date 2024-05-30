$(document).ready(function(){
	
	$("li#revision_history").click(function(){
		$("div.active_base_status").slideUp(500);
		$("div#revision_history_show").slideToggle(500);
		$("div#material_order_status_show").slideUp(500);
		$("div#install_order_status_show").slideUp(500);
		$(this).children("div").slideToggle(500);
	});

	$("li#material_order_status").click(function(){
		$("div.active_base_status").slideUp(500);
		$("div#material_order_status_show").slideToggle(500);
		$("div#revision_history_show").slideUp(500);
		$("div#install_order_status_show").slideUp(500);
		$(this).children("div").slideToggle(500);
	});

	$("li#install_order_status").click(function(){
		$("div.active_base_status").slideUp(500);
		$("div#install_order_status_show").slideToggle(500);
		$("div#material_order_status_show").slideUp(500);
		$("div#revision_history_show").slideUp(500);
		$(this).children("div").slideToggle(500);
	});


	$(".shipment_details").click(function(){
		var shipping_info_raw = $(this).parent('tr').find("input.shipping_info").val();
		var shipping_info = JSON.parse(decodeURIComponent(shipping_info_raw));
		$("span#cname").text(shipping_info.carrier);
		$("span#carrier_no").text(shipping_info.carrier_no);
		$("span#pname").text(shipping_info.contact_name);
		$("span#shipping_cost").text("$ "+parseFloat(shipping_info.cost).toFixed(2));
		$("span#date").text(shipping_info);
		$("span#eta_date").text(shipping_info.eta);

		$.fancybox({
			href: "#shipping_information",
			fitToView	: false,
			width		: 'auto',
			height		: 'auto',
			autoSize	: true,
		});
	});

	$(".update_matreceived").click(function(){
		$("#processing_manf").val($(this).parent("tr").attr("data-alias"));
		
		$.fancybox({
			href: "#get_receipt_info",
			fitToView	: false,
			width		: 'auto',
			height		: 'auto',
			autoSize	: false
		});	
	});

	$("#receipt_info_submit").click(function(){
		var order_id = $("input#order_id").val();
		var manufacturer = $("#processing_manf").val();
		var rec_name = $("#get_rec_name").val();
		var rec_date = $("#get_rec_date").val();
		var rec_comment = $("#get_rec_comment").val();
		if(rec_name!="" && rec_date!=""){
			$.ajax({
				type:"post",
				url: root_path+"ajax_process.php",
				data:{"ajax_mode":"update_matreceived", "manufacturer":manufacturer, "rec_name":rec_name, "rec_date":rec_date, "rec_comment":rec_comment, "order_id":order_id},
				success:function(data){
					location.reload();
				}
			});
		}else{
			$("label#error_msg").text("Please fill receipt information.!");
		}
	});

	$(".delivery_details").click(function(){
		var delivery_info_raw = $(this).parent('tr').find("input.delivery_info").val();
		var delivery_info = JSON.parse(decodeURIComponent(delivery_info_raw));
		$("span#put_rec_person").text(delivery_info.receiver);
		$("span#put_rec_date").text(delivery_info.date);
		$("span#put_rec_comment").text(delivery_info.comment);

		$.fancybox({
			href: "#delivery_information",
			fitToView	: false,
			width		: 'auto',
			height		: 'auto',
			autoSize	: true,
		});
	});

	$(".problem_report").click(function(){
		$.fancybox({
			href: "#problem_information",
			fitToView	: false,
			width		: 'auto',
			height		: 'auto',
			autoSize	: true,
		});
	});

});