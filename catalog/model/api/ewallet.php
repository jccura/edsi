<?php
class ModelApiEwallet extends Model {

	public function getEwallet($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['user_id'])) {
			if(empty($data['user_id'])) {
				$valid = 0;
				$return_msg .= "User ID is required\n";
			} else {
				$sql = "SELECT COUNT(1) total, user_group_id FROM oc_user WHERE user_id = ".$this->db->escape($data['user_id']);
				$query = $this->db->query($sql);
				$total = $query->row['total'];
				$user_group_id = $query->row['user_group_id'];

				if($total == 0){
					$valid = 0;
					$return_msg .= "User ID doesn't exist\n";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "User ID is required\n";
		}

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$sql = "	SELECT b.description com_type, a.debit, c.username source_username, a.credit, a.date_added, a.level
					  FROM oc_ewallet_hist a
					  JOIN oc_commission_type b on(a.commission_type_id = b.commission_type_id)
					  JOIN oc_user c on(a.source_user_id = c.user_id)
					  WHERE 1 = 1 ";

			if(in_array($user_group_id, Array(13, 39, 40))){
				$sql .= " AND a.user_id = ".$this->db->escape($data['user_id']);
			}
			
			$wallets = $this->db->query($sql);

			$rebates = $this->getPersonalRebates($data['user_id']);
			$referrals = $this->getReferrals($data['user_id']);

	 		return Array( 	'status'			=> 200,
	  						'valid' 			=> true,
	  						'status_message'	=> 'Ewallet successfully loaded!',
	  						'data'				=> Array(	'rebates' => $rebates,
	  														'referrals' => $referrals,
	  														'wallets' => $wallets->rows) );
		}
	}

	public function getPersonalRebates($user_id) {
		$sql = "select coalesce(sum(credit),0) credit
				  from oc_ewallet_hist 
				 where user_id = ".$this->db->escape($user_id)."
				   and commission_type_id = 21 ";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['credit'];
	}
	
	public function getReferrals($user_id) {
		$sql = "select coalesce(sum(credit),0) credit
				  from oc_ewallet_hist 
				 where user_id = ".$this->db->escape($user_id)."
				   and commission_type_id in(1,2,19,20) ";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['credit'];
	}

}
?>