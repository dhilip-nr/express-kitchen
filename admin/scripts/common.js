$.urlParam = function(name){
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null){
       return null;
    } else {
       return results[1] || 0;
    }
}

$(document).ready(function(){

	$('#disc_click').click(function(){
		var disc_sur_amt = $("#disc_sur_amt").val();
		var order_id = $("#order_id").val();
		var net_amt = $("#net_amt").val();

		if(disc_sur_amt != "0.00") {
			$.ajax({
				type:"POST",
				url: root_path+"ajax_process.php",
				data:{"ajax_mode":"insert_disc_amt", "order_id":order_id, "disc_sur_amt":disc_sur_amt, "net_amt":net_amt},
				async:false,
				success:function(data){
					location.reload();
				}
			}); 
		} else {
			 alert("Please Enter $ Amount");
			 return false;
		}
	});
	$(".cartcount").click (function(){
		$("#recent_items").fadeToggle();
	});

	$(document).on("click", "#category_items ul li span", function(){
			$("#category_items ul li span").removeClass("active");
			$(this).addClass("active");
	});
/*
	$("#help-fancybox").fancybox({
		fitToView: false,
		type: "iframe",
		width: '540px',
		height: '545px',
		padding: 0,
		margin: 0,
		autoSize: false,
		closeClick: false,
		showCloseButton: false,
		hideOnOverlayClick: false,
		hideOnContentClick: false,
		openEffect: 'none',
		closeEffect: 'none'
	});
*/
});

function validate_update(frm_id){
	var frm_act = true;
	var msg = "";
	$("input.ipeditable").each(function(index, value){
		if($(this).attr("data-row")==frm_id){
			var item_minmax = $(this).attr("data-minmax").split("-");
			if(parseFloat(item_minmax[0])>parseFloat($(this).val()) || parseFloat(item_minmax[1])<parseFloat($(this).val())){
				frm_act = false;
				msg += $(this).attr("data-optnm")+" should be in size between "+item_minmax[0]+" and "+item_minmax[1]+"\n";
			}				
		}
	});
	msg != "" ? alert(msg) : "";

	return frm_act;
}

function handleAjaxError(xhr, textStatus, errorThrown){
	switch(xhr.status){
		case 401: alert("It seems your session was expired, so we are redirecting you to login page.");
				  window.location.href=root_path+"login.html";
				break;
		case 404: alert("Requested service resource was not found. Please contact the administrator ...!");
				break;
		default: alert("Something went wrong, your request could not be completed right now.\n\nPlease try again later...!");
				break;
	}
}
