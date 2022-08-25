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
			 where a.status_id > 0 
			   and a.status_id not in(19,113) 
			   and a.encoded_date >= '2020-06-01 00:00:00' 
			   and a.encoded_date <= '2020-06-30 23:59:59'";
	$query = $db->query($sql);	
	$sales_encoded_3rd_month = $query->rows;
				
	foreach($sales_encoded_3rd_month as $sm3) {
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
		$encoded_orders = $query->rows;
		
		//check if may record na sa sales encoded ang user na umorder
		foreach($encoded_orders as $eo) {
			//if retail at packages
			if($eo['item_id'] == 16 or $eo['item_id'] == 20) {
				//if distributor at send to my address
				if($eo['user_group_id'] == 56 and $eo['send_to'] == 110) {
					$sql = "select count(1) total
						  from oc_sales_encoded	
						 where user_id =". $eo['user_id']."
						 and item_id = ".$eo['item_id'];
					$query = $db->query($sql);
					$total_delivered = $query->row['total'];
					
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_delivered == 0)	{
						$sql ="insert into oc_sales_encoded
								  set user_id = ". $eo['user_id']."
									  ,item_id = ". $eo['item_id']."
									  ,sales_third_month = ". $eo['amount']."
									  ,discount_per_item = ". $eo['discount']."
									  ,date_added ='2020-06-30 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set sales_third_month = sales_third_month + ". $eo['amount']." 
									 ,discount_per_item = discount_per_item + ". $eo['discount']."
								where user_id =". $eo['user_id']."
								  and item_id = ". $eo['item_id'];
						$db->query($sql);
					}
					//kapag reseller at send to my address
				} else if($eo['user_group_id'] == 46 and $eo['send_to'] == 110){
					if($eo['item_id'] == 16 or $eo['item_id'] == 20) {
						$sql = "select count(1) total
							  from oc_sales_encoded	
							 where user_id =". $eo['user_id']."
							 and item_id = ".$eo['item_id'];
						$query = $db->query($sql);
						$total_delivered = $query->row['total'];
						
						//kapag walang  sales encoded na nakarecord sa user na umorder
						if($total_delivered == 0)	{
							$sql ="insert into oc_sales_encoded
									  set user_id = ". $eo['user_id']."
										  ,item_id = ". $eo['item_id']."
										  ,sales_third_month = ". $eo['amount']."
										  ,discount_per_item = ". $eo['discount']."
										  ,date_added ='2020-06-30 12:00:00' ";
							$db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_encoded
									  set sales_third_month = sales_third_month + ". $eo['amount']." 
										 ,discount_per_item = discount_per_item + ". $eo['discount']."
									where user_id =". $eo['user_id']."
									  and item_id = ". $eo['item_id'];
							$db->query($sql);
						}
					}
					//kapag send to my customer o kaya customeer ang umorder / sales admin					
				} else  {
					$sql = "select count(1) total
						  from oc_sales_encoded	
						 where user_id =". $eo['user_id']."
						 and item_id = ".$eo['item_id'];
					$query = $db->query($sql);
					$total_delivered = $query->row['total'];
					
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_delivered == 0)	{
						$sql ="insert into oc_sales_encoded
								  set user_id = ". $eo['user_id']."
									  ,item_id = ". $eo['item_id']."
									  ,sales_third_month = ". $eo['amount']." 
									  ,date_added ='2020-06-30 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set sales_third_month = sales_third_month + ". $eo['amount']." 
								where user_id =". $eo['user_id']."
								  and item_id = ". $eo['item_id'];
						$db->query($sql);
					}
				}
				//kapag package
			} else  {
				$sql = "select count(1) total
					  from oc_sales_encoded	
					 where user_id =". $eo['user_id']."
					 and item_id = ".$eo['item_id'];
				$query = $db->query($sql);
				$total_delivered = $query->row['total'];
				
				//kapag walang  sales encoded na nakarecord sa user na umorder
				if($total_delivered == 0)	{
					$sql ="insert into oc_sales_encoded
							  set user_id = ". $eo['user_id']."
								  ,item_id = ". $eo['item_id']."
								  ,sales_third_month = ". $eo['amount']." 
								  ,date_added ='2020-06-30 12:00:00' ";
					$db->query($sql);
				} else {//if meron nangnakarecord sa sales encoded na user
					$sql ="update oc_sales_encoded
							  set sales_third_month = sales_third_month + ". $eo['amount']." 
							where user_id =". $eo['user_id']."
							  and item_id = ". $eo['item_id'];
					$db->query($sql);
				}
			}
		} 
	}
	
	//sales 2nd day encoded
	$sql = "select a.order_id
			  from oc_order a
			 where a.status_id > 0 
			   and a.status_id not in(19,113) 
			   and a.encoded_date >= '2020-07-01 00:00:00' 
			   and a.encoded_date <= '2020-07-31 23:59:59'
			 group by a.user_id ";
	$query = $db->query($sql);	
	$sales_encoded_2nd_month = $query->rows;
				
	foreach($sales_encoded_2nd_month as $sm2) {
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
		$encoded_orders = $query->rows;
		
		//check if may record na sa sales encoded ang user na umorder
		foreach($encoded_orders as $eo) {
			//if retail at packages
			if($eo['item_id'] == 16 or $eo['item_id'] == 20) {
				//if distributor at send to my address
				if($eo['user_group_id'] == 56 and $eo['send_to'] == 110) {
					$sql = "select count(1) total
						  from oc_sales_encoded	
						 where user_id =". $eo['user_id']."
						 and item_id = ".$eo['item_id'];
					$query = $db->query($sql);
					$total_delivered = $query->row['total'];
					
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_delivered == 0)	{
						$sql ="insert into oc_sales_encoded
								  set user_id = ". $eo['user_id']."
									  ,item_id = ". $eo['item_id']."
									  ,sales_second_month = ". $eo['amount']."
									  ,discount_per_item = ". $eo['discount']."
									  ,date_added ='2020-07-31 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set sales_second_month = sales_second_month + ". $eo['amount']."  
									 ,discount_per_item = discount_per_item + ". $eo['discount']."
								where user_id =". $eo['user_id']."
								  and item_id = ". $eo['item_id'];
						$db->query($sql);
					}
					//kapag reseller at send to my address
				} else if($eo['user_group_id'] == 46 and $eo['send_to'] == 110){
					if($eo['item_id'] == 16 or $eo['item_id'] == 20) {
						$sql = "select count(1) total
							  from oc_sales_encoded	
							 where user_id =". $eo['user_id']."
							 and item_id = ".$eo['item_id'];
						$query = $db->query($sql);
						$total_delivered = $query->row['total'];
						
						//kapag walang  sales encoded na nakarecord sa user na umorder
						if($total_delivered == 0)	{
							$sql ="insert into oc_sales_encoded
									  set user_id = ". $eo['user_id']."
										  ,item_id = ". $eo['item_id']."
										  ,sales_second_month = ". $eo['amount']." 
										  ,discount_per_item = ". $eo['discount']."
										  ,date_added ='2020-07-31 12:00:00' ";
							$db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_encoded
									  set sales_second_month = sales_second_month + ". $eo['amount']." 
										 ,discount_per_item = discount_per_item + ". $eo['discount']."
									where user_id =". $eo['user_id']."
									  and item_id = ". $eo['item_id'];
							$db->query($sql);
						}
					}
					//kapag send to my customer o kaya customeer ang umorder / sales admin					
				} else  {
					$sql = "select count(1) total
						  from oc_sales_encoded	
						 where user_id =". $eo['user_id']."
						 and item_id = ".$eo['item_id'];
					$query = $db->query($sql);
					$total_delivered = $query->row['total'];
					
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_delivered == 0)	{
						$sql ="insert into oc_sales_encoded
								  set user_id = ". $eo['user_id']."
									  ,item_id = ". $eo['item_id']."
									  ,sales_second_month = ". $eo['amount']." 
									  ,date_added ='2020-07-31 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set sales_second_month = sales_second_month + ". $eo['amount']." 
								where user_id =". $eo['user_id']."
								  and item_id = ". $eo['item_id'];
						$db->query($sql);
					}
				}
				//kapag package
			} else  {
				$sql = "select count(1) total
					  from oc_sales_encoded	
					 where user_id =". $eo['user_id']."
					 and item_id = ".$eo['item_id'];
				$query = $db->query($sql);
				$total_delivered = $query->row['total'];
				
				//kapag walang  sales encoded na nakarecord sa user na umorder
				if($total_delivered == 0)	{
					$sql ="insert into oc_sales_encoded
							  set user_id = ". $eo['user_id']."
								  ,item_id = ". $eo['item_id']."
								  ,sales_second_month = ". $eo['amount']." 
								  ,date_added ='2020-07-31 12:00:00' ";
					$db->query($sql);
				} else {//if meron nangnakarecord sa sales encoded na user
					$sql ="update oc_sales_encoded
							  set sales_second_month = sales_second_month + ". $eo['amount']." 
							where user_id =". $eo['user_id']."
							  and item_id = ". $eo['item_id'];
					$db->query($sql);
				}
			}
		} 
	}
	
	//sales lastmonth encoded
	$sql = "select a.order_id
			  from oc_order a
			 where a.status_id > 0 
			   and a.status_id not in(19,113) 
			   and a.encoded_date >= '2020-08-01 00:00:00' 
			   and a.encoded_date <= '2020-08-31 23:59:59'
			 group by a.user_id ";
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
		$encoded_orders = $query->rows;
		
		//check if may record na sa sales encoded ang user na umorder
		foreach($encoded_orders as $eo) {
			//if retail at packages
			if($eo['item_id'] == 16 or $eo['item_id'] == 20) {
				//if distributor at send to my address
				if($eo['user_group_id'] == 56 and $eo['send_to'] == 110) {
					$sql = "select count(1) total
						  from oc_sales_encoded	
						 where user_id =". $eo['user_id']."
						 and item_id = ".$eo['item_id'];
					$query = $db->query($sql);
					$total_delivered = $query->row['total'];
					
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_delivered == 0)	{
						$sql ="insert into oc_sales_encoded
								  set user_id = ". $eo['user_id']."
									  ,item_id = ". $eo['item_id']."
									  ,prev_month = ". $eo['amount']."
									  ,discount_per_item = ". $eo['discount']."
									  ,date_added ='2020-08-31 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set prev_month = prev_month + ". $eo['amount']." 
									 ,discount_per_item = discount_per_item + ". $eo['discount']."
								where user_id =". $eo['user_id']."
								  and item_id = ". $eo['item_id'];
						$db->query($sql);
					}
					//kapag reseller at send to my address
				} else if($eo['user_group_id'] == 46 and $eo['send_to'] == 110){
					if($eo['item_id'] == 16 or $eo['item_id'] == 20) {
						$sql = "select count(1) total
							  from oc_sales_encoded	
							 where user_id =". $eo['user_id']."
							 and item_id = ".$eo['item_id'];
						$query = $db->query($sql);
						$total_delivered = $query->row['total'];
						
						//kapag walang  sales encoded na nakarecord sa user na umorder
						if($total_delivered == 0)	{
							$sql ="insert into oc_sales_encoded
									  set user_id = ". $eo['user_id']."
										  ,item_id = ". $eo['item_id']."
										  ,prev_month = ". $eo['amount']."
										  ,discount_per_item = ". $eo['discount']."
										  ,date_added ='2020-08-31 12:00:00' ";
							$db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_encoded
									  set prev_month = prev_month + ". $eo['amount']."
										 ,discount_per_item = discount_per_item + ". $eo['discount']."
									where user_id =". $eo['user_id']."
									  and item_id = ". $eo['item_id'];
							$db->query($sql);
						}
					}
					//kapag send to my customer o kaya customeer ang umorder / sales admin					
				} else  {
					$sql = "select count(1) total
						  from oc_sales_encoded	
						 where user_id =". $eo['user_id']."
						 and item_id = ".$eo['item_id'];
					$query = $db->query($sql);
					$total_delivered = $query->row['total'];
					
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_delivered == 0)	{
						$sql ="insert into oc_sales_encoded
								  set user_id = ". $eo['user_id']."
									  ,item_id = ". $eo['item_id']."
									  ,prev_month = ". $eo['amount']." 
									  ,date_added ='2020-07-31 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set prev_month = prev_month + ". $eo['amount']." 
								where user_id =". $eo['user_id']."
								  and item_id = ". $eo['item_id'];
						$db->query($sql);
					}
				}
				//kapag package
			} else  {
				$sql = "select count(1) total
					  from oc_sales_encoded	
					 where user_id =". $eo['user_id']."
					 and item_id = ".$eo['item_id'];
				$query = $db->query($sql);
				$total_delivered = $query->row['total'];
				
				//kapag walang  sales encoded na nakarecord sa user na umorder
				if($total_delivered == 0)	{
					$sql ="insert into oc_sales_encoded
							  set user_id = ". $eo['user_id']."
								  ,item_id = ". $eo['item_id']."
								  ,prev_month = ". $eo['amount']." 
								  ,date_added ='2020-08-31 12:00:00' ";
					$db->query($sql);
				} else {//if meron nangnakarecord sa sales encoded na user
					$sql ="update oc_sales_encoded
							  set prev_month = prev_month + ". $eo['amount']." 
							where user_id =". $eo['user_id']."
							  and item_id = ". $eo['item_id'];
					$db->query($sql);
				}
			}
		} 
	}

	//sales lastmonth encoded
	$sql = "select a.order_id
			  from oc_order a
			 where a.status_id > 0 
			   and a.status_id not in(19,113) 
			   and a.encoded_date >= '2020-09-01 00:00:00' 
			   and a.encoded_date <= '2020-09-29 23:59:59'
			 group by a.user_id ";
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
		$encoded_orders = $query->rows;
		
		//check if may record na sa sales encoded ang user na umorder
		foreach($encoded_orders as $eo) {
			//if retail at packages
			if($eo['item_id'] == 16 or $eo['item_id'] == 20) {
				//if distributor at send to my address
				if($eo['user_group_id'] == 56 and $eo['send_to'] == 110) {
					$sql = "select count(1) total
						  from oc_sales_encoded	
						 where user_id =". $eo['user_id']."
						 and item_id = ".$eo['item_id'];
					$query = $db->query($sql);
					$total_delivered = $query->row['total'];
					
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_delivered == 0)	{
						$sql ="insert into oc_sales_encoded
								  set user_id = ". $eo['user_id']."
									  ,item_id = ". $eo['item_id']."
									  ,sales_month = ". $eo['amount']."
									  ,discount_per_item = ". $eo['discount']."
									  ,date_added ='2020-09-29 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set sales_month = sales_month + ". $eo['amount']."
									 ,discount_per_item = discount_per_item + ". $eo['discount']."
								where user_id =". $eo['user_id']."
								  and item_id = ". $eo['item_id'];
						$db->query($sql);
					}
					//kapag reseller at send to my address
				} else if($eo['user_group_id'] == 46 and $eo['send_to'] == 110){
					if($eo['item_id'] == 16 or $eo['item_id'] == 20) {
						$sql = "select count(1) total
							  from oc_sales_encoded	
							 where user_id =". $eo['user_id']."
							 and item_id = ".$eo['item_id'];
						$query = $db->query($sql);
						$total_delivered = $query->row['total'];
						
						//kapag walang  sales encoded na nakarecord sa user na umorder
						if($total_delivered == 0)	{
							$sql ="insert into oc_sales_encoded
									  set user_id = ". $eo['user_id']."
										  ,item_id = ". $eo['item_id']."
										  ,sales_month = ". $eo['amount']." 
										  ,discount_per_item = ". $eo['discount']."
										  ,date_added ='2020-09-29 12:00:00' ";
							$db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_encoded
									  set sales_month = sales_month + ". $eo['amount']." 
										 ,discount_per_item = discount_per_item + ". $eo['discount']."
									where user_id =". $eo['user_id']."
									  and item_id = ". $eo['item_id'];
							$db->query($sql);
						}
					}
					//kapag send to my customer o kaya customeer ang umorder / sales admin					
				} else  {
					$sql = "select count(1) total
						  from oc_sales_encoded	
						 where user_id =". $eo['user_id']."
						 and item_id = ".$eo['item_id'];
					$query = $db->query($sql);
					$total_delivered = $query->row['total'];
					
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_delivered == 0)	{
						$sql ="insert into oc_sales_encoded
								  set user_id = ". $eo['user_id']."
									  ,item_id = ". $eo['item_id']."
									  ,sales_month = ". $eo['amount']." 
									  ,date_added ='2020-09-29 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set sales_month = sales_month + ". $eo['amount']." 
								where user_id =". $eo['user_id']."
								  and item_id = ". $eo['item_id'];
						$db->query($sql);
					}
				}
				//kapag package
			} else  {
				$sql = "select count(1) total
					  from oc_sales_encoded	
					 where user_id =". $eo['user_id']."
					 and item_id = ".$eo['item_id'];
				$query = $db->query($sql);
				$total_delivered = $query->row['total'];
				
				//kapag walang  sales encoded na nakarecord sa user na umorder
				if($total_delivered == 0)	{
					$sql ="insert into oc_sales_encoded
							  set user_id = ". $eo['user_id']."
								  ,item_id = ". $eo['item_id']."
								  ,sales_month = ". $eo['amount']." 
								  ,date_added ='2020-09-29 12:00:00' ";
					$db->query($sql);
				} else {//if meron nangnakarecord sa sales encoded na user
					$sql ="update oc_sales_encoded
							  set sales_month = sales_month + ". $eo['amount']." 
							where user_id =". $eo['user_id']."
							  and item_id = ". $eo['item_id'];
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