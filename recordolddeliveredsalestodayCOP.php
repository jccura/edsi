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
	
	$dates_to_record = array("2020-09-22", "2020-09-23", "2020-09-24");
	
	//sales 3rd day delivered
	$sql = "select a.order_id
			  from oc_order a
			 where a.status_id in (152) 
			   and a.pickedup_date >= '2020-09-27 00:00:00' 
			   and a.pickedup_date <= '2020-09-27 23:59:59'";
	$query = $db->query($sql);	
	$sales_delivered_3rd_day = $query->rows;
				
	foreach($sales_delivered_3rd_day as $sd3) {
		//kunin ang details ng order
		$sql = "select a.order_id,b.item_id,d.user_group_id,c.price * b.quantity amount,b.quantity,a.user_id
					  ,d.user_group_id,a.send_to,a.delivery_fee,a.discount
					  ,ROUND(c.distributor_discount_per / 100 * c.price * b.quantity, 2)discount_dist
					  ,ROUND(c.reseller_discount_per / 100 * c.price * b.quantity, 2)discount_res
				  from oc_order a
			      join oc_order_details b on(a.order_id = b.order_id)
			      join gui_items_tbl c on(b.item_id = c.item_id)
			      join oc_user d on(a.user_id = d.user_id)
				  where a.order_id =". $sd3['order_id'];
		$query = $db->query($sql);
		$delivered_orders = $query->rows;
		
		//check if may record na sa sales encoded ang user na umorder
		foreach($delivered_orders as $do) {
			//if retail at packages
			if($do['item_id'] == 16 or $do['item_id'] == 20) {
				//if distributor at send to my address
				if($do['user_group_id'] == 56 and $do['send_to'] == 110) {
					$sql = "select count(1) total
						  from oc_sales_delivered	
						 where user_id =". $do['user_id']."
						 and item_id = ".$do['item_id'];
					$query = $db->query($sql);
					$total_delivered = $query->row['total'];
					
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_delivered == 0)	{
						$sql ="insert into oc_sales_delivered
								  set user_id = ". $do['user_id']."
									  ,item_id = ". $do['item_id']."
									  ,sales_third_day = ". $do['amount']."
									  ,discount_per_item = ". $do['discount']."
									  ,date_added ='2020-09-27 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_delivered
								  set sales_third_day = sales_third_day + ". $do['amount']."
									 ,discount_per_item = discount_per_item + ". $do['discount']."
								where user_id =". $do['user_id']."
								  and item_id = ". $do['item_id'];
						$db->query($sql);
					}
					//kapag reseller at send to my address
				} else if($do['user_group_id'] == 46 and $do['send_to'] == 110){
					if($do['item_id'] == 16 or $do['item_id'] == 20) {
						$sql = "select count(1) total
							  from oc_sales_delivered	
							 where user_id =". $do['user_id']."
							 and item_id = ".$do['item_id'];
						$query = $db->query($sql);
						$total_delivered = $query->row['total'];
						
						//kapag walang  sales encoded na nakarecord sa user na umorder
						if($total_delivered == 0)	{
							$sql ="insert into oc_sales_delivered
									  set user_id = ". $do['user_id']."
										  ,item_id = ". $do['item_id']."
										  ,sales_third_day = ". $do['amount']." 
										  ,discount_per_item = ". $do['discount']."
										  ,date_added ='2020-09-27 12:00:00' ";
							$db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_delivered
									  set sales_third_day = sales_third_day + ". $do['amount']." 
										 ,discount_per_item = discount_per_item + ". $do['discount']."
									where user_id =". $do['user_id']."
									  and item_id = ". $do['item_id'];
							$db->query($sql);
						}
					}
					//kapag send to my customer o kaya customeer ang umorder / sales admin					
				} else  {
					$sql = "select count(1) total
						  from oc_sales_delivered	
						 where user_id =". $do['user_id']."
						 and item_id = ".$do['item_id'];
					$query = $db->query($sql);
					$total_delivered = $query->row['total'];
					
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_delivered == 0)	{
						$sql ="insert into oc_sales_delivered
								  set user_id = ". $do['user_id']."
									  ,item_id = ". $do['item_id']."
									  ,sales_third_day = ". $do['amount']." 
									  ,date_added ='2020-09-27 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_delivered
								  set sales_third_day = sales_third_day + ". $do['amount']." 
								where user_id =". $do['user_id']."
								  and item_id = ". $do['item_id'];
						$db->query($sql);
					}
				}
				//kapag package
			} else  {
				$sql = "select count(1) total
					  from oc_sales_delivered	
					 where user_id =". $do['user_id']."
					 and item_id = ".$do['item_id'];
				$query = $db->query($sql);
				$total_delivered = $query->row['total'];
				
				//kapag walang  sales encoded na nakarecord sa user na umorder
				if($total_delivered == 0)	{
					$sql ="insert into oc_sales_delivered
							  set user_id = ". $do['user_id']."
								  ,item_id = ". $do['item_id']."
								  ,sales_third_day = ". $do['amount']." 
								  ,date_added ='2020-09-27 12:00:00' ";
					$db->query($sql);
				} else {//if meron nangnakarecord sa sales encoded na user
					$sql ="update oc_sales_delivered
							  set sales_third_day = sales_third_day + ". $do['amount']." 
							where user_id =". $do['user_id']."
							  and item_id = ". $do['item_id'];
					$db->query($sql);
				}
			}
		} 
	}
	
	//sales 2rd day delivered
	$sql = "select a.order_id
			  from oc_order a
			 where a.status_id in (152) 
			   and a.pickedup_date >= '2020-09-28 00:00:00' 
			   and a.pickedup_date <= '2020-09-28 23:59:59'";
	$query = $db->query($sql);	
	$sales_delivered_2nd_day = $query->rows;
				
	foreach($sales_delivered_2nd_day as $sd2) {
		//kunin ang details ng order
		$sql = "select a.order_id,b.item_id,d.user_group_id,c.price * b.quantity amount,b.quantity,a.user_id
					  ,d.user_group_id,a.send_to,a.delivery_fee,a.discount
					  ,ROUND(c.distributor_discount_per / 100 * c.price * b.quantity, 2)discount_dist
					  ,ROUND(c.reseller_discount_per / 100 * c.price * b.quantity, 2)discount_res
				  from oc_order a
			      join oc_order_details b on(a.order_id = b.order_id)
			      join gui_items_tbl c on(b.item_id = c.item_id)
			      join oc_user d on(a.user_id = d.user_id)
				  where a.order_id =". $sd2['order_id'];
		$query = $db->query($sql);
		$delivered_orders = $query->rows;
		
		//check if may record na sa sales encoded ang user na umorder
		foreach($delivered_orders as $do) {
			//if retail at packages
			if($do['item_id'] == 16 or $do['item_id'] == 20) {
				//if distributor at send to my address
				if($do['user_group_id'] == 56 and $do['send_to'] == 110) {
					$sql = "select count(1) total
						  from oc_sales_delivered	
						 where user_id =". $do['user_id']."
						 and item_id = ".$do['item_id'];
					$query = $db->query($sql);
					$total_delivered = $query->row['total'];
					
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_delivered == 0)	{
						$sql ="insert into oc_sales_delivered
								  set user_id = ". $do['user_id']."
									  ,item_id = ". $do['item_id']."
									  ,sales_second_day = ". $do['amount']."
									  ,discount_per_item = ". $do['discount']."
									  ,date_added ='2020-09-28 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_delivered
								  set sales_second_day = sales_second_day + ". $do['amount']."
									 ,discount_per_item = discount_per_item + ". $do['discount']."
								where user_id =". $do['user_id']."
								  and item_id = ". $do['item_id'];
						$db->query($sql);
					}
					//kapag reseller at send to my address
				} else if($do['user_group_id'] == 46 and $do['send_to'] == 110){
					if($do['item_id'] == 16 or $do['item_id'] == 20) {
						$sql = "select count(1) total
							  from oc_sales_delivered	
							 where user_id =". $do['user_id']."
							 and item_id = ".$do['item_id'];
						$query = $db->query($sql);
						$total_delivered = $query->row['total'];
						
						//kapag walang  sales encoded na nakarecord sa user na umorder
						if($total_delivered == 0)	{
							$sql ="insert into oc_sales_delivered
									  set user_id = ". $do['user_id']."
										  ,item_id = ". $do['item_id']."
										  ,sales_second_day = ". $do['amount']."
										  ,discount_per_item = ". $do['discount']."
										  ,date_added ='2020-09-28 12:00:00' ";
							$db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_delivered
									  set sales_second_day = sales_second_day + ". $do['amount']."
										 ,discount_per_item = discount_per_item + ". $do['discount']."
									where user_id =". $do['user_id']."
									  and item_id = ". $do['item_id'];
							$db->query($sql);
						}
					}
					//kapag send to my customer o kaya customeer ang umorder / sales admin					
				} else  {
					$sql = "select count(1) total
						  from oc_sales_delivered	
						 where user_id =". $do['user_id']."
						 and item_id = ".$do['item_id'];
					$query = $db->query($sql);
					$total_delivered = $query->row['total'];
					
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_delivered == 0)	{
						$sql ="insert into oc_sales_delivered
								  set user_id = ". $do['user_id']."
									  ,item_id = ". $do['item_id']."
									  ,sales_second_day = ". $do['amount']." 
									  ,date_added ='2020-09-28 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_delivered
								  set sales_second_day = sales_second_day + ". $do['amount']." 
								where user_id =". $do['user_id']."
								  and item_id = ". $do['item_id'];
						$db->query($sql);
					}
				}
				//kapag package
			} else  {
				$sql = "select count(1) total
					  from oc_sales_delivered	
					 where user_id =". $do['user_id']."
					 and item_id = ".$do['item_id'];
				$query = $db->query($sql);
				$total_delivered = $query->row['total'];
				
				//kapag walang  sales encoded na nakarecord sa user na umorder
				if($total_delivered == 0)	{
					$sql ="insert into oc_sales_delivered
							  set user_id = ". $do['user_id']."
								  ,item_id = ". $do['item_id']."
								  ,sales_second_day = ". $do['amount']." 
								  ,date_added ='2020-09-28 12:00:00' ";
					$db->query($sql);
				} else {//if meron nangnakarecord sa sales encoded na user
					$sql ="update oc_sales_delivered
							  set sales_second_day = sales_second_day + ". $do['amount']." 
							where user_id =". $do['user_id']."
							  and item_id = ". $do['item_id'];
					$db->query($sql);
				}
			}
		} 
	}
	
//sales yesterday delivered
	$sql = "select a.order_id
			  from oc_order a
			 where a.status_id in (152) 
			   and a.pickedup_date >= '2020-09-29 00:00:00' 
			   and a.pickedup_date <= '2020-09-29 23:59:59'";
	$query = $db->query($sql);	
	$sales_delivered_yesterday = $query->rows;
				
	foreach($sales_delivered_yesterday as $sy) {
		//kunin ang details ng order
		$sql = "select a.order_id,b.item_id,d.user_group_id,c.price * b.quantity amount,b.quantity,a.user_id
					  ,d.user_group_id,a.send_to,a.delivery_fee,a.discount
					  ,ROUND(c.distributor_discount_per / 100 * c.price * b.quantity, 2)discount_dist
					  ,ROUND(c.reseller_discount_per / 100 * c.price * b.quantity, 2)discount_res
				  from oc_order a
			      join oc_order_details b on(a.order_id = b.order_id)
			      join gui_items_tbl c on(b.item_id = c.item_id)
			      join oc_user d on(a.user_id = d.user_id)
				  where a.order_id =". $sy['order_id'];
		$query = $db->query($sql);
		$delivered_orders = $query->rows;
		
		//check if may record na sa sales encoded ang user na umorder
		foreach($delivered_orders as $do) {
			//if retail at packages
			if($do['item_id'] == 16 or $do['item_id'] == 20) {
				//if distributor at send to my address
				if($do['user_group_id'] == 56 and $do['send_to'] == 110) {
					$sql = "select count(1) total
						  from oc_sales_delivered	
						 where user_id =". $do['user_id']."
						 and item_id = ".$do['item_id'];
					$query = $db->query($sql);
					$total_delivered = $query->row['total'];
					
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_delivered == 0)	{
						$sql ="insert into oc_sales_delivered
								  set user_id = ". $do['user_id']."
									  ,item_id = ". $do['item_id']."
									  ,sales_yesterday = ". $do['amount']."
									  ,discount_per_item = ". $do['discount']."
									  ,date_added ='2020-09-29 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_delivered
								  set sales_yesterday = sales_yesterday + ". $do['amount']."
									 ,discount_per_item = discount_per_item + ". $do['discount']."
								where user_id =". $do['user_id']."
								  and item_id = ". $do['item_id'];
						$db->query($sql);
					}
					//kapag reseller at send to my address
				} else if($do['user_group_id'] == 46 and $do['send_to'] == 110){
					if($do['item_id'] == 16 or $do['item_id'] == 20) {
						$sql = "select count(1) total
							  from oc_sales_delivered	
							 where user_id =". $do['user_id']."
							 and item_id = ".$do['item_id'];
						$query = $db->query($sql);
						$total_delivered = $query->row['total'];
						
						//kapag walang  sales encoded na nakarecord sa user na umorder
						if($total_delivered == 0)	{
							$sql ="insert into oc_sales_delivered
									  set user_id = ". $do['user_id']."
										  ,item_id = ". $do['item_id']."
										  ,sales_yesterday = ". $do['amount']."
										  ,discount_per_item = ". $do['discount']."
										  ,date_added ='2020-09-29 12:00:00' ";
							$db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_delivered
									  set sales_yesterday = sales_yesterday + ". $do['amount']."
										 ,discount_per_item = discount_per_item + ". $do['discount']."
									where user_id =". $do['user_id']."
									  and item_id = ". $do['item_id'];
							$db->query($sql);
						}
					}
					//kapag send to my customer o kaya customeer ang umorder / sales admin					
				} else  {
					$sql = "select count(1) total
						  from oc_sales_delivered	
						 where user_id =". $do['user_id']."
						 and item_id = ".$do['item_id'];
					$query = $db->query($sql);
					$total_delivered = $query->row['total'];
					
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_delivered == 0)	{
						$sql ="insert into oc_sales_delivered
								  set user_id = ". $do['user_id']."
									  ,item_id = ". $do['item_id']."
									  ,sales_yesterday = ". $do['amount']." 
									  ,date_added ='2020-09-29 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_delivered
								  set sales_yesterday = sales_yesterday + ". $do['amount']." 
								where user_id =". $do['user_id']."
								  and item_id = ". $do['item_id'];
						$db->query($sql);
					}
				}
				//kapag package
			} else  {
				$sql = "select count(1) total
					  from oc_sales_delivered	
					 where user_id =". $do['user_id']."
					 and item_id = ".$do['item_id'];
				$query = $db->query($sql);
				$total_delivered = $query->row['total'];
				
				//kapag walang  sales encoded na nakarecord sa user na umorder
				if($total_delivered == 0)	{
					$sql ="insert into oc_sales_delivered
							  set user_id = ". $do['user_id']."
								  ,item_id = ". $do['item_id']."
								  ,sales_yesterday = ". $do['amount']." 
								  ,date_added ='2020-09-29 12:00:00' ";
					$db->query($sql);
				} else {//if meron nangnakarecord sa sales encoded na user
					$sql ="update oc_sales_delivered
							  set sales_yesterday = sales_yesterday + ". $do['amount']." 
							where user_id =". $do['user_id']."
							  and item_id = ". $do['item_id'];
					$db->query($sql);
				}
			}
		} 
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