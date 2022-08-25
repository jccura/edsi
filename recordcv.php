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
	$uni_comp_id = 0;
	
	$sql  = "update oc_user set month3_cv = month2_cv where month2_cv > 0";
	logger('START', $sql, $log);
	$query = $db->query($sql);
	
	$sql  = "update oc_user set month2_cv = month1_cv where month1_cv > 0";
	logger('START', $sql, $log);
	$query = $db->query($sql);
	
	$sql  = "update oc_user set month1_cv = current_cv where current_cv > 0";
	logger('START', $sql, $log);
	$query = $db->query($sql);
	
	$sql  = "update oc_user set unilevel_qualified = 0 where unilevel_qualified = 1 ";
	logger('START', $sql, $log);
	$query = $db->query($sql);
	
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