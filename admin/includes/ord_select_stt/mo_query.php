<?php
class MoQry {
	function orderInfo($order_id){
		$orderinfoqry = "select 
			rid.firstname as inst_firstname,
			rid.lastname as inst_lastname,
			rid.officephone as inst_phone,
			rid.email as inst_email,
			rid.installer as inst_company,
			rid.officeaddress as inst_address,
			rid.city as inst_city,
			rid.state as inst_state,
			rid.zipcode as inst_zipcode,
			rid.ship_to,
			ro.id,
			ro.company,
			ro.company_id,
			ro.branchid,
			ro.ins_mode,
			ro.store_num,
			ro.order_id as job_order_id,
			ro.sent_material,
			ro.sent_installer,
			ro.dealer_mat_status,
			ro.comments,
			concat('".ORD_PREFIX."',ro.id) as order_id,
			ro.installer_id as installer_id, 'userdefined' as ins_type,
			rb.name branch,
			rb.branch_code,
			rb.branch_num,
			'' as branch_material_order_cc,
			concat(rc.firstname,' ',rc.lastname) as customer_name,
			rc.firstname as customer_fname,
			rc.lastname as customer_lname,
			rc.email as customer_email,
			rc.telephone as customer_telephone,
			rc.address as customer_address,
			rc.city as customer_city,
			rc.state as customer_state,
			rc.zipcode as customer_zipcode,
			ro.total_product,
			ro.total_qty,
			ro.total_amount,
			ro.jobid,
			ro.mat_hold as material_hold,
			ro.status as order_status
			from remode_orders ro
			left join remode_branch_master rb on ro.branchid = rb.id 
			left join remode_customers rc on rc.id = ro.customer_id
			left join remode_installers rid on rid.id = ro.installer_id
			where ro.id = '".$order_id."'";
		return $orderinfoqry;
	}

	function productInfo($order_id, $mfg=""){
		$where = ($mfg!=""? " AND rv.alias='".$mfg."'" : "");
		$productinfoqry = "select 
			roi.name as item_name,
			'1' as cat_id,
			roi.description as description,
			roi.id as orderitems_id,
			roi.slfeet as slfeet,
			roi.price as price,
			roi.options,
			roi.minmax as minmax,
			roi.quantity as quantity,
			roi.uom as uom,
			roi.productcost,
			roi.pricingmodel as remode_orderitem_pricingmodel,
			roi.comments,
			'EA' as orderinguom,
			'1' as orderingincrement,
			rv.alias as manufacturer_alias,
			rv.detail as manufacturer_detail,
			rv.group as manufacturer,
			rv.contact as manufacturer_contact,
			rv.email as manufacturer_email,
			rv.email_cc as manufacturer_emailcc,
			rv.phone as manufacturer_phone,
			concat(rv.address,', ',rv.city,', ',rv.state,' ',rv.zipcode) as manufacturer_address
			from remode_orders
			left join remode_orderitems roi on remode_orders.order_id = roi.order_id
			left join remode_vendors rv on rv.vendor = roi.manufacturer
			where remode_orders.id = '".$order_id."' AND roi.is_approved!=0 ".$where."
			group by roi.id
			order by rv.vendor asc";
			
		return $productinfoqry;
	}

	function miscInfo($order_id, $mfg=""){
		$where = ($mfg!=""? " AND (rom.vendor='".$mfg."' or rv.alias='".$mfg."')" : "");
		$productinfoqry="SELECT 
			rom.*,
			CASE trim(rv.group)
				WHEN (trim(rv.group) IS NULL) THEN trim(rv.group) ELSE 'UNKNOWN'
			END as manufacturer,
			rv.contact as manufacturer_contact,
			rv.email as manufacturer_email,
			rv.phone as manufacturer_phone,
			concat(rv.address,', ',rv.city,', ',rv.state,' ',rv.zipcode) as manufacturer_address
			FROM remode_orderitems_miscs rom
			left join remode_vendors rv on rv.alias = rom.vendor 
			WHERE rom.order_id='".$order_id."' AND rom.is_approved!=0".$where."
			group by rom.id
			order by rv.vendor asc";

		return $productinfoqry;
	}
}
?>