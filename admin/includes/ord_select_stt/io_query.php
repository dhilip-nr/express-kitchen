<?php
class IoQry {
	function orderInfo($order_id){
		$orderinfoqry = "select 
			ro.id,
			ro.order_id as job_order_id,
			ro.company,
			ro.company_id,
			ro.branchid,
			ro.store_num,
			concat('".ORD_PREFIX."',ro.id) as order_id,
			ro.installer_id as installer_id,
			'' as branch_installer_email_cc,
			concat(rc.firstname,' ',rc.lastname) as customer_name,
			rc.firstname as customer_fname,
			rc.lastname as customer_lname,
			rc.email as customer_email,
			rc.telephone as customer_telephone,
			rc.address as customer_address,
			rc.city as customer_city,
			rc.state as customer_state,
			rc.zipcode as customer_zipcode,
			ro.lt_amt,
			ro.lfwp_amt,
			ro.admin_fee,
			ro.total_product,
			ro.total_qty,
			ro.total_amount,
			ro.promo_amt,
			ro.permit_percent,
			ro.permit_max,
			ro.jobid,
			ro.sent_installer,
			ro.status as order_status,
			ro.on_change,
			ro.comments,
			ro.attachments as order_attachments,
			rid.installer as installer_company,
			rid.provider_num as vendor_num,
			rid.ins_rate_id as ins_rate_id,
			rid.firstname as installer_firstname,
			rid.lastname as installer_lastname,
			rid.mobile as installer_mobile,
			rid.officephone as installer_officephone,
			rid.email as installer_email,
			rid.email_cc as installer_email_cc,
			rid.officeaddress as installer_officeaddress,
			rid.city as installer_city,
			rid.state as installer_state,
			rid.zipcode as installer_zipcode
			from 
			remode_orders ro
			left join remode_customers rc on rc.id = ro.customer_id
			left join remode_installers rid on rid.id = ro.installer_id
			left join remode_branch_master rb on rb.id = ro.branchid
			where ro.id = '".$order_id."'";
		return $orderinfoqry;
	}

	function productInfo($order_id){
		$productinfoqry = "select 
			' ' as cat_id,
			'EA' as orderinguom,
			'1' as orderingincrement,
			roi.id as orderitems_id,
			roi.name as item_name,
			roi.description as description,
			roi.price as price,
			roi.options,
			roi.minmax as minmax,
			roi.slfeet as slfeet,
			roi.quantity as quantity,
			roi.uom as uom,
			roi.pricingmodel as remode_orderitem_pricingmodel,
			roi.laborcost,
			roi.by_ip,
			roi.is_approved,
			roi.old_qsf,
			rv.vendor as manufacturer,
			rv.email as manufacturer_email,
			rv.phone as manufacturer_phone,
			concat(rv.address,', ',rv.city,', ',rv.state,' ',rv.zipcode) as manufacturer_address
			from 
			remode_orders ro
			left join remode_orderitems roi on ro.order_id = roi.order_id
			left join remode_installers rid on rid.id = ro.installer_id
			left join remode_vendors rv on rv.vendor = roi.manufacturer
			where ro.id = '".$order_id."' AND roi.is_approved!=0
			group by roi.id
			order by rv.vendor asc";			
		return $productinfoqry;
	}
}
?>