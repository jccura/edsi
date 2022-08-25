<?php
class ModelApiAddress extends Model {
	
	public function getCountries($data) {
		
		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$sql  = "SELECT * 
					FROM oc_country";		

			$countries = $this->db->query($sql);

			return Array( 	'status'			=> 200,
      						'valid' 			=> true,
      						'status_message'	=> 'Successfully fetch countries',
      						'data' 				=> Array('countries' => $countries->rows) );
		}
	}

	public function getProvinces($data) {
		
		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$sql  = "SELECT province_id, description 
					FROM oc_provinces
					ORDER BY description ASC";		

			$provinces = $this->db->query($sql);

			return Array( 	'status'			=> 200,
      						'valid' 			=> true,
      						'status_message'	=> 'Successfully fetch provinces',
      						'data' 				=> Array('provinces' => $provinces->rows) );
		}
	}

	public function getCities($data) {
		
		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['province_id'])) {
			if(empty($data['province_id'])) {
				$valid = 0;
				$return_msg .= "Province ID is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Province ID is required\n";
		}

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$sql  = "SELECT city_municipality_id, description
					FROM oc_city_municipality
					WHERE province_id = ".$this->db->escape($data['province_id'])."
					ORDER BY description ASC";		

			$cities = $this->db->query($sql);

			return Array( 	'status'			=> 200,
      						'valid' 			=> true,
      						'status_message'	=> 'Successfully fetch cities',
      						'data' 				=> Array('cities' => $cities->rows) );
		}
	}

	public function getBarangays($data) {
		
		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['province_id'])) {
			if(empty($data['province_id'])) {
				$valid = 0;
				$return_msg .= "Province ID is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Province ID is required\n";
		}

		if(isset($data['city_municipality_id'])) {
			if(empty($data['city_municipality_id'])) {
				$valid = 0;
				$return_msg .= "City/Municipality ID is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "City/Municipality ID is required\n";
		}

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$sql  = "SELECT barangay_id, description
					FROM oc_barangays
					WHERE province_id = ".$this->db->escape($data['province_id'])."
					AND city_municipality_id = ".$this->db->escape($data['city_municipality_id'])."
					ORDER BY description ASC";			

			$barangays = $this->db->query($sql);

			return Array( 	'status'			=> 200,
      						'valid' 			=> true,
      						'status_message'	=> 'Successfully fetch barangays',
      						'data' 				=> Array('barangays' => $barangays->rows) );
		}
	}

}
?>