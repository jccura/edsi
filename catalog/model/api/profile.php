<?php
class ModelApiProfile extends Model {

	public function updateProfile($data) {
		
		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['user_id'])) {
			if(empty($data['user_id'])) {
				$valid = 0;
				$return_msg .= "User ID is required\n";
			}
		} else {
			$valid = 0;	
			$return_msg .= "User ID is required\n";
		}

		if(isset($data['username'])) {
			if(empty($data['username'])) {
				$valid = 0;
				$return_msg .= "Username is required\n";
			} else {
				$sql  = "SELECT COUNT(1) AS total
						FROM oc_user
						WHERE username = '".$this->db->escape($data['username'])."'
						AND user_id != ".$this->db->escape($data['user_id']);		

				$row_count = $this->db->query($sql)->row['total'];
				
				if($row_count > 0){
					$valid = 0;
					$return_msg .= "Username is already taken.\n";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "Username is required\n";
		}

		if(isset($data['firstname'])) {
			if(empty($data['firstname'])) {
				$valid = 0;
				$return_msg .= "Firstname is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Firstname is required\n";
		}	

		if(isset($data['lastname'])) {
			if(empty($data['lastname'])) {
				$valid = 0;
				$return_msg .= "Lastname is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Lastname is required\n";
		}	

		if(isset($data['mobile_number'])) {
			if(empty($data['mobile_number'])) {
				$valid = 0;
				$return_msg .= "Mobile number is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Mobile number is required\n";
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
			$sql  = "UPDATE oc_user
					SET username = '".$this->db->escape($data['username'])."'
					,	firstname = '".$this->db->escape($data['firstname'])."'
					,	middlename = '".$this->db->escape($data['middlename'])."'
					,	lastname = '".$this->db->escape($data['lastname'])."'
					,	contact = '".$this->db->escape($data['mobile_number'])."'
					, 	email = '".$this->db->escape($data['email'])."'
					WHERE user_id = '".$this->db->escape($data['user_id'])."'";		

			$query = $this->db->query($sql);

			$sql  = "SELECT user_id,
							username, 
							firstname,
							middlename,
							lastname,
							CONCAT(firstname, ' ', middlename, ' ', lastname) name,
							email,
							contact,
							ewallet,
							profile_pic,
							cart_id
					FROM oc_user
					WHERE user_id = ".$this->db->escape($data['user_id']);		

			$user_query = $this->db->query($sql);

			return Array( 	'status'			=> 200,
      						'valid' 			=> true,
      						'status_message'	=> 'Profile update successfully!',
      						'data' 				=> $user_query->rows);
		}
	}

	public function updatePassword($data) {
		
		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['user_id'])) {
			if(empty($data['user_id'])) {
				$valid = 0;
				$return_msg .= "User ID is required\n";
			} else {
				$sql = "SELECT COUNT(1) total
						FROM oc_user 
						WHERE user_id = ".$this->db->escape($data['user_id']);

				$total = $this->db->query($sql)->row['total'];

				if($total == 0){
					$valid = 0;
					$return_msg .= "User ID does not exist.\n";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "User ID is required\n";
		}

		if(isset($data['old_password'])) {
			if(empty($data['old_password'])) {
				$valid = 0;
				$return_msg .= "Old password is required\n";
			} else {
				$sql = "SELECT COUNT(1) total
						FROM oc_user 
						WHERE user_id = ".$this->db->escape($data['user_id'])."
						AND password = '".$this->db->escape(md5($data['old_password']))."'";

				$total = $this->db->query($sql)->row['total'];

				if($total == 0){
					$valid = 0;
					$return_msg .= "Old password is incorrect.\n";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "Old password is required\n";
		}

		if(isset($data['new_password'])) {
			if(empty($data['new_password'])) {
				$valid = 0;
				$return_msg .= "New password is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "New password is required\n";
		}

		if(isset($data['confirm_password'])) {
			if(empty($data['confirm_password'])) {
				$valid = 0;
				$return_msg .= "Confirm password is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Confirm password is required\n";
		}

		if($valid == 1){
			if($data['new_password'] != $data['confirm_password']){
				$valid = 0;
				$return_msg .= "Passwords do not match\n";
			}
		}

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {
			$sql  = "UPDATE oc_user
					SET password = '".$this->db->escape(md5($data['new_password']))."'
					WHERE user_id = '".$this->db->escape($data['user_id'])."'";		

			$query = $this->db->query($sql);

			return Array( 	'status'			=> 200,
      						'valid' 			=> true,
      						'status_message'	=> 'Password update successfully!',
      						'data' 				=> Array());
		}
	}

	public function updatePicture($data) {
		
		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['user_id'])) {
			if(empty($data['user_id'])) {
				$valid = 0;
				$return_msg .= "User ID is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "User ID is required\n";
		}

		if(isset($data['profile_picture'])) {
			if(empty($data['profile_picture'])) {
				$valid = 0;
				$return_msg .= "Profile picture is required\n";
			} else {
				$decodedImage = base64_decode($data['profile_picture']);

				$sql  = "SELECT username
						FROM oc_user
						WHERE user_id = ".$this->db->escape($data['user_id']);		

				$username = $this->db->query($sql)->row['username'];
 
    			$return = file_put_contents(DOC_ROOT."image/profiles/".$username.".JPG", $decodedImage);
			}
		} else {
			$valid = 0;
			$return_msg .= "Profile picture is required\n";
		}

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {
			// list($id, $extension) = explode(".", $data['user_id']);

			$sql  = "UPDATE oc_user
					SET profile_pic = CONCAT(username, '.JPG')
					WHERE user_id = '".$this->db->escape($data['user_id'])."'";		

			$query = $this->db->query($sql);

			$sql  = "SELECT user_id,
							username, 
							firstname,
							middlename,
							lastname,
							CONCAT(firstname, ' ', middlename, ' ', lastname) name,
							email,
							contact,
							ewallet,
							profile_pic,
							cart_id
					FROM oc_user
					WHERE user_id = ".$this->db->escape($data['user_id']);		

			$user_query = $this->db->query($sql);

			return Array( 	'status'			=> 200,
      						'valid' 			=> true,
      						'status_message'	=> 'Profile update successfully!',
      						'data' 				=> $user_query->rows);
		}
	}

}
?>