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
	$sql = "select a.user_id, a.order_id, b.order_det_id, b.item_id, (b.quantity * c.cv) cv, a.actual_delivery_date dates
			  from oc_order a
			  join oc_order_details b on(a.order_id = b.order_id)
			  join gui_items_tbl c on(b.item_id = c.item_id and c.cv > 0)
			where a.status_id in(35) ";
	$query = $db->query($sql);	
	$ord_dtls = $query->rows;
				
	foreach($ord_dtls as $ord) {
		distributeCommission($db,$ord['user_id'], $ord['order_id'], $ord['order_det_id'], $ord['item_id'], $ord['cv'], $ord['dates']);
	}

	//delivered orders
	$sql = "select a.user_id, a.order_id, b.order_det_id, b.item_id, (b.quantity * c.cv) cv, a.pickedup_date dates
			  from oc_order a
			  join oc_order_details b on(a.order_id = b.order_id)
			  join gui_items_tbl c on(b.item_id = c.item_id and c.cv > 0)
			where a.status_id in(152) ";
	$query = $db->query($sql);	
	$ord_dtls = $query->rows;
				
	foreach($ord_dtls as $ord) {
		distributeCommission($db,$ord['user_id'], $ord['order_id'], $ord['order_det_id'], $ord['item_id'], $ord['cv'], $ord['dates']);
	}

	//delivered orders
	$sql = "select a.user_id, a.order_id, b.order_det_id, b.item_id, (b.quantity * c.cv) cv, a.payment_confirmed_date dates
			  from oc_order a
			  join oc_order_details b on(a.order_id = b.order_id)
			  join gui_items_tbl c on(b.item_id = c.item_id and c.cv > 0)
			where a.payment_confirmed_date is not null ";
	$query = $db->query($sql);	
	$ord_dtls = $query->rows;
				
	foreach($ord_dtls as $ord) {
		distributeCommission($db,$ord['user_id'], $ord['order_id'], $ord['order_det_id'], $ord['item_id'], $ord['cv'], $ord['dates']);
	}
	
	function distributeCommission($db,$user_id, $order_id, $order_det_id, $item_id, $item_cv, $dates) {
		if($item_cv > 0) {
			
			//determine the usergroup of the user
			$sql ="select user_id, user_group_id 
					 from oc_user 
					where user_id =	".$user_id;
			$query = $db->query($sql);
			$user_group = $query->row['user_group_id'];
			
			//dapat ang usergroup ay 56 o 46
			if($user_group == 56 or $user_group == 46){
				//gagamitin ito para sa rank bonus ng mga higher ranking
				$sapphire_percentage = 0.05;
				$emerald_percentage = 0.05;
				$ruby_percentage = 0.03;
				$diamond_percentage = 0.02;
				
				//then mag bigyan sa ewallet ng as rebates , 20% item_cv of order
				insertEwallet($db,($item_cv * 0.2), $user_id, $user_id, $order_id, 42, 0, 0, $dates);	
				//pag may distributor or reseller
				//bibigyan ng 10% ng cv ang 1st to 6th level of his/her upline 
				$sql = "select a.sponsor_user_id ,b.month1_cv, b.unilevel_req_exempted,b.username
						  from oc_unilevel a
						  join oc_user b on (a.sponsor_user_id = b.user_id)
						 where a.user_id = ".$user_id."
						   and (b.month1_cv > 105 or b.unilevel_req_exempted = 1 ) 
						 limit 6";
				$query = $db->query($sql);
				$sponsors = $query->rows;
				$level = 1;		
				
				foreach($sponsors as $sp){
					//bigyan ang mga sponsor upperline of the user
					insertEwalletWithLevel($db,($item_cv * 0.1), $sp['sponsor_user_id'], $user_id, $order_id, 43, 0, 0,$level, $dates);	
					
					$level += 1;
				}
				
					//hahanapin nya ang sapphire executive
				$sql = "select count(1) total, a.sponsor_user_id
						  from oc_unilevel a
						  join oc_user b on (a.sponsor_user_id = b.user_id) 
						 where a.user_id = ".$user_id."
						   and b.rank_id = 2
						 order by level
						  limit 1";
						  
				$query = $db->query($sql);
				$sapphire_user_id = $query->row['sponsor_user_id'];
				$total_count = $query->row['total'];
				
				if($total_count > 0){
					//if meron bigay sa kanya ung rank bonus
					insertEwallet($db,($item_cv * $sapphire_percentage), $sapphire_user_id, $user_id, $order_id, 44, 0, 0, $dates);	

					$sapphire_percentage = 0;//declare nya ang sapphire percentage sa zero if may nakuha na ung for sapphire executive
				}
				//hahanapin nya ang emerald executive
				$sql = "select count(1) total, a.sponsor_user_id
						  from oc_unilevel a
						  join oc_user b on (a.sponsor_user_id = b.user_id) 
						 where a.user_id = ".$user_id."
						   and b.rank_id = 3
						 order by level
						  limit 1";
						  
				$query = $db->query($sql);
				$emerald_user_id = $query->row['sponsor_user_id'];
				$total_count = $query->row['total'];
				
				if($total_count > 0){//if merong nakuhang sapphire executive
					//if meron bigay sa kanya ung rank bonus
					$emerald_percent = $emerald_percentage + $sapphire_percentage;
					insertEwallet($db,($item_cv * $emerald_percent), $emerald_user_id, $user_id, $order_id, 44, 0, 0, $dates);	
					
					//declare nya ang sapphire at emerald percentage sa zero kasi nakuha na ni emerald
					$sapphire_percentage = 0;
					$emerald_percentage = 0;
				}
				//hahanapin naman nya ang ruby executive
				$sql = "select count(1) total, a.sponsor_user_id
						  from oc_unilevel a
						  join oc_user b on (a.sponsor_user_id = b.user_id) 
						 where a.user_id = ".$user_id."
						   and b.rank_id = 4
						 order by level
						  limit 1";
						  
				$query = $db->query($sql);
				$ruby_user_id = $query->row['sponsor_user_id'];
				$total_count = $query->row['total'];
				
				if($total_count > 0){//if merong nakitang ruby executive
					//if meron bigay sa kanya ung rank bonus
					$ruby_percent = $ruby_percentage + $emerald_percentage + $sapphire_percentage;
					insertEwallet($db,($item_cv * $ruby_percent), $ruby_user_id, $user_id, $order_id, 44, 0, 0, $dates);	

					
					//declare nya ang sapphire at emerald at ruby percentage sa zero kasi nakuha na ni ruby
					$sapphire_percentage = 0;
					$emerald_percentage = 0;
					$ruby_percentage = 0;
				}
				//hahanapin naman nya ang diamond executive
				$sql = "select count(1) total, a.sponsor_user_id
						  from oc_unilevel a
						  join oc_user b on (a.sponsor_user_id = b.user_id) 
						 where a.user_id = ".$user_id."
						   and b.rank_id = 5
						 order by level
						  limit 1";
						  
				$query = $db->query($sql);
				$diamond_user_id = $query->row['sponsor_user_id'];
				$total_count = $query->row['total'];
				
				if($total_count > 0){//if merong diamond executive na nakuha 
					//if meron bigay sa kanya ung rank bonus
					$diamond_percent = $diamond_percentage + $ruby_percentage + $emerald_percentage + $sapphire_percentage;
					insertEwallet($db, ($item_cv * $diamond_percent), $diamond_user_id, $user_id, $order_id, 44, 0, 0, $dates);	
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
	
	function insertEwalletWithLevel($db, $income, $user_id, $source_user_id, $order_id, $commission_type_id, $created_by, $tax_flag = 0,$level, $dates) {
		
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
						level = ".$level.",
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