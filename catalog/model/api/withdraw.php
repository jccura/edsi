<?php
class ModelApiWithdraw extends Model {

	public function getWithdraw($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['user_id'])) {
			if(empty($data['user_id'])) {
				$valid = 0;
				$return_msg .= "User ID is required\n";
			} else {
				$sql = "SELECT COUNT(1) total, user_group_id, activation_flag FROM oc_user WHERE user_id = ".$this->db->escape($data['user_id']);
				$query = $this->db->query($sql);
				$total = $query->row['total'];
				$user_group_id = $query->row['user_group_id'];
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

			$sql = "SELECT a.withdrawal_id, a.amount, a.proc_fee, a.gross_amt_withdraw, a.amount_in_btc, a.wallet_address,
							concat(b.firstname, ' ', b.lastname) banker, a.banker_id,
							concat(c.firstname, ' ', c.lastname) requestor, a.user_id,
							d.description status, a.date_added, a.status status_id, 
							a.reference, a.bdo_acct_no
					FROM oc_withdraw_hist a 
					LEFT JOIN oc_user b ON(a.banker_id = b.user_id)
					LEFT JOIN oc_user c ON(a.user_id = c.user_id)
					LEFT JOIN gui_status_tbl d ON(a.status = d.status_id)
					WHERE 1 = 1  ";

			if(in_array($user_group_id, Array(13, 39))){
				$sql .= " AND a.user_id = ".$this->db->escape($data['user_id']);
			}
			$sql .= " ORDER BY a.date_added DESC";
			
			$withdraws = $this->db->query($sql);

			
			$withdrawal = $this->getTotalWithdrawal($data['user_id']);
			$ewallet = $this->getTotalEwallet($data['user_id']);
			$earnings = $withdrawal + $ewallet;
			$trans_session_id = $this->user->Random(20);

	 		return Array( 	'status'			=> 200,
	  						'valid' 			=> true,
	  						'status_message'	=> 'Withdraw successfully loaded!',
	  						'data'				=> Array(	'earnings' => $earnings,
	  														'withdrawal' => $withdrawal,
	  														'ewallet' => $ewallet,
	  														'trans_session_id' => $trans_session_id,
	  														'activation_flag' => $activation_flag,
	  														'withdraws' => $withdraws->rows ));
		}
	}

	public function getTotalEwallet($user_id) {
		$sql = "select ewallet
				  from oc_user
				 where user_id = ".$this->db->escape($user_id);

		$query = $this->db->query($sql);
		return $query->row['ewallet'];
	}

	public function getTotalWithdrawal($user_id) {
		$sql = "select coalesce(sum(amount),0) withdrawal
				  from oc_withdraw_hist 
				 where user_id = ".$this->db->escape($user_id)."
				   and status in(72,74,76)";

		$query = $this->db->query($sql);
		return $query->row['withdrawal'];
	}

	public function withdraw($data) {

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

		if(isset($data['trans_session_id'])) {
			if(empty($data['trans_session_id'])) {
				$valid = 0;
				$return_msg .= "Transaction Session ID is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Transaction Session ID is required\n";
		}

		if(isset($data['amount'])) {
			if(empty($data['amount'])) {
				$valid = 0;
				$return_msg .= "Amount is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Amount is required\n";
		}

		if(isset($data['bdo_acct_no'])) {
			if(empty($data['bdo_acct_no'])) {
				$valid = 0;
				$return_msg .= "BDO Account No. is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "BDO Account No. is required\n";
		}

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$deposit_id = 0;
			$sql = "select withdrawal_id from oc_withdraw_hist where trans_session_id = '".$this->db->escape($data['trans_session_id'])."' ";
			$query = $this->db->query($sql);
			
			if(!isset($query->row['withdrawal_id'])) {	
				$sql = "select ewallet 
						  from oc_user 
						 where user_id = ".$this->db->escape($data['user_id']);
				$query = $this->db->query($sql);
				$ewallet = $query->row['ewallet'];
				
				$proc_fee = 50;
				$gross_amt_withdraw = $data['amount'] - $proc_fee;
					
				if($ewallet >= $data['amount'] && $data['amount'] >= 500) {
						
					$sql = "INSERT INTO oc_withdraw_hist ";
					$sql .= "SET date_added ='".$this->user->now()."'";
					$sql .= ", amount = ".$this->db->escape($data['amount']);
					$sql .= ", proc_fee = ".$proc_fee;
					$sql .= ", gross_amt_withdraw = ".$gross_amt_withdraw;
					$sql .= ", bdo_acct_no = '".$this->db->escape($data['bdo_acct_no'])."' ";
					$sql .= ", user_id = ".$this->db->escape($data['user_id']);
					$sql .= ", trans_session_id = '".$this->db->escape($data['trans_session_id'])."'";
					$sql .= ", status = 72 ";
					$this->db->query($sql);
					$withdrawal_id = $this->db->getLastId();
					
					$sql = "insert into oc_ewallet_hist
								   set user_id = ".$this->db->escape($data['user_id'])."
									  ,source_user_id = ".$this->db->escape($data['user_id'])."
									  ,withdrawal_id = ".$withdrawal_id."
									  ,commission_type_id = 18 
									  ,debit = ".$this->db->escape($data['amount'])."
									  ,created_by = 1 
									  ,date_added = '".$this->user->now()."'";
					$this->db->query($sql);
					
					$sql = "update oc_user 
							   set ewallet = ewallet - ".$data['amount']." 
							 where user_id = ".$this->db->escape($data['user_id']);
					$this->db->query($sql);

					$withdraws = $this->getWithdraw(Array('user_id' => $data['user_id']))['data']['withdraws'];
					
					return Array( 	'status'			=> 200,
			  						'valid' 			=> true,
			  						'status_message'	=> 'Successful transaction. Please refer to withdrawal ID number '.$withdrawal_id.'.',
			  						'data'				=> Array('withdraws' => $withdraws) );
				} else {
					return Array( 	'status'			=> 200,
			  						'valid' 			=> false,
			  						'status_message'	=> 'Withdrawal Balance is not enough. (Minimum of Php 500)',
			  						'data'				=> [] );
				}				
				
			} else {
				return Array( 	'status'			=> 200,
		  						'valid' 			=> false,
		  						'status_message'	=> 'Resubmission of transaction is not allowed.',
		  						'data'				=> [] );
			}
		}
	}

}
?>