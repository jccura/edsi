<?php
class ModelApiDashboard extends Model {

	public function loadDashboard($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['user_id'])) {
			if(empty($data['user_id'])) {
				$valid = 0;
				$return_msg .= "User ID is required\n";
			} else {
				$sql = "SELECT COUNT(1) total, username, id_no, activation_flag FROM oc_user WHERE user_id = ".$this->db->escape($data['user_id']);
				$query = $this->db->query($sql);
				$total = $query->row['total'];
				$username = $query->row['username'];
				$id_no = $query->row['id_no'];
				$activation_flag = $query->row['activation_flag'];

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

	 		return Array( 	'status'			=> 200,
	  						'valid' 			=> true,
	  						'status_message'	=> 'Dashboard successfully loaded!',
	  						'data'				=> Array('referral_link' => LOCAL_PROD.'register/'.$username,
	  													'total_earnings' => $this->getEwalletPerStatus('1', $data['user_id']),
	  													'total_personal_rebates' => $this->getEwalletPerStatus('21', $data['user_id']),
	  													'total_referrals' => $this->getEwalletPerStatus('19,20', $data['user_id']),
	  													'id_no' => $id_no,
	  													'activation_flag' => $activation_flag) );
		}
	}

	public function getEwalletPerStatus($com_id, $user_id) {
		
		if($com_id == "all") {
			$sql = "SELECT CASE WHEN sum(credit) IS NULL THEN 0.00 ELSE sum(credit) END total 
					FROM oc_ewallet_hist 
					WHERE user_id = " . $this->db->escape($user_id);
			$query = $this->db->query($sql);
		} else {
			$sql = "SELECT CASE WHEN sum(credit) IS NULL THEN 0.00 ELSE sum(credit) END total 
					FROM oc_ewallet_hist 
					WHERE user_id = " . $this->db->escape($user_id). " 
					AND commission_type_id in(".$com_id.")";
			$query = $this->db->query($sql);	
		}

		return $query->row['total'];
	}

}
?>