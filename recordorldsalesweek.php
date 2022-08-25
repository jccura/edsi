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

	//sales second week encoded
	$sql = "select a.order_id
			  from oc_order a
			 where a.status_id > 0 
			   and a.status_id not in(19,113) 
			   and a.encoded_date >= '2020-09-07 00:00:00' 
			   and a.encoded_date <= '2020-09-13 23:59:59'";
	$query = $db->query($sql);	
	$sales_third_week = $query->rows;
				
	foreach($sales_third_week as $stw) {
		//kunin ang details ng order
		$sql = "select a.order_id,b.item_id,d.user_group_id,c.price * b.quantity amount,b.quantity,a.user_id
					  ,d.user_group_id,a.send_to,a.delivery_fee,a.discount
					  ,ROUND(c.distributor_discount_per / 100 * c.price * b.quantity, 2)discount_dist
					  ,ROUND(c.reseller_discount_per / 100 * c.price * b.quantity, 2)discount_res
				  from oc_order a
			      join oc_order_details b on(a.order_id = b.order_id)
			      join gui_items_tbl c on(b.item_id = c.item_id)
			      join oc_user d on(a.user_id = d.user_id)
				  where a.order_id =". $stw['order_id'];
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
									  ,sales_third_week = ". $eo['amount']."
									  ,discount_per_item = ". $eo['discount']."
									  ,date_added ='2020-09-13 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set sales_third_week = sales_third_week + ". $eo['amount']."
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
										  ,sales_third_week = ". $eo['amount']." 
										  ,discount_per_item = ". $eo['discount']."
										  ,date_added ='2020-09-13 12:00:00' ";
							$db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_encoded
									  set sales_third_week = sales_third_week + ". $eo['amount']." 
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
									  ,sales_third_week = ". $eo['amount']." 
									  ,date_added ='2020-09-13 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set sales_third_week = sales_third_week + ". $eo['amount']." 
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
								  ,sales_third_week = ". $eo['amount']." 
								  ,date_added ='2020-09-13 12:00:00' ";
					$db->query($sql);
				} else {//if meron nangnakarecord sa sales encoded na user
					$sql ="update oc_sales_encoded
							  set sales_third_week = sales_third_week + ". $eo['amount']." 
							where user_id =". $eo['user_id']."
							  and item_id = ". $eo['item_id'];
					$db->query($sql);
				}
			}
		} 
	}
	
	//sales second week encoded
	$sql = "select a.order_id
			  from oc_order a
			 where a.status_id > 0 
			   and a.status_id not in(19,113) 
			   and a.encoded_date >= '2020-09-14 00:00:00' 
			   and a.encoded_date <= '2020-09-20 23:59:59'";
	$query = $db->query($sql);	
	$sales_second_week = $query->rows;
				
	foreach($sales_second_week as $ssw) {
		//kunin ang details ng order
		$sql = "select a.order_id,b.item_id,d.user_group_id,c.price * b.quantity amount,b.quantity,a.user_id
					  ,d.user_group_id,a.send_to,a.delivery_fee,a.discount
					  ,ROUND(c.distributor_discount_per / 100 * c.price * b.quantity, 2)discount_dist
					  ,ROUND(c.reseller_discount_per / 100 * c.price * b.quantity, 2)discount_res
				  from oc_order a
			      join oc_order_details b on(a.order_id = b.order_id)
			      join gui_items_tbl c on(b.item_id = c.item_id)
			      join oc_user d on(a.user_id = d.user_id)
				  where a.order_id =". $ssw['order_id'];
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
									  ,sales_second_week = ". $eo['amount']."
									  ,discount_per_item = ". $eo['discount']."
									  ,date_added ='2020-09-20 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set sales_second_week = sales_second_week + ". $eo['amount']."
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
										  ,sales_second_week = ". $eo['amount']." 
										  ,discount_per_item = ". $eo['discount']."
										  ,date_added ='2020-09-20 12:00:00' ";
							$db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_encoded
									  set sales_second_week = sales_second_week + ". $eo['amount']." 
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
									  ,sales_second_week = ". $eo['amount']." 
									  ,date_added ='2020-09-20 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set sales_second_week = sales_second_week + ". $eo['amount']." 
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
								  ,sales_second_week = ". $eo['amount']." 
								  ,date_added ='2020-09-20 12:00:00' ";
					$db->query($sql);
				} else {//if meron nangnakarecord sa sales encoded na user
					$sql ="update oc_sales_encoded
							  set sales_second_week = sales_second_week + ". $eo['amount']." 
							where user_id =". $eo['user_id']."
							  and item_id = ". $eo['item_id'];
					$db->query($sql);
				}
			}
		} 
	}
	
	//sales prev week encoded
	$sql = "select a.order_id
			  from oc_order a
			 where a.status_id > 0 
			   and a.status_id not in(19,113) 
			   and a.encoded_date >= '2020-09-21 00:00:00' 
			   and a.encoded_date <= '2020-09-27 23:59:59' ";
	$query = $db->query($sql);	
	$sales_encoded_prev_week = $query->rows;
				
	foreach($sales_encoded_prev_week as $sepw) {
		//kunin ang details ng order
		$sql = "select a.order_id,b.item_id,d.user_group_id,c.price * b.quantity amount,b.quantity,a.user_id
					  ,d.user_group_id,a.send_to,a.delivery_fee,a.discount
					  ,ROUND(c.distributor_discount_per / 100 * c.price * b.quantity, 2)discount_dist
					  ,ROUND(c.reseller_discount_per / 100 * c.price * b.quantity, 2)discount_res
				  from oc_order a
			      join oc_order_details b on(a.order_id = b.order_id)
			      join gui_items_tbl c on(b.item_id = c.item_id)
			      join oc_user d on(a.user_id = d.user_id)
				  where a.order_id =". $sepw['order_id'];
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
									  ,prev_week = ". $eo['amount']."
									  ,discount_per_item = ". $eo['discount']."
									  ,date_added ='2020-09-27 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set prev_week = prev_week + ". $eo['amount']."
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
										  ,prev_week = ". $eo['amount']." 
										  ,discount_per_item = ". $eo['discount']."
										  ,date_added ='2020-09-27 12:00:00' ";
							$db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_encoded
									  set prev_week = prev_week + ". $eo['amount']." 
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
									  ,prev_week = ". $eo['amount']." 
									  ,date_added ='2020-09-27 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set prev_week = prev_week + ". $eo['amount']." 
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
								  ,prev_week = ". $eo['amount']." 
								  ,date_added ='2020-09-27 12:00:00' ";
					$db->query($sql);
				} else {//if meron nangnakarecord sa sales encoded na user
					$sql ="update oc_sales_encoded
							  set prev_week = prev_week + ". $eo['amount']." 
							where user_id =". $eo['user_id']."
							  and item_id = ". $eo['item_id'];
					$db->query($sql);
				}
			}
		} 
	}
	
	//sales yesterday encoded
	$sql = "select a.order_id
			  from oc_order a
			 where a.status_id > 0 
			   and a.status_id not in(19,113) 
			   and a.encoded_date >= '2020-09-28 00:00:00' 
			   and a.encoded_date <= '2020-09-29 23:59:59' ";
	$query = $db->query($sql);	
	$sales_week = $query->rows;
				
	foreach($sales_week as $sw) {
		//kunin ang details ng order
		$sql = "select a.order_id,b.item_id,d.user_group_id,c.price * b.quantity amount,b.quantity,a.user_id
					  ,d.user_group_id,a.send_to,a.delivery_fee,a.discount
					  ,ROUND(c.distributor_discount_per / 100 * c.price * b.quantity, 2)discount_dist
					  ,ROUND(c.reseller_discount_per / 100 * c.price * b.quantity, 2)discount_res
				  from oc_order a
			      join oc_order_details b on(a.order_id = b.order_id)
			      join gui_items_tbl c on(b.item_id = c.item_id)
			      join oc_user d on(a.user_id = d.user_id)
				  where a.order_id =". $sepw['order_id'];
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
									  ,sales_week = ". $eo['amount']."
									  ,discount_per_item = ". $eo['discount']."
									  ,date_added ='2020-09-29 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set sales_week = sales_week + ". $eo['amount']."
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
										  ,sales_week = ". $eo['amount']." 
										  ,discount_per_item = ". $eo['discount']."
										  ,date_added ='2020-09-29 12:00:00' ";
							$db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_encoded
									  set sales_week = sales_week + ". $eo['amount']." 
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
									  ,sales_week = ". $eo['amount']." 
									  ,date_added ='2020-09-29 12:00:00' ";
						$db->query($sql);
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set sales_week = sales_week + ". $eo['amount']." 
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
								  ,sales_week = ". $eo['amount']." 
								  ,date_added ='2020-09-29 12:00:00' ";
					$db->query($sql);
				} else {//if meron nangnakarecord sa sales encoded na user
					$sql ="update oc_sales_encoded
							  set sales_week = sales_week + ". $eo['amount']." 
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