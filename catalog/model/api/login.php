<?php
class ModelApiLogin extends Model {
	
	private $site = WEBSITE;

	public function login($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['username'])) {
			if(empty($data['username'])) {
				$valid = 0;
				$return_msg .= "Username is required\n";
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

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$sql = "SELECT a.user_id, concat(a.lastname, ', ', a.firstname, ' ', a.middlename) name 
						, a.code group_code, b.name user_group, a.firstname, a.middlename, a.lastname  
						, b.redirect_page, a.gender, a.id_no, a.ewallet, b.user_group_id, a.username, a.permission_level
						, 	a.profile_pic
						, 	a.contact
						, 	a.cart_id
						,	a.email
						,	c.description province
						,	d.description city_municipality
						,	e.description barangay
						,	a.address
					FROM oc_user a  
					JOIN oc_user_group b on (b.user_group_id = a.user_group_id) 
					LEFT JOIN oc_provinces c ON(c.province_id = a.province_id)
					LEFT JOIN oc_city_municipality d ON(d.city_municipality_id = a.city_municipality_id)
					LEFT JOIN oc_barangays e ON(e.barangay_id = a.barangay_id)
				   	WHERE lower(a.username) = lower('" .$this->db->escape($data['username'])."') 	
					AND a.password = '" . $this->db->escape(md5($data['password'])) . "' 
					AND a.status = '1' 
					AND site = '".$this->site."'";		
					
			$query = $this->db->query($sql);

    		if(count($query->rows) > 0){
    			return Array( 	'status'			=> 200,
	      						'valid' 			=> true,
	      						'status_message'	=> 'Successfully Logged in!',
	      						'data'				=> Array('user_info' => $query->row) );
    		} else {
    			return Array( 	'status'			=> 200,
	      						'valid' 			=> false,
	      						'status_message'	=> 'Username or password is incorrect!');
    		}
	    	
		}
	}

}
?>