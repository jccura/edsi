<?php
class ModelAccountBrgyMaint extends Model {
	
	public function getBrgyList($data, $query_type){
		//var_dump($data);
		$sql = "select a.barangay_id, a.description barangays, a.rider_id, b.description city, 
					   concat(c.username, ' (', c.firstname, ' ', c.lastname, ')') city_dist, 
				       d.description province, f.description branch, a.city_distributor_id,
					   case when a.within_city = 1 then 'Within The City' else 'Outside The City' end within_city
					from oc_barangays a
					left join oc_city_municipality b on(a.city_municipality_id = b.city_municipality_id)
					left join oc_user c on(a.city_distributor_id = c.user_id)
					left join oc_provinces d on(b.province_id = d.province_id)
					left join gui_branch_tbl f on(a.branch_id = f.branch_id)
					where 1 = 1 ";
		
		if(isset($data['checkout_provinces'])){
			if(!empty($data['checkout_provinces'])){
				if ($data['checkout_provinces'] != "all") {
					$sql .= " and d.province_id = " . $data['checkout_provinces'];
				}
			}
		}

		if(isset($data['checkout_city'])){
			if(!empty($data['checkout_city'])){
				if ($data['checkout_city'] != "all") {
					$sql .= " and b.city_municipality_id = " . $data['checkout_city'];
				}
			}
		}
		
		if(isset($data['checkout_barangay'])){
			if(!empty($data['checkout_barangay'])){
				if ($data['checkout_barangay'] != "all") {
					$sql .= " and a.barangay_id = " . $data['checkout_barangay'];
				}
			}
		}
		
		if(isset($data['city_dist'])){
			if(!empty($data['city_dist'])){
				if ($data['city_dist'] != "all") {
					$sql .= " and a.city_distributor_id = " . $data['city_dist'];
				}
			}
		}
		
		if(isset($data['within_city'])) {
			if ($data['within_city'] != "all") {
				$sql .= " and a.within_city = " . $data['within_city'];
			}
		}

		if($query_type == "data") {
			$sql .= " order by city asc ";
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 6;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			// echo $sql;
			$query = $this->db->query($sql);
			return $query->rows;
	    } else if($query_type == "total") {
			$sqlt = "select count(1) total from (".$sql.") t";
			$query = $this->db->query($sqlt);
			return $query->row['total'];
		}
	}

	public function getProvince(){
		$sql = "select province_id, description from oc_provinces order by description asc";
		$result = $this->db->query($sql);
		return $result->rows;
	}
	
	public function getBranches(){

		$sql = "SELECT * 
				  FROM gui_branch_tbl
				 WHERE status = 1
			  ORDER BY description asc";
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function assignBranch($data){
		//var_dump($data);
		$return_msg = "";
		if(isset($data['selected'])) {	
			foreach ($data['selected'] as $code_selected) {
				$sql  = "UPDATE oc_barangays 
						SET branch_id = '" . $data['branch_code'] . "'			
						, rider_id = 0 		
				 WHERE barangay_id = '".$code_selected . "' ";		
				$this->db->query($sql);
			}
			$return_msg = "Successful assignment of branch to the selected barangay(s).";
		} else {
			$return_msg = "No barangay selected.";
		}
		//echo $sql;
		return $return_msg;
	}

	public function getBranchUsers(){

		$sql = "SELECT * FROM oc_user WHERE user_group_id = 53";
		$query = $this->db->query($sql);

		return $query->rows;

	}

	public function updateBranchCoordinates($data){
		$sql = "UPDATE gui_branch_tbl SET longitude = '". $data['longitude'] ."', latitude = '". $data['latitude'] ."' WHERE branch_id = " . $data['branch_code_coordinates'];
		$this->db->query($sql);

		return "Success Updating Branch Coordinates on Map";
	}

	public function updateBranchName($data){
		$sql = "UPDATE gui_branch_tbl SET description = '". $data['new_branch_name'] ."' WHERE branch_id = " . $data['branch_code_update_name'];
		$this->db->query($sql);

		return "Success Updating new Branch Name";
	}

	public function addBranch($data){
		//assigned_user
		if(isset($data['branch_name'])){

			$sql = "INSERT INTO gui_branch_tbl SET 
				user_id = 0
				, branch_code = '". $data['branch_name'] ."'
				, description = '". $data['branch_name'] ."'
				, branch_type = 'BRANCH'
				, status = 1
				, owned_flag = 0
				, date_added = '". $this->user->now() ."'";

			$this->db->query($sql);

			return "Successful add of Branch";

		}else{
			return "Branch name is required";
		}

	}

	public function addDesignatedUser($data){

		$sql = "SELECT count(1) count FROM gui_branch_tbl WHERE user_id = " . $data['assigned_user'];
		$query = $this->db->query($sql);

		$exist = $query->row['count'];

		if($exist == "1"){
			return "User already assigned to a Branch";
		}else{

			$sql = "UPDATE gui_branch_tbl SET user_id = " . $data['assigned_user'] . " WHERE branch_id = " . $data['branch_code_id'];
			$this->db->query($sql);

			return "Successful assignment of Branch";

		}

	}
	
	public function getCityDistributor(){

		$sql = "select * from oc_user
					where user_group_id = 39
					and status = 1 ";
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getWholeSaler(){

		$sql = "select * from oc_user
					where user_group_id = 56
					and status = 1 ";
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function assignToBrgy($data){
		//var_dump($data);
		$return_msg = "";
		if(isset($data['selected'])) {	
			foreach ($data['selected'] as $code_selected) {
				$sql  = "UPDATE oc_barangays 
						SET rider_id = 0 ";
						
				if(isset($data['city_dist'])) {
					if($data['city_dist'] > 0) {
						$sql .= " , city_distributor_id = " . $data['city_dist'];
					}
				}
				
				if(isset($data['branch_id'])) {
					if($data['branch_id'] > 0) {
						$sql .= " , branch_id = " . $data['branch_id'];
					}
				}
				if(isset($data['within_city'])) {
					if($data['within_city'] != "all") {
						$sql .= " , within_city = " . $data['within_city'];
					}
				}
				
				$sql .= " WHERE barangay_id = '".$code_selected . "' ";
				// echo $sql."<br>";
				$this->db->query($sql);
			}
			$return_msg = "Successful assignment of Area Operator to the selected barangay(s).";
		} else {
			$return_msg = "No barangay selected.";
		}
		//echo $sql;
		return $return_msg;
	}
	
	
	public function unAssignBrgy($data){
		$return_msg = "";
		if(isset($data['selected'])) {	
			foreach ($data['selected'] as $code_selected) {
				$sql  = "UPDATE oc_barangays 
							SET city_distributor_id = 0
							   ,within_city = 0
					      WHERE barangay_id = '".$code_selected . "' ";
				$this->db->query($sql);
			}
			$return_msg = "Successful removal of assignment of Area Operator to the selected barangay(s).";
		} else {
			$return_msg = "No barangay selected.";
		}
		//echo $sql;
		return $return_msg;
	}
	
	
	public function getProvinceDesc($province_id) {
		$sql = "select description from oc_provinces where province_id = ".$province_id;
		$query = $this->db->query($sql);
		return $query->row['description'];
	}
	
	public function getCityDesc($city_municipality_id) {
		$sql = "select description 
				  from oc_city_municipality 
				 where city_municipality_id = ".$city_municipality_id;
		$query = $this->db->query($sql);
		return $query->row['description'];
	}
	
	public function getBrgyDesc($barangay_id) {
		$sql = "select description from oc_barangays where barangay_id = ".$barangay_id;
		$query = $this->db->query($sql);
		return $query->row['description'];
	}
	
	public function getCityDist($city_dist_id) {
		$sql = "select concat(username, ' (', firstname, ' ', lastname, ')') dist 
				  from oc_user 
				 where user_id = ".$city_dist_id;
		$query = $this->db->query($sql);
		return $query->row['dist'];
	}
}
?>