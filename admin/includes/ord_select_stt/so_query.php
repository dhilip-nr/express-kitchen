<?php
class SoQry {
	function orderInfo($order_id){
		$orderinfoqry = "select 
			ro.id,
			ro.order_id as job_order_id,
			ro.company,
			ro.company_id,
			concat('".ORD_PREFIX."',ro.id) as order_id,
			concat('".CID_PREFIX."',rc.id) as customer_id,
			concat(rc.firstname,' ',rc.lastname) as customer_name,
			rc.email as customer_email,
			rc.telephone as customer_telephone,
			rc.address as customer_address,
			rc.city as customer_city,
			rc.state as customer_state,
			rc.zipcode as customer_zipcode,
			concat(ru.firstname, ' ', ru.lastname) repname,
			ro.total_product,
			ro.total_qty,
			ro.total_amount,
			ro.admin_fee,
			ro.lt_amt,
			ro.lfwp_amt,
			ro.promo_type,
			ro.promo_percent,
			ro.promo_amt,
			ro.apd_amt,
			ro.gen_con,
			ro.permit_percent,
			ro.permit_max,
			ro.net_amount,
			ro.jobid,
			ro.lead_id,
			ro.username as rep_name,
			ro.branchid as branch_id,
			ro.store_num,
			ro.installer_id,
			ro.sent_installer,
			ro.attachments as attached_files,
			ro.status as order_status,
			ro.comments,
			ro.disc_sur_amt
			from 
			remode_orders ro
			left join remode_customers rc on rc.id = ro.customer_id
			left join remode_users ru on ru.username = ro.username
			where ro.id = '".$order_id."'
			order by ro.order_id asc";
		return $orderinfoqry;
	}

	function productInfo($order_id){
		$productinfoqry = "select 
			'' as cat_id,
			roi.name as item_name,
			roi.description as description,
			roi.category,
			roi.slfeet as slfeet,
			roi.adnl_price,
			roi.other_price,
			roi.price as price,
			roi.quantity as quantity,
			roi.uom as uom,
			roi.options,
			roi.dimension,
			roi.pricingmodel
			from 
			remode_orders 
			left join remode_customers on remode_customers.id = remode_orders.customer_id
			left join remode_orderitems roi on remode_orders.order_id = roi.order_id
			where remode_orders.id = '".$order_id."' AND roi.is_approved!=0
			order by remode_orders.order_id asc";
			
		return $productinfoqry;
	}
}
?>