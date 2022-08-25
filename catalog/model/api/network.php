<?php
class ModelApiNetwork extends Model {

	public function getNetwork($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['user_id'])) {
			if(empty($data['user_id'])) {
				$valid = 0;
				$return_msg .= "User ID is required\n";
			} else {
				$sql = "SELECT COUNT(1) total FROM oc_user WHERE user_id = ".$this->db->escape($data['user_id']);
				$query = $this->db->query($sql);
				$total = $query->row['total'];

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

			$sql = "SELECT a.user_id dl_user_id, 
						     b.username dl_username, concat(b.firstname, ' ', b.middlename, ' ', b.lastname) dl_desc, 
							 c.username sp_username, concat(c.firstname, ' ', c.middlename, ' ', c.lastname) sp_desc,
							 a.level, b.date_added,
							 case when b.activation_flag = 1 then 'Active' else 'In-Active' end act_flag
					FROM oc_unilevel a
					JOIN oc_user b on(a.user_id = b.user_id)
					JOIN oc_user c on(c.user_id = b.refer_by_id)
					WHERE a.sponsor_user_id = ".$this->db->escape($data['user_id'])." and a.level <= 2
					ORDER BY a.level";

			$unilevel_list = $this->db->query($sql);


			$total_active_reseller = $this->getTotalActiveMF($data['user_id']);
			$total_inactive_reseller = $this->getTotalInActiveMF($data['user_id']);

	 		return Array( 	'status'			=> 200,
	  						'valid' 			=> true,
	  						'status_message'	=> 'Network successfully loaded!',
	  						'data'				=> Array(	'total_active_reseller' => $total_active_reseller,
	  														'total_inactive_reseller' => $total_inactive_reseller,
	  														'networks' => $unilevel_list->rows) );
		}
	}

	public function getTotalActiveMF($user_id) {
		$sql = "SELECT count(1) total
				FROM oc_unilevel a
				JOIN oc_user b ON(a.user_id = b.user_id)
				WHERE a.sponsor_user_id = ".$this->db->escape($user_id)."
				AND a.level <= 2
				AND b.user_group_id = 39
				AND b.activation_flag = 1 ";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	public function getTotalInActiveMF($user_id) {
		$sql = "SELECT count(1) total
				FROM oc_unilevel a
				JOIN oc_user b ON(a.user_id = b.user_id)
				WHERE a.sponsor_user_id = ".$this->db->escape($user_id)."
				AND a.level <= 2
				AND b.user_group_id = 13
				AND b.activation_flag = 0 ";
		$query = $this->db->query($sql);
		return $query->row['total'];		
	}

}
?>