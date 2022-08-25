<?php
class ModelAccountBooking extends Model {
	
	public function addDeliveries($data){
		$return_msg = "";
		$valid = 1;	
		
		if(empty($data['pu_phone'])){
			$valid = 0;
			$return_msg = "Pick Up Phone number is required. <br>";
		}
		
		if(empty($data['origin-input'])){
			$valid = 0;
			$return_msg .= "Pick Up location is required. <br>";
		}
		
		if(empty($data['pu_address'])){
			$valid = 0;
			$return_msg .= "Pick Up Address is required. <br>";
		}
		
		if(empty($data['do_name'])){
			$valid = 0;
			$return_msg .= "Receiver is required. <br>";
		}
		
		if(empty($data['do_phone'])){
			$valid = 0;
			$return_msg .= "Drop Off Phone number is required. <br>";
		}
		
		if(empty($data['destination-input'])){
			$valid = 0;
			$return_msg .= "Drop Off location is required. <br>";
		}
		
		if(empty($data['do_address'])){
			$valid = 0;
			$return_msg .= "Drop off Address is required. <br>";
		}
		
		if(isset($data['origin_lat'])){
			if(!empty($data['origin_lat'])){
				$latlong = $data['origin_lat'];
				$origin_lat = preg_replace("/[^0-9,.]/", "",  $latlong);
			}
		}
		
		if(isset($data['origin_long'])){
			if(!empty($data['origin_long'])){
				$latlong = $data['origin_long'];
				$origin_long = preg_replace("/[^0-9,.]/", "",  $latlong);
			}
		}
		
		if(isset($data['dest_lat'])){
			if(!empty($data['dest_lat'])){
				$latlong = $data['dest_lat'];
				$dest_lat = preg_replace("/[^0-9,.]/", "",  $latlong);
			}
		}
		
		if(isset($data['dest_long'])){
			if(!empty($data['dest_long'])){
				$latlong = $data['dest_long'];
				$dest_long = preg_replace("/[^0-9,.]/", "",  $latlong);
			}
		}
		
		if(isset($data['dist'])){
			if(!empty($data['dist'])){
				$dist = $data['dist'];
				$distance = preg_replace("/[^0-9,.]/", "",  $dist);
			}
		}
		
		
		if($valid == 1){
			
			$sql = "insert into oc_quick_deliveries
						set user_id = ".$this->user->getId().",
							rider_id = 1,
							delivery_type = 194,
							required_e_wallet = 1,
							distance_in_km = ".$distance.",
							pickup_contact_name = '".$this->user->getName()."',
							pickup_contact_number = '".$data['pu_phone']."',
							pickup_location = '".$data['origin-input']."',
							pickup_address = '".$data['pu_address']."',
							pickup_landmark = '".$data['pu_landmark']."',
							pickup_latitude = ".$origin_lat.",
							pickup_longitude = ".$origin_long.",
							drop_off_location = '".$data['destination-input']."',
							drop_off_address = '".$data['do_address']."',							
							drop_off_landmark = '".$data['do_landmark']."',
							drop_off_latitude = ".$dest_lat.",
							drop_off_longitude = ".$dest_long.",
							drop_off_contact_name = '".$data['do_name']."',
							drop_off_contact_number = '".$data['do_phone']."',
							date_added = '".$this->user->now()."',
							object_type = 1,
							length = 1,
							width = 1,
							height = 1,
							schedule_of_delivery = '".$this->user->now()."' ";
			$this->db->query($sql);
			
			$quick_deliveries_id = $this->db->getLastId();
			
			// $sql = "select count(1) total 
						// from gui_status_tbl 
					  // where `grouping`  = 'ADDITIONAL SERVICES'";
			// $query = $this->db->query($sql);
			// $count = $query->row['total'];
			// // var_dump($count);
			
			// $sql = "select status_id 
					// from gui_status_tbl 
				  // where `grouping`  = 'ADDITIONAL SERVICES'";
			// $query = $this->db->query($sql);
			// $status_id = $query->row['status_id'];
			// // var_dump($status_id);
			
			// for($i=0;$i<$count;$i++){
				// var_dump($status_id);
			// }
			
			// // for($i=0;$i<$count;$i++){	
				// if(isset(($data['services']))){
					// // if(!null($data['services'].$status_id)){
					// $sql = "insert into oc_additional_services
								// set quick_deliveries_id = ".$quick_deliveries_id.",
									// service_id = ".$data['services'].$status_id;
					// $this->db->query($sql);
					// // var_dump($sql);
					// // var_dump($data['services']);
					// // }
				// }
			// // }
			
			// $sql = "select trim(trailing '0' FROM distance_in_km) as distance_in_km from oc_quick_deliveries where quick_deliveries_id = ".$quick_deliveries_id;
			// $query = $this->db->query($sql);
			// $distance_in_km = $query->row['distance_in_km'];
			
			return "Success. Your delivery has been added.";
		
		} else {
			
			return $return_msg;
		}
	}
	
	public function getServices(){
		$sql = "select * 
					from gui_status_tbl 
				  where `grouping`  = 'ADDITIONAL SERVICES'";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getTotalServices(){
		$sql = "select count(1) total
					from gui_status_tbl 
				  where `grouping` = 'ADDITIONAL SERVICES'
				  order by status_id";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	public function insert_data(){
		ini_set("display_errors", 1);
        $posts = array();    
        // $user_id = $_REQUEST[1234];      
        $user_id = $this->user->getId();      
        
        $sql = "INSERT INTO oc_order(user_id) 
				VALUES (".$user_id.")";
             // sql query to insert values in the table
		$query = $this->db->query($sql);
       
        if($query){
			
         $posts['response'] = array("success" => "1", "msg" => "Inserted Successfully");
		 
        } else {
			
          $posts['response'] = array("success" => "0", "msg" => "Not Inserted");
        }
        echo json_encode($posts);
	}
	
	public function getCurrentBooking() {
		$sql = "select current_qd_id 
				  from oc_user
				 where user_id = ".$this->user->getId();
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getCurrentBookingData($currentBooking) {
		$currentBooking = $currentBooking;
		$sql = "select a.quick_deliveries_id, a.quick_deliveries_code, a.required_e_wallet, a.required_cash_on_hand,
					   concat(b.firstname, ' ', b.lastname) customer_name, a.total_amount, a.special_flag,
					   a.total_waiting_time, a.distance_in_km, a.total_waiting_fee, a.delivery_fee, a.status status_id, c.description status
				  from oc_quick_delivery a
          join oc_user b on(a.user_id = b.user_id)
          left join gui_status_tbl c ON(c.status_id = a.status)
				 where a.quick_deliveries_id = ".$currentBooking['current_qd_id'];
		$query = $this->db->query($sql);
		$this->log->write($sql);
		$currentBooking['quick_deliveries_id'] = $query->row['quick_deliveries_id'];
		$currentBooking['quick_deliveries_code'] = $query->row['quick_deliveries_code'];
		$currentBooking['customer_name'] = $query->row['customer_name'];
		$currentBooking['required_e_wallet'] = $query->row['required_e_wallet'];
		$currentBooking['required_cash_on_hand'] = $query->row['required_cash_on_hand'];
		$currentBooking['total_amount'] = $query->row['total_amount'];
		$currentBooking['distance_in_km'] = $query->row['distance_in_km'];
		$currentBooking['total_waiting_time'] = $query->row['total_waiting_time'];
		$currentBooking['total_waiting_fee'] = $query->row['total_waiting_fee'];
		$currentBooking['delivery_fee'] = $query->row['delivery_fee'];
		$currentBooking['special_flag'] = $query->row['special_flag'];
		$currentBooking['status_id'] = $query->row['status_id'];
		$currentBooking['status'] = $query->row['status'];
		
		$currentBooking['pickuplocations'] = null;
		$sql = "select a.*, a.status status_id, e.description status,
					   b.description brgy_desc, c.description city_town_desc, d.description province_desc
				  from oc_qd_location a 
				  join oc_barangays b on(a.barangay = b.barangay_id)
				  join oc_city_municipality c on(a.city = c.city_municipality_id)
					join oc_provinces d on(a.province = d.province_id)
					left join gui_status_tbl e ON(e.status_id = a.status)
				 where a.quick_deliveries_id = ".$currentBooking['quick_deliveries_id']."
				   and location_type IN ('PICKUP','PICKUPMERCH') ORDER BY a.qd_location_id ASC";
		$query = $this->db->query($sql);
		$query = $this->db->query($sql);
		$pickuplocations = $query->rows;
		$currentBooking['pickuplocations'] = array();
		$counter = 0;
		$cancelled_button_visibility = 1;
		$item_picked_up_total = 0;
		foreach($pickuplocations as $pil) {
			$temp_pil = $pil;
			$sql = "select * 
				  from oc_qd_location_item 
				 where qd_location_id = ".$pil['qd_location_id'];
			$query = $this->db->query($sql);
			$temp_pil['items'] = $query->rows;
			
			$currentBooking['pickuplocations'][$counter] = $temp_pil;
			$counter += 1;

			if($pil['status_id'] != 138){
				$cancelled_button_visibility = 0;
			}

			if($pil['status_id'] == 245){ // if picked up na lahat ng items
				$item_picked_up_total += 1;
			}

		}

		if(!in_array($currentBooking['status_id'], [138, 302])){
			$item_picked_up_total += 1;
		}
		$currentBooking['cancelled_button_visibility'] = $cancelled_button_visibility;
		$currentBooking['delivered_button_visibility'] = count($pickuplocations) == $item_picked_up_total ? 1 : 0;
		
		$sql = "select a.*, a.status status_id, e.description status,
					   b.description brgy_desc, c.description city_town_desc, d.description province_desc
				  from oc_qd_location a 
				  join oc_barangays b on(a.barangay = b.barangay_id)
				  join oc_city_municipality c on(a.city = c.city_municipality_id)
					join oc_provinces d on(a.province = d.province_id)
					left join gui_status_tbl e ON(e.status_id = a.status)
				 where a.quick_deliveries_id = ".$currentBooking['quick_deliveries_id']."
				   and location_type = 'DROPOFF' ";
		$query = $this->db->query($sql);
		$query = $this->db->query($sql);
		$currentBooking['dropofflocations'] = $query->rows;
		
		return $currentBooking;
	}
	
	public function createQD($data) {
		$sql = "select count(1) total
				  from oc_quick_delivery
				 where trans_session_id = '".$data['trans_session_id']."'";
		$query = $this->db->query($sql);
		
		if($query->row['total'] == 0) {
			$sql = "insert into oc_quick_delivery
						set user_id = ".$this->user->getId().",
							rider_id = 0,
							area_id = ".$this->user->getAreaId().",
							payment_method = 194,
							status = 0,
							required_e_wallet = 0,
							trans_session_id = '".$data['trans_session_id']."',
							date_added = '".$this->user->now()."',
							schedule_of_delivery = '".$this->user->now()."' ";
			$this->db->query($sql);
				
			$current_qd_id = $this->db->getLastId();
			
			$sql = "update oc_quick_delivery
					   set quick_deliveries_code = concat('QD','".$this->user->nowym()."', '-',lpad(".$current_qd_id.", 5, '0'))
					 where quick_deliveries_id = ".$current_qd_id;
			$this->db->query($sql);
			
			$sql = "update oc_user 
					   set current_qd_id = ".$current_qd_id."
					 where user_id = ".$this->user->getId();
			$this->db->query($sql);
			
			$this->load->model('api/sendbooking') ;
			$this->model_api_sendbooking->sendBookingDetails($data = array(), $current_qd_id);
		}
	}
	
	public function createQDS($data) {
		$sql = "select count(1) total
				  from oc_quick_delivery
				 where trans_session_id = '".$data['trans_session_id']."'";
		$query = $this->db->query($sql);
		
		if($query->row['total'] == 0) {
			$sql = "insert into oc_quick_delivery
						set user_id = ".$this->user->getId().",
							rider_id = 1,
							area_id = ".$this->user->getAreaId().",
							payment_method = 194,
							status = 0,
							required_e_wallet = 0,
							special_flag = 1,
							trans_session_id = '".$data['trans_session_id']."',
							date_added = '".$this->user->now()."',
							schedule_of_delivery = '".$this->user->now()."' ";
			$this->db->query($sql);
				
			$current_qd_id = $this->db->getLastId();
			
			$sql = "update oc_quick_delivery
					   set quick_deliveries_code = concat('QD','".$this->user->nowym()."', '-',lpad(".$current_qd_id.", 5, '0'))
					 where quick_deliveries_id = ".$current_qd_id;
			$this->db->query($sql);
			
			$sql = "update oc_user 
					   set current_qd_id = ".$current_qd_id."
					 where user_id = ".$this->user->getId();
			$this->db->query($sql);
		}
	}
	
	public function createQDM($data) {
		$sql = "select count(1) total
				  from oc_quick_delivery
				 where trans_session_id = '".$data['trans_session_id']."'";
		$query = $this->db->query($sql);
		
		if($query->row['total'] == 0) {
			$sql = "insert into oc_quick_delivery
						set user_id = ".$this->user->getId().",
							rider_id = 1,
							area_id = ".$this->user->getAreaId().",
							payment_method = 194,
							status = 0,
							required_e_wallet = 0,
							special_flag = 2,
							trans_session_id = '".$data['trans_session_id']."',
							date_added = '".$this->user->now()."',
							schedule_of_delivery = '".$this->user->now()."' ";
			$this->db->query($sql);
				
			$current_qd_id = $this->db->getLastId();
			
			$sql = "update oc_quick_delivery
					   set quick_deliveries_code = concat('QD','".$this->user->nowym()."', '-',lpad(".$current_qd_id.", 5, '0'))
					 where quick_deliveries_id = ".$current_qd_id;
			$this->db->query($sql);
			
			$sql = "update oc_user 
					   set current_qd_id = ".$current_qd_id."
					 where user_id = ".$this->user->getId();
			$this->db->query($sql);
		}
	}
	
	public function addLocationQD($data) {
		$valid = 1;
		$return_array = array();
		$return_array['qd_location_id'] = 0;
		$return_array['qd_location_msg'] = "";
		
		if(isset($data['current_qd_id'])){
			if(empty($data['current_qd_id'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "You need to start a booking first.";
			} else {
				$return_array['current_qd_id'] = $data['current_qd_id'];
			}
		}
		
		if(isset($data['location_type'])){
			if(empty($data['location_type'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Location Type is mandatory.<br>";
			}
		}
		
		if(isset($data['customer_name'])){
			if(empty($data['customer_name'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Customer Name is mandatory.<br>";
			}
		}
		
		if(isset($data['contactno'])){
			if(empty($data['contactno'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Customer Contact Number is mandatory.<br>";
			}
		}
		
		if(isset($data['cust_address'])){
			if(empty($data['cust_address'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Street is mandatory.<br>";
			}
		}
		
		if(isset($data['barangay'])){
			if(empty($data['barangay'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Barangay is mandatory.<br>";
			}
		}
		
		if(isset($data['city_town'])){
			if(empty($data['city_town'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "City/Town is mandatory.<br>";
			}
		}
		
		if(isset($data['new_province'])){
			if(empty($data['new_province'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Province is mandatory.<br>";
			}
		}
		
		if(isset($data['new_province'])){
			if(empty($data['new_province'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Province is mandatory.<br>";
			}
		}
		
		// if(isset($data['latitude'])){
			// if(empty($data['latitude'])){
				// $valid = 0;
				// $return_array['qd_location_msg'] .= "Make sure the marker in the map is set.<br>";
			// }
		// }
		
		// if(isset($data['longitude'])){
			// if(empty($data['longitude'])){
				// $valid = 0;
				// $return_array['qd_location_msg'] .= "Make sure the marker in the map is set.<br>";
			// }
		// }
		
		if($valid == 1) {
			
			$sql = "select count(1) total
				  from oc_quick_delivery
				 where trans_session_id = '".$data['trans_session_id']."'";
			$query = $this->db->query($sql);
			
			if($query->row['total'] == 0) {
				$sql = "insert into oc_qd_location 
						   set quick_deliveries_id = ".$data['current_qd_id']."
							  ,location_type = '".$data['location_type']."'
							  ,`status` = 138 
							  , contact_name = '".$this->db->escape($data['customer_name'])."' 
							  , contact_number = '".$data['contactno']."' 
							  , street = '".$this->db->escape($data['cust_address'])."' 
							  , barangay = ".$data['barangay']."
							  , city = ".$data['city_town']."
							  , province = ".$data['new_province']."
							  , latitude = '".$data['latitude']."'
							  , longitude = '".$data['longitude']."'
							  , trans_session_id = '".$data['trans_session_id']."'
							  , landmark = '".$this->db->escape($data['landmarks'])."' 
							  , instruction = '".$this->db->escape($data['instructions'])."' 
							  , date_added = '".$this->user->now()."' ";
				$this->db->query($sql);
				
				$this->load->model('api/sendbooking');
				$this->model_api_sendbooking->sendLocation($data, $this->db->getLastId());
				
				$this->computeTotals($data['current_qd_id']);
				$this->computeDistances($data['current_qd_id']);
				$return_array['qd_location_id'] = $this->db->getLastId();
				$return_array['current_qd_id'] = $data['current_qd_id'];
				$return_array['qd_location_msg'] = $data['location_type']." Location successfully added.";
			} else {
				$this->redirect("booking");
			}
		}
		return $return_array;
	}

	public function editLocationQD($data) {
		$valid = 1;
		$return_array = array();
		$return_array['qd_location_id'] = 0;
		$return_array['qd_location_msg'] = "";
		
		if(isset($data['current_qd_id'])){
			if(empty($data['current_qd_id'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "You need to start a booking first.";
			} else {
				$return_array['current_qd_id'] = $data['current_qd_id'];
			}
		}
		
		if(isset($data['location_type'])){
			if(empty($data['location_type'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Location Type is mandatory.<br>";
			}
		}
		
		if(isset($data['customer_name'])){
			if(empty($data['customer_name'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Customer Name is mandatory.<br>";
			}
		}
		
		if(isset($data['contactno'])){
			if(empty($data['contactno'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Customer Contact Number is mandatory.<br>";
			}
		}
		
		if(isset($data['cust_address'])){
			if(empty($data['cust_address'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Street is mandatory.<br>";
			}
		}
		
		if(isset($data['barangay'])){
			if(empty($data['barangay'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Barangay is mandatory.<br>";
			}
		}
		
		if(isset($data['city_town'])){
			if(empty($data['city_town'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "City/Town is mandatory.<br>";
			}
		}
		
		if(isset($data['new_province'])){
			if(empty($data['new_province'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Province is mandatory.<br>";
			}
		}
		
		if(isset($data['new_province'])){
			if(empty($data['new_province'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Province is mandatory.<br>";
			}
		}
		
		if(isset($data['latitude'])){
			if(empty($data['latitude'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Make sure the marker in the map is set.<br>";
			}
		}
		
		if(isset($data['longitude'])){
			if(empty($data['longitude'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Make sure the marker in the map is set.<br>";
			}
		}
		
		if($valid == 1) {
			$sql = "update oc_qd_location 
					   set `status` = 138 
						  , contact_name = '".$this->db->escape($data['customer_name'])."' 
						  , contact_number = '".$data['contactno']."' 
						  , street = '".$this->db->escape($data['cust_address'])."' 
						  , barangay = ".$data['barangay']."
						  , city = ".$data['city_town']."
						  , province = ".$data['new_province']."
						  , landmark = '".$this->db->escape($data['landmarks'])."' 
						  , instruction = '".$this->db->escape($data['instructions'])."' 
						  , latitude = '".$data['latitude']."'
						  , longitude = '".$data['longitude']."'
						  , date_added = '".$this->user->now()."' 
					where qd_location_id = ".$data['qd_location_id'];
			$this->db->query($sql);
			$this->computeTotals($data['current_qd_id']);
			$this->computeDistances($data['current_qd_id']);
			$return_array['current_qd_id'] = $data['current_qd_id'];
			$return_array['qd_location_id'] = $data['qd_location_id'];
			$return_array['qd_location_msg'] = "Pick-up Location successfully updated.";
		}
		return $return_array;
	}
	
	public function getMerchants() {
		$sql = "select a.description, a.merchant_id, a.user_id, COALESCE(b.longitude, '0.00') longitude, COALESCE(b.latitude, '0.00') latitude
				  from oc_merchant a
				  join oc_user b on(a.user_id = b.user_id)
				 where a.area_id = ".$this->user->getAreaId();
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function submitFromAffiliatedMerchant($data) {
		var_dump($data);
	}
	
	
	public function addLocationSubmitWithMarkers($data) {
		//var_dump($data);
		$valid = 1;
		$return_array = array();
		$return_array['qd_location_id'] = 0;
		$return_array['qd_location_msg'] = "";
		
		if(isset($data['current_qd_id'])){
			if(empty($data['current_qd_id'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "You need to start a booking first.";
			} else {
				$return_array['current_qd_id'] = $data['current_qd_id'];
			}
			
		} 
		
		if(isset($data['merchant_id'])){
			if(empty($data['merchant_id'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "You have not selected a merchant.";
			} else {
				$sql = "select count(1) total
					      from oc_qd_location
					     where quick_deliveries_id = ".$data['current_qd_id']."
					       and merchant_id = ".$data['merchant_id'];
				$query = $this->db->query($sql);
				//echo $sql."<br>";
				$total = $query->row['total'];
				
				if ($total > 0) {
					$valid = 0;
					$return_array['qd_location_msg'] .= "You have already selected this pickup location.";
				}
			}
		}
		
		if($valid == 1) {
			$sql = "select count(1) total
				  from oc_qd_location
				 where trans_session_id = '".$data['trans_session_id']."'";
			$query = $this->db->query($sql);
			
			if($query->row['total'] == 0) {			
				$qd_location_id = 0;
				if(isset($data['qd_location_id'])){
					$qd_location_id = $data['qd_location_id'];
				}
				
				$sql = "select a.description customer_name, b.contact contactno,
							   b.address cust_address, b.brgy barangay, b.city_town, b.province new_province,
							   b.latitude, b.longitude 
						  from oc_merchant a
						  join oc_user b on(a.user_id = b.user_id)
						 where a.merchant_id = ".$data['merchant_id'];
				$query = $this->db->query($sql);
				$merch = $query->row;
				
				if($qd_location_id == 0) {
					$sql = "insert into oc_qd_location 
							   set quick_deliveries_id = ".$data['current_qd_id']."
								  ,location_type = '".$data['location_type']."'
								  ,`status` = 138 
								  , contact_name = '".$merch['customer_name']."' 
								  , contact_number = '".$merch['contactno']."' 
								  , street = '".$this->db->escape($merch['cust_address'])."' 
								  , barangay = ".$merch['barangay']."
								  , city = ".$merch['city_town']."
								  , province = ".$merch['new_province']."
								  , latitude = '".$data['latitude']."'
								  , longitude = '".$data['longitude']."'
								  , landmark = '' 
								  , instruction = '' 
								  , merchant_id = ".$data['merchant_id']."
								  , trans_session_id = '".$data['trans_session_id']."'
								  , with_merchant = 1
								  , date_added = '".$this->user->now()."' ";
					$this->db->query($sql);
					$return_array['qd_location_id'] = $this->db->getLastId();
					$this->computeTotals($data['current_qd_id']);
					$this->computeDistances($data['current_qd_id']);
					$return_array['current_qd_id'] = $data['current_qd_id'];
					$return_array['qd_location_msg'] = $data['location_type']." Location successfully added.";
				} else {
					$sql = "update oc_qd_location 
							   set `status` = 138 
								  , contact_name = '".$merch['customer_name']."' 
								  , contact_number = '".$merch['contactno']."' 
								  , street = '".$this->db->escape($merch['cust_address'])."' 
								  , barangay = ".$merch['barangay']."
								  , city = ".$merch['city_town']."
								  , province = ".$merch['new_province']."
								  , latitude = '".$data['latitude']."'
								  , longitude = '".$data['longitude']."'
								  , landmark = '".$data['landmarks']."' 
								  , instruction = '".$data['instructions']."' 
								  , merchant_id = ".$data['merchant_id']."
								  , with_merchant = 1
								  , date_added = '".$this->user->now()."' 
							where qd_location_id = ".$qd_location_id;
					$this->db->query($sql);
					$this->computeTotals($data['current_qd_id']);
					$this->computeDistances($data['current_qd_id']);
					$return_array['current_qd_id'] = $data['current_qd_id'];
					$return_array['qd_location_id'] = $qd_location_id;
					$return_array['qd_location_msg'] = $data['location_type']." Location successfully updated.";
				}
			} else {
				$this->redirect("booking");
			}
		}
		return $return_array;
	}
	
	public function getCurrentBookingLocation($qd_location_id) {
		
		$return_array = array();
		$sql = "select a.*, a.status status_id, f.description status,
					   b.description brgy_desc, c.description city_town_desc, 
					   d.description province_desc
				  from oc_qd_location a 
				  join oc_barangays b on(a.barangay = b.barangay_id)
				  join oc_city_municipality c on(a.city = c.city_municipality_id)
				  join oc_provinces d on(a.province = d.province_id)
				  left join gui_status_tbl f on(f.status_id = a.status)
				 where a.qd_location_id = ".$qd_location_id;
		$query = $this->db->query($sql);
		$return_array = $query->row;
		
		$sql = "select * 
				  from oc_qd_location_item 
				 where qd_location_id = ".$qd_location_id;
		$query = $this->db->query($sql);
		$return_array['items'] = $query->rows;
		
		if($return_array['merchant_id'] > 0) {
			$sql = "select * 
					  from oc_items 
					 where merchant_id = ".$return_array['merchant_id'];
			$query = $this->db->query($sql);
			$return_array['mitems'] = $query->rows;
		}
		
		return $return_array;
	}
	
	public function removeLocationSubmit($data) {
		$this->load->model('api/sendbooking');
		$this->model_api_sendbooking->removeLocation($data);
				
		$sql = "delete from oc_qd_location
				where qd_location_id = ".$data['qd_location_id'];
		$this->db->query($sql);
		$this->computeTotals($data['current_qd_id']);
		$this->computeDistances($data['current_qd_id']);
		$return_array['qd_location_id'] = $data['current_qd_id'];
		$return_array['qd_location_msg'] = "Location successfully removed.";
	}
	
	public function addLocationItems($data) {
		//var_dump($data);
		$valid = 1;
		$return_array = array();
		$return_array['current_qd_id'] = $data['current_qd_id'];
		$return_array['qd_location_id'] = $data['qd_location_id'];
		$return_array['qd_location_msg'] = "";
		
		if(isset($data['current_qd_id'])){
			if(empty($data['current_qd_id'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "You need to start a booking first.<br";
			}
		}
		
		if(isset($data['qd_location_id'])){
			if(empty($data['qd_location_id'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "You have not added a location.<br";
			}
		}
		
		if(isset($data['item_desc'])){
			if(empty($data['item_desc'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Item is mandatory.<br>";
			}
		} else {
			$valid = 0;
			$return_array['qd_location_msg'] .= "Item is mandatory.<br>";
		}
		
		if(isset($data['quantity'])){
			if($data['quantity'] == ""){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Quantity is mandatory.<br>";
			} else {
				if(!is_numeric($data['quantity'])) {
					$valid = 0;
					$return_array['qd_location_msg'] .= "Quantity is a number.<br>";
				} else {
					if($data['quantity'] <= 0) {
						$valid = 0;
						$return_array['qd_location_msg'] .= "You must put a quantity, should be more than 0.<br>";
					}
				}
			}
		}
		
		if(isset($data['estimated_price'])){
			if($data['estimated_price'] == ""){
				$valid = 0;
				$return_array['qd_location_msg'] .= "Estimated Price is mandatory.<br>";
			} else {
				if(!is_numeric($data['estimated_price'])) {
					$valid = 0;
					$return_array['qd_location_msg'] .= "Estimated Price is a number.<br>";
				} else {
					if($data['estimated_price'] <= 0) {
						$valid = 0;
						$return_array['qd_location_msg'] .= "You must put an estimated price, hould be more than 0.<br>";
					}
				}
			}
		}
	
		if($valid == 1) {
			$sql = "select count(1) total
				  from oc_qd_location_item
				 where trans_session_id = '".$data['trans_session_id']."'";
			$query = $this->db->query($sql);
			
			if($query->row['total'] == 0) {			
				$sql = "insert into oc_qd_location_item
						   set qd_location_id = ".$data['qd_location_id']."
							 , quick_deliveries_id = ".$data['current_qd_id']."
							 , item_desc = '".$this->db->escape($data['item_desc'])."' 
							 , notes = '".$this->db->escape($data['notes'])."'
							 , est_quantity = ".$data['quantity']."
							 , est_price = ".$data['estimated_price']."
							 , trans_session_id = '".$data['trans_session_id']."'
							 , date_added = '".$this->user->now()."' ";
				$this->db->query($sql);		
				$this->computeTotals($data['current_qd_id']);
				$this->computeDistances($data['current_qd_id']);
				$return_array['qd_location_msg'] = "Items successfully updated.";
			} else {
				$return_array['qd_location_msg'] = "Resubmit is not allowed.";
			}
		} else {
			$return_array['qd_location_msg'] = "No items added, please check: <br>".$return_array['qd_location_msg'];
		}
		
		return $return_array;
	}

	public function addLocationManualItems($data) {
		//var_dump($data);
		$valid = 1;
		$return_array = array();
		$return_array['current_qd_id'] = $data['current_qd_id'];
		$return_array['qd_location_id'] = $data['qd_location_id'];
		$return_array['qd_location_msg'] = "";
		
		if(isset($data['current_qd_id'])){
			if(empty($data['current_qd_id'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "You need to start a booking first.<br";
			}
		}
		
		if(isset($data['qd_location_id'])){
			if(empty($data['qd_location_id'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "You have not added a location.<br";
			}
		}

		if($valid == 1) {
			for($i=1;$i<=10;$i++) {
				$valid = 1;
				if(isset($data['item_desc'.$i])){
					if(empty($data['item_desc'.$i])){
						$valid = 0;
					}
				} else {
					$valid = 0;
				}
				
				if(isset($data['quantity'.$i])){
					if($data['quantity'.$i] == ""){
						$valid = 0;
					} else {
						if(!is_numeric($data['quantity'.$i])) {
							$valid = 0;
						} else {
							if($data['quantity'.$i] <= 0) {
								$valid = 0;
							}
						}
					}
				}
				
				if(isset($data['estimated_price'.$i])){
					if($data['estimated_price'.$i] == ""){
						$valid = 0;
					} else {
						if(!is_numeric($data['estimated_price'.$i])) {
							$valid = 0;
						} else {
							if($data['estimated_price'.$i] <= 0) {
								$valid = 0;
							}
						}
					}
				}
			
				if($valid == 1) {
					$sql = "select count(1) total
						  from oc_qd_location_item
						 where trans_session_id = '".$data['trans_session_id'].$i."'";
					$query = $this->db->query($sql);
					
					if($query->row['total'] == 0) {			
						$sql = "insert into oc_qd_location_item
								   set qd_location_id = ".$data['qd_location_id']."
									 , quick_deliveries_id = ".$data['current_qd_id']."
									 , item_desc = '".$this->db->escape($data['item_desc'.$i])."' 
									 , notes = '".$this->db->escape($data['notes'.$i])."'
									 , est_quantity = ".$data['quantity'.$i]."
									 , est_price = ".$data['estimated_price'.$i]."
									 , trans_session_id = '".$data['trans_session_id'].$i."'
									 , date_added = '".$this->user->now()."' ";
						$this->db->query($sql);
						
						$this->computeTotals($data['current_qd_id']);
						$this->computeDistances($data['current_qd_id']);
						$return_array['qd_location_msg'] .= "Items ".$i." successfully added.<br>";
					} else {
						$return_array['qd_location_msg'] .= "Resubmit is not allowed.<br";
					}
				} else {
					$return_array['qd_location_msg'] .= "Item ".$i." is not added insufficient info.<br>";
				}
			}
		}
		return $return_array;
	}

	public function addLocationPredefinedItems($data) {
		$valid = 1;
		$return_array = array();
		$return_array['current_qd_id'] = $data['current_qd_id'];
		$return_array['qd_location_id'] = $data['qd_location_id'];
		$return_array['qd_location_msg'] = "";
		
		if(isset($data['current_qd_id'])){
			if(empty($data['current_qd_id'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "You need to start a booking first.<br";
			}
		}
		
		if(isset($data['qd_location_id'])){
			if(empty($data['qd_location_id'])){
				$valid = 0;
				$return_array['qd_location_msg'] .= "You have not added a location.<br";
			}
		}
		//var_dump($data);
		
		if($valid == 1) {
			for($i=1;$i<=10;$i++) {
				$valid = 1;				
				if(isset($data['pmitem_id'.$i])){
					if($data['pmitem_id'.$i] == ""){
						$valid = 0;
					} else {
						if(!is_numeric($data['pmitem_id'.$i])) {
							$valid = 0;
						} else {
							if($data['pmitem_id'.$i] <= 0) {
								$valid = 0;
							}
						}
					}
				}
				
				if(isset($data['pquantity'.$i])){
					if($data['pquantity'.$i] == ""){
						$valid = 0;
					} else {
						if(!is_numeric($data['pquantity'.$i])) {
							$valid = 0;
						} else {
							if($data['pquantity'.$i] <= 0) {
								$valid = 0;
							}
						}
					}
				}
			
				if($valid == 1) {
					$sql = "select count(1) total
						  from oc_qd_location_item
						 where trans_session_id = '".$data['trans_session_id'].$i."'";
					$query = $this->db->query($sql);
					$total = $query->row['total']; 					
					if($total == 0) {	
						$sql = "select a.item_id, a.description item, a.srp, a.manong_commission, 
									   a.merchant_id, b.description merchant 
								 from oc_items a
								 join oc_merchant b on(a.merchant_id = b.merchant_id)
								where a.item_id = ".$data['pmitem_id'.$i];
						$query = $this->db->query($sql);
						$item = $query->row;
					
						$sql = "insert into oc_qd_location_item
								   set qd_location_id = ".$data['qd_location_id']."
									 , quick_deliveries_id = ".$data['current_qd_id']."
									 , item_desc = '".$this->db->escape($item['item'])."' 
									 , notes = '".$this->db->escape($data['pnotes'.$i])."'
									 , est_quantity = ".$data['pquantity'.$i]."
									 , est_price = ".($data['pquantity'.$i] * $item['srp'])."
									 , item_id = ".$data['pmitem_id'.$i]."
									 , merchant_id = ".$item['merchant_id']."
									 , trans_session_id = '".$data['trans_session_id'].$i."'
									 , date_added = '".$this->user->now()."' ";
						$this->db->query($sql);
						
						$this->computeTotals($data['current_qd_id']);
						$this->computeDistances($data['current_qd_id']);
						$return_array['qd_location_msg'] .= "Items ".$i." successfully added.<br>";
					} else {
						$return_array['qd_location_msg'] .= "Resubmit is not allowed for Item ".$i.".<br>";
					}
				} else {
					$return_array['qd_location_msg'] .= "Item ".$i." is not added insufficient info.<br>";
				}
			}
		}
		
		return $return_array;
	}
	
	public function computeTotals($current_qd_id) {

		// clear tables
		$sql = "UPDATE oc_qd_location
						SET total_amount = 0.00
						,		dist_from_last_loc = 0.00
						WHERE quick_deliveries_id = ".$current_qd_id;
		$this->db->query($sql);

		$sql = "UPDATE oc_quick_delivery
						SET distance_in_km = 0
						,		required_e_wallet = 0
						,		delivery_fee = 0.00
						WHERE quick_deliveries_id = ".$current_qd_id;
		$this->db->query($sql);

		$total_est_price = 0;
		$total_actual_price = 0;
		$total_waiting_fee = 0;
		$sql = "select * 
				  from oc_qd_location 
				 where quick_deliveries_id = ".$current_qd_id."
				   and location_type = 'PICKUP' ";
		$query = $this->db->query($sql);
		$pickups = $query->rows;

		foreach($pickups as $pil) {
			$pil_total_est_price = 0;
			$pil_total_actual_price = 0;
			$sql = "select * 
					  from oc_qd_location_item 
					 where qd_location_id = ".$pil['qd_location_id'];
			$query = $this->db->query($sql);
			$items = $query->rows;
			foreach($items as $item) {
				$total_est_price += $item['est_price'];
				$total_actual_price += $item['actual_price'] * $item['actual_quantity'];
				$pil_total_est_price += $item['est_price'];
				$pil_total_actual_price += $item['actual_price'] * $item['actual_quantity'];
			}
			$total_waiting_fee += $pil['waiting_fee'];
			
			$sql = "update oc_qd_location 
					   set total_amount = ".$pil_total_est_price."
						  ,actual_amount_collected = ".$pil_total_actual_price."
					 where qd_location_id = ".$pil['qd_location_id'];
			$this->db->query($sql);
			$this->log->write($sql);
		}
		
		$sql = "update oc_quick_delivery 
				   set total_amount = ".$total_est_price."
					  ,required_cash_on_hand = ".$total_est_price."
					  ,total_waiting_fee = ".$total_waiting_fee."
				 where quick_deliveries_id = ".$current_qd_id;
		$this->db->query($sql);
	}
	
	public function computeDistances($current_qd_id) {
		$this->load->model('api/proxyserver') ;
		$sql = "select * 
				  from oc_qd_location 
				 where quick_deliveries_id = ".$current_qd_id."
				   and location_type = 'PICKUP' 
				  order by qd_location_id ";
		$query = $this->db->query($sql);
		$pickups = $query->rows;
		$counter = 1;
		$last_position = "";
		$next_position = "";
		$total_distance = 0;
		
		$sql = "select special_flag 
				  from oc_quick_delivery
				 where quick_deliveries_id = ".$current_qd_id; 
		$query = $this->db->query($sql);
		$special_flag = $query->row['special_flag'];
		
		$total_pickups = count($pickups);

		foreach($pickups as $index => $pil) {
			$next_position = $pil['latitude'].",".$pil['longitude'];
			if($counter > 1) {
				$link = 'https://maps.googleapis.com/maps/api/distancematrix/json?';
				$link .= 'origins='.$last_position;
				$link .= '&destinations='.$next_position;
				$link .= '&key=AIzaSyCPqcTzbj3Wc1YuqYYa8IKYHG6EEumTGSM';
				$this->log->write($link);
				$distance_arr = $this->curlGetRequest($link);
				
				$elements = $distance_arr->rows[0]->elements;
				$total_distance += $elements[0]->distance->value;

				$sql = "update oc_qd_location 
							set dist_from_last_loc = (".$elements[0]->distance->value."/1000)
						where qd_location_id = ".$pil['qd_location_id'];
				$this->db->query($sql);
			} else {
				$sql = "update oc_qd_location 
							set dist_from_last_loc = 0
						where qd_location_id = ".$pil['qd_location_id'];
				$this->db->query($sql);
			}
			
			$counter += 1;
			$last_position = $next_position;
		}

		if ($special_flag != 1) {
			$total_distance = 0;
		}

		$sql = "select * 
				  from oc_qd_location 
				 where quick_deliveries_id = ".$current_qd_id."
				   and location_type = 'DROPOFF' 
				  order by qd_location_id ";
		$query = $this->db->query($sql);
		$dropoff = $query->rows;
		foreach($dropoff as $dol) {
			if($total_pickups > 0) {
				$next_position = $dol['latitude'].",".$dol['longitude'];
				$link = 'https://maps.googleapis.com/maps/api/distancematrix/json?';
				$link .= 'origins='.$last_position;
				$link .= '&destinations='.$next_position;
				$link .= '&key=AIzaSyCPqcTzbj3Wc1YuqYYa8IKYHG6EEumTGSM';
				$this->log->write($link);
				$distance_arr = $this->curlGetRequest($link);
				
				$elements = $distance_arr->rows[0]->elements;
				$total_distance += $elements[0]->distance->value;
				$sql = "update oc_qd_location 
								set dist_from_last_loc = (".$elements[0]->distance->value."/1000)
							where qd_location_id = ".$dol['qd_location_id'];
				$this->db->query($sql);
			}
			
			$last_position = $next_position;
		}
		
		$distanceinkm = $total_distance / 1000;
		
		// $distanceinkm = number_format($distanceinkm,2);
		
		$sql = "update oc_quick_delivery 
				   set distance_in_km = ".$distanceinkm."
				 where quick_deliveries_id = ".$current_qd_id;
		$this->db->query($sql);
		
		$default_fee = 0;
		
		if ($special_flag == 1) {

			$special_amount = 0.00;
		
			$sql = "select * 
						from oc_qd_location 
					where quick_deliveries_id = ".$current_qd_id."
						and location_type = 'PICKUP' order by qd_location_id asc limit 1 ";
			$query = $this->db->query($sql);
			
			$with_merchant = $query->row['with_merchant'];
			$merchant_id = $query->row['merchant_id'];
			
			if($with_merchant){
				$sql = "SELECT special_amount FROM oc_merchant WHERE merchant_id = ".$merchant_id;
				$special_amount = $this->db->query($sql)->row['special_amount'];
			}
			
			$default_fee = empty($special_amount) ? 85 : $special_amount;
			
			$sql = "select count(1) total 
					  from oc_qd_location
					 where quick_deliveries_id = ".$current_qd_id." 
					   and location_type = 'DROPOFF' "; 
			$query = $this->db->query($sql);
			$totalDROPOFF = $query->row['total'];
			$totalDROPOFF = $totalDROPOFF - 1;
			$totalDROPOFF = $totalDROPOFF * 35;
				
			$sql = "select count(1) total 
					  from oc_qd_location
					 where quick_deliveries_id = ".$current_qd_id." 
					   and location_type = 'PICKUP' "; 
			$query = $this->db->query($sql);
			$totalPICKUP = $query->row['total'];
			$totalPICKUP = $totalPICKUP - 1;
			$totalPICKUP = $totalPICKUP * 35;
			
			$delivery_fee = $default_fee + $totalPICKUP + $totalDROPOFF;
			
			$sql = "update oc_quick_delivery 
					   set delivery_fee = ".$delivery_fee.",
						   required_e_wallet = ".$delivery_fee."
					 where quick_deliveries_id = ".$current_qd_id;
			$this->db->query($sql);
			
		} 
		
		if ($special_flag == 0) {
			
			$default_fee = 50;
			
			$sql = "select count(1) total 
					  from oc_qd_location
					 where quick_deliveries_id = ".$current_qd_id." 
					   and location_type = 'PICKUP' "; 
			$query = $this->db->query($sql);
			$totalPICKUP = $query->row['total'];
			$totalPICKUP = $totalPICKUP - 1;
			$totalPICKUP = $totalPICKUP * 35;
			
			$distanceinkm = $distanceinkm * 7;
			$delivery_fee = $distanceinkm + $default_fee + $totalPICKUP;
			
			$sql = "update oc_quick_delivery 
					   set delivery_fee = ".$delivery_fee.",
						   required_e_wallet = ".$delivery_fee."
					 where quick_deliveries_id = ".$current_qd_id;
			$this->db->query($sql);
			
		} 
		
		if($total_pickups == 0 && count($dropoff) == 0){
			$sql = "update oc_quick_delivery 
					   set delivery_fee = 0,
						   required_e_wallet = 0
					 where quick_deliveries_id = ".$current_qd_id;
			$this->db->query($sql);
		}
		
	}
	
	public function curlGetRequest($link){

		// Get cURL resource
		$curl = curl_init();
		// Check if initialization had gone wrong*    
	    if ($curl === false) {
	        throw new Exception('failed to initialize');
	    }

		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, [
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $link
		]);

		// Send the request & save response to $resp
		$response = json_decode(curl_exec($curl));
		// Check the return value of curl_exec(), too
	    if ($response === false) {
	        throw new Exception(curl_error($curl), curl_errno($curl));
	    }
		
		// Close request to clear up some resources
		curl_close($curl);

		return $response;
	}
	
	public function cancelBooking($data){

		if(isset($data['current_qd_id'])){
			$current_qd_id = $data['current_qd_id'];
		} else {
			$sql = "select current_qd_id 
						from oc_user
					where user_id = ".$this->user->getId();
			$query = $this->db->query($sql);
			$current_qd_id = $query->row['current_qd_id'];
		}
		
		if ($current_qd_id > 0) {
			$sql = "update oc_quick_delivery set status = 140 where quick_deliveries_id = ".$current_qd_id;
			$this->db->query($sql);
			
			$sql = "update oc_user set current_qd_id = 0 where user_id = ".$this->user->getId();
			$this->db->query($sql);

			return "Booking cancelled successfully.";
		}
	}
  
	public function hideOrShowQuickDelivery($data){

		$return_array = array();
		$valid = 1;
		$return_msg = "";

		if(isset($data['qd_id'])) {
			if(empty($data['qd_id'])) {
				$valid = 0;
				$return_msg .= "Quick delivery ID is required<br>";
			} else {
					if($data['visibility'] == "to_hide"){
						$sql  = "SELECT COUNT(quick_deliveries_id) total, COALESCE(last_viewed, '0000-00-00 00:00:00') last_viewed, viewing_rider_id
								FROM oc_quick_delivery
								WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);		

						$query = $this->db->query($sql);
						$total = $query->row['total'];
						$last_viewed = $query->row['last_viewed'];
						$viewing_rider_id = $query->row['viewing_rider_id'];

						date_default_timezone_set('Asia/Manila');
						$start_date = new DateTime($last_viewed);
						$since_start = $start_date->diff(new DateTime($this->user->now()));

						if($total > 0 && $since_start->days == 0 && $since_start->y == 0 &&
							$since_start->m == 0 && $since_start->d == 0 && $since_start->h == 0 &&
							$since_start->i == 0 && $since_start->s <= 45){

							if($rider_id != $viewing_rider_id){
								$valid = 0;
								$return_msg .= "Quick delivery is not available<br>";
							}
							
						} else {
							$sql  = "UPDATE oc_quick_delivery
									SET viewable = 1
									WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);	

							$this->db->query($sql);
						}
					}
			}
		} else {
			$valid = 0;
			$return_msg .= "Quick delivery ID is required<br>";
		}		

		if($valid == 0){
			return trim($return_msg);
		} else {
			if ($data['visibility'] == "to_hide") {
				$sql  = "UPDATE oc_quick_delivery
						SET viewable = 0
						,	viewing_rider_id = ".$this->db->escape($this->user->getId())."
						,	last_viewed = '".$this->user->now()."'
						WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);	

				$this->db->query($sql);

				$sql  = "SELECT user_id 
						FROM oc_quick_delivery
						WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);	

				$user_id = $this->db->query($sql)->row['user_id'];

				// add notification
				$sql = "INSERT INTO oc_notifications
						SET title = 'Quick Delivery #".$this->db->escape($data['qd_id'])."'
						,	user_id = ".$user_id."
						,	quick_deliveries_id = ".$this->db->escape($data['qd_id'])."
						,	description = 'Someone viewed you\'re booking!'
						,	date_added = '".$this->user->now()."'";

				$this->db->query($sql);

			} else if($data['visibility'] == "to_show"){
					$sql  = "UPDATE oc_quick_delivery
					SET viewable = 1
					WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);	

				$this->db->query($sql);
			}
			return 'QD Information successfully loaded.';
		}
	}

	public function acceptBooking($data){

			$return_array = array();
			$valid = 1;
			$return_msg = "";

			if(isset($data['qd_id'])) {
				if(empty($data['qd_id'])) {
					$valid = 0;
					$return_msg .= "Quick deliveries ID is required<br>";
				} else {
						$sql = "SELECT user_id, required_e_wallet FROM oc_quick_delivery WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);
						$required_e_wallet = $this->db->query($sql)->row['required_e_wallet'];
						$user_id = $this->db->query($sql)->row['user_id'];
						
				    if($this->user->getId() < $required_e_wallet){
				        $valid = 0;
					   		$return_msg .= "Insufficient ewallet.<br>";
				    }
				}
			} else {
				$valid = 0;
				$return_msg .= "Quick deliveries ID is required<br>";
			}	

			if(isset($data['trans_session_id'])) {
				if(empty($data['trans_session_id'])) {
					$valid = 0;
					$return_msg .= "Transaction Session ID is required<br>";
				} else {
					$sql = "SELECT COUNT(1) total 
							FROM oc_ewallet_hist 
							WHERE session_id = '".$this->db->escape($data['trans_session_id'])."' 
							AND commission_type_id = (SELECT commission_type_id 
						    					FROM gui_commission_type_tbl 
												WHERE LOWER(description) = LOWER('QUICK DELIVERY E-WALLET BOOKING COST DEDUCTION'))";
					$query = $this->db->query($sql);
					$total = $query->row['total'];
	
					if($total > 0){
						$valid = 0;
						$return_msg .= "Resubmission of request is not allowed.<br>";
					}
				}
			} else {
				$valid = 0;
				$return_msg .= "Transaction Session ID is required<br>";
			}

			if($valid == 1){

				$sql = "SELECT COUNT(1) total 
								FROM oc_quick_delivery 
								WHERE rider_id = ".$this->user->getId()."
								AND status IN (244, 245, 302)";
				$query = $this->db->query($sql);
				$total = $query->row['total'];
				if($total >= 3){
					$valid = 0;
					$return_msg .= "Maximum amount of 3 quick deliveries already reached.<br>";
				}
			}

			if($valid == 0){
				return 	trim($return_msg);
			} else {
				
				$sql  = "UPDATE oc_quick_delivery
							SET rider_id = ".$this->user->getId()."
							WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);		

				$this->db->query($sql);

				$sql  = "UPDATE oc_quick_delivery
										SET status = 302 WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);		

				$this->db->query($sql);

				$sql = "INSERT INTO oc_quick_delivery_hist
								SET status_id = 302
								,	quick_deliveries_id = ".$this->db->escape($data['qd_id'])."
								,	date_added = '".$this->user->now()."'";

				$this->db->query($sql);

				// update qd location status
				$sql = "UPDATE oc_qd_location 
								SET status = 138 
								WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);
				$this->db->query($sql);

				$sql = "SELECT qd_location_id FROM oc_qd_location WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);
				$query = $this->db->query($sql);

				foreach($query->rows as $row){
					// update qd location history
					$sql = "INSERT INTO oc_qd_location_hist
									SET status_id = 138
									,	qd_location_id = ".$row['qd_location_id']."
									,	date_added = '".$this->user->now()."'";

					$this->db->query($sql);
				}

				$sql = "SELECT required_e_wallet 
						FROM oc_quick_delivery
						WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);

				$required_e_wallet = $this->db->query($sql)->row['required_e_wallet'];

				$sql = "SELECT ewallet 
						FROM oc_user
						WHERE user_id = ".$this->user->getId();

				$current_e_wallet = $this->db->query($sql)->row['ewallet'];

				$e_wallet = $current_e_wallet - $required_e_wallet;

				// deduct to rider e wallet
				$sql  = "UPDATE oc_user
							SET ewallet = ".$e_wallet."
							WHERE user_id = ".$this->user->getId();		

				$this->db->query($sql);

				// insert history
				$sql = "INSERT INTO oc_ewallet_hist
						SET user_id = ".$this->user->getId()."
						,	debit = ".$this->db->escape($required_e_wallet)."
						,	commission_type_id = (SELECT commission_type_id 
						    					FROM gui_commission_type_tbl 
												WHERE LOWER(description) = LOWER('QUICK DELIVERY E-WALLET BOOKING COST DEDUCTION'))
						,	quick_deliveries_id = ".$this->db->escape($data['qd_id'])."
						,	session_id = '".$this->db->escape($data['trans_session_id'])."'
						,	date_added = '". $this->user->now()."'";

				$this->db->query($sql);

				// add notification
				$sql = "INSERT INTO oc_notifications
								SET title = 'Quick Delivery #".$this->db->escape($data['qd_id'])."'
								, 	user_id = ".$user_id."
								,	quick_deliveries_id = ".$this->db->escape($data['qd_id'])."
								,	description = '".$this->db->escape($this->user->getName().' accepted the booking.')."'
								,	date_added = '".$this->user->now()."'";

				$this->db->query($sql);

				return 'Successfully claimed quick delivery!';
			}
	}

	public function imHere($data){

		$return_array = array();
		$valid = 1;
		$return_msg = "";

		if(isset($data['qd_location_id'])) {
			if(empty($data['qd_location_id'])) {
				$valid = 0;
				$return_msg .= "QD Location ID is required<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "QD Location ID is required<br>";
		}	

		if(isset($data['current_qd_id'])) {
			if(empty($data['current_qd_id'])) {
				$valid = 0;
				$return_msg .= "Quick Deliveries ID is required<br>";
			} else {
				$sql = "SELECT COUNT(1) total, user_id, required_e_wallet
								FROM oc_quick_delivery 
								WHERE quick_deliveries_id = ".$this->db->escape($data['current_qd_id']);
				$query = $this->db->query($sql);
				$total = $query->row['total'];
				$user_id = $query->row['user_id'];
				$required_e_wallet = $query->row['required_e_wallet'];

				if($total == 0){
						$valid = 0;	
						$return_msg .= "Quick deliveries ID doesn't exist.\n";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "Quick Deliveries ID is required<br>";
		}	

		if(isset($data['trans_session_id'])) {
			if(empty($data['trans_session_id'])) {
				$valid = 0;
				$return_msg .= "Transaction Session ID is required<br>";
			} else {
				$sql = "SELECT COUNT(1) total 
						FROM oc_ewallet_hist 
						WHERE session_id = '".$this->db->escape($data['trans_session_id'])."' 
						AND commission_type_id = (SELECT commission_type_id 
												FROM gui_commission_type_tbl 
											WHERE LOWER(description) = LOWER('QUICK DELIVERY E-WALLET BOOKING COST DEDUCTION'))";
				$query = $this->db->query($sql);
				$total = $query->row['total'];

				if($total > 0){
					$valid = 0;
					$return_msg .= "Resubmission of request is not allowed.<br>";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "Transaction Session ID is required<br>";
		}

		if($valid == 0){

			return 	trim($return_msg);

		} else {

			$sql  = "UPDATE oc_qd_location
									SET status = 244 WHERE qd_location_id = ".$this->db->escape($data['qd_location_id']);		

			$this->db->query($sql);

			$sql = "INSERT INTO oc_qd_location_hist
							SET status_id = 244
							,	qd_location_id = ".$this->db->escape($data['qd_location_id'])."
							,	date_added = '".$this->user->now()."'";

			$this->db->query($sql);

			// add notification
			$sql = "INSERT INTO oc_notifications
							SET title = 'Quick Delivery #".$this->db->escape($data['current_qd_id'])."(Location #".$this->db->escape($data['qd_location_id']).")'
							, 	user_id = ".$user_id."
							,	quick_deliveries_id = ".$this->db->escape($data['current_qd_id'])."
							,	qd_location_id = ".$this->db->escape($data['qd_location_id'])."
							,	description = '".$this->db->escape($this->user->getName().' is already on the location and waiting for you.')."'
							,	date_added = '".$this->user->now()."'";

			$this->db->query($sql);

			return 'Notification sent to the customer that you are on the location.';
		}
	}

	public function transferDelivery($data){
		return 'Under development.';
	}

	public function cancelledByCustomer($data){

		$return_array = array();
		$valid = 1;
		$return_msg = "";

		if(isset($data['qd_id'])) {
			if(empty($data['qd_id'])) {
				$valid = 0;
				$return_msg .= "Quick deliveries ID is required<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Quick deliveries ID is required<br>";
		}	

		if(isset($data['trans_session_id'])) {
			if(empty($data['trans_session_id'])) {
				$valid = 0;
				$return_msg .= "Transaction Session ID is required<br>";
			} else {
				$sql = "SELECT COUNT(1) total 
						FROM oc_ewallet_hist 
						WHERE session_id = '".$this->db->escape($data['trans_session_id'])."' 
						AND commission_type_id = (SELECT commission_type_id 
												FROM gui_commission_type_tbl 
											WHERE LOWER(description) = LOWER('QUICK DELIVERY E-WALLET BOOKING COST DEDUCTION'))";
				$query = $this->db->query($sql);
				$total = $query->row['total'];

				if($total > 0){
					$valid = 0;
					$return_msg .= "Resubmission of request is not allowed.<br>";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "Transaction Session ID is required<br>";
		}

		if($valid == 0){

			return 	trim($return_msg);

		} else {

			$sql  = "UPDATE oc_quick_delivery
									SET status = 140 WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);		

			$this->db->query($sql);

			$sql = "INSERT INTO oc_quick_delivery_hist
							SET status_id = 140
							,	quick_deliveries_id = ".$this->db->escape($data['qd_id'])."
							,	date_added = '".$this->user->now()."'";

			$this->db->query($sql);

			$sql = "SELECT required_e_wallet 
						FROM oc_quick_delivery
						WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);

			$required_e_wallet = $this->db->query($sql)->row['required_e_wallet'];

			$sql = "SELECT ewallet 
					FROM oc_user
					WHERE user_id = ".$this->user->getId();

			$current_e_wallet = $this->db->query($sql)->row['ewallet'];

			$e_wallet = $current_e_wallet + $required_e_wallet;

			// deduct to rider e wallet
			$sql  = "UPDATE oc_user
						SET ewallet = ".$e_wallet."
						WHERE user_id = ".$this->user->getId();		

			$this->db->query($sql);

			// insert history
			$sql = "INSERT INTO oc_ewallet_hist
					SET user_id = ".$this->user->getId()."
					,	credit = ".$this->db->escape($required_e_wallet)."
					,	commission_type_id = (SELECT commission_type_id 
												FROM gui_commission_type_tbl 
											WHERE LOWER(description) = LOWER('QUICK DELIVERY CANCELLED ORDER RETURN'))
					,	quick_deliveries_id = ".$this->db->escape($data['qd_id'])."
					,	session_id = '".$this->db->escape($data['trans_session_id'])."'
					,	date_added = '". $this->user->now()."'";

			$this->db->query($sql);
			
			// add notification
			$sql = "INSERT INTO oc_notifications
							SET title = 'Quick Delivery #".$this->db->escape($data['qd_id'])."'
							, 	user_id = ".$this->user->getId()."
							,	quick_deliveries_id = ".$this->db->escape($data['qd_id'])."
							,	description = 'You canceled the delivery.'
							,	date_added = '".$this->user->now()."'";

			$this->db->query($sql);

			return "Quick Delivery #".$data['qd_id']." successfully cancelled.!";
		}
	}

	public function itemPickedUp($data){

		$return_array = array();
		$valid = 1;
		$return_msg = "";

		if(isset($data['qd_location_id'])) {
			if(empty($data['qd_location_id'])) {
				$valid = 0;
				$return_msg .= "QD Location ID is required<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "QD Location ID is required<br>";
		}	

		if(isset($data['current_qd_id'])) {
			if(empty($data['current_qd_id'])) {
				$valid = 0;
				$return_msg .= "Quick deliveries ID is required<br>";
			} else {
				$sql = "SELECT COUNT(1) total, user_id, required_e_wallet
								FROM oc_quick_delivery 
								WHERE quick_deliveries_id = ".$this->db->escape($data['current_qd_id']);
				$query = $this->db->query($sql);
				$total = $query->row['total'];
				$user_id = $query->row['user_id'];
				$required_e_wallet = $query->row['required_e_wallet'];

				if($total == 0){
						$valid = 0;	
						$return_msg .= "Quick deliveries ID doesn't exist.\n";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "Quick deliveries ID is required<br>";
		}	

		if(isset($data['trans_session_id'])) {
			if(empty($data['trans_session_id'])) {
				$valid = 0;
				$return_msg .= "Transaction Session ID is required<br>";
			} else {
				$sql = "SELECT COUNT(1) total 
						FROM oc_ewallet_hist 
						WHERE session_id = '".$this->db->escape($data['trans_session_id'])."' 
						AND commission_type_id = (SELECT commission_type_id 
												FROM gui_commission_type_tbl 
											WHERE LOWER(description) = LOWER('QUICK DELIVERY E-WALLET BOOKING COST DEDUCTION'))";
				$query = $this->db->query($sql);
				$total = $query->row['total'];

				if($total > 0){
					$valid = 0;
					$return_msg .= "Resubmission of request is not allowed.<br>";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "Transaction Session ID is required<br>";
		}

		if($valid == 0){

			return 	trim($return_msg);

		} else {

			$sql  = "UPDATE oc_qd_location
									SET status = 245 WHERE qd_location_id = ".$this->db->escape($data['qd_location_id']);		

			$this->db->query($sql);

			$sql = "INSERT INTO oc_qd_location_hist
							SET status_id = 245
							,	qd_location_id = ".$this->db->escape($data['qd_location_id'])."
							,	date_added = '".$this->user->now()."'";

			$this->db->query($sql);

			// add notification
			$sql = "INSERT INTO oc_notifications
							SET title = 'Quick Delivery #".$this->db->escape($data['current_qd_id'])."(Location #".$this->db->escape($data['qd_location_id']).")'
							, 	user_id = ".$user_id."
							,	quick_deliveries_id = ".$this->db->escape($data['current_qd_id'])."
							,	description = '".$this->user->getName()." successfully picked up the items.'
							,	date_added = '".$this->user->now()."'";

			$this->db->query($sql);

			// update items
			$total_actual_price = 0;
					
			if(isset($data['qd_location_item_id'])){
				if(count($data['qd_location_item_id']) > 0){
					foreach($data['qd_location_item_id'] as $index => $qd_location_item_id){
						$total_actual_price += $data['actual_price'][$index] * $data['actual_quantity'][$index];
						$sql = "UPDATE oc_qd_location_item
										SET actual_quantity = ".$this->db->escape($data['actual_quantity'][$index])."
										,	actual_price = ".$this->db->escape($data['actual_price'][$index])."
										WHERE qd_location_item_id = ".$this->db->escape($qd_location_item_id);
						$this->db->query($sql);
					}
					
					$sql = "update oc_qd_location 
								set actual_amount_collected = ".$total_actual_price."
							where qd_location_id = ".$this->db->escape($data['qd_location_id']);
					$this->db->query($sql);
				}
			}

			return "Quick Delivery #".$data['current_qd_id']." successfully picked up the items.";
		}

	}

	public function updateItems($data){

    $total_actual_price = 0;
        
		foreach($data['qd_location_item_id'] as $index => $qd_location_item_id){
		  $total_actual_price += $data['actual_price'][$index] * $data['actual_quantity'][$index];
			$sql = "UPDATE oc_qd_location_item
							SET actual_quantity = ".$this->db->escape($data['actual_quantity'][$index])."
							,	actual_price = ".$this->db->escape($data['actual_price'][$index])."
							WHERE qd_location_item_id = ".$this->db->escape($qd_location_item_id);
			$this->db->query($sql);
		}
		#$this->computeTotals($data['current_qd_id']);
		$sql = "update oc_qd_location 
				   set actual_amount_collected = ".$total_actual_price."
				 where qd_location_id = ".$this->db->escape($qd_location_item_id);
		$this->db->query($sql);

		$sql = "SELECT SUM(actual_amount_collected) actual_amount_collected 
						FROM oc_qd_location 
						WHERE quick_deliveries_id = ".$this->db->query($data['current_qd_id']);
		$query = $this->db->query($sql);
		$actual_amount_collected = $query->row['actual_amount_collected'];

		$sql = "update oc_quick_delivery 
				   set actual_collected_amount = ".$actual_amount_collected."
				 where quick_deliveries_id = ".$this->db->escape($data['current_qd_id']);
		$this->db->query($sql);
		return 'Item successfully updated.';
	}

	public function delivered($data){

		$return_array = array();
		$valid = 1;
		$return_msg = "";

		if(isset($data['qd_id'])) {
			if(empty($data['qd_id'])) {
				$valid = 0;
				$return_msg .= "Quick deliveries ID is required<br>";
			}
		} else {
			$valid = 0;
			$return_msg .= "Quick deliveries ID is required<br>";
		}	

		if(isset($data['trans_session_id'])) {
			if(empty($data['trans_session_id'])) {
				$valid = 0;
				$return_msg .= "Transaction Session ID is required<br>";
			} else {
				$sql = "SELECT COUNT(1) total 
						FROM oc_ewallet_hist 
						WHERE session_id = '".$this->db->escape($data['trans_session_id'])."' 
						AND commission_type_id = (SELECT commission_type_id 
												FROM gui_commission_type_tbl 
											WHERE LOWER(description) = LOWER('QUICK DELIVERY E-WALLET BOOKING COST DEDUCTION'))";
				$query = $this->db->query($sql);
				$total = $query->row['total'];

				if($total > 0){
					$valid = 0;
					$return_msg .= "Resubmission of request is not allowed.<br>";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "Transaction Session ID is required<br>";
		}

		if($valid == 0){

			return 	trim($return_msg);

		} else {

			$sql  = "UPDATE oc_quick_delivery
									SET status = 247 WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);		

			$this->db->query($sql);

			$sql = "INSERT INTO oc_quick_delivery_hist
							SET status_id = 247
							,	quick_deliveries_id = ".$this->db->escape($data['qd_id'])."
							,	date_added = '".$this->user->now()."'";

			$this->db->query($sql);

			// =============

			// get quick delivery delivery fee
			$sql = "SELECT delivery_fee, user_id FROM oc_quick_delivery WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);
			$query = $this->db->query($sql);
			$delivery_fee = $query->row['delivery_fee'];
			$customer_id = $query->row['user_id'];

			// get user_group_id
			$sql = "SELECT user_group_id FROM oc_user WHERE user_id = ".$this->db->escape($customer_id);
			$user_group_id = $this->db->query($sql)->row['user_group_id'];

			// get rider current ewallet, area_id and referror id
			$sql = "SELECT ewallet, area_id, refer_by_id FROM oc_user WHERE user_id = ".$this->user->getId();
			$query = $this->db->query($sql);
			$rider_e_wallet = $query->row['ewallet'];
			$rider_area_id = $query->row['area_id'];

			// get area manager user_group_id 
			$sql = "SELECT user_group_id FROM oc_user_group WHERE name = 'AREA MANAGER'";
			$query = $this->db->query($sql);
			$area_manager_group_id = $query->row['user_group_id'];

			// get rider's area manager id and ewallet
			$sql = "SELECT user_id, ewallet
							FROM oc_user 
							WHERE user_group_id = ".$area_manager_group_id." 
							AND area_id = ".$rider_area_id;
			$query = $this->db->query($sql);
			$area_manager_id = $query->row['user_id'];
			$area_manager_e_wallet = $query->row['ewallet'];

			// compute delivery fee: 5% referral, 15% manong profit, 80% rider
			if(in_array($user_group_id, [88, 98])){
					$referral_bonus = $delivery_fee * 0.05;
					$rider_ewallet_return = $delivery_fee * 0.8;
					$profit = $delivery_fee * .15;
			}

			if($user_group_id == 111){
					$referral_bonus = 0.00;
					$rider_ewallet_return = $delivery_fee * 0.8;
					$profit = $delivery_fee * .2;
			}

			// update rider's ewallet
			$sql  = "UPDATE oc_user
							SET ewallet = (ewallet + ".$rider_ewallet_return.")
							WHERE user_id = ".$this->user->getId();
	
			$this->db->query($sql);

			// add rider's ewallet history
			$sql = "INSERT INTO oc_ewallet_hist
					SET user_id = ".$this->user->getId()."
						, 	credit = ".$this->db->escape($rider_ewallet_return)."
						, 	commission_type_id = (SELECT commission_type_id 
												FROM gui_commission_type_tbl 
											WHERE LOWER(description) = LOWER('QUICK DELIVERY DELIVERY FEE E-WALLET RETURN'))
					,	quick_deliveries_id = ".$this->db->escape($data['qd_id'])."
					,	session_id = '".$this->db->escape($data['trans_session_id'])."'
					, 	date_added = '". $this->user->now()."'";
			
			$this->db->query($sql);

			// update profit column on oc_quick_delivery table
			$sql = "UPDATE oc_quick_delivery SET profit = ".$profit." WHERE quick_deliveries_id = ".$this->db->escape($data['qd_id']);

			$this->db->query($sql);

			// update area manager's ewallet
			$sql  = "UPDATE oc_user
							SET ewallet = (ewallet + ".$profit.")
							WHERE user_id = ".$area_manager_id;
	
			$this->db->query($sql);
			
			// add area manager's ewallet history
			$sql = "INSERT INTO oc_ewallet_hist
						SET user_id = ".$area_manager_id."
							, 	credit = ".$this->db->escape($profit)."
							, 	commission_type_id = (SELECT commission_type_id 
													FROM gui_commission_type_tbl 
												WHERE LOWER(description) = LOWER('QUICK DELIVERY PROFIT'))
						,	quick_deliveries_id = ".$this->db->escape($data['qd_id'])."
						,	session_id = '".$this->db->escape($data['trans_session_id'])."'
						, 	date_added = '". $this->user->now()."'";

			$this->db->query($sql);

			if(in_array($user_group_id, [88, 98])){

				// ==============

				//So sa 5%
				// = 30% sa nag order
				// = 30% 1st level sponsor
				// = 20% 2nd level sponsor
				// = 20% 3rd level sponsor

				//commission direct referral
				$sql = "select a.refer_by_id
						from oc_user a
						where a.user_id = ".$customer_id;
				$query = $this->db->query($sql);

				$this->insertCommission($customer_id, $customer_id, 71, 1, $referral_bonus * 0.3, $data['qd_id']);
				
				$sql = "select a.sponsor_user_id, a.level
						from oc_unilevel a
						where a.user_id = ".$customer_id;
				$query = $this->db->query($sql);
				$referrors = $query->rows;

				foreach($referrors as $ref) {
					if($ref['level'] == 1) { 
						$this->insertCommission($ref['sponsor_user_id'], $customer_id, 70, $ref['level'], $referral_bonus * 0.3, $data['qd_id']);
					}
					
					if($ref['level'] == 2) {
						$this->insertCommission($ref['sponsor_user_id'], $customer_id, 70, $ref['level'], $referral_bonus * 0.2, $data['qd_id']);
					}
					
					if($ref['level'] == 3) {
						$this->insertCommission($ref['sponsor_user_id'], $customer_id, 70, $ref['level'], $referral_bonus * 0.2, $data['qd_id']);
					}
				}
			}
			
		
			// =============

			// add notification
			$sql = "INSERT INTO oc_notifications
							SET title = 'Quick Delivery #".$this->db->escape($data['qd_id'])."'
							, 	user_id = ".$this->user->getId()."
							,	quick_deliveries_id = ".$this->db->escape($data['qd_id'])."
							,	description = '".$this->db->escape('Quick delivery #'.$data['qd_id'].' successfully finished.')."'
							,	date_added = '".$this->user->now()."'";

			$this->db->query($sql);

			return 'Quick delivery #'.$data['qd_id'].' successfully delivered.';
		}
	}
	
	public function insertCommission($user_id, $source_user_id, $com_type_id, $level, $amount, $id = 0) {
		
		$sql = "update oc_user set ewallet = ewallet + ".$this->db->escape($amount)." where user_id = ".$this->db->escape($user_id);
		$this->db->query($sql);
		
		$sql = "INSERT INTO oc_ewallet_hist
				SET user_id 					= ".$this->db->escape($user_id)."
				,	source_user_id			= ".$this->db->escape($source_user_id)."
				,	commission_type_id 	= ".$this->db->escape($com_type_id)."
				,	level 							= ".$this->db->escape($level)."
				,	quick_deliveries_id = ".$this->db->escape($id)."
				,	credit 							= ".$this->db->escape($amount)."
				,	date_added 					= '".$this->user->now()."'
				,	android_flag				= 0";
		
		$this->db->query($sql);	

		// add notification
		$sql = "INSERT INTO oc_notifications
						SET title = 'Earned ₱ ".$amount." referral bonus'
						, 	user_id = ".$user_id."
						,		quick_deliveries_id = ".$this->db->escape($id)."
						,		description = 'Received referral bonus from ".$this->getFullName($source_user_id)."'
						,		date_added = '".$this->user->now()."'";

		$this->db->query($sql);
	}	

	public function getFullName($user_id){
		$sql  = "SELECT CONCAT(firstname, ' ', middlename, ' ', lastname) full_name
								FROM oc_user
								WHERE user_id = ".$this->db->escape($user_id);

		return $this->db->query($sql)->row['full_name'];
	}
	
	public function bookDelivery($data){
		$result = array();
		$result['msg'] = "";
		
		$sql = "select current_qd_id 
				  from oc_user
				 where user_id = ".$this->user->getId();
		$query = $this->db->query($sql);
		$current_qd_id = $query->row['current_qd_id'];
		
		$sql = "select count(1) total
				  from oc_qd_location
				 where location_type = 'PICKUP' and quick_deliveries_id = ".$current_qd_id;
		$query = $this->db->query($sql);
		$pickup = $query->row['total'];
		
		$sql = "select count(1) total
				  from oc_qd_location
				 where location_type = 'DROPOFF' and quick_deliveries_id = ".$current_qd_id;
		$query = $this->db->query($sql);
		$dropoff = $query->row['total'];
		
		if ($pickup > 0 && $dropoff > 0) {
			if ($current_qd_id > 0) {
				$sql = "select special_flag, delivery_fee
						  from oc_quick_delivery
						 where quick_deliveries_id = ".$current_qd_id;
				$query = $this->db->query($sql);
				$special_flag = $query->row['special_flag'];
				$delivery_fee = $query->row['delivery_fee'];
				
				if ($special_flag == 2) { 
					if ($delivery_fee > 0) { 
						$sql = "update oc_quick_delivery set status = 138, rider_id = 0 where quick_deliveries_id = ".$current_qd_id;
						$this->db->query($sql);
						
						$sql = "update oc_user set current_qd_id = 0 where user_id = ".$this->user->getId();
						$this->db->query($sql);
						
						$result['success'] = 1;
						$result['msg'] .= "You successfully book your delivery!";
					} else {
						$result['success'] = 0;
						$result['msg'] .= "Error! Delivery Fee is not set!";
					}
				} else {
					$sql = "update oc_quick_delivery set status = 138, rider_id = 0 where quick_deliveries_id = ".$current_qd_id;
					$this->db->query($sql);
					
					$sql = "update oc_user set current_qd_id = 0 where user_id = ".$this->user->getId();
					$this->db->query($sql);
					
					$result['success'] = 1;
					$result['msg'] .= "You successfully book your delivery!";
				}
			} else {
				$result['success'] = 0;
				$result['msg'] .= "Book a delivery ID not found.";
			}
		} else {
			$result['success'] = 0;
			$result['msg'] .= "Error! You need to have atleast 1 pickup and 1 dropoff location to proceed.";	
		}
		// var_dump($result);
		return $result;
	}
	
	public function saveDeliveryFee($data){
		
		$sql = "select current_qd_id 
				  from oc_user
				 where user_id = ".$this->user->getId();
		$query = $this->db->query($sql);
		$current_qd_id = $query->row['current_qd_id'];
		
		$sql = "update oc_quick_delivery
				   set delivery_fee = ".$data['delivery_fee'].",
					   required_e_wallet = ".$data['delivery_fee']."
				 where quick_deliveries_id = ".$current_qd_id;
		$this->db->query($sql);
		
		return "Success! Delivery Fee has been Updated.";
	}
	
	public function removeItemSubmit($data){
		
		$return_array = array();
		$return_array['current_qd_id'] = $data['current_qd_id'];
		$return_array['qd_location_id'] = $data['qd_location_id'];
		$return_array['qd_location_msg'] = "";
		
		$sql = "delete from oc_qd_location_item 
				 where qd_location_item_id = ".$data['qd_location_item_id'];
		$this->db->query($sql);
			
		$this->computeTotals($data['current_qd_id']);
		$return_array['qd_location_msg'] = "You have successfully remove the item from the delivery.";

		return $return_array;
		
	}
	
	public function getMerchantItems($merchant_id) {
		$sql = "select item_id, srp, description
				  from oc_items 
				 where merchant_id = ".$merchant_id."
				   and raw = 0
				   and active = 1
				 order by description";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getItemInfo($merchant_id, $data) {
		$trimmed = str_replace('%20', ' ', $data['item_desc']); 
		
		$sql = "select srp
				  from oc_items 
				 where merchant_id = ".$merchant_id."
				   and description = '".$trimmed."'
				   and active = 1
				 order by description";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getMerchantInfo($data) {
		$sql = "select a.description customer_name, b.contact contactno,
					   b.address cust_address, b.brgy barangay, b.city_town, b.province new_province,
					   b.latitude, b.longitude, c.description barangay_desc, d.description city_desc, e.description province_desc
				  from oc_merchant a
				  join oc_user b on(a.user_id = b.user_id)
				  join oc_barangays c on(b.brgy = c.barangay_id)
				  join oc_city_municipality d on(b.city_town = d.city_municipality_id)
				  join oc_provinces e on(b.province = e.province_id)
				 where a.merchant_id = ".$data['merchant_id'];
		$query = $this->db->query($sql);
		return $query->row;
	}
	
}
?>