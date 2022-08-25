<?php
class ModelAccountUsers extends Model {
	public function addUser($data) {
		$valid = 1;
		$return_msg = "";
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
		
		if(isset($data['email'])) {
			if(empty($data['email'])) {
				$valid = 0;
				$return_msg .= "Email is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Email is mandatory.<br>";
		}
		
		if(isset($data['phone'])) {
			if(empty($data['phone'])) {
				$valid = 0;
				$return_msg .= "Phone is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Phone is mandatory.<br>";
		}
		
		if(isset($data['user_group_id'])) {
			if(empty($data['user_group_id'])) {
				$valid = 0;
				$return_msg .= "Usergroup is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Usergroup is mandatory.<br>";
		}
		
		if($valid == 1) {
			$sql  = "INSERT INTO oc_user ";
			$sql .= "   SET username = '".$this->db->escape($data['username'])."' ";
			$sql .= "      ,password = '" . $this->db->escape(md5('14344')) . "'";
			$sql .= "      ,firstname = UCASE('" . $this->db->escape($data['firstname']) . "')";
			$sql .= "      ,middlename = UCASE('" . $this->db->escape($data['middlename']) . "')";
			$sql .= "      ,lastname = UCASE('" . $this->db->escape($data['lastname']) . "')";
			$sql .= "      ,birthdate = '" . $data['birthdate'] . "'";
			$sql .= "      ,email = LCASE('" . $this->db->escape($data['email']) . "')";
			$sql .= "      ,contact = LCASE('" . $this->db->escape($data['phone']) . "')";
			$sql .= "      ,user_group_id = " . $data['user_group_id'] ;
			$sql .= "      ,status = 1 " ;
			$sql .= "      ,site = '".WEBSITE."' " ;
			$sql .= "      ,date_added = '".$this->user->now()."' ";
			
			
			if($data['user_group_id'] == 39) {
				$sql .= " ,sme_flag = 1 ";
			}
			
			// echo $sql;
			$this->db->query($sql);
			
			$user_id = $this->db->getLastId();
			
			$sql = "UPDATE oc_user 
					   SET id_no = concat('".IDPREFIX."',lpad(".$user_id.", 10, '0')) 
					       ,ref ='".md5("EDSI-ID".$user_id)."'
			         WHERE user_id = ".$user_id;
			// var_dump($sql);
			$this->db->query($sql);
			
			return "Successful Creation of user ".$data['username']."<br>";
		} else {
			return $return_msg;
		}	
	}
	
	public function editContacts($data = array()) {
		$valid = 1;
		$return_msg = "";
		
		if(isset($data['email'])) {
			if(empty($data['email'])) {
				$valid = 0;
				$return_msg .= "Email is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Email is mandatory.<br>";
		}
		
		if(isset($data['contact'])) {
			if(empty($data['contact'])) {
				$valid = 0;
				$return_msg .= "Contact is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Contact is mandatory.<br>";
		}
		
		if($valid == 1) {
			if($data['task'] == "editContacts") {
				$sql  = "UPDATE oc_user 
							SET email = '".$this->db->escape($data['email'])."' 
							   ,contact = '" . $this->db->escape($data['contact']) ."'
						  WHERE user_id = ".$this->user->getId();
				$this->db->query($sql);
				
				$this->user->setContact($this->db->escape($data['contact']));
				$this->user->setEmail($this->db->escape($data['email']));
			}
			
			return "Successful update of user.";
		} else {
			return $return_msg;
		}		
		
	}
	public function editAccount($data = array()) {
		$valid = 1;
		$return_msg = "";
		
		if(isset($data['bank_name'])) {
			if(empty($data['bank_name'])) {
				$valid = 0;
				$return_msg .= "Bank name is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Bank name is mandatory.<br>";
		}
		
		if(isset($data['account_name'])) {
			if(empty($data['account_name'])) {
				$valid = 0;
				$return_msg .= "Account name is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Account name is mandatory.<br>";
		}
		
		if(isset($data['account_no'])) {
			if(empty($data['account_no'])) {
				$valid = 0;
				$return_msg .= "Account number is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Account number is mandatory.<br>";
		}
		
		if($valid == 1) {
			if($data['task'] == "editAccount") {
				$sql  = "UPDATE oc_user 
							SET bank_name = '".$this->db->escape($data['bank_name'])."' 
							   ,account_name = '" . $this->db->escape($data['account_name']) ."'
							   ,account_no = '" . $this->db->escape($data['account_no']) ."'
						  WHERE user_id = ".$this->user->getId();
				$this->db->query($sql);
				
			}
			
			return "Successful update of user.";
		} else {
			return $return_msg;
		}		
		
	}
	public function editUser($data = array()) {
		
		$valid = 1;
		$return_msg = "";
		
		$sql = "SELECT code 
				  FROM oc_user 
			     WHERE user_id = ".$this->user->getId();
		$query = $this->db->query($sql);
		$secret_code = $query->row['code'];
		
	
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
					
		if(isset($data['phone'])) {
			if($data['phone'] == "") {
				$valid = 0;
				$return_msg .= "Phone is mandatory.<br>";
			}
		} else {
			$valid = 0;
		}
			
		if($data['task'] == "editProfile") {		
			if(isset($data['secret_code'])){
				if($secret_code != $data['secret_code']) {
					$valid = 0;
					$return_msg = "Secret Code is Invalid. <br>";
				} 
			} else {
				$valid = 0;
			}
			
			if(isset($data['bank_name'])) {
				$valid = 0;
				$return_msg .= "Bank name is mandatory.<br>";
			} else {
				$valid = 0;
			}
			
			if(isset($data['account_name'])) {
				$valid = 0;
				$return_msg .= "Account name is mandatory.<br>";
			} else {
				$valid = 0;
			}
			
			if(isset($data['account_no'])) {
				$valid = 0;
				$return_msg .= "Account number is mandatory.<br>";
			} else {
				$valid = 0;
			}
		}
				
		if($valid == 1) {
			if($data['task'] == "editProfile") {
				$sql  = "UPDATE oc_user 
							SET  username = '".$data['username']."' 
								,contact = LCASE('" . $data['phone'] . "')
								,user_group_id = " . $data['user_group_id'] ."
								,bank_name = '" . $data['bank_name'] . "'
								,account_name = '" . $data['account_name'] . "'
								,account_no = '" . $data['account_no'] . "'
						  WHERE user_id = ".$this->user->getId();
				$this->db->query($sql);
			}
			
			if($data['task'] == "submitedit") {
				$sql  = "UPDATE oc_user 
							SET  username = '".$data['username']."' 
								,firstname = '" . $data['firstname'] . "'
								,middlename = '" . $data['middlename'] . "'
								,lastname = '" . $data['lastname'] . "'
								,birthdate = '" . $data['birthdate'] . "'
								,teamlead_id = '" . $data['teamlead_id'] . "'
								,coordinator_id = '" . $data['coordinator_id'] . "'
								,email = LCASE('" . $data['email'] . "')
								,contact = LCASE('" . $data['phone'] . "')
								,user_group_id = " . $data['user_group_id'] ."									
						  WHERE user_id = ".$data['user_id'];
						  //echo $sql;
				$this->db->query($sql);
			}
			
			return "Successful update of user ".$data['username'];
		} else {
			return $return_msg;
		}
			
		
	}
	
	public function editAddress($data = array()) {
		$valid = 1;
		$return_msg = "";
		
		if(isset($data['checkout_provinces'])) {
			if(empty($data['checkout_provinces'])) {
				$valid = 0;
				$return_msg .= "Province is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Province is mandatory.<br>";
		}
		
		if(isset($data['checkout_city'])) {
			if(empty($data['checkout_city'])) {
				$valid = 0;
				$return_msg .= "City is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "City is mandatory.<br>";
		}
		
		if(isset($data['checkout_barangay'])) {
			if(empty($data['checkout_barangay'])){
				$valid = 0;
				$return_msg .= "Barangay is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Barangay is mandatory.<br>";
		}
		
		if(isset($data['cust_address'])) {
			if(empty($data['cust_address'])){
				$valid = 0;
				$return_msg .= "Address is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Address is mandatory.<br>";
		}
		
		if(isset($data['land_mark'])) {
			if(empty($data['land_mark'])){
				$valid = 0;
				$return_msg .= "Landmark is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Landmark is mandatory.<br>";
		}
		
		if($valid == 1) {
			if($data['task'] == "editAddress") {
				$sql  = "UPDATE oc_user 
							SET address = '".$this->db->escape($data['cust_address'])."'
								,province_id = ".$data['checkout_provinces']."
								,city_municipality_id = ".$data['checkout_city']." 
								,barangay_id = ".$data['checkout_barangay']."
								,landmark = '".$this->db->escape($data['land_mark'])."'
						  WHERE user_id = ".$this->user->getId();
				$this->db->query($sql);
			
				$sql = "SELECT  b.province_id, b.description province_desc, c.city_municipality_id 
							   ,c.description city_municipality_desc, d.barangay_id
							   ,d. description barangay_desc
					 	  FROM oc_user a 
						  LEFT JOIN oc_provinces b ON (a.province_id = b.province_id)
						  LEFT JOIN oc_city_municipality c ON (a.city_municipality_id = c.city_municipality_id)
						  LEFT JOIN oc_barangays d ON (a.barangay_id = d.barangay_id)
						 WHERE user_id = ".$this->user->getId();
				$query = $this->db->query($sql);
				
				$province_desc = $query->row['province_desc'];
				$city_municipality_desc = $query->row['city_municipality_desc'];
				$city_municipality_id = $query->row['city_municipality_id'];
				$barangay_desc = $query->row['barangay_desc'];
				$barangay_id = $query->row['barangay_id'];
				
				$this->user->setLandmark($this->db->escape($data['land_mark']));
				$this->user->setAddress($this->db->escape($data['cust_address']));
				$this->user->setProvinceDesc($this->db->escape($province_desc));
				$this->user->setCityMunicipalityDesc($this->db->escape($city_municipality_desc));
				$this->user->setCityMunicipalityId($this->db->escape($city_municipality_id));
				$this->user->setBarangayDesc($this->db->escape($barangay_desc));
				$this->user->setBarangayId($this->db->escape($barangay_id));
							
							
			}
			// var_dump($sql);
			return "Successful update of address.";
		} else {
			return $return_msg;
		}	
	}
	
	
	public function resetUser($user_id) {
		$sql = "SELECT username 
				  FROM oc_user 
				 WHERE user_id = ".$user_id;
		$query = $this->db->query($sql);
		$username = $query->row['username'];
		
		$sql  = "UPDATE oc_user ";
		$sql .= "   SET password = '" . $this->db->escape(md5('12345')) . "'";
		$sql .= " WHERE user_id = ".$user_id;
		
		$this->db->query($sql);
		
		return "Successful Reset of user ".$username."<br>";
		
	}
	
	public function deleteUser($user_id) {
		$sql = "select username from oc_user where user_id = ".$user_id;
		$query = $this->db->query($sql);
		$username = $query->row['username'];
		
		$sql  = "DELETE FROM oc_user ";
		$sql .= " WHERE user_id = '".$user_id."'";
		//echo $sql;
		$this->db->query($sql);
		
		return "Successful Delete of user ".$username."<br>";
	}	
	public function getUsers($data = array(), $query_type) {
		$sql  = "SELECT  a.user_id, a.username, concat(a.lastname, ', ', a.firstname) as name, a.birthdate
						,b.name user_group , case a.status when 1 then 'Enabled' else 'Disabled' end status_desc, a.status
						,a.contact phone, a.email, e.username sponsor
				   FROM oc_user a 
				   JOIN oc_user_group b ON(a.user_group_id = b.user_group_id)
				   LEFT JOIN oc_user e ON(a.operator_id = e.user_id)
				  WHERE 1 = 1 ";
		
		if(isset($data['status'])) {
			if ($data['status']<>"") {
				$sql .= " AND a.status = " . $data['status']. "";
			}
		}
		
		if(isset($data['branch_id'])) {
			if ($data['branch_id']<>"") {
				$sql .= " AND c.branch_id = " . $data['branch_id']. "";
			}
		}
		
		if(isset($data['user_group_id'])) {
			if ($data['user_group_id']<>"") {
				$sql .= " AND a.user_group_id = " . $data['user_group_id']. "";
			}
		}
		
		if(isset($data['username'])) {
			if (!empty($data['username'])) {
				$sql .= " AND LCASE(a.username) LIKE '%" . $this->db->escape(utf8_strtolower($data['username'])) . "%'";
			}	
		}			
		
		if(isset($data['firstname'])) {
			if (!empty($data['firstname'])) {
				$sql .= " AND LCASE(a.firstname) LIKE '%" . $this->db->escape(utf8_strtolower($data['firstname'])) . "%'";
			}
		}
		
		if(isset($data['middlename'])) {
			if (!empty($data['middlename'])) {
				$sql .= " AND LCASE(a.middlename) LIKE '%" . $this->db->escape(utf8_strtolower($data['middlename'])) . "%'";
			}
		}
		
		if(isset($data['lastname'])) {		
			if (!empty($data['lastname'])) {
				$sql .= " AND LCASE(a.lastname) LIKE '%" . $this->db->escape(utf8_strtolower($data['lastname'])) . "%'";
			}
		}
		
		if($query_type == "data") {
			$sql .= " ORDER BY a.user_id ";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
				if ($data['limit'] < 1) {
					$data['limit'] = 6;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
			return $query->rows;
		} else {
			$sqlt = "SELECT count(1) total FROM (".$sql.") t ";
			$query = $this->db->query($sqlt);
			return $query->row['total'];
		}
	
	}
	
	public function getUserGroups($data = array()) {
			$sql = "SELECT  user_group_id, name 
					  FROM oc_user_group
					 WHERE user_group_id  IN (12,56,57,46,48,54,36,58,59,60)
					 ORDER BY name";		
			
			$query = $this->db->query($sql);
		
			return $query->rows;
	}
	
	public function getUserReference() {
			$sql = "SELECT  ref 
					  FROM oc_user
					 WHERE user_id = ".$this->user->getId();	
			
			$query = $this->db->query($sql);
		
			return $query->row;
	}
	public function checkUsername($username) {
		$count = 0;
		$sql = "SELECT count(1) AS total 
				  FROM oc_user 
				 WHERE LOWER(username) = '".strtolower($username)."'";
		$query = $this->db->query($sql);
		$count = $query->row['total'];
		
		if ($count > 0) {
			return false;
		} else {
			return true;
		}
	}
	public function getBranches() {
		$sql  = "SELECT branch_id, description 
				   FROM gui_branch_tbl";
		$query = $this->db->query($sql);
		return $query->rows;
	}	
	public function getUserDetails($user_id) {
		$sql = "  SELECT a.user_id, concat(a.lastname, ', ', a.firstname, ' ', a.middlename) name 
						,a.code group_code, b.name group_name, a.firstname, a.middlename, a.lastname  
					    ,b.redirect_page, a.gender, a.id_no, a.ewallet, b.user_group_id, a.teamlead_id, a.coordinator_id
						,a.birthdate, a.email, a.contact phone, a.username, a.bank_name, a.account_name, a.account_no
				    FROM oc_user a  
					JOIN oc_user_group b ON (b.user_group_id = a.user_group_id) 
				   WHERE a.user_id = " .$user_id ;
		$query = $this->db->query($sql);
		return $query->row;
	}
	public function updatePassword($data = array()) {
		
		$valid = 1;
		$return_msg = "";
		if(isset($data['oldpassword'])) {
			if(empty($data['oldpassword'])) {
				$valid = 0;
				$return_msg .= "Old Password is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Old Password is mandatory.<br>";
		}
		
		if(isset($data['password'])) {
			if(empty($data['password'])) {
				$valid = 0;
				$return_msg .= "New Password is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "New Password is mandatory.<br>";
		}
		
		if($valid == 1) {
			$sql = "SELECT count(1) as total from oc_user 
					 WHERE user_id = ".$this->user->getId()."
					   AND password = '".$this->db->escape(md5($data['oldpassword']))."'";
			$query = $this->db->query($sql);
			$count = $query->row['total'];
			
			if($count == 0) {
				$valid = 0;
				$return_msg .= "Old Password doesnt match the record on our data.<br>";
			}
		}
		
		if($valid == 1) {
			$sql  = "UPDATE oc_user 
						SET password = '".$this->db->escape(md5($data['password']))."'
					  WHERE user_id = ".$this->user->getId()."
						AND password = '".$this->db->escape(md5($data['oldpassword']))."'";
			$this->db->query($sql);
			return 'Success in updating password of user '.$data['username'];					
		} else {
			return $return_msg;
		}
	}
	
	public function editPassword($data) {
		$sql  = "UPDATE oc_user 
					SET password = '".$this->db->escape(md5($data['password']))."'
				  WHERE user_id = ".$this->user->getId();
		$this->db->query($sql);
		return "Success in updating password.";
	}
	
	public function getModules ($user_group_id) {
		$sql  = "SELECT * 
				   FROM gui_scr_permission_tbl 
				  WHERE user_group_id = ".$user_group_id."
				    AND viewable = 1
				  ORDER BY sort";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getMainMenu($user_group_id){
		$user_group_id = $this->user->getUserGroupId();
		$sql = "SELECT a.permission_id,  a.description, a.image,a.color, a.module, (select count(1) total
				  FROM gui_scr_permission_tbl b 
				 WHERE user_group_id = ".$user_group_id." 
				   AND parent_menu = a.permission_id) counter
				  FROM gui_scr_permission_tbl a
				 WHERE a.user_group_id = ".$user_group_id."
				   AND a.viewable = 1
			       AND a.menu_level = 1
				   AND a.parent_menu = 0
				   AND a.permission_level <= ".$this->user->getPermissionLevel()."
				 ORDER BY a.sort";
				 
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getUserData() {
		$sql = "SELECT a.user_id user_id, concat(a.lastname, ', ', a.firstname, ' ', a.middlename) name 
					  ,a.code group_code, b.name user_group, a.firstname, a.middlename, a.lastname  
				      ,b.redirect_page, a.gender, a.id_no, a.ewallet, b.user_group_id
				      ,a.username, a.birthdate, a.contact phone, a.bank_name, a.account_name, a.account_no
				  FROM oc_user a  
				  JOIN oc_user_group b ON (b.user_group_id = a.user_group_id) 
				 WHERE a.user_id = " .(int)$this->user->getId()."
				   AND a.status = 1 
				   AND site = '".WEBSITE."'";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getUserAccount(){
		$sql = "SELECT bank_name, account_name, account_no, tin FROM oc_user WHERE user_id = " . $this->user->getId();
		$query = $this->db->query($sql);

		return $query->row;
	}
	
	public function setProfilePicExt($profile_pic_ext,$user_id) {
		$sql = "update oc_user 
				   set profile_pic_ext = '".$profile_pic_ext."'
				 where user_id = ".$user_id;
		$this->db->query($sql);
	}
	
	public function setSignaturePicExt($signature_pic_ext) {
		$sql = "update oc_user 
				   set signature_pic_ext = '".$signature_pic_ext."'
				 where user_id = ".$this->user->getId();
		$this->db->query($sql);
	}
	


}
?>