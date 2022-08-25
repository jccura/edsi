<?php
class ModelApiOrders extends Model {

	public function getOrders($data) {

		$return_array = array();
		$valid = 1;
		$return_msg = "";
		$count = 0;

		if(isset($data['user_id'])) {
			if(empty($data['user_id'])) {
				$valid = 0;
				$return_msg .= "User ID is required\n";
			} else {
				$sql = "SELECT COUNT(1) total, user_group_id FROM oc_user WHERE user_id = ".$this->db->escape($data['user_id']);
				$query = $this->db->query($sql);
				$total = $query->row['total'];
				$user_group_id = $query->row['user_group_id'];

				if($total == 0){
					$valid = 0;
					$return_msg .= "User ID doesn't exist\n";
				}
			}
		} else {
			$valid = 0;
			$return_msg .= "User ID is required\n";
		}

		if(isset($data['type'])) {
			if(empty($data['type'])) {
				$valid = 0;
				$return_msg .= "Type is required\n";
			}
		} else {
			$valid = 0;
			$return_msg .= "Type is required\n";
		}

		if($valid == 0){
			return 	Array( 	'status'			=> 200,
      						'valid' 			=> false,
      						'status_message'	=> trim($return_msg),
      						'data' 				=> [] );
		} else {

			$sql = "select a.order_id, a.receipt_number, a.date_added, a.status_id,
						   a.customer_name, a.address, h.description status, f.description mod_desc,
						   a.tracking, i.description moc, a.total, a.amount, e.description payment_option_desc,
						   a.packed_date, a.paid_date, a.actual_delivery_date, a.contact, a.email,
						   a.reference, a.receiver, a.notes, a.ref,  
						   concat(g.firstname, ' ', g.lastname, '(', g.username,')') reseller,
						   case when a.paid_flag = 1 then 'Paid' else 'Not Paid Yet' end paid_flag,
						   a.delivery_fee, a.discount, j.description send_to
					  from oc_order a
					  left join oc_provinces b on(a.province_id = b.province_id)
					  left join oc_city_municipality c on(a.city_municipality_id = c.city_municipality_id)
					  left join oc_barangays d on(a.barangay_id = d.barangay_id)
					  left join gui_status_tbl e on(a.payment_option = e.status_id)
					  left join gui_status_tbl f on(a.delivery_option = f.status_id)
					  left join oc_user g on(a.reseller_id = g.user_id) 
					  left join gui_status_tbl h on(a.status_id = h.status_id)
					  left join gui_status_tbl i on(a.mode_of_collection = i.status_id)
					  left join gui_status_tbl j on(a.send_to = j.status_id)
					 where 1 = 1 ";
			
			if($data['type'] == "cancelledorders") {
				$sql .= " and a.status_id in(19,113) ";
			} else if($data['type'] == "processedorders") {
				$sql .= " and a.status_id in(35,112) ";
			} else if($data['type'] == "orders") {
				if($user_group_id == 39 or $user_group_id == 13) {
					$sql .= " and a.status_id in(0,18,34) ";
				} else {
					$sql .= " and a.status_id in(18,34) ";
				}
			}
			
			if($user_group_id == 39 or $user_group_id == 13) {
				$sql .= " and a.user_id = ".$data['user_id'];
			}
				
			if($user_group_id == 12) {
				$sql .= " and a.status_id not in(0) ";
			}	
			
			if($user_group_id == 41) {
				$sql .= " and a.payment_option in(92,100) ";
			}
			
			if($user_group_id == 42) {
				$sql .= " and a.payment_option in(93,106) ";
			}
			
			if($user_group_id == 43) {
				$sql .= " and a.payment_option in(108,89,90,91,94,95,107) ";
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
			
			if(isset($data['mode_of_deliveries'])) {
				if(!empty($data['mode_of_deliveries'])) {
					$sql .= " and a.delivery_option = ".$data['mode_of_deliveries'];
					
				}
			}
			if(isset($data['trac_no'])) {
				if(!empty($data['trac_no'])) {
					$sql .= " and a.tracking = '".$this->db->escape($data['trac_no'])."'";
					
				}
			}
			
			if(isset($data['payment_option'])) {
				if(!empty($data['payment_option'])) {
					$sql .= " and a.payment_option = ".$data['payment_option'];
					
				}
			}		
			
			if(isset($data['order_id'])) {
				if(!empty($data['order_id'])) {
					if($data['order_id'] != "o") {
						$sql .= " and a.order_id in (".$this->db->escape(trim($data['order_id'],",")).")";
					}
				}
			}
			
			if(isset($data['status_id'])) {
				if(!empty($data['status_id'])) {
					$sql .= " and a.status_id in (".$this->db->escape($data['status_id']).")";
					
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

	 		return Array( 	'status'			=> 200,
	  						'valid' 			=> true,
	  						'status_message'	=> 'Orders successfully loaded!',
	  						'data'				=> Array('orders' => $newRows) );
		}
	}

}
?>