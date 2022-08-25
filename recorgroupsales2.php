<?php

	
	ini_set('max_execution_time', 0);
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	date_default_timezone_set('Asia/Manila');
    
	// Version
	define('VERSION', '1.5.1.3');

	// Config
	require_once('configcron.php');

	// Startup
	require_once('db.php');
	
	// log
	require_once('log.php');
	
	require_once('mysql.php');
	
	echo "Start of Processing<br>";	
	
	// Database 
	$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	
	$total_percentage = 0;
	$total_amount = 0;
	$counter = 0;
	$total_amount_partition = 0;
	$group_sales = 0;
	$not_counted = 0;
	$count_user = 0;
	$sales = 0;
	
	//get all user 
	$sql ="select user_id,user_group_id,rank_id,username 
	         from oc_user 
	        where user_group_id in (46,56)
	          and rank_id = 1";
	$query = $db->query($sql);	
	$users = $query->rows;
	
	foreach($users as $us) {
		//count downline per user
		$sql ="select count(1) total_downline
				 from oc_unilevel
				where sponsor_user_id = ".$us['user_id'];		
		$query = $db->query($sql);
		$total_downline = $query->row['total_downline'];
		
		if($us['user_group_id'] == 56) {
			$dist_res_id = " distributor_id = ".$us['user_id'];
		} else {
			$dist_res_id = " user_id = ".$us['user_id'];
		}
		
		$sql ="select coalesce(sum(amount),0) amount
				 from oc_order 
				where ".$dist_res_id."
				  and paid_date >= '2020-09-01 00:00:00' 
				  and paid_date <= '2020-11-30 23:59:59'";
		$query = $db->query($sql);
		$personal_sales = $query->row['amount'];
		
		if($total_downline >= 3){
			// VAR_DUMP($total_downline);
			
			if($personal_sales >= 75000) {
				echo("user id ".$us['user_id']." ======> ".$personal_sales."<br>");
				
				$sql = "select a.user_id, b.user_group_id
							from oc_unilevel a
							left join oc_user b on (a.user_id = b.user_id)
						where a.sponsor_user_id = ".$us['user_id'];
				$query = $db->query($sql);
				$downline_user_id = $query->rows;
				
				foreach($downline_user_id as $dui) {
					if($dui['user_group_id'] == 56) {
						$dist_res_id2 = " distributor_id = ".$dui['user_id'];
					} else {
						$dist_res_id2 = " sales_rep_id = ".$dui['user_id'];
					}
					
					$sql ="select coalesce(sum(amount),0)-coalesce(sum(delivery_fee),0) + coalesce(sum(discount),0) amount
							 from oc_order 
							where ".$dist_res_id2."
							  and paid_date >= '2020-09-01 00:00:00' 
							  and paid_date <= '2020-11-30 23:59:59'";
					$query = $db->query($sql);
					$total_amount_partition = $query->row['amount'];
					
					echo("--- downline ==> ".$dui['user_id']."_sales == ".$total_amount_partition."<br>");
					
					if($total_amount_partition >= 25000) {
						$counter += 1;
					} else {
						$sales += $total_amount_partition;
					}
				}
				if($counter >= 3) {
					$total_amount = ($total_amount_partition * $counter) +  $not_counted;
					echo($us['user_id']." TOTAL COUNTER SALES ===>> ".$total_amount."<br>");
				} else {
					$total_amount = $sales;
					echo($us['user_id']." TOTAL PARTITION SALES ===>> ".$total_amount."<br>");
				}
			}			
		}		
	}
	
	function nowString($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();		
		}
		
		return $now->format('Y-m-d H:i:s');
	}
?>