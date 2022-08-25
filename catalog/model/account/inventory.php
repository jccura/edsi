<?php
class ModelAccountInventory extends Model {
	
	public function getBranches() {
		$sql  = "select branch_id, description from gui_branch_tbl where status = 1 order by description ";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getProductList($data, $query_type = "data") {
		
		$sql = " select a.qty, b.price, b.description, b.item_name, b.item_id,a.user_id
				   from oc_inventory a 
				   join gui_items_tbl b on(a.item_id = b.item_id) 
				  where 1 = 1 and a.user_id = ".$this->user->getId() ;
				  
			// if(isset($data['branch_id'])) {
				// if(!empty($data['branch_id'])) {
					// $sql .= " and a.branch_id = ".$data['branch_id'];
				// }
			// }
			
			if(isset($data['user_id'])) {
				if(!empty($data['user_id'])) {
					$sql .= " and a.user_id = ".$data['user_id'];
				}
			}
			
			if(isset($data['item_id'])) {
				if(!empty($data['item_id'])) {
					$sql .= " and a.item_id = ".$data['item_id'];
				}
			}
			
			if(isset($data['promo_item_id2'])) {
				if(!empty($data['promo_item_id2'])){
					$sql .= " and a.item_id = ".$data['promo_item_id2'] ;
				}
			}
			
			if(isset($data['promo_item_id'])) {
				if(!empty($data['promo_item_id'])){
					$sql .= " and a.item_id = ".$data['promo_item_id'] ;
				}
			}
				 				 
		if($query_type == "data") {
			
			$sql .= " order by a.user_id, b.description asc ";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 6;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			// echo $sql."<br>"; 
			$result = $this->db->query($sql);
			return $result->rows;
		} else {
			$sqlt = "select count(1) total from (".$sql.") t";
			$result = $this->db->query($sqlt);
			return $result->row['total'];			
		}		

	} 	
	
	public function getOrders($orderId){
		$ordersSql = "SELECT * FROM oc_order_details WHERE order_header_id=".$orderId;
		return $this->db->query($ordersSql)->rows;
	}

	public function updateInventory($orderId,$transactionType = "add"){
		$orders = $this->getOrders($orderId);
		
		foreach($orders as $order){
			$inventoryId = 0;
			$inventoryIdSql = "SELECT inventory_id FROM oc_inventory WHERE branch_id=".$this->user->getBranchId()." AND item_id=".$order['item_id'];
			$inventoryIdQuery = $this->db->query($inventoryIdSql);
			$inventoryId = $inventoryIdQuery->row['inventory_id'];
			
			$updateInvSql = "UPDATE oc_inventory ";
			$insertInvHistorySql = "INSERT INTO oc_inventory_history_tbl ";
			
			if($transactionType == "add") {
				$updateInvSql .= "SET quantity = quantity +".$order['quantity'];
				$insertInvHistorySql .= "SET re_stock =  ".$order['quantity'];
			}else{
				$updateInvSql .= "SET quantity = quantity -".$order['quantity'];
				$insertInvHistorySql .= "SET sold =  ".$order['quantity'];
			}
			$updateInvSql .= ", date_modified='".$this->user->now()."'";
			$updateInvSql .= ", modified_by=".$this->user->getId()." ";
			$updateInvSql .= "WHERE branch_id=".$this->user->getBranchId()." ";
			$updateInvSql .= "AND item_id=".$order['item_id'];
			
			$this->db->query($updateInvSql);
			
			$insertInvHistorySql .= ", user_id= ".$this->user->getId()." ";
			$insertInvHistorySql .= ", item_id= ".$order['item_id'];
			$insertInvHistorySql .= ", date_added='".$this->user->now()."'";
			$insertInvHistorySql .= ", inventory_id=".$inventoryId;
			$insertInvHistorySql .= ", branch_id=".$this->user->getBranchId()." ";
			$this->db->query($insertInvHistorySql);
		}	
		
		return "Successful inventory update.";
	}

	public function getOrderHistorySummary(){
		$sql = "select order_id, date_added, quantity from oc_order where user_id = " . $this->user->getId();
		$result = $this->db->query($sql);

		return $result->rows;
	}

	public function getCodProvinces(){
		$sql = "select area_id,description from oc_cod_provinces";
		$result = $this->db->query($sql);

		return $result->rows;
	}

	public function getCodCities($id){
		$sql = "select city from oc_cod_areas where province = " . $id;
		$result = $this->db->query($sql);

		return $result->rows;
	}

	public function getCodShippingRates(){
		$sql = "select quantity,price_rate from oc_cod_shipping_rates";
		$result = $this->db->query($sql);

		return $result->rows;
	}

	public function getThirdPartyShippingRates(){
		$sql = "select quantity,lbc_rate,jrs_rate from oc_third_party_shipping_rates";
		$result = $this->db->query($sql);

		return $result->rows;
	}

	public function getItemHistoryTotal($data) {
		//get the inventoryhist of a user
		$sql = "SELECT SUM(a.re_stock) re_stock,SUM(a.sold) sold,a.item_id,a.user_id,b.item_name
		              ,SUM(a.assembled_from) assembled,SUM(a.packed) total_packed
					  ,SUM(a.assembled_from + a.packed) total_stock
					  ,SUM(a.returned) total_returned
					FROM oc_inventory_history_tbl  a
					INNER JOIN gui_items_tbl b on a.item_id = b.item_id
					WHERE a.item_id = ".$data['item_id']." AND a.user_id= ".$this->user->getId();
			$result = $this->db->query($sql);
			return $result->row;
		
		
		
		// if($this->user->getUserGroupId() == 44) {
			// $sql = "SELECT SUM(a.re_stock) re_stock,SUM(a.sold) sold,a.item_id,a.user_id,b.description
					// FROM oc_inventory_history_tbl  a
					// INNER JOIN gui_items_tbl b on a.item_id = b.item_id
					// WHERE a.item_id = ".$data['item_id']." AND a.user_id= ".$this->user->getId();
				
			// $result = $this->db->query($sql);
			
			// return $result->row;
		// } else if ($this->user->getUserGroupId() == 39 || $this->user->getUserGroupId() == 43){
			// $sql = "SELECT SUM(a.re_stock) re_stock,SUM(a.sold) sold, SUM(a.re_stock - a.assembled_from - a.sold) total,a.item_id,b.item_name
					// FROM oc_inventory_history_tbl  a
					// INNER JOIN gui_items_tbl b on a.item_id = b.item_id
					// WHERE a.item_id = ".$data['item_id']." AND a.user_id= ".$this->user->getId();
			// $result = $this->db->query($sql);
			// return $result->row;
		// }else if ($this->user->getUserGroupId() == 49){
			// $sql = "SELECT SUM(a.re_stock) re_stock,SUM(a.sold) sold,a.item_id,b.description
					// FROM oc_inventory_history_tbl  a
					// INNER JOIN gui_items_tbl b on a.item_id = b.item_id
					// WHERE a.item_id = ".$data['item_id']." AND a.user_id= ".$this->user->getId();
			// $result = $this->db->query($sql);
			// return $result->row;
		// }
	}
	
	public function getItemHistory($data,$query_type){
		$sql = "SELECT a.inventory_history_id,a.re_stock , a.sold,a.item_id,
				CASE WHEN a.recieved_by is not null then concat(f.firstname, ' ',f.lastname)
				ELSE concat(f.firstname, ' ', f.lastname) END 'received_by',
				a.assembled_from, a.assembled_to, a.returned, a.packed,
				b.description, a.date_added, e.description status,
				CASE WHEN c.description is not null then c.description 
				ELSE concat(d.firstname, ' ', d.lastname) END 'affiliatedBranch',
				a.order_id, a.remarks, b.item_name, a.load_inventory_id
				FROM oc_inventory_history_tbl  a
				INNER JOIN gui_items_tbl b on (a.item_id = b.item_id)
				LEFT JOIN gui_branch_tbl c on (a.branch_affiliated = c.branch_id)
				LEFT JOIN oc_user f on (a.recieved_by = f.user_id)
				LEFT JOIN oc_user d on (a.user_id = d.user_id)
				LEFT JOIN gui_status_tbl e on(a.status = e.status_id)
				WHERE a.item_id = ".$data['item_id']." ";
			
		if($this->user->getUserGroupId()== 66 ) {
			if(isset($data['branch_id'])) {
				if(!empty($data['branch_id'])) {
					$sql .= " and a.branch_id = ".$data['branch_id'];
				}
			} 
		} else {
			$sql .= " AND a.user_id=".$this->user->getId();
		}

		if($query_type == "data") {
			$sql .= " ORDER BY a.inventory_history_id desc ";

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 6;
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
	
	public function getItemMovementHist($data,$query_type){
		$sql = "SELECT a.history_id,a.re_stock , a.sold,a.item_id,b.description,a.date_added, e.description status,
				CASE WHEN c.description is not null then c.description 
				     ELSE concat(d.firstname, ' ', d.lastname) END 'affiliatedBranch',
				a.order_id, a.remarks
				FROM oc_inventory_history_tbl  a
				INNER JOIN gui_items_tbl b on a.item_id = b.item_id
				LEFT JOIN gui_branch_tbl c on a.branch_affiliated = c.branch_id
				LEFT JOIN oc_user d on a.user_id = d.user_id
				LEFT JOIN gui_status_tbl e on(a.status = e.status_id)
				WHERE a.branch_id=".$this->user->getBranchId();
				
			if(isset($data['item_id'])) {
				if(!empty($data['item_id'])) {
					$sql .= " and a.item_id = ".$data['item_id'];
				}
			} 
			
			if(isset($data['item_id'])) {
				if(!empty($data['order_id'])) {
					$sql .= " and a.order_id = ".$data['order_id'];
				}
			} 
			

		if($query_type == "data") {
			$sql .= " ORDER BY a.history_id desc ";

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 6;
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
	
	public function getNonRawItems() {
		$sql = "select item_id, description, price, category_id 
		          from gui_items_tbl 
				 where active = 1 and raw = 0 ";
		$result = $this->db->query($sql);
		return $result->rows;				 
	}
	
	public function getActiveItems() {
		$sql = "select item_id, description, price, category_id 
		          from gui_items_tbl 
				 where active = 1 ";
		$result = $this->db->query($sql);
		return $result->rows;				 
	}
	
	public function getItem($item_id) {
		$sql  = "select a.*, b.description as category
                   from gui_items_tbl a
				   left join gui_category_tbl b on (a.category_id = b.category_id)
				 where a.item_id = " .$item_id;

		$query = $this->db->query($sql);
		return $query->row;	
	}

	public function getMySerials($data, $query_type) {
		$sql = "select a.serial_code, b.item_name, a.order_id, a.date_added, a.date_used,
					   concat(c.firstname, ' ', c.lastname, ' (', c.username, ')') used_by,
					   concat(d.firstname, ' ', d.lastname, ' (', d.username, ')') ordered_thru
				from oc_serials a
				join gui_items_tbl b on(a.item_id = b.item_id)
				left join oc_user c on(a.user_id = c.user_id)
				left join oc_user d on(a.order_user_id = d.user_id)
				where a.order_user_id = ".$this->user->getId();
		if($query_type == "data") {
			$sql .= " order by a.serial_id desc ";
			$query = $this->db->query($sql);
			return $query->rows;
		} else {
			$sqlt = "select count(1) total from (".$sql.") t ";
			$query = $this->db->query($sqlt);
			return $query->row['total'];
		}
	}
}
?>