<?php
class ModelAccountManageWholeSaler extends Model {
	
	public function getWholeSalerDetails($data){
		$sql = "Select a.user_id, a.firstname, a.middlename, a.lastname, a.contact, a.email,
						a.birthdate, a.gender, a.username, b.description 'province', 
						c.description 'city', d.description 'barangay', a.address, 
						a.barangay_id, a.city_municipality_id, a.province_id
				from oc_user a
				left join oc_provinces b on (a.province_id = b.province_id)
				left join oc_city_municipality c on (a.city_municipality_id = c.city_municipality_id)
				left join oc_barangays d on (a.barangay_id = d.barangay_id)
				Where a.user_id =" . $data['user_id'];
		$query = $this->db->query($sql);

		return $query->row;
	}
	
	public function addNewWholeSaler($data){
		
		$sql  = "INSERT INTO oc_user ";
		$sql .= "   SET username ='".$this->db->escape(strtolower($data['username']))."' ";
		$sql .= "      ,password = '" . $this->db->escape(md5($data['password'])) . "'";
		$sql .= "      ,firstname = UCASE('" . $data['firstname'] . "')";
		$sql .= "      ,middlename = UCASE('" . $data['middlename'] . "')";
		$sql .= "      ,lastname = UCASE('" . $data['lastname'] . "')";
		$sql .= "      ,contact =  '". $data['contactno']."' ";
		$sql .= "      ,email =  '". $data['email']."' ";
		$sql .= "      ,birthdate =  '". $data['birthdate']."' ";
		$sql .= "      ,gender =  '". $data['gender']."' ";
		$sql .= "      ,user_group_id = 56 ";
		$sql .= "      ,address = '".$this->db->escape($data['cust_address'])."' ";
		$sql .= "      ,province_id = '".$data['checkout_provinces']."' ";
		$sql .= "      ,city_municipality_id = '".$data['checkout_city']."' ";
		$sql .= "      ,barangay_id = '".$data['checkout_barangay']."' ";
		$sql .= "      ,site = '".WEBSITE."' " ;
		$sql .= "      ,status = 1 " ;
		$sql .= "      ,sme_flag = 1 " ;
		$sql .= "	   ,wholesaler_id = ".$this->user->getId();
		$sql .= "      ,date_added = '".$this->user->now()."' ";
		$this->db->query($sql);
		
		$user_id = $this->db->getLastId();
		$sql = "UPDATE oc_user 
				   SET id_no = concat('".IDPREFIX."',lpad(".$user_id.", 10, '0')) 
				 WHERE user_id = ".$user_id;
		$this->db->query($sql);
		
		return "Wholesaler Registration Successful";
	}
	
	public function updateWholeSalerDetails($data){
		//var_dump($data);
		$password = "";
		if(isset($data['password'])) {
			if(!empty($data['password'])) {
				$password = $data['password'];
			}
		}
		
		if($password == "") {
			
			$sql = "update oc_user 
						SET username ='".$this->db->escape(strtolower($data['username']))."' 
							,firstname = UCASE('" . $data['firstname'] . "')
							,middlename = UCASE('" . $data['middlename'] . "')
							,lastname = UCASE('" . $data['lastname'] . "')
							,contact =  ". $data['contactno']."
							,email =  '". $data['email']."' 
							,birthdate =  '". $data['birthdate']."' 
							,gender =  '". $data['gender']."' 
							,address = '".$this->db->escape($data['cust_address'])."'
							,province_id = ".$data['checkout_provinces']."
							,city_municipality_id = ".$data['checkout_city']." 
							,barangay_id = ".$data['checkout_barangay']." 
							,date_modified = '".$this->user->now()."' 
					where user_id = ".$data['user_id'];

			$this->db->query($sql);
			
		} else {
			
			$sql  = "update oc_user 
						SET username ='".$this->db->escape(strtolower($data['username']))."' 
							,password = '" . $this->db->escape(md5($data['password'])) . "' 
							,firstname = UCASE('" . $data['firstname'] . "')
							,middlename = UCASE('" . $data['middlename'] . "')
							,lastname = UCASE('" . $data['lastname'] . "')
							,contact =  ". $data['contactno']."
							,email =  '". $data['email']."' 
							,birthdate =  '". $data['birthdate']."' 
							,gender =  '". $data['gender']."' 
							,address = '".$this->db->escape($data['cust_address'])."'
							,province_id = ".$data['checkout_provinces']."
							,city_municipality_id = ".$data['checkout_city']." 
							,barangay_id = ".$data['checkout_barangay']." 
							,date_modified = '".$this->user->now()."' 
					where user_id = ".$data['user_id'];
					
			$this->db->query($sql);
		}
		return "Successful Updating Wholesaler Details.";
		
	}
	
	public function disableWholeSaler($data){
		
		$sql = "select username from oc_user where user_id = ".$data['user_id'];
		$query = $this->db->query($sql);
		$username = $query->row['username'];
		
		$sql  = "UPDATE oc_user ";
		$sql .= "   SET status = 0";
		$sql .= "   WHERE user_id = '".$data['user_id']."' ";
		$this->db->query($sql);
		return "Successful Disabling of Wholesaler (".$username.")";

	}
	
	public function enableWholeSaler($data){
		
		$sql = "select username from oc_user where user_id = ".$data['user_id'];
		$query = $this->db->query($sql);
		$username = $query->row['username'];
		
		$sql  = "UPDATE oc_user ";
		$sql .= "   SET status = 1";
		$sql .= "   WHERE user_id = '".$data['user_id']."' ";
		$this->db->query($sql);
		return "Successful Enabling of Wholesaler (".$username.")";

	}
	
	public function getProvince(){
		$sql = "select province_id, description from oc_provinces order by description asc";
		$result = $this->db->query($sql);
		return $result->rows;
	}
		
	public function getWholeSaler($data, $query_type = "data") {
		
		$sql = "Select a.user_id, a.id_no, a.username, concat(a.firstname, ' ',a.lastname) fullname,
				a.email, a.contact, case a.status when 1 then 'Enabled' else 'Disabled' end status, a.date_added,
				concat(a.address, ', ',d.description, ', ',c.description, ', ',b.description) address
				from oc_user a
				left join oc_provinces b on (a.province_id = b.province_id)
				left join oc_city_municipality c on (a.city_municipality_id = c.city_municipality_id)
				left join oc_barangays d on (a.barangay_id = d.barangay_id)
				WHERE a.user_group_id = 56 ";

		if(isset($data['search_username'])) {
			if(!empty($data['search_username'])){
				$sql .= " and lower(a.username) = '".strtolower($data['search_username'])."' ";
			}
		}
		
		if($query_type == "data") {
			$sql .= " ORDER BY a.user_id desc ";
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 6;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
				
			$result = $this->db->query($sql);
			return $result->rows;
			
		} else {
			$sqlt = "select count(1) total from (".$sql.") t";
			$result = $this->db->query($sqlt);
			return $result->row['total'];			
		}
		
	} 
	
	public function getUsername($data){
		$sql = "select count(1) total from oc_user where username = '" .$data['username']. "' ";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	public function getContact($data){
		$sql = "select count(1) total from oc_user where contact = '" .$data['contact']. "' ";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	public function getEmail($data){
		$sql = "select count(1) total from oc_user where email = '" .$data['email']. "' ";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	
}
?>