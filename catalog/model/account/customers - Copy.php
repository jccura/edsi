<?php
class ModelAccountCustomers extends Model {	

	public function messageIfWithDuplicateName($username) {
		$message = "Your choices are: <br>";
		$sql = "select concat(username, '(', firstname, ' ',  middlename, ' ',  lastname, ')') name from oc_user where lower(username) = lower('".$username."') or lower(o_username) = lower('".$username."')"; 
		$query = $this->db->query($sql);
		foreach($query->rows as $names) {
			$message .= $names['name'].".<br>";
		}
		return $message;
	}

	public function checkIfWithDuplicateName($username) {	
		$with_duplicate = 0;
		$sql = "select sum(with_duplicate) with_duplicate from oc_user where lower(username) = lower('".$username."') or lower(o_username) = lower('".$username."')";
		$query = $this->db->query($sql);
		if(isset($query->row['with_duplicate'])) {
			$with_duplicate = $query->row['with_duplicate'];
		}
		return $with_duplicate;
	}

	public function checkNameCount($firstname, $middlename, $lastname) {
		$ret = true;
		
		$sql = "select count(1) as total from oc_user where lower(firstname) = '".strtolower($firstname)."' and lower(middlename) = '".strtolower($middlename)."' and lower(lastname) = '".strtolower($lastname)."' ";
		$query = $this->db->query($sql);
		$total_count_after = $query->row['total'];
		
		
		if($total_count_after > MAX_NAME_COUNT) {
			$ret = false;
		}
		
		return $ret;
	}
	
	public function checkIfDownline($user_id,$dline) {

		$ret = true;
		
		if($user_id <> $dline) {				
			$sql  = "select user_id from oc_infinity where sponsor_user_id = ".$user_id." and user_id = ".$dline;
			$query = $this->db->query($sql);
			if(!isset($query->row['user_id'])) {
				$ret = false;
			}
		}
	
		return $ret;
	}

	public function checkReferror($referror) {
		$sql  = "select user_id from oc_user where lower(username) = lower('".$referror."') ";
		//echo $sql;
		$query = $this->db->query($sql);
		$user_id = 0;
		$ret = false;
		if(isset($query->row['user_id'])) {
			$user_id = $query->row['user_id'];			
			$sql  = "select user_id from oc_unilevel where user_id = ".$user_id." limit 1 ";
			$query = $this->db->query($sql);
			if(isset($query->row['user_id'])) {
				$ret = true;
			} else {			
				$ret = false;
			}
		} else {
			$ret = false;
		}	
		return $ret;
	}	

	public function checkUplineDline($upline) {
		
		$ret = false;
		
		$sql  = "select user_id from oc_user where lower(username) = lower('".$upline."')";
		$query = $this->db->query($sql);
		$upline_id = $query->row['user_id'];
		
		$sql = "select count(1) as total from oc_infinity where sponsor_user_id = ".$upline_id." and level = 1 ";
		$query = $this->db->query($sql);
		
		if(isset($query->row['total'])) {
			////////echo $query->row['total'];
			if($query->row['total'] < 2) {
				$ret = true;
			}			
		} 
		
		return $ret;
	}

	public function checkPosition($upline, $position) {
		
		$ret = false;
		
		$sql  = "select user_id from oc_user where lower(username) = lower('".$upline."')";
		$query = $this->db->query($sql);
		if(isset($query->row['user_id'])) {
			if($query->row['user_id']) {
				$upline_id = $query->row['user_id'];
				$sql = "select count(1) as total from oc_infinity where sponsor_user_id = ".$upline_id." and level = 1 and position = '".$position."'";
				$query = $this->db->query($sql);
				//echo $sql."<br>";
				if(isset($query->row['total'])) {
					if($query->row['total'] == 0) {
						$ret = true;
					}			
				} 			
			} else {
				$ret = false;
			}
		
		} else {
			$ret = false;
		}
		
		return $ret;
	}
	
	public function checkUpline($upline) {
		$sql  = "select user_id from oc_user where lower(username) = lower('".$upline."')";
		$query = $this->db->query($sql);
		$user_id = 0;
		$ret = false;
		if(isset($query->row['user_id'])) {
			$ret = true;
		} else {
			$ret = false;
		}	
		return $ret;
	}

	public function validateRegistrationSerials($serial, $code) {
		$sql = "select item_id from oc_serials where serial_code = '".$serial."' and password = '".$code."'";
		//echo $sql."<br>";
		$query = $this->db->query($sql);
		$item_id = $query->row['item_id'];

		//if($item_id == 1 or $item_id == 75 or $item_id == 18 or $item_id == 19 or $item_id == 22 or $item_id == 106) {
		if($item_id == 109 or $item_id == 110 or $item_id = 111) {
			return true;
		} else {
			return false;
		}
	}

	public function validateSerialsLineUpline($serial, $code, $upline) {
		
		$ret = false;
		
		$sql  = "select a.user_group_id ";
		$sql .= "  from oc_serials a ";
		$sql .= " where a.serial_code = '" .$this->db->escape($serial)."' ";	
		$sql .= "   and a.password = '" . $this->db->escape($code) . "' ";
		$query = $this->db->query($sql);
		
		$serial_user_group = 0;
		
		if(isset($query->row['user_group_id'])) {
			$serial_user_group = $query->row['user_group_id'];				
			$sql = "select user_group_id from oc_user where lower(username) = lower('".$upline."')";
			$query = $this->db->query($sql);
			if(isset($query->row['user_group_id'])) {				
				////////echo $serial_user_group.":".$query->row['user_group_id'];
				if($serial_user_group == $query->row['user_group_id']) {
					$ret = true;							
				} else {
					$ret = false;
				}
			} else {			
				$ret = false;
			}				
		} else {			
			$ret = false;
		}		
		return $ret;
	}	
	
	
	public function validateSerialsLine($serial, $code) {
		
		$ret = false;
		
		$sql  = "select a.user_group_id ";
		$sql .= "  from oc_serials a ";
		$sql .= " where a.serial_code = '" .$this->db->escape($serial)."' ";	
		$sql .= "   and a.password = '" . $this->db->escape($code) . "' ";
		$query = $this->db->query($sql);
		
		if(isset($query->row['user_group_id'])) {
			if($this->user->getUserGroupId() == $query->row['user_group_id']) {
				$ret = true;
			} else {
				$ret = false;
			}
		} else {			
			$ret = false;
		}		
		return $ret;
	}	
	
	public function validateSerials($data) {
		$sql  = "select a.serial_code ";
		$sql .= "  from oc_serials a ";
		$sql .= " where a.serial_code = '" .$this->db->escape($data['serial'])."' ";	
		$sql .= "   and a.password = '" . $this->db->escape($data['ak']) . "' ";
		$query = $this->db->query($sql);
		if(isset($query->row['serial_code'])) {
			$ret = true;
		} else {
			$ret = false;
		}		
		return $ret;
	}

	public function validateSerialsToUserType($data) {
		$ret = true;
		$sql  = "select a.user_group_id ";
		$sql .= "  from oc_serials a ";
		$sql .= " where a.serial_code = '" .$this->db->escape($data['serial'])."' ";	
		$sql .= "   and a.password = '" . $this->db->escape($data['ak']) . "' ";
		$query = $this->db->query($sql);
		if(isset($query->row['user_group_id'])) {
			$user_group_id = $query->row['user_group_id'];
			if($data['type_of_registration'] == "DISTRIBUTOR") {
				if($user_group_id == 34) {
					$ret = false;
				} 
			} else if($data['type_of_registration'] == "RESELLER") {
				if($user_group_id == 13) {
					$ret = false;
				}
			}
		} else {
			$ret = false;
		}		
		return $ret;
	}
	
			

	public function checkIfBatchSerial($serial, $code) {
		$ret = true;
		$sql  = "select a.batch ";
		$sql .= "  from oc_batch a ";
		$sql .= " where a.batch = '" .$this->db->escape($serial)."' ";	
		$sql .= "   and lower(a.code) = lower('" . $this->db->escape($code) . "') ";
		$query = $this->db->query($sql);
		if(isset($query->row['serial_code'])) {
			$ret = true;
		} else {		
			$ret = false;
		}		
		return $ret;
	}	
	
	public function validateUsedSerials($serial, $code) {
		$ret = true;
		$sql  = "select count(1) as total ";
		$sql .= "  from oc_serials a ";
		$sql .= " where a.serial_code = '" .$this->db->escape($serial)."' ";	
		$sql .= "   and a.password = '" . $this->db->escape($code) . "' and used_flag in (1, 2)";
		//echo "$sql<br>";
		$query = $this->db->query($sql);
		
		if($query->row['total'] > 0) {			
			$ret = false;
		}
		
		return $ret;
	}	

	public function register($data, $event) {
		
		//$salt = substr(md5(uniqid(rand(), true)), 0, 9);		
		$upline = $this->getCustomerId($data['upline']);
		$referror = $upline;
		$orig_referror = $referror;
		$batch_created_user = array();
		$orig_username = $data['username'];
		$gcode = null;
				
		$sql = " SELECT a.branch_id, a.user_group_id, a.paid_flag, 
						a.item_id, b.max_count, b.points, a.com_deduct, b.cost, b.description alias
				  FROM oc_serials a 
				  JOIN gui_items_tbl b on(a.item_id = b.item_id)
				 WHERE a.serial_code = '".$data['serial']."'
				   AND a.password = '".$data['ak']."'
				   AND a.used_flag = 0 ";
		
		$query = $this->db->query($sql);
		
		if(isset($query->row['user_group_id'])) {
			
			$com_user_package = $query->row['alias'];
			$branch_id = $query->row['branch_id'];
			$user_group_id = $query->row['user_group_id'];
			$paid_flag = $query->row['paid_flag'];
			$item_id = $query->row['item_id'];
			$cv = $query->row['points'];
			$com_deduct = $query->row['com_deduct'];
			$com_deduct_cost = $query->row['cost'];
			
			//ver2 no freeslot
			if($paid_flag == 1) {
				$free_slot = 0;
			} else {
				$free_slot = 1;
			}
			
			$user_status = 1;
			
			$this->log->write($data['username'].":".$upline.":".$referror.":".$data['position']);					
			
			if($event == "ADD ACCOUNT") {
				
				$sql = "select code, password from oc_user where user_id = ".$this->user->getId();
				$query = $this->db->query($sql);
				$password = $query->row['password'];					
				
				$sql = "INSERT INTO oc_user "; 
				$sql.= " SET firstname = '".$this->user->getFirstname()."' ";
				$sql.= " ,middlename = '".$this->user->getMiddlename()."' ";
				$sql.= " ,lastname = '".$this->user->getLastname()."' ";
				$sql.= " ,birthdate = '".$this->user->getBirthdate()."' ";		
				$sql.= " ,email = '".$this->user->getEmail()."' ";		
				$sql.= " ,username = '".$data['username']."' ";			
				$sql.= " ,serial = '".$data['serial']."' ";			
				$sql.= " ,activation_key = '".$data['ak']."' ";			
				$sql.= " ,gender = '".$this->user->getGender()."' ";			
				$sql.= " ,date_added = '".$this->user->now()."' ";			
				$sql.= " ,date_modified = '".$this->user->now()."' ";		
				$sql.= " ,password = '".$password."' ";	
				$sql.= " ,item_id = ".$item_id." ";				
				$sql.= " ,upline = ".$upline." ";
				$sql.= " ,refer_by_id = ".$referror." ";
				$sql.= " ,user_group_id = ".$user_group_id." ";
				$sql.= " ,paid_flag = ".$paid_flag." ";
				$sql.= " ,status = ".$user_status;
				$sql.= " ,rank_id = 1 ";
				$sql.= " ,site = '".WEBSITE."' ";
				$sql.= " ,position = '".$data['position']."' ";
				
				
			} else if($event == "REGISTER") {
			
				$sql = "INSERT INTO oc_user "; 
				$sql.= " SET firstname = '".$data['firstname']."' ";
				$sql.= " ,middlename = '".$data['middlename']."' ";
				$sql.= " ,lastname = '".$data['lastname']."' ";
				$sql.= " ,birthdate = '".$data['birthdate']."' ";
				$sql.= " ,contact = '".$data['mobile']."' ";		
				$sql.= " ,email = '".$data['email']."' ";		
				$sql.= " ,username = '".$data['username']."' ";			
				$sql.= " ,serial = '".$data['serial']."' ";			
				$sql.= " ,activation_key = '".$data['ak']."' ";			
				$sql.= " ,gender = '".$data['gender']."' ";			
				$sql.= " ,date_added = '".$this->user->now()."' ";			
				$sql.= " ,date_modified = '".$this->user->now()."' ";		
				$sql.= " ,password = '".md5($data['password'])."' ";												
				$sql.= " ,item_id = ".$item_id." ";
				$sql.= " ,upline = ".$upline." ";
				$sql.= " ,refer_by_id = ".$referror." ";
				$sql.= " ,user_group_id = ".$user_group_id." ";
				$sql.= " ,paid_flag = ".$paid_flag." ";
				$sql.= " ,status = ".$user_status;
				$sql.= " ,rank_id = 1 ";
				$sql.= " ,site = '".WEBSITE."' ";
				$sql.= " ,position = '".$data['position']."' ";						
			
			}
			$this->log->write($sql);
			$this->db->query($sql);
									
			$user_id = $this->db->getLastId();
			$shift = 1;
			//echo "free_slot::".$free_slot."<br>";

			$sql = " UPDATE oc_serials set used_flag = 1, user_id = ".$user_id.", date_modified = '".$this->user->now()."'";
			$sql.= "  WHERE serial_code = '".$data['serial']."' and password = '".$data['ak']."' ";
			$this->log->write($sql);
			$this->db->query($sql);
			
			//insert to oc_user_points
			$sql = "insert into oc_user_points 
					   set user_id = ".$user_id."
						  ,left_points = 0
						  ,right_points = 0
						  ,date_added = '".$this->user->now()."'
						  ,date_modified = '".$this->user->now()."'";
			$this->log->write($sql);
			$this->db->query($sql);
			
			if($com_deduct == 1) {
				$commission_status = 38;
				$commission_type_id = 1; //Commission Deduction
				$this->insertCommission2($user_id, $user_id, $com_user_package, $commission_type_id, $com_deduct_cost * -1, $commission_status, $this->user->nowDate(), $shift);
			}

			$sql = "UPDATE oc_user SET id_no = concat('AND',lpad(".$user_id.", 10, '0')) WHERE user_id = ".$user_id;
			$this->log->write($sql);
			$this->db->query($sql);
			
			$sql = " INSERT INTO gui_user_branch ";
			$sql.= " SET user_id = ".$user_id." ";
			$sql.= " , branch_id = ".$branch_id." ";
			$sql.= " , status = ".$user_status;
			$sql.= " , date_added = '".$this->user->now()."' ";
			$sql.= " , expiry_date = '9999-12-31' ";
			$this->log->write($sql);
			$this->db->query($sql);
						
			$sql = "insert into oc_infinity(user_id, sponsor_user_id, level, date_added, position, counter)
									values (".$user_id.", ".$upline.", 1, '".$this->user->now()."', '".$data['position']."',".$free_slot.")";
			$this->db->query($sql);						
			$this->log->write($sql);
			
			//echo "inserting to infinity: ".$sql."<br>";			
			$sql = "insert into oc_infinity(user_id, sponsor_user_id, level, date_added, position, counter )
					select ".$user_id.", sponsor_user_id, (level + 1), '".$this->user->now()."', position, ".$free_slot."
						  from oc_infinity oc1
					 where user_id = ".$upline."
						   and sponsor_user_id <> 0";
			$this->db->query($sql);	
			$this->log->write($sql);
			
			$binary = 1;
			
			if($free_slot == 0) {
				if($binary == 1) {
					$this->log->write("With Binary");
					//if not freeslot always call the checkBinary
					$this->checkBinaryVer3($user_id, $com_user_package);
				}
			}
			
			$this->log->write($event);
			
			if($event == "ADD ACCOUNT") {
				return "Congratulations ".$orig_username."(".strtoupper($this->user->getFirstname()." ".$this->user->getMiddlename()." ".$this->user->getLastname().").")."You have successfully registered your account. Welcome to your ".WEBSITE_TITLE." family.";
			} else if($event == "REGISTER") { 
				return "Congratulations ".$orig_username."(".strtoupper($data['firstname']." ".$data['middlename']." ".$data['lastname'].").")."You have successfully registered your account. Welcome to your ".WEBSITE_TITLE." family.";
			}
			
		} else {
			return "Serial is not yet sold by cashier.";
		} 
		
	}	
	
	//added 2017-06-13
	public function commissionDirectorsCommission($item_id, $user_id, $shift, $user_package) {	
		//echo "commissionDirectorsCommission inside<br>";
		$this->debugLog("inside commissionDirectorsCommission", "");
		$sql = "select a.sponsor_user_id
				  from oc_unilevel a
				  join oc_user b on(a.sponsor_user_id = b.user_id)
				 where a.user_id = ".$user_id."
				   and b.director = 1
				  order by a.user_id desc, a.level asc
				limit 1 ";				
		$query = $this->db->query($sql);
		//echo "sql >>> ".$sql."<br>";
		
		if(isset($query->row['sponsor_user_id'])) {
			$this->debugLog("director", $query->row['sponsor_user_id']);
			$director_user_id = $query->row['sponsor_user_id'];
			//echo "director_user_id >>> ".$director_user_id."<br>";
			$commission_type_id = $this->getCommissionTypeId('Directors Commission');
			$sql = "select directors_com from gui_items_tbl where item_id = ".$item_id;
			$query = $this->db->query($sql);
			$amount = $query->row['directors_com'];		
			$this->debugLog("amount", $amount);
			if($amount > 0) {
				$this->insertCommission2($director_user_id, $user_id, $user_package, $commission_type_id, $amount, 38, $this->user->nowDate(), $shift);
			}
		}
		$this->debugLog("leaving commissionDirectorsCommission", "");
	}

	public function getCommissionTypeId($commission_type_desc) {
		$commission_type_id = 0;
		$sql = "select commission_type_id from commission_type where description = '".$commission_type_desc."' ";
		$query = $this->db->query($sql);
		$commission_type_id = $query->row['commission_type_id'];
		return $commission_type_id;
	}
	
	public function getUserRank($user_id) {
		$rank = 1;
		$sql = "select rank_id from oc_user where user_id = ".$user_id;
		$query = $this->db->query($sql);
		if(isset($query->row['rank_id'])) {
			$rank = $query->row['rank_id'];
		}
		return $rank;		
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
	
	public function insertCommission($user_id, $com_user_id, $commission_type_id, $amount, $commission_status) {		
		$sql = " INSERT INTO oc_commission ";
		$sql.= " 	SET user_id = ".$user_id;
		$sql.= " 	  , com_user_id = ".$com_user_id;
		$sql.= " 	  , commission_type_id = ".$commission_type_id; 
		$sql.= " 	  , amount = 0.9 * ".$amount." ";
		$sql.= " 	  , tax = 0.1 * ".$amount." ";
		$sql.= " 	  , date_commissioned = '".$this->user->now()."' ";
		$sql.= " 	  , `date` = '".$this->user->now()."' ";
		$sql.= " 	  , status = ".$commission_status;
		//echo $sql."<br>";
		$this->db->query($sql);

		if($commission_status == 38) {
			$sql = " UPDATE oc_user set totalcommission = totalcommission + (0.9 * ".$amount.") where user_id = ".$user_id;
			$this->db->query($sql);
		}
	}

	public function insertCommission2($user_id, $com_user_id, $com_user_package, $commission_type_id, $amount, $commission_status, $date, $shift) {		

		$sql = "select b.description alias
				  from oc_serials a
				  join gui_items_tbl b on(a.item_id = b.item_id)
				 where a.user_id = ".$user_id." and a.uni_code_id = 0 ";
		
		$query = $this->db->query($sql);
		$source_alias = $query->row['alias'];
		
		$sql = " INSERT INTO oc_commission2 ";
		$sql.= " 	SET user_id = ".$user_id;
		$sql.= " 	  , com_user_id = ".$com_user_id; 
		$sql.= " 	  , commission_type_id = ".$commission_type_id; 
		$sql.= " 	  , amount = '".$amount."' ";
		$sql.= " 	  , date_commissioned = '".$this->user->now()."' ";
		$sql.= " 	  , `dates` = '".$date."' ";
		$sql.= " 	  , `source_alias` = '".$source_alias."' ";
		$sql.= " 	  , `source_alias2` = '".$com_user_package."' ";		
		$sql.= " 	  , `shift` = '".$shift."' ";
		$sql.= " 	  , status = ".$commission_status;
		$sql.= " 	  , ver = 3 "; //always change this if there is a major version change
		
		//if($commission_type_id == 4) {
		//	echo $sql."<br>";
		//}
		
		$this->db->query($sql);

		if($commission_status == 38) {
			$sql = " UPDATE oc_user set totalcommission = totalcommission + (0.9 * ".$amount.") where user_id = ".$user_id;
			$this->db->query($sql);
		}
	}
	
	public function insertEwallet($user_id, $com_user_id, $commission_type_id, $amount, $commission_status) {		

		$sql = "select b.description alias
				  from oc_serials a
				  join gui_items_tbl b on(a.item_id = b.item_id)
				 where a.user_id = ".$user_id." and a.uni_code_id = 0 ";
		
		$query = $this->db->query($sql);
		$source_alias = $query->row['alias'];
		
		$sql = "UPDATE oc_user set ewallet = ewallet + ".$amount." where user_id = ".$user_id;
		$this->db->query($sql);
		$this->log->write($sql);
		
		$sql = "INSERT INTO oc_ewallet_hist 
				   SET user_id = " . $user_id . ", 
				       source_user_id = ".$com_user_id.", 
					   commission_type_id = ".$commission_type_id.", 
					   debit = 0.00, 
					   credit = ".$amount.", 
					   remarks = '".$source_alias."', 
					   created_by = 1, 
					   date_added = '". $this->user->now() ."'";
		
		$this->db->query($sql);
		$this->log->write($sql);
	}	
	
	public function insertCommission3($uni_comp_id, $user_id, $com_user_id, $commission_type_id, $amount, $commission_status, $date, $shift) {		
		
		$sql = "select b.description alias
				  from oc_serials a
				  join gui_items_tbl b on(a.item_id = b.item_id)
				 where a.user_id = ".$user_id." and a.uni_code_id = 0 ";
		
		$query = $this->db->query($sql);
		$source_alias = $query->row['alias'];

		$sql = "select b.description alias
				  from oc_serials a
				  join gui_items_tbl b on(a.item_id = b.item_id)
				 where a.user_id = ".$com_user_id." and a.uni_code_id = 0 ";
		
		$query = $this->db->query($sql);
		$source_alias2 = $query->row['alias'];
		
		$sql = " INSERT INTO oc_commission2 ";
		$sql.= " 	SET user_id = ".$user_id;
		$sql.= " 	  , com_user_id = ".$com_user_id; 
		$sql.= " 	  , commission_type_id = ".$commission_type_id; 
		$sql.= " 	  , amount = '".$amount."' ";
		$sql.= " 	  , date_commissioned = '".$this->user->now()."' ";
		$sql.= " 	  , `dates` = '".$date."' ";
		$sql.= " 	  , `source_alias` = '".$source_alias."' ";
		$sql.= " 	  , `source_alias2` = '".$source_alias2."' ";
		$sql.= " 	  , `shift` = '".$shift."' ";
		$sql.= " 	  , status = ".$commission_status;
		$sql.= " 	  , uni_comp_id = ".$uni_comp_id;
		
		//if($commission_type_id == 4) {
		//	echo $sql."<br>";
		//}
		
		$this->db->query($sql);

		if($commission_status == 38) {
			$sql = " UPDATE oc_user set totalcommission = totalcommission + (0.9 * ".$amount.") where user_id = ".$user_id;
			$this->db->query($sql);
		}
	}	
		
	public function getCustomerId($username) {
		
		$sql  = "SELECT user_id ";
		$sql .= "  FROM oc_user ";
		$sql .= " WHERE lower(username) = lower('".$username."')";

		$query = $this->db->query($sql);
		
		if(isset($query->row['user_id'])) {
			return $query->row['user_id'];
		} else {
			return 0;
		}
		
	}

	public function editCustomer ($data = array()) {
		
		$return_msg = "";
		$valid = 1;
		
		if($data['firstname'] == "") {
			$valid = 0;
			$return_msg .= "Firstname is mandatory.";
		}

		if($data['middlename'] == "") {
			$valid = 0;
			$return_msg .= "Middlename or Middle Initial is mandatory.";
		}		

		if($data['lastname'] == "") {
			$valid = 0;
			$return_msg .= "Lastname is mandatory.";
		}		
		
		if($valid == 1) {
			$sql  = "UPDATE oc_user 
					   SET firstname = UPPER('".$this->db->escape($data['firstname']) . "')
						  ,middlename = UPPER('". $this->db->escape($data['middlename']) . "')
						  ,lastname = UPPER('".$this->db->escape($data['lastname']) . "')
						  ,birthdate = '".$this->db->escape($data['birthdate']) . "'
						  ,username = '".$this->db->escape($data['username']) . "'
						  ,email = '".$this->db->escape($data['email'])."'
						  ,auto_spill_setup = '".$this->db->escape($data['auto_spill_setup'])."'		
					 WHERE user_id = ".$this->user->getId();
			
			$this->db->query($sql);
		
			return "Successful edit of Profile.";
		} else {
			return $return_msg;
		}
	}
	

	public function resetUser($user_id) {
				
		$sql  = "UPDATE oc_user ";
		$sql .= "   SET password = '" . $this->db->escape(md5('12345')) . "'";
		$sql .= " WHERE user_id = ".$user_id;
		
		$this->db->query($sql);
		
	}
	
	public function getUserGroups($data = array()) {

			$sql = "SELECT  user_group_id, name ";
			$sql.= "from oc_user_group ";			
			
			$sql .= " ORDER BY name ";
			
			$query = $this->db->query($sql);
		
			return $query->rows;
	}

	public function checkUsername($username) {
		$count = 0;
		$sql = "SELECT count(1) as total from oc_user where lower(username) = lower('".$username."')";
		$query = $this->db->query($sql);
		$count = $query->row['total'];
		////echo $sql;
		if ($count > 0) {
			return false;
		} else {
			return true;
		}
	}

	public function checkUsernameProfile($username) {
		$count = 0;
		$sql = "SELECT count(1) as total from oc_user where lower(username) = lower('".$username."') and user_id <> ".$this->user->getId();
		$query = $this->db->query($sql);
		$count = $query->row['total'];
		////echo $sql;
		if ($count > 0) {
			return false;
		} else {
			return true;
		}
	}	

	public function getBranches() {
		$sql  = "select branch_id, description from gui_branch_tbl";
		$query = $this->db->query($sql);
		return $query->rows;
	}	


	public function getCustomerDetails($customer_id) {
		$sql  = "SELECT * ";
		$sql .= "  FROM gui_customer_tbl a ";
		$sql .= " WHERE a.customer_id = " .$customer_id ;

		$query = $this->db->query($sql);
		////////echo "<br/>".$sql;
		return $query->row;
	
	}

	public function editPassword ($data = array()) {

		$count = 0;
		$sql = "SELECT count(1) as total from oc_user ";
		$sql .= " WHERE user_id = ".$data['user_id'];
		$sql .= "   AND password = '".$this->db->escape(md5($data['oldpassword']))."'";
		
		$query = $this->db->query($sql);
		$count = $query->row['total'];
		
		$sql  = "UPDATE oc_user ";
		$sql .= "   SET password = '".$this->db->escape(md5($data['password']))."'";
		$sql .= " WHERE user_id = ".$data['user_id'];
		$sql .= "   AND password = '".$this->db->escape(md5($data['oldpassword']))."'";
		
		////////echo $sql;
		$this->db->query($sql);

		if ($count > 0) {
			return 'Success in updating password.';
		} else {
			return 'Correct old password is required.';
		}		
		
	}

	function getInfinityList($user_id, $data = array()) {

		$sql  = " 
			SELECT b.id_no dl_id_no, b.username dl_username, b.user_id dl_user_id, 
				   concat(b.firstname,' ',b.middlename, ' ',b.lastname) dl_desc, 
				   b.date_added, c.id_no, c.username, b.upline, e.username sp_username,
				   concat(c.firstname,' ',c.middlename, ' ',c.lastname) upline_desc,
				   concat(e.firstname,' ',e.middlename, ' ',e.lastname) sp_desc, 				   
				   b.counter, a.level, b.graduate_flag, d.description status , a.position, a.level_counter
			  FROM oc_infinity a 
			  LEFT JOIN oc_user b ON(b.user_id = a.user_id)
			  LEFT JOIN oc_user c ON(c.user_id = b.upline)
			  LEFT JOIN oc_ranks d ON(d.rank_id = b.rank_id)
			  LEFT JOIN oc_user e ON(e.user_id = b.refer_by_id)
			 WHERE a.sponsor_user_id = ".$user_id."
			ORDER BY a.level, a.level_counter, b.upline, a.user_id	
		";	

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 5;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		////echo "<br>".$sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}

	function getAccountList($data = array()) {
	
		date_default_timezone_set('Asia/Manila');			
		$nowDate = new DateTime();		
		$day = $nowDate->format('d');
		
		$ym = $nowDate->format('Y-m');

		$commission_string = " select sum(amount) from oc_commission where user_id = ou.user_id and commission_type_id <> 5 and status = 38 ";
		
		$tax_string = " select sum(tax) from oc_commission where user_id = ou.user_id and commission_type_id <> 5 and status = 38 ";

		if($day < 10 ) {
			$not_for_encash = " select sum(amount) from oc_commission where user_id = ou.user_id and commission_type_id in(25, 26, 27, 28, 29) and status = 38 ";
			$not_for_encash .= " AND CAST(date_commissioned AS DATE) >= STR_TO_DATE('".$ym."-01', '%Y-%m-%d') ";
		} else {
			$not_for_encash = " 0.00 ";
		}
		
		$sql  = "select ou.username, ou.user_id, ou.date_added, ou.graduate_flag, d.rank_id,
						c.description as account_type, d.description status_desc,
						coalesce((".$commission_string."),0) totalcommission,
						coalesce((".$not_for_encash."),0) not_for_encash,
						coalesce((".$tax_string."),0) totaltax,
						coalesce((select sum(amount) from oc_encashment_det where user_id = ou.user_id),0) totalencashment, 
						ou.bank_name, ou.account_no, ou.account_name, b.item_id,
						case when ou.profilepic is null then concat('image/',d.images) else concat('profilepics/',ou.profilepic) end status_pic,
						oup.left_points - oup.left_used_points - oup.left_flushed_points left_remain_points,
						oup.right_points - oup.right_used_points - oup.right_flushed_points right_remain_points,
						oup.pairs, oup.incentive_points - oup.used_incentive_points incentive_points, oup.cv - oup.used_cv unused_cv,
						c.alias
		           from oc_user a
				   join oc_user ou on(a.code = ou.code)
				   join oc_user_points oup on(oup.user_id = ou.user_id)
				   left join oc_serials b on (ou.user_id = b.user_id and b.uni_code_id = 0)
				   left join gui_items_tbl c on (b.item_id = c.item_id)
				   join oc_ranks d on (d.rank_id = ou.rank_id)
		          where a.user_id = ".$this->user->getId();	

	  
		$sql.= " ORDER BY ou.user_id ";

		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 5;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		//echo "<br>".$sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}

	function getAccountList2($data = array()) {
	
		date_default_timezone_set('Asia/Manila');			
		$nowDate = new DateTime();		
		$day = $nowDate->format('d');
		
		$ym = $nowDate->format('Y-m');

		$commission_string = " select sum(amount) from oc_commission2 where user_id = ou.user_id and commission_type_id <> 5 and status = 38 ";
		
		/*
		if($day < 10 ) {
			$not_for_encash = " select sum(amount) from oc_commission2 where user_id = ou.user_id and commission_type_id in(25, 26, 27, 28, 29) and status = 38 ";
			$not_for_encash .= " AND CAST(date_commissioned AS DATE) >= STR_TO_DATE('".$ym."-01', '%Y-%m-%d') ";
		} else {
			$not_for_encash = " 0.00 ";
		}
		*/
		
		$not_for_encash = " 0.00 ";
		
		$sql  = "select ou.username, ou.user_id, ou.date_added, ou.graduate_flag, d.rank_id,
						c.description as account_type, d.description status_desc,
						coalesce((".$commission_string."),0) totalcommission,
						coalesce((".$not_for_encash."),0) not_for_encash,
						coalesce((select sum(amount) from oc_encashment_det2 where user_id = ou.user_id),0) totalencashment, 
						ou.bank_name, ou.account_no, ou.account_name, b.item_id,
						case when ou.profilepic is null then concat('image/',d.images) else concat('profilepics/',ou.profilepic) end status_pic,
						oup.left_points - oup.left_used_points - oup.left_flushed_points left_remain_points,
						oup.right_points - oup.right_used_points - oup.right_flushed_points right_remain_points,
						oup.pairs, oup.incentive_points, 
						(select count(1) from oc_commission2 where user_id = ou.user_id and commission_type_id in(4,5) and shift = 1 and dates = '".$this->user->nowDate()."') daily_cycle1,
						(select count(1) from oc_commission2 where user_id = ou.user_id and commission_type_id in(4,5) and shift = 2 and dates = '".$this->user->nowDate()."') daily_cycle2,
						c.alias, oup.cv - oup.used_cv unused_cv, oup.ewallet - oup.used_ewallet unused_ewallet
		           from oc_user a
				   join oc_user ou on(a.code = ou.code)
				   join oc_user_points oup on(oup.user_id = ou.user_id)
				   left join oc_serials b on (ou.user_id = b.user_id and b.uni_code_id = 0)
				   left join gui_items_tbl c on (b.item_id = c.item_id)
				   join oc_ranks d on (d.rank_id = ou.rank_id)
		          where a.user_id = ".$this->user->getId();	

	  
		$sql.= " ORDER BY ou.user_id ";

		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 5;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		//echo "<br>".$sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}	

	function getTotalAccountList($data = array()) {

		$sql  = "select count(1) as total
		           from oc_user a
				   join oc_user ou on(a.code = ou.code)
		          where a.user_id = ".$this->user->getId();	

		$query = $this->db->query($sql);
		
		return $query->row['total'];	
	}	
	
	public function getTotalInfinityList($user_id, $data = array()) {
		$sql  = " 
			SELECT count(1) as total 
			  FROM oc_infinity a 
			  JOIN oc_user b ON(b.user_id = a.user_id)
			  JOIN oc_user c ON(c.user_id = b.upline)
			 WHERE a.sponsor_user_id = ".$user_id;
		$query = $this->db->query($sql);
		
		////////echo $sql."<br>";
		return $query->row['total'];	
	}

	function getCommissionSummaryTotal($user_id, $data = array()) {

		$sql  = "select ou.username, concat(ou.firstname, ' ', ou.middlename, ' ', ou.lastname) name,
					  sum(oc.amount) total_amt, sum(oc.tax) total_tax
				  from oc_user oi
				  join oc_user ou on (ou.code = oi.code)
				  join oc_commission oc on(ou.user_id = oc.user_id)
				  join commission_type ct on(oc.commission_type_id = ct.commission_type_id)
				 where oi.user_id = ".$user_id." and oc.commission_type_id <> 5  and oc.status = 38 ";

		if (!empty($data['username'])) {
			$sql .= " AND ou.username = '" . $data['username']."'";
		}		
		
		if (!empty($data['datefrom']) and (!empty($data['datefrom']))) {
			$sql .= " AND oc.date_commissioned between '" . $data['datefrom']."' and '".$data['dateto']."'";
		}

		if (!empty($data['commission_type_id'])) {
			$sql .= " AND oc.commission_type_id = '" . $data['commission_type_id']."'";
		}
		
		$sql .= " GROUP BY ou.username ";
		
		//echo $sql."<br>";
		
	    $query = $this->db->query($sql);			
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 5;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		////////echo "<br>".$sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}	

	function getCommissionGrandSummaryTotal($user_id, $data = array()) {

		$sql  = "select concat(ou.firstname, ' ', ou.middlename, ' ', ou.lastname) name,
					  sum(oc.amount) total_amt, sum(oc.tax) total_tax
				  from oc_user oi
				  join oc_user ou on (ou.code = oi.code)
				  join oc_commission oc on(ou.user_id = oc.user_id)
				  join commission_type ct on(oc.commission_type_id = ct.commission_type_id)
				 where oi.user_id = ".$user_id." and oc.commission_type_id <> 5  and oc.status = 38 ";

		if (!empty($data['username'])) {
			$sql .= " AND ou.username = '" . $data['username']."'";
		}		
		
		if (!empty($data['datefrom']) and (!empty($data['datefrom']))) {
			$sql .= " AND oc.date_commissioned between '" . $data['datefrom']."' and '".$data['dateto']."'";
		}	

		if (!empty($data['commission_type_id'])) {
			$sql .= " AND oc.commission_type_id = '" . $data['commission_type_id']."'";
		}
		
		$sql .= " GROUP BY concat(ou.firstname, ' ', ou.middlename, ' ', ou.lastname) ";
		
		//echo $sql."<br>";
		
	    $query = $this->db->query($sql);			
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 5;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		//echo "<br>".$sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}
	
	function getCommissionSummary($user_id, $data = array()) {
		
		//echo "getCommissionSummary <br>";
		$sql  = "select ou.username, concat(ou.firstname, ' ', ou.middlename, ' ', ou.lastname) name,
					  ct.description, sum(oc.amount) total_amt, sum(oc.tax) total_tax
				  from oc_user oi
				  join oc_user ou on (ou.code = oi.code)
				  join oc_commission oc on(ou.user_id = oc.user_id)
				  join commission_type ct on(oc.commission_type_id = ct.commission_type_id)
				 where oi.user_id = ".$user_id." and oc.commission_type_id <> 5  and oc.status = 38 ";
	    $query = $this->db->query($sql);			

		if (!empty($data['username'])) {
			$sql .= " AND ou.username = '" . $data['username']."'";
		}		
		
		if (!empty($data['datefrom']) and (!empty($data['datefrom']))) {
			$sql .= " AND oc.date_commissioned between '" . $data['datefrom']."' and '".$data['dateto']."'";
		}		

		if (!empty($data['commission_type_id'])) {
			$sql .= " AND oc.commission_type_id = '" . $data['commission_type_id']."'";
		}
		
		$sql .= " GROUP BY ou.username, ct.description ";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 5;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		//echo "<br>".$sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}	
	
	function getCommissionList2($user_id, $data = array(), $commission_type = "All") {
	
		//echo "getCommissionList <br>";
		$sql  = "select ou.user_id, ou.username, concat(ou.firstname, ' ', ou.middlename, ' ', ou.lastname) name, ucs.username source,
					  ct.description, oc.amount, oc.date_commissioned, gst.description status, oc.shift, oc.commission_type_id
				  from oc_user oi
				  join oc_user ou on (ou.code = oi.code)
				  join oc_commission2 oc on(ou.user_id = oc.user_id)
				  left join oc_user ucs on (oc.com_user_id = ucs.user_id)
				  join commission_type ct on(oc.commission_type_id = ct.commission_type_id)
				  join gui_status_tbl gst on(gst.status_id = oc.status)
				 where oi.user_id = ".$user_id." and oc.status = 38  ";			

		if (!empty($data['username'])) {
			$sql .= " AND ou.username = '" . $data['username']."'";
		}				 
				 
		if (!empty($data['dusername'])) {
			$sql .= " AND ucs.username = '" . $data['dusername']."'";
		}		

		if (!empty($data['commission_type_id'])) {
			$sql .= " AND oc.commission_type_id = '" . $data['commission_type_id']."'";
		}				

		if( $commission_type <> "All") {
			if($commission_type == "Binary") {
				$sql .= " AND oc.commission_type_id in (4,5) ";
			} else if($commission_type == "Referral") {
				$sql .= " AND (oc.commission_type_id in (3,13,14,15,16,17) or  oc.commission_type_id between 29 and 42 )";
			} else if($commission_type == "Unilevel") {
				$sql .= " AND oc.commission_type_id in (26, 25, 27) ";
			} else if($commission_type == "Profit Sharing") {
				$sql .= " AND oc.commission_type_id in (7, 55, 56, 57) ";
			} else if($commission_type == "Ranking") {
				$sql .= " AND oc.commission_type_id in (28,29) ";
			}
		}
		
		if (!empty($data['datefrom']) and (!empty($data['datefrom']))) {
			$sql .= " AND oc.date_commissioned between '" . $data['datefrom']."' and '".$data['dateto']."'";
		}	
		
		$sql .= " ORDER BY commission_id DESC ";
		
		
		
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
	}
	
	public function getTotalCommissionList2($user_id, $data = array(), $commission_type = "All") {
	
		$sql  = "select count(1) as total
				  from oc_user oi
				  join oc_user ou on (ou.code = oi.code)
				  join oc_commission2 oc on(ou.user_id = oc.user_id)
				  left join oc_user ucs on (oc.com_user_id = ucs.user_id)
				  join commission_type ct on(oc.commission_type_id = ct.commission_type_id)
				  join gui_status_tbl gst on(gst.status_id = oc.status)
				 where oi.user_id = ".$user_id." and oc.status = 38  ";						 
				 
		if (!empty($data['username'])) {
			$sql .= " AND ou.username = '" . $data['username']."'";
		}				 
				 
		if (!empty($data['dusername'])) {
			$sql .= " AND ucs.username = '" . $data['dusername']."'";
		}		

		if (!empty($data['act_username'])) {
			$sql .= " AND ouc.username = '" . $data['act_username']."'";
		}
		
		if (!empty($data['paired_username'])) {
			$sql .= " AND oup.username = '" . $data['paired_username']."'";
		}		

		if (!empty($data['commission_type_id'])) {
			$sql .= " AND oc.commission_type_id = '" . $data['commission_type_id']."'";
		}
		
		if( $commission_type <> "All") {
			if($commission_type == "Binary") {
				$sql .= " AND oc.commission_type_id in (4,5) ";
			} else if($commission_type == "Referral") {
				$sql .= " AND (oc.commission_type_id in (3,13,14,15,16,17) or  oc.commission_type_id between 29 and 42 )";
			} else if($commission_type == "Unilevel") {
				$sql .= " AND oc.commission_type_id in (26, 25, 27) ";
			} else if($commission_type == "Profit Sharing") {
				$sql .= " AND oc.commission_type_id in (7, 55, 56, 57) ";
			} else if($commission_type == "Ranking") {
				$sql .= " AND oc.commission_type_id in (28,29) ";
			}
		}
		
		if (!empty($data['datefrom']) and (!empty($data['datefrom']))) {
			$sql .= " AND oc.date_commissioned between '" . $data['datefrom']."' and '".$data['dateto']."'";
		}
		
		$query = $this->db->query($sql);
		//echo $sql."<br>";
		return $query->row['total'];	
	}	

	function getCommissionList($user_id, $data = array()) {
	
		//echo "getCommissionList <br>";
		$sql  = "select ou.user_id, ou.username, oc.level, concat(ou.firstname, ' ', ou.middlename, ' ', ou.lastname) name,
					  ct.description, oc.amount, oc.tax, oc.date_commissioned, gst.description status,
					  ouc.username com_user, oup.username paired
				  from oc_user oi
				  join oc_user ou on (ou.code = oi.code)
				  join oc_commission oc on(ou.user_id = oc.user_id)
				  join commission_type ct on(oc.commission_type_id = ct.commission_type_id)
				  join gui_status_tbl gst on(gst.status_id = oc.status)
				  left join oc_user ouc on(ouc.user_id = oc.com_user_id)
				  left join oc_user oup on(oup.user_id = oc.paired)
				 where oi.user_id = ".$user_id." and oc.commission_type_id <> 5 and oc.status = 38  ";			

		if (!empty($data['username'])) {
			$sql .= " AND ou.username = '" . $data['username']."'";
		}		

		if (!empty($data['act_username'])) {
			$sql .= " AND ouc.username = '" . $data['act_username']."'";
		}

		if (!empty($data['commission_type_id'])) {
			$sql .= " AND oc.commission_type_id = '" . $data['commission_type_id']."'";
		}		

		
		if (!empty($data['paired_username'])) {
			$sql .= " AND oup.username = '" . $data['paired_username']."'";
		}		
		
		if (!empty($data['datefrom']) and (!empty($data['datefrom']))) {
			$sql .= " AND oc.date_commissioned between '" . $data['datefrom']."' and '".$data['dateto']."'";
		}	
		
		$sql .= " ORDER BY commission_id DESC ";
		
		
		
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
	}
	
	public function getTotalCommissionList($user_id, $data = array()) {
	
		$sql  = "select count(1) as total
				  from oc_user oi
				  join oc_user ou on (ou.code = oi.code)
				  join oc_commission oc on(ou.user_id = oc.user_id)
				  join commission_type ct on(oc.commission_type_id = ct.commission_type_id)
				  join gui_status_tbl gst on(gst.status_id = oc.status)
				  left join oc_user ouc on(ouc.user_id = oc.com_user_id)
				  left join oc_user oup on(oup.user_id = oc.paired)
				 where oi.user_id = ".$user_id." and oc.commission_type_id <> 5 and oc.status = 38  ";			

		if (!empty($data['username'])) {
			$sql .= " AND ou.username = '" . $data['username']."'";
		}		

		if (!empty($data['act_username'])) {
			$sql .= " AND ouc.username = '" . $data['act_username']."'";
		}
		
		if (!empty($data['paired_username'])) {
			$sql .= " AND oup.username = '" . $data['paired_username']."'";
		}		

		if (!empty($data['commission_type_id'])) {
			$sql .= " AND oc.commission_type_id = '" . $data['commission_type_id']."'";
		}
		
		if (!empty($data['datefrom']) and (!empty($data['datefrom']))) {
			$sql .= " AND oc.date_commissioned between '" . $data['datefrom']."' and '".$data['dateto']."'";
		}
		
		$query = $this->db->query($sql);
		////////echo $sql."<br>";
		return $query->row['total'];	
	}	
	
	function getFinanceCommissionSummary($user_id, $data = array()) {

		$sql  = "select concat(ou.firstname, ' ', ou.middlename, ' ', ou.lastname) name,
					  ct.description, sum(oc.amount) total_amt, sum(oc.tax) total_tax
				  from oc_user oi
				  join oc_user ou on (ou.code = oi.code)
				  join oc_commission oc on(ou.user_id = oc.user_id)
				  join commission_type ct on(oc.commission_type_id = ct.commission_type_id)
				 where 1 = 1 ";
				 
	    $query = $this->db->query($sql);	

		if (!empty($data['username'])) {
			$sql .= " AND oi.username = '" . $data['username']."'";
		}		
		
		if (!empty($data['datefrom']) and (!empty($data['datefrom']))) {
			$sql .= " AND oc.date_commissioned between '" . $data['datefrom']."' and '".$data['dateto']."'";
		}		
		
		$sql .= " GROUP BY concat(ou.firstname, ' ', ou.middlename, ' ', ou.lastname), ct.description ";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 5;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		////echo "<br>".$sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}	
	
	function getFinanceCommissionList($user_id, $data = array()) {

		$sql  = "select ou.user_id, ou.username, concat(ou.firstname, ' ', ou.middlename, ' ', ou.lastname) name,
					  ct.description, oc.amount, oc.tax, oc.date_commissioned
				  from oc_user oi
				  join oc_user ou on (ou.code = oi.code)
				  join oc_commission oc on(ou.user_id = oc.user_id)
				  join commission_type ct on(oc.commission_type_id = ct.commission_type_id)
				 where 1 = 1 ";
	    $query = $this->db->query($sql);	

		if (!empty($data['username'])) {
			$sql .= " AND oi.username = '" . $data['username']."'";
		}		
		
		if (!empty($data['datefrom']) and (!empty($data['datefrom']))) {
			$sql .= " AND oc.date_commissioned between '" . $data['datefrom']."' and '".$data['dateto']."'";
		}		
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 5;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		////echo "<br>".$sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}
	
	public function getTotalFinanceCommissionList($user_id, $data = array()) {
		
		$sql  = "select count(1) as total
				  from oc_user oi
				  join oc_user ou on (ou.code = oi.code)
				  join oc_commission oc on(ou.user_id = oc.user_id)
				  join commission_type ct on(oc.commission_type_id = ct.commission_type_id)
				 where 1 = 1";
	    

		if (!empty($data['username'])) {
			$sql .= " AND oi.username = '" . $data['username']."'";
		}		
		
		if (!empty($data['datefrom']) and (!empty($data['datefrom']))) {
			$sql .= " AND oc.date_commissioned between '" . $data['datefrom']."' and '".$data['dateto']."'";
		}
		
		$query = $this->db->query($sql);
		
		////echo "<br>".$sql;
		return $query->row['total'];	
	}	

	public function generateSerials($data = array()){
		set_time_limit(0);
		date_default_timezone_set('Asia/Manila');
		
		$sql = "select concat(SUBSTRING(firstname,1,1),SUBSTRING(middlename,1,1),SUBSTRING(lastname,1,1)) abbr from oc_user where user_id = ".$this->user->getId();
		$query = $this->db->query($sql);
		
		$abbr = $query->row['abbr'];

		if(isset($data['item_id'])) {
		
			if($data['item_id'] <> 0) {
				$no_of_serials = 500;
				if(isset($data['no_of_serials'])) {
					$no_of_serials = $data['no_of_serials'];
				}
				
				$sql = "select user_group_id, REPLACE(REPLACE(REPLACE( item_code, ' ', '' ), ' ',''), ' ','') no_blank_item_code from gui_items_tbl where item_id = ".$data['item_id'];
				$query = $this->db->query($sql);
				
				$user_group_id = $query->row['user_group_id']; 
				$batch_code = $abbr.$query->row['no_blank_item_code'].date("YmdHms");

				$cashier_flag = 2;
				$uni_quantity = 0;

				
				$serial_prefix = "";
				
				if($data['item_id'] == 1) {
					$serial_prefix = "RES";
				} else if($data['item_id'] == 2) {
					$serial_prefix = "BRZ";
				} else if($data['item_id'] == 3) {
					$serial_prefix = "SLV";
				} else if($data['item_id'] == 4) {
					$serial_prefix = "GLD";
				}  
				
				for ($i=0;$i<$no_of_serials;$i++){
					////////echo $i;
							 
					$randomValueSerials = $this->Random('oc_serials', 'serial_code');
					$randomValuePassword = $this->Random(); 
					$sql = "SELECT count(1) as total from oc_serials where serial_code = '".$serial_prefix.$randomValueSerials."'";
					$query_count = $this->db->query($sql);  
					 
					if ($query_count->row['total']<1){

						$sql = "INSERT INTO oc_serials(serial_code, password, batch, date_added, branch_id,item_id,user_group_id, paid_flag, cashier_flag, uni_quantity, com_deduct) 
						values ('".$serial_prefix.$randomValueSerials."','".$randomValuePassword."','" .$batch_code. "', '".$this->user->now()."', ".$data['branch_id'].",".$data['item_id'].",".$user_group_id.", ".$data['paid_flag'].", ".$cashier_flag.", ".$uni_quantity.", ".$data['com_deduct']."  )";	
						////////echo "<br>".$sql2;
						$this->db->query($sql);
										
					}
					
				}
				
				$code = $this->Random('oc_batch', 'code');
				
				$sql  = "insert into oc_batch (batch, code, date_added, branch_id,item_id,user_group_id, created_by) ";
				$sql .= "values('".$batch_code."', '".$code."', '".$this->user->now()."', ".$data['branch_id'].",".$data['item_id'].",".$user_group_id.",".$this->user->getId().")";
				$this->db->query($sql); 
				
				return "Generated ".$no_of_serials." serials with batch ".$batch_code.".";

			}
		}
		

		return "Something wrong.";

	}			
	
	public function Random($table = "", $column = "", $length = 8) {
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
		
		$unique = 0;
		
		while($unique == 0) {			
			//first count of $keys is empty so "1", remaining count is 1-6 = total 7 times
			while(count($keys) < $length) {
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
			
			if($table == "" and $column == "") {
				$unique = 1;
			} else {
				$sql = "select count(1) total from ".$table." where ".$column." = '".$random_chars."'";
				$query = $this->db->query($sql);
				if($query->row['total'] == 0) {
					$unique = 1;
				}
			} 
		}

		return $random_chars;
	}	

	public function getEncashmentTypes() {
		$sql = "select * from oc_encashment_type";
		$query = $this->db->query($sql); 
		return $query->rows;
	}
 	
	function getEncashmentDetails($data = array()) {
		$sql = "SELECT a.encashment_id, a.encashment_det_id, a.user_id, a.account_id, c.*
				  FROM oc_encashment_details a
				  JOIN oc_serials b on(a.user_id = b.user_id and a.account_id = b.account_id)
				  JOIN oc_payout_comp_tbl c on(b.item_id = c.item_id)
				 where a.encashment_id = '".$data['encashment_id']."'";
		
		$query = $this->db->query($sql);
		
		return $query->rows;		
	}
	
	public function checkIfBelongToGroup($user_id) {
		//////echo "checkIfBelongToGroup";
		$ret = false;
		$code_cur_user = "acymc";
		$code_sel_user = "bcymc";
		$sql = "select code from oc_user where user_id = ".$this->user->getId();
		$query = $this->db->query($sql);
		if(isset($query->row['code'])) {
			$code_cur_user = $query->row['code'];
		}
		$sql = "select code from oc_user where user_id = ".$user_id;
		$query = $this->db->query($sql);
		if(isset($query->row['code'])) {
			$code_sel_user = $query->row['code'];
		}

		if($code_sel_user == $code_cur_user) {
			$ret = true;
			////////echo "true";
		}

		return $ret;
	}
	
	public function checkBinaryVer3($sponsor, $com_user_package) {

		$this->log->write("checkBinaryVer3 ==> base_user_id = ".$sponsor);
	
		$base_user_id = $sponsor;
		
		$sql = "select b.points, b.item_id
				  from oc_serials a
				  join gui_items_tbl b on(a.item_id = b.item_id)
				 where a.user_id = ".$sponsor;
		$query = $this->db->query($sql);
		//echo $sql."<br>";
		$points = $query->row['points'];				
		$base_points = $query->row['points'];				
		$base_item_id = $query->row['item_id'];

		$this->log->write("checkBinaryVer3 initial_values, points = ".$points.", base_item_id = ".$base_item_id);
		
		//starting from the downline
		$sql = "select oi.sponsor_user_id, oi.position from oc_infinity oi where oi.user_id = ".$sponsor." order by oi.level ";
		$query = $this->db->query($sql);
		//echo $sql."<br>";
		$uplines = $query->rows;
		
		if($points > 0) {
		
			//update the oc_user_points
			foreach($uplines as $upline) { 
				$position = $upline['position'];
				$upline_sponsor = $upline['sponsor_user_id'];
				$upline_user_group_id = 34;
				$sponsor_alias = "DEALER";
				$points = $base_points;
				
				$sql = "select b.item_id, c.description alias, a.user_group_id 
				          from oc_user a 
						  join oc_serials b on(a.user_id = b.user_id)
						  join gui_items_tbl c on(b.item_id = c.item_id)
						 where a.user_id = ".$upline_sponsor;
				$query = $this->db->query($sql);
								
				if(isset($upline['sponsor_user_id'])) {
					$upline_user_group_id = $query->row['user_group_id']; 
					$sponsor_alias = $query->row['alias'];
					$sponsor_item_ids = $query->row['item_id'];
				}
				
				if($upline_user_group_id != 34) {
					//if left
					if($upline['position'] == "L") {
						$sql = "update oc_user_points set left_points = left_points + ".$points." where user_id = ".$upline['sponsor_user_id'];
						$this->db->query($sql);
						$this->log->write("checkBinaryVer3, sql = ".$sql);					
						//insert into table of points history
						$sql = "insert into oc_user_points_hist set user_id = ".$upline['sponsor_user_id'].", source_alias = '".$sponsor_alias."', source_alias2 = '".$com_user_package."', source_user_id = ".$base_user_id.", movement = 'IN', left_points = ".$points.", date_added = '".$this->user->now()."'";					
						$this->db->query($sql);
						$this->log->write("checkBinaryVer3, sql = ".$sql);					
					//if right
					} else {
						$sql = "update oc_user_points set right_points = right_points + ".$points." where user_id = ".$upline['sponsor_user_id'];
						$this->db->query($sql);
						$this->log->write("checkBinaryVer3, sql = ".$sql);
										
						$sql = "insert into oc_user_points_hist set user_id = ".$upline['sponsor_user_id'].",  source_alias = '".$sponsor_alias."', source_alias2 = '".$com_user_package."', source_user_id = ".$base_user_id.", movement = 'IN', right_points = ".$points.", date_added = '".$this->user->now()."'";					
						$this->db->query($sql);
						$this->log->write("checkBinaryVer3, sql = ".$sql);
					}
				}				
			}
		
			foreach($uplines as $upline) { 
				//get left and right remaining points
				$upline_sponsor = $upline['sponsor_user_id'];
				$left_diff = 0;
				$left_diff = 0;
				
				$sql = "select left_points - left_used_points - left_flushed_points left_diff
							  ,right_points - right_used_points - right_flushed_points right_diff
						  from oc_user_points 
						  where user_id = ".$upline_sponsor;			
				$query = $this->db->query($sql);
				$this->log->write("checkBinaryVer3, sql = ".$sql);
				
				if(isset($query->row['left_diff'])) {
					$left_diff = $query->row['left_diff'];
				} else {
					$left_diff = 0;
				}

				if(isset($query->row['right_diff'])) {
					$right_diff = $query->row['right_diff'];
				} else {
					$right_diff = 0;
				}

				$this->log->write("checkBinaryVer3, left_diff = ".$left_diff.", right_diff = ".$right_diff);				
				
				//get the shift of the day
				//shift 1 = 12:01 am to 12:00nn
				//shift 2 = 12:01 pm to 12:00mn
				$shift = 1;			
				
				//get the recent item id of package used by the upline
				$sql = "select b.item_id, b.description alias, b.pairs
						  from oc_serials a
						  join gui_items_tbl b on(a.item_id = b.item_id)
						 where a.user_id = ".$upline_sponsor;
				$query = $this->db->query($sql);
				//echo $sql."<br>";
				$sponsor_item_id = $query->row['item_id'];	
				$sponsor_alias = $query->row['alias'];	
				$max_pairs = $query->row['pairs'];	

				$upline_comm_per_shift = 0;							
				
				$sql = "select count(1) total from oc_ewallet_hist where user_id = ".$upline_sponsor." and date(date_added) = '".$this->user->nowDate()."' and commission_type_id in(2)";
				$query = $this->db->query($sql);
				
				$upline_comm_per_shift = $query->row['total'];			
				
				//check if the present day commission total >= 8
				if($upline_comm_per_shift >= $max_pairs) {				
					
					//flush points
					$sql = " update oc_user_points 
											set left_used_points = left_used_points + ".PAIRING."
											   ,right_used_points = right_used_points + ".PAIRING."
											   ,pairs = pairs + 1
										  where user_id = ".$upline_sponsor;
					$this->db->query($sql);
				} else {
					//insert valid commission
					//add used points
					//if both remaining points > 0	
					if($left_diff >= PAIRING && $right_diff >= PAIRING) {				
						//determine which is lower
						$n = 0;
						if($left_diff > $right_diff) {
							$n = $right_diff;
						} else {
							$n = $left_diff;
						}
						
						//loop n times where n = the lower remaining points
						for($i = 0; $i < $n; $i+=PAIRING ) {
							$pairs = 0;
							$upline_comm_per_shift2 = 0;
							$remaining_i = $n - $i;
							if($remaining_i < PAIRING) {
								break;
							}
							
							//check again if the commission is already greater than allowable per day
							$sql = "select count(1) total from oc_ewallet_hist where user_id = ".$upline_sponsor." and date(date_added) = '".$this->user->nowDate()."' and commission_type_id in(2)";
							$query = $this->db->query($sql);			
							$upline_comm_per_shift2 = $query->row['total'];
							
							if($upline_comm_per_shift2 >= $max_pairs) {
								//just use the points
								$sql = " update oc_user_points 
											set left_used_points = left_used_points + ".PAIRING."
											   ,right_used_points = right_used_points + ".PAIRING."
											   ,pairs = pairs + 1
										  where user_id = ".$upline_sponsor;
								$this->db->query($sql);
								$this->log->write($sql);
								$this->log->write("flushing ".$PAIRING." points of ".$upline_sponsor);
							} else {
								//commission
								$sql = " update oc_user_points 
											set left_used_points = left_used_points + ".PAIRING."
											   ,right_used_points = right_used_points + ".PAIRING."
											   ,pairs = pairs + 1
										  where user_id = ".$upline_sponsor;
								$this->db->query($sql);
								
								$this->insertEwallet($upline_sponsor, $base_user_id, 2, PAIRING, 38);
							
								$sql = "insert into oc_user_points_hist set user_id = ".$upline_sponsor.",  source_alias = '".$sponsor_alias."', source_alias2 = '".$com_user_package."', source_user_id = ".$base_user_id.", movement = 'OUT', left_used_points = ".PAIRING.", right_used_points = ".PAIRING.", date_added = '".$this->user->now()."'";					
								$this->db->query($sql);										
																								
							}

							$sql = "select count(1) total from oc_ewallet_hist where user_id = ".$upline_sponsor." and date(date_added) = '".$this->user->nowDate()."' and commission_type_id in(2)";
							$query = $this->db->query($sql);
							$upline_comm_per_shift3 = $query->row['total'];
							
							if($upline_comm_per_shift3 == $max_pairs) {	
								//just use the points
								$sql = " update oc_user_points 
											set left_used_points = left_used_points + ".PAIRING."
											   ,right_used_points = right_used_points + ".PAIRING."
											   ,pairs = pairs + 1
										  where user_id = ".$upline_sponsor;
								$this->db->query($sql);
								$this->log->write($sql);
								$this->log->write("flushing ".$PAIRING." points of ".$upline_sponsor);
							}							
						}
					}					
				}
				 
								
			}		
		}		
	}	
	
	public function flushPoints($upline_sponsor, $com_user_id, $shift) {

		$sql = "select c.description alias 
				  from oc_user a 
				  join oc_serials b on(a.user_id = b.user_id and b.uni_code_id = 0)
				  join gui_items_tbl c on(b.item_id = c.item_id)
				 where a.user_id = ".$upline_sponsor;
		$query = $this->db->query($sql);
						
		$alias = $query->row['alias'];
						
		$left_diff = 0;
		$right_diff = 0;
		
		$sql = "select left_points - left_used_points - left_flushed_points left_diff
					  ,right_points - right_used_points - right_flushed_points right_diff
				  from oc_user_points 
				  where user_id = ".$upline_sponsor;			
		$query = $this->db->query($sql);
		//echo $sql."<br>";
		
		if(isset($query->row['left_diff'])) {
			$left_diff = $query->row['left_diff'];
		} else {
			$left_diff = 0;
		}

		if(isset($query->row['right_diff'])) {
			$right_diff = $query->row['right_diff'];
		} else {
			$right_diff = 0;
		}

		$sql = " update oc_user_points 
					set left_flushed_points = left_flushed_points + ".$left_diff."
					   ,right_flushed_points = right_flushed_points + ".$right_diff."
				  where user_id = ".$upline_sponsor;
		$this->db->query($sql);
		//echo $sql."<br>";
		
		
		//insert to flush points history left and right
		//insert to flush points history L
		$sql = "INSERT INTO oc_flush_points_hist (user_id, com_user_id, points, position, date_added, dates, shift) values ";
		$sql.= " (".$upline_sponsor.", ".$com_user_id.", ".$left_diff.", 'L', '".$this->user->now()."', '".$this->user->nowDate()."', ".$shift.") ";
		$this->db->query($sql);
		//echo $sql."<br>";
		//insert to flush points history R
		$sql = "INSERT INTO oc_flush_points_hist (user_id, com_user_id, points, position, date_added, dates, shift) values ";
		$sql.= " (".$upline_sponsor.", ".$com_user_id.", ".$right_diff.", 'R', '".$this->user->now()."', '".$this->user->nowDate()."', ".$shift.") ";
		$this->db->query($sql);
		
		$sql = "insert into oc_user_points_hist set user_id = ".$upline_sponsor.",  source_alias = '".$alias."', source_user_id = ".$com_user_id.", movement = 'OUT', left_flushed_points = ".$left_diff.", right_flushed_points = ".$right_diff.", date_added = '".$this->user->now()."'";					
		$this->db->query($sql);
		
	}
	
	public function updatePayoutOpt($data = array()) {
		$sql = " select code, bank_name, account_no from oc_user where user_id = ".$this->user->getId();
		$query = $this->db->query($sql);
		$gcode = $query->row['code'];
		
		if(isset($data['bank_name']) && isset($data['bank_name']) && isset($data['bank_name'])) {
			if(($data['bank_name']<>"") && ($data['bank_name']<>"") && ($data['bank_name']<>"")) {
				$sql = " update oc_user set bank_name = '".$data['bank_name']."', account_no = '".$data['account_no']."', account_name = '".$data['account_name']."' ";
				$sql.= " where code = '".$gcode."' and bank_name is null and account_no is null and account_name is null ";
				$this->db->query($sql);				
			}
		}
	}
	
	public function getTotalEncashmentList($user_id, $data = array()) {
		
		$sql  = " SELECT count(1) as total from oc_encashment where 1 = 1 ";
		
		if($this->user->getUserGroupId() <> 28) {
			$sql .= " and user_id = ".$user_id;
		}
		
		$query = $this->db->query($sql);
		
		////echo $sql."<br>";
		return $query->row['total'];	
	}

	function getEncashmentList($user_id, $data = array()) {
		if($this->user->getUserGroupId() <> 12) {
			$sql  = " select oe.encashment_id, concat(ou.firstname, ' ', ou.middlename, ' ', ou.lastname) as name, 
							 ou.username, gst.description, oe.amount, ou.bank_name, ou.account_no, ou.account_name,
							 oe.date_requested, oe.proc_fee, oe.tax, oe.enc_type
					 from oc_user oi
					 join oc_user ou on(oi.code = ou.code)
					 join oc_encashment oe on(ou.user_id = oe.user_id)
					 join gui_status_tbl gst on(oe.status = gst.status_id)
					where 1 = 1 ";
			
			
			$sql .= " AND oi.user_id = ".$user_id;

			if(isset($data['datefrom'])) {
				if($data['datefrom'] <> "") {
					$sql.= " AND oe.date_requested >= '".$data['datefrom']."'";
				}
			}

			if(isset($data['dateto'])) {
				if($data['dateto'] <> "") {
					$sql.= " AND oe.date_requested <= '".$data['dateto']."'";
				}
			} 			
			
			$sql .= "		  order by encashment_id desc ";	
		

		
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 5;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			////echo "<br>".$sql;
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		} else {
			$sql  = " select oe.encashment_id, concat(ou.firstname, ' ', ou.middlename, ' ', ou.lastname) as name, 
							 ou.username, gst.description, oe.amount, ou.bank_name, ou.account_no, ou.account_name,
							 oe.date_requested, oe.tax, oe.proc_fee
					    from oc_encashment oe
					    join oc_user ou on(oe.user_id = ou.user_id)
					    join gui_status_tbl gst on(oe.status = gst.status_id)
					   where 1 = 1 ";
			

			if(isset($data['datefrom'])) {
				if($data['datefrom'] <> "") {
					$sql.= " AND oe.date_requested >= '".$data['datefrom']."'";
				}
			}

			if(isset($data['dateto'])) {
				if($data['dateto'] <> "") {
					$sql.= " AND oe.date_requested <= '".$data['dateto']."'";
				}
			}			

			if(isset($data['username'])) {
				if($data['username'] <> "") {
					$sql.= " AND oe.user_id in( select oiu.user_id from oc_user oii join oc_user oiu on(oii.code = oiu.code) where oii.username = '".$data['username']."' )";
				}
			}
			
			
			
			
			$sql .= "		  order by encashment_id desc ";	

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 5;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			////echo "<br>".$sql;
			
			$query = $this->db->query($sql);
			
			return $query->rows;			
		}
	}	
	
	public function determineplacement($user_id) {

		$sql = "select a.sponsor_user_id
				  from oc_unilevel a
				  join oc_user b on(a.sponsor_user_id = b.user_id)
				 where a.user_id = ".$user_id."
				   and b.user_group_id = 13
				  order by a.level asc
				  limit 1 ";
		$query = $this->db->query($sql);
		$refer_by_id = $query->row['sponsor_user_id'];
		
		$placement = array();
		$placement['position'] = "L";
		$placement['upline'] = $refer_by_id;
		
		$sql = "select auto_spill_setup from oc_user where user_id = ".$refer_by_id;
		$query = $this->db->query($sql);	
		$auto_spill_setup = $query->row['auto_spill_setup'];
		
		//check the set-up of the upline
		//extremeleft = EXTREME LEFT
		//extremeright = EXTREME RIGHT
		
		$placement['upline'] = $this->checkTheLastInPosition($refer_by_id, $auto_spill_setup);
		$placement['position'] = $auto_spill_setup;
		
		return $placement;
	}
	

	public function checkTheLastInPosition($refer_by_id, $position) {
		$found = 0;
		while($found == 0) {
			$sql = "select user_id from oc_infinity 
					 where sponsor_user_id = ".$refer_by_id." 
					   and position = '".$position."' 
					   and level = 1 ";
			$query = $this->db->query($sql);
			if(isset($query->row['user_id'])) {
				$found = 0;
				$refer_by_id = $query->row['user_id'];
			} else {
				$found = 1;
			}			
		}
		return $refer_by_id;		
	}	

	public function exportToCsv($data = array()) {
		if(isset($data['payout_id'])) {
			$sql = " select b.username, concat(b.firstname, ' ', b.middlename, ' ', b.lastname) name, 
							b.bank_name, b.account_name, b.account_no, sum(a.amount) net_amount
						from oc_commission a
						join oc_user b on a.user_id = b.user_id 
						where a.status = 38 and payout_id = ".$data['payout_id']."
						group by b.username
						order by sum(amount+tax) desc ";
					   
			$query = $this->db->query($sql);
			////echo $sql."<br>";
			return $query->rows;
		}
	}

	
	public function getTotalEncashmentAdminList($user_id, $data = array()) {
		
		$sql  = " SELECT count(1) as total from oc_encashment oe where 1 = 1 ";
		
		$query = $this->db->query($sql);

		if(isset($data['datefrom'])) {
			if($data['datefrom'] <> "") {
				$sql.= " AND oe.date_requested >= '".$data['datefrom']."'";
			}
		}

		if(isset($data['dateto'])) {
			if($data['dateto'] <> "") {
				$sql.= " AND oe.date_requested <= '".$data['dateto']."'";
			}
		} 			

		if(isset($data['username'])) {
			if($data['username'] <> "") {
				$sql.= " AND oe.user_id in( select oiu.user_id from oc_user oii join oc_user oiu on(oii.code = oiu.code) where oii.username = '".$data['username']."' )";
			}
		}
		
		//echo $sql."<br>";
		return $query->row['total'];	
	}	

	function getEncashmentAdminList($user_id, $data = array()) {

		$sql  = " select oe.encashment_id, ou.username, concat(ou.firstname, ' ', ou.middlename, ' ', ou.lastname) as name, ou.tin,
						 oe.enc_type, ou.contact, gst.status_id, gst.description, oe.amount, oe.proc_fee, ou.bank_name, ou.account_no, 
						 ou.account_name, oe.date_requested
					from oc_encashment oe
					join oc_user ou on(oe.user_id = ou.user_id)
					join gui_status_tbl gst on(oe.status = gst.status_id)
				   where 1 = 1 ";
		

		if(isset($data['datefrom'])) {
			if($data['datefrom'] <> "") {
				$sql.= " AND oe.date_requested >= '".$data['datefrom']."'";
			}
		}

		if(isset($data['dateto'])) {
			if($data['dateto'] <> "") {
				$sql.= " AND oe.date_requested <= '".$data['dateto']."'";
			}
		}			

		if(isset($data['username'])) {
			if($data['username'] <> "") {
				$sql.= " AND oe.user_id in( select oiu.user_id from oc_user oii join oc_user oiu on(oii.code = oiu.code) where oii.username = '".$data['username']."' )";
			}
		}
		
		if(isset($data['status'])) {
			if($data['status'] <> "") {
				$sql.= " AND oe.status = ".$data['status'];
			}
		}		
		
		$sql .= "		  order by encashment_id desc ";	

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 5;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		//echo "<br>".$sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;			
		
	}

	public function updateEncashmentStatus($enc_id, $status_id) {
		
		$sql = "select status from oc_encashment where encashment_id = ".$enc_id;
		$query = $this->db->query($sql);
		
		if(isset($query->row['status'])) {
			$cur_status = $query->row['status'];
			if($cur_status <> 32 ) {
				$sql = "update oc_encashment set status = ".$status_id." where encashment_id = ".$enc_id;
				$this->db->query($sql);
			}
		}
		
	}
	
	public function encodeUnilevel($data = array()) {
		//echo "encodeUnilevel check 1 <br>";
		if(isset($data['username'])){
			//echo "encodeUnilevel check 2 <br>";
			if($data['username'] != "") {
				if(isset($data['cv'])){
					if($data['cv'] != "") {
						$cv = (int) $data['cv'];												
						$modulus = $cv % 5;												
						if($modulus != 0) {														
							return "You can only encode CV divisible by 5";													
						} else {
							//get unused cv
							$unused_cv = 0;
							$sql = "select (cv - used_cv) as unused_cv from oc_user_points where user_id = ".$this->user->getId();
							$query = $this->db->query($sql);
							$unused_cv = $query->row['unused_cv'];
									
							if($unused_cv >= $cv) {
								//get the user_id, item_id of the username
								$sql = " select a.user_id
										   from oc_user a
										  where a.username = '".$data['username']."' ";
										  
								$query = $this->db->query($sql);
								
								$user_id = 0;
								$adm_rank_id = 0;
								
								if(!isset($query->row['user_id'])) {
									return "Please input a valid username.";
								} else {
									$user_id = $query->row['user_id'];
								}
										

								//insert into oc_unilevel_codes														
								$sql = " insert into oc_unilevel_codes
											set user_id = ".$user_id."
											   ,added_by = ".$this->user->getId()." 
											   ,quantity = ".$cv." 
											   ,date_added = '".$this->user->now()."' ";
								
								$this->db->query($sql);
								
								$uni_code_id = $this->db->getLastId();

								if($user_id <> $this->user->getId()) {
									//insert into oc_unilevel_codes														
									$sql = " insert into oc_cv_hist
												set user_id = ".$this->user->getId()."
												   ,source_user_id = ".$this->user->getId()."
												   ,event = 'ENCODE UNILEVEL FOR ".$data['username']."' 
												   ,cv = ".$cv." 
												   ,uni_code_id = ".$uni_code_id." 
												   ,date_added = '".$this->user->now()."' ";
									$this->db->query($sql);								
								}
								
								//insert into oc_unilevel_codes														
								$sql = " insert into oc_cv_hist
											set user_id = ".$user_id."
											   ,source_user_id = ".$this->user->getId()."
											   ,event = 'UNILEVEL' 
											   ,cv = ".$cv." 
											   ,uni_code_id = ".$uni_code_id." 
											   ,date_added = '".$this->user->now()."' ";
								$this->db->query($sql);

								$sql = " update oc_user_points set used_cv = used_cv + ".$cv." where user_id = ".$this->user->getId();								
								$this->db->query($sql);
								
								$sql = "select sponsor_user_id, level from oc_unilevel where user_id = ".$user_id." order by level ";
								$query = $this->db->query($sql);
								$sponsors = $query->rows;
								
								foreach($sponsors as $sp) {
									//echo "sponsor>>".$sp['sponsor_user_id'].", level>>".$sp['level']."<br>";
									$sql  = " select count(1) total from oc_group_unilevel where months = '".$this->user->nowGetFirstDayOfMonth()."' and user_id = ".$sp['sponsor_user_id'];
									$query = $this->db->query($sql);
									if($query->row['total'] > 0) {
										$sql = " update oc_group_unilevel set quantity = quantity + ".$cv." where months = '".$this->user->nowGetFirstDayOfMonth()."' and user_id = ".$sp['sponsor_user_id'];
										$this->db->query($sql);
									} else {
										$sql = " insert into oc_group_unilevel set quantity =  ".$cv." , months = '".$this->user->nowGetFirstDayOfMonth()."' , user_id = ".$sp['sponsor_user_id'].", date_added = '".$this->user->now()."'";
										$this->db->query($sql);
									}
								}								
								
								return $cv." CV encoded to ".$data['username'].".";
							} else {
								return "Unused CV is not enough.";
							}												}
					}
				}
			} else {
				return "Username is mandatory.";
			}
		} else {
			return "Username is mandatory.";
		}
	}
	
	public function getTotalEncodedUnilevel($data = array()) {	
		$sql = " select count(1) as total
		           from oc_user ou
				   join oc_unilevel_codes a on(ou.user_id = a.user_id)
				   join gui_trxn_dtl_tbl b on(a.trxn_dtl_id = b.trxn_dtl_id)
				   join gui_trxn_tbl c on(b.trxn_id = c.trxn_id)
				  where 1 = 1 ";
		
		if(isset($data['username'])) {
			if($data['username'] <> "") {
				if($this->user->getUserGroupId() == 36) {
					$sql .= " and ou.username = '".$data['username']."' ";
				}
			}
		} else {			
			$sql .= " and a.user_id = '".$this->user->getId()."' ";			
		}
		
		$query = $query = $this->db->query($sql);
		
		return $query->row['total'];		
	}

	public function getEncodedUnilevel($data = array()) {
		$sql = " select oua.username encoder, ou.username member, a.uni_code_id, a.unilevel_code, a.trxn_dtl_id, b.trxn_id, 
						c.trxn_date, a.date_added, a.quantity unilevel
		           from oc_user ou
				   join oc_unilevel_codes a on(ou.user_id = a.user_id)
				   join oc_user oua on(oua.user_id = a.added_by)
				   left join gui_trxn_dtl_tbl b on(a.trxn_dtl_id = b.trxn_dtl_id)
				   left join gui_trxn_tbl c on(b.trxn_id = c.trxn_id)
				   left join oc_serials d on(d.serial_code = a.serial_code)
				  where 1 = 1 ";
		
		if(isset($data['username'])) {
			if($data['username'] <> "") {
				if($this->user->getUserGroupId() == 36) {
					$sql .= " and ou.username = '".$data['username']."' ";
				} else {
					$sql .= " and a.user_id = '".$this->user->getId()."' ";
				}
			}
		} else {			
			$sql .= " and a.user_id = '".$this->user->getId()."' ";			
		}

		$sql .= " order by a.uni_code_id desc ";	

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 5;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		//echo "sql >> ".$sql."<br>";
		
		$query = $query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getGiftCertCount() {
		$sql = " select sum(c.incentive_points) incentive_points
				  from oc_user a
				  join oc_user b on(a.code = b.code)
				  join oc_user_points c on(b.user_id = c.user_id)
				 where a.user_id = ".$this->user->getId();
				 
		$query = $this->db->query($sql);		
		return $query->row['incentive_points'];
	}

	public function getGiftCertClaimedCount() {
		
		$sql = " select sum(c.used_incentive_points) used_incentive_points
				  from oc_user a
				  join oc_user b on(a.code = b.code)
				  join oc_user_points c on(b.user_id = c.user_id)
				 where a.user_id = ".$this->user->getId();
				 
		$query = $this->db->query($sql);		
		return $query->row['used_incentive_points'];
	}
	
	public function claimGiftCert($data = array()) {
		
		$sql = "select main_account from oc_user where user_id = ".$this->user->getId();
		$query = $this->db->query($sql);
		$main_account = $query->row['main_account'];
		
		if($main_account == 0) {
			return "Only main account can claim gift certificates. Please login on your main account to claim incentives.";
		}
	
		$sql = " select sum(c.incentive_points) incentive_points
				  from oc_user a
				  join oc_user b on(a.code = b.code)
				  join oc_user_points c on(b.user_id = c.user_id)
				 where a.user_id = ".$this->user->getId();
				 
		$query = $this->db->query($sql);		
		$total_incentives = $query->row['incentive_points'];	
	
		$sql = " select sum(c.used_incentive_points) used_incentive_points
				  from oc_user a
				  join oc_user b on(a.code = b.code)
				  join oc_user_points c on(b.user_id = c.user_id)
				 where a.user_id = ".$this->user->getId();
				 
		$query = $this->db->query($sql);		
		$total_used_incentives = $query->row['used_incentive_points'];
		
		$available_gc = $total_incentives - $total_used_incentives;

		//echo "available_gc >>> ".$available_gc."<br>";
		
		if(isset($data['quantity']) && (isset($data['combo_id']))) {
			if($data['quantity'] != "") {			
				
				if($available_gc >= $data['quantity']) {

					$randomGCSerials = $this->Random('oc_claim_gc', 'gc_code');		
							
					$sql = " INSERT INTO oc_claim_gc ";
					$sql.= " 	SET user_id = ".$this->user->getId();
					$sql.= " 	  , quantity = ".$data['quantity'];
					$sql.= " 	  , combo_id = ".$data['combo_id'];
					$sql.= " 	  , gc_code = '".$randomGCSerials."'";
					$sql.= " 	  , date_added = '".$this->user->now()."'";
					$this->db->query($sql);
					
					$sql = " UPDATE oc_user_points set used_incentive_points = used_incentive_points + ".$data['quantity']." where user_id = ".$this->user->getId();
					$this->db->query($sql);
					
				} else {
					return "Number of Incentive Points for claiming is greater than Available Incentive Points.";
				}
			} else {
				return "Input Number of Incentive Points to be claimed.";
			}		
		}
	}

	public function claimGiftCertList($data = array()) {
	
		$sql = " SELECT c.claim_id, c.quantity, c.gc_code, c.date_added, c.release_date
					   ,case when c.combo_id = 1 then '1 Perfume'
							 when c.combo_id = 2 then '2 Green4Life'
							 when c.combo_id = 3 then '2 Gluta Soap'
							 when c.combo_id = 4 then '1 Gluta and 1 Kojic Soap'
							 when c.combo_id = 5 then '2 Kojic Soap'
							 when c.combo_id = 6 then '7 Bottles of Perfume'
							 when c.combo_id = 7 then '10 Box Shape Up Coffee'
							 when c.combo_id = 8 then 'Zenfone Max'
							 when c.combo_id = 9 then 'Ipad Mini'
							 when c.combo_id = 10 then 'Iphone 6 Plus'
							 when c.combo_id = 11 then 'Toyota Downpayment'
							 when c.combo_id = 12 then 'Toyota Amortization'
							 when c.combo_id = 13 then 'Montero Downpayment'
							 when c.combo_id = 14 then 'Montero Amortization'
						     else 'No Combination' end as combo
					   ,case when c.status = 2 then 'For Processing'
							 when c.status = 3 then 'Claimed'
						     else 'Requested' end as status							 
				   FROM oc_user a
				   JOIN oc_user b on(a.code = b.code)
				   JOIN oc_claim_gc c on(b.user_id = c.user_id)
				  WHERE a.user_id = ".$this->user->getId()."
				 ORDER BY claim_id DESC ";
				 
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
	}

	public function claimGiftCertListTotal($data = array()) {
	
		$sql = " SELECT count(1) as total							 
				   FROM oc_claim_gc 
				  WHERE user_id = ".$this->user->getId();
				  
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}	

	public function getGiftCertCountByStatus($status, $data = array()) {
		$sql = " SELECT sum(ocg.quantity) as total
				   FROM oc_claim_gc ocg
				   JOIN oc_user ou ON(ocg.user_id = ou.user_id)
				  WHERE ocg.status = ".$status;
				  
		
		
		if(isset($data['username'])) {
			if(!empty($data['username'])) {
				$sql.= " AND lower(ou.username) = lower('".$data['username']."')";
			}
		}

		if(isset($data['date_from'])) {
			if(!empty($data['date_from'])) {
				$sql.= " AND ocg.date_added >= '".$data['date_from']."'";
			}
		}		

		if(isset($data['date_to'])) {
			if(!empty($data['date_to'])) {
				$sql.= " AND ocg.date_added <= '".$data['date_to']."'";
			}
		}
		
		$query = $this->db->query($sql);
		
		//echo $sql."<br>";
		
		return $query->row['total'];
	}	

	public function getGiftCertClaimedCountAdmin() {
		$sql = " SELECT count(1) as total
				   FROM oc_claim_gc ocg
				   JOIN oc_user ou ON(ocg.user_id = ou.user_id)
				  WHERE 1 = 1 ";

		if(isset($data['gc_code'])) {
			if(!empty($data['gc_code'])) {
				$sql.= " AND ocg.gc_code = '".$data['gc_code']."'";
			}
		}				  
				  
		if(isset($data['status'])) {
			if(!empty($data['status'])) {
				$sql.= " AND ocg.status = '".$data['status']."'";
			}
		}
				  
		if(isset($data['username'])) {
			if(!empty($data['username'])) {
				$sql.= " AND lower(ou.username) = lower('".$data['username']."')";
			}
		}

		if(isset($data['date_from'])) {
			if(!empty($data['date_from'])) {
				$sql.= " AND ocg.date_added >= '".$data['date_from']."'";
			}
		}		

		if(isset($data['date_to'])) {
			if(!empty($data['date_to'])) {
				$sql.= " AND ocg.date_added <= '".$data['date_to']."'";
			}
		}
		
		$query = $this->db->query($sql);		  
		return $query->row['total'];
	}
	
	public function claimGiftCertListAdmin($data = array()) {
	
		$sql = " SELECT ocg.claim_id, ocg.quantity, ocg.gc_code, ocg.date_added, ocg.release_date, ocg.status
					   ,case when ocg.combo_id = 1 then '1 Perfume'
							 when ocg.combo_id = 2 then '2 Green4Life'
							 when ocg.combo_id = 3 then '2 Gluta Soap'
							 when ocg.combo_id = 4 then '1 Gluta and 1 Kojic Soap'
							 when ocg.combo_id = 5 then '2 Kojic Soap'
							 when ocg.combo_id = 6 then '7 Bottles of Perfume'
							 when ocg.combo_id = 7 then '10 Box Shape Up Coffee'
							 when ocg.combo_id = 8 then 'Zenfone Max'
							 when ocg.combo_id = 9 then 'Ipad Mini'
							 when ocg.combo_id = 10 then 'Iphone 6 Plus'
							 when ocg.combo_id = 11 then 'Toyota Downpayment'
							 when ocg.combo_id = 12 then 'Toyota Amortization'
							 when ocg.combo_id = 13 then 'Montero Downpayment'
							 when ocg.combo_id = 14 then 'Montero Amortization'
						     else 'No Combination' end as combo
					   ,case when ocg.status = 2 then 'For Processing'
							 when ocg.status = 3 then 'Claimed'
						     else 'Requested' end as status_desc
					   ,ou.username, concat(ou.firstname, ' ', ou.middlename, ' ', ou.lastname) name
				   FROM oc_claim_gc ocg
				   JOIN oc_user ou ON(ocg.user_id = ou.user_id)
				  WHERE 1 = 1 ";

		if(isset($data['gc_code'])) {
			if(!empty($data['gc_code'])) {
				$sql.= " AND ocg.gc_code = '".$data['gc_code']."'";
			}
		}				  
				  
		if(isset($data['status'])) {
			if(!empty($data['status'])) {
				$sql.= " AND ocg.status = '".$data['status']."'";
			}
		}
				  
		if(isset($data['username'])) {
			if(!empty($data['username'])) {
				$sql.= " AND lower(ou.username) = lower('".$data['username']."')";
			}
		}

		if(isset($data['date_from'])) {
			if(!empty($data['date_from'])) {
				$sql.= " AND ocg.date_added >= '".$data['date_from']."'";
			}
		}		

		if(isset($data['date_to'])) {
			if(!empty($data['date_to'])) {
				$sql.= " AND ocg.date_added <= '".$data['date_to']."'";
			}
		}

		$sql.= " ORDER BY claim_id DESC ";
		
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
	}
	
	public function getGiftCertCountUpdateStatus($data =  array()) {
		if(isset($data['claim_id'])) {
			if(isset($data['status'])) {
				$sql = "SELECT status FROM oc_claim_gc WHERE claim_id = ".$data['claim_id'];
				$query = $this->db->query($sql);		
				$status = $query->row['status'];

				if($status == 3) {
					return "GC is already claimed";
				} else {
					if($status == $data['status']) {
						return "GC is already updated.";
					} else {
						$sql = "UPDATE oc_claim_gc set status = ".$data['status'];
						if($data['status'] == 3) {
							$sql.= ", release_date = '".$this->user->now()."'";
							$sql.= ", released_by = '".$this->user->getId()."'";
						}
						$sql.= " WHERE claim_id = ".$data['claim_id'];
						$this->db->query($sql);
						return "GC is successfully updated.";
					}
				}
			} else {
				return "Provide a Status";
			}
		} else {
			return "Provide a GC Claim";
		}
	}

	public function getTotalUniGeneologyList($user_id, $data = array()) {

		$sql  = " SELECT count(1) as total from oc_unilevel 
				 where sponsor_user_id = ".$user_id;
		$query = $this->db->query($sql);
		////////echo $sql."<br>";
		return $query->row['total'];	
	}

	function getGeneologyList($user_id, $data = array()) {	
		if($data['start'] < 0) {
			$data['start'] = 0;
		}
		
		$sql  = " select a.user_id dl_user_id, 
					     b.username dl_username, concat(b.firstname, ' ', b.lastname) dl_desc, 
						 c.username sp_username, concat(c.firstname, ' ', c.lastname) sp_desc,
						 a.level, b.date_added,
						 case when b.activation_flag = 1 then 'Active' else 'In-Active' end act_flag
				  from oc_unilevel a
				  left join oc_user b on(a.user_id = b.user_id)
				  left join oc_user c on(c.user_id = b.refer_by_id)
				 where a.sponsor_user_id = ".$user_id." and a.level <= 3 and b.user_group_id = 56
				 order by a.level 
				 LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];

		//echo "<br>".$sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}
	
	function getCitydistributorList($user_id, $data = array()) {	
		if($data['start'] < 0) {
			$data['start'] = 0;
		}
		
		$sql  = "select a.user_id dl_user_id, 
				b.username dl_username, concat(b.firstname, ' ', b.middlename, ' ', b.lastname) dl_desc, 
				c.username sp_username, concat(c.firstname, ' ', c.middlename, ' ', c.lastname) sp_desc,
				a.level, b.date_added,
				case when b.activation_flag = 1 then 'Active' else 'In-Active' end act_flag
				from oc_unilevel a
				join oc_user b on(a.user_id = b.user_id)
				join oc_user c on(c.user_id = b.refer_by_id)
				where a.sponsor_user_id = ".$user_id." and a.level <= 3 and b.user_group_id = 57
				order by a.level 
				LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];

		//echo "<br>".$sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}
	
	public function updateGroupProfilePicture($pic) {
		$sql = "select code from oc_user where user_id = ".$this->user->getId();
		$query = $this->db->query($sql);
		$code = $query->row['code'];
		
		if(!empty($code)) {
			$sql = "update oc_user set profilepic = '".$pic."' where code = '".$code."'";
			$this->db->query($sql);
			return "Successful update of profile picture.";
		} 
	}

	public function getAccountHistory($data) {
		if(isset($data['user_id'])) {
			$sql = "select c.username, b.description, a.date_modified
						from oc_serials a
						join gui_items_tbl b on(a.item_id = b.item_id)
						join oc_user c on(a.user_id = c.user_id or a.o_user_id = c.user_id) 
						where (a.user_id = ".$data['user_id']." or a.o_user_id = ".$data['user_id'].")
						and a.uni_code_id = 0
						order by a.date_modified asc ";
			$query = $this->db->query($sql);			
			return $query->rows;
		} else {
			return null;
		}
	}
	
	public function getPointsHistory($data) {

		$user_id = $this->user->getId();
		
		if(isset($data['user_id'])) {
			$user_id = $data['user_id'];
		}
	
		$sql = "select a.source_alias status, b.username source, a.movement, 
						a.left_points, a.left_used_points, a.left_flushed_points, 
						a.right_points, a.right_used_points, a.right_flushed_points
					from oc_user_points_hist a
					join oc_user b on(a.source_user_id = b.user_id)
					where a.user_id = ".$user_id."
					order by a.date_added desc ";
		$query = $this->db->query($sql);			
		return $query->rows;

	}	
	
	public function getUnusedCV($data = array()) {
		
		$sql = "select cv - used_cv unused_cv from oc_user_points where user_id = ".$this->user->getId();
		$query = $this->db->query($sql);
		return $query->row['unused_cv'];
		
	}
		
	public function getTotalEwallet($user_id) {
		$sql = "select ewallet from oc_user_points where user_id = ".$user_id;
		$query = $this->db->query($sql);
		//echo $sql."<br>";
		return $query->row['ewallet'];		
	}
	
	public function getTotalEwalletList($user_id, $data = array()) {
	
		$sql  = "select count(1) as total
				  from oc_ewallet_hist
				 where user_id = ".$user_id;			
		
		$query = $this->db->query($sql);
		//echo $sql."<br>";
		return $query->row['total'];	
	}	

	public function getEwalletList($user_id, $data = array()) {
	
		$sql  = "select concat(ou.firstname, ' ', ou.middlename, ' ', ou.lastname) name, 
		                oeh.event, oeh.ewallet, oeh.date_added,
						concat(os.firstname, ' ', os.middlename, ' ', os.lastname) sname
				  from oc_user oi
				  join oc_user ou on (ou.code = oi.code)
				  join oc_ewallet_hist oeh on(oeh.user_id = ou.user_id)
				  join oc_user os on (os.user_id = oeh.source_user_id)
				 where oi.user_id = ".$user_id;			

		$sql.= " order by oeh.ewallet_hist_id desc ";		 
				 
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
	}
	
	public function transfer($data) {
		
		$sql = "select count(1) as total from oc_ewallet_hist where session_id = '".$data['session_id']."'";
		$query = $this->db->query($sql);
		$hist_total =  $query->row['total'];
		
		if($hist_total > 0) {
			return "Duplicate transaction. You might have accidentally refresh/reload the screen.";
		}		
		
		$user_id = 0;
		$sql = "select user_id from oc_user where lower(username) = lower('".$data['username']."')";
		$query = $this->db->query($sql);
		
		if(isset($query->row['user_id'])) {
			
			$user_id = $query->row['user_id'];
			
			$sql = "select (ewallet - used_ewallet) as ewallet from oc_user_points where user_id = ".$this->user->getId();
			$query = $this->db->query($sql);			


			if(isset($query->row['ewallet'])) {
				$ewallet =  $query->row['ewallet'];
			} else {
				$ewallet = 0;
			}
			
			if(isset($data['amount'])) {
				if(!empty($data['amount'])) {
					$amount =  $data['amount'];
				} else {
					$amount =  0;
				}
				
			} else {
				$amount = 0;
			}			

			if($ewallet > 0 and $amount > 0) {
				if($ewallet >= $amount) {
					$sql = "insert into oc_ewallet_hist(event, source_user_id, user_id, ewallet, date_added, session_id) ";
					$sql.= " values('TRANSFER-IN FROM ".$this->user->getUsername()."', ".$this->user->getId().",".$user_id.",".$data['amount'].",'".$this->user->now()."', '".$data['session_id']."')";
					$this->db->query($sql);	

					$sql = " update oc_user_points set ewallet = ewallet + ".$data['amount']." where user_id = ".$user_id;
					$this->db->query($sql);	

					$sql = "insert into oc_ewallet_hist(event, source_user_id, user_id, ewallet, date_added, session_id) ";
					$sql.= " values('TRANSFER-OUT TO ".$data['username']."', ".$this->user->getId().",".$this->user->getId().",".$data['amount'].",'".$this->user->now()."', '".$data['session_id']."')";
					$this->db->query($sql);
					
					$sql = " update oc_user_points set ewallet = ewallet - ".$data['amount']." where user_id = ".$this->user->getId();
					$this->db->query($sql);				
					return "Successful transfer of ".$data['amount']." ewallet credits to ".$data['username'].".";
				} else {
					return "You dont have enough ewallet credits to transfer.";
				}					
			} else {
				return "You have no ewallet credits to transfer. Or you did not put any ewallet to credit.";
			} 
			
		} else {
			return "Username is not in this system.";
		}
				
	}
	
	public function transfercv($data) {
		
		//echo "session_id = ".$data['session_id']."<br>";
		$sql = "select count(1) as total from oc_cv_hist where session_id = '".$data['session_id']."'";
		$query = $this->db->query($sql);
		$hist_total =  $query->row['total'];
		
		if($hist_total > 0) {
			return "Duplicate transaction. You might have accidentally refresh/reload the screen.";
		}		
		
		$user_id = 0;
		$sql = "select user_id from oc_user where lower(username) = lower('".$data['username']."')";
		$query = $this->db->query($sql);
		
		//echo $sql."<br>";
		if(isset($query->row['user_id'])) {
			
			$user_id = $query->row['user_id'];
			
			$sql = "select (cv - used_cv) as cv from oc_user_points where user_id = ".$this->user->getId();
			$query = $this->db->query($sql);
			//echo $sql."<br>";
			

			if(isset($query->row['cv'])) {
				$cv =  $query->row['cv'];
			} else {
				$cv = 0;
			}
			
			if(isset($data['amount'])) {
				if(!empty($data['amount'])) {
					$amount =  $data['amount'];
				} else {
					$amount =  0;
				}
				
			} else {
				$amount = 0;
			}			
			
			if($cv > 5 and $amount > 5) {
				$modulos = 0;
				$modulos = $amount % 5;
				
				//echo "$modulos<br>";
				
				if($modulos > 0) {
					return "You can only transfer cv divisible by 5.";
				} else {
					//echo "cv = $cv, amount = $amount <br>";
					if($cv >= $amount) {
						$sql = "insert into oc_cv_hist(event, source_user_id, user_id, cv, date_added, session_id) ";
						$sql.= " values('TRANSFER-IN FROM ".$this->user->getUsername()."', ".$this->user->getId().",".$user_id.",".$amount.",'".$this->user->now()."', '".$data['session_id']."')";
						$this->db->query($sql);	

						$sql = " update oc_user_points set cv = cv + ".$data['amount']." where user_id = ".$user_id;
						$this->db->query($sql);	
						//echo $sql."<br>";
						$sql = "insert into oc_cv_hist(event, source_user_id, user_id, cv, date_added, session_id) ";
						$sql.= " values('TRANSFER-OUT TO ".$data['username']."', ".$this->user->getId().",".$this->user->getId().",".$amount.",'".$this->user->now()."', '".$data['session_id']."')";
						$this->db->query($sql);
						
						$sql = " update oc_user_points set cv = cv - ".$amount." where user_id = ".$this->user->getId();
						$this->db->query($sql);				
						//echo $sql."<br>";
						return "Successful transfer of ".$data['amount']." cv credits to ".$data['username'].".";
					} else {
						return "You dont have enough cv credits to transfer.";
					}					
				}
				

			} else {
				return "You dont have enough cv credits to transfer. Cv greater than 5 is transferable. Or you did not put any cv to credit.";
			}
		} else {
			return "Username is not in this system.";
		}
				
	}	

	public function getTotalCvList($user_id, $data = array()) {
	
		$sql  = "select count(1) as total
				  from oc_cv_hist
				 where user_id = ".$user_id;			
		
		$query = $this->db->query($sql);
		//echo $sql."<br>";
		return $query->row['total'];	
	}	

	public function getCvList($user_id, $data = array()) {
	
		$sql  = "select concat(ou.firstname, ' ', ou.middlename, ' ', ou.lastname) name, 
		                oeh.event, oeh.cv, oeh.date_added,
						concat(os.firstname, ' ', os.middlename, ' ', os.lastname) sname
				  from oc_user oi
				  join oc_user ou on (ou.code = oi.code)
				  join oc_cv_hist oeh on(oeh.user_id = ou.user_id)
				  join oc_user os on (os.user_id = oeh.source_user_id)
				 where oi.user_id = ".$user_id;			

		$sql.= " order by oeh.cv_hist_id desc ";		 
				 
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
	}

	public function addEWalllet($data) {
		$user_id = 0;
		$sql = "select user_id from oc_user where lower(username) = lower('".$data['username']."')";
		$query = $this->db->query($sql);
		
		if(isset($query->row['user_id'])) {
			
			$user_id = $query->row['user_id'];
			
			$sql = "insert into oc_ewallet_hist(event, source_user_id, user_id, ewallet, date_added) ";
			$sql.= " values('TRANSFER-IN FROM ".$this->user->getUsername()."', ".$this->user->getId().",".$user_id.",".$data['cvewallet'].",'".$this->user->now()."')";
			$this->db->query($sql);	

			$sql = " update oc_user_points set ewallet = ewallet + ".$data['cvewallet']." where user_id = ".$user_id;
			$this->db->query($sql);	

			$sql = "insert into oc_ewallet_hist(event, source_user_id, user_id, ewallet, date_added) ";
			$sql.= " values('TRANSFER-OUT TO ".$data['username']."', ".$this->user->getId().",".$this->user->getId().",".$data['cvewallet'].",'".$this->user->now()."')";
			$this->db->query($sql);			
			
			return "Successful transfer of ".$data['cvewallet']." ewallet credits to ".$data['username'].".";
		} else {
			return "Username is not in this system.";
		}		
	}
	
	public function addCV($data) {
		$user_id = 0;
		$sql = "select user_id from oc_user where lower(username) = lower('".$data['username']."')";
		$query = $this->db->query($sql);
		
		if(isset($query->row['user_id'])) {
			
			$user_id = $query->row['user_id'];
			
			$sql = "insert into oc_cv_hist(event, source_user_id, user_id, cv, date_added) ";
			$sql.= " values('TRANSFER-IN FROM ".$this->user->getUsername()."', ".$this->user->getId().",".$user_id.",".$data['cvewallet'].",'".$this->user->now()."')";
			$this->db->query($sql);	

			$sql = " update oc_user_points set cv = cv + ".$data['cvewallet']." where user_id = ".$user_id;
			$this->db->query($sql);	

			$sql = "insert into oc_cv_hist(event, source_user_id, user_id, cv, date_added) ";
			$sql.= " values('TRANSFER-OUT TO ".$data['username']."', ".$this->user->getId().",".$this->user->getId().",".$data['cvewallet'].",'".$this->user->now()."')";
			$this->db->query($sql);			
			
			return "Successful transfer of ".$data['cvewallet']." CV credits to ".$data['username'].".";
		} else {
			return "Username is not in this system.";
		}		
	}

	public function top5sellers() {
		$sql = "select b.username, coalesce(sum(a.quantity), 0) cv 
					from oc_unilevel_codes a
					join oc_user b on(a.user_id = b.user_id)
					where a.date_added >= '".$this->user->nowGetFirstDayOfMonth()." 00:00:00' and a.date_added <= '".$this->user->nowGetLastDayOfMonth()." 23:59:59'
					group by a.user_id
					order by sum(a.quantity) desc
					limit 10";

		//echo $sql."<br>";
		$query = $this->db->query($sql);
		return $query->rows;					
	}
	
	public function top5sellersPrevMonth() {
		$sql = "select b.username, coalesce(sum(a.quantity), 0) cv 
					from oc_unilevel_codes a
					join oc_user b on(a.user_id = b.user_id)
					where a.date_added >= '".$this->user->nowGetFirstDayOfLastMonth()." 00:00:00' and a.date_added <= '".$this->user->nowGetLastDayOfLastMonth()." 23:59:59'
					group by a.user_id
					order by sum(a.quantity) desc
					limit 5";

		//echo $sql."<br>";
		$query = $this->db->query($sql);
		return $query->rows;					
	}	
	
	public function getGlobalPool() {
		$sql = "select (coalesce(sum(a.quantity), 0) * 0.1) gp
					from oc_unilevel_codes a
					where a.date_added >= '".$this->user->nowGetFirstDayOfMonth()." 00:00:00' and a.date_added <= '".$this->user->nowGetLastDayOfMonth()." 23:59:59'";	
		$query = $this->db->query($sql);
		return $query->row['gp'];						
	}
	
	public function getGlobalPoolPrevMonth() {
		$sql = "select (coalesce(sum(a.quantity), 0) * 0.1) gp
					from oc_unilevel_codes a
					where a.date_added >= '".$this->user->nowGetFirstDayOfLastMonth()." 00:00:00' and a.date_added <= '".$this->user->nowGetLastDayOfLastMonth()." 23:59:59'";	
		$query = $this->db->query($sql);
		//echo $sql."<br>";
		return $query->row['gp'];						
	}

	public function getEncodedUnilevelForComputation($data) {
		
		$datefrom = $this->user->nowGetFirstDayOfLastMonth();
		$dateto = $this->user->nowGetLastDayOfLastMonth();
		
		if(isset($data['year']) && isset($data['month'])) {
			if(!empty($data['year']) && !empty($data['month'])) {
				$datefrom = $data['year']."-".$data['month']."-01"." 00:00:00";
				$dateto = date('Y-m-t', strtotime($data['year']."-".$data['month']."-01"))." 23:59:59";
			} 
		} 
		
		$sql = " select b.user_id, b.username, concat(b.firstname, ' ', b.middlename, ' ', b.lastname) name, d.alias package, sum(a.quantity) tcv, 'Uncompiled' status
				   from oc_unilevel_codes a
				   join oc_user b on(a.user_id = b.user_id)
				   join oc_serials c on(b.user_id = c.user_id and c.uni_code_id = 0)
				   join gui_items_tbl d on(c.item_id = d.item_id)
				  where a.date_added >= '".$datefrom."'
				    and a.date_added <= '".$dateto."'
				    and a.quantity > 0 
					and a.status = 0
				  group by a.user_id
				  order by sum(a.quantity) desc ";
		
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
	}
	
	public function getTotalEncodedUnilevelForComputation($data) {
		
		$datefrom = $this->user->nowGetFirstDayOfLastMonth()." 00:00:00";
		$dateto = $this->user->nowGetLastDayOfLastMonth()." 23:59:59";
		
		if(isset($data['year']) && isset($data['month'])) {
			if(!empty($data['year']) && !empty($data['month'])) {
				$datefrom = $data['year']."-".$data['month']."-01"." 00:00:00";
				$dateto = date('Y-m-t', strtotime($data['year']."-".$data['month']."-01"))." 23:59:59";
			} 
		} 
		
		$sql = " select count(1) as total from (
		         select  b.username
				   from oc_unilevel_codes a
				   join oc_user b on(a.user_id = b.user_id)
				   join oc_serials c on(b.user_id = c.user_id and c.uni_code_id = 0)
				   join gui_items_tbl d on(c.item_id = d.item_id)
				  where a.date_added >= '".$datefrom."'
				    and a.date_added <= '".$dateto."'
				    and a.quantity > 0 
					and a.status = 0
					group by a.user_id ) t ";
		
		//echo $sql."<br>";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function compileCv($data) {

		$datefrom = $this->user->nowGetFirstDayOfLastMonth()." 00:00:00";
		$dateto = $this->user->nowGetLastDayOfLastMonth()." 23:59:59";
		
		if(isset($data['year']) && isset($data['month'])) {
			if(!empty($data['year']) && !empty($data['month'])) {
				$datefrom = $data['year']."-".$data['month']."-01"." 00:00:00";
				$dateto = date('Y-m-t', strtotime($data['year']."-".$data['month']."-01"))." 23:59:59";
			} 
		} 	
	
		$sql = " select count(1) as total from (
		         select  b.username
				   from oc_unilevel_codes a
				   join oc_user b on(a.user_id = b.user_id)
				   join oc_serials c on(b.user_id = c.user_id and c.uni_code_id = 0)
				   join gui_items_tbl d on(c.item_id = d.item_id)
				  where a.date_added >= '".$datefrom."'
				    and a.date_added <= '".$dateto."'
				    and a.quantity > 0 
					and a.status = 0
					group by a.user_id ) t ";
		
		//echo $sql."<br>";
		$query = $this->db->query($sql);
		$countForCompilation = (int) $query->row['total'];
		
		if($countForCompilation > 0) {
			

			$sql = "insert into oc_unilevel_compute (date_added, `years`, `month`) values ('".$this->user->now()."','".$data['year']."','".$data['month']."')";
			$this->db->query($sql);
			
			$uni_comp_id = $this->db->getLastId();
			
			$sql = " select b.user_id, b.username, concat(b.firstname, ' ', b.middlename, ' ', b.lastname) name, d.alias package, sum(a.quantity) tcv, 'Uncompiled' status
					   from oc_unilevel_codes a
					   join oc_user b on(a.user_id = b.user_id)
					   join oc_serials c on(b.user_id = c.user_id and c.uni_code_id = 0)
					   join gui_items_tbl d on(c.item_id = d.item_id)
					  where a.date_added >= '".$datefrom."'
						and a.date_added <= '".$dateto."'
						and a.quantity > 0 
						and a.status = 0
					  group by a.user_id
					  order by sum(a.quantity) desc ";
					  
			$query = $this->db->query($sql);
			
			foreach($query->rows as $forCompilation) {
				$sql = "insert into oc_unilevel_compute_dtl(uni_comp_id, user_id, pv, date_added) values (".$uni_comp_id." ,".$forCompilation['user_id']." ,".$forCompilation['tcv'].", '".$this->user->now()."')";
				$this->db->query($sql);				
			}
			
			$sql = "update oc_unilevel_codes set uni_comp_id = ".$uni_comp_id.", status = 1 where date_added >= '".$datefrom."' and date_added <= '".$dateto."'";
			$this->db->query($sql);	
		}
	
		return "Successful Compilation of CV.";
	}

	public function searchCompileCv($data) {
		$sql = "select * from oc_unilevel_compute where `years` is not null ";
		if($data['year'] != "" && $data['month'] != "") {
			$sql.= " and `years` = '".$data['year']."' and month = '".$data['month']."' ";
		}
		
		$sql.= " order by uni_comp_id desc limit 10 ";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function computeProfitSharing($data) {
		/*
		$sql = "select uni_comp_id, profit_sharing from oc_unilevel_compute where `years` is not null ";
		if($data['year'] != "" && $data['month'] != "") {
			$sql.= " and `years` = '".$data['year']."' and month = '".$data['month']."' ";
		} else {
			return "Please provide year and month.";
		}

		$global_pool = $this->getGlobalPoolPrevMonth();
		
		$query = $this->db->query($sql);
		if(isset($query->row['uni_comp_id'])) {
			
			$uni_comp_id = $query->row['uni_comp_id'];
			if($query->row['profit_sharing'] == 0) {
				$sql = "select b.user_id, b.username, coalesce(sum(a.quantity), 0) cv 
							from oc_unilevel_codes a
							join oc_user b on(a.user_id = b.user_id)
							where a.date_added >= '".$this->user->nowGetFirstDayOfLastMonth()." 00:00:00' and a.date_added <= '".$this->user->nowGetLastDayOfLastMonth()." 23:59:59'
							group by a.user_id
							order by sum(a.quantity) desc
							limit 5";

				//echo $sql."<br>";
				$query = $this->db->query($sql);
				$rank = 0;
				$message = "Global pool is Php".number_format($global_pool, 2).", Successful Commissioning of Profit Sharing to the ff: <br>";
				$commission_type_id = $this->getCommissionTypeId('Profit Sharing');
				foreach($query->rows as $top5) {
					$rank += 1;
					if($rank == 1) {
						$percent = .4;
						$share = "40%";
					} else if ($rank == 2) {
						$percent = .25;
						$share = "25%";
					} else if ($rank == 3) {
						$percent = .2;
						$share = "20%";
					} else if ($rank == 4) {
						$percent = .1;
						$share = "10%";
					} else if ($rank == 5) {
						$percent = .05;
						$share = "5%";
					}
																							
					$this->insertCommission3($uni_comp_id, $top5['user_id'], $top5['user_id'], $commission_type_id, $global_pool*$percent, 38, $this->user->nowDate(), $this->user->getHalf());
					
					$message = $message."Rank:".$rank.", username:".$top5['username'].", ".$share." share, commission: Php".number_format($global_pool*$percent, 2)."<br>";
				}
				
				$sql = "update oc_unilevel_compute set profit_sharing = 1 where uni_comp_id = ".$uni_comp_id;
				$this->db->query($sql);
				
				return $message;
			} else {
				return "Profit sharing for the period ".$data['year']."-".$data['month']." was previously computed already.";
			}
		} else {
			return "No compiled CVs yet for the period ".$data['year']."-".$data['month'];
		}
		*/
		return "Ongoing development.";
	}

	public function computePersonalRebates($data) {
		set_time_limit(0);
		$pr_commission_type_id = $this->getCommissionTypeId('Personal Rebates');
		$pu_commission_type_id = $this->getCommissionTypeId('Pass Up Reward');
		
		$sql = "select uni_comp_id, personal_rebate from oc_unilevel_compute where `years` is not null ";
		if($data['year'] != "" && $data['month'] != "") {
			$sql.= " and `years` = '".$data['year']."' and month = '".$data['month']."' ";
		} else {
			return "Please provide year and month.";
		}
		
		$query = $this->db->query($sql);
		
		if(isset($query->row['uni_comp_id'])) {
			$uni_comp_id = $query->row['uni_comp_id'];
			if($query->row['personal_rebate'] == 0) {				
				$sql = "select a.user_id, d.username, a.pv, c.alias 
						from oc_unilevel_compute_dtl a
						join oc_serials b on(a.user_id = b.user_id and b.uni_code_id = 0)
						join gui_items_tbl c on(b.item_id = c.item_id)
						join oc_user d on(a.user_id = d.user_id)
						where uni_comp_id = ".$uni_comp_id."
						order by uni_comp_dtl_id ";
				$query = $this->db->query($sql);
				$message = "";
				foreach($query->rows as $pr) {					
										
					if($pr['alias'] == 'SUB-DEALER' || $pr['alias'] == 'DEALER') {
						$message .= "username>>".$pr['username'].", pv>>".$pr['pv'].", commission>> Php ".number_format($pr['pv']*.1,2).", 10% personal rebate, alias>>".$pr['alias']."<br>";
						//echo "user_id>>".$pr['user_id'].", pv>>".$pr['pv'].", commission>> Php ".number_format($pr['pv']*.1,2).", 10% personal rebate, alias>>".$pr['alias']."<br>";
						$this->insertCommission3($uni_comp_id, $pr['user_id'], $pr['user_id'], $pr_commission_type_id, ($pr['pv']*0.10), 38, $this->user->nowDate(), $this->user->getHalf());
						
						$referror = $this->getReferror($pr['user_id']);
						$elite = 0;
						
						while ($elite == 0) {

							$sql = "select a.user_id, b.alias, c.pv
									from oc_serials a 
									join gui_items_tbl b on(a.item_id = b.item_id)
									join oc_unilevel_compute_dtl c on(a.user_id = c.user_id and c.uni_comp_id = ".$uni_comp_id.")
									where a.user_id = ".$referror."
									and a.uni_code_id = 0 ";
									   
							$query = $this->db->query($sql);  
							
							
							$pv = 0;
							$alias = "";
							$pr_user_id = 0;
							
							if (isset($query->row['pv'])){
								$pv = $query->row['pv'];
								$alias = $query->row['alias'];
								
								//echo $pv.">>>".$alias."<br>";
								if($alias == 'SUB-DEALER' || $alias == 'DEALER') {
									$referror = $this->getReferror($referror);
								} else if($referror == 2 or $referror == 5 or $referror == 6 or $referror == 7 or $referror == 8 or $referror == 9 or $referror == 10
										or $referror == 11 or $referror == 18 or $referror == 81 or $referror == 809
										or $referror == 826 or $referror == 830 or $referror == 840 or $referror == 1685 ) {
									
									$sql = "select username from oc_user where user_id = ".$referror;
									$query = $this->db->query($sql);
									$referror_username = $query->row['username'];
									$message .= "Pass Up username >> ".$referror_username.", user_id>>".$pr['username'].", pv>>".$pr['pv'].", commission>> Php ".number_format($pr['pv']*.1,2).", 10% Pass Up personal rebate, alias>>".$pr['alias']."<br>";
									$this->insertCommission3($uni_comp_id, $referror, $pr['user_id'], $pu_commission_type_id, ($pr['pv']*0.10), 38, $this->user->nowDate(), $this->user->getHalf());										
									$elite = 1;									
								} else {
									$elite = 1;
									if($pv >= 200) {
										$pr_user_id = $query->row['user_id'];
										$referror_username = "";
										$sql = "select username from oc_user where user_id = ".$pr_user_id;
										$query = $this->db->query($sql);
										$referror_username = $query->row['username'];
										$message .= "Pass Up username >> ".$referror_username.", user_id>>".$pr['username'].", pv>>".$pr['pv'].", commission>> Php ".number_format($pr['pv']*.1,2).", 10% Pass Up personal rebate, alias>>".$pr['alias']."<br>";
										$this->insertCommission3($uni_comp_id, $referror, $pr['user_id'], $pu_commission_type_id, ($pr['pv']*0.10), 38, $this->user->nowDate(), $this->user->getHalf());	
										$elite = 1;								
									} else {
										$referror = $this->getReferror($referror);
									}
								}
							} else {
								if($referror == 0) {
									//echo "else referror = 2 <br>";
									$elite = 1;
								} else if($referror == 2 or $referror == 5 or $referror == 6 or $referror == 7 or $referror == 8 or $referror == 9 or $referror == 10
										or $referror == 11 or $referror == 18 or $referror == 81 or $referror == 809
										or $referror == 826 or $referror == 830 or $referror == 840 or $referror == 1685 ) {
									$sql = "select username from oc_user where user_id = ".$referror;
									$query = $this->db->query($sql);
									$referror_username = $query->row['username'];
									$message .= "Pass Up username >> ".$referror_username.", user_id>>".$pr['username'].", pv>>".$pr['pv'].", commission>> Php ".number_format($pr['pv']*.1,2).", 10% Pass Up personal rebate, alias>>".$pr['alias']."<br>";
									$this->insertCommission3($uni_comp_id, $referror, $pr['user_id'], $pu_commission_type_id, ($pr['pv']*0.10), 38, $this->user->nowDate(), $this->user->getHalf());										
									$elite = 1;										
								} else {
									$referror = $this->getReferror($referror);
									//echo "else referror != 2 <br>";
								}
							}
							
						}						
					} else {
						$message .= "user_id>>".$pr['user_id'].", pv>>".$pr['pv'].", commission>> Php ".number_format($pr['pv']*.2,2).", 20% personal rebate, alias>>".$pr['alias']."<br>";
						$this->insertCommission3($uni_comp_id, $pr['user_id'], $pr['user_id'], $pr_commission_type_id, ($pr['pv']*0.20), 38, $this->user->nowDate(), $this->user->getHalf());					
					}
				}
				
				$sql = "update oc_unilevel_compute set personal_rebate = 1 where uni_comp_id = ".$uni_comp_id;
				$this->db->query($sql);
				
				return "Successful Processing of Personal Rebates. See Logs: <br>".$message;
				
			} else {
				return "Personal Rebates for the period ".$data['year']."-".$data['month']." was previously computed already.";
			}	
		} else {
			return "No compiled CVs yet for the period ".$data['year']."-".$data['month'];
		}
	}	
	
	public function qualifyProfitSharing($data) {
		//insert silver
		$sql = " select user_id from oc_user_points where incentive_points >= 100 ";
		$query = $this->db->query($sql);
		foreach($query->rows as $pr) {
			//echo $pr['user_id']."<br>";
			$sql = "select count(1) as total from oc_profit_sharing_rank where user_id = ".$pr['user_id']." and profit_sharing_rank = 7 ";
			$query = $this->db->query($sql);
			$pscount = $query->row['total'];
			//if existing ignore
			//if not existing insert			
			if($pscount == 0) {
				$sql = "insert into oc_profit_sharing_rank(user_id, profit_sharing_rank, date_added, date_activation) values (".$pr['user_id'].", 7, '".$this->user->now()."', '".$this->user->nowGetFirstDayOfNextMonth()." 00:00:00')";
				$this->db->query($sql);
			} 
			
			//update oc_user profit_sharing_rank to silver
			$sql = "update oc_user set profit_sharing_rank = 7 where user_id = ".$pr['user_id'];
			$this->db->query($sql);
		}
		
		//insert gold
		$sql = " select user_id from oc_user_points where incentive_points >= 300 ";
		$query = $this->db->query($sql);
		foreach($query->rows as $pr) {
			//echo $pr['user_id']."<br>";
			//check first if already in the table with gold entry
			$sql = "select count(1) as total from oc_profit_sharing_rank where user_id = ".$pr['user_id']." and profit_sharing_rank = 8 ";
			$query = $this->db->query($sql);
			$pscount = $query->row['total'];
			//if existing ignore
			//if not existing insert			
			if($pscount == 0) {
				$sql = "insert into oc_profit_sharing_rank(user_id, profit_sharing_rank, date_added, date_activation) values (".$pr['user_id'].", 8, '".$this->user->now()."', '".$this->user->nowGetFirstDayOfNextMonth()." 00:00:00')";
				$this->db->query($sql);
			} 

			//update oc_user profit_sharing_rank to gold
			$sql = "update oc_user set profit_sharing_rank = 8 where user_id = ".$pr['user_id'];
			$this->db->query($sql);			
		}		
		//insert platinum
		$sql = " select user_id from oc_user_points where incentive_points >= 1000 ";
		$query = $this->db->query($sql);
		foreach($query->rows as $pr) {
			//echo $pr['user_id']."<br>";
			$sql = "select count(1) as total from oc_profit_sharing_rank where user_id = ".$pr['user_id']." and profit_sharing_rank = 9 ";
			$query = $this->db->query($sql);
			$pscount = $query->row['total'];
			//if existing ignore
			//if not existing insert			
			if($pscount == 0) {
				$sql = "insert into oc_profit_sharing_rank(user_id, profit_sharing_rank, date_added, date_activation) values (".$pr['user_id'].", 9, '".$this->user->now()."', '".$this->user->nowGetFirstDayOfNextMonth()." 00:00:00')";
				$this->db->query($sql);
			} 
			//update oc_user profit_sharing_rank to platinum
			$sql = "update oc_user set profit_sharing_rank = 9 where user_id = ".$pr['user_id'];
			$this->db->query($sql);			
		}

		return "Successful Qualifying to Profit Sharing. You may see the result in Maintenance->Admin Reports->Ranking Reports. ";
	}

	public function computeUnilevel($data) {
		$sql = "select uni_comp_id, unilevel from oc_unilevel_compute where `years` is not null and unilevel = 0 ";
		if($data['year'] != "" && $data['month'] != "") {
			$sql.= " and `years` = '".$data['year']."' and month = '".$data['month']."' ";
		} else {
			return "Please provide year and month.";
		}
		
		$query = $this->db->query($sql);
		if(isset($query->row['uni_comp_id'])) {
			$uni_comp_id = $query->row['uni_comp_id'];
			if($query->row['unilevel'] == 0) {
				$sql = "select a.uni_comp_dtl_id, d.refer_by_id, a.user_id, d.username, a.pv, c.alias 
						from oc_unilevel_compute_dtl a
						join oc_serials b on(a.user_id = b.user_id and b.uni_code_id = 0)
						join gui_items_tbl c on(b.item_id = c.item_id)
						join oc_user d on(a.user_id = d.user_id)
						where a.uni_comp_id = ".$uni_comp_id." and a.unilevel = 0
						order by a.uni_comp_dtl_id
						limit 100 ";
				$query = $this->db->query($sql);
				
				foreach($query->rows as $pr) {
					$referror = $pr['user_id'];
					for($i = 1; $i <= 10; $i++) {
						$referror = $this->commissionUnilevel($uni_comp_id, $referror, $pr['user_id'], $pr['pv'], $uni_comp_id);
					}
					
					$sql = "update oc_unilevel_compute_dtl set unilevel = 1 where uni_comp_dtl_id = ".$pr['uni_comp_dtl_id'];
					$this->db->query($sql);
				}
				
				$sql = "select count(1) total
						from oc_unilevel_compute_dtl a
						join oc_serials b on(a.user_id = b.user_id and b.uni_code_id = 0)
						join gui_items_tbl c on(b.item_id = c.item_id)
						join oc_user d on(a.user_id = d.user_id)
						where a.uni_comp_id = ".$uni_comp_id." and a.unilevel = 0
						order by uni_comp_dtl_id";
				$query = $this->db->query($sql);				
				
				if($query->row['total'] > 0) {
					return "Successful Partial Unilevel Computation. Please see commission report.";								
				} else {
					$sql = "update oc_unilevel_compute set unilevel = 1 where uni_comp_id = ".$uni_comp_id;
					$this->db->query($sql);				
					return "Successful Computation. Please see commission report.";
				}
				
			} else {
				return "Unilevel for the period ".$data['year']."-".$data['month']." was previously computed already.";
			}	
		} else {
			return "No compiled CVs yet for the period ".$data['year']."-".$data['month'].". Or it was already been computed.";
		}
	}
	
	public function commissionUnilevel($uni_comp_id, $sponsor1, $user_id, $pv_user) {
		
		if($sponsor1 <> 0) {
			$sql = "select refer_by_id from oc_user where user_id = ".$sponsor1;						
			//echo $sql."<br>";
			$query = $this->db->query($sql);
			$sponsor2 = $query->row['refer_by_id']; 
			if($sponsor2 <> 0) {
				$step1 = false;
				$step1_user = $sponsor2;
				while($step1 == false) {
					$sql = "select pv from oc_unilevel_compute_dtl where user_id = ".$step1_user." and uni_comp_id = ".$uni_comp_id;
					//echo $sql."<br>";
					$query = $this->db->query($sql);
					
					if(isset($query->row['pv']) or $step1_user == 2 or $step1_user == 5 or $step1_user == 6
							or $step1_user == 7 or $step1_user == 8 or $step1_user == 9 or $step1_user == 10
							or $step1_user == 11 or $step1_user == 18 or $step1_user == 81 or $step1_user == 809
							or $step1_user == 826 or $step1_user == 830 or $step1_user == 840 or $step1_user == 1685) {
						
						if(isset($query->row['pv'])) {
							$pv = $query->row['pv'];
						} else {
							$pv = 0;
						}
						
						if($pv >= 200 or $step1_user == 2 or $step1_user == 5 or $step1_user == 6
							or $step1_user == 7 or $step1_user == 8 or $step1_user == 9 or $step1_user == 10
							or $step1_user == 11 or $step1_user == 18 or $step1_user == 81 or $step1_user == 809
							or $step1_user == 826 or $step1_user == 830 or $step1_user == 840 or $step1_user == 1685
						){
							$step1 = true;
							$sponsor2 = $step1_user;
							//commission 5% to $sponsor2
							
							$commission_unilevel = 0.08 * $pv_user;
														
							$commission_type_id = $this->getCommissionTypeId('Unilevel');							
							
							////echo "commission_type_id::".$commission_type_id."<br>";
							$this->insertCommission3($uni_comp_id, $sponsor2, $user_id, $commission_type_id, $commission_unilevel, 38, $this->user->nowDate(), $this->user->getHalf());
							
						} else {
							$sql = "select refer_by_id from oc_user where user_id = ".$step1_user;
							//echo $sql."<br>";
							$query = $this->db->query($sql);
							$step1_user = $query->row['refer_by_id'];									
						}
					} else {
						$sql = "select refer_by_id from oc_user where user_id = ".$step1_user;
						//echo $sql."<br>";
						$query = $this->db->query($sql);
						$step1_user = $query->row['refer_by_id'];									
					}
					
					if($step1_user == 0) {
						$sponsor2 = $step1_user;
						break;
					}
				}						
			}
		} else {
			$sponsor2 = 0;
		}
		return $sponsor2;
	}	
	
	public function getCompiledCV($data) {		
		$sql = "select * from oc_unilevel_compute where `years` is not null order by uni_comp_id desc limit 10 ";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getAccidentInsuranceList($data) {
		$sql = "select a.username, a.firstname, a.middlename, a.lastname
					  ,a.tin, a.email, a.contact, a.birthdate, a.gender
					  ,a.civil_status, a.occupation, a.nature_of_work
					  ,a.annual_income, a.question1, a.question2, a.question3
					  ,a.question4, a.beneficiaries, b.date_modified latest_upgrade
					  ,case when b.paid_flag = 0 then 'Free Slot' else 'Paid' end paid_flag
					  ,d.alias ,c.address
				from oc_user a
				join oc_serials b on(a.user_id = b.user_id and b.uni_code_id = 0)
				left join gui_customer_tbl c on(a.user_id = c.user_id)
				join gui_items_tbl d on (b.item_id = d.item_id)
				where a.accident_insurance = 1 ";

		if(isset($data['paid_flag'])) {
			if(!empty($data['paid_flag'])) {
				$sql.= " AND b.paid_flag = '".$data['paid_flag']."'";
			}
		}
				  
		if(isset($data['type'])) {
			if(!empty($data['type'])) {
				$sql.= " AND d.alias = '".$data['type']."'";
			}
		}

		if(isset($data['date_from'])) {
			if(!empty($data['date_from'])) {
				$sql.= " and b.date_modified >= '".$data['date_from']." 00:00:00'";
			}
		}		

		if(isset($data['date_to'])) {
			if(!empty($data['date_to'])) {
				$sql.= " and b.date_modified <= '".$data['date_to']." 00:00:00' ";
			}
		}	
				
		$sql .= " order by b.date_modified desc";

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
	}


	public function getAccidentInsuranceListTotal($data) {
		$sql = "select count(1) as total
				from oc_user a
				join oc_serials b on(a.user_id = b.user_id and b.uni_code_id = 0)
				left join gui_customer_tbl c on(a.user_id = c.user_id)
				join gui_items_tbl d on (b.item_id = d.item_id)
				where a.accident_insurance = 1 ";
				
		if(isset($data['paid_flag'])) {
			if(!empty($data['paid_flag'])) {
				$sql.= " AND b.paid_flag = '".$data['paid_flag']."'";
			}
		}
				  
		if(isset($data['type'])) {
			if(!empty($data['type'])) {
				$sql.= " AND d.alias = '".$data['type']."'";
			}
		}

		if(isset($data['date_from'])) {
			if(!empty($data['date_from'])) {
				$sql.= " and b.date_modified >= '".$data['date_from']." 00:00:00'";
			}
		}		

		if(isset($data['date_to'])) {
			if(!empty($data['date_to'])) {
				$sql.= " and b.date_modified <= '".$data['date_to']." 00:00:00'";
			}
		}				
				
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getBankDetails() {
		$sql = " select ou.bank_name, ou.account_name, ou.account_no
				  from oc_user oi
				  join oc_user ou on (ou.code = oi.code) 
				where oi.user_id = ".$this->user->getId()."
				  and ou.main_account = 1
				 limit 1 ";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getFirstLevelUnilevelDownline($days, $qlimit) {			  
				  
		$sql = "select a.username, UPPER(concat(a.firstname, ' ', a.middlename, ' ', a.lastname)) fullname, date_format(b.months, '%Y-%M') months, sum(b.quantity) total_cv,
					   case when sum(b.quantity) >= ".$qlimit." then ".$qlimit." else sum(b.quantity) end qualified_gcv
				  from oc_user a
				  join oc_group_unilevel b on(a.user_id = b.user_id) 
				 where a.refer_by_id = ".$this->user->getId()."
				   and b.months = '".$this->user->nowGetFirstDayXDaysAgoMonth($days)."'
				  group by a.username, concat(a.firstname, ' ', a.middlename, ' ', a.lastname), date_format(b.months, '%Y-%M')";
		
		//echo $sql."<br>";
		
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getSummaryCommissionV1() {
		$sql = "select t.com_type, 
				   case when com_type = '5th Pair' then count(t.amount) 
				   else sum(t.amount) end amount
			  from (
			select case when c.commission_type_id = 4 then 'Binary'
						when c.commission_type_id = 5 then '5th Pair'
					when c.commission_type_id in (3,13,14,15,16,17,18,19,20,21,22,23) then 'Referral'
					when c.commission_type_id = 25 then 'Personal Rebates'
					when c.commission_type_id = 26 then 'Unilevel'
					when c.commission_type_id = 27 then 'Pass-Up'
					when c.commission_type_id in (28,29) then 'Ranking'
					else 'Others' end com_type,
				   c.amount + c.tax amount
			  from oc_user a
			  join oc_user b on(a.code = b.code)
			  join oc_commission c on(b.user_id = c.user_id and c.status = 38)
			 where a.user_id = ".$this->user->getId()."
			) t
			group by t.com_type ";
			
		$query = $this->db->query($sql);
		return $query->rows;	
	}
	
	public function getSummaryCommissionV2() {
		$sql = "select t.com_type, 
					   case when com_type = '5th Pair' then count(t.amount) 
					   else sum(t.amount) end amount
				  from (
				select case when c.commission_type_id = 4 then 'Binary'
						when c.commission_type_id = 5 then '5th Pair'
						when c.commission_type_id in (3,13,14,15,16,17) then 'Referral'
						when c.commission_type_id between 29 and 42 then 'Referral'
						when c.commission_type_id = 25 then 'Personal Rebates'
						when c.commission_type_id = 26 then 'Unilevel'
						when c.commission_type_id = 27 then 'Pass-Up'
						when c.commission_type_id in (7, 55, 56, 57) then 'Profit Sharing'
						when c.commission_type_id in (28,29) then 'Ranking'
						else 'Others' end com_type,
					   c.amount 
				  from oc_user a
				  join oc_user b on(a.code = b.code)
				  join oc_commission2 c on(b.user_id = c.user_id)		  
				 where a.user_id = ".$this->user->getId()."
				) t
				group by t.com_type ";
			
		$query = $this->db->query($sql);
		return $query->rows;	
	}

	public function getMemberName($username) {
		$member_name = "Username";
		$sql = "select count(1) as total from oc_user where username = '".$username."'";
		$query = $this->db->query($sql);
		$total = $query->row['total'];
		
		if($total > 0) {
			$sql = "select concat(firstname, ' ', middlename, ' ', lastname) as name from oc_user where username = '".$username."'";
			$query = $this->db->query($sql);
			$member_name = $query->row['name'];			
		}
		
		return $member_name;
	}

	public function getMemberIntro($username) {
		$member_introduction = "Username";
		$sql = "select count(1) as total from oc_user where username = '".$username."'";
		$query = $this->db->query($sql);
		$total = $query->row['total'];
		
		if($total > 0) {
			$sql = "select introduction from oc_user where username = '".$username."'";
			$query = $this->db->query($sql);
			$member_introduction = $query->row['introduction'];			
		}
		
		return $member_introduction;
	}
	/*
	public function getProducts() {
		$sql = " select item_id, item_code, description, cost, image, online_introduction from gui_items_tbl where online = 1";
		$query = $this->db->query($sql);
		return $query->rows;
	}*/
	
	public function getGenealogyAccounts($user_string) {
		$sql  = "select a.username, a.user_id, a.date_added, c.description package , c.alias account_type,
						oup.left_points - oup.left_used_points - oup.left_flushed_points left_remain_points,
						oup.right_points - oup.right_used_points - oup.right_flushed_points right_remain_points
		           from oc_user a
				   join oc_user_points oup on(oup.user_id = a.user_id)
				   left join oc_serials b on (a.user_id = b.user_id and b.uni_code_id = 0)
				   left join gui_items_tbl c on (b.item_id = c.item_id)
		          where a.user_id in (".$user_string.")";	

	  
		$sql.= " ORDER BY a.user_id ";	

		$query = $this->db->query($sql);
		return $query->rows;		
	}
	
	public function getTotalCommissions	() {
		$total_commission = 0;
		
		$sql = "select sum(amount) total
				  from oc_user a
				  join oc_user b on(a.code = b.code)
				  join oc_commission c on(b.user_id = c.user_id)		  
				 where a.user_id = ".$this->user->getId()."
				   and commission_type_id not in(5,54)
				";
			
		$query = $this->db->query($sql);
		$com_v1 = $query->row['total'];

		//echo "com_v1>>".$com_v1."<br>";
		
		$sql = "select sum(amount) total
				  from oc_user a
				  join oc_user b on(a.code = b.code)
				  join oc_commission2 c on(b.user_id = c.user_id)		  
				 where a.user_id = ".$this->user->getId()."
				   and commission_type_id not in(5,54)
				";
			
		$query = $this->db->query($sql);
		$com_v2 = $query->row['total'];	
		
		//echo "com_v2>>".$com_v2."<br>";
		
		$sql = "select sum(amount) total
				  from oc_user a
				  join oc_user b on(a.code = b.code)
				  join oc_commission2 c on(b.user_id = c.user_id)		  
				 where a.user_id = ".$this->user->getId()."
				   and c.commission_type_id = 54
				";
			
		$query = $this->db->query($sql);
		$com_deduct = $query->row['total'];	
		
		//echo "com_deduct>>".$com_deduct."<br>";
		
		$total_commission = $com_v1 + $com_v2 - $com_deduct;
		
		//echo "total_commission>>".$total_commission."<br>";
		
		return $total_commission;
	}

	public function getTotalEncashment() {
		$total_encashment = 0;
		$sql = "select b.code, sum(c.amount + c.tax + c.proc_fee) total
				  from oc_user a
				  join oc_user b on(a.code = b.code)
				  join oc_encashment c on(b.user_id = c.user_id)
				 where a.user_id = ".$this->user->getId()."
				 group by b.code ";
		
		$query = $this->db->query($sql);
		$total_encashment = $query->row['total'];	
		
		//echo "total_encashment>>".$total_encashment."<br>";
		
		return $total_encashment;		
	}

	public function adminUserPointsView($user_id) {
		
		$sql = "select b.username, a.source_alias sp_package, d.description dl_package, a.movement, a.left_points, a.left_used_points, a.left_flushed_points, a.right_points, a.right_used_points, a.right_flushed_points, a.date_added 
				  from oc_user_points_hist a
				  join oc_user b on(a.user_id = b.user_id)
				  join oc_serials c on(a.source_user_id = c.user_id and c.uni_code_id = 0)
				  join gui_items_tbl d on(c.item_id = d.item_id)				  
				 where source_user_id = ".$user_id."
				 order by user_points_hist_id desc
				 limit 500 ";		

		$query = $this->db->query($sql);
		return $query->rows;		
	}

	public function adminUserCommissionView($user_id) {		
		$sql = "select b.username, concat(b.firstname, ' ', b.middlename, ' ', b.lastname) name, c.description,  a.amount, a.date_commissioned, a.shift 
					from oc_commission2 a 
					join oc_user b on(a.user_id = b.user_id)
					join commission_type c on(a.commission_type_id = c.commission_type_id)
					where com_user_id = ".$user_id."
				 order by commission_id desc
				 limit 500 ";		

		$query = $this->db->query($sql);
		return $query->rows;		
	}	
 	
	public function myUserPointsView() {
		
		$sql = "select b.username, a.source_alias sp_package, d.description dl_package, a.movement, a.left_points, a.left_used_points, a.left_flushed_points, a.right_points, a.right_used_points, a.right_flushed_points, a.date_added 
				  from oc_user_points_hist a
				  join oc_user b on(a.source_user_id = b.user_id)
				  join oc_serials c on(a.source_user_id = c.user_id and c.uni_code_id = 0)
				  join gui_items_tbl d on(c.item_id = d.item_id)
				 where a.user_id = ".$this->user->getId()."
				 order by user_points_hist_id desc
				 limit 500 ";		

		$query = $this->db->query($sql);
		return $query->rows;		
	}

	public function adminreportquery($data = array(), $query_type) {

		$firstdate_string = $data['firstdate_string'];
		$lastdate_string = $data['lastdate_string'];
		$sql = "";
		
		if($query_type == "data") {
			$cols = "b.username sponsor_username, c.username dline_username, d.description com_type, a.amount, a.date_commissioned, a.shift, a.ver";
			$group = "";
			$order = "";
		} else {
			$cols = " count(1) as total ";
			$group = " group by UPPER( concat(b.firstname, ' ', b.middlename, ' ', b.lastname)) ";
			$order = " order by sum(a.amount) desc ";			
		}
		
		if($data['report'] == "monthlygeneratedcommission") {
						
			$sql .= "select b.username sponsor_username, concat(b.firstname, ' ', b.middlename, ' ', b.lastname) sponsor_fullname, c.username dline_username, d.description com_type, a.amount, a.date_commissioned, a.shift, a.ver
					  from oc_commission2 a
					  join oc_user b on(a.user_id = b.user_id)
					  join oc_user c on(a.com_user_id = c.user_id)
					  join commission_type d on(a.commission_type_id = d.commission_type_id)
					 where a.date_commissioned >= '".$firstdate_string."'
					   and a.date_commissioned <= '".$lastdate_string."' ";	
					   
		} else if($data['report'] == "detailedgeneratedcommission") {

			
			$sql .= " select UPPER(concat(b.firstname, ' ', b.middlename, ' ', b.lastname)) fullname, sum(a.amount) amount
					    from oc_commission2 a
					    join oc_user b on(a.user_id = b.user_id)
					    join commission_type c on (c.commission_type_id = a.commission_type_id)
					    left join oc_user d on(a.com_user_id = d.user_id)
					   where a.date_commissioned >= '".$firstdate_string."'
					     and a.date_commissioned <= '".$lastdate_string."'
					     and a.status = 38 
						 group by UPPER( concat(b.firstname, ' ', b.middlename, ' ', b.lastname))
						 order by sum(a.amount) desc
					";
					
		} else if($data['report'] == "monthlysummarygeneratedcommission") {	

			$sql = "select t.com_type, 
					case when com_type = '5th Pair' then count(t.amount) 
					else sum(t.amount) end amount
					from (
					select case when c.commission_type_id = 4 then 'Binary'
					when c.commission_type_id = 5 then '5th Pair'
					when c.commission_type_id in (3,13,14,15,16,17) then 'Referral'
					when c.commission_type_id between 29 and 42 then 'Referral'
					when c.commission_type_id = 25 then 'Personal Rebates'
					when c.commission_type_id = 26 then 'Unilevel'
					when c.commission_type_id = 27 then 'Pass-Up'
					when c.commission_type_id = 7 then 'Profit Sharing'
					when c.commission_type_id = 54 then 'Commission Deduction'
					when c.commission_type_id in (28,29) then 'Ranking'
					else 'Others' end com_type,
					c.amount 
					from oc_commission2 c 	  
					where c.date_commissioned >= '".$firstdate_string."'
					and c.date_commissioned <= '".$lastdate_string."'
					) t
					group by t.com_type ";
					
		} else if($data['report'] == "monthlyencashment") {	

			$sql = "select b.username, UPPER( concat(b.firstname, ' ', b.middlename, ' ', b.lastname)) fullname, 
						   a.enc_type, a.date_requested, a.amount
					  from oc_encashment a
					  join oc_user b on(a.user_id = b.user_id)
					 where a.date_requested >= '".$firstdate_string."'
					   and a.date_requested <= '".$lastdate_string."' ";
					
		} else if($data['report'] == "summaryencashment") {	

			$sql = "select a.enc_type, sum(a.amount) amount
					  from oc_encashment a
					  join oc_user b on(a.user_id = b.user_id)
					 where a.date_requested >= '".$firstdate_string."'
					   and a.date_requested <= '".$lastdate_string."' 
					   group by a.enc_type ";
					
		} else if($data['report'] == "incentivereports") {	

			$sql = "select b.username, concat(b.firstname, ' ', b.middlename, ' ', b.lastname) name, 
						   a.incentive_points, a.used_incentive_points, a.incentive_points - a.used_incentive_points remaining 
					from oc_user_points a
					join oc_user b on(a.user_id = b.user_id)
					order by a.incentive_points desc ";

		} else if($data['report'] == "taxreport") {	

			$sql = "select b.username, upper(concat(b.firstname, ' ', b.lastname)) name, 
						   b.tin, sum(a.amount+a.tax) gross, sum(a.tax) tax, sum(a.proc_fee) proc_fee 
					from oc_encashment a
					join oc_user b on(a.user_id = b.user_id)
					where a.date_requested >= '".$firstdate_string."'
					and a.date_requested <= '".$lastdate_string."'
					and a.enc_type not in('Convert to E-Wallet')
					group by upper(concat(b.firstname, ' ', b.lastname))
					order by b.tin desc, sum(a.tax) desc			
				";

		} else if($data['report'] == "rankingreport") {	

			$sql = "select b.description, c.username, concat(c.firstname, ' ', c.lastname) name, a.date_added, date_activation
					  from oc_profit_sharing_rank a
					  join oc_ranks b on(a.profit_sharing_rank = b.rank_id)
					  join oc_user c on(a.user_id = c.user_id)			
				";					
				
		} else if($data['report'] == "detailedpackagereport") {	

			$sql = " select b.alias package, c.username, concat(c.firstname, ' ', c.lastname) name, 
					  case when a.paid_flag = 0 then 'Free Slot' else 'Paid' end paid_flag, 
					  case when a.com_deduct = 0 then 'Normal' else 'Commission Deduct' end com_deduct, 
					  b.cost amount
					  from oc_serials a
					  join gui_items_tbl b on(a.item_id = b.item_id)
					  join oc_user c on(a.user_id = c.user_id)
					 where a.date_modified >= '".$firstdate_string."'
					   and a.date_modified <= '".$lastdate_string."' 
					";
					
		} else if($data['report'] == "summarypackagereport") {	

			$sql = " select b.alias package, 
							  case when a.paid_flag = 0 then 'Free Slot' else 'Paid' end paid_flag, 
							  case when a.com_deduct = 0 then 'Normal' else 'Commission Deduct' end com_deduct, 
							  count(1) quantity, sum(b.cost) amount
							  from oc_serials a
							  join gui_items_tbl b on(a.item_id = b.item_id)
						where a.date_modified >= '".$firstdate_string."'
						and a.date_modified <= '".$lastdate_string."' 
					    group by b.alias, a.paid_flag, a.com_deduct
					";
					
		}	
		
		if($query_type == "data") {		
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
			
			$sqlt = "select count(1) total from (".$sql.") t ";
			
			$query = $this->db->query($sqlt);
			
			//echo $sqlt."<br>";
			return $query->row['total'];
		}		
	}
	
	public function getUnilevelComputations($user_id, $data = array(), $query_type) {

		$sql = " select a.uni_comp_id, CONCAT('UNILEVEL_COMP',a.uni_comp_id) ref, b.years, a.ver, format(sum(a.amount), 2) amount,
						case when b.month = '01' then 'January'
						 when b.month = '02' then 'February'
						 when b.month = '03' then 'March'
						 when b.month = '04' then 'April'
						 when b.month = '05' then 'May'
						 when b.month = '06' then 'June'
						 when b.month = '07' then 'July'
						 when b.month = '08' then 'August'
						 when b.month = '09' then 'September'
						 when b.month = '10' then 'October'
						 when b.month = '11' then 'November'
						 when b.month = '12' then 'December'
						else '' end month,
						case when a.ver = 2 then format((sum(a.amount) * 100 ) / 10, 0) 
						     when a.ver = 3 then format((sum(a.amount) * 100) / 8, 0)
							 else 0 end cv						
					from oc_commission2 a
					join oc_unilevel_compute b on(a.uni_comp_id = b.uni_comp_id)
					where a.user_id = ".$this->user->getId()." and a.uni_comp_id <> 0 and a.commission_type_id = 26
					group by a.uni_comp_id, a.ver
					order by a.uni_comp_id
				";	
	
		if($query_type == "data") {		
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
			
			$sqlt = "select count(1) total from (".$sql.") t ";
			
			$query = $this->db->query($sqlt);
			
			//echo $sqlt."<br>";
			return $query->row['total'];
		}	
	
	}

	public function getUnilevelComputationDetails($user_id, $data = array(), $query_type) {

		$sql = " select b.username, concat(b.firstname, ' ', b.lastname) name, CONCAT('UNILEVEL_COMP',a.uni_comp_id) ref, 
						a.amount, a.shift, a.ver, c.level,
						case when a.ver = 2 then format((a.amount * 100 ) / 10, 0) 
						     when a.ver = 3 then format((a.amount * 100) / 8, 0)
							 else 0 end cv, a.date_commissioned
					from oc_commission2 a
					join oc_user b on(a.com_user_id = b.user_id)
					join oc_unilevel c on(c.sponsor_user_id = a.user_id and c.user_id = a.com_user_id)
					where a.user_id = ".$this->user->getId()." and a.uni_comp_id = ".$data['uni_comp_id']." and a.commission_type_id = 26
					order by a.uni_comp_id
				";	
	
		if($query_type == "data") {		
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

	public function getBinaryCommissionSummary($user_id, $data = array(), $query_type) {

		$sql = " select t.month1, t.month2, t.months, t.years, t.commission_type_id, t.com_type,  
					sum(t.amount) amount
					from (
						select
						DATE_FORMAT(c.date_commissioned,'%Y-%m') month1,
						DATE_FORMAT(c.date_commissioned,'%Y-%M') month2,
						DATE_FORMAT(c.date_commissioned,'%m') months,
						DATE_FORMAT(c.date_commissioned,'%Y') years, 
						c.commission_type_id,
						case when c.commission_type_id = 4 then 'Binary'
						when c.commission_type_id = 5 then '5th Pair'
						else 'Others' end com_type,
						case when (c.commission_type_id = 5 and c.ver = 2) then 1
						when (c.commission_type_id = 5 and c.ver = 3) then 2
						else c.amount end amount 
						from oc_user a
						join oc_user b on(a.code = b.code)
						join oc_commission2 c on(c.user_id = b.user_id)	  
						where a.user_id = ".$this->user->getId()."
						  and c.commission_type_id in(4, 5)
						order by DATE_FORMAT(c.date_commissioned,'%Y-%m')
					) t
					group by t.month1 desc, t.com_type
				";	
	
		if($query_type == "data") {		
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
			
			$sqlt = "select count(1) total from (".$sql.") t ";
			
			$query = $this->db->query($sqlt);
			
			//echo $sqlt."<br>";
			return $query->row['total'];
		}	
	
	}
	
	public function getReferralCommissionSummary($user_id, $data = array(), $query_type) {

		$sql = " select t.month1, t.month2, t.months, t.years, t.commission_type_id, t.com_type,  
						sum(t.amount) amount
					from (
						select
						DATE_FORMAT(c.date_commissioned,'%Y-%m') month1,
						DATE_FORMAT(c.date_commissioned,'%Y-%M') month2,
						DATE_FORMAT(c.date_commissioned,'%m') months,
						DATE_FORMAT(c.date_commissioned,'%Y') years, 
						c.commission_type_id,
						case when commission_type_id in(3, 30) then 'Direct'
						else 'Indirect' end com_type,
						c.amount 
						from oc_user a
						join oc_user b on(a.code = b.code)
						join oc_commission2 c on(c.user_id = b.user_id)	
						where a.user_id = ".$this->user->getId()."
						  and (c.commission_type_id in (3,13,14,15,16,17) 
						       or c.commission_type_id between 29 and 42)
						order by DATE_FORMAT(c.date_commissioned,'%Y-%m')
					) t
					group by t.month1 desc, t.com_type
				";	
	
		if($query_type == "data") {		
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
			
			$sqlt = "select count(1) total from (".$sql.") t ";
			
			$query = $this->db->query($sqlt);
			
			//echo $sqlt."<br>";
			return $query->row['total'];
		}	
	
	}	
	
	public function getBinaryComputationDetails($user_id, $data = array(), $query_type) {

		$firstdate_string = $data['years']."-".$data['months']."-01 00:00:00";	

		$date = new DateTime($data['years']."-".$data['months']."-01 00:00:00") ;
		$lastdate_string = $date->format( 'Y-m-t' )." 23:59:59";
	
		$sql = " select b.username spu, concat(b.firstname, ' ', b.lastname) spname,
						d.username, concat(d.firstname, ' ', d.lastname) name, DATE_FORMAT(c.date_commissioned,'%Y-%M') month, 
						case when c.commission_type_id = 4 then c.amount 
						     when (c.ver = 2 and c.commission_type_id = 5) then 1 
							 when (c.ver = 3 and c.commission_type_id = 5) then 2 
							 else 1 end amount, 
						c.shift, c.ver, c.date_commissioned, e.description com_type, c.source_alias, c.source_alias2
						from oc_user a
						join oc_user b on(a.code = b.code)
						join oc_commission2 c on(c.user_id = b.user_id)	  
						left join oc_user d on(d.user_id = c.com_user_id)	  
						join commission_type e on(c.commission_type_id = e.commission_type_id)	  
						where a.user_id = ".$this->user->getId()."
						  and c.commission_type_id in(4, 5)
						  and c.date_commissioned >= '".$firstdate_string."'
						  and c.date_commissioned <= '".$lastdate_string."'
					order by b.user_id asc, c.commission_id desc
				";	
	
		if($query_type == "data") {		
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
			
			$sqlt = "select count(1) total from (".$sql.") t ";
			
			$query = $this->db->query($sqlt);
			
			//echo $sqlt."<br>";
			return $query->row['total'];
		}
	
	}

	public function getReferralComputationDetails($user_id, $data = array(), $query_type) {

		$firstdate_string = $data['years']."-".$data['months']."-01 00:00:00";	

		$date = new DateTime($data['years']."-".$data['months']."-01 00:00:00") ;
		$lastdate_string = $date->format( 'Y-m-t' )." 23:59:59";
	
		$sql = " select b.username spu, concat(b.firstname, ' ', b.lastname) spname,
						d.username, concat(d.firstname, ' ', d.lastname) name, DATE_FORMAT(c.date_commissioned,'%Y-%M') month, 
						c.amount, 
						c.shift, c.ver, c.date_commissioned, e.description com_type
						from oc_user a
						join oc_user b on(a.code = b.code)
						join oc_commission2 c on(c.user_id = b.user_id)	  
						join oc_user d on(d.user_id = c.com_user_id)	  
						join commission_type e on(c.commission_type_id = e.commission_type_id)	  
						where a.user_id = ".$this->user->getId()."
						  and (c.commission_type_id in (3,13,14,15,16,17) 
						       or c.commission_type_id between 29 and 42)
						  and c.date_commissioned >= '".$firstdate_string."'
						  and c.date_commissioned <= '".$lastdate_string."'
					order by b.user_id asc, c.commission_id desc
				";	
	
		if($query_type == "data") {		
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
			
			$sqlt = "select count(1) total from (".$sql.") t ";
			
			$query = $this->db->query($sqlt);
			
			//echo $sqlt."<br>";
			return $query->row['total'];
		}
	
	}
	
	public function debugLog($var1, $var2) {
		$debug = "insert into temps (var1, var2) values ('".$var1."', '".$var2."')";
		$this->db->query($debug);		
	}
	
	public function getEwallets($data, $query_type) {
		$sql = "select a.*, b.username source, c.description com_type
		          from oc_ewallet_hist a
				  left join oc_user b on(a.source_user_id = b.user_id)
				  left join oc_commission_type c on(a.commission_type_id = c.commission_type_id)
				 where a.user_id = ".$this->user->getId();
		if($query_type == "data") {
			$sql .=" order by a.ewallet_hist_id desc ";
			$query = $this->db->query($sql);
			return $query->rows;
		} else {
			$sqlt = "select count(1) total from (".$sql.") t ";
			$query = $this->db->query($sqlt);
			return $query->row['total'];
		}
	}
	
	public function getUserData() {
		$sql = "select a.*, concat(a.lastname, ', ', a.firstname, ' ', a.middlename) name, a.code group_code 
					 , c.branch_id, c.description branch, d.name user_group, a.firstname, a.middlename, a.lastname  
					 , concat(c.branch_type, ' - ', c.legacy_description) legacy, legacy_id, d.redirect_page  
					 , f.images rank_img, a.gender, a.id_no, a.ewallet, a.auto_spill_setup 
				  from oc_user a  
				  join gui_user_branch b on (a.user_id = b.user_id and b.status = 1) 
				  join gui_branch_tbl c on ( b.branch_id = c.branch_id ) 
				  join oc_user_group d on (d.user_group_id = a.user_group_id) 
				  join oc_ranks f on (f.rank_id = a.rank_id) 
				 where a.user_id = " .(int)$this->user->getId()."
					and a.status = 1 and site = '".WEBSITE."'";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getTotalActiveMF() {
		$sql = "select count(1) total
				  from oc_unilevel a
				  join oc_user b on(a.user_id = b.user_id)
				 where a.sponsor_user_id = ".$this->user->getId()."
				   and a.level <= 2
				   and b.user_group_id = 39
				   and b.activation_flag = 1 ";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	public function getTotalInActiveMF() {
		$sql = "select count(1) total
				  from oc_unilevel a
				  join oc_user b on(a.user_id = b.user_id)
				 where a.sponsor_user_id = ".$this->user->getId()."
				   and a.level <= 2
				   and b.user_group_id = 13
				   and b.activation_flag = 0 ";
		$query = $this->db->query($sql);
		return $query->row['total'];		
	}
	
	public function getTotalBanker() {
		$sql = "select count(1) total
				  from oc_unilevel a
				  join oc_user b on(a.user_id = b.user_id)
				 where a.sponsor_user_id = ".$this->user->getId()."
				   and b.user_group_id = 39
				   and b.activation_flag = 1 ";
		$query = $this->db->query($sql);
		return $query->row['total'];		
	}
	
	public function getProducts() {
		$sql = "select item_id, description from gui_items_tbl where active = 1 ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getOrders($data, $query_type = "data") {
		
		$sql = "select a.order_id, a.receipt_number, a.date_added, a.status_id,
					   a.customer_name, a.address, h.description status, f.description mod_desc,
					   a.tracking, i.description moc, a.total, a.amount, e.description payment_option_desc,
					   a.packed_date, a.paid_date, a.delivery_date, a.contact, a.email,
					   a.reference, a.receiver, a.notes, 
					   concat(g.firstname, ' ', g.lastname, '(', g.username,')') reseller,
					   case when a.paid_flag = 1 then 'Paid' else 'Not Paid Yet' end paid_flag
				  from oc_order a
				  left join oc_provinces b on(a.province_id = b.province_id)
				  left join oc_city_municipality c on(a.city_municipality_id = c.city_municipality_id)
				  left join oc_barangays d on(a.barangay_id = d.barangay_id)
				  left join gui_status_tbl e on(a.payment_option = e.status_id)
				  left join gui_status_tbl f on(a.delivery_option = f.status_id)
				  left join oc_user g on(a.user_id = g.user_id) 
				  left join gui_status_tbl h on(a.status_id = h.status_id)
				  left join gui_status_tbl i on(a.mode_of_collection = i.status_id)
				 where 1 = 1 ";
				 
		if($this->user->getUserGroupId() == 39 or $this->user->getUserGroupId() == 13) {
			$sql .= " and a.user_id = ".$this->user->getId();
		}
			
		if($this->user->getUserGroupId() == 12) {
			$sql .= " and a.status_id not in(0,19) ";
		}	
			
		if(isset($data['status_search'])){
			if(!empty($data['status_search'])) {
				if($data['status_search'] == 18) {
					$sql .= " and a.status_id = 18";
				} else if ($data['status_search'] == 34) {
					$sql .= " and a.status_id = 34";
				}else if ($data['status_search'] == 35) {
					$sql .= " and a.status_id = 35";
				}else if ($data['status_search'] == 36) {
					$sql .= " and a.status_id = 36";
				}else if ($data['status_search'] == 19) {
					$sql .= " and a.status_id = 19";
				}else if ($data['status_search'] == 37) {
					$sql .= " and a.status_id = 37";
				}else if ($data['status_search'] == 78) {
					$sql .= " and a.status_id = 78";
				}
			}
		} 

		if(isset($stats)){
			if(!empty($stats)){
				$sql .= " and a.status_id = ".$stats."";
			}
		}
		
		if(isset($data['order_id'])) {
			if(!empty($data['order_id'])) {
				$sql .= " and a.order_id = ".$data['order_id'];
				
			}
		}
		
		if(isset($data['datefrom'])) {
			if (!empty($data['datefrom'])) {
				$filter .= " and a.date_delivered >= '" . $data['datefrom'] . " 00:00:00'";
			}
		}		
		
		if(isset($data['dateto'])) {
			if (!empty($data['dateto'])) {
				$filter .= " and a.date_delivered <= '" . $data['dateto'] . " 23:59:59'";
			}
		}
				  
		if($query_type == "data") {
			$sql .= " order by a.order_id desc ";
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 6;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			//echo $sql."<br>";
			$query = $this->db->query($sql);
			$orders = $query->rows;
			$newRows = array();
			$counter=0;
			foreach($orders as $datum){
				$sql = "SELECT b.item_name description, a.quantity 
						 FROM oc_order_details a 
						 JOIN gui_items_tbl b ON (a.item_id = b.item_id)
						WHERE order_id = ".$datum['order_id'];
				$query = $this->db->query($sql);
				$itemColumn = "";
				$count = 1;
				foreach($query->rows as $items){
					$itemColumn .= $items['description']." - (".$items['quantity'].")<br>";
					$count += 1;
				}
				
				$finalDatum = array();
				$finalDatum = $datum;
				$finalDatum['items'] = $itemColumn; 
				$newRows[$counter] = $finalDatum;
				$counter++;
			}
			return $newRows;
		} else {
			$sqlt = "select count(1) total from (".$sql.") t ";
			$query = $this->db->query($sqlt);
			return $query->row['total'];
		}
	}
	
	public function getStatusByGrouping($grouping) {
		$sql = "select status_id, description from gui_status_tbl where `grouping` = '".$grouping."' ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function updateOrder($data) {
		if($this->user->getUserGroupId() == 12) {
			$sql = "update oc_order 
					   set status_id = ".$data['status_id'].",
						   notes = '".$this->db->escape($data['notes'])."'
					 where order_id = ".$data['order_id'];
			$this->db->query($sql);
		} else {
			$sql = "update oc_order 
					   set notes = '".$this->db->escape($data['notes'])."',
						   reference = '".$this->db->escape($data['reference'])."'
					 where order_id = ".$data['order_id'];
			$this->db->query($sql);

		}
	}

	public function orderexporttocsvbysup($data) {	
		
		$sql = "select m.item_code, m.item_name, sum(l.quantity) total
				  from oc_order a
				  left join oc_order_details l on(a.order_id = l.order_id)
				  left join gui_items_tbl m on(l.item_id = m.item_id)
				 where a.status_id > 0 ";
				 
		if($this->user->getUserGroupId() == 39) {
			$sql .= " and a.user_id = ".$this->user->getId();
		}
		
		if($this->user->getUserGroupId() == 12) {
			$sql .= " and a.status_id not in(0,19) ";
		}
		
		if(isset($data['order_id'])) {
			if(!empty($data['order_id'])) {
				$sql .= " and a.order_id in (".$this->db->escape($data['order_id']).")";
				
			}
		}
		
		if(isset($data['admin_id'])) {
			if(!empty($data['admin_id'])) {
				$sql .= " and a.user_id in (".$this->db->escape($data['admin_id']).")";
				
			}
		}
		
		if(isset($data['status_id'])) {
			if(!empty($data['status_id'])) {
				$sql .= " and a.status_id in (".$this->db->escape($data['status_id']).")";
				
			}
		}
		
		if(isset($data['paid_flag'])) {
			if($data['paid_flag'] != "") {
				$sql .= " and a.paid_flag in (".$this->db->escape($data['paid_flag']).")";				
			}
		}
		
		if(isset($data['page_id'])) {
			if(!empty($data['page_id'])) {
				$sql .= " and a.page_id in (".$this->db->escape($data['page_id']).")";
				
			}
		}
		
		if(isset($data['mode_of_deliveries'])) {
			if(!empty($data['mode_of_deliveries'])) {
				$sql .= " and a.delivery_option in (".$this->db->escape($data['mode_of_deliveries']).")";
				
			}
		}
		
		if(isset($data['payment_option'])) {
			if(!empty($data['payment_option'])) {
				$sql .= " and a.payment_option in (".$this->db->escape($data['payment_option']).")";
				
			}
		}
		
		if(isset($data['mode_of_collection'])) {
			if(!empty($data['mode_of_collection'])) {
				$sql .= " and a.mode_of_collection in (".$this->db->escape($data['mode_of_collection']).")";
				
			}
		}
		
		if(isset($data['datecreatedfrom'])) {
			if (!empty($data['datecreatedfrom'])) {
				$sql .= " and a.date_added >= '" . $data['datecreatedfrom'] . " 00:00:00'";
			}
		}		
		
		if(isset($data['datecreatedto'])) {
			if (!empty($data['datecreatedto'])) {
				$sql .= " and a.date_added <= '" . $data['datecreatedto'] . " 23:59:59'";
			}
		}
		
		if(isset($data['packeddatefrom'])) {
			if (!empty($data['packeddatefrom'])) {
				$sql .= " and a.packed_date >= '" . $data['packeddatefrom'] . " 00:00:00'";
			}
		}		
		
		if(isset($data['packeddateto'])) {
			if (!empty($data['packeddateto'])) {
				$sql .= " and a.packed_date <= '" . $data['packeddateto'] . " 23:59:59'";
			}
		}
		
		if(isset($data['datepaidfrom'])) {
			if (!empty($data['datepaidfrom'])) {
				$sql .= " and a.paid_date >= '" . $data['datepaidfrom'] . " 00:00:00'";
			}
		}		
		
		if(isset($data['datepaidto'])) {
			if (!empty($data['datepaidto'])) {
				$sql .= " and a.paid_date <= '" . $data['datepaidto'] . " 23:59:59'";
			}
		}
		
		if(isset($data['deliverydatefrom'])) {
			if (!empty($data['deliverydatefrom'])) {
				$sql .= " and a.delivery_date >= '" . $data['deliverydatefrom'] . "'";
			}
		}		
		
		if(isset($data['deliverydateto'])) {
			if (!empty($data['deliverydateto'])) {
				$sql .= " and a.delivery_date <= '" . $data['deliverydateto'] . "'";
			}
		}
	
		$sql .= " group by m.item_code, m.item_name ";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getOrdersExportToCSV($data) {	
		
		$sql = "select a.order_id, c.description status, a.date_added, a.packed_date, a.paid_date,
					   case when a.paid_flag = 1 then 'Paid' else 'Not Paid' end paid_flag,
					   a.delivery_date, a.customer_name, 
					   concat(a.address, ', ', k.description, ', ', j.description, ', ', i.description) address,
					   a.contact, e.description mod_desc, a.tracking, h.description moc, 
					   m.item_code, m.item_name, l.quantity, a.amount total,
					   f.username admin_name, d.description payment_option_desc, a.reference, a.receiver,
					   g.description page, a.notes 
				  from oc_order a
				  left join oc_user b on(a.user_id = b.user_id) 
				  left join gui_status_tbl c on(a.status_id = c.status_id)
				  left join gui_status_tbl d on(a.payment_option = d.status_id)
				  left join gui_status_tbl e on(a.delivery_option = e.status_id)
				  left join oc_user f on(a.user_id = f.user_id)
				  left join gui_status_tbl g on(a.page_id = g.status_id)
				  left join gui_status_tbl h on(a.mode_of_collection = h.status_id)
				  left join oc_provinces i on(a.province_id = i.province_id)
		          left join oc_city_municipality j on(a.city_municipality_id = j.city_municipality_id)
		          left join oc_barangays k on(a.barangay_id = k.barangay_id)
				  left join oc_order_details l on(a.order_id = l.order_id)
				  left join gui_items_tbl m on(l.item_id = m.item_id)
				 where a.status_id > 0 ";
				 
		if($this->user->getUserGroupId() == 39) {
			$sql .= " and a.user_id = ".$this->user->getId();
		}
		
		if($this->user->getUserGroupId() == 12) {
			$sql .= " and a.status_id not in(0,19) ";
		}
		
		if(isset($data['order_id'])) {
			if(!empty($data['order_id'])) {
				$sql .= " and a.order_id in (".$this->db->escape($data['order_id']).")";
				
			}
		}
		
		if(isset($data['admin_id'])) {
			if(!empty($data['admin_id'])) {
				$sql .= " and a.user_id in (".$this->db->escape($data['admin_id']).")";
				
			}
		}
		
		if(isset($data['status_id'])) {
			if(!empty($data['status_id'])) {
				$sql .= " and a.status_id in (".$this->db->escape($data['status_id']).")";
				
			}
		}
		
		if(isset($data['paid_flag'])) {
			if($data['paid_flag'] != "") {
				$sql .= " and a.paid_flag in (".$this->db->escape($data['paid_flag']).")";				
			}
		}
		
		if(isset($data['page_id'])) {
			if(!empty($data['page_id'])) {
				$sql .= " and a.page_id in (".$this->db->escape($data['page_id']).")";
				
			}
		}
		
		if(isset($data['mode_of_deliveries'])) {
			if(!empty($data['mode_of_deliveries'])) {
				$sql .= " and a.delivery_option in (".$this->db->escape($data['mode_of_deliveries']).")";
				
			}
		}
		
		if(isset($data['payment_option'])) {
			if(!empty($data['payment_option'])) {
				$sql .= " and a.payment_option in (".$this->db->escape($data['payment_option']).")";
				
			}
		}
		
		if(isset($data['mode_of_collection'])) {
			if(!empty($data['mode_of_collection'])) {
				$sql .= " and a.mode_of_collection in (".$this->db->escape($data['mode_of_collection']).")";
				
			}
		}
		
		if(isset($data['datecreatedfrom'])) {
			if (!empty($data['datecreatedfrom'])) {
				$sql .= " and a.date_added >= '" . $data['datecreatedfrom'] . " 00:00:00'";
			}
		}		
		
		if(isset($data['datecreatedto'])) {
			if (!empty($data['datecreatedto'])) {
				$sql .= " and a.date_added <= '" . $data['datecreatedto'] . " 23:59:59'";
			}
		}
		
		if(isset($data['packeddatefrom'])) {
			if (!empty($data['packeddatefrom'])) {
				$sql .= " and a.packed_date >= '" . $data['packeddatefrom'] . " 00:00:00'";
			}
		}		
		
		if(isset($data['packeddateto'])) {
			if (!empty($data['packeddateto'])) {
				$sql .= " and a.packed_date <= '" . $data['packeddateto'] . " 23:59:59'";
			}
		}
		
		if(isset($data['datepaidfrom'])) {
			if (!empty($data['datepaidfrom'])) {
				$sql .= " and a.paid_date >= '" . $data['datepaidfrom'] . " 00:00:00'";
			}
		}		
		
		if(isset($data['datepaidto'])) {
			if (!empty($data['datepaidto'])) {
				$sql .= " and a.paid_date <= '" . $data['datepaidto'] . " 23:59:59'";
			}
		}
		
		if(isset($data['deliverydatefrom'])) {
			if (!empty($data['deliverydatefrom'])) {
				$sql .= " and a.delivery_date >= '" . $data['deliverydatefrom'] . "'";
			}
		}		
		
		if(isset($data['deliverydateto'])) {
			if (!empty($data['deliverydateto'])) {
				$sql .= " and a.delivery_date <= '" . $data['deliverydateto'] . "'";
			}
		}
	
		$sql .= " order by a.order_id desc ";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}	
	
	public function updateFileExtension($order_id, $ext) {
		$sql = "update oc_order set extension = '".$ext."' where order_id = ".$order_id;
		$this->db->query($sql);
	}

}
?>