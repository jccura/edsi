<?php
class ModelApiSendOrder extends Model {
	
	public function sendOrderDetails($data = array()){

		$order_result = array();
		$return_msg = "";
		$valid = 1;
		$quick_delivery_flag = 0;
		$postFields = array();
		
		if(empty($data['order_id'])){
			$valid = 0;
			$return_msg = "Input is required. <br>";
		}
		
		$sql = "select a.order_id, a.status_id, b.branch_type 
					from oc_order a 
				left join gui_branch_tbl b on (a.branch_id = b.branch_id)
				where a.order_id in(".$data['order_id'].") ";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		
		foreach($query->rows as $orders) {
			
			if($valid == 1) { 
				$order_id = $orders['order_id'];
				
				$sql = "select count(1) total
							from oc_order_details a
						left join gui_items_tbl b on (a.item_id = b.item_id)
						where b.category_id = 2
						and a.order_id = ".$order_id;
				$query = $this->db->query($sql);
				$package = $query->row['total'];
				
				$sql = "select a.order_id, b.manong_status as status, a.status_id, a.user_id, a.payment_option payment_option_id,
								case when a.contact <> '' then a.contact else e.contact end customer_phone, 
								a.amount total, c.description as payment_option, d.description as shipped_thru, 
								a.delivery_fee shipping_fee, a.total quantity, a.date_added, a.reference, g.address 'origin_address',
								concat(a.address, ', ',j.description, ', ',k.description, ', ',l.description, ', ', m.name) destination_address,
								a.country_id country, a.province_id, a.city_municipality_id, a.barangay_id, a.email customer_email, '' zip_code, a.address, a.landmark landmarks, now() date_ordered,
								case when a.customer_name <> '' then a.customer_name else concat(e.firstname, ' ', e.lastname) end as customer_fullname,
								concat(f.firstname, ' ', f.lastname, '(', coalesce(f.contact,''), ')') admin_fullname, f.contact as admin_phone,
								concat(h.firstname, ' ', h.lastname, '(', coalesce(h.contact, ''), ')') distributor_fullname, h.contact as distributor_phone,
								concat(i.firstname, ' ', i.lastname, '(', coalesce(i.contact, ''), ')') affiliate_fullname, i.contact as affiliate_phone,
								g.description branch_name, a.remarks latest_comment, '' requested_delivery_date, j.city_distributor_id, j.city_distributor_id area_id_flag,
								concat(n.firstname, ' ', n.middlename, ' ', n.lastname) city_distributor, n.contact city_distributor_no,
								n.address city_distributor_add, n.landmark city_distributor_landmark, n.province_id city_distributor_province,
								n.city_municipality_id city_distributor_city, n.barangay_id city_distributor_brgy
						  from oc_order a 
						 left join gui_status_tbl b on (a.status_id = b.status_id)
						 left join gui_status_tbl c on (a.payment_option = c.status_id) 
						 left join oc_user e on (a.user_id = e.user_id)
						 left join gui_status_tbl d on (a.delivery_option = d.status_id) 
						 left join oc_user f on (a.city_distributor_id = f.user_id)
						 left join oc_user i on (a.operator_id = i.user_id)
						 left join oc_user h on (a.sales_rep_id = h.user_id)
						 left join gui_branch_tbl g on (a.branch_id = g.branch_id)
						 left join oc_barangays j on (a.barangay_id = j.barangay_id)
						 left join oc_city_municipality k on (a.city_municipality_id = k.city_municipality_id)
						 left join oc_provinces l on (a.province_id = l.province_id)
						 left join oc_country m on (a.country_id = m.country_id)
						 left join oc_user n on (n.user_id = j.city_distributor_id)
						 where a.order_id = ".$order_id;
				$this->log->write($sql);			 
				$query = $this->db->query($sql);
				$order_detail = $query->row;
				
				$sql = "select concat(a.item_id,',',a.cost,',',a.quantity,',',b.item_name) items
						from oc_order_details a
						left join gui_items_tbl b on (a.item_id = b.item_id)
						where a.order_id = ".$order_id;
				$query = $this->db->query($sql);
				$item = $query->rows;
				
				foreach ($item as $item) {
					$items[] = $item['items'];
				}
				
				$item_str = implode('|', $items);
			
				$access_key = MDE_ACCESS_KEY;
				echo "delivery api = ".MDE_SITE."/".MDE_INSTANCE."/deliveryapi<br>";
				$cInit = curl_init(MDE_SITE."/".MDE_INSTANCE."/deliveryapi");
				
				$input_array = array(
						'task' => "sendOrder",
						'access_key' => $access_key,
						'site' => 'mdpworldinc.com',
						'order_id' => $order_id,
						'status' => $order_detail['status'],
						'user_id' => $order_detail['user_id'],
						'total' => $order_detail['total'],
						'payment_option' => $order_detail['payment_option'],
						'shipping_fee' => $order_detail['shipping_fee'],
						'quantity' => $order_detail['quantity'],
						'date_added' => $order_detail['date_added'],
						'reference' => $order_detail['reference'],
						'province' => $order_detail['province_id'],
						'city' => $order_detail['city_municipality_id'],
						'brgy' => $order_detail['barangay_id'],
						'street' => $order_detail['address'],
						'customer_fullname' => $order_detail['customer_fullname'],
						'customer_phone' => $order_detail['customer_phone'],
						'customer_email' => $order_detail['customer_email'],
						'admin_fullname' => $order_detail['admin_fullname'],
						'admin_phone' => $order_detail['admin_phone'],
						'sales_rep' => 0,
						'sales_rep_phone' => 0,
						'distributor_fullname' => $order_detail['distributor_fullname'],
						'distributor_phone' => $order_detail['distributor_phone'],
						'affiliate_fullname' => $order_detail['affiliate_fullname'],
						'affiliate_phone' => $order_detail['affiliate_phone'],
						'branch_name' => $order_detail['branch_name'],
						'remarks' => $order_detail['latest_comment'],
						'destination_address' => $order_detail['destination_address'],
						'origin_address' => $order_detail['origin_address'],	
						'admin_id' => 0,
						'shipped_thru' => $order_detail['shipped_thru'],
						'country' => $order_detail['country'],
						'zip_code' => $order_detail['zip_code'],
						'landmarks' => $order_detail['landmarks'],
						'date_ordered' => $order_detail['date_ordered'],
						'requested_delivery_date' => $order_detail['requested_delivery_date'],
						'city_distributor' => $order_detail['city_distributor'],
						'city_distributor_no' => $order_detail['city_distributor_no'],
						'city_distributor_add' => $order_detail['city_distributor_add'],
						'city_distributor_landmark' => $order_detail['city_distributor_landmark'],
						'city_distributor_province' => $order_detail['city_distributor_province'],
						'city_distributor_city' => $order_detail['city_distributor_city'],
						'city_distributor_brgy' => $order_detail['city_distributor_brgy'],
						'quick_delivery_flag' => 1,
						'integration_flag' => 1,
						'package' => $package,
						'ewallet_payment' => 0,
						'items' => $item_str
					);
				
				var_dump(highlight_string("<?\n". var_export($input_array, true)));
				
				curl_setopt_array($cInit, array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => $input_array
				));
				
				$result = curl_exec($cInit);
				$err = curl_errno($cInit);
				$errmsg = curl_error($cInit);
				var_dump($result);
				var_dump($err);
				var_dump($errmsg);
				$return_msg .= "Order Id " .$orders['order_id']. " sent successfully.";
				
			} else {			
				return $return_msg;
			}
			
		}
		return $return_msg;
		
	}
	
	public function updateRemarksApi($data) {
		$order_result = array();
		$return_msg = "";
		$valid = 1;	
		
		// echo $data['order_id']."<br>";
		// var_dump($data['order_id']);
		
		// if($valid == 1) {
			$sql = "select order_id, remarks from oc_order where order_id = ".$data['order_id'];
			$query = $this->db->query($sql);
			$order_id = $query->row['order_id']; 
			$latest_comment = $query->row['remarks'];
		// }
		
		$access_key = "222b6e537e63d37e8aee6e2a6bcd61e6";
		$cInit = curl_init("https://".MDE_SITE."/".MDE_INSTANCE."/deliveryapi");
		// $cInit = curl_init("https://".MDE_SITE."/dev/deliveryapi");
		// $cInit = curl_init("https://".MDE_SITE."/sys/deliveryapi");
		// $cInit = curl_init("http://localhost/manongexpress/deliveryapi");
		curl_setopt_array($cInit, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'task' => "sendRemarks",
					'remarks' => $latest_comment,
					'access_key' => $access_key,
					'site' => 'mdpworldinc.com',
					'order_id' => $order_id
		)
			));
			$result = curl_exec($cInit);
			$err = curl_errno($cInit);
			$errmsg = curl_error($cInit);
			// var_dump($result);
			// echo $result;
			
		$return_msg = "Order Id # ".$order_id." remarks is successfully updated.<br>";

		return $return_msg;
	}
}
?>