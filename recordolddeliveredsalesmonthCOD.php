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
	
	$dates_to_record = array("2020-09-15", "2020-09-16", "2020-09-17");
	
	//sales 3rd day encoded
	$sql = "select a.order_id
			  from oc_order a
			 where a.status_id in (35) 
			   and a.payment_option in (148,146,157,158) 
			   and a.actual_delivery_date >= '2020-06-01 00:00:00' 
			   and a.actual_delivery_date <= '2020-06-30 23:59:59'";
	$query = $db->query($sql);	
	$sales_delivered_3rd_month = $query->rows;
				
	foreach($sales_delivered_3rd_month as $sm3) {
		//kunin ang details ng order
		$sql = "select a.order_id,b.item_id,d.user_group_id,c.price * b.quantity amount,b.quantity,a.user_id
					  ,d.user_group_id,a.send_to,a.delivery_fee,a.discount
					  ,ROUND(c.distributor_discount_per / 100 * c.price * b.quantity, 2)discount_dist
					  ,ROUND(c.reseller_discount_per / 100 * c.price * b.quantity, 2)discount_res
				  from oc_order a
			      join oc_order_details b on(a.order_id = b.order_id)
			      join gui_items_tbl c on(b.item_id = c.item_id)
			      join oc_user d on(a.user_id = d.user_id)
				  where a.order_id =". $sm3['order_id'];
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
									  ,sales_third_month = ". $do['amount']."
									  ,discount_per_item = ". $do['discount']."
									  ,date_added ='2020-06-30 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_delivered
								  set sales_third_month = sales_third_month + ". $do['amount']."
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
										  ,sales_third_month = ". $do['amount']."
										  ,discount_per_item = ". $do['discount']."
										  ,date_added ='2020-06-30 12:00:00' ";
							$db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_delivered
									  set sales_third_month = sales_third_month + ". $do['amount']."
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
									  ,sales_third_month = ". $do['amount']." 
									  ,date_added ='2020-06-30 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_delivered
								  set sales_third_month = sales_third_month + ". $do['amount']." 
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
								  ,sales_third_month = ". $do['amount']." 
								  ,date_added ='2020-06-30 12:00:00' ";
					$db->query($sql);
				} else {//if meron nangnakarecord sa sales encoded na user
					$sql ="update oc_sales_delivered
							  set sales_third_month = sales_third_month + ". $do['amount']." 
							where user_id =". $do['user_id']."
							  and item_id = ". $do['item_id'];
					$db->query($sql);
				}
			}
		} 
	}
	
	//sales 2nd day encoded
	$sql = "select a.order_id
			  from oc_order a
			 where a.status_id in (35) 
			   and a.payment_option in (148,146,157,158) 
			   and a.actual_delivery_date >= '2020-07-01 00:00:00' 
			   and a.actual_delivery_date <= '2020-07-31 23:59:59'";
	$query = $db->query($sql);	
	$sales_delivered_2nd_month = $query->rows;
				
	foreach($sales_delivered_2nd_month as $sm2) {
		//kunin ang details ng order
		$sql = "select a.order_id,b.item_id,d.user_group_id,c.price * b.quantity amount,b.quantity,a.user_id
					  ,d.user_group_id,a.send_to,a.delivery_fee,a.discount
					  ,ROUND(c.distributor_discount_per / 100 * c.price * b.quantity, 2)discount_dist
					  ,ROUND(c.reseller_discount_per / 100 * c.price * b.quantity, 2)discount_res
				  from oc_order a
			      join oc_order_details b on(a.order_id = b.order_id)
			      join gui_items_tbl c on(b.item_id = c.item_id)
			      join oc_user d on(a.user_id = d.user_id)
				  where a.order_id =". $sm2['order_id'];
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
									  ,sales_second_month = ". $do['amount']."
									  ,discount_per_item = ". $do['discount']."
									  ,date_added ='2020-07-31 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_delivered
								  set sales_second_month = sales_second_month + ". $do['amount']."
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
										  ,sales_second_month = ". $do['amount']."
										  ,discount_per_item = ". $do['discount']."
										  ,date_added ='2020-07-31 12:00:00' ";
							$db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_delivered
									  set sales_second_month = sales_second_month + ". $do['amount']."
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
									  ,sales_second_month = ". $do['amount']." 
									  ,date_added ='2020-07-31 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_delivered
								  set sales_second_month = sales_second_month + ". $do['amount']." 
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
								  ,sales_second_month = ". $do['amount']." 
								  ,date_added ='2020-07-31 12:00:00' ";
					$db->query($sql);
				} else {//if meron nangnakarecord sa sales encoded na user
					$sql ="update oc_sales_delivered
							  set sales_second_month = sales_second_month + ". $do['amount']." 
							where user_id =". $do['user_id']."
							  and item_id = ". $do['item_id'];
					$db->query($sql);
				}
			}
		} 
	}
	
	//sales lastmonth encoded
	$sql = "select a.order_id
			  from oc_order a
			 where a.status_id in (35) 
			   and a.payment_option in (148,146,157,158) 
			   and a.actual_delivery_date >= '2020-08-01 00:00:00' 
			   and a.actual_delivery_date <= '2020-08-31 23:59:59' ";
	$query = $db->query($sql);	
	$sales_prev_month = $query->rows;
				
	foreach($sales_prev_month as $sp2) {
		//kunin ang details ng order
		$sql = "select a.order_id,b.item_id,d.user_group_id,c.price * b.quantity amount,b.quantity,a.user_id
					  ,d.user_group_id,a.send_to,a.delivery_fee,a.discount
					  ,ROUND(c.distributor_discount_per / 100 * c.price * b.quantity, 2)discount_dist
					  ,ROUND(c.reseller_discount_per / 100 * c.price * b.quantity, 2)discount_res
				  from oc_order a
			      join oc_order_details b on(a.order_id = b.order_id)
			      join gui_items_tbl c on(b.item_id = c.item_id)
			      join oc_user d on(a.user_id = d.user_id)
				  where a.order_id =". $sp2['order_id'];
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
									  ,prev_month = ". $do['amount']."
									  ,discount_per_item = ". $do['discount']."
									  ,date_added ='2020-08-31 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_delivered
								  set prev_month = prev_month + ". $do['amount']."
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
										  ,prev_month = ". $do['amount']." 
										  ,discount_per_item = ". $do['discount']."
										  ,date_added ='2020-08-31 12:00:00' ";
							$db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_delivered
									  set prev_month = prev_month + ". $do['amount']."
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
									  ,prev_month = ". $do['amount']." 
									  ,date_added ='2020-08-31 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_delivered
								  set prev_month = prev_month + ". $do['amount']." 
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
								  ,prev_month = ". $do['amount']." 
								  ,date_added ='2020-08-31 12:00:00' ";
					$db->query($sql);
				} else {//if meron nangnakarecord sa sales encoded na user
					$sql ="update oc_sales_delivered
							  set prev_month = prev_month + ". $do['amount']." 
							where user_id =". $do['user_id']."
							  and item_id = ". $do['item_id'];
					$db->query($sql);
				}
			}
		} 
	}

	//sales lastmonth encoded
	$sql = "select a.order_id
			  from oc_order a
			 where a.status_id in (35) 
			   and a.payment_option in (148,146,157,158) 
			   and a.actual_delivery_date >= '2020-09-01 00:00:00' 
			   and a.actual_delivery_date <= '2020-09-29 23:59:59'";
	$query = $db->query($sql);	
	$sales_month = $query->rows;
				
	foreach($sales_month as $sm) {
		//kunin ang details ng order
		$sql = "select a.order_id,b.item_id,d.user_group_id,c.price * b.quantity amount,b.quantity,a.user_id
					  ,d.user_group_id,a.send_to,a.delivery_fee,a.discount
					  ,ROUND(c.distributor_discount_per / 100 * c.price * b.quantity, 2)discount_dist
					  ,ROUND(c.reseller_discount_per / 100 * c.price * b.quantity, 2)discount_res
				  from oc_order a
			      join oc_order_details b on(a.order_id = b.order_id)
			      join gui_items_tbl c on(b.item_id = c.item_id)
			      join oc_user d on(a.user_id = d.user_id)
				  where a.order_id =". $sm['order_id'];
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
									  ,sales_month = ". $do['amount']."
									  ,discount_per_item = ". $do['discount']."
									  ,date_added ='2020-09-29 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_delivered
								  set sales_month = sales_month + ". $do['amount']." 
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
										  ,sales_month = ". $do['amount']."
										  ,discount_per_item = ". $do['discount']."
										  ,date_added ='2020-09-29 12:00:00' ";
							$db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_delivered
									  set sales_month = sales_month + ". $do['amount']."
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
									  ,sales_month = ". $do['amount']." 
									  ,date_added ='2020-09-29 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_delivered
								  set sales_month = sales_month + ". $do['amount']." 
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
								  ,sales_month = ". $do['amount']." 
								  ,date_added ='2020-09-29 12:00:00' ";
					$db->query($sql);
				} else {//if meron nangnakarecord sa sales encoded na user
					$sql ="update oc_sales_delivered
							  set sales_month = sales_month + ". $do['amount']." 
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