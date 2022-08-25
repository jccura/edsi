<?php
class ModelAccountregister extends Model {
	
	public function addManualMember($data){
		$return_array = array();
		$valid = 1;
		$return_msg = "Row #".$data['row']." result: ";
		$count = 0;
		$sql = "SELECT count(1) as total from oc_user where lower(username) = '".strtolower($data['username'])."'";
		$query = $this->db->query($sql);
		$count = $query->row['total'];
		if ($count > 0) {
			$valid = 0;
			$return_msg .= "Username (".$data['username'].") is already used. ";
		}
		$sql = "select user_id, teamlead_id, coordinator_id from oc_user where lower(username) =  '".strtolower(trim($data['sponsor']))."'" ;
		$query = $this->db->query($sql);
		$sponsor_id = 0;
		$teamlead_id = 0;
		$coordinator_id = 0;
		$err_counter = 0;
		if(isset($query->row['user_id'])){
			if($query->row['user_id'] == 0) {
				$valid = 0;
				$return_msg .= "Sponsor given is not valid. ";
			} else {
				$sponsor_id =  $query->row['user_id'];
				$teamlead_id =  $query->row['teamlead_id'];
				$coordinator_id =  $query->row['coordinator_id'];
			}
		} else {
			$valid = 0;
			$return_msg .= "Sponsor given is not valid. ";
		}		
		
		if(isset($data['username'])) {			
			if(empty($data['username'])) {				
				$valid = 0;	
				$err_counter += 1;				
				$return_msg .= "Username is mandatory. ";			
			}		
		} else {			
			$valid = 0;		
			$err_counter += 1;
			$return_msg .= "Username is mandatory. ";		
		}	
		
		if(isset($data['firstname'])) {			
			if(empty($data['firstname'])) {				
				$valid = 0;
				$err_counter += 1;
				$return_msg .= "Firstname is mandatory. ";			
			}		
		} else {			
			$valid = 0;
			$err_counter += 1;
			$return_msg .= "Firstname is mandatory. ";		
		}
		
		if(isset($data['middlename'])) {
			if(empty($data['middlename'])) {
				$valid = 0;
				$err_counter += 1;
				$return_msg .= "Middlename is mandatory. ";
			}
		} else {
			$valid = 0;
			$err_counter += 1;
			$return_msg .= "Middlename is mandatory. ";
		}
		
		if(isset($data['lastname'])) {
			if(empty($data['lastname'])) {
				$valid = 0;
				$err_counter += 1;
				$return_msg .= "Lastname is mandatory. ";
			}
		} else {
			$valid = 0;
			$err_counter += 1;
			$return_msg .= "Lastname is mandatory. ";
		}
		
		if(isset($data['mobile'])) {
			if(empty($data['mobile'])) {
				$valid = 0;
				$err_counter += 1;
				$return_msg .= "Phone is mandatory. ";
			}
		} else {
			$valid = 0;
			$err_counter += 1;
			$return_msg .= "Phone is mandatory. ";
		}
		
		if(isset($data['email'])) {
			if(empty($data['email'])) {
				$valid = 0;
				$err_counter += 1;
				$return_msg .= "Email is mandatory. ";
			}
		} else {
			$valid = 0;
			$err_counter += 1;
			$return_msg .= "Email is mandatory. ";
		}
		
		if($valid == 1){			
			$secret_code = $this->user->Random(6);
			$sql  = "INSERT INTO oc_user 
						SET username ='".$this->db->escape(strtolower($data['username']))."' 
						   ,password = '" . $this->db->escape(md5($data['password'])) . "'
						   ,firstname = UCASE('" . $data['firstname'] . "')						   
						   ,middlename = UCASE('".$data['middlename']."') 
						   ,lastname = UCASE('" . $data['lastname'] . "')
						   ,contact =  '". $data['mobile']."' 
						   ,user_group_id = 39
						   ,gender = 'M' 					   
						   ,refer_by_id = ".$sponsor_id." 
						   ,teamlead_id = ".$teamlead_id." 						   
						   ,coordinator_id = ".$coordinator_id." 						   
						   ,email = '".$data['email']."' 						   
						   ,code = '".$secret_code."' 						   
						   ,status = 1 
						   ,paid_flag = 1						   
						   ,activation_flag = 1						   
						   ,sme_flag = 1						   
						   ,date_added = '".$this->user->now()."' 						   
						   ,site = '".WEBSITE."'";
			

			if(isset($data['address'])) {
				if($data['address'] != "") {
					$sql .= " address = '".$this->db->escape($data['address'])."'";
				}
			}
			
			if(isset($data['province_id'])) {
				if($data['province_id'] > 0) {
					$sql .= " province_id = ".$data['province_id'];
				}
			}
			
			if(isset($data['city_municipality_id'])) {
				if($data['city_municipality_id'] > 0) {
					$sql .= " city_municipality_id = ".$data['city_municipality_id'];
				}
			}
			
			if(isset($data['barangay_id'])) {
				if($data['barangay_id'] > 0) {
					$sql .= " barangay_id = ".$data['barangay_id'];
				}
			}
			
			$this->db->query($sql);						
			$user_id = $this->db->getLastId();	
			
			if(isset($data['autoactivate'])) {
				if($data['autoactivate'] == 1) {
					$sql = "update oc_user 
							   set activation_flag = 1
								  ,user_group_id = 39 
							 where user_id = ".$user_id;
					$this->db->query($sql);
				}
			}

			$sql = "update oc_user 
					   set id_no = '".IDPREFIX.$user_id."' 
					       ,ref = '".md5("EDSI-ID".$user_id)."'  
					 where user_id = ".$user_id;
			$this->db->query($sql);
			
			$sql = "insert into oc_unilevel(user_id, sponsor_user_id, level, date_added)										
				         values (".$user_id.", ".$sponsor_id.", 1, '".$this->user->now()."')";			
			$this->db->query($sql);		
			
			$sql = "insert into oc_unilevel(user_id, sponsor_user_id, level, date_added )					
				    select ".$user_id.", sponsor_user_id, (level + 1), '".$this->user->now()."'						  
					from oc_unilevel ou					 
					where user_id = ".$sponsor_id."						   
					and sponsor_user_id <> 0";			
			$this->db->query($sql);				
			$return_msg = "Row #".$data['row']." result: Registration Successful for username ".$data['username'].".";			
		} else {
			if($err_counter == 6) {
				$return_msg = "Row #".$data['row']." result: No Input";
			}
		}			
		
		$return_array['result'] = $valid;		
		$return_array['result_msg'] = $return_msg;		
		return $return_array;	
	}
	
	public function addMember($data){
		
		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;
		$sql = "SELECT count(1) as total 
				  from oc_user 
				 where lower(username) = '".strtolower($data['username'])."'";
		$query = $this->db->query($sql);
		$count = $query->row['total'];
		if ($count > 0) {
			$valid = 0;
			$return_msg .= "Username is not available.<br>";
		}
		$sql = "select user_id, teamlead_id, coordinator_id ,user_group_id
				  from oc_user 
				 where lower(username) =  '".strtolower(trim($data['sponsor']))."'" ;
		$query = $this->db->query($sql);
		$sponsor_id = 0;
		$user_group = $query->row['user_group_id'];
		$teamlead_id = 0;
		$coordinator_id = 0;
		if(isset($query->row['user_id'])){
			if($query->row['user_id'] == 0) {
				$valid = 0;
				$return_msg .= "Sponsor given is not valid.<br>";
			} else {
				if($user_group == 56 or $user_group == 46) {
					$sponsor_id =  $query->row['user_id'];
					$teamlead_id =  $query->row['teamlead_id'];
					$coordinator_id =  $query->row['coordinator_id'];
				} else {
					$valid = 0;
					$return_msg .= "Sponsor given is not valid. <br>";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "Sponsor given is not valid.<br>";
		}		
		
		if(isset($data['username'])) {			
			if(empty($data['username'])) {				
				$valid = 0;				
				$return_msg .= "Username is mandatory.<br>";			
			} else {
				$username = $data['username'];
				if ($username == trim($username) && strpos($username, ' ') !== false) {
					$valid = 0;
					$return_msg .= "Username only accepts alphanumeric character.<br>";
				} else if (!preg_match('/^(?=[a-z]{2})(?=.{4,26})(?=[^.]*\.?[^.]*$)(?=[^_]*_?[^_]*$)[\w.]+$/iD', $username)) {
					$valid = 0;
					$return_msg .= "Username only accepts alphanumeric character.<br>";
				}
			}	
		} else {			
			$valid = 0;			
			$return_msg .= "Username is mandatory.<br>";		
		}

		if(isset($data['serial_code'])) {			
			if(empty($data['serial_code'])) {				
				$valid = 0;	
				$err_counter += 1;				
				$return_msg .= "Serial Code is mandatory.<br> ";			
			} else {
				$sql = "select count(1) total, used_flag, item_id, serial_id, order_id 
						  from oc_serials 
						 where lower(serial_code) = '".strtolower($this->db->escape($data['serial_code']))."'";
				$query = $this->db->query($sql);
				$serial_details = $query->row;
				if($serial_details['total'] == 0) {
					$valid = 0;					
					$return_msg .= "Serial Code is not existing.<br> ";
				} else {
					if($serial_details['used_flag'] == 1) {
						$valid = 0;				
						$return_msg .= "Serial Code is already used. <br>";
					} else {
						$item_id = $serial_details['item_id'];
						$serial_id = $serial_details['serial_id'];
						$order_id = $serial_details['order_id'];
						$sql = "select user_group_id, direct_referral ,package_type
								  from gui_items_tbl 
								 where item_id = ".$item_id;
						$query = $this->db->query($sql);
						$user_group_id = $query->row['user_group_id'];
						$package_type = $query->row['package_type'];
						$direct_referral = $query->row['direct_referral'];
					}
				}
			}		
		} else {			
			$valid = 0;		
			$return_msg .= "Serial Code is mandatory.<br> ";		
		}
		
		if(isset($data['username'])) {			
			if(empty($data['username'])) {				
				$valid = 0;				
				$return_msg .= "Username is mandatory.<br>";			
			}		
		} else {			
			$valid = 0;			
			$return_msg .= "Username is mandatory.<br>";		
		}	
		
		if(isset($data['firstname'])) {			
			if(empty($data['firstname'])) {				
				$valid = 0;				
				$return_msg .= "Firstname is mandatory.<br>";			
			}		
		} else {			
			$valid = 0;			
			$return_msg .= "Firstname is mandatory.<br>";		
		}
		
		if(isset($data['middlename'])) {
			if(empty($data['middlename'])) {
				$valid = 0;
				$return_msg .= "Middlename is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Middlename is mandatory.<br>";
		}
		
		if(isset($data['lastname'])) {
			if(empty($data['lastname'])) {
				$valid = 0;
				$return_msg .= "Lastname is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Lastname is mandatory.<br>";
		}
		
		if(isset($data['mobile'])) {
			if(empty($data['mobile'])) {
				$valid = 0;
				$return_msg .= "Phone is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Phone is mandatory.<br>";
		}
		if(isset($data['password'])) {
			if(empty($data['password'])) {
				$valid = 0;
				$return_msg .= "Password is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Password is mandatory.<br>";
		}
		
		if(isset($data['email'])) {
			if(empty($data['email'])) {
				$valid = 0;
				$return_msg .= "Email is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Email is mandatory.<br>";
		}
		if(isset($data['password'])) {
			if(!empty($data['password'])){
				if(isset($data['cpassword'])) {
					if(!empty($data['cpassword'])){
						if($data['password'] != $data['cpassword']){
							$valid = 0;
							$return_msg .= "Password didn't match.<br>";
						}
					}
				}
			}
		}
		if($valid == 1){			
			$sql  = "INSERT INTO oc_user 
						SET username ='".$this->db->escape(strtolower($data['username']))."' 
						   ,password = '" . $this->db->escape(md5($data['password'])) . "'
						   ,firstname = UCASE('" . $data['firstname'] . "')						   
						   ,middlename = UCASE('".$data['middlename']."') 
						   ,lastname = UCASE('" . $data['lastname'] . "')
						   ,contact =  '". $data['mobile']."' 
						   ,user_group_id = ".$user_group_id."
						   ,item_id = ".$item_id."				   
						   ,refer_by_id = ".$sponsor_id." 
						   ,teamlead_id = ".$teamlead_id." 						   
						   ,coordinator_id = ".$coordinator_id." 						   
						   ,activation_flag = 1 						   
						   ,email = '".$data['email']."' 						   
						   ,code = '".$data['serial_code']."' 						   
						   ,status = 1 
						   ,paid_flag = 0						   
						   ,date_added = '".$this->user->now()."' 						   
						   ,site = '".WEBSITE."'";
			
			
			if(isset($data['address'])) {
				if($data['address'] != "") {
					$sql .= " ,address = '".$this->db->escape($data['address'])."'";
				}
			}
			
			if(isset($data['province_id'])) {
				if($data['province_id'] > 0) {
					$sql .= " ,province_id = ".$data['province_id'];
				}
			}
			
			if(isset($data['city_municipality_id'])) {
				if($data['city_municipality_id'] > 0) {
					$sql .= " ,city_municipality_id = ".$data['city_municipality_id'];
				}
			}
			
			if(isset($data['barangay_id'])) {
				if($data['barangay_id'] > 0) {
					$sql .= " ,barangay_id = ".$data['barangay_id'];
				}
			}
			
			$this->db->query($sql);						
			$user_id = $this->db->getLastId();	
			
			$sql = "update oc_serials 
					   set user_id = ".$user_id."
						  ,used_flag = 1
						  ,date_used = '".$this->user->now()."'
					 where serial_id = ".$serial_id;
			$this->db->query($sql);

			$sql = "update oc_user 
					   set id_no = concat('".IDPREFIX."', '-', LPAD(user_id, 8, '0')) 
							,ref = '".md5("EDSI-ID".$user_id)."' 
					 where user_id = ".$user_id;
			$this->db->query($sql);
			
			$sql = "insert into oc_unilevel(user_id, sponsor_user_id, level, date_added)										
				         values (".$user_id.", ".$sponsor_id.", 1, '".$this->user->now()."')";			
			$this->db->query($sql);		
			
			$sql = "insert into oc_unilevel(user_id, sponsor_user_id, level, date_added )					
				    select ".$user_id.", sponsor_user_id, (level + 1), '".$this->user->now()."'						  
					from oc_unilevel ou					 
					where user_id = ".$sponsor_id."						   
					and sponsor_user_id <> 0";			
			$this->db->query($sql);				
			$return_msg = "Registration Successful";

			//distribute referral commission
			$com_type = 0;
			if($package_type == 2) { //distributor
				$com_type = 2;
			} else if($package_type == 1 ) {
				$com_type = 1;
			}
			
			if($direct_referral > 0 and $com_type > 0) {
				$this->load->model('account/orders');
				$this->model_account_orders->insertEwallet($direct_referral, $sponsor_id, $user_id, $order_id, $com_type, 1, 1);
			}
		}			
		
		$return_array['result'] = $valid;		
		$return_array['result_msg'] = $return_msg;		
		return $return_array;	
	}
	
	public function getSponsorDetails($username) {
		$return_array = array();
		$sql = "select user_id sponsor_id, id_no, concat(firstname, ' ', lastname) name, activation_flag 
				  from oc_user 
				 where lower(username) = lower('".$username."')";
		$this->log->write($sql);
		$query = $this->db->query($sql);
		
		if(isset($query->row['id_no'])) {
			$activation_flag = $query->row['activation_flag'];
			if($activation_flag == 0) {
				$return_array['id_no'] = "Sponsor ".$username." is not active.";
				$return_array['name'] = "Not Activated";
				$return_array['sponsor_id'] = 0;
				$return_array['activation_flag'] = $query->row['activation_flag'];
				$return_array['success'] = "1";
			} else {
				$return_array['id_no'] = $query->row['id_no'];
				$return_array['name'] = $query->row['name'];
				$return_array['sponsor_id'] = $query->row['sponsor_id'];
				$return_array['activation_flag'] = $query->row['activation_flag'];
				$return_array['success'] = "1";
			}
		} else {
			$return_array['id_no'] = "Sponsor ".$username." is not existing.";
			$return_array['name'] = "Not Existing";
			$return_array['sponsor_id'] = 0;
			$return_array['activation_flag'] = "0";
			$return_array['success'] = "0";
		}
		return $return_array;
	}
}
?>
