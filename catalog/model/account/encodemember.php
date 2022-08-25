<?php
class ModelAccountEncodeMember extends Model {
	
	public function manualCreate($data) {
		/*manul creation of user*/
		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;
		
		if (isset($data['username'])) {
			if (empty($data['username'])) {
				$valid = 0;
				$return_msg .= "Username is Required.";
			} else {
				$sql = "select count(1) as total 
						  from oc_user 
						 where lower(username) = '".$this->db->escape(strtolower($data['username']))."'
						   and user_group_id in(46, 47, 56) ";
				$query = $this->db->query($sql);
				$count = $query->row['total'];
				if ($count > 0) {
					$valid = 0;
					$return_msg .= "Username is not available.<br>";
				}				
			}
		} else {
			$valid = 0;
			$return_msg .= "Username is Required.<br>";
		}	
		
		if (isset($data['sponsor'])) {
			if (empty($data['sponsor'])) {
				$valid = 0;
				$return_msg .= "Sponsor is Required.<br>";
			} else {
				$sql = "select user_id 
						  from oc_user 
						 where lower(username) = '".$this->db->escape(strtolower($data['sponsor']))."'
						   and user_group_id in(46, 47, 56) ";
				$query = $this->db->query($sql);
				if(isset($query->row['user_id'])) {
					$sponsor_id = $query->row['user_id'];
				} else {
					$valid = 0;
					$return_msg .= "Sponsor is valid.<br>";
				}				
			}
		} else {
			$valid = 0;
			$return_msg .= "Sponsor is Required.<br>";
		}		
		
		if (isset($data['firstname'])) {
			if (empty($data['firstname'])) {
				$valid = 0;
				$return_msg .= "First Name is Required.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "First Name is Required.<br>";
		}
		
		if (isset($data['lastname'])) {
			if (empty($data['lastname'])) {
				$valid = 0;
				$return_msg .= "Last Name is Required.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Last Name is Required.<br>";
		}

		if (isset($data['email'])) {
			if (empty($data['email'])) {
				$valid = 0;
				$return_msg .= "Email is Required.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Email is Required.<br>";
		}
		
		if (isset($data['contact'])) {
			if (empty($data['contact'])) {
				$valid = 0;
				$return_msg .= "Contact # is Required.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Contact # is Required.<br>";
		}
		
		if (isset($data['password'])) {
			if (empty($data['password'])) {
				$valid = 0;
				$return_msg .= "Password is Required.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Password is Required.<br>";
		}
		
		if (isset($data['user_group_id'])) {
			if ($data['user_group_id'] == 0) {
				$valid = 0;
				$return_msg .= "User Type is Required.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Password is Required.<br>";
		}
				
		if($valid == 1) {
			
			$sql  = "INSERT INTO oc_user ";
			$sql .= "   SET firstname = '".$this->db->escape($data['firstname'])."' ";
			$sql .= "      ,lastname = '".$this->db->escape($data['lastname'])."' ";
			$sql .= "      ,site = '". WEBSITE ."' ";
			$sql .= "      ,user_group_id = ".$this->db->escape($data['user_group_id'])." ";
			$sql .= "      ,username = '". $this->db->escape(strtolower($data['username'])) ."' ";
			$sql .= "      ,password = '". $this->db->escape(md5($data['password'])) ."' ";
			$sql .= "      ,status = 1 ";
			$sql .= "      ,activation_flag = 1	" ;
			$sql .= "      ,branch_id = 1 ";
			$sql .= "      ,contact = '".$this->db->escape($data['contact'])."' ";			
			$sql .= "      ,email = '".$this->db->escape($data['email'])."' ";
			$sql .= "      ,refer_by_id = '".$this->db->escape($sponsor_id)."' ";
			$sql .= "      ,date_added = '".$this->user->now()."' ";
			
			$this->db->query($sql);
			
			$user_id = $this->db->getLastId();	
			
			$sql = "insert into oc_unilevel(user_id, sponsor_user_id, level, date_added)										
				         values (".$user_id.", ".$sponsor_id.", 1, '".$this->user->now()."')";			
			$this->db->query($sql);		
			
			$sql = "insert into oc_unilevel(user_id, sponsor_user_id, level, date_added )					
							select ".$user_id.", sponsor_user_id, (level + 1), '".$this->user->now()."'						  
							  from oc_unilevel ou					 
					 where user_id = ".$sponsor_id."						   
					   and sponsor_user_id <> 0";			
			$this->db->query($sql);	
			
			$sql = "select user_group_id from oc_user where user_id = ".$sponsor_id;
			$query = $this->db->query($sql);
			$sponsor_user_group_id = $query->row['user_group_id'];
			
			$sql = "update oc_user 
				       set id_no = concat('".IDPREFIX."', '-', lpad(".$user_id.", 8, '0'))
							,ref ='".md5("EDSI-ID".$user_id)."'	";
					if($sponsor_user_group_id == 46){
						$sql .= " ,reseller_id = '".$this->db->escape($sponsor_id)."' ";
					} else if($sponsor_user_group_id == 56) {
						$sql .= " ,distributor_id = '".$this->db->escape($sponsor_id)."' ";
					}
			$sql .= " where user_id = '".$this->db->escape($user_id)."' ";
			$this->db->query($sql);
			$return_msg = "You have successfully registered user ".strtolower($data['username']).".<br>";
			return $return_msg;

		} else {
			return "Row ".$data['row']." failed due to incomplete or wrong information.<br>".$return_msg;
		}
			
	}
	
	public function getToDefine() {
		$sql = "select * from to_define order by user_id ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function manualCreate2($data) {
		//var_dump($data);
		/*manul creation of user*/
		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;
		
		if (isset($data['username'])) {
			if (empty($data['username'])) {
				$valid = 0;
				$return_msg .= "Username is Required.";
			} else {
				$sql = "select count(1) as total 
						  from oc_user 
						 where lower(username) = '".$this->db->escape(strtolower($data['username']))."'
						   and user_group_id in(46, 47, 56) ";
				$query = $this->db->query($sql);
				$count = $query->row['total'];
				if ($count > 0) {
					$valid = 0;
					$return_msg .= "Username is not available.<br>";
				}				
			}
		} else {
			$valid = 0;
			$return_msg .= "Username is Required.<br>";
		}	
		
		if (isset($data['sponsor'])) {
			if (empty($data['sponsor'])) {
				$valid = 0;
				$return_msg .= "Sponsor is Required.<br>";
			} else {
				$sql = "select user_id 
						  from oc_user 
						 where lower(username) = '".$this->db->escape(strtolower($data['sponsor']))."'
						   and user_group_id in(46, 47, 56) ";
				$query = $this->db->query($sql);
				if(isset($query->row['user_id'])) {
					$sponsor_id = $query->row['user_id'];
				} else {
					$valid = 0;
					$return_msg .= "Sponsor is invalid.<br>";
				}				
			}
		} else {
			$valid = 0;
			$return_msg .= "Sponsor is Required.<br>";
		}		
		
		if (isset($data['firstname'])) {
			if (empty($data['firstname'])) {
				$valid = 0;
				$return_msg .= "First Name is Required.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "First Name is Required.<br>";
		}
		
		if (isset($data['lastname'])) {
			if (empty($data['lastname'])) {
				$valid = 0;
				$return_msg .= "Last Name is Required.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Last Name is Required.<br>";
		}

		if (isset($data['email'])) {
			if (empty($data['email'])) {
				$valid = 0;
				$return_msg .= "Email is Required.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Email is Required.<br>";
		}
		
		if (isset($data['contact'])) {
			if (empty($data['contact'])) {
				$valid = 0;
				$return_msg .= "Contact # is Required.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Contact # is Required.<br>";
		}
		
		if (isset($data['password'])) {
			if (empty($data['password'])) {
				$valid = 0;
				$return_msg .= "Password is Required.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Password is Required.<br>";
		}
		
		if (isset($data['user_group_id'])) {
			if ($data['user_group_id'] == 0) {
				$valid = 0;
				$return_msg .= "User Type is Required.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Password is Required.<br>";
		}
				
		if($valid == 1) {
			
			$sql  = "INSERT INTO oc_user ";
			$sql .= "   SET firstname = '".$this->db->escape($data['firstname'])."' ";
			$sql .= "      ,lastname = '".$this->db->escape($data['lastname'])."' ";
			$sql .= "      ,site = '". WEBSITE ."' ";
			$sql .= "      ,user_group_id = ".$this->db->escape($data['user_group_id'])." ";
			$sql .= "      ,username = '". $this->db->escape(strtolower($data['username'])) ."' ";
			$sql .= "      ,status = 1 ";
			$sql .= "      ,activation_flag = 1	" ;
			$sql .= "      ,branch_id = 1 ";
			$sql .= "      ,contact = '".$this->db->escape($data['contact'])."' ";			
			$sql .= "      ,email = '".$this->db->escape($data['email'])."' ";
			$sql .= "      ,refer_by_id = '".$this->db->escape($sponsor_id)."' ";
			$sql .= "      ,country_id = '".$data['country_id']."' ";
			$sql .= "      ,address = '".$this->db->escape($data['address'])."' ";
			$sql .= "      ,password = '".$data['password']."' ";
			$sql .= "      ,province_id = '".$data['province_id']."' ";
			$sql .= "      ,city_municipality_id = '".$data['city_municipality_id']."' ";
			$sql .= "      ,barangay_id = '".$data['barangay_id']."' ";
			$sql .= "      ,date_added = '".$data['date_added']."' ";
			
			$this->db->query($sql);
			
			$user_id = $this->db->getLastId();	
			
			$sql = "insert into oc_unilevel(user_id, sponsor_user_id, level, date_added)										
				         values (".$user_id.", ".$sponsor_id.", 1, '".$this->user->now()."')";			
			$this->db->query($sql);		
			
			$sql = "insert into oc_unilevel(user_id, sponsor_user_id, level, date_added )					
							select ".$user_id.", sponsor_user_id, (level + 1), '".$this->user->now()."'						  
							  from oc_unilevel ou					 
					 where user_id = ".$sponsor_id."						   
					   and sponsor_user_id <> 0";			
			$this->db->query($sql);	
			
			$sql = "select user_group_id from oc_user where user_id = ".$sponsor_id;
			$query = $this->db->query($sql);
			$sponsor_user_group_id = $query->row['user_group_id'];
			
			$sql = "update oc_user 
				       set id_no = concat('".IDPREFIX."', '-', lpad(".$user_id.", 8, '0')) ";
					if($sponsor_user_group_id == 46){
						$sql .= " ,reseller_id = '".$this->db->escape($sponsor_id)."' ";
					} else if($sponsor_user_group_id == 56) {
						$sql .= " ,distributor_id = '".$this->db->escape($sponsor_id)."' ";
					}
			$sql .= " where user_id = '".$this->db->escape($user_id)."' ";
			$this->db->query($sql);
			$return_msg = "You have successfully registered user ".strtolower($data['username']).".<br>";
			return $return_msg;

		} else {
			return "Row ".$data['row']." failed due to incomplete or wrong information.<br>".$return_msg;
		}
			
	}
}
?>
