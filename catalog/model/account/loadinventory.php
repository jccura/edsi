<?php
class ModelAccountLoadInventory extends Model {
	
	public function updateMerchant($data){
		$sql = "UPDATE oc_load_inventory SET merchant_id=".$data['merchant_id']." WHERE load_inventory_id=".$data['load_inventory_id'];
		$this->db->query($sql);
	}
	
	public function getMerchants(){
		$sql = "SELECT * FROM oc_merchant";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function addInventory($data) {
		if(isset($data['product_id_sel'])) {
			$sql = "select count(1) cnt from oc_load_inventory_details where load_inventory_id = ".$data['load_inventory_id']." and item_id = ".$data['product_id_sel'];
			$query = $this->db->query($sql);
			$count = $query->row['cnt'];
			if($count == 1){
				if($data['product_id_sel'] != "0") {
					$sql  = "select qty from oc_load_inventory_details where load_inventory_id = ".$data['load_inventory_id']." and item_id = ".$data['product_id_sel'];
					$query = $this->db->query($sql);
					$qty = $query->row['qty'];
					$sql  = "UPDATE oc_load_inventory_details ";
					$sql .= "   SET qty = ".($qty + $data['quantity_sel']);
					$sql .= "      ,date_added = '".$this->user->now()."' ";
					$sql .= "   where load_inventory_id = ".$data['load_inventory_id']." and item_id = ".$data['product_id_sel'];
					$this->db->query($sql);
					$this->computeInventory($data['load_inventory_id'],$data['product_id_sel'],$data['load_inventory_detail_id']);
				}
			}
			else{
				if($data['product_id_sel'] != "0") {
					$sql  = "INSERT INTO oc_load_inventory_details ";
					$sql .= "   SET load_inventory_id = ".$data['load_inventory_id']." ";
					$sql .= "      ,item_id = ".$data['product_id_sel']." ";
					$sql .= "      ,qty = ".$data['quantity_sel']." ";
					$sql .= "      ,date_added = '".$this->user->now()."' ";
					$this->db->query($sql);
					$this->computeInventory($data['load_inventory_id'],$data['product_id_sel'],$data['load_inventory_detail_id']);
				}
			}
		}

		return "Successful addition to Inventory.";
	} 	
	
	public function deleteInventoryItem($data) {
		// var_dump($data);
		if(isset($data['load_inventory_detail_id'])) {
			if($data['load_inventory_detail_id'] != "0") {
				$this->computeInventory($data['load_inventory_id'],$data['product_id_sel'],$data['load_inventory_detail_id']);
				$sql = "delete from oc_load_inventory_details where load_inventory_detail_id = ".$data['load_inventory_detail_id'];
				$this->db->query($sql);
				return "Successful delete of Item.";
			}
		}
		return "Error in deletion of item.";
	}
	
	public function computeInventory($load_inventory_id, $product_id_sel, $load_inventory_detail_id) {
		// var_dump($load_inventory_id)."<br>";
		// var_dump($product_id_sel);
		$sql = "select coalesce(sum(b.price * a.qty), 0) cost, coalesce(sum(a.qty),0) quantity, b.price, a.qty
				  from oc_load_inventory_details a
				  join gui_items_tbl b on(a.item_id = b.item_id)
				 where a.load_inventory_id = ".$load_inventory_id."
					and a.item_id = ".$product_id_sel;
		// echo $sql."<br>";
		$query = $this->db->query($sql);
		
		$qty = $query->row['qty'];
		$price = $query->row['price'];
		$quantity = $query->row['quantity'];

		$sql = "update oc_load_inventory_details set amount = ".$price.", qty = ".$quantity." 
					where load_inventory_id = ".$load_inventory_id."
					and item_id = ".$product_id_sel;
		// echo $sql."<br>";
		$this->db->query($sql);	

		if($this->request->post['task'] == "deleteItem"){
			$sql = "select coalesce(sum(b.price * a.qty), 0) cost, coalesce(sum(a.qty),0) quantity, b.price, a.qty
				  from oc_load_inventory_details a
				  join gui_items_tbl b on(a.item_id = b.item_id)
				 where a.load_inventory_id = ".$load_inventory_id." and a.load_inventory_detail_id = ".$load_inventory_detail_id;
			// echo $sql."<br>";
			$query = $this->db->query($sql);
			$cost = $query->row['cost'];
			$quantity = $query->row['quantity'];
			
			$sql = "update oc_load_inventory set total_amount = (total_amount - ".$cost."), total_qty = (total_qty - ".$quantity.")
						where load_inventory_id = ".$load_inventory_id;
			// echo $sql."<br>";
			$this->db->query($sql);		
		} else {
			$sql = "select coalesce(sum(b.price * a.qty), 0) cost, coalesce(sum(a.qty),0) quantity, b.price, a.qty
					  from oc_load_inventory_details a
					  join gui_items_tbl b on(a.item_id = b.item_id)
					 where a.load_inventory_id = ".$load_inventory_id;
			// echo $sql."<br>";
			$query = $this->db->query($sql);
			
			$cost = $query->row['cost'];
			$quantity = $query->row['quantity'];
			
			$sql = "update oc_load_inventory set total_amount =  ".$cost.", total_qty = ".$quantity."
						where load_inventory_id = ".$load_inventory_id;
			// echo $sql."<br>";
			$this->db->query($sql);
		}
						
	}
	
	public function getInventoryDetails($load_inventory_id) {
		$sql = "select a.load_inventory_id, a.total_amount, a.total_qty, a.status, b.description stats, c.description
				  from oc_load_inventory a
				  left join gui_status_tbl b on (b.status_id = a.status)
				  left join gui_branch_tbl c on (a.branch_id = c.branch_id)
				 where a.load_inventory_id = ".$load_inventory_id;
				 
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getActiveProducts() {
		$sql = " select item_id, description, item_name, cost from gui_items_tbl 
				  where active = 1 
				  order by item_name  ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
		
	public function getLoadInventoryList($data = array(), $query_type = "data") {
		$sql = "select a.load_inventory_id, 
					a.total_amount, a.total_qty, a.date_added, a.status, a.user_id, 
					lower(c.username) created_by, d.description status_desc
					from oc_load_inventory a
					left join oc_user c on (a.user_id = c.user_id) 				
					left join gui_status_tbl d on (a.status = d.status_id)
				where a.user_id = ".$this->user->getId();			 
				
		if($query_type == "data") {
			$sql .= " order by a.load_inventory_id desc ";
			$query = $this->db->query($sql);
			return $query->rows;			
		} else {
			$sqlt = "select count(1) total from (".$sql.") t ";
			$query = $this->db->query($sqlt);
			return $query->row['total'];			
		}
		// echo $this->user->getId()."</br>";	
	} 
	
	public function getInventoryItems($data = array(), $query_type = "data") {
		$sql = "select a.load_inventory_id, a.load_inventory_detail_id, a.item_id, a.qty total_qty, b.item_name item, 
					b.price * a.qty cost, c.description category,  a.date_added
				  from oc_load_inventory_details a 
				  left join gui_items_tbl b on(a.item_id = b.item_id)
				  left join gui_category_tbl c on(b.category_id = c.category_id)
				 where a.load_inventory_id = ".$data['load_inventory_id'];	
		
		if($query_type == "data") {
			$query = $this->db->query($sql);
			return $query->rows;			
		} else {
			$sqlt = "select count(1) total from (".$sql.") t ";
			$query = $this->db->query($sqlt);
			return $query->row['total'];			
		}
	} 	

	public function createInventory($data) {
		// echo "createInventory" ;
		
		$result = array();

		$result['status_msg'] = "Cannot Create new Inventory";	
		
		$sql = "select user_id from oc_user where user_group_id = ".$this->user->getUserGroupId();
		$query = $this->db->query($sql);
		$invofficer_id = $query->row['user_id'];
		
		try {
			$sql  = "INSERT INTO oc_load_inventory ";
			$sql .= "   SET user_id = ".$invofficer_id;
			$sql .= "   ,date_added = '".$this->user->now()."' ";
			$sql .= "   ,status = 114 ";
			// echo $sql."<br>";
			$this->db->query($sql);
			
			$purchaseId = $this->db->getLastId();
			$sql = "UPDATE oc_load_inventory SET inventory_reference = '".$this->db->escape(sha1("edsipurchase".$purchaseId))."' where load_inventory_id = ".$purchaseId;
			$this->db->query($sql);
			
			$result['load_inventory_id'] = $purchaseId;
			$result['status'] = "success";
			$result['status_msg'] = "You have successfully created Inventory# ".$result['load_inventory_id'].". You may now add items to it.";			
		} catch (Exception $e) {
			$result['load_inventory_id'] = 0;
			$result['status'] = "failed";
			$result['status_msg'] = "Error in Creating Inventory.";			
		}		
	
		return $result;
	}
	
	// public function validate($data) {

		// $sql = "select count(1) count from oc_purchase where session = '".$data['session_id']."'";
		// $query = $this->db->query($sql);
		// $result = $query->row['count'];

		// return $result;
	// }
	
	// public function addRemark($data){

		// $sql = "select purchase_id from oc_purchase where purchase_reference = '" . $data['reference'] . "'";
		// $query = $this->db->query($sql);
		// $header_id = $query->row['purchase_id'];

		// $sql = "INSERT into oc_purchase_comments SET user_id = " . $this->user->getId() . ", purchase_id = ". $header_id .", remark = '". $data['remark'] ."', date_added = '". $this->user->now() ."'";
		// $query = $this->db->query($sql);

	// }
	
	public function getItems($data) {
		$sql = "select item_code, item_id, item_name, raw description, price 'cost' from gui_items_tbl 
				 where raw = 1 ";
		if(isset($data['category_id'])) {
			if($data['category_id'] != "") {
				$sql .= "and category_id = ".$data['category_id']; 
			}			
		}

		$sql .= " order by item_name ";
		$query = $this->db->query($sql);
		return $query->rows;
	}	
	
	// public function getItemsWithInventory($data) {
		// $sql = "select a.barcode, a.item_id, a.description, a.cost from gui_items_tbl a
				 // join gui_inventory_tbl b on (b.item_id = a.item_id)
				 // where b.quantity > 0";
		// if(isset($data['category_id'])) {
			// if($data['category_id'] != "") {
				// $sql .= "and a.category_id = ".$data['category_id']; 
			// }			
		// }

		// $sql .= " order by description ";
		// $query = $this->db->query($sql);
		// return $query->rows;
	// }	
	
	public function approveInventory($data) {
		if(isset($data['load_inventory_id'])) {
			$sql = "select status from oc_load_inventory where status = 114 and load_inventory_id = ".$data['load_inventory_id'];
			
			$query = $this->db->query($sql);
			$status = 0;
			if(isset($query->row['status'])) {
				$status = $query->row['status'];
				
				if($status == 114){
					
					$sql = "select a.load_inventory_id, b.load_inventory_detail_id, a.user_id, 
									a.total_amount, a.total_qty, a.status, a.date_added,
									b.item_id, b.amount, b.qty, b.date_added date_det_added
								from oc_load_inventory a
								join oc_load_inventory_details b on (a.load_inventory_id = b.load_inventory_id)
								where a.load_inventory_id = ".$data['load_inventory_id'];
								
					$query = $this->db->query($sql);
					$details = $query->rows;
					
					foreach ($details as $detail) {
						
						$sql = "select a.load_inventory_id, b.load_inventory_detail_id, a.user_id, 
									a.total_amount, a.total_qty, a.status, a.date_added,
									b.item_id, b.amount, b.qty, b.date_added date_det_added
								 from oc_load_inventory a
								 join oc_load_inventory_details b on (a.load_inventory_id = b.load_inventory_id)
								where item_id = " .$detail['item_id']. " 
								  and a.load_inventory_id = ".$data['load_inventory_id']."
								  and a.user_id = ".$this->user->getId();
						
						$query = $this->db->query($sql);
						// $inventory = $query->row['total_qty'];
	 
						$inventoryId = 0;
						
						$sql = "SELECT count(1) total 
								  FROM oc_inventory 
								 where user_id = ".$this->user->getId()."
								   and item_id = ".$detail['item_id'];
								   
						$query = $this->db->query($sql);
						$total = $query->row['total'];
						
						if ($total == 0) {
							$sql = "INSERT INTO oc_inventory 
										set qty = ".$detail['qty'].", 
											modified_by='".$this->user->getId()."', 
											item_id = ".$detail['item_id'].", 
											date_added='".$this->user->now()."',
											user_id = ".$this->user->getId();										
							$this->db->query($sql);						
						} else {							
							$sql = "UPDATE oc_inventory
									   set qty = qty + ".$detail['qty'].",
										   modified_by='".$this->user->getId()."',
										   date_added='".$this->user->now()."'
									 where user_id = ".$this->user->getId()."
								       and item_id = ".$detail['item_id'];
									   
							$this->db->query($sql);				
						}
					
						$inventoryId = $this->db->getLastId();
													
						$sql = "INSERT INTO oc_inventory_history_tbl
									SET user_id = '".$this->user->getId()."',
										item_id = ".$detail['item_id'].",
										date_added = '".$this->user->now()."',
										re_stock = ".$detail['qty'].",
										sold = 0,
										inventory_id = ".$inventoryId.",
										status = 115, 
										load_inventory_id = ".$data['load_inventory_id'];
										
						$this->db->query($sql);

						$sql = "update oc_load_inventory 
								   set status = 115 
								 where load_inventory_id = ".$data['load_inventory_id'];
						$this->db->query($sql);
					}
				}				
			} else {
				return "Failed, the Inventory is not available.";
			}

		}
		return "Successful approval of inventory.";
	}
	
	public function cancelInventory($data){
		$sql = "UPDATE oc_load_inventory
					SET status = 116
				WHERE load_inventory_id = ".$data['load_inventory_id'];
				
		$this->db->query($sql);
		
		return "Successful cancel of inventory.";
	}
	
	public function getCategories() {
		$sql  = "select category_id, description, active
                   from gui_category_tbl
				  where active = 1 
				  order by description";

		$query = $this->db->query($sql);
		return $query->rows;			
	}
}
?>