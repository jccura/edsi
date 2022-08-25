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
	$sql ="SELECT  UPPER(concat(b.firstname,' ',b.lastname))fullname,sum(a.amount) sales,a.status_id,a.user_id,c.name user_group
				 FROM oc_order a
				 JOIN oc_user b ON(a.user_id = b.user_id)
				 JOIN oc_order_details d ON(a.order_id = d.order_id)
			     JOIN oc_user_group c ON (b.user_group_id = c.user_group_id)
				WHERE date_format(a.paid_date,'%Y%m')  = 202009
				  AND d.item_id in (17,18)
				GROUP BY a.user_id 
			    ORDER BY sum(a.amount) desc  
				LIMIT 10";
		$query = $db->query($sql);
		$sales_encoded = $query->rows;
		var_dump ($sales_encoded);	
	
	function nowString($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();		
		}
		
		return $now->format('Y-m-d H:i:s');
	}
?>