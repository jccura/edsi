<?php
class ModelAccountRewards extends Model {
	
	public function getCommissionType () {
		$sql = "SELECT commission_type_id, description from oc_commission_type";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getEwalletHistory($data,$query_type){
			$sql = "select a.ewallet_hist_id, a.withdrawal_id, concat(c.firstname,' ',c.lastname) source_name, b.description com_type, 
					   a.tax, a.debit, a.credit, a.remarks, a.date_added
				from oc_ewallet_hist a
				left join oc_commission_type b on(a.commission_type_id = b.commission_type_id)
				left join oc_user c on(a.source_user_id = c.user_id)
				where a.user_id = " . $this->user->getId();
				
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
				
				if(isset($data['commission_type_id'])) {
					if (!empty($data['commission_type_id'])) {
						$sql .= " AND a.commission_type_id = " . $data['commission_type_id'];
					}
				}	
		
		if($query_type == "data") {
			
			$sql .= " order by a.date_added desc";
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 0;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			//echo $sql;
			$result = $this->db->query($sql);
			return $result->rows;
		} else {
			$sqlt = "select count(1) total from (".$sql.") t";
			$result = $this->db->query($sqlt);
			return $result->row['total'];			
		}
	}
	public function getAvailableEwallet($data){
		
		//echo "getEwalletTotal";
		
		$sql = "SELECT IFNULL ( (SELECT ewallet FROM oc_user where user_id = ".$this->user->getId();
		
		$sql .= " ),0) ewallet";
		$query = $this->db->query($sql);
		return $query->row['ewallet'];
	}
	public function getTotalWithdrawal($data){
		
		$sql = "select sum(amount) amount 
					from oc_withdraw_hist 
					where user_id = ".$this->user->getId()." 
					and status in(72,74) ";
		
		$query = $this->db->query($sql);
		return $query->row['amount']; 
	}
	public function getTotalEwallet($data){
		
		$sql = "select sum(credit) total from oc_ewallet_hist where user_id = " . $this->user->getId();
		
		$query = $this->db->query($sql);
		return $query->row['total']; 
	}

}
?>