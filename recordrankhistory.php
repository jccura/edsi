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
	
	//get all user 
	$sql ="select user_id,user_group_id,rank_id,username 
	         from oc_user 
	        where user_group_id in (46,56)
	          and rank_id = 1";
	$query = $db->query($sql);	
	$users = $query->rows;
	// var_dump($users['user_id']);
	foreach($users as $us) {
		//count downline per user
		// var_dump($us['user_id']);
		$sql ="select count(1) total_downline
				 from oc_unilevel 
				where sponsor_user_id = ".$us['user_id'];		
		$query = $db->query($sql);
		$total_downline = $query->row['total_downline'];
		// var_dump($sql);
		
		if($total_downline > 3){
			VAR_DUMP($total_downline);
		}		
	}
	// VAR_DUMP($total_downline);
	
	
		// echo 'duaamdito <br>';
		$total_amount_partition = 25000;
		// var_dump($total_amount_partition);
		//kunin lahat ng downline at admin
		$sql = "select a.user_id,b.user_group_id,level from oc_unilevel a
				  left join oc_user b on (a.user_id = b.user_id)
				 where sponsor_user_id = '".$us['user_id']."'
				order by a.user_id";
		$query = $db->query($sql);
		$user_id = $query->rows;
		// var_dump($user_id);
		//foreach sa bawat user id na nakuha
		foreach($user_id as $ui){
			// echo 'duaamdito <br>';
			//check if may record na order
			// var_dump ($ui['user_id']);
			if ($ui['user_group_id'] == 56) {
				$sql ="select count(1) total 
						 from oc_order a
						 left join oc_user b on (a.user_id = b.user_id)
						where a.distributor_id = '".$ui['user_id']."'	
						  and a.paid_date >= '2020-07-01 00:00:00' 
						  and a.paid_date <= '2020-10-31 23:59:59'";
				
				$query = $db->query($sql);
				$total_order = $query->row['total'];
				// var_dump($total_order,$ui['user_id']);
			} else {
				$sql ="select count(1) total 
						 from oc_order a
						 left join oc_user b on (a.user_id = b.user_id)
						where a.user_id = '".$ui['user_id']."'
						  and a.paid_date >= '2020-07-01 00:00:00' 
						  and a.paid_date <= '2020-10-31 23:59:59'";
				
				$query = $db->query($sql);
				$total_order = $query->row['total'];
				
				// var_dump($total_order,$ui['user_id'],$ui['user_group_id']);
			}
		// var_dump($total_order);
			if($total_order > 0) {
				// var_dump($total_order,$ui['user_id']);
				// echo 'test';
				if ($ui['user_group_id'] == 56) {
					$sql = "select a.user_id, sum(c.cost * c.quantity) sales,
									 concat(b.firstname, ' ', b.lastname) fullname, b.username 
							  from oc_order a
							  left join oc_user b on (a.user_id = b.user_id)
							  left join oc_order_details c on (a.order_id = c.order_id)
							 where a.paid_date >= '2020-07-01 00:00:00' 
							   and a.paid_date <= '2020-10-31 23:59:59'
							   and  a.distributor_id = '".$ui['user_id']."'
							   and b.user_group_id != 46";
					
					
				} else {
					$sql = "select a.user_id, sum(c.cost * c.quantity) sales,
									 concat(b.firstname, ' ', b.lastname) fullname, b.username 
							  from oc_order a
							  left join oc_user b on (a.user_id = b.user_id)
							  left join oc_order_details c on (a.order_id = c.order_id)
							 where a.user_id = '".$ui['user_id']."'
							   and a.paid_date >= '2020-07-01 00:00:00' 
							   and a.paid_date <= '2020-10-31 23:59:59'";
					// echo 'dumaan dito <br>';
				}
					$query = $db->query($sql);
					$sales = $query->row['sales'];
					// var_dump ($sql);
				if($sales >= $total_amount_partition) {
					$counter += 1;
					// var_dump($counter,$query->row['username'],$us['user_id'],$sales);
				} else {
					
					$not_counted += $sales;
					// var_dump($not_counted,$query->row['username'],$us['user_id'],$sales);
					
				}
					// var_dump($total_amount_partition,  $not_counted);
					$total_amount = ($total_amount_partition * $counter) +  $not_counted;
				
				var_dump($total_amount,$us['user_id'],$ui['user_id']);
				if($total_amount >= 75000) {
					// var_dump($us['user_id'],$ui['user_id']);
					$sql = "select a.user_id, coalesce(sum(c.cost * c.quantity),0) sales,
							concat(b.firstname, ' ', b.lastname) fullname, b.username 
							from oc_order a
							left join oc_user b on (a.user_id = b.user_id)
							left join oc_order_details c on (a.order_id = c.order_id)
							where a.paid_date >= '2020-07-01 00:00:00' 
							and a.paid_date <= '2020-10-31 23:59:59'";
						
						
						if($us['user_group_id'] == 56){
								$sql .= " and a.distributor_id = '".$us['user_id']."' and b.user_group_id != 46";
						} else if ($us['user_group_id'] == 46){
							$sql .= " AND a.user_id = '".$us['user_id']."'";
						} 	
		
					$query = $db->query($sql);
					$personal_sales = $query->row['sales'];
					
					// var_dump($personal_sales,$us['user_id']);
					if($personal_sales >= 75000) {
						// var_dump($,$personal_sales,$us['user_id'],$us['username']);
						$count_user += 1;
						// var_dump($count_user,$us['user_id'],$personal_sales);
					}
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