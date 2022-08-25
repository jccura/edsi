<?php
class ModelApiRegister extends Model {
	

	public function register($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['sponsor'])) {
			if(empty($data['sponsor'])) {
				$valid = 0;
				$return_msg .= "Sponsor is required\n";
			} else {
				$sql = "SELECT user_id, teamlead_id, coordinator_id 
						FROM oc_user 
						WHERE LOWER(username) = LOWER('".$this->db->escape(trim($data['sponsor']))."')";

				$query = $this->db->query($sql);
				
				$sponsor_details = $this->getSponsorDetails($data);
				if($sponsor_details['valid']){
					$sponsor_id =  $query->row['user_id'];
					$teamlead_id =  $query->row['teamlead_id'];
					$coordinator_id =  $query->row['coordinator_id'];
				} else {
					$valid = 0;
					$return_msg .= $sponsor_details['status_message']."\n";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "Sponsor is required\n";
		}	

		if(isset($data['firstname'])) {
			if(empty($data['firstname'])) {
				$valid = 0;
				$return_msg .= "First Name is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "First Name is required\n";
		}	

		if(isset($data['middlename'])) {
			if(empty($data['middlename'])) {
				$valid = 0;
				$return_msg .= "Middle Name is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Middle Name is required\n";
		}	

		if(isset($data['lastname'])) {
			if(empty($data['lastname'])) {
				$valid = 0;
				$return_msg .= "Last Name is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Last Name is required\n";
		}	

		if(isset($data['gender'])) {
			if(empty($data['gender'])) {
				$valid = 0;
				$return_msg .= "Gender is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Gender is required\n";
		}	

		if(isset($data['username'])) {
			if(empty($data['username'])) {
				$valid = 0;
				$return_msg .= "Username is required\n";
			} else {
				$sql  = "SELECT COUNT(1) AS total
						FROM oc_user
						WHERE username = '".$this->db->escape($data['username'])."'
						AND 1 = 1";

				$total = $this->db->query($sql)->row['total'];

				if($total > 0){
					$valid = 0;
					$return_msg .= "Username is already taken.\n";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "Username is required\n";
		}	

		if(isset($data['password'])) {
			if(empty($data['password'])) {
				$valid = 0;
				$return_msg .= "Password is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Password is required\n";
		}	

		if(isset($data['confirm_password'])) {
			if(empty($data['confirm_password'])) {
				$valid = 0;
				$return_msg .= "Confirm Password is required\n";
			} else {
				if($data['password'] != $data['confirm_password']){
					$valid = 0;
					$return_msg .= "Passwords doesn't match.\n";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "Confirm Password is required\n";
		}	

		if(isset($data['phone_number'])) {
			if(empty($data['phone_number'])) {
				$valid = 0;
				$return_msg .= "Phone Number is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Phone Number is required\n";
		}	

		if(isset($data['email'])) {
			if(empty($data['email'])) {
				$valid = 0;
				$return_msg .= "Email is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Email is required\n";
		}	

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$secret_code = $this->user->Random(6);
			$sql  = "INSERT INTO oc_user 
						SET username 		= '".$this->db->escape(strtolower($data['username']))."' 
						,	password		= '".$this->db->escape(md5($data['password']))."'
						,	firstname		= UCASE('".$this->db->escape($data['firstname'])."')						   
						,	middlename		= UCASE('".$this->db->escape($data['middlename'])."') 
						,	lastname		= UCASE('".$this->db->escape($data['lastname'])."')
						,	contact		 	= '".$this->db->escape($data['phone_number'])."' 
						,	user_group_id	= 13
						,	gender		 	= UCASE('".$this->db->escape($data['gender'])."') 					   
						,	refer_by_id		= ".$sponsor_id." 
						,	teamlead_id		= ".$teamlead_id." 						   
						,	coordinator_id	= ".$coordinator_id." 						   
						,	email		 	= '".$this->db->escape($data['email'])."' 						   
						,	code		 	= '".$secret_code."' 						   
						,	status		 	= 1 
						,	paid_flag		= 0						   
						,	date_added		= '".$this->user->now()."' 						   
						,	site		 	= '".WEBSITE."'";
	 		
	 		$this->db->query($sql);						
			$user_id = $this->db->getLastId();	
			
			if(isset($data['autoactivate'])) {
				if($data['autoactivate'] == 1) {
					$sql = "UPDATE oc_user 
							SET activation_flag = 1
							,	user_group_id = 39 
							WHERE user_id = ".$user_id;
					$this->db->query($sql);
				}
			}

			$sql = "UPDATE oc_user 
					SET id_no = '".IDPREFIX.$user_id."' 
					WHERE user_id = ".$user_id;
			$this->db->query($sql);
			
			$sql = "INSERT INTO oc_unilevel(user_id, sponsor_user_id, level, date_added)										
				    VALUES (".$user_id.", ".$sponsor_id.", 1, '".$this->user->now()."')";			
			$this->db->query($sql);		
			
			$sql = "INSERT INTO oc_unilevel(user_id, sponsor_user_id, level, date_added )					
				    SELECT ".$user_id.", sponsor_user_id, (level + 1), '".$this->user->now()."'						  
					FROM oc_unilevel ou					 
					WHERE user_id = ".$sponsor_id."						   
					AND sponsor_user_id <> 0";			
			$this->db->query($sql);				

			$this->load->model('api/login');
			$user_info = $this->model_api_login->login($data)['data'];

    		return Array( 	'status'			=> 200,
      						'valid' 			=> true,
      						'status_message'	=> 'Registration Successful!',
      						'data'				=> Array('user_info' => $user_info['user_info']) );
		}
	}

	public function getSponsorDetails($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['sponsor'])) {
			if(empty($data['sponsor'])) {
				$valid = 0;
				$return_msg .= "Sponsor is required\n";
			} else {
				$sql  = "SELECT COUNT(1) AS total, user_id sponsor_id, id_no, concat(firstname, ' ', lastname) name, activation_flag
						FROM oc_user
						WHERE LOWER(username) = LOWER('".$this->db->escape($data['sponsor'])."')
						AND 1 = 1";

				$query = $this->db->query($sql);
				$activation_flag = $query->row['activation_flag'];
				$total = $query->row['total'];

				if($total == 0){
					$valid = 0;
					$return_msg .= "Sponsor doesn't exist.\n";
				} else {
					if($activation_flag == 0) {
						$valid = 0;
						$return_msg .= "Sponsor ".$data['sponsor']." is not active.\n";
					} else {
						$return_array['id_no'] = $query->row['id_no'];
						$return_array['name'] = $query->row['name'];
						$return_array['sponsor_id'] = $query->row['sponsor_id'];
						$return_array['activation_flag'] = $query->row['activation_flag'];
					}
				}
				
			}
		} else {
			$valid = 0;
			$return_msg .= "Sponsor is required\n";
		}	

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {


    		return Array( 	'status'			=> 200,
      						'valid' 			=> true,
      						'status_message'	=> 'Sponsor successfully loaded!',
      						'data'				=> $return_array );
		}
	}

}
?>