<?php
class ModelAccountVaultin extends Model {
	
	public function addVaultIn($data = array()) {
		$sql = "select concat(firstname , ' ', middlename , ' ' , lastname) as fullname, epoints, used_epoints from oc_user where user_id = ".$this->user->getId();
		$query = $this->db->query($sql);
		$current_epoints = $query->row['epoints'] - $query->row['used_epoints'];
		$fullname = $query->row['fullname'];
		if($current_epoints >= $data['amount']) {
			$sql = "select vaultin_id from oc_vaultin where session_id = '".$data['trans_session_id']."' ";
			$query = $this->db->query($sql);
			
			if(!isset($query->row['vaultin_id'])) {			
				
				$sql = " insert into oc_vaultin
							set user_id = ".$this->user->getId()."
							   ,amount = ".$data['amount']."
							   ,session_id = '".$data['trans_session_id']."'
							   ,date_added = '".$this->user->now()."'";
				
				$this->db->query($sql);
				
				$vaultin_id = $this->db->getLastId();
				
				$sql = "update oc_user set epoints = epoints - ".$data['amount']."
										 , used_epoints = used_epoints + ".$data['amount']." 
								     where user_id = ".$this->user->getId();
				$this->db->query($sql);
				
				$sql = " insert into oc_epoints_hist 
							set user_id = ".$this->user->getId()."
							   ,source_user_id = ".$this->user->getId()."
							   ,vaultin_id = ".$vaultin_id."
							   ,debit = ".$data['amount']."
							   ,commission_type_id = 20
							   ,date_added = '".$this->user->now()."'";
				$this->db->query($sql);
								
				$counter = $data['amount'] / 1000;
				
				$refer_by_id = 0;
				$sql = "select sponsor_user_id 
						  from oc_unilevel 
						 where user_id = ".$this->user->getId()."
						   and level = 1 ";
				$query = $this->db->query($sql);
				if(isset($query->row['sponsor_user_id'])) {
					$refer_by_id = $query->row['sponsor_user_id'];
				}
				
				if($refer_by_id > 0) {
					$sql = "update oc_user 
									   set ewallet = ewallet + 1.00 
									 where user_id = ".$refer_by_id;
					$this->db->query($sql);
									
					$sql = "insert into oc_ewallet_hist
									   set user_id = ".$refer_by_id."
										  ,source_user_id = ".$this->user->getId()."
										  ,vaultin_id = ".$vaultin_id."
										  ,commission_type_id = 1 
										  ,level = 1
										  ,credit = (1.00 * ".$counter.")
										  ,created_by = 1 
										  ,date_added = '".$this->user->now()."'";
					$this->db->query($sql);				
				}
				
				$sql = "select sponsor_user_id, level 
						  from oc_unilevel 
						 where user_id = ".$this->user->getId()." 
						   and level >= 2 
						   and level <= 10 
						   order by level asc ";
				$query = $this->db->query($sql);
				
				foreach($query->rows as $mf2) {
					if($mf2['sponsor_user_id'] > 0) {
						$sql = "update oc_user 
										   set ewallet = ewallet + 0.10 
										 where user_id = ".$mf2['sponsor_user_id'];
						$this->db->query($sql);
										
						$sql = "insert into oc_ewallet_hist
										   set user_id = ".$mf2['sponsor_user_id']."
											  ,source_user_id = ".$this->user->getId()."
											  ,vaultin_id = ".$vaultin_id."
											  ,commission_type_id = ".$mf2['level']." 
											  ,level = ".$mf2['level']."
											  ,credit = (0.10 * ".$counter.")
											  ,created_by = 1 
											  ,date_added = '".$this->user->now()."'";
						$this->db->query($sql);	
					}
				}
				
				return "Successful transaction. Please refer to vaultin ID number ".$vaultin_id.".";
				
			} else {
				return "Resubmission of transaction is not allowed.";
			}
		} else {
			return "You don't have enough ecoins.";			
		}
	}	

	public function getVaultIns($data, $query_type) {

		$sql = " select a.vaultin_id, a.date_added, a.amount, a.days, a.dates, a.cycle_number,
						case when completed = 1 then 'Completed' else 'Ongoing' end status,
						week1_roi, week2_roi, week3_roi, week4_roi, week5_roi, week6_roi, roi_amount
				  FROM oc_vaultin a 
				  WHERE 1 = 1  ";	

		if($this->user->getUserGroupId() == 13 or $this->user->getUserGroupId() == 39 or $this->user->getUserGroupId() == 40) {
			$sql .= " AND a.user_id = ".$this->user->getId();
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
			
			$sql .= " order by a.vaultin_id desc ";
			
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
	
	public function getUserEpoints() {
		$sql = "select epoints
				  from oc_user 
				 where user_id = ".$this->user->getId();
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['epoints'];
	}
	
	public function getTotalVaultIns() {
		$sql = "select coalesce(sum(amount),0) vaultins
				  from oc_vaultin 
				 where user_id = ".$this->user->getId();
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['vaultins'];
	}
	
	public function getTotalEarnings() {
		$sql = "select coalesce(sum(credit),0) earnings
				  from oc_ewallet_hist 
				 where user_id = ".$this->user->getId()."
				   and commission_type_id = 19 ";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['earnings'];
	}
}
?>