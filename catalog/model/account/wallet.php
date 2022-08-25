<?php
class ModelAccountWallet extends Model {	

	public function getWallets($data, $query_type) {

		$sql = " select b.description com_type, a.debit, c.username source_username, 
						a.gross_amt, a.credit, a.tax, a.date_added, a.level, a.order_id, a.request_id
				  FROM oc_ewallet_hist a
				  JOIN oc_commission_type b on(a.commission_type_id = b.commission_type_id)
				  JOIN oc_user c on(a.source_user_id = c.user_id)
				  WHERE 1 = 1  ";	

		if($this->user->getUserGroupId() == 13 or $this->user->getUserGroupId() == 39 
			or $this->user->getUserGroupId() == 40 or $this->user->getUserGroupId() == 45 
			or $this->user->getUserGroupId() == 46 or $this->user->getUserGroupId() == 56
			or $this->user->getUserGroupId() == 58 or $this->user->getUserGroupId() == 59
			or $this->user->getUserGroupId() == 60 or $this->user->getUserGroupId() == 36) {
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
			
			$sql .= " order by a.ewallet_hist_id desc ";
			
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
	
	public function getCurrentEwallet() {
		$sql = "select coalesce(ewallet, 0) ewallet
				  from oc_user
				 where user_id = ".$this->user->getId();
				 
		$query = $this->db->query($sql);
		return $query->row['ewallet'];
	}
	
	public function getTotalEwallet() {
		$sql = "select coalesce(sum(credit),0) credit
				  from oc_ewallet_hist 
				 where user_id = ".$this->user->getId()."
				   and credit > 0 ";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['credit'];
	}
	
	public function getOrderCommissions() {
		$sql = "select coalesce(sum(credit),0) credit
				  from oc_ewallet_hist 
				 where user_id = ".$this->user->getId()."
				   and commission_type_id not in(1,2,41)
				   and credit > 0 ";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['credit'];
	}
	
	public function getPersonalRebates() {
		$sql = "select coalesce(sum(credit),0) credit
				  from oc_ewallet_hist 
				 where user_id = ".$this->user->getId()."
				   and commission_type_id = 21 ";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['credit'];
	}
	
	public function getReferrals() {
		$sql = "select coalesce(sum(credit),0) credit
				  from oc_ewallet_hist 
				 where user_id = ".$this->user->getId()."
				   and commission_type_id in(1,2) ";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['credit'];
	}
	
	public function getEarned1stFC() {
		$sql = "select sum(credit) credit
				  from oc_ewallet_hist 
				 where user_id = ".$this->user->getId()."
				   and commission_type_id = 1 ";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['credit'];
	}
	
	public function getEarned2nd10thFC() {
		$sql = "select sum(credit) credit
				  from oc_ewallet_hist 
				 where user_id = ".$this->user->getId()."
				   and commission_type_id >= 2
				   and commission_type_id <= 10 ";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['credit'];
	}
	
	public function getEarnedTransaction() {
		$sql = "select sum(credit) credit
				  from oc_ewallet_hist 
				 where user_id = ".$this->user->getId()."
				   and commission_type_id = 13 ";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['credit'];
	}
	
	public function getEarnedWithdrawal() {
		$sql = "select sum(credit) credit
				  from oc_ewallet_hist 
				 where user_id = ".$this->user->getId()."
				   and commission_type_id = 14 ";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['credit'];
	}
	
}
?>