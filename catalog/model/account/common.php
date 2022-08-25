<?php
class ModelAccountCommon extends Model {
	
	public function getActiveItems($data, $query_type){		
		$sql = " select b.description category, a.* 
				   from gui_items_tbl a
				   join gui_category_tbl b on(a.category_id = b.category_id)
				  where a.active = 1 ";
				  
		if($query_type == "data") {					
			$sql .= " order by sort ";
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
				if ($data['limit'] < 1) {
					$data['limit'] = 5;
				}			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}		
			$query = $this->db->query($sql);
			return $query->rows;			
		} else {
			$sqlt = "select count(1) total from (".$sql.") t ";			
			$query = $this->db->query($sqlt);
			return $query->row['total'];
		}			  
	}
	
	public function getItem($data) {
		$sql = " select b.description category, a.* 
				   from gui_items_tbl a 
				   join gui_category_tbl b on(a.category_id = b.category_id)
				  where a.item_id = ".$data['item_id'];
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getDistributorPackage($data) {
		$sql = " select b.description category, a.* 
					from gui_items_tbl a 
					join gui_category_tbl b on(a.category_id = b.category_id)
					where a.active = 1 and a.user_group_id = 56	";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getResellerPackage($data) {
		$sql = " select b.description category, a.* 
					from gui_items_tbl a 
					join gui_category_tbl b on(a.category_id = b.category_id)
					where a.active = 1 and a.user_group_id = 46 ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getItemRetail($data) {
		$sql = " select b.description category, a.* 
					from gui_items_tbl a
					join gui_category_tbl b on(a.category_id = b.category_id)
					where a.active = 1 and a.category_id = 2; ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function addToCart($data) {
		$result = array();
		if(isset($data['order_id'])) {
			if($data['order_id'] > 0) {
				$result = $this->updateCart($data);
				// echo "updateCart";
			} else {
				$result = $this->createCart($data);
				// echo "createCart1";
			}
		} else {
			$result = $this->createCart($data);
			// echo "createCart2";
		}
		
		$this->summarizeOrder($data);
		
		return $result;
	}
	
	public function summarizeOrder($data) {
		// var_dump($data);
		$sql = "select coalesce(sum(a.quantity),0) quantity, coalesce(sum(a.quantity * b.price),0) price
				  from oc_order_details a
				  join gui_items_tbl b on (a.item_id = b.item_id)
				 where a.order_id = ".$this->session->data['order_id'];
		$query = $this->db->query($sql);
		$quantity = $query->row['quantity'];
		$price = $query->row['price'];
		
		$sql = "update oc_order 
				   set total = ".$quantity."
					 , amount = ".$price." 
			     where order_id = ".$this->session->data['order_id'];
		$this->db->query($sql);
		// var_dump($sql);
	}
	
	public function addToCartForDownline($data) {
		//var_dump($data);
		
		$result = array();
		//$order_id = $this->session->data['order_id'];
		//$order_id = isset($this->session->data['order_id']) ? $this->session->data['order_id'] : 0;
		$order_id = 0;
		//echo($order_id); 
		$user_id = 0;
		$current_order_id = 0;
		
		//check if downline user id is a reseller or wholesaler
		$sql = "select user_group_id, firstname, lastname, province_id, city_municipality_id, barangay_id, address
				  from oc_user 
				 where user_id = ".$data['downline_user_id'];
		$query = $this->db->query($sql);
		$user_group_id = $query->row['user_group_id'];
		$firstname = $query->row['firstname'];
		$lastname = $query->row['lastname'];
		$province_id = $query->row['province_id'];
		$city_municipality_id = $query->row['city_municipality_id'];
		$barangay_id = $query->row['barangay_id'];
		$address = $query->row['address'];
			
		if($this->user->isLogged()) {
			$user_id = $this->user->getId();
			
			$sql = "select current_order_id 
					from oc_user 
				where user_id = ".$user_id;
			$query = $this->db->query($sql);
			$order_id = $query->row['current_order_id'];
			
			$sql = "select status_id 
					from oc_order 
				where order_id = ".$order_id;
			$query = $this->db->query($sql);
			//var_dump($data);
			// echo $sql;
			
			// if($query->row['status_id'] == 18){
				// $current_order_id = 0;
			// } else{
				// $current_order_id = $order_id;
			// }
			
			if($query->row['status_id'] == 138){
				$current_order_id = 0;
			} else{
				$current_order_id = $order_id;
			}
		}
		
		
		//unset($_SESSION['order_id']);
		//var_dump($this->session->data);
		
		//if current_order_id == 0
		if($current_order_id == 0){
			
			if(isset($this->session->data['order_id'])) {
				//echo ($this->session->data['order_id']);
				if($this->session->data['order_id'] == 0) {
					//if reseller
					if($user_group_id == 46){
						$sql = "insert into oc_order(user_id, reseller_id, province_id, 
													 city_municipality_id, barangay_id, address,
													 firstname, lastname, date_added) 
								values (".$user_id.",".$data['downline_user_id'].",".$province_id.",
										".$city_municipality_id.",".$barangay_id.",'".$address."',
										'".$firstname."','".$lastname."','".$this->user->now()."')";
						$this->db->query($sql);
						$order_id = $this->db->getLastId();
						
					//if wholesaler
					} else if($user_group_id == 56){
						$sql = "insert into oc_order(user_id, wholesaler_id, province_id, 
													 city_municipality_id, barangay_id, address,
													 firstname, lastname, date_added) 
								values (".$user_id.",".$data['downline_user_id'].",".$province_id.",
										".$city_municipality_id.",".$barangay_id.",'".$address."',
										'".$firstname."','".$lastname."','".$this->user->now()."')";
						$this->db->query($sql);
						$order_id = $this->db->getLastId();						
					} else if($user_group_id == 57){
						$sql = "insert into oc_order(user_id, city_distributor_id, province_id, 
													 city_municipality_id, barangay_id, address,
													 firstname, lastname, date_added) 
								values (".$user_id.",".$data['downline_user_id'].",".$province_id.",
										".$city_municipality_id.",".$barangay_id.",'".$address."',
										'".$firstname."','".$lastname."','".$this->user->now()."')";
						$this->db->query($sql);
						$order_id = $this->db->getLastId();						
					}
					$sql = "update oc_order 
							   set ref = '".md5("AV".$order_id)."' 
							 where order_id = ".$order_id;
					$this->db->query($sql);		
					
					$sql = "update oc_user 
							   set current_order_id = ".$order_id."
							 where user_id = ".$user_id;
					$this->db->query($sql);	
				} else {				
					//if reseller
					if($user_group_id == 46){
						$sql = "update oc_order 
								   set reseller_id =".$data['downline_user_id'].", wholesaler_id = 0, city_distributor_id= 0,
								   province_id = ".$province_id.",city_municipality_id = ".$city_municipality_id.", 
								   barangay_id = ".$barangay_id.", address = '".$address."',
								   firstname = '".$firstname."', lastname = '".$lastname."'
								 where order_id =" .$this->session->data['order_id'];
						$this->db->query($sql);
					//if wholesaler
					} else if($user_group_id == 56){
						$sql = "update oc_order 
								   set wholesaler_id =".$data['downline_user_id'].", reseller_id = 0, city_distributor_id= 0,
								   province_id = ".$province_id.",city_municipality_id = ".$city_municipality_id.", 
								   barangay_id = ".$barangay_id.", address = '".$address."',
								   firstname = '".$firstname."', lastname = '".$lastname."'
								 where order_id =" .$this->session->data['order_id'];
						$this->db->query($sql);
					//if city_distributor
					} else if($user_group_id == 57){
						$sql = "update oc_order 
								   set city_distributor_id =".$data['downline_user_id'].",
								   wholesaler_id = 0, reseller_id = 0,
								   province_id = ".$province_id.",city_municipality_id = ".$city_municipality_id.", 
								   barangay_id = ".$barangay_id.", address = '".$address."',
								   firstname = '".$firstname."', lastname = '".$lastname."'
								 where order_id =" .$this->session->data['order_id'];
						$this->db->query($sql);
					}			
				}
			} else {
				//if reseller
				if($user_group_id == 46){
					$sql = "insert into oc_order(user_id, reseller_id, province_id, 
													 city_municipality_id, barangay_id, address,
													 firstname, lastname, date_added) 
								values (".$user_id.",".$data['downline_user_id'].",".$province_id.",
										".$city_municipality_id.",".$barangay_id.",'".$address."',
										'".$firstname."','".$lastname."','".$this->user->now()."')";
					$this->db->query($sql);
					$order_id = $this->db->getLastId();
					
				//if wholesaler
				} else if($user_group_id == 56){
					$sql = "insert into oc_order(user_id, wholesaler_id, province_id, 
													 city_municipality_id, barangay_id, address,
													 firstname, lastname, date_added) 
								values (".$user_id.",".$data['downline_user_id'].",".$province_id.",
										".$city_municipality_id.",".$barangay_id.",'".$address."',
										'".$firstname."','".$lastname."','".$this->user->now()."')";
					$this->db->query($sql);
					$order_id = $this->db->getLastId();
				
				//if city_distributor
				} else if($user_group_id == 57){
					$sql = "insert into oc_order(user_id, city_distributor_id, province_id, 
													 city_municipality_id, barangay_id, address,
													 firstname, lastname, date_added) 
								values (".$user_id.",".$data['downline_user_id'].",".$province_id.",
										".$city_municipality_id.",".$barangay_id.",'".$address."',
										'".$firstname."','".$lastname."','".$this->user->now()."')";
					$this->db->query($sql);
					$order_id = $this->db->getLastId();	
				}
				$sql = "update oc_order 
						   set ref = '".md5("AV".$order_id)."' 
						 where order_id = ".$order_id;
				$this->db->query($sql);
				
				$sql = "update oc_user 
						   set current_order_id = ".$order_id."
						 where user_id = ".$user_id;
				$this->db->query($sql);	
			}
			
		} else if($current_order_id > 0){
			//if reseller
				if($user_group_id == 46){
					$sql = "update oc_order 
							   set reseller_id =".$data['downline_user_id'].", wholesaler_id = 0, city_distributor_id = 0,
								   province_id = ".$province_id.",city_municipality_id = ".$city_municipality_id.", 
								   barangay_id = ".$barangay_id.", address = '".$address."',
								   firstname = '".$firstname."', lastname = '".$lastname."'
								 where order_id = ".$current_order_id;
					$this->db->query($sql);
					// echo $sql;
				//if wholesaler
				} else if($user_group_id == 56){
					$sql = "update oc_order 
							   set wholesaler_id =".$data['downline_user_id'].", reseller_id = 0, city_distributor_id = 0,
								   province_id = ".$province_id.", city_municipality_id = ".$city_municipality_id.", 
								   barangay_id = ".$barangay_id.", address = '".$address."',
								   firstname = '".$firstname."', lastname = '".$lastname."'
								 where order_id = ".$current_order_id;
					$this->db->query($sql);					
					// echo $sql;
				//if city_distributor
				} else if($user_group_id == 57){
					$sql = "update oc_order 
							   set city_distributor_id =".$data['downline_user_id'].",
									wholesaler_id = 0, reseller_id = 0,
								   province_id = ".$province_id.",city_municipality_id = ".$city_municipality_id.", 
								   barangay_id = ".$barangay_id.", address = '".$address."',
								   firstname = '".$firstname."', lastname = '".$lastname."'
								 where order_id = ".$current_order_id;
					$this->db->query($sql);
					// echo $sql;
				}
				
				$order_id = $current_order_id;
				
				//echo "dito";
		}
		
		$result['order_id'] = $order_id;
		
		//set order_id to session
		$this->session->data['order_id'] = $order_id;
		
		$result['success'] = 1;
		$result['result_msg'] = "Successful addition of item and creation of order #".$order_id;

		return $result;	
	}
	
	public function createCart($data) {
		$result = array();
		
		if($this->user->isLogged()) {
			$user_id = $this->user->getId();
		} else {
			$user_id = 0;
		}
		
		if(isset($this->session->data['order_id'])) {						
			if($this->session->data['order_id'] == 0) {
				$sql = "insert into oc_order(user_id, date_added, payment_option, delivery_option) 
						values (".$user_id.",'".$this->user->now()."',0,0)";
				$this->db->query($sql);
				$order_id = $this->db->getLastId();
				
				$sql = "update oc_order 
						   set ref = '".md5("AV".$order_id)."' 
						 where order_id = ".$order_id;
				$this->db->query($sql);
				
				$sql = "insert into oc_order_hist_tbl
							set user_id = ".$user_id.",
								order_id = ".$order_id.",
								from_status_id = 0,
								to_status_id = 0,
								remarks = 'New',
								date_added = '".$this->user->now()."' ";
				$this->db->query($sql);
				
			} else {
				$order_id = $this->session->data['order_id'];
			}
		} else {
			$sql = "insert into oc_order(user_id, date_added, payment_option, delivery_option) 
					values (".$user_id.",'".$this->user->now()."',0,0)";
			$this->db->query($sql);
			$order_id = $this->db->getLastId();
			
			$sql = "update oc_order 
						   set ref = '".md5("AV".$order_id)."' 
						 where order_id = ".$order_id;
			$this->db->query($sql);
			
			$sql = "insert into oc_order_hist_tbl
							set user_id = ".$user_id.",
								order_id = ".$order_id.",
								from_status_id = 0,
								to_status_id = 0,
								remarks = 'New',
								date_added = '".$this->user->now()."' ";
			$this->db->query($sql);
		}
		
		if(isset($order_id)) {
			
			$sql = "select count(1) total 
					  from oc_order_details 
					 where order_id = ".$order_id." 
					   and item_id = ".$data['item_id'];
			$query = $this->db->query($sql);
			
			if($query->row['total'] == 0) {
				
				$sql = "select price from gui_items_tbl
						 where item_id = ".$data['item_id'];
				$query = $this->db->query($sql);
				$amount = $query->row['price'];
				// var_dump($amount);
				
				$sql = "insert into oc_order_details 
						   set order_id = ".$order_id."
							 , item_id = ".$data['item_id']."
							 , cost = ".$amount." 
							 , quantity = ".$data['quantity']."
							 , date_added = '".$this->user->now()."'";
				$this->db->query($sql);
				
				// echo "createCart total 0";
			} else {
				$sql = "select a.order_det_id, b.item_id, b.item_name, 
						   a.quantity, a.quantity * b.price amount
					  from oc_order_details a
					  join gui_items_tbl b on(a.item_id = b.item_id) 
					 where a.order_id = ".$order_id;
				$query = $this->db->query($sql);
				$cost = $query->row['amount'];
				// var_dump($cost);
			
				$sql = "update oc_order_details 
						   set quantity = ".$data['quantity']."
							 , cost = ".$cost." 
						 where order_id = ".$order_id." 
						   and item_id = ".$data['item_id'];
				$this->db->query($sql);
				// echo "createCart total 1";
			}
			
			$result['order_id'] = $order_id;
			$this->session->data['order_id'] = $order_id;
			$result['success'] = 1;
			$result['return_msg'] = "Successful addition of item and creation of order #".$order_id;
		} else {
			$result['success'] = 0;
			$result['return_msg'] = "Failed addition of item.";
		}
	}
	
	public function updateCart($data) {
		$result = array();
		
		if($this->user->isLogged()) {
			$user_id = $this->user->getId();
		} else {
			$user_id = 0;
		}
		
		$order_id = $this->session->data['order_id'];
		
		$sql = "select count(1) total 
				  from oc_order_details 
				 where order_id = ".$order_id." 
				   and item_id = ".$data['item_id'];
		$query = $this->db->query($sql);
		if($query->row['total'] == 0) {
			
			$sql = "select price from gui_items_tbl
					 where item_id = ".$data['item_id'];
			$query = $this->db->query($sql);
			$amount = $query->row['price'];
			// var_dump($amount);
				
			$sql = "insert into oc_order_details 
					   set order_id = ".$order_id."
						 , item_id = ".$data['item_id']."
						 , cost = ".$amount."
						 , quantity = ".$data['quantity']."
						 , date_added = '".$this->user->now()."'";
			$this->db->query($sql);
			// echo "updateCart total 0";
		} else {
			$sql = "select a.order_det_id, b.item_id, b.item_name, 
					   a.quantity, (a.quantity + ".$data['quantity'].") * b.price amount
				  from oc_order_details a
				  join gui_items_tbl b on(a.item_id = b.item_id) 
				 where a.order_id = ".$order_id."
						and a.item_id = ".$data['item_id'];
			$query = $this->db->query($sql);
			$cost = $query->row['amount'];
			// var_dump($cost);
			
			$sql = "update oc_order_details 
					   set quantity = quantity + ".$data['quantity']."
					     , cost = ".$cost."
					 where order_id = ".$order_id." 
					   and item_id = ".$data['item_id'];
			$this->db->query($sql);
			// echo "updateCart total 1";
		}
		
		$result['order_id'] = $order_id;
		$this->session->data['order_id'] = $order_id;
		$result['success'] = 1;
		$result['return_msg'] = "Successful addition of item and update of order #".$order_id;
	}	
	
	public function getSmeFlag() {
		$sql = "select sme_flag
				  from oc_user
				 where user_id = ".$this->user->getId();
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getCart($data = array()) {
		$return = array();
		$order_id = 0;
		
		if(isset($this->session->data['order_id'])) {
			$order_id = $this->session->data['order_id'];
		}
		
		if(isset($data['order_id'])) {
			if($data['order_id'] > 0) {
				$order_id = $data['order_id'];
			}
		}
		
		if(isset($data['ref'])) {
			if(!empty($data['ref'])) {
				$sql = "select order_id from oc_order where ref = '".$data['ref']."' ";
				$query = $this->db->query($sql);
				$order_id = $query->row['order_id'];
			}
		}
		
		$sql = "select a.order_id, a.status_id, a.customer_name, a.contact, a.email, a.ref, a.user_id, g.user_group_id,
				a.total, a.landmark, a.amount,case when a.payment_option in(148,157,158,146,147) then concat(e.description,'(',h.username,')') else e.description end payment_option,
				f.description delivery_option,concat(a.address, ',', d.description, ',', c.description, ',', b.description) address, a.delivery_fee,a.send_to
				from oc_order a 
				left join oc_provinces b on(a.province_id = b.province_id)
				left join oc_city_municipality c on(a.city_municipality_id = c.city_municipality_id)
				left join oc_barangays d on(a.barangay_id = d.barangay_id)
				left join gui_status_tbl e on(a.payment_option = e.status_id)
				left join gui_status_tbl f on(a.delivery_option = f.status_id)
				left join oc_user g on(a.user_id = g.user_id)
				left join oc_user h on(a.city_distributor_id = h.user_id)
				where order_id = ".$order_id;
				 
		$query = $this->db->query($sql);
		$return['header'] = $query->row;
		//$total_quantity = $query->row['total'];
		
		$total_quantity = isset($query->row['total']) ? $query->row['total'] : 0;
		
		$sql = "select a.order_det_id, b.item_id, b.item_name, 
				a.quantity, a.quantity * b.price amount, 
				(b.price * b.distributor_discount_per * a.quantity / 100) distributor_disc , 
				(b.price * b.reseller_discount_per * a.quantity / 100) reseller_disc
				from oc_order_details a
				join gui_items_tbl b on(a.item_id = b.item_id) 
				where a.order_id = ".$order_id;
				 
		$query = $this->db->query($sql);
		
		$return['details'] = $query->rows;
		
		//var_dump($return['details']);
		
		$distributor_disc = 0;
		$reseller_disc = 0;
		
		foreach($return['details'] as $odet) {
			if($odet['distributor_disc'] > 0) {
				$distributor_disc += $odet['distributor_disc'];
			}
			
			if($odet['reseller_disc'] > 0) {
				$reseller_disc += $odet['reseller_disc'];
			}
		}
		
		if($this->user->isLogged()) {
			if(isset($return['header']['send_to'])) {
				if($return['header']['send_to'] == 111) {
					$return['discount'] = 0;
				} else {
					if($this->user->getUserGroupId() == 56) { //dist
						$return['discount'] = $distributor_disc;
					} else if($this->user->getUserGroupId() == 46) { //reseller
						$return['discount'] = $reseller_disc;
					} else {
						$return['discount'] = 0;
					}
				}
			} else {
				$return['discount'] = 0;
			}
		} else {
			$return['discount'] = 0;
		}
		$return['delivery_fee'] = 0;
		$return['with_package'] = $this->determineIfWithPackage($order_id);
		$return['with_usergroup'] = $this->determineIfWithUserGroup($order_id);
		
		return $return;
	}
	
	public function determineIfWithPackage($order_id) {
		$sql = "select count(1) total
				  from oc_order_details a
				  join gui_items_tbl b on(a.item_id = b.item_id)
				 where a.order_id = ".$order_id."
				   and b.category_id = 1 ";
		$query = $this->db->query($sql);
		// echo $sql;
		return $query->row['total'];
	}
	
	public function determineIfWithUserGroup($order_id) {
		$sql = "select count(1) total
				  from oc_order_details a
				  join gui_items_tbl b on(a.item_id = b.item_id)
				 where a.order_id = ".$order_id."
				   and b.user_group_id > 0 ";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	
	public function getPoints($order_id) {
		$sql = "select coalesce(sum(b.points * a.quantity),0) points
				  from oc_order_details a
				  join gui_items_tbl b on(a.item_id = b.item_id)
				 where a.order_id = ".$order_id;
		$query = $this->db->query($sql);
		return $query->row['points'];
	}
	
	public function getRetailPoints($order_id) {
		$sql = "select coalesce(sum(a.quantity * b.discount_per_item),0) points
				  from oc_order_details a
				  join gui_items_tbl b on(a.item_id = b.item_id)
				 where a.order_id = ".$order_id."
				   and b.category_id = 2 ";
		$query = $this->db->query($sql);
		return $query->row['points'];
	}
	
	public function submitOrder($data) {
		
		$operator_id = 0;
		$sales_rep_id = 0;
		$distributor_id = 0;
		$company_admin_id = 0;
		$e_seller_id = 0;
		$customer = 1;
		
		$order_result = array();
		$valid = 1;
		$return_msg = "";
		
		if($this->user->isLogged()) {
			$customer = 0;
			if($this->user->getUserGroupId() == 39) {
				$operator_id = $this->user->getId();				
			} else {
				$operator_id = $this->user->getOperator();
			}
			
			if($this->user->getUserGroupId() == 45) {
				$sales_rep_id = $this->user->getId();
			}
			
			if($this->user->getUserGroupId() == 46) {
				$sales_rep_id = $this->user->getId();
				$sql = "select a.sponsor_user_id,a.level
						  from oc_unilevel a
						  join oc_user b on (a.sponsor_user_id = b.user_id)
						 where a.user_id = ".$sales_rep_id."
						   and b.user_group_id = 56
						 order by a.level asc
	 					 limit 1";
				$query = $this->db->query($sql);
				$distributor_id = $query->row['sponsor_user_id'];
				
			}

			if($this->user->getUserGroupId() == 56) {
				$distributor_id = $this->user->getId();
			}	
			
			if($this->user->getUserGroupId() == 47) {
				$company_admin_id = $this->user->getId();
				$sql = "select distributor_id from oc_user where user_id = ".$company_admin_id;
				$query = $this->db->query($sql);
				$distributor_id = $query->row['distributor_id'];
			} else {
				$sql = "select admin_id from oc_user where user_id = ".$this->user->getId();
				$query = $this->db->query($sql);
				$company_admin_id = $query->row['admin_id'];
			}
			$user_id = $this->user->getId();
		} else {
			$customer = 1;
			if(isset($data['sponsor'])) {
				$sponsor = 0;
				$user_group_id = 0;
				$sql = "select user_id, user_group_id from oc_user where lower(username) = '".strtolower($data['sponsor'])."'";
				$query = $this->db->query($sql);
				
				if(isset($query->row['user_id'])) {
					$sponsor = $query->row['user_id'];
					$user_group_id = $query->row['user_group_id'];
					
					if($user_group_id == 39) {
						$distributor_id = $sponsor;				
					} else {
						$sql = "select distributor_id from oc_user where user_id = ".$sponsor;
						$query = $this->db->query($sql);
						$distributor_id = $query->row['distributor_id'];
					}
					
					if($user_group_id == 56) {
						$distributor_id = $sponsor;
					}	
					
					if($user_group_id == 46) {
						$sales_rep_id = $sponsor;
						$sql = "select distributor_id from oc_user where user_id = ".$sponsor;
						$query = $this->db->query($sql);
						$distributor_id = $query->row['distributor_id'];
					}
					
					if($user_group_id == 45) {
						$sales_rep_id = $sponsor;
						$sql = "select distributor_id from oc_user where user_id = ".$sponsor;
						$query = $this->db->query($sql);
						$distributor_id = $query->row['distributor_id'];
					}			
					if($user_group_id == 47) {
						$company_admin_id = $sponsor;
						$sql = "select distributor_id from oc_user where user_id = ".$company_admin_id;
						$query = $this->db->query($sql);
						$distributor_id = $query->row['distributor_id'];
					}
					
					// $user_id = $sponsor;
				} else {
					$valid = 0;
					$return_msg .= "Invalid sponsor.<br>";
				}
				$user_id = $sponsor;				
			} else {
				$valid = 0;
				$return_msg .= "Sponsor is mandatory.<br>";
			}
		}
		
		$num_length = strlen($data['contact']);
			if($num_length != 11) {
			$valid = 0;
			$return_msg .= "Please check your Contact Number<br>";
		
		}
		if($data['package'] == 1) {
			if(isset($data['username'])){
				if(empty($data['username'])) {
					$valid = 0;
					$return_msg .= "Username is mandatory.<br>";
				}	
				
				$sql = "select count(1) total from oc_user where lower(username) = '".strtolower($data['username'])."' ";
				$query = $this->db->query($sql);
				$totalusername = $query->row['total'];
				
				if($totalusername == 1) {
					$valid = 0;
					$return_msg .= "Username already exists.<br>";
				}
			}
			
			if(empty($data['password'])) {
				$valid = 0;
				$return_msg .= "Password is mandatory.<br>";
			}	
		} 
			
		
		if(isset($data['checkout_barangay'])) {
			if(empty($data['checkout_barangay'])) {
				$valid = 0;
				$return_msg .= "Barangay is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Barangay is mandatory.<br>";
		}
		
		if(isset($data['checkout_city'])) {
			if(empty($data['checkout_city'])) {
				$valid = 0;
				$return_msg .= "City is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "City is mandatory.<br>";
		}
		
		if(isset($data['address'])) {
			if(empty($data['address'])) {
				$valid = 0;
				$return_msg .= "Address is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Address is mandatory.<br>";
		}
		
		if(isset($data['contact'])) {
			if(empty($data['contact'])) {
				$valid = 0;
				$return_msg .= "Contact is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Contact is mandatory.<br>";
		}
		
		if(isset($data['payment_option'])) {
			if($data['payment_option'] == 157 or $data['payment_option'] == 158) {
				if($this->user->getUserGroupId() == 56 or $this->user->getUserGroupId() == 46) {
					if($data['payment_option'] == 157){
						$status_id = 127;
					} else if($data['payment_option'] == 158){
						$status_id = 138;	
					}
					
					$sql = "select ewallet,user_id
								  from oc_user
								  where user_id = ".$this->user->getId();		 
						$query = $this->db->query($sql);
						$ewallet_from = $query->row['ewallet'];
						$grand_total = $data['grand_total'];
							
						
						if($ewallet_from < $grand_total) {
							$valid = 0;
							$return_msg = "Insufficient Ewallet"; 
						}
				} else {
					$valid = 0;
					$return_msg = "Payment option via ewallet is only applicable for Distributor or Reseller."; 
				}
			}else if ($data['payment_option'] == 94) {
				if(empty($data['receiving_branch'])) {
					$valid = 0;
					$return_msg .= "Receiving Branch is mandatory.<br>";
				} else {
					$status_id = 138;
				}
			} else if($data['payment_option'] == 93 or $data['payment_option'] == 147) {
				$status_id = 127;
			} else {
				$status_id = 138;
			}
			
			
		} else {
			$valid = 0;
			$return_msg .= "Payment Option is mandatory.<br>";
		}
		
		$sql = "select count(1) count from oc_order_details where order_id = ".$data['order_id'];
		$query = $this->db->query($sql);
		$count = $query->row['count'];
		
		if($count == 0) {
			$valid = 0;
			$return_msg .= "Please add item/s to cart.<br>";
		}
			
		if($valid == 1) {
			
			$sql = "update oc_order 
				   set customer_name = '".strtoupper($this->db->escape($data['firstname']))." ".strtoupper($this->db->escape($data['lastname']))."'
					  ,firstname = '".strtoupper($this->db->escape($data['firstname']))."'
					  ,lastname = '".strtoupper($this->db->escape($data['lastname']))."'
					  ,province_id = ".$data['country_id']."
					  ,province_id = ".$data['checkout_provinces']."
					  ,city_municipality_id = ".$data['checkout_city']."
					  ,barangay_id = '".$data['checkout_barangay']."'
					  ,landmark = '".$this->db->escape($data['order_landmark'])."'
					  ,payment_option = '".$data['payment_option']."'
					  ,delivery_option = '".$data['delivery_option']."'
					  ,delivery_fee = '".$data['delivery_fee']."'
					  ,send_to = ".$data['send_to']."
					  ,distributor_id = ".$distributor_id."
					  ,sales_rep_id = ".$sales_rep_id."
					  ,company_admin_id = ".$company_admin_id."
					  ,user_id = ".$user_id."
					  ,amount = ".$data['grand_total']."
					  ,discount = '".$data['discount']."'
					  ,address = '".$this->db->escape($data['address'])."'
					  ,contact = '".$this->db->escape($data['contact'])."'
					  ,email = '".$this->db->escape($data['email'])."'
					  ,customer_flag = ".$customer."
					  ,status_id = ".$status_id."
					  ,encoded_date = '".$this->user->now()."'";
					  
					  
			if(isset($data['receiving_branch'])) {
				if(!empty($data['receiving_branch'])){
					$sql .= " ,receiving_branch = '".$this->db->escape($data['receiving_branch'])."' ";
				}
			}
			
			$sql .= " where order_id = ".$data['order_id'];
			
			//die($sql);
			$this->db->query($sql);
			
			$sql = "SELECT b.barangay_id, a.city_distributor_id, a.wholesaler_id FROM oc_barangays a
					LEFT JOIN oc_order b
					ON (b.barangay_id = a.barangay_id)
					WHERE b.order_id =	".$data['order_id'];
			$query = $this->db->query($sql);
			$city_distributor_id = $query->row['city_distributor_id'];
			$wholesaler_id = $query->row['wholesaler_id'];
			
			$sql = "update oc_order set city_distributor_id = ".$city_distributor_id.", 
					wholesaler_id = " .$wholesaler_id."
					where order_id = " .$data['order_id'];
			$this->db->query($sql);
			
			$sql = "update oc_user set current_order_id = 0 ";
			$this->db->query($sql);
			
			if(isset($data['payment_option'])){
				if($data['payment_option'] == 157 || $data['payment_option'] == 158) {	
						$sql = "select ewallet,user_id,user_group_id
						  from oc_user
						  where user_id = ".$this->user->getId();		 
						$query = $this->db->query($sql);
						$ewallet_from = $query->row['ewallet'];
						$order_from = $query->row['user_id'];
						$grand_total = $data['grand_total'];
						$user_group =  $query->row['user_group_id'];
						
					if($user_group == 56 || $user_group == 46) {
						$this->load->model('account/orders');
							
						$this->model_account_orders->deductEwallet($grand_total, $order_from, $order_from, $data['order_id'], 40, $this->user->getId());
					}
				}
			}
					
			$sql = "select a.status_id, b.description
						from oc_order a
					  left join gui_status_tbl b on(a.status_id = b.status_id)
					 where order_id = ".$data['order_id'];
			$query = $this->db->query($sql);
			$to_status_id = $query->row['status_id'];
			$remarks = $query->row['description'];
			
			$sql = "insert into oc_order_hist_tbl
						set order_id = ". $data['order_id'].",
							from_status_id = 0,
							to_status_id = ".$to_status_id.",
							remarks = '".$remarks."',
							date_added = '".$this->user->now()."' ";
			$this->db->query($sql);
			
			//kunin ang details ng order
			$sql = "select a.user_id,b.item_id,c.price * b.quantity amount,d.user_group_id,a.send_to
						  ,ROUND(c.distributor_discount_per / 100 * c.price, 2)discount_dist
						  ,ROUND(c.reseller_discount_per / 100 * c.price, 2)discount_res
					  from oc_order a
					  join oc_order_details b on(a.order_id = b.order_id)
					  join gui_items_tbl c on(b.item_id = c.item_id)
					  join oc_user d on(a.user_id = d.user_id)
					 where a.order_id =  ". $data['order_id'];
			$query = $this->db->query($sql);
			$encode_orders = $query->rows;
			// var_dump($encode_orders);
	
			foreach($encode_orders as $eo) {
				//if retail at promo
				if($eo['item_id'] == 16 or $eo['item_id'] == 20) {
					//if distributor	
					if($eo['user_group_id'] == 56 and $eo['send_to'] == 110) {
						//check if may record na sa sales encoded ang user na umorder
						$sql = "select count(1) total
							  from oc_sales_encoded 	
							 where user_id =". $eo['user_id']."
							 and item_id = ".$eo['item_id'];
						$query = $this->db->query($sql);
						$total_encoded = $query->row['total'];
					//kapag walang  sales encoded na nakarecord sa user na umorder
						if($total_encoded == 0)	{
							$sql ="insert into oc_sales_encoded
									  set  user_id = ". $eo['user_id']."
										  ,item_id = ". $eo['item_id']."
										  ,sales_today = ". $eo['amount']."
										  ,sales_week = ". $eo['amount']." 
										  ,sales_month = ". $eo['amount']."
										  ,sales_year = ". $eo['amount']." 
										  ,date_added ='".$this->user->now()."' ";
							$this->db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_encoded
									  set	sales_today = sales_today + ". $eo['amount']."
											,sales_week = sales_week + ". $eo['amount']." 
											,sales_month = sales_month + ". $eo['amount']." 
											,sales_year = sales_year + ". $eo['amount']."
											,date_added ='".$this->user->now()."' 
									where user_id =". $eo['user_id']."
									  and item_id = ". $eo['item_id'];
							$this->db->query($sql);
						}
					} else if($eo['user_group_id'] == 46 and $eo['send_to'] == 110) {//if reseller
						//check if may record na sa sales encoded ang user na umorder
						$sql = "select count(1) total
							  from oc_sales_encoded 	
							 where user_id =". $eo['user_id']."
							 and item_id = ".$eo['item_id'];
						$query = $this->db->query($sql);
						$total_encoded = $query->row['total'];
					//kapag walang  sales encoded na nakarecord sa user na umorder
						if($total_encoded == 0)	{
							$sql ="insert into oc_sales_encoded
									  set  user_id = ". $eo['user_id']."
										  ,item_id = ". $eo['item_id']."
										  ,sales_today = ". $eo['amount']." 
										  ,sales_week = ". $eo['amount']." 
										  ,sales_month = ". $eo['amount']."
										  ,sales_year = ". $eo['amount']." 
										  ,date_added ='".$this->user->now()."' ";
							$this->db->query($sql);
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_encoded
									  set	sales_today = sales_today + ". $eo['amount']." 
											,sales_week = sales_week + ". $eo['amount']." 
											,sales_month = sales_month + ". $eo['amount']."
											,sales_year = sales_year + ". $eo['amount']."
											,date_added ='".$this->user->now()."' 
									where user_id =". $eo['user_id']."
									  and item_id = ". $eo['item_id'];
							$this->db->query($sql);
						}
						
					} else {
						
					//check if may record na sa sales encoded ang user na umorder
						$sql = "select count(1) total
							  from oc_sales_encoded 	
							 where user_id =". $eo['user_id']."
							 and item_id = ".$eo['item_id'];
						$query = $this->db->query($sql);
						$total_encoded = $query->row['total'];
						// echo $sql .'select<br>';
					//kapag walang  sales encoded na nakarecord sa user na umorder
						if($total_encoded == 0)	{
							$sql ="insert into oc_sales_encoded
									  set  user_id = ". $eo['user_id']."
										  ,item_id = ". $eo['item_id']."
										  ,sales_today = ". $eo['amount']."
										  ,sales_week = ". $eo['amount']."
										  ,sales_month = ". $eo['amount']."
										  ,sales_year = ". $eo['amount']."
										  ,date_added ='".$this->user->now()."' ";
							$this->db->query($sql);
							// echo $sql .'naginsert<br>';
						} else {//if meron nangnakarecord sa sales encoded na user
							$sql ="update oc_sales_encoded
									  set	sales_today = sales_today + ". $eo['amount']."
											,sales_week = sales_week + ". $eo['amount']."
											,sales_month = sales_month + ". $eo['amount']."
											,sales_year = sales_year + ". $eo['amount']."
											,date_added ='".$this->user->now()."' 
									where user_id =". $eo['user_id']."
									  and item_id = ". $eo['item_id'];
							$this->db->query($sql);
							
							// echo $sql .'nagupadte<br>';
							
						}
					}
				//if package 
				} else {
					$sql = "select count(1) total
						  from oc_sales_encoded 	
						 where user_id =". $eo['user_id']."
						 and item_id = ".$eo['item_id'];
					$query = $this->db->query($sql);
					$total_encoded = $query->row['total'];
					
					// echo $sql .'select<br>';
				//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_encoded == 0)	{
						$sql ="insert into oc_sales_encoded
								  set  user_id = ". $eo['user_id']."
									  ,item_id = ". $eo['item_id']."
									  ,sales_today = ". $eo['amount']."
									  ,sales_week = ". $eo['amount']."
									  ,sales_month = ". $eo['amount']."
									  ,sales_year = ". $eo['amount']."
									  ,date_added ='".$this->user->now()."' ";
						$this->db->query($sql);
						// echo $sql .'naginsert<br>';
					} else {//if meron nangnakarecord sa sales encoded na user
						$sql ="update oc_sales_encoded
								  set	sales_today = sales_today + ". $eo['amount']."
										,sales_week = sales_week + ". $eo['amount']."
										,sales_month = sales_month + ". $eo['amount']."
										,sales_year = sales_year + ". $eo['amount']."
										,date_added ='".$this->user->now()."' 
								where user_id =". $eo['user_id']."
								  and item_id = ". $eo['item_id'];
						$this->db->query($sql);
						// echo $sql .'nagupadte<br>';
					}
				} 
			}			
			$sql = "select branch_id from oc_barangays where barangay_id = ".$data['checkout_barangay'] ;
			$query = $this->db->query($sql);
			$branch_id = $query->row['branch_id'];
			
			if($branch_id == 0){
				$sql = "update oc_order 
						  set branch_id = 2
					  where order_id = ".$data['order_id'];
				$query = $this->db->query($sql);
				
				// $order_id = $data['order_id'];
			}
			
			if($data['payment_option'] == 94 || $data['payment_option'] == 107){
				$sql = "update oc_order 
						  set branch_id = 18
					  where order_id = ".$data['order_id'];
				$query = $this->db->query($sql);
			}
			
			$this->session->data['order_id'] = 0;
			
			if(isset($data['username'])) {
				$sql = "update oc_order 
				   set username = '".$data['username']."'
				 where order_id = ".$data['order_id'];
				 $this->db->query($sql);
			}
			
			if(isset($data['password'])) {
				$sql = "update oc_order 
				   set password = '".$data['password']."'
				 where order_id = ".$data['order_id'];
				 $this->db->query($sql);
			}
			
			$sql = "select ref 
					  from oc_order 
					 where order_id = ".$data['order_id'];
			$query = $this->db->query($sql);
			$reference = $query->row['ref'];
			
			$order_result['reference'] = $reference;
			$order_result['msg'] = "Order Successful";
			$order_result['order_id'] = $data['order_id'];
		
		} else {
			$order_result['msg'] = "Order Failed : <br>".$return_msg;
			$order_result['order_id'] = 0;
		}
		
		return $order_result;

	}
	
	public function getProvinces() {
		$sql = "select province_id, description from oc_provinces order by description asc";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getCitiesNewCustomer($data){
		$sql = "select * from oc_city_municipality where province_id = " . $data['province_id'];
		$result = $this->db->query($sql);

		return $result->rows;
	}
	
	public function getBrgyNewCustomer($data){
		$sql = "select * from oc_barangays where city_municipality_id = " . $data['city_id'] ." order by description asc";
		$result = $this->db->query($sql);

		return $result->rows;
	}
	
	public function getPaymentOptions($grouping2 = ""){
		$sql = "select status_id,description 
				  from gui_status_tbl 
				 where `grouping` = 'PAYMENT OPTION' ";
				 
		if($grouping2 != "") {
			$sql .= " and upper(grouping2) = '".strtoupper($grouping2)."' ";
		}
		
		$sql .= " order by status_id ";
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getPaymentOptions1($data){
		$sql = "select branch_id , city_distributor_id, within_city from oc_barangays where barangay_id = ".$data['brgy_id'] ;
		$query = $this->db->query($sql);
		$branch_id = $query->row['branch_id'];
		$city_distributor_id = $query->row['city_distributor_id'];
		$within_city = $query->row['within_city'];
		$user_id = $this->user->getId();
		
		if($city_distributor_id > 0 && $user_id > 0) {		
			$sql = "select status_id,description 
					  from gui_status_tbl 
					 where `grouping` = 'PAYMENT OPTION'
					   and `grouping`2 = 'EDSI'
					   and within_city in(3,2,".$within_city.")
					   and active = 1 ";
		} else if($city_distributor_id > 0) {		
			$sql = "select status_id,description 
					  from gui_status_tbl 
					 where `grouping` = 'PAYMENT OPTION'
					   and `grouping`2 = 'EDSI'
					   and within_city in(2,".$within_city.")
					   and active = 1 ";
		} else {
			$sql = "select status_id,description 
					  from gui_status_tbl 
					 where `grouping` = 'PAYMENT OPTION' 
					   and `grouping`2 in ('DROPSHIP')
					   and active = 1 ";
		}
		
		$sql .= " order by status_id ";
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getdeliveryOptions($grouping2 = ""){
		$sql = "select status_id, description 
				  from gui_status_tbl 
				 where `grouping` = 'DELIVERY OPTION'";
				 
		if($grouping2 != "") {
			$sql .= " and upper(grouping2) = '".strtoupper($grouping2)."' ";
		}
		
		$sql .= " order by status_id ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function removeItemFromCart($data) {
		// var_dump($data);
		$sql = "delete from oc_order_details where order_det_id = ".$data['order_det_id'];
		$this->db->query($sql);
		$this->summarizeOrder($data);
		return "Item removed.";
	}
	
	public function cancelOrder($data) {
		$sql = "select user_id,status_id,payment_option,amount 
			      from oc_order 
				 where order_id = ".$data['order_id'];
		$query = $this->db->query($sql);
		$status_id = $query->row['status_id'];
		$refund_amount = $query->row['amount'];
		$user_refunded = $query->row['user_id'];
		$payment_option = $query->row['payment_option'];
		
		
		$sql = "update oc_order set status_id = 19 where order_id = ".$data['order_id'];
		$this->db->query($sql);
		
		$order_id = $data['order_id'];
		
		$sql = "insert into oc_order_hist_tbl
					set order_id = ".$order_id.",
						from_status_id = ".$status_id.",
						to_status_id = 113,
						remarks = 'Cancelled',
						date_added = '".$this->user->now()."' ";
		$this->db->query($sql);
		
		if($payment_option == 158 or $payment_option == 157){ //COP PAYMENT_OPTION or COD PAYMENT OPTION
		$this->load->model('account/orders');
		$this->model_account_orders->insertEwallet($refund_amount, $user_refunded, $user_refunded, $order_id, 41, $this->user->getId(), 0);
		// insertEwallet($income, $user_id, $source_user_id, $order_id, $commission_type_id, $created_by, $tax_flag = 0) 
		}
		
		return "Order cancelled.";
	}
	
	public function getDeliveryFee($order_id, $payment_option, $brgy_id) {
		$delivery_fee = 0;
		$points = $this->getPoints($order_id);	
		$within_city = $this->getWithinCity($brgy_id);
		
		if($within_city['island'] == 1){
			$sql = "select rate delivery_fee 
					  from oc_shipping_rates" ;
					  
					  if($payment_option == 158 and $within_city['within_city'] > 0){
						  $sql .= " where payment_option = 146 
									and quantity = ".ceil($points)."
									and area = 1 ";
					  } else if($payment_option == 158) {
							$sql .= " where payment_option = 148
									and quantity = ".ceil($points);
					  } else {
							$sql .= " where payment_option = ".$payment_option." 
									and quantity = ".ceil($points);
					  }
			$query = $this->db->query($sql);
			$delivery_fee = isset($query->row['delivery_fee']) ? $query->row['delivery_fee'] : 0;
			
		}	else {
			
			$sql = "select rate delivery_fee 
					  from oc_shipping_rates" ;
					  
					  if($payment_option == 158 and $within_city['within_city'] > 0){
						  $sql .= " where payment_option = 146 
									and quantity = ".ceil($points);
					  } else if($payment_option == 158) {
							$sql .= " where payment_option = 148
									and quantity = ".ceil($points);
					  } else {
							$sql .= " where payment_option = ".$payment_option." 
									and quantity = ".ceil($points);
					  }
			$query = $this->db->query($sql);
			$delivery_fee = isset($query->row['delivery_fee']) ? $query->row['delivery_fee'] : 0;
		}
		
		if(isset($delivery_fee)) {
			if($delivery_fee > 0) {
				$sql = "select total from oc_order where order_id = ".$order_id;		 
				$query = $this->db->query($sql);
				$total_quantity = $query->row['total'];
				
				if($total_quantity >= 30){
					$delivery_fee = 0;
				}
			} else {
				$delivery_fee = 0;
			}
		} 
		
		return $delivery_fee;
	}
	
	public function getWithinCity($brgy_id) {
		$sql = "select branch_id , city_distributor_id, within_city,island from oc_barangays where barangay_id = ".$brgy_id;
		$query = $this->db->query($sql);
		return $query->row;
	}
	
}
?>