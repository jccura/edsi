<?php
class ModelAccountInstawin extends Model {
	
	public function getInstaWinsAttempts($data, $query_type) {

		$sql = " select a.instawin_hist_id, a.date_added, a.amount, 
						case when winner = 1 then 'Win' else 'Lose' end status
				  FROM oc_instawin_hist a 
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
			
			$sql .= " order by a.instawin_hist_id desc ";
			
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
	
	public function getWins() {
		$sql = "select coalesce(count(1) * 40,0) wins
				  from oc_instawin_hist 
				 where user_id = ".$this->user->getId()."
				   and winner = 1 ";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		return $query->row['wins'];
	}
	
	public function attemptInstawin($data = array()) {
		$returnArray = array();
		$returnArray['success'] = "Success";
		
		$sql = "select concat(firstname , ' ', middlename , ' ' , lastname) as fullname, epoints, used_epoints from oc_user where user_id = ".$this->user->getId();
		$query = $this->db->query($sql);
		$current_epoints = $query->row['epoints'] - $query->row['used_epoints'];
		$fullname = $query->row['fullname'];
		if($current_epoints >= $data['type']) {
			
			$sql = "update oc_user set epoints = epoints - ".$data['type'].", used_epoints = used_epoints + ".$data['type']." where user_id = ".$this->user->getId();
			$this->db->query($sql);
			
			if($this->user->getUserGroupId() == 13) {
				$sql = "update oc_user set activation_flag = 1 where user_id = ".$this->user->getId();
				$this->db->query($sql);
				$this->user->setActivationFlag(1);
			} 
			
			$sql = "select credit from oc_instawin where amount = ".$data['type'];
			$query = $this->db->query($sql);
			$credit = $query->row['credit'];
			$type_winner = 3 * $data['type'];
			
			if($credit == $type_winner) {
				
				$sql = "insert into oc_instawin_hist 
					   set user_id = ".$this->user->getId()."
					      ,amount = ".$data['type']."
						  ,winner = 1
						  ,date_added = '".$this->user->now()."'";
				$this->db->query($sql);
				
				$instawin_hist_id = $this->db->getLastId();
				
				$sql = "update oc_instawin set credit = 0, iteration = iteration + 1 where amount = ".$data['type'];
				$this->db->query($sql);
				$winning_profit = $data['type'] / 50 * 2;
				
				$sql = "update oc_user set ewallet = ewallet + ".$winning_profit." where user_id = ".$this->user->getId();
				$this->db->query($sql);
								
				$sql = "insert into oc_ewallet_hist
								   set user_id = ".$this->user->getId()."
									  ,source_user_id = ".$this->user->getId()."
									  ,instawin_hist_id = ".$instawin_hist_id."
									  ,commission_type_id = 11 
									  ,credit = ".$winning_profit."
									  ,created_by = 1 
									  ,date_added = '".$this->user->now()."'";
				$this->db->query($sql);
				
				$returnArray['type_winner'] = $type_winner;
				$returnArray['winner'] = "1";
				$returnArray['msg'] = "<center><h1 style='color:green; font-weight: bold;'>You win!</h1></center><br><h4>$".$winning_profit." has been credited to your ewallet.</h4>";
					
			} else {
				
				$sql = "insert into oc_instawin_hist 
					   set user_id = ".$this->user->getId()."
					      ,amount = ".$data['type']."
						  ,winner = 0
						  ,date_added = '".$this->user->now()."'";
				$this->db->query($sql);
				
				$instawin_hist_id = $this->db->getLastId();
				
				$sql = "update oc_instawin set credit = credit + ".$data['type']." where amount = ".$data['type'];
				$this->db->query($sql);
				
				$returnArray['type_winner'] = $type_winner;
				$returnArray['winner'] = "0";
				$returnArray['msg'] = "<center><h1 style='color:red; font-weight: bold;'>You lose!</h1>";			
			}
			
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
									  ,instawin_hist_id = ".$instawin_hist_id."
									  ,commission_type_id = 1 
									  ,level = 1
									  ,credit = 1.00
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
										  ,instawin_hist_id = ".$instawin_hist_id."
										  ,commission_type_id = ".$mf2['level']." 
										  ,level = ".$mf2['level']."
										  ,credit = 0.10
										  ,created_by = 1 
										  ,date_added = '".$this->user->now()."'";
					$this->db->query($sql);	
				}
			}
			
			$sql = " insert into oc_epoints_hist 
						set user_id = ".$this->user->getId()."
						   ,source_user_id = ".$this->user->getId()."
						   ,instawin_hist_id = ".$instawin_hist_id."
						   ,debit = ".$data['type']."
						   ,commission_type_id = 20
						   ,date_added = '".$this->user->now()."'";
			$this->db->query($sql);
			
		} else {
			$returnArray['msg'] = "You don't have enough ecoins.";			
		}					
		return $returnArray;
	}
	
}
?>