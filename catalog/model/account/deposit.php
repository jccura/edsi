<?php
class ModelAccountDeposit extends Model {
	
	public function depositInScreen($data = array()) {
		$deposit_id = 0;
		$sql = "select deposit_id from oc_deposit where session_id = '".$data['trans_session_id']."' ";
		$query = $this->db->query($sql);
		
		if(!isset($query->row['deposit_id'])) {
			$receipt_counter = 0;

			$endorseDate = $data['deposit_slip_year'] . "-" . $data['deposit_slip_month'] . "-" . $data['deposit_slip_date'];

			$sql = "select count(1) total from oc_deposit where deposit_slip_datetime = '".$endorseDate." ".$data['deposit_slip_hour'].":".$data['deposit_slip_minute'].":".$data['deposit_slip_seconds']."'";
			$query = $this->db->query($sql);
			$receipt_counter = $query->row['total'] + 1;			
			
			$sql = " insert into oc_deposit
						set user_id = ".$this->user->getId()."
						   ,mode_of_remittance = ".$data['mode_of_remittance']."
						   ,amount = ".$data['total']."
						   ,deposit_slip_datetime = '".$endorseDate." ".$data['deposit_slip_hour'].":".$data['deposit_slip_minute'].":".$data['deposit_slip_seconds']."'
						   ,receipt_counter = ".$receipt_counter."
						   ,reference = '".$data['reference']."'
						   ,status_flag = 1
						   ,session_id = '".$data['trans_session_id']."'
						   ,date_added = '".$this->user->now()."'";
			
			$this->db->query($sql);
			
			$deposit_id = $this->db->getLastId();
			
		} else {
			return "Resubmission of transaction is not allowed.";
		}
		return $deposit_id;
	}	

	public function updateFileExtension($deposit_id,$extension){
		$sql = " update oc_deposit set file_extension = '" . $extension . "' where deposit_id = ".$deposit_id;
		//echo $sql."<br>";
		$this->db->query($sql);	
	}
	
	public function getTotalEndorsedFundsForExport($data, $query_type) {

		$sql = " select sum(a.amount_deposited) as total
				  FROM oc_deposit a 
				  JOIN gui_status_tbl b ON(b.status_id = a.mode_of_remittance)
				  JOIN oc_user c ON(a.user_id = c.user_id)
				  LEFT JOIN oc_user d ON(a.approved_by = d.user_id)
				  WHERE 1 = 1
				";	
		
		if($this->user->getUserGroupId() == 13 or $this->user->getUserGroupId() == 48) {
			$sql .= " AND a.user_id = ".$this->user->getId();
			$sql .= " AND a.status_flag not in(3)";
		}

		if($this->user->getUserGroupId() == 47) {
			$sql .= " AND c.mastertrader = '".$this->user->getMasterTrader()."' ";
		}
	
		if($query_type == "data") {					

			if (!empty($data['area'])) {
				$sql .= " AND lower(c.area) LIKE '" . $this->db->escape(utf8_strtolower($data['area'])) . "%'";
			}

			if (!empty($data['mastertrader'])) {
				$sql .= " AND lower(c.mastertrader) LIKE '" . $this->db->escape(utf8_strtolower($data['mastertrader'])) . "%'";
			}
			
			if (!empty($data['date_deposited'])) {
				$sql .= " AND a.date_deposited = '" . $data['date_deposited'] . "'";
			}

			if (!empty($data['datefrom'])) {
				$sql .= " AND a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
			
			if (!empty($data['dateto'])) {
				$sql .= " AND a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}				
			
			if (!empty($data['mode_of_remittance'])) {
				$sql .= " AND a.mode_of_remittance = '" . $data['mode_of_remittance'] . "'";
			}			

			if (!empty($data['status'])) {
				$sql .= " AND a.status_flag = '" . $data['status'] . "'";
			}

			if (!empty($data['id_transaction'])) {
				$sql .= " AND a.deposit_id = '" . $data['id_transaction'] . "'";
			}		
			
			$sql .= " order by a.deposit_id desc ";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
				if ($data['limit'] < 1) {
					$data['limit'] = 5;
				}			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}		
			//echo $sql."<br>";
			$query = $this->db->query($sql);
			return $query->row['total'];			
		} else {

			if (!empty($data['area'])) {
				$sql .= " AND lower(c.area) LIKE '" . $this->db->escape(utf8_strtolower($data['area'])) . "%'";
			}

			if (!empty($data['mastertrader'])) {
				$sql .= " AND lower(c.mastertrader) LIKE '" . $this->db->escape(utf8_strtolower($data['mastertrader'])) . "%'";
			}

			if (!empty($data['received_by'])) {
				$sql .= " AND LCASE(a.received_by) LIKE '" . $this->db->escape(utf8_strtolower($data['received_by'])) . "%'";
			}

			if (!empty($data['datefrom'])) {
				$sql .= " AND a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
			
			if (!empty($data['dateto'])) {
				$sql .= " AND a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}	
			
			if (!empty($data['date_deposited'])) {
				$sql .= " AND date_deposited = '" . $data['date_deposited'] . "'";
			}
					
			if (!empty($data['mode_of_remittance'])) {
				$sql .= " AND mode_of_remittance = '" . $data['mode_of_remittance'] . "'";
			}

			if (!empty($data['status'])) {
				$sql .= " AND a.status_flag = '" . $data['status'] . "'";
			}

			if (!empty($data['id_transaction'])) {
				$sql .= " AND a.deposit_id = '" . $data['id_transaction'] . "'";
			}	
			
			$sqlt = "select count(1) total from (".$sql.") t ";
			
			$query = $this->db->query($sqlt);
			
			//echo $sqlt."<br>";
			return $query->row['total'];
		}		
		
	}
	
	public function getEndorsedFundsForExport($data, $query_type) {

		$sql = " select a.deposit_id, c.username cashier_username, concat(c.firstname, ' ', c.middlename, ' ', c.lastname) cashier_name, 
						c.area, a.amount_deposited, b.description mode_of_remittance, a.date_added,
						case when status_flag = 1 then 'For Approval' when status_flag = 2 then 'Cancelled' when status_flag = 3 then 'Deleted' when status_flag = 4 then 'Approved' when status_flag = 5 then 'Denied' else 'Others' end status,
						concat(d.firstname, ' ', d.middlename, ' ', d.lastname) approved_by
				  FROM oc_deposit a 
				  JOIN gui_status_tbl b ON(b.status_id = a.mode_of_remittance)
				  JOIN oc_user c ON(a.user_id = c.user_id)
				  LEFT JOIN oc_user d ON(a.approved_by = d.user_id)
				  WHERE 1 = 1 
				";	
		
		if($this->user->getUserGroupId() == 13 or $this->user->getUserGroupId() == 48) {
			$sql .= " AND c.area = '".$this->user->getArea()."' ";
			$sql .= " AND a.status_flag not in(3)";
		}

		if($this->user->getUserGroupId() == 14) {
			$sql .= " AND a.user_id = ".$this->user->getId();
			$sql .= " AND a.status_flag not in(3)";
		}
		
		if($this->user->getUserGroupId() == 47) {
			$sql .= " AND c.mastertrader = '".$this->user->getMasterTrader()."' ";
		}		
		
		if($query_type == "data") {					

			if (!empty($data['area'])) {
				$sql .= " AND lower(c.area) LIKE '" . $this->db->escape(utf8_strtolower($data['area'])) . "%'";
			}

			if (!empty($data['mastertrader'])) {
				$sql .= " AND lower(c.mastertrader) LIKE '" . $this->db->escape(utf8_strtolower($data['mastertrader'])) . "%'";
			}
			
			if (!empty($data['date_deposited'])) {
				$sql .= " AND a.date_deposited = '" . $data['date_deposited'] . "'";
			}

			if (!empty($data['datefrom'])) {
				$sql .= " AND a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
			
			if (!empty($data['dateto'])) {
				$sql .= " AND a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}				
			
			if (!empty($data['mode_of_remittance'])) {
				$sql .= " AND a.mode_of_remittance = '" . $data['mode_of_remittance'] . "'";
			}

			if (!empty($data['deposit_slip_datetime'])) {
				$sql .= " AND a.deposit_slip_datetime like '%" . strtolower($data['deposit_slip_datetime']) . "%'";
			}			

			if (!empty($data['status_flag'])) {
				$sql .= " AND a.status_flag = '" . $data['status_flag'] . "'";
			}

			if (!empty($data['id_transaction'])) {
				$sql .= " AND a.deposit_id = '" . $data['id_transaction'] . "'";
			}	
			
			$sql .= " order by a.deposit_id desc ";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
				if ($data['limit'] < 1) {
					$data['limit'] = 5;
				}			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}		
			//echo $sql."<br>";
			$query = $this->db->query($sql);
			return $query->rows;			
		} else {

			if (!empty($data['area'])) {
				$sql .= " AND lower(c.area) LIKE '" . $this->db->escape(utf8_strtolower($data['area'])) . "%'";
			}

			if (!empty($data['mastertrader'])) {
				$sql .= " AND lower(c.mastertrader) LIKE '" . $this->db->escape(utf8_strtolower($data['mastertrader'])) . "%'";
			}

			if (!empty($data['received_by'])) {
				$sql .= " AND LCASE(a.received_by) LIKE '" . $this->db->escape(utf8_strtolower($data['received_by'])) . "%'";
			}

			if (!empty($data['datefrom'])) {
				$sql .= " AND a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
			
			if (!empty($data['deposit_slip_datetime'])) {
				$sql .= " AND a.deposit_slip_datetime like '%" . strtolower($data['deposit_slip_datetime']) . "%'";
			}
			
			if (!empty($data['dateto'])) {
				$sql .= " AND a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}	
			
			if (!empty($data['date_deposited'])) {
				$sql .= " AND date_deposited = '" . $data['date_deposited'] . "'";
			}
					
			if (!empty($data['mode_of_remittance'])) {
				$sql .= " AND mode_of_remittance = '" . $data['mode_of_remittance'] . "'";
			}

			if (!empty($data['status'])) {
				$sql .= " AND a.status_flag = '" . $data['status'] . "'";
			}

			if (!empty($data['id_transaction'])) {
				$sql .= " AND a.deposit_id = '" . $data['id_transaction'] . "'";
			}	
			
			$sqlt = "select count(1) total from (".$sql.") t ";
			
			$query = $this->db->query($sqlt);
			
			//echo $sqlt."<br>";
			return $query->row['total'];
		}		
		
	}
	
	public function getEndorsedFunds($data, $query_type) {

		$sql = " select a.*, b.description mode_of_rem_desc, c.username cashier_username,
						concat(c.firstname, ' ', c.middlename, ' ', c.lastname) cashier_name, 
						concat(d.firstname, ' ', d.middlename, ' ', d.lastname) approved_by_name,
						case when status_flag = 1 then 'For Approval' 
						     when status_flag = 2 then 'Cancelled' 
							 when status_flag = 3 then 'Deleted' 
							 when status_flag = 4 then 'Approved' 
							 when status_flag = 5 then 'Denied' 
							 else 'Others' end status
				  FROM oc_deposit a 
				  JOIN gui_status_tbl b ON(b.status_id = a.mode_of_remittance)
				  JOIN oc_user c ON(a.user_id = c.user_id)
				  LEFT JOIN oc_user d ON(a.approved_by = d.user_id)
				  WHERE 1 = 1  ";	

		if($this->user->getUserGroupId() == 13 or $this->user->getUserGroupId() == 39 or $this->user->getUserGroupId() == 40) {
			$sql .= " AND a.user_id = ".$this->user->getId();
			$sql .= " AND a.status_flag not in(3)";
		}
		
		//echo $sql."<br>";
	
		if($query_type == "data") {					
			
			if (!empty($data['date_deposited'])) {
				$sql .= " AND a.date_deposited = '" . $data['date_deposited'] . "'";
			}

			if (!empty($data['datefrom'])) {
				$sql .= " AND a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
			
			if (!empty($data['dateto'])) {
				$sql .= " AND a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}				
			
			if (!empty($data['mode_of_remittance'])) {
				$sql .= " AND a.mode_of_remittance = '" . $data['mode_of_remittance'] . "'";
			}	

			if (!empty($data['status_flag'])) {
				$sql .= " AND a.status_flag = '" . $data['status_flag'] . "'";
			}	

			if (!empty($data['receipt_counter'])) {
				$sql .= " AND a.receipt_counter = '" . $data['receipt_counter'] . "'";
			}				

			if (!empty($data['deposit_slip_datetime'])) {
				$sql .= " AND a.deposit_slip_datetime like '%" . strtolower($data['deposit_slip_datetime']) . "%'";
			}	
			
			if (!empty($data['reference'])) {
				$sql .= " AND a.reference like '%" . strtolower($data['reference']) . "%'";
			}

			if (!empty($data['id_transaction'])) {
				$sql .= " AND a.deposit_id = '" . $data['id_transaction'] . "'";
			}				
			
			$sql .= " order by a.deposit_id desc ";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
				if ($data['limit'] < 1) {
					$data['limit'] = 5;
				}			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}		
			//echo $sql."<br>";
			$query = $this->db->query($sql);
			return $query->rows;			
		} else {

			if (!empty($data['area'])) {
				$sql .= " AND lower(c.area) LIKE '" . $this->db->escape(utf8_strtolower($data['area'])) . "%'";
			}

			if (!empty($data['mastertrader'])) {
				$sql .= " AND lower(c.mastertrader) LIKE '" . $this->db->escape(utf8_strtolower($data['mastertrader'])) . "%'";
			}

			if (!empty($data['received_by'])) {
				$sql .= " AND LCASE(a.received_by) LIKE '" . $this->db->escape(utf8_strtolower($data['received_by'])) . "%'";
			}

			if (!empty($data['datefrom'])) {
				$sql .= " AND a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
			
			if (!empty($data['dateto'])) {
				$sql .= " AND a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}	
			
			if (!empty($data['date_deposited'])) {
				$sql .= " AND date_deposited = '" . $data['date_deposited'] . "'";
			}
					
			if (!empty($data['mode_of_remittance'])) {
				$sql .= " AND mode_of_remittance = '" . $data['mode_of_remittance'] . "'";
			}

			if (!empty($data['status_flag'])) {
				$sql .= " AND a.status_flag = '" . $data['status_flag'] . "'";
			}	

			if (!empty($data['deposit_slip_datetime'])) {
				$sql .= " AND a.deposit_slip_datetime like '%" . strtolower($data['deposit_slip_datetime']) . "%'";
			}	
			
			if (!empty($data['reference'])) {
				$sql .= " AND a.reference like '%" . strtolower($data['reference']) . "%'";
			}

			if (!empty($data['id_transaction'])) {
				$sql .= " AND a.deposit_id = '" . $data['id_transaction'] . "'";
			}
			
			$sqlt = "select count(1) total from (".$sql.") t ";
			
			$query = $this->db->query($sqlt);
			
			//echo $sqlt."<br>";
			return $query->row['total'];
		}		
		
	}
	
	public function deleteRemittance($deposit_id) {			
		$sql = " update oc_deposit set status_flag = 3 where deposit_id = ".$deposit_id;
		//echo $sql."<br>";
		$this->db->query($sql);		
	}

	public function cancelRemittance($deposit_id) {	
		$sql = "select count(1) total from oc_deposit where deposit_id = ".$deposit_id." and status_flag = 1 ";
		$query = $this->db->query($sql);
		
		if($query->row['total'] > 0) {		
			$sql = " update oc_deposit set status_flag = 2 where deposit_id = ".$deposit_id;
			//echo $sql."<br>";
			$this->db->query($sql);	
			$this->returnEwallet($deposit_id);
			return "Succesful cancel of Endorsed Fund ID# ". $deposit_id .".";
		} else {
			return "You can only cancel endorsement that is for approval. Endorsement ID# ".$deposit_id." is not for approval.";
		}			
	}	

	public function denyRemittance($data) {	
		if(isset($data['deposit_id'])) {
			if(!empty($data['deposit_id'])) {
				$deposit_id = $data['deposit_id'];
			} else {
				$deposit_id = 0;
			}
		} else {
			$deposit_id = 0;
		}
	
		$sql = "select count(1) total from oc_deposit where deposit_id = ".$deposit_id." and status_flag = 1 ";
		$query = $this->db->query($sql);
		
		if($query->row['total'] > 0) { 
			//echo "denyRemittance<br>";
			$notes = "";
			if(!isset($data['checker_order_table'])) {
				$notes .= "Order table did not appear.<br>";
			}
			if(!isset($data['checker_image_visibility'])) {
				$notes .= "Image is not clear.<br>";
			}
			if(!isset($data['checker_mop'])) {
				$notes .= "Mode of Payment does not match the image.<br>";
			}
			if(!isset($data['checker_trans_mark'])) {
				$notes .= "Trasaction Mark does not match the image.<br>";
			}
			if(!isset($data['checker_date'])) {
				$notes .= "Date does not match the image.<br>";
			}
			if(!isset($data['checker_time'])) {
				$notes .= "Time does not match the image.<br>";
			}
			if(!isset($data['did_not_use_mil_time'])) {
				$notes .= "Follow military time, 24hour format(1am is 01:00:00 and 1pm is still 01:00:00).<br>";
			}			
			if(!isset($data['checker_amount'])) {
				$notes .= "Inputted amount is bigger than the actual amount in the image.<br>";
			}				
			if(!isset($data['ref_number'])) {
				$notes .= "Reference Number does not match or is not visible.<br>";
			}			
			//echo $notes."<br>";
				
			
			$sql = " update oc_deposit set  reference = '".$data['reference']."', status_flag = 5, notes = '".$notes."' where deposit_id = ".$data['deposit_id'];
			//echo $sql."<br>";
			$this->db->query($sql);
			
			$this->returnEwallet($data['deposit_id']);	
			
			return "Succesful denial of Endorsed Fund ID# ".$data['deposit_id'].".";
		} else {
			return "You can only deny endorsement that is for approval. Endorsement ID# ".$deposit_id." is not for approval.";
		}
	}
	
	public function returnEwallet($deposit_id) {
		
		$sql = "select mode_of_remittance, status_flag from oc_deposit where deposit_id = ".$deposit_id;
		$query = $this->db->query($sql);
		$mode_of_remittance =  $query->row['mode_of_remittance'];
		$status_flag =  $query->row['status_flag'];

		if($mode_of_remittance == 54 && ($status_flag == 2 or $status_flag == 5)) {
			$sql = "select cashier_rem_det_id, user_id, trade_amt, topup_amt, act_fee from oc_cashier_remittance_det where deposit_id = ".$deposit_id;
			$query = $this->db->query($sql);
			$ewallet_amount_for_return = $query->rows;

			foreach($ewallet_amount_for_return as $eafr) {
				$total_for_return = 0;
				
				$sql = "update oc_user set ewallet_to_tr = ewallet_to_tr - ".$eafr['trade_amt'].",
                                           ewallet_to_mcr = ewallet_to_mcr - ".$eafr['topup_amt'].",
                                           ewallet_to_act_fee = ewallet_to_act_fee - ".$eafr['act_fee']."										   
				         where user_id = ".$eafr['user_id'];
				$this->db->query($sql);
				//echo $sql."<br>";
				$total_for_return = $eafr['trade_amt'] + $eafr['topup_amt'] + $eafr['act_fee'];
				
				$sql = "insert into oc_ewallet_hist(event, debit_credit, source_user_id, user_id, ewallet, date_added, session_id, deposit_id) ";
				$sql.= " values('RETURN TO EWALLET', 'CREDIT', ".$eafr['user_id'].",".$eafr['user_id'].",".$total_for_return.",'".$this->user->now()."', '".$this->user->now()."', ".$deposit_id.")";
				$this->db->query($sql);	
				//echo $sql."<br>";
			}
		}
	}
	
	public function approveRemittance($data) {
		//var_dump($data);
		$deposit_id = $data['deposit_id'];
		$sql = "select user_id, amount, status_flag from oc_deposit where deposit_id = ".$deposit_id;
		$query = $this->db->query($sql);
		$user_id = $query->row['user_id'];
		$status_flag = $query->row['status_flag'];
		$amount_deposited = $query->row['amount'];
		
		
		if($status_flag == 1) {
			
			$sql = " update oc_deposit set status_flag = 4, approved_by = ".$this->user->getId().", approved_date = '".$this->user->now()."' where deposit_id = ".$deposit_id;
			$this->db->query($sql);
			
			$sql = "select refer_by_id, activation_flag from oc_user where user_id = ".$user_id;
			$query = $this->db->query($sql);
			$refer_by_id = $query->row['refer_by_id'];
			$activation_flag = $query->row['activation_flag'];
			
			if($activation_flag == 0) {
				if($refer_by_id > 0) {
					//50 pesos direct referral
					$this->insertCommission($refer_by_id, $user_id, 1, 1, 50, $deposit_id);
					//10 pesos indirect referral
					$sql = "select sponsor_user_id, level from oc_unilevel 
							 where user_id = ".$refer_by_id." 
							   and level <= 5 
							   and sponsor_user_id > 0";
					$query = $this->db->query($sql);
					$referrors2ndLevel = $query->rows;
					foreach($referrors2ndLevel as $ref) {
						$this->insertCommission($ref['sponsor_user_id'], $user_id, 2, $ref['level'], 10, $deposit_id);
					}
				}
				
				$sql = "update oc_user set paid_flag = 1, activation_flag = 1, user_group_id = 39 where user_id = ".$user_id;
				$this->db->query($sql);
				
				if($amount_deposited > 500) {
					$this->insertCommission($user_id, $user_id, 3, 1, ($amount_deposited - 500), $deposit_id);
				}
			} else {
				$this->insertCommission($user_id, $user_id, 3, 1, $amount_deposited, $deposit_id);
			}
									
			return "Successful Approval of Deposit ID#".$deposit_id;
		} else {
			return "Endorsement can only be approved once.";
		}
	}
	
	public function getEndorsedFundDetails($deposit_id) {
		$sql = " select b.username, concat(b.firstname, ' ', b.middlename, ' ', b.lastname) name, a.trade_amt, a.topup_amt, a.act_fee, a.receipt_id
				   from oc_cashier_remittance_det a	
				   join oc_user b on (a.user_id = b.user_id)
				  where a.deposit_id = ".$deposit_id;
		$query = $this->db->query($sql);		  
		return $query->rows;
	}
	
	public function getEndorsedFundHeader($deposit_id) {
		$sql = " select a.deposit_id, a.amount_deposited, b.username, concat(b.firstname, ' ', b.middlename, ' ', b.lastname) name, 
		                a.approved_by, a.status_flag, a.deposit_slip_datetime, a.receipt_counter, a.reference, a.notes, c.description mop,
						a.mode_of_remittance, a.file_extension
				   from oc_deposit a
				   join oc_user b on (a.user_id = b.user_id)
				   left join gui_status_tbl c on(a.mode_of_remittance = c.status_id)
				  where a.deposit_id = ".$deposit_id;
		$query = $this->db->query($sql);		  
		return $query->row;
	}

	public function Random() {
		//To Pull 7 Unique Random Values Out Of AlphaNumeric

		//removed number 0, capital o, number 1 and small L
		//Total: keys = 32, elements = 33
		$random_chars = "";
		$characters = array(
		"A","B","C","D","E","F","G","H","J","K","L","M",
		"N","P","Q","R","S","T","U","V","W","X","Y","Z",
		"1","2","3","4","5","6","7","8","9");

		//make an "empty container" or array for our keys
		$keys = array();

		//first count of $keys is empty so "1", remaining count is 1-6 = total 7 times
		while(count($keys) < 10) {
			//"0" because we use this to FIND ARRAY KEYS which has a 0 value
			//"-1" because were only concerned of number of keys which is 32 not 33
			//count($characters) = 33
			$x = mt_rand(0, count($characters)-1);
			if(!in_array($x, $keys)) {
			   $keys[] = $x;
			}
		}

		foreach($keys as $key){
		   $random_chars .= $characters[$key];
		}
		return $random_chars;
	}

	public function commission_mtgt($user_id, $amount, $deposit_id) {
		$sql = "select user_group_id from oc_user where user_id = " . $user_id;
		$query = $this->db->query($sql);
		$user_group_id = $query->row['user_group_id'];

		if($user_group_id == 47) {
			$this->insertCommission($user_id, $user_id, 6, 0, $amount * 0.003, $deposit_id);
			
			$sql = "SELECT b.user_id FROM oc_user a 
					JOIN oc_user b ON (b.area = CONCAT(substr(a.area, 1, 3), '1') 
					AND a.user_id <> b.user_id) WHERE a.user_id = ".$user_id." AND b.user_group_id = 48";						
			$result = $this->db->query($sql);
			$grouptrader = $result->row['user_id'];
			
			$this->insertCommission($grouptrader, $user_id, 6, 0, $amount * 0.003, $deposit_id);			
		} else {
			$sql = "SELECT b.user_id FROM oc_user a 
					  JOIN oc_user b ON (b.mastertrader = a.mastertrader) 
					 WHERE a.user_id = ".$user_id." AND b.user_group_id = 47";
			$query = $this->db->query($sql);
			$mastertrader = $query->row['user_id'];
			$this->insertCommission($mastertrader, $user_id, 6, 0, $amount * 0.003, $deposit_id);
			
			$sql = "SELECT b.user_id FROM oc_user a 
					  JOIN oc_user b ON (b.area = a.area) 
					 WHERE a.user_id = ".$user_id." AND b.user_group_id = 48";					
			$query = $this->db->query($sql);
			$grouptrader = $query->row['user_id'];
			$this->insertCommission($grouptrader, $user_id, 6, 0, $amount * 0.003, $deposit_id);
		}
		
	}
	
	public function insertCommission($user_id, $source_user_id, $com_type_id, $level, $amount, $deposit_id = 0) {
		
		$sql = "update oc_user set ewallet = ewallet + ".$amount." where user_id = ".$user_id;
		$this->db->query($sql);
		
		$sql = "insert into oc_ewallet_hist
				   set user_id = ".$user_id.", 
					   source_user_id = ".$source_user_id.", 
					   commission_type_id = ".$com_type_id.", 
					   level = ".$level.", 
					   credit = ".$amount.", 
					   date_added = '".$this->user->now()."'";
					   
		if($deposit_id != 0) {
			$sql .= ", deposit_id = ".$deposit_id;
		}
		
		$this->db->query($sql);	
	}	

	
	public function getReferror($user_id) {
		$referror = 0;		
		$sql = "select refer_by_id from oc_user where user_id = ".$user_id;
		$query = $this->db->query($sql);
		if(isset($query->row['refer_by_id'])) {
			$referror = $query->row['refer_by_id'];
		}
		return $referror;
	}

	public function checkapproval($data) {
		
		$return = array();
		$msg = "";
		$valid = 1;
		
		//$return['data'] = $data;
		if(!empty($data['reference'])) {
			$sql = "select deposit_id from oc_deposit 
					 where reference like '%".$data['reference']."%' 
					   and mode_of_remittance = ".$data['mode_of_remittance']." 
					   and deposit_id <> ".$data['deposit_id'];
			$query = $this->db->query($sql);
			
			if(isset($query->rows)) {
				foreach($query->rows as $remittance) {
					$msg .= "Check remittance id ".$remittance['deposit_id'].", it has the same reference number and mode of payment.<br>";
					$valid = 0;
				}
			}
		} 		

		$sql = "select deposit_id from oc_deposit 
			     where deposit_slip_datetime = '".$data['deposit_slip_datetime']."' 
				   and mode_of_remittance = ".$data['mode_of_remittance']." 
				   and deposit_id <> ".$data['deposit_id'];
		$query = $this->db->query($sql);
		
		//echo $sql."<br>";
		if(isset($query->rows)) {
			foreach($query->rows as $remittance) {
				$msg .= "Check remittance id ".$remittance['deposit_id'].", it has the same date and mode of payment.<br>";
				$valid = 0;
			}
		} 
		
		$sql = "select count(1) as total from oc_deposit where deposit_id = ".$data['deposit_id'];
		$query = $this->db->query($sql);
		if($query->row['total'] == 0) {
			$msg .= "There is no details in the table.<br>";
			$valid = 0;
		} 

		$sql = "select b.cashier_rem_det_id, b.user_id, b.trade_amt, b.topup_amt, b.act_fee 
				  from oc_deposit a
				  join oc_cashier_remittance_det b on(a.deposit_id = b.deposit_id)
				 where a.deposit_id = ".$data['deposit_id']."
				   and a.mode_of_remittance = ".$data['mode_of_remittance']."";
		$query = $this->db->query($sql);
		if(isset($query->rows)) {
			foreach($query->rows as $rem_det) {
				if($rem_det['trade_amt'] > 0) {
					$sql = "select deposit_id from oc_cashier_remittance_det where user_id = ".$rem_det['user_id']." and cashier_rem_det_id <> ".$rem_det['cashier_rem_det_id']." and trade_amt = ".$rem_det['trade_amt'];
					$query = $this->db->query($sql);
					if(isset($query->rows)) {
						foreach($query->rows as $det) {
							$msg .= "Check remittance id ".$det['deposit_id'].", same user is endorsed with the same trade amount and mode of payment.<br>";
							$valid = 0;
						}
					}
				}
				
				if($rem_det['topup_amt'] > 0) {
					$sql = "select deposit_id from oc_cashier_remittance_det where user_id = ".$rem_det['user_id']." and cashier_rem_det_id <> ".$rem_det['cashier_rem_det_id']." and topup_amt = ".$rem_det['topup_amt'];
					$query = $this->db->query($sql);
					if(isset($query->rows)) {
						foreach($query->rows as $det) {
							$msg .= "Check remittance id ".$det['deposit_id'].", same user is endorsed with the same top up amount and mode of payment.<br>";
							$valid = 0;
						}
					}
				}

				if($rem_det['act_fee'] > 0) {
					
					$sql = "select activation_flag from oc_user where user_id = ".$rem_det['user_id'];
					$query = $this->db->query($sql);
					
					if(isset($query->row['activation_flag'])) {
						if($query->row['activation_flag'] == 1) {
							$msg .= "User is already active.<br>";
							$valid = 0;							
						}
					}
					
					$sql = "select deposit_id from oc_cashier_remittance_det where user_id = ".$rem_det['user_id']." and cashier_rem_det_id <> ".$rem_det['cashier_rem_det_id']." and act_fee = ".$rem_det['act_fee'];
					$query = $this->db->query($sql);
					if(isset($query->rows)) {
						foreach($query->rows as $det) {
							$msg .= "Check remittance id ".$det['deposit_id'].", same user is endorsed with the same activation amount and mode of payment.<br>";
							$valid = 0;
						}
					}
				}				
			}
		}
		
		$return['valid'] = $valid ;
		$return['msg'] = $msg;
		return $return;
	}

	public function updateDateTime($data){
		$endorseDate = $data['deposit_slip_year'] . "-" . $data['deposit_slip_month'] . "-" . $data['deposit_slip_date'];

		$sql = "update oc_deposit set deposit_slip_datetime =  '".$endorseDate." ".$data['deposit_slip_hour'].":".$data['deposit_slip_minute'].":".$data['deposit_slip_seconds']."' where deposit_id = " . $data['header_rem_id'];

		$this->db->query($sql);

	}
	
}
?>