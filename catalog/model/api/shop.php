<?php
class ModelApiShop extends Model {

	public function loadItems($data) {

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

			$sql = "SELECT a.item_id, a.item_name, b.description category, a.price, a.short_description, CONCAT('image/products/product', a.item_id, '.jpg') image
					FROM gui_items_tbl a
					JOIN gui_category_tbl b ON(a.category_id = b.category_id)
					WHERE a.active = 1";

			if(isset($data['search'])){
				$sql .= " AND a.item_name LIKE '%".$this->db->escape($data['search'])."%'";
			}

			$items = $this->db->query($sql);

	 		return Array( 	'status'			=> 200,
	  						'valid' 			=> true,
	  						'status_message'	=> 'Items loaded!',
	  						'data'				=> Array('items' =>  $items->rows));
		}
	}

	public function getItemDetails($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['item_id'])) {
			if(empty($data['item_id'])) {
				$valid = 0;
				$return_msg .= "Item ID is required\n";
			} else {
				$sql = "SELECT COUNT(1) total FROM gui_items_tbl WHERE item_id = ".$this->db->escape($data['item_id']);
				$query = $this->db->query($sql);
				$total = $query->row['total'];

				if($total == 0){
					$valid = 0;
					$return_msg .= "Item ID doesn't exist\n";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "Item ID is required\n";
		}

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$sql = "SELECT item_id, item_name, description category, price, short_description, CONCAT('image/products/product', item_id, '.jpg') image
				   	FROM gui_items_tbl 
				  	WHERE item_id = ".$this->db->escape($data['item_id']);

			$item_detail = $this->db->query($sql);

	 		return Array( 	'status'			=> 200,
	  						'valid' 			=> true,
	  						'status_message'	=> 'Item details loaded!',
	  						'data'				=> Array('item_details' => $item_detail->row));
		}
	}

	public function addToCart($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(!isset($data['order_id'])) {
			$valid = 0;	
			$return_msg .= "Order ID is required\n";
		}

		if(isset($data['user_id'])) {
			if(empty($data['user_id'])) {
				$valid = 0;
				$return_msg .= "User ID is required\n";
			}
		} else {
			$valid = 0;	
			$return_msg .= "User ID is required\n";
		}

		if(isset($data['item_id'])) {
			if(empty($data['item_id'])) {
				$valid = 0;
				$return_msg .= "Item ID is required\n";
			}
		} else {
			$valid = 0;	
			$return_msg .= "Item ID is required\n";
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
			if(isset($data['order_id'])) {
				if($data['order_id'] > 0) {
					$result = $this->updateCart($data);
				} else {
					$result = $this->createCart($data);
				}
			} else {
				$result = $this->createCart($data);
			}
			
			$this->summarizeOrder($data);

			$this->load->model('api/mycart');
			$array = Array('order_id'	=>	$result['order_id']);
			$cart = $this->model_api_mycart->getMyCart($array);

			return Array( 	'status'			=> 200,
	  						'valid' 			=> $result['success'],
	  						'status_message'	=> $result['return_msg'],
	  						'data' 				=> $cart['data'] );
		}	
	}

	public function summarizeOrder($data) {
		$sql = "select coalesce(sum(a.quantity),0) quantity, coalesce(sum(a.quantity * b.price),0) price
				  from oc_order_details a
				  join gui_items_tbl b on (a.item_id = b.item_id)
				 where a.order_id = ".$this->db->escape($data['order_id']);
		$query = $this->db->query($sql);
		$quantity = $query->row['quantity'];
		$price = $query->row['price'];
		
		$sql = "update oc_order 
				   set total = ".$this->db->escape($quantity)."
					 , amount = ".$this->db->escape($price)." 
			     where order_id = ".$this->db->escape($data['order_id']);
		$this->db->query($sql);
	}

	public function createCart($data) {
		$result = array();
		
		$user_id = $data['user_id'];
		
		if(isset($data['order_id'])) {						
			if($data['order_id'] == 0) {
				$sql = "insert into oc_order(user_id, date_added) 
						values (".$this->db->escape($user_id).",'".$this->user->now()."')";
				$this->db->query($sql);
				$order_id = $this->db->getLastId();
				
				$sql = "update oc_order 
						   set ref = '".md5("KACHI".$order_id)."' 
						 where order_id = ".$this->db->escape($order_id);
				$this->db->query($sql);
			} else {
				$order_id = $data['order_id'];
			}
		} else {
			$sql = "insert into oc_order(user_id, date_added) 
					values (".$this->db->escape($user_id).",'".$this->user->now()."')";
			$this->db->query($sql);
			$order_id = $this->db->getLastId();
			
			$sql = "update oc_order 
						   set ref = '".md5("KACHI".$order_id)."' 
						 where order_id = ".$this->db->escape($order_id);
			$this->db->query($sql);
		}
		
		if(isset($order_id)) {
			
			$sql = "select count(1) total 
					  from oc_order_details 
					 where order_id = ".$this->db->escape($order_id)." 
					   and item_id = ".$this->db->escape($data['item_id']);
			$query = $this->db->query($sql);
			if($query->row['total'] == 0) {
				$sql = "insert into oc_order_details 
						   set order_id = ".$this->db->escape($order_id)."
							 , item_id = ".$this->db->escape($data['item_id'])."
							 , quantity = ".$this->db->escape($data['quantity'])."
							 , date_added = '".$this->user->now()."'";
				$this->db->query($sql);
			} else {
				$sql = "update oc_order_details 
						   set quantity = ".$this->db->escape($data['quantity'])."
						 where order_id = ".$this->db->escape($order_id)." 
						   and item_id = ".$this->db->escape($data['item_id']);
				$this->db->query($sql);
			}

			$sql = "UPDATE oc_user SET cart_id = ".$this->db->escape($order_id)." WHERE user_id = ".$this->db->escape($data['user_id']);
			$this->db->query($sql);
			
			$result['order_id'] = $order_id;
			$result['success'] = true;
			$result['return_msg'] = "Successful addition of item and creation of order #".$order_id;
		} else {
			$result['success'] = false;
			$result['return_msg'] = "Failed addition of item.";
		}
	}

	public function updateCart($data) {
		$result = array();
		
		$user_id = $data['user_id'];
		
		$order_id = $data['order_id'];
		
		$sql = "select count(1) total 
				  from oc_order_details 
				 where order_id = ".$this->db->escape($order_id)." 
				   and item_id = ".$this->db->escape($data['item_id']);
		$query = $this->db->query($sql);
		if($query->row['total'] == 0) {
			$sql = "insert into oc_order_details 
					   set order_id = ".$this->db->escape($order_id)."
						 , item_id = ".$this->db->escape($data['item_id'])."
						 , quantity = ".$this->db->escape($data['quantity'])."
						 , date_added = '".$this->user->now()."'";
			$this->db->query($sql);
		} else {
			$sql = "update oc_order_details 
					   set quantity = quantity + ".$this->db->escape($data['quantity'])."
					 where order_id = ".$this->db->escape($order_id)." 
					   and item_id = ".$this->db->escape($data['item_id']);
			$this->db->query($sql);
		}
		
		$result['order_id'] = $order_id;
		$result['success'] = true;
		$result['return_msg'] = "Successful addition of item and update of order #".$order_id;
		return $result;
	}	

}
?>