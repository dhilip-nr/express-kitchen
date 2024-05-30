$(document).ready(function($){
	$(".order_filterhead").css("width", 1000);
	var view="?view="+$.urlParam("view");
	if($.urlParam("view")!=null)
	{
		var view_arr = $.urlParam("view").split(',');
		if($.inArray("bycompany",view_arr)!=-1){
			view+= "&company="+$("select#company_list").val();
		}
		if($.inArray("bybranch",view_arr)!=-1){
			view+= "&branch="+$("select#branch_list").val();
		}
		if($.inArray("bystatus",view_arr)!=-1){
			view+= "&status="+$("select#status_list").val();
		}
		if($.inArray("bwdates",view_arr)!=-1){
			var ftdate = $.urlParam("bwdates").split("#");
			view+= "&bwdates="+ftdate[0]+"<->"+ftdate[1];
		}
	}
	//window.location.href=view;

	var prev_searchCol = "", custom_search = {id: [0, 3, 4] , name: ["Order ID", "Cust. Name", "Cust. Phone"]};

	var oDataTable = $('#order_table').DataTable({
		"processing": true,
		"serverSide": true,
        "ajax": {
			url: 'dashboard_data.php'+ view,
			error: handleAjaxError
		},
		"sorting": [[10,'desc'],[0,'desc'] ],
		"columnDefs": [
			{ "targets": [ 1,2,6,7,8,9,11,12,13 ], "visible": false },
/*
			{ "targets": [ 5, 6, 7, 8, 9, 10, 11, 12, 14], "searchable": false },
			{ "targets": [ 0, 10, 11, 12, 14 ], "class": 'center' },
			{ "targets": [ 5, 6, 7, 8, 10 ], "class": 'right'},
			{ "targets": [ 12, 14 ], "sortable": false }
*/
		],
		"createdRow": function ( row, data, index ) {
            if ( parseFloat(data[9]) < 50 ) {
                $('td', row).eq(8).addClass('belowmargin');
            }
        },
		"custSearchFields": custom_search,
		"pageLength": 15
	});

	$('span#custom_search').on('click', '#search_orderdata', function () {
		var search_field = parseInt($('#search_by_column').val());
		var search_value = $('#search_value').val();
		oDataTable.column(prev_searchCol).search("").column(search_field).search(search_value).draw();
		prev_searchCol = search_field;
		$('#search_value').val(search_value);
	});

/*
	$('#order_table tbody').on( 'hover', 'a.show_comments', function () {
	});
*/
	$('#order_table tbody').on( 'click', 'a.show_comments', function () {
        var data = oDataTable.row( $(this).parents('tr') ).data();
		$.fancybox({
			minWidth	: 400,
			maxWidth	: 600,
			maxHeight	: 600,
			content		: "<b style='display:block; margin-bottom:10px;'>Comments:</b>"+ data[18]
		});
    });

	$(".orderfilter").click(function(){
		var query_string = "";

		var brview_arr = [];
		brview_arr.push($("select#other_filters").val());

		$("input.filter_order:checked").each(function(index,value){
		});

		var multi_company = [];
		$("select.company_list option:selected").each(function(index,value){
			multi_company.push($(this).val());
		});
		var selected_company = multi_company.join(',');

		var multi_branch = [];
		$("select.branch_list option:selected").each(function(index,value){
			multi_branch.push($(this).val());
		});
		var selected_branch = multi_branch.join(',');

		var multi_status = [];
		$("select.status_list option:selected").each(function(index,value){
			multi_status.push($(this).val());
		});
		var selected_status = multi_status.join(',');


		if(selected_company != "") {
			query_string+= "&company="+selected_company;
			brview_arr.push("bycompany");
		}

		if(selected_branch != "") {
			query_string+= "&branch="+selected_branch;
			brview_arr.push("bybranch");
		}

		if(selected_status != "") {
			query_string+= "&status="+selected_status;
			brview_arr.push("bystatus");
		}		

		if(($("input#date_from").val() != "") && ($("input#date_to").val() != "")) {
			query_string+= "&bwdates="+$("#date_from").val()+"<->"+$("#date_to").val();
			brview_arr.push("bwdates");
		}
		var brview = brview_arr.join(',');
		if(brview == "") {
			brview = "all";
		}
		window.location.href="?view="+brview+query_string;
	});

	/*
	$("select#status_list").change(function(){
		var brview_arr = [];
			$("input.filter_order:checked").each(function(index,value){
				brview_arr.push($(this).val());
			});
			var brview = brview_arr.join(',');
		window.location.href="?view="+brview+"&status="+this.value;
	});
	
	$("#date_range #datefilter").click(function(){
		var brview = $("#filter_order").val();
		var bwdates = $("#date_from").val()+"<->"+$("#date_to").val();
		window.location.href="?view="+brview+"&bwdates="+bwdates;
	});
	*/

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

	$(document).on("click", "#tableToExcel", function(){
		$("#order_table").table2excel({
			exclude: ".noExl",
			name: "Orders",
			filename: "ORDERS-"+(""+$.now()).slice(0,-3),
			fileext: ".xls",
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true
		});
	});

});