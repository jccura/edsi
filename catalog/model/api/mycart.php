<?php
class ModelApiMyCart extends Model {

	public function getMyCart($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['order_id'])) {
			if(empty($data['order_id'])) {
				$valid = 0;
				$return_msg .= "Order ID is required\n";
			} else {
				$sql = "SELECT COUNT(1) total FROM oc_order WHERE order_id = ".$this->db->escape($data['order_id']);
				$query = $this->db->query($sql);
				$total = $query->row['total'];

				if($total == 0){
					$valid = 0;
					$return_msg .= "Order ID doesn't exist\n";
				}
			}
		} else {
			$valid = 0;	
			$return_msg .= "Order ID is required\n";
		}

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$sql = "select a.order_id, a.status_id, a.customer_name, a.contact, a.email, a.ref, a.user_id, g.user_group_id,
						   a.total, a.amount, e.description payment_option, f.description delivery_option,
						   concat(a.address, ',', d.description, ',', c.description, ',', b.description) address
					  from oc_order a 
					  left join oc_provinces b on(a.province_id = b.province_id)
					  left join oc_city_municipality c on(a.city_municipality_id = c.city_municipality_id)
					  left join oc_barangays d on(a.barangay_id = d.barangay_id)
					  left join gui_status_tbl e on(a.payment_option = e.status_id)
					  left join gui_status_tbl f on(a.delivery_option = f.status_id)
					  join oc_user g on(a.user_id = g.user_id)
					 where order_id = ".$this->db->escape($data['order_id']);
					 
			$query = $this->db->query($sql);
			$return['header'] = $query->row;
			
			$sql = "select a.order_det_id, b.item_id, b.item_name, 
						   a.quantity, a.quantity * b.price amount, CONCAT('image/products/product', a.item_id, '.jpg') image
					  from oc_order_details a
					  join gui_items_tbl b on(a.item_id = b.item_id) 
					 where a.order_id = ".$this->db->escape($data['order_id']);
			$query = $this->db->query($sql);
			$return['cart_items'] = $query->rows;
			
			$return['with_package'] = $this->determineIfWithPackage($data['order_id']);
			$return['points'] = $this->getPoints($data['order_id']);
			$return['delivery_fee'] = 0;
			
			$return['retpoints'] = $this->getRetailPoints($data['order_id']);
			
			if($return['header']['user_group_id'] == 39) {
				$return['discount'] = $return['retpoints'];
			} else {
				$return['discount'] = 0;
			}

	 		return Array( 	'status'			=> 200,
	  						'valid' 			=> true,
	  						'status_message'	=> 'Cart successfully loaded!',
	  						'data'				=> $return );
		}
	}

	public function determineIfWithPackage($order_id) {
		$sql = "select count(1) total
				  from oc_order_details a
				  join gui_items_tbl b on(a.item_id = b.item_id)
				 where a.order_id = ".$this->db->escape($order_id)."
				   and b.category_id = 1 ";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getPoints($order_id) {
		$sql = "select coalesce(sum(b.points * a.quantity),0) points
				  from oc_order_details a
				  join gui_items_tbl b on(a.item_id = b.item_id)
				 where a.order_id = ".$this->db->escape($order_id);
		$query = $this->db->query($sql);
		return $query->row['points'];
	}
	
	public function getRetailPoints($order_id) {
		$sql = "select coalesce(sum(a.quantity * b.discount_per_item),0) points
				  from oc_order_details a
				  join gui_items_tbl b on(a.item_id = b.item_id)
				 where a.order_id = ".$this->db->escape($order_id)."
				   and b.category_id = 2 ";
		$query = $this->db->query($sql);
		return $query->row['points'];
	}

	public function removeItem($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['order_det_id'])) {
			if(empty($data['order_det_id'])) {
				$valid = 0;
				$return_msg .= "Order Details ID is required\n";
			}
		} else {
			$valid = 0;	
			$return_msg .= "Order Details ID is required\n";
		}

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$sql = "SELECT order_id FROM oc_order_details WHERE order_det_id = ".$this->db->escape($data['order_det_id']);
			$order_id = $this->db->query($sql)->row['order_id'];

			$sql = "delete from oc_order_details where order_det_id = ".$this->db->escape($data['order_det_id']);
			$this->db->query($sql);

			$this->load->model('api/shop');
			$array = Array('order_id' => $order_id);
			$this->model_api_shop->summarizeOrder($array);
			$cart = $this->getMyCart($array);

			return Array( 	'status'			=> 200,
	  						'valid' 			=> true,
	  						'status_message'	=> 'Item removed successfully.',
	  						'data' 				=> $cart);	
		}
	}

	public function getPaymentOptions($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$sql = "SELECT status_id,description 
				  	FROM gui_status_tbl 
					where `grouping` = 'PAYMENT OPTION' ORDER BY status_id ";

			$payment_options = $this->db->query($sql);

			return Array( 	'status'			=> 200,
	  						'valid' 			=> true,
	  						'status_message'	=> 'Payment options loaded successfully.',
	  						'data' 				=> Array('payment_options' => $payment_options->rows) );	
		}
	}

	public function getDeliveryOption($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['payment_option_id'])) {
			if(empty($data['payment_option_id'])) {
				$valid = 0;
				$return_msg .= "Payment Option ID is required\n";
			}
		} else {
			$valid = 0;	
			$return_msg .= "Payment Option ID is required\n";
		}

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			if(in_array($data['payment_option_id'], Array(89, 90, 91))){
				$delivery_options = Array(	'delivery_option' => 96,
											'delivery_option_desc' => 'Dropship');
			} else if(in_array($data['payment_option_id'], Array(92, 100))){
				$delivery_options = Array(	'delivery_option' => 97,
											'delivery_option_desc' => 'Kachimogo');
			} else if(in_array($data['payment_option_id'], Array(94, 107))){
				$delivery_options = Array(	'delivery_option' => 109,
											'delivery_option_desc' => 'LBC Express');
			} else if(in_array($data['payment_option_id'], Array(93, 106))){
				$delivery_options = Array(	'delivery_option' => 98,
											'delivery_option_desc' => 'Manong Express');
			} else if(in_array($data['payment_option_id'], Array(95, 108))){
				$delivery_options = Array(	'delivery_option' => 99,
											'delivery_option_desc' => 'J & T Express');
			}

			

			return Array( 	'status'			=> 200,
	  						'valid' 			=> true,
	  						'status_message'	=> 'Delivery Options loaded successfully.',
	  						'data' 				=> Array('delivery_options' => Array($delivery_options)) );	
		}
	}

	public function getCalculations($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['order_id'])) {
			if(empty($data['order_id'])) {
				$valid = 0;
				$return_msg .= "Order ID is required\n";
			} else {
				$sql = "SELECT COUNT(1) total FROM oc_order WHERE order_id = ".$this->db->escape($data['order_id']);
				$query = $this->db->query($sql);
				$total = $query->row['total'];

				if($total == 0){
					$valid = 0;
					$return_msg .= "Order ID doesn't exist\n";
				}
			}
		} else {
			$valid = 0;	
			$return_msg .= "Order ID is required\n";
		}

		if(isset($data['payment_option_id'])) {
			if(empty($data['payment_option_id'])) {
				$valid = 0;
				$return_msg .= "Payment Option ID is required\n";
			} else {
				$sql = "SELECT COUNT(1) total FROM gui_status_tbl 
					where `grouping` = 'PAYMENT OPTION' AND status_id = ".$this->db->escape($data['payment_option_id']);
				$query = $this->db->query($sql);
				$total = $query->row['total'];

				if($total == 0){
					$valid = 0;
					$return_msg .= "Payment Option ID doesn't exist\n";
				}
			}
		} else {
			$valid = 0;	
			$return_msg .= "Payment Option ID is required\n";
		}

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$delivery_fee = 0;
			$discount = 0;
			$grand_total = 0;

			$points = $this->getPoints($data['order_id']);		
			$sql = "select rate delivery_fee 
					  from oc_shipping_rates 
					 where payment_option = ".$this->db->escape($data['payment_option_id'])." 
					   and quantity = ".ceil($points);

			$query = $this->db->query($sql);

			if(isset($query->row['delivery_fee'])) {
				$delivery_fee = $query->row['delivery_fee'];
			} 

			$cart = $this->getMyCart(Array('order_id' => $data['order_id']))['data'];
			$total_quantity 	= $cart['header']['total'];
			$total_item_amount 	= $cart['header']['amount'];
			$discount 			= $cart['discount'];
			$grand_total 		= $cart['header']['amount'] + $delivery_fee - $discount;

			return Array( 	'status'			=> 200,
	  						'valid' 			=> true,
	  						'status_message'	=> 'Calculations loaded successfully.',
	  						'data' 				=> Array(	'total_quantity' => $total_quantity,
	  														'total_item_amount' => $total_item_amount,
	  														'delivery_fee' => $delivery_fee,
	  														'discount' => $discount,
	  														'grand_total' => $grand_total) );	
		}
	}

	public function updateItemQuantity($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['order_det_id'])) {
			if(empty($data['order_det_id'])) {
				$valid = 0;
				$return_msg .= "Order Details ID is required\n";
			}
		} else {
			$valid = 0;	
			$return_msg .= "Order Details ID is required\n";
		}

		if(isset($data['quantity'])) {
			if(empty($data['quantity'])) {
				$valid = 0;
				$return_msg .= "Quantity is required\n";
			}
		} else {
			$valid = 0;	
			$return_msg .= "Quantity is required\n";
		}

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$sql = "UPDATE oc_order_details 
					SET quantity = ".$this->db->escape($data['quantity'])."
					WHERE order_det_id = ".$this->db->escape($data['order_det_id']);
			$query = $this->db->query($sql); // ,	cost = cost * ".$this->db->escape($data['quantity'])."

			$sql = "SELECT order_id FROM oc_order_details WHERE order_det_id = ".$this->db->escape($data['order_det_id']);
			$order_id = $this->db->query($sql)->row['order_id'];

			$this->load->model('api/shop');
			$array = Array('order_id' => $order_id);
			$this->model_api_shop->summarizeOrder($array);
			$cart = $this->getMyCart($array);

			return Array( 	'status'			=> 200,
	  						'valid' 			=> true,
	  						'status_message'	=> 'Item quantity successfully updated.',
	  						'data' 				=> $cart);	
		}
	}

	public function checkout($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['send_to'])) {
			if(empty($data['send_to'])) {
				$valid = 0;
				$return_msg .= "Sent to is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Sent to is required\n";
		}

		if(isset($data['user_id'])) {
			if(empty($data['user_id'])) {
				$valid = 0;
				$return_msg .= "User ID is required\n";
			} else {
				$sql = "SELECT COUNT(1) total, user_group_id, refer_by_id FROM oc_user WHERE user_id = ".$this->db->escape($data['user_id']);
				$query = $this->db->query($sql);

				$total = $query->row['total'];
				$user_group_id = $query->row['user_group_id'];
				$reseller_id = $user_group_id == 13 ? $query->row['refer_by_id'] : $data['user_id'];

				if($total == 0){
					$valid = 0;
					$return_msg .= "User ID doesn't exist\n";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "User ID is required\n";
		}

		if(isset($data['order_id'])) {
			if(empty($data['order_id'])) {
				$valid = 0;
				$return_msg .= "Order ID is required\n";
			} else {
				$sql = "SELECT COUNT(1) total FROM oc_order WHERE order_id = ".$this->db->escape($data['order_id']);
				$query = $this->db->query($sql);

				if($total == 0){
					$valid = 0;
					$return_msg .= "Order ID doesn't exist\n";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "Order ID is required\n";
		}
		
		if(isset($data['firstname'])) {
			if(empty($data['firstname'])) {
				$valid = 0;
				$return_msg .= "Firstname is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Firstname is required\n";
		}

		if(isset($data['lastname'])) {
			if(empty($data['lastname'])) {
				$valid = 0;
				$return_msg .= "lastname is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "lastname is required\n";
		}

		if(isset($data['province_id'])) {
			if(empty($data['province_id'])) {
				$valid = 0;
				$return_msg .= "Province ID is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Province ID is required\n";
		}

		if(isset($data['city_id'])) {
			if(empty($data['city_id'])) {
				$valid = 0;
				$return_msg .= "City ID is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "City ID is required\n";
		}

		if(isset($data['barangay_id'])) {
			if(empty($data['barangay_id'])) {
				$valid = 0;
				$return_msg .= "Barangay ID is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Barangay ID is required\n";
		}
		
		if(isset($data['address'])) {
			if(empty($data['address'])) {
				$valid = 0;
				$return_msg .= "Address is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Address is required\n";
		}
		
		if(isset($data['contact'])) {
			if(empty($data['contact'])) {
				$valid = 0;
				$return_msg .= "Contact is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Contact is required\n";
		}

		if(isset($data['email'])) {
			if(empty($data['email'])) {
				$valid = 0;
				$return_msg .= "Email is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Email is required\n";
		}
		
		if(isset($data['payment_option_id'])) {
			if(empty($data['payment_option_id'])) {
				$valid = 0;
				$return_msg .= "Payment Option is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Payment Option is required\n";
		}

		if(isset($data['delivery_option_id'])) {
			if(empty($data['delivery_option_id'])) {
				$valid = 0;
				$return_msg .= "Delivery Option is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Delivery Option is required\n";
		}
				
		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$calculations =$this->getCalculations(Array('order_id' => $data['order_id'], 'payment_option_id' => $data['payment_option_id']))['data'];
			$delivery_fee = $calculations['delivery_fee'];
			$grand_total = $calculations['grand_total'];
			$discount = $calculations['discount'];

			$send_to = $data['send_to'] == 'My Address' ? 110 : 111;

			$sql = "UPDATE oc_order 
				   	SET customer_name 			= '".$this->db->escape(strtoupper($data['firstname']))." ".$this->db->escape(strtoupper($data['lastname']))."'
					,	firstname 				= '".$this->db->escape(strtoupper($data['firstname']))."'
					,	lastname 				= '".$this->db->escape(strtoupper($data['lastname']))."'
					,	province_id 			=  ".$this->db->escape($data['province_id'])."
					,	city_municipality_id	=  ".$this->db->escape($data['city_id'])."
					,	barangay_id 			= '".$this->db->escape($data['barangay_id'])."'
					,	payment_option 			= '".$this->db->escape($data['payment_option_id'])."'
					,	payment_option 			= '".$this->db->escape($data['payment_option_id'])."'
					,	delivery_option 		= '".$this->db->escape($data['delivery_option_id'])."'
					,	delivery_fee 			= '".$this->db->escape($delivery_fee)."'
					,	send_to 				=  ".$this->db->escape($send_to)."
					,	reseller_id 			=  ".$this->db->escape($reseller_id)."
					,	amount 					=  ".$this->db->escape($grand_total)."
					,	discount 				= '".$this->db->escape($discount)."'
					,	address 				= '".$this->db->escape($data['address'])."'
					,	contact 				= '".$this->db->escape($data['contact'])."'
					,	email 					= '".$this->db->escape($data['email'])."'
					,	status_id 				= 18
				 	WHERE order_id 				= ".$this->db->escape($data['order_id']);
				 
			$this->db->query($sql);
			$this->session->data['order_id'] = 0;
			
			if(isset($data['username'])) {
				$sql = "UPDATE oc_order 
				  		SET username = '".$this->db->escape($data['username'])."'
				 		WHERE order_id = ".$this->db->escape($data['order_id']);
				 $this->db->query($sql);
			}
			
			if(isset($data['password'])) {
				$sql = "UPDATE oc_order 
				  		SET password = '".$this->db->escape($data['password'])."'
				 		WHERE order_id = ".$this->db->escape($data['order_id']);
				 $this->db->query($sql);
			}

			$sql = "UPDATE oc_user SET cart_id = 0 WHERE user_id = ".$this->db->escape($data['user_id']);
			$this->db->query($sql);
			
			return Array( 	'status'			=> 200,
	  						'valid' 			=> true,
	  						'status_message'	=> 'Order successful.',
	  						'data' 				=> [] );	
		}
	}

}
?>