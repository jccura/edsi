<?php
class ModelApiSendBooking extends Model {
	
	public function sendBookingDetails($data = array(), $current_qd_id){
		$order_result = array();
		$return_msg = "";
		$valid = 1;	
		
		if($valid == 1) {
			$sql = "select a.quick_deliveries_id, a.area_id, a.quick_deliveries_code, a.distance_in_km, a.schedule_of_delivery, 
						a.delivery_fee, a.required_cash_on_hand, a.trans_session_id, a.total_amount, b.description status
					from oc_quick_delivery a
					left join gui_status_tbl b on (a.status = b.status_id)
					where a.quick_deliveries_id = ".$current_qd_id;
									
			$query = $this->db->query($sql);
			
			$current_qd_id = $current_qd_id; 
			$status = $query->row['status']; 
			$area_id = $query->row['area_id'];
			$quick_deliveries_code = $query->row['quick_deliveries_code'];
			$distance_in_km = $query->row['distance_in_km'];
			$schedule_of_delivery = $query->row['schedule_of_delivery'];
			$delivery_fee = $query->row['delivery_fee'];
			$required_cash_on_hand = $query->row['required_cash_on_hand'];
			$trans_session_id = $query->row['trans_session_id'];
			$total_amount = $query->row['total_amount'];
			
			$access_key = "668e76e5f7dd93041065386f4f5f5916";
			$cInit = curl_init("http://localhost/manongexpress/bookingweb");
			curl_setopt_array($cInit, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'task' => "createBooking",
					'access_key' => $access_key,
					'merchant_qd_id' => $current_qd_id,
					'status' => $status,
					'area_id' => $area_id,
					'quick_deliveries_code' => $quick_deliveries_code,
					'distance_in_km' => $distance_in_km,
					'schedule_of_delivery' => $schedule_of_delivery,
					'delivery_fee' => $delivery_fee,
					'required_cash_on_hand' => $required_cash_on_hand,
					'trans_session_id' => $trans_session_id,
					'total_amount' => $total_amount
				)
			));
			$result = curl_exec($cInit);
			$err = curl_errno($cInit);
			$errmsg = curl_error($cInit);
			// echo $access_key ."<br/>";
			// var_dump($cInit);
			// var_dump($result);
			// echo $result;
			// var_dump($err);
			// var_dump($errmsg);
			// var_dump($data);
			
			$return_msg = "Quick Delivery Id # ".$current_qd_id." is sent successfully.<br>";
			
			return $return_msg;
		} else {			
			return $return_msg;
		}
	}
	
	public function sendLocation($data, $current_qd_location_id) {
		$order_result = array();
		$return_msg = "";
		$valid = 1;	
		
		if($valid == 1) {
			$sql = "select a.quick_deliveries_id, a.location_type, a.contact_name, a.contact_number 
						, a.street, a.barangay, a.city, a.province, a.latitude, a.longitude, a.trans_session_id
						, a.landmark, a.instruction , b.description status
					from oc_qd_location a
					left join gui_status_tbl b on (a.status = b.status_id)
					where a.qd_location_id = ".$current_qd_location_id;
			$query = $this->db->query($sql);
			
			$quick_deliveries_id = $query->row['quick_deliveries_id'];
			$location_type = $query->row['location_type'];
			$contact_name = $query->row['contact_name'];
			$contact_number = $query->row['contact_number'];
			$street = $query->row['street'];
			$barangay = $query->row['barangay'];
			$city = $query->row['city'];
			$province = $query->row['province'];
			$latitude = $query->row['latitude'];
			$longitude = $query->row['longitude'];
			$trans_session_id = $query->row['trans_session_id'];
			$landmark = $query->row['landmark'];
			$instruction = $query->row['instruction'];
			$status = $query->row['status'];
			
			$access_key = "668e76e5f7dd93041065386f4f5f5916";
			$cInit = curl_init("http://localhost/manongexpress/bookingweb");
			curl_setopt_array($cInit, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'task' => "sendLocation",
					'access_key' => $access_key,
					'qd_location_id' => $current_qd_location_id,
					'merchant_qd_id' => $quick_deliveries_id,
					'location_type' => $location_type,
					'contact_name' => $contact_name,
					'contact_number' => $contact_number,
					'street' => $street,
					'barangay' => $barangay,
					'city' => $city,
					'province' => $province,
					'latitude' => $latitude,
					'longitude' => $longitude,
					'trans_session_id' => $trans_session_id,
					'landmark' => $landmark,
					'instruction' => $instruction,
					'status' => $status
				)
			));
			$result = curl_exec($cInit);
			$err = curl_errno($cInit);
			$errmsg = curl_error($cInit);
			// echo $access_key ."<br/>";
			// var_dump($cInit);
			// var_dump($result);
			// echo $result;
			// var_dump($err);
			// var_dump($errmsg);
			// var_dump($data);
			
			$return_msg = "Location is sent successfully.<br>";
			
			return $return_msg;
		}
	}
	
	public function removeLocation($data) {
		$order_result = array();
		$return_msg = "";
		$valid = 1;	
		
		if($valid == 1) {
			$sql = "select qd_location_id, trans_session_id, quick_deliveries_id
						from oc_qd_location 
					where qd_location_id = ".$data['qd_location_id'];
			$query = $this->db->query($sql);
			
			$qd_location_id = $query->row['qd_location_id'];
			$trans_session_id = $query->row['trans_session_id'];
			$quick_deliveries_id = $query->row['quick_deliveries_id'];
			
			$access_key = "668e76e5f7dd93041065386f4f5f5916";
			$cInit = curl_init("http://localhost/manongexpress/bookingweb");
			curl_setopt_array($cInit, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'task' => "removeLocation",
					'access_key' => $access_key,
					'qd_location_id' => $qd_location_id,
					'merchant_qd_id' => $quick_deliveries_id,
					'trans_session_id' => $trans_session_id
				)
			));
			$result = curl_exec($cInit);
			$err = curl_errno($cInit);
			$errmsg = curl_error($cInit);
			// echo $access_key ."<br/>";
			// var_dump($cInit);
			// var_dump($result);
			// echo $result;
			// var_dump($err);
			// var_dump($errmsg);
			// var_dump($data);
		}
	}
	
}
?>