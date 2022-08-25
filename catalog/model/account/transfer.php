<?php
class ModelAccountTransfer extends Model {
	
	public function transferEcoins($data = array()) {
		
		$deposit_id = 0;
		$sql = "select transfer_id from oc_transfer where session_id = '".$data['trans_session_id']."' ";
		$query = $this->db->query($sql);
		
		if(!isset($query->row['transfer_id'])) {			
		
			$sql = " insert into oc_transfer
						set source_user_id = ".$this->user->getId()."
						   ,user_id = ".$data['user_id']."
						   ,credit = ".$data['amount']."
						   ,session_id = '".$data['trans_session_id']."'
						   ,date_added = '".$this->user->now()."'";
			$this->db->query($sql);
			
			$transfer_id = $this->db->getLastId();
			
			$sql = "update oc_user 
					   set epoints = epoints + ".$data['amount']." 
					 where user_id = ".$data['user_id'];
			$this->db->query($sql);
			
			$sql = " insert into oc_epoints_hist 
						set user_id = ".$data['user_id']."
						   ,source_user_id = ".$this->user->getId()."
						   ,deposit_id = ".$deposit_id."
						   ,credit = ".$data['amount']."
						   ,commission_type_id = 17
						   ,date_added = '".$this->user->now()."'";
			$this->db->query($sql);
			
			$sql = "update oc_user 
					   set epoints = epoints - ".$data['amount']." 
					 where user_id = ".$this->user->getId();
			$this->db->query($sql);
			
			$sql = " insert into oc_epoints_hist 
						set user_id = ".$data['user_id']."
						   ,source_user_id = ".$this->user->getId()."
						   ,deposit_id = ".$deposit_id."
						   ,debit = ".$data['amount']."
						   ,commission_type_id = 16
						   ,date_added = '".$this->user->now()."'";
			$this->db->query($sql);
				
			return "Successful transaction. Please refer to transaction ID number ".$transfer_id.".";
			
		} else {
			return "Resubmission of transfer is not allowed.";
		}

	}	

	public function getTransferredEcoins($data, $query_type) {

		$sql = " select a.transfer_id, a.date_added, a.debit, a.credit, 
						b.username, c.username source_username
				  FROM oc_transfer a 
				  JOIN oc_user b on(a.user_id = b.user_id)
				  JOIN oc_user c on(a.source_user_id = c.user_id)
				  WHERE 1 = 1  ";	

		if($this->user->getUserGroupId() == 13 or $this->user->getUserGroupId() == 39 or $this->user->getUserGroupId() == 40) {
			$sql .= " and (a.user_id = ".$this->user->getId()." or a.source_user_id = ".$this->user->getId().")";
		}
		
		if(isset($data['datefrom'])) {
			if (!empty($data['datefrom'])) {
				$sql .= " AND a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
		}
		
		if(isset($data['dateto'])) {
			if (!empty($data['dateto'])) {
				$sql .= " AND a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}
	
		if($query_type == "data") {									
			
			$sql .= " order by a.transfer_id desc ";
			
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
			
			//echo $sqlt."<br>";
			return $query->row['total'];
		}		
		
	}
	
	public function getTotalTransfered() {
		return 0;
	}
	
	public function getTotalReceived() {
		return 0;
	}
	
	public function getUserDetails($username) {
		$return_array = array();
		$sql = "select user_id, id_no, concat(firstname, ' ', lastname) name, activation_flag 
				  from oc_user 
				 where lower(username) = lower('".$username."')";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		
		if(isset($query->row['user_id'])) {
			$return_array['id_no'] = $query->row['id_no'];
			$return_array['name'] = $query->row['name'];
			$return_array['user_id'] = $query->row['user_id'];
			$return_array['success'] = "1";
		} else {
			$return_array['id_no'] = "Username ".$username." is not existing.";
			$return_array['name'] = "Not Existing";
			$return_array['user_id'] = 0;
			$return_array['activation_flag'] = "0";
			$return_array['success'] = "0";
		}
		return $return_array;
	}
	
}
?>