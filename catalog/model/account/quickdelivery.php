<?php
class ModelAccountQuickDelivery extends Model {
	
	public function getQuickDeliveryDetails($data, $query_type = "data") {
	
		$sql = "select a.quick_deliveries_id, concat(b.firstname, '',b.lastname) user, 
					   concat(c.firstname, '',c.lastname) rider, d.description 'stats', 
					   e.description 'method', f.description 'area', a.date_added, 
					   MAX(g.date_added) modified_date
				  from oc_quick_delivery a
				  left join oc_user b on (a.user_id = b.user_id)
				  left join oc_user c on (a.rider_id = c.user_id)
				  left join gui_status_tbl d on (a.status = d.status_id)
				  left join gui_status_tbl e on (a.payment_method = e.status_id)
				  left join oc_area f on (a.area_id = f.area_id)
				  left join oc_quick_delivery_hist g on (a.quick_deliveries_id = g.quick_deliveries_id)
				 where 1 = 1 ";
			
			
			if(isset($data['status_id'])){
				if(!empty($data['status_id'])){
					$sql .= " AND a.status in (".$this->db->escape($data['status_id']).")";
				}
			}
			

			if ($this->user->getUserGroupId() == 112 || $this->user->getUserGroupId() == 86 || $this->user->getUserGroupId() == 87 || $this->user->getUserGroupId() == 111) {
				$sql .= " AND a.area_id = '".$this->user->getAreaId()."' ";
			} else {
				if(isset($data['area_id'])){
					if(!empty($data['area_id'])){
						$sql .= " AND a.area_id = '".$this->db->escape($data['area_id'])."' ";
					}
				}
			}
			
			if ($this->user->getUserGroupId() == 86 || $this->user->getUserGroupId() == 87) {
				if(!in_array(138, explode(',', $data['status_id']))){
					$sql .= " AND a.rider_id = '".$this->user->getId()."'";
					$sql .= " AND a.area_id = '".$this->user->getAreaId()."' ";
				}
				if(in_array(138, explode(',', $data['status_id']))){
					$sql .= " AND (a.viewable = 1 OR (a.viewable = 0 AND a.last_viewed < '".date('Y-m-d H:i:s', strtotime($this->user->now(). ' - 45 seconds'))."'))";
					$sql .= " AND (a.rider_id IS NULL OR a.rider_id = 0)";
					$sql .= " AND a.area_id = '".$this->user->getAreaId()."' ";
				}
			} else {
				if(isset($data['rider_id'])){
					if(!empty($data['rider_id'])){
						$sql .= " and a.rider_id = '".$data['rider_id']."' ";
						$sql .= " AND a.area_id = '".$this->user->getAreaId()."' ";
					}
				}
				
				if(isset($data['rider_name'])){
					if(!empty($data['rider_name'])){
						$sql .= " and a.rider_id = '".$data['rider_name']."' ";
					}
				}
			}
			
			if ($this->user->getUserGroupId() == 88 || $this->user->getUserGroupId() == 98 || $this->user->getUserGroupId() == 97) {
				$sql .= " AND a.user_id = '".$this->user->getId()."' ";
			}
			
			if(isset($data['quick_delivery_id'])){
				if(!empty($data['quick_delivery_id'])){
					$sql .= " and a.quick_deliveries_id = '".$data['quick_delivery_id']."' ";
				}
			}

			if(isset($data['datefrom_search'])){
				if(!empty($data['datefrom_search'])){
					$sql .= " AND a.date_added >= '".$data['datefrom_search']." 00:00:00'";
				}
			}

			if(isset($data['dateto_search'])){
				if(!empty($data['dateto_search'])){
					$sql .= " AND a.date_added <= '".$data['dateto_search']." 23:59:59'";
				}
			}
			
			if(isset($data['mdatefrom_search'])){
				if(!empty($data['mdatefrom_search'])){
					$sql .= " AND g.date_added >= '".$data['mdatefrom_search']." 00:00:00'";
				}
			}

			if(isset($data['mdateto_search'])){
				if(!empty($data['mdateto_search'])){
					$sql .= " AND g.date_added <= '".$data['mdateto_search']." 23:59:59'";
				}
			}
		
		if($query_type == "data") {
			
			$sql .= " group by a.quick_deliveries_id order by a.quick_deliveries_id desc";
			
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
			$returnFinal = array();
			$ctr = 0;
			
			foreach($query->rows as $quicky){
				
				$sql = "select delivery_fee, special_flag from oc_quick_delivery where quick_deliveries_id = ".$quicky['quick_deliveries_id'];
				$query = $this->db->query($sql);
				$delivery_fee = $query->row['delivery_fee'];
				$special_flag = $query->row['special_flag'];
				
				if ($special_flag == 0) {
					$special_flag = "Regular";
				}
				
				if ($special_flag == 1) {
					$special_flag = "Special";
				}
				
				if ($special_flag == 2) {
					$special_flag = "Manual";
				}
				
				$commission_amount = $delivery_fee * 0.2;
				
				$sql = "select count(1) total 
						  from oc_qd_location 
						 where location_type = 'PICKUP' and quick_deliveries_id = ".$quicky['quick_deliveries_id'];
				$query = $this->db->query($sql);
				$pickup = $query->row['total'];
				
				$sql = "select count(1) total 
						  from oc_qd_location 
						 where location_type = 'DROPOFF' and quick_deliveries_id = ".$quicky['quick_deliveries_id'];
				$query = $this->db->query($sql);
				$dropoff = $query->row['total'];
				
				
				
				if ($pickup > 0) {
					$sql = "select contact_name
							  from oc_qd_location 
							 where location_type = 'PICKUP' and quick_deliveries_id = ".$quicky['quick_deliveries_id']." LIMIT 1";
					$query = $this->db->query($sql);
					$contact_name = $query->row['contact_name'];
				} else {
					$contact_name = "";
				}
			
				$return = array();
				$return['quick_deliveries_id'] = $quicky['quick_deliveries_id'];
				$return['user'] = $quicky['user'];
				$return['contact_name'] = $contact_name;
				$return['delivery_fee'] = $delivery_fee;
				$return['rider'] = $quicky['rider'];
				$return['commission_amount'] = $commission_amount;
				$return['stats'] = $quicky['stats'];
				$return['method'] = $quicky['method'];
				$return['special_flag'] = $special_flag;
				$return['pickup'] = $pickup;
				$return['dropoff'] = $dropoff;
				$return['area'] = $quicky['area'];
				$return['date_added'] = $quicky['date_added'];
				$return['modified_date'] = $quicky['modified_date'];
				$returnFinal[$ctr] = $return;
				$ctr++;

			}
				return $returnFinal;	
		} else {
			$sql .= " group by a.quick_deliveries_id order by a.quick_deliveries_id desc";
			
			$sqlt = "select count(1) total from (".$sql.") t ";
			$query = $this->db->query($sqlt);
			return $query->row['total'];	
		}

	} 
	
	public function getEwallet($data){
		$sql = "select ewallet 
				  from oc_user 
				 where 1 = 1 ";
		
		if(isset($data['rider_name'])){
			if(!empty($data['rider_name'])){
				$sql .= " and user_id = '".$data['rider_name']."' ";
			}
		}
		
		if(isset($data['rider_id'])){
			if(!empty($data['rider_id'])){
				$sql .= " and user_id = '".$data['rider_id']."' ";
			}
		}
		
		$query = $this->db->query($sql);
		return $query->row;	
	}
	
	public function getTotalCommission($data){
		$sql = "select count(1) total, sum(a.profit) delivery_fee, b.ewallet
				  from oc_quick_delivery a
				  left join oc_user b on (a.rider_id = b.user_id)
				 where a.status = 247 ";
				 
			if(isset($data['status_id'])){
				if(!empty($data['status_id'])){
					$sql .= " AND a.status in (".$this->db->escape($data['status_id']).")";
				}
			}
			

			if ($this->user->getUserGroupId() == 112 || $this->user->getUserGroupId() == 86 || $this->user->getUserGroupId() == 87 || $this->user->getUserGroupId() == 111) {
				$sql .= " AND a.area_id = '".$this->user->getAreaId()."' ";
			} else {
				if(isset($data['area_id'])){
					if(!empty($data['area_id'])){
						$sql .= " AND a.area_id = '".$this->db->escape($data['area_id'])."' ";
					}
				}
			}
			
			if ($this->user->getUserGroupId() == 86 || $this->user->getUserGroupId() == 87) {
				if(!in_array(138, explode(',', $data['status_id']))){
					$sql .= " AND a.rider_id = '".$this->user->getId()."'";
					$sql .= " AND a.area_id = '".$this->user->getAreaId()."' ";
				}
				if(in_array(138, explode(',', $data['status_id']))){
					$sql .= " AND (a.viewable = 1 OR (a.viewable = 0 AND a.last_viewed < '".date('Y-m-d H:i:s', strtotime($this->user->now(). ' - 45 seconds'))."'))";
					$sql .= " AND (a.rider_id IS NULL OR a.rider_id = 0)";
					$sql .= " AND a.area_id = '".$this->user->getAreaId()."' ";
				}
			} else {
				if(isset($data['rider_id'])){
					if(!empty($data['rider_id'])){
						$sql .= " and a.rider_id = '".$data['rider_id']."' ";
						$sql .= " AND a.area_id = '".$this->user->getAreaId()."' ";
					}
				}
				
				if(isset($data['rider_name'])){
					if(!empty($data['rider_name'])){
						$sql .= " and a.rider_id = '".$data['rider_name']."' ";
					}
				}
			}
			
			if ($this->user->getUserGroupId() == 88 || $this->user->getUserGroupId() == 98 || $this->user->getUserGroupId() == 97) {
				$sql .= " AND a.user_id = '".$this->user->getId()."' ";
			}
			
			if(isset($data['quick_delivery_id'])){
				if(!empty($data['quick_delivery_id'])){
					$sql .= " and a.quick_deliveries_id = '".$data['quick_delivery_id']."' ";
				}
			}

			if(isset($data['datefrom_search'])){
				if(!empty($data['datefrom_search'])){
					$sql .= " AND a.date_added >= '".$data['datefrom_search']." 00:00:00'";
				}
			}

			if(isset($data['dateto_search'])){
				if(!empty($data['dateto_search'])){
					$sql .= " AND a.date_added <= '".$data['dateto_search']." 23:59:59'";
				}
			}
			
			// if(isset($data['mdatefrom_search'])){
				// if(!empty($data['mdatefrom_search'])){
					// $sql .= " AND g.date_added >= '".$data['mdatefrom_search']." 00:00:00'";
				// }
			// }

			// if(isset($data['mdateto_search'])){
				// if(!empty($data['mdateto_search'])){
					// $sql .= " AND g.date_added <= '".$data['mdateto_search']." 23:59:59'";
				// }
			// }
		
		$query = $this->db->query($sql);
		return $query->row;		
	}
	
	public function getQDDetails($quick_deliveries_id) {
		$sql = "select a.quick_deliveries_id, concat(b.firstname, '',b.lastname) user, 
						  concat(c.firstname, '',c.lastname) rider, 
						  a.customer_type 'custype', 
						  a.required_e_wallet, a.delivery_fee, d.description 'stats', 
						  e.description 'method', a.schedule_of_delivery, a.date_added
					 from oc_quick_delivery a
					 left join oc_user b on (a.user_id = b.user_id)
					 left join oc_user c on (a.rider_id = c.user_id)
					 left join gui_status_tbl d on (a.status = d.status_id)
					 left join gui_status_tbl e on (a.payment_method = e.status_id)
					 left join oc_drop_off f on (a.quick_deliveries_id = f.quick_deliveries_id)
					 where a.quick_deliveries_id = ".$quick_deliveries_id;
			
		$query = $this->db->query($sql);
		return $query->row;	

	}
	
	public function getDODetails($quick_deliveries_id) {
		$sql = "SELECT a.drop_off_id, GROUP_CONCAT(CONCAT(b.quantity, ' x (', b.description, ')')) item_details, 
					SUM(b.amount * b.quantity) amount, 
					a.drop_off_contact_name, 
					a.drop_off_contact_number, 
					a.drop_off_location
				FROM oc_drop_off a
				LEFT JOIN oc_item_details b 
				ON(b.drop_off_id = a.drop_off_id)
				WHERE a.quick_deliveries_id = ".$quick_deliveries_id."
				AND b.description IS NOT NULL";
		
		$query = $this->db->query($sql);
		return $query->rows;	
		
	}
	
	public function getArea(){
		$sql = "select area_id, description from oc_area order by description asc";
		$result = $this->db->query($sql);

		return $result->rows;
	}
	
	public function getRidernames(){
		$sql = "select user_id 'rider_id', concat(firstname, ' ',lastname) ridername
				  from oc_user 
				 where user_group_id = 86 
				   and status = 1
				 order by ridername asc";
		$result = $this->db->query($sql);

		return $result->rows;
	}
	
}
?>