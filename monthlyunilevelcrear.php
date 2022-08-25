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
	$uni_comp_id = 0;
	
	$sql  = "update oc_user set month3_cv = month2_cv where month2_cv > 0";
	$query = $db->query($sql);
	
	$sql  = "update oc_user set month2_cv = month1_cv where month1_cv > 0";
	$query = $db->query($sql);
	
	$sql  = "update oc_user set month1_cv = current_cv where current_cv > 0";
	$query = $db->query($sql);
	
	function nowString($date = null) {
		if(isset($date)) {
			
		} else {
			date_default_timezone_set('Asia/Manila');
			
			$now = new DateTime();		
		}
		
		return $now->format('Y-m-d H:i:s');
	}
?>