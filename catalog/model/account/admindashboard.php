<?php
class ModelAccountAdmindashboard extends Model {

	public function getInstawinPlayedEcoins(){
		$sql = "SELECT sum(amount) total FROM oc_instawin_hist where 1 = 1 ";
		if(isset($data['datefrom'])) {
			if (!empty($data['datefrom'])) {
				$sql .= " and date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
		}
		if(isset($data['dateto'])) {
			if (!empty($data['dateto'])) {
				$sql .= " and date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}
		$query = $this->db->query($sql);
		return $query->row['total']; 
	}

	public function getInstawinWinnings(){
		$sql = "SELECT sum(amount * 2) total FROM oc_instawin_hist where winner = 1 ";
		if(isset($data['datefrom'])) {
			if (!empty($data['datefrom'])) {
				$sql .= " and date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
		}
		if(isset($data['dateto'])) {
			if (!empty($data['dateto'])) {
				$sql .= " and date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}
		$query = $this->db->query($sql);
		return $query->row['total']; 
	}	
	
	public function getVaultinEarnings(){
		$sql = "SELECT sum(credit) total FROM oc_ewallet_hist where commission_type_id = 19 ";
		if(isset($data['datefrom'])) {
			if (!empty($data['datefrom'])) {
				$sql .= " and date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
		}
		if(isset($data['dateto'])) {
			if (!empty($data['dateto'])) {
				$sql .= " and date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}
		$query = $this->db->query($sql);
		return $query->row['total']; 
	}
	
	public function getTransferredEcoins(){
		$sql = "SELECT sum(debit) total FROM oc_epoints_hist where commission_type_id = 16 ";
		if(isset($data['datefrom'])) {
			if (!empty($data['datefrom'])) {
				$sql .= " and date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
		}
		if(isset($data['dateto'])) {
			if (!empty($data['dateto'])) {
				$sql .= " and date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}
		$query = $this->db->query($sql);
		return $query->row['total']; 
	}
	
	public function getTotalCreatedEcoins(){
		$sql = "SELECT sum(credit) total FROM oc_epoints_hist where commission_type_id = 15 ";
		if(isset($data['datefrom'])) {
			if (!empty($data['datefrom'])) {
				$sql .= " and date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
		}
		if(isset($data['dateto'])) {
			if (!empty($data['dateto'])) {
				$sql .= " and date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}
		$query = $this->db->query($sql);
		return $query->row['total']; 
	}

	public function getVaultinEcoins(){
		$sql = "SELECT sum(amount) total FROM oc_vaultin where 1 = 1 ";
		if(isset($data['datefrom'])) {
			if (!empty($data['datefrom'])) {
				$sql .= " and date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
		}
		if(isset($data['dateto'])) {
			if (!empty($data['dateto'])) {
				$sql .= " and date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}
		$query = $this->db->query($sql);
		return $query->row['total']; 
	}	

}
?>