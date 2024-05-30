
function unique_vendor_email(value,label) {
	var return_res 	= "false";
	var message 	= "";
	var selr 		= "";
	selr = jQuery('#vendor').jqGrid('getGridParam','selrow'); // returns null if no row is selected  (single row) -- return row pkid
	var ajax_data = {"ajax_mode" : "unique_vendor_email", "email" : value, "vendor_id" : selr}
	$.ajax({
	   type: "POST",
	   url: "ajax_process.php",
	   data: ajax_data,
	   dataType: "json",
	   async: false,
	   success: function(data){
			if(data.result == "true")
			{
				return_res = "true"; 
			}
			else if(data.result == "false")
			{
				message = label+" is already exists";
				return_res = "false"; 
			}				   

	   }
	});				
	//alert("Row id: "+selr);

	if(return_res=="true") {
		return [true,""];
	} else {
		return [false,message];			
	}
}

function branch_user_validation(value,label) {
	var return_res 	= "false";
	var message 	= "";
	var selr 		= "";
	selr = jQuery('#branch_users_mapping').jqGrid('getGridParam','selrow'); // returns null if no row is selected  (single row)

	//to get company main_fabs id
	var branch_id 	= jQuery.trim(jQuery("#TblGrid_branch_users_mapping").find("select#branch").val());
	var user_id 	= value;
	var ajax_data = {"ajax_mode" : "branch_user_validation", "user_id" : user_id, "branch_id": branch_id, "id":selr}
	
	$.ajax({
	   type: "POST",
	   url: "ajax_process.php",
	   data: ajax_data,
	   dataType: "json",
	   async: false,
	   success: function(data){
			if(data.result == "true")
			{
				return_res = "true"; 
			}
			else if(data.result == "false")
			{
				message ="Branch - user mapping is already exists";
				return_res = "false"; 
			}				   
		}
	});	

	if(return_res=="true") {
		return [true,""];
	} else {
		return [false,message];			
	}
}

function customer_validation(value,label) {
	
	var return_res 	= "false";
	var message 	= "";
	var selr 		= "";
	var selrow		= jQuery('#customers').jqGrid('getGridParam','selrow');
	var id			= (isNaN(selrow)? "": selrow);

	var ajax_data = {"ajax_mode" : "customer_validation", "email" : value, "id" : id}
	$.ajax({
	   type: "POST",
	   url: "ajax_process.php",
	   data: ajax_data,
	   dataType: "json",
	   async: false,
	   success: function(data){
			if(data.result == "true") {
				return_res = "true"; 
			} else if(data.result == "false") {
				message = label+" is already exists";
				return_res = "false"; 
			}
	   }
	});				
	//alert("Row id: "+selr);
	
	if(return_res=="true") {
		return [true,""];
	} else {
		return [false,message];			
	}
}
