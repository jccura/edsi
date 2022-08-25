<?php
class ModelAccountWithdraw extends Model {
		
	public function requestWithdrawal($data){
		$return_msg = "";
		$valid = 1;
		$sql = "select count(1) total 
				  from oc_withdraw_hist 
				 where trans_session_id = '".$data['trans_session_id']."' ";
		$query = $this->db->query($sql);
		$total = $query->row['total'];
		
		if($total > 0) {
			return "Resubmission is not allowed.";
		}
		
		if(isset($data['amount'])) {
			if(!empty($data['amount'])) {
				if(is_numeric($data['amount'])) {
					if($data['amount'] < 500) {
						$valid = 0;
						$return_msg .= "Amount is at least 500.";
					} else {
						$sql = "select (ewallet - ".$data['amount'].") will_remail
								  from oc_user 
								 where user_id = ".$this->user->getId();
						$query = $this->db->query($sql);
						$will_remail = $query->row['will_remail'];
						
						if($will_remail < 0) {
							$valid = 0;
							$return_msg .= "Insufficient Ewallet.<br>";
						}
					}
				} else {
					$valid = 0;
					$return_msg .= "Amount is a number.<br>";
				}
			} else {
				$valid = 0;
				$return_msg .= "Amount is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Amount is mandatory.<br>";
		}
		
		if(isset($data['account_name'])) {
			if(empty($data['account_name'])) {
				$valid = 0;
				$return_msg .= "Account Name is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Account Name is mandatory.<br>";
		}
		
		if(isset($data['account_no'])) {
			if(empty($data['account_no'])) {
				$valid = 0;
				$return_msg .= "Account Number is mandatory";
			}
		} else {
			$valid = 0;
			$return_msg .= "Account Number is mandatory.<br>";
		}
		
		if($valid == 1) {
			$sql = "INSERT INTO oc_withdraw_hist 
					   SET withdraw_type = ".$data['bank']."
					     , date_added = '".$this->user->now()."'
					     , amount = ".$data['amount']." - 50
					     , amount_requested = ".$data['amount']."
					     , user_id = ".$this->user->getId()."
						 , status = 72
						 , trans_session_id = '".$data['trans_session_id']."'
						 , proc_fee = 50
						 , bank_name = ".$data['bank']."
						 , account_no = '".$this->db->escape($data['account_no'])."'
						 , account_name = '".$this->db->escape($data['account_name'])."' ";				
			$query = $this->db->query($sql);
			
			$sql = "UPDATE oc_user 
					   SET ewallet = ewallet - ".$data['amount']." 
					 WHERE user_id = ".$this->user->getId();
			$this->db->query($sql);
			
			$sql = "INSERT INTO oc_ewallet_hist 
					   SET user_id = ".$this->user->getId()."
						 , source_user_id = ".$this->user->getId()."
						 , debit = ".$data['amount']."
						 , commission_type_id = 18
						 , date_added ='".$this->user->now()."'";
			$this->db->query($sql);
			
			return "Successful withdrawal of ".$data['amount'].". Processing fee of Php 50 is deducted automatically.";
		} else {
			return $return_msg;
		}
	}
	
	// public function requestWithdrawal($data = array()) {
		
		// $deposit_id = 0;
		// $sql = "select withdrawal_id from oc_withdraw_hist where trans_session_id = '".$data['trans_session_id']."' ";
		// $query = $this->db->query($sql);
		
		// if(!isset($query->row['withdrawal_id'])) {	
			// $sql = "select ewallet 
					  // from oc_user 
					 // where user_id = ".$this->user->getId();
			// $query = $this->db->query($sql);
			// $ewallet = $query->row['ewallet'];
			// //echo "<br><br><br>Ewallet ".$ewallet."<br>";
			// //echo "<br><br><br>Amount ".$data['amount']."<br>";
			
			// if($ewallet >= $data['amount']) {
			
				// $sql = "INSERT INTO oc_withdraw_hist ";
				// $sql .= "SET date_added ='".$this->user->now()."'";
				// $sql .= ", amount = ".$data['amount'];
				// $sql .= ", bdo_acct_no = '".$this->db->escape($data['bdo_acct_no'])."' ";
				// $sql .= ", user_id = ".$this->user->getId();
				// $sql .= ", trans_session_id = '".$this->db->escape($data['trans_session_id'])."'";
				// $sql .= ", status = 72 ";
				// $this->log->write($sql);
				// $this->db->query($sql);			
				// $withdrawal_id = $this->db->getLastId();
				
				// $sql = "insert into oc_ewallet_hist
							   // set user_id = ".$this->user->getId()."
								  // ,source_user_id = ".$this->user->getId()."
								  // ,withdrawal_id = ".$withdrawal_id."
								  // ,commission_type_id = 18 
								  // ,debit = ".$data['amount']."
								  // ,created_by = 1 
								  // ,date_added = '".$this->user->now()."'";
				// $this->db->query($sql);
				// $this->log->write($sql);
				
				// $sql = "update oc_user 
						   // set ewallet = ewallet - ".$data['amount']." 
						 // where user_id = ".$this->user->getId();
				// $this->db->query($sql);
				// $this->log->write($sql);
				
				// return "Successful transaction. Please refer to withdrawal ID number ".$withdrawal_id.".";
			// } else {
				// return "Withdrawal balance is not enough.";
			// }				
			
		// } else {
			// return "Resubmission of transaction is not allowed.";
		// }

	// }	
	
	public function releaseWithdrawal($data) {
		$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $withdrawal_id) {
				$sql = "select status from oc_withdraw_hist where withdrawal_id = ".$withdrawal_id;
				$query = $this->db->query($sql);
				
				if($query->row['status'] == 72) {
					$sql = "update oc_withdraw_hist 
							   set status = 74
								  ,approval_date = '".$this->user->now()."'
							 where withdrawal_id = ".$withdrawal_id;
					$this->db->query($sql);
					$this->log->write($sql);
					$return_msg .=  "Withdrawal Id ".$withdrawal_id." is released.<br>";				
				} else if ($query->row['status'] == 156) {
					$return_msg .=  "Withdrawal Id ".$withdrawal_id." is already cancelled. Resubmission is not allowed.<br>";
				} else {
					$return_msg .=  "Withdrawal Id ".$withdrawal_id." is already processed. Resubmission is not allowed.<br>";
				}				
			}
			return $return_msg;
		} else {
			return "No withdrawal to approve";
		}
		
	}
	
	public function receiveWithdrawal($data) {
		//var_dump($data);
		$sql = "select status, amount, user_id, banker_id from oc_withdraw_hist where withdrawal_id = ".$data['withdrawal_id'];
		$query = $this->db->query($sql);
		
		if($query->row['status'] == 74) {
			$amount = $query->row['amount'] * 1.025;
			$user_id = $query->row['user_id'];
			
			$sql = "update oc_withdraw_hist 
					   set status = 76
					 where withdrawal_id = ".$data['withdrawal_id'];
			$this->db->query($sql);

			return "Withdrawal Number ".$data['withdrawal_id']." is received.";
		} else {
			return "Withdrawal is already processed. Resubmission is not allowed.";
		}
	}

	public function getWithdrawals($data, $query_type) {

		$sql = " select a.withdrawal_id, a.amount, a.amount_in_btc, a.wallet_address,
						concat(b.firstname, ' ', b.lastname) banker, a.banker_id,
						concat(c.firstname, ' ', c.lastname) requestor, a.user_id,
						d.description status, a.date_added, a.status status_id, 
						a.reference, a.account_no, e.description bank_name, a.account_name,
						a.amount_requested, a.proc_fee
				  FROM oc_withdraw_hist a 
				  LEFT JOIN oc_user b on(a.banker_id = b.user_id)
				  LEFT JOIN oc_user c on(a.user_id = c.user_id)
				  LEFT JOIN gui_status_tbl d on(a.status = d.status_id)
				  LEFT JOIN gui_status_tbl e on(a.bank_name = e.status_id)
				  WHERE 1 = 1  ";	

		if($this->user->getUserGroupId() == 56 or $this->user->getUserGroupId() == 39 
		   or $this->user->getUserGroupId() == 46 or $this->user->getUserGroupId() == 36
		   or $this->user->getUserGroupId() == 60) {
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
		
		if(isset($data['withdrawal_ids'])) {
			if (!empty($data['withdrawal_ids'])) {
				$sql .= " AND a.withdrawal_id = ".$data['withdrawal_ids'];
			}
		}
	
		if($query_type == "data") {									
			
			$sql .= " order by a.withdrawal_id desc ";
			
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
	
	public function getWithdrawalsForExport($data) {

		$sql = "select a.withdrawal_id, a.amount_requested, a.proc_fee, a.amount,UPPER(d.description) status
					  ,concat(c.firstname, ' ', c.lastname) requestor, e.description bank_name
				      ,a.account_name,a.account_no,a.date_added
				  FROM oc_withdraw_hist a 
				  LEFT JOIN oc_user b on(a.banker_id = b.user_id)
				  LEFT JOIN oc_user c on(a.user_id = c.user_id)
				  LEFT JOIN gui_status_tbl d on(a.status = d.status_id)
				  LEFT JOIN gui_status_tbl e on(a.bank_name = e.status_id)
				  WHERE 1 = 1 AND a.status = 72";	
		
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
		
		if(isset($data['withdrawal_ids'])) {
			if (!empty($data['withdrawal_ids'])) {
				$sql .= " AND a.withdrawal_id = ".$data['withdrawal_ids'];
			}
		}								
			
		$sql .= " order by a.withdrawal_id desc ";	

		$query = $this->db->query($sql);
		return $query->rows;			
	}

	public function getWithdrawalsApp($data, $query_type) {

		$sql = " select a.withdrawal_id, a.amount, a.amount_in_btc, a.wallet_address,
						concat(b.firstname, ' ', b.lastname) banker, a.banker_id,
						concat(c.firstname, ' ', c.lastname) requestor, a.user_id,
						d.description status, a.date_added, a.status status_id, 
						a.reference, a.account_no, e.description bank_name, a.account_name,
						a.amount_requested, a.proc_fee
				  FROM oc_withdraw_hist a 
				  LEFT JOIN oc_user b on(a.banker_id = b.user_id)
				  LEFT JOIN oc_user c on(a.user_id = c.user_id)
				  LEFT JOIN gui_status_tbl d on(a.status = d.status_id)
				  LEFT JOIN gui_status_tbl e on(a.bank_name = e.status_id)
				  WHERE 1 = 1  ";	
		
		if($this->user->getUserGroupId() == 39) {
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
		
		if(isset($data['withdrawal_ids'])) {
			if (!empty($data['withdrawal_ids'])) {
				$sql .= " AND a.withdrawal_id = ".$data['withdrawal_ids'];
			}
		}
	
		if($query_type == "data") {									
			
			$sql .= " order by a.withdrawal_id desc ";
			
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
	
	public function getTotalEwallet() {
		$sql = "select ewallet
				  from oc_user
				 where user_id = ".$this->user->getId();
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['ewallet'];
	}
	
	public function getTotalWithdrawal() {
		$sql = "select coalesce(sum(amount_requested),0) withdrawal
				  from oc_withdraw_hist 
				 where user_id = ".$this->user->getId()."
				   and status in(72,74,76)";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['withdrawal'];
	}
	
	public function getBankerDetails($username) {
		$return_array = array();
		$sql = "select user_id, id_no, concat(firstname, ' ', lastname) name, activation_flag,
					   email, contact
				  from oc_user 
				 where lower(username) = lower('".$username."')
				   and user_group_id = 39 ";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		
		if(isset($query->row['user_id'])) {
			$return_array['id_no'] = $query->row['id_no'];
			$return_array['name'] = $query->row['name'];
			$return_array['email'] = $query->row['email'];
			$return_array['contact'] = $query->row['contact'];
			$return_array['user_id'] = $query->row['user_id'];
			$return_array['success'] = "1";
		} else {
			$return_array['id_no'] = "Username ".$username." is not existing or is not a banker.";
			$return_array['name'] = "Not Existing";
			$return_array['email'] = "Not Existing";
			$return_array['contact'] = "Not Existing";
			$return_array['user_id'] = 0;
			$return_array['activation_flag'] = "0";
			$return_array['success'] = "0";
		}
		return $return_array;
	}
	
	public function getBankFees($type){

		if($type == "all"){

			$sql = "SELECT * FROM oc_withdrawal_fee order by withdrawal_fee_id asc";
			$query = $this->db->query($sql);

			return $query->rows;

		}else{

			$sql = "SELECT IFNULL((SELECT a.amount FROM oc_withdrawal_fee a JOIN oc_user b ON (b.bank_name = a.bank_status_id) WHERE b.user_id = " . $this->user->getId() . "),100) withdrawal_fee";
			$query = $this->db->query($sql);

			return $query->row;

		}
	}
	
	public function getBanksList(){
		$sql = "SELECT * FROM gui_status_tbl where `grouping` = 'Bank' and active = 1";
		$query = $this->db->query($sql);


		return $query->rows;
	}
	
	public function getUserAccount(){
		$sql = "SELECT bank_name, account_name, account_no, tin FROM oc_user WHERE user_id = " . $this->user->getId();
		$query = $this->db->query($sql);

		return $query->row;
	}
		
}
?>