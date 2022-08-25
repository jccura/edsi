<?php

	ini_set('max_execution_time', 0);
	
	error_reporting(E_ALL ^ E_DEPRECATED);
	
	date_default_timezone_set('Asia/Manila');
    
	// Version
	define('VERSION', '1.5.1.3');

	// Config
	require_once('config.php');

	// Startup
	require_once('db.php');
	
	// log
	require_once('log.php');
	
	require_once('mysql.php');
	
	$log = new Log("error.txt");
	
	logger('START', "Start of Processing",$log);
	echo "Start of Processing<br>";	
	
	// Database 
	$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	
	// determine half
	$date = date("Y-m-d");
	
	//delivered orders
	$sql = "select a.request_id, a.request_from user_id, a.approval_date
			from oc_request a
			where a.status = 77
			and a.user_group_from = 39 
			order by a.approval_date ";
	$query = $db->query($sql);	
	$requests = $query->rows;
				
	foreach($requests as $r) {
		$sql = "insert into oc_sales_inventory(user_id, request_id, request_detail_id, item_id, srp, tools, 
							service_fee, topup, cost, 
							income, tax, distributor_price, reseller_price, direct_referral, cv,system_fee,
							shipping, commissions, date_added)
						SELECT  a.request_from user_id, a.request_id, b.request_detail_id, b.item_id, c.price*b.qty_on_hand srp, c.tools*b.qty_on_hand tools, 
							c.service_fee*b.qty_on_hand service_fee, c.top_up*b.qty_on_hand top_up, c.cost*b.qty_on_hand cost, 
							c.income*b.qty_on_hand income, c.tax*b.qty_on_hand tax,
							(c.price * (100 - c.distributor_discount_per) * b.qty_on_hand)/100 distributor_price, 
							(c.price * (100 - c.reseller_discount_per) * b.qty_on_hand)/100 reseller_price, 
							c.direct_referral*b.qty_on_hand direct_referral, c.cv*b.qty_on_hand cv,c.system_fee * b.qty_on_hand system_fee,
							c.shipping * b.qty_on_hand shipping, c.commissions * b.qty_on_hand commissions, a.approval_date
						  FROM oc_request a
						  join oc_request_details b on (a.request_id = b.request_id)
						  join gui_items_tbl c on(b.item_id = c.item_id)
						 Where a.request_id = ".$r['request_id'];
		$db->query($sql);
		
		$sql = "SELECT  sum(c.system_fee * b.qty_on_hand) system_fee
				  FROM oc_request a
				  join oc_request_details b on (a.request_id = b.request_id)
				  join gui_items_tbl c on(b.item_id = c.item_id)
				 where a.request_id = ".$r['request_id'];	 
		$query = $db->query($sql);
		$system_fee = $query->row['system_fee'];
					
		if($system_fee > 0) {
			$sql = "select user_id
					  from oc_user 
					 where user_group_id = 60
					   and `status` = 1 
					  limit 1 ";
			$query = $db->query($sql);
			if($query->row['user_id']) {
				$gniuse_admin_id = $query->row['user_id'];
				if($gniuse_admin_id > 0) {
					$sql = "select description
							  from oc_commission_type 
							 where commission_type_id = 45 ";
					$query = $db->query($sql);
					$remarks = $query->row['description'];
					
					$sql = "update oc_user set ewallet = ewallet + ".$system_fee."
							 where user_id = ".$gniuse_admin_id;
					$db->query($sql);
					
					$sql = "insert into oc_ewallet_hist 
								set user_id = ".$gniuse_admin_id.",
									request_id = ".$r['request_id'].",
									source_user_id = ".$r['user_id'].",
									commission_type_id = 45,
									credit = ".$system_fee.",
									tax = 0,
									gross_amt = ".$system_fee.",
									remarks = '".$remarks."',
									created_by = 1,
									date_added = '".$r['approval_date']."' ";
					$db->query($sql);
				}
			}
		}
	}

	function insertEwallet($db, $income, $user_id, $source_user_id, $order_id, $commission_type_id, $created_by, $tax_flag = 0, $dates) {
		
		if($tax_flag == 1) {
			$tax = $income * 0.1;
			$net = $income * 0.9;
		} else {
			$tax = 0;
			$net = $income;
		}
		
		$sql = "select description
				  from oc_commission_type 
				 where commission_type_id = ".$commission_type_id;
		$query = $db->query($sql);
		$remarks = $query->row['description'];
		
		$sql = "update oc_user set ewallet = ewallet + ".$net."
				 where user_id = ".$user_id;
		$db->query($sql);
		
		$sql = "insert into oc_ewallet_hist 
					set user_id = ".$user_id.",
						order_id = ".$order_id.",
						source_user_id = ".$source_user_id.",
						commission_type_id = ".$commission_type_id.",
						credit = ".$net.",
						tax = ".$tax.",
						gross_amt = ".$income.",
						remarks = '".$remarks."',
						created_by = ".$created_by.",
						date_added = '".$dates."' ";
		$db->query($sql);
	}

	function now($date = null) {
		
		if(isset($date)) {
			date_default_timezone_set('Asia/Manila');			
			$now = new DateTime($date);				
		} else {
			date_default_timezone_set('Asia/Manila');			
			$now = new DateTime();		
		}
		
		return $now;
	}
	
	function getDateXDayFromDate($date, $day_number) {

		date_default_timezone_set('Asia/Manila');
		
		$now = new DateTime($date);
		for($i = 1; $i <= $day_number; $i++) {
			$now->modify("+1 days");
		} 
		
		return $now;
	}	

	function nowDateString($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();		
		}
		
		return $now->format('Y-m-d');
	}	
	
	function nowString($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();		
		}
		
		return $now->format('Y-m-d H:i:s');
	}
	
	function logger($var1, $var2, $log) {
		$log->write($var1."==>".$var2);
	}
?>