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
	$sql = "select a.order_id, a.user_id, a.payment_confirmed_date
			from oc_order a
		   where a.payment_confirmed_date is not null ";
	$query = $db->query($sql);	
	$orders = $query->rows;
				
	foreach($orders as $o) {
		echo $o['order_id']." - ".$o['payment_confirmed_date']."<br>";
		
		$sql = "insert into oc_sales_inventory(user_id, order_id, order_det_id, item_id, srp, tools, 
						service_fee, topup, cost, 
						income, tax, distributor_price, reseller_price, direct_referral, cv,system_fee,
						shipping, commissions, date_added)
					select  a.user_id, a.order_id, b.order_det_id, b.item_id, c.price*b.quantity srp, c.tools*b.quantity tools, 
						c.service_fee*b.quantity service_fee, c.top_up*b.quantity top_up, c.cost*b.quantity cost, 
						c.income*b.quantity income, c.tax*b.quantity tax,
						(c.price * (100 - c.distributor_discount_per) * b.quantity)/100 distributor_price, 
						(c.price * (100 - c.reseller_discount_per) * b.quantity)/100 reseller_price, 
						c.direct_referral*b.quantity direct_referral, c.cv*b.quantity cv,c.system_fee * b.quantity system_fee,
						c.shipping * b.quantity shipping, c.commissions*b.quantity commissions, a.payment_confirmed_date
					  from oc_order a
					  join oc_order_details b on (a.order_id = b.order_id)
					  join gui_items_tbl c on(b.item_id = c.item_id)
					 where a.order_id = ".$o['order_id'];
		$db->query($sql);
		
		$sql = "select sum(c.system_fee) system_fee
				  from oc_order a
				  join oc_order_details b on (a.order_id = b.order_id)
				  join gui_items_tbl c on(b.item_id = c.item_id)
				 where a.order_id = ".$o['order_id'];	 
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
					insertEwallet($db, $system_fee, $gniuse_admin_id, $o['user_id'], $o['order_id'], 46, 1, 0, $o['payment_confirmed_date']);
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