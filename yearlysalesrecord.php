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
	
	// determine half
	$date = date("Y-m-d");
	
	//sales month encoded
	$sql = "select user_id,item_id,sales_year
				 ,prev_year,sales_second_year,sales_third_year
			 from oc_sales_encoded";
	$query = $db->query($sql);	
	$sales_encoded = $query->rows;
				
	foreach($sales_encoded as $se) {
		//update the 3rd day of sales encoded
		$sql = "update oc_sales_encoded set sales_third_year = ".$se['sales_second_year']."
				 where user_id = ".$se['user_id']." and item_id = ".$se['item_id'];
		$db->query($sql);	
		
		//update the 2nd day of sales encoded
		$sql = "update oc_sales_encoded set sales_second_year = ".$se['prev_year']."
				 where user_id = ".$se['user_id']." and item_id = ".$se['item_id'];
		$db->query($sql);	
		
		//update the yesterday  sales encoded
		$sql = "update oc_sales_encoded set prev_year = ".$se['sales_year']."
				 where user_id = ".$se['user_id']." and item_id = ".$se['item_id'];
		$db->query($sql);
		
		//update the   sales encoded
		$sql = "update oc_sales_encoded set sales_year = 0
				 where user_id = ".$se['user_id']." and item_id = ".$se['item_id'];
		$db->query($sql);
	}

	//sales year delivered
	$sql = "select user_id,item_id,sales_year
				 ,prev_year,sales_second_year,sales_third_year
			 from oc_sales_delivered ";
	$query = $db->query($sql);	
	$sales_delivered = $query->rows;
				
	foreach($sales_delivered as $sd) {
		//update the 3rd day of sales delivered
		$sql = "update oc_sales_delivered set sales_third_year = ".$sd['sales_second_year']."
				 where user_id = ".$sd['user_id']." and item_id = ".$sd['item_id'];
		$db->query($sql);	
		
		//update the 2nd day of sales delivered
		$sql = "update oc_sales_delivered set sales_second_year = ".$sd['prev_year']."
				 where user_id = ".$sd['user_id']." and item_id = ".$sd['item_id'];
		$db->query($sql);	
		
		//update the yesterday  sales delivered
		$sql = "update oc_sales_delivered set prev_year = ".$sd['sales_year']."
				 where user_id = ".$sd['user_id']." and item_id = ".$sd['item_id'];
		$db->query($sql);
		
		//update the   sales delivered
		$sql = "update oc_sales_delivered set sales_year = 0
				 where user_id = ".$sd['user_id']." and item_id = ".$sd['item_id'];
		$db->query($sql);
	
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