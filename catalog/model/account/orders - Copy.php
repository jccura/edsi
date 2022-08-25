<?php
class ModelAccountOrders extends Model {	
		
	public function getProducts() {
		$sql = "select item_id, description from gui_items_tbl where active = 1 ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getOrders($data, $query_type = "data") {
		
		$sql = "select a.order_id, a.receipt_number, a.date_added, a.status_id, a.shipped_date, a.payment_option,
					   a.customer_name, a.address, h.description status, f.description mod_desc,
					   a.tracking, i.description moc, a.total, a.amount, e.description payment_option_desc,
					   a.packed_date, a.paid_date, a.actual_delivery_date, a.contact, a.email,
					   a.reference, a.receiver, a.notes, a.ref, a.remarks,
					   concat(g.firstname, ' ', g.lastname, '(', g.username,')') reseller,
					   case when a.paid_flag = 1 then 'Paid' else 'Not Paid Yet' end paid_flag,
					   a.delivery_fee, a.discount, j.description send_to, a.delivery_option, 
					   k.username order_username
				  from oc_order a
				  left join oc_provinces b on(a.province_id = b.province_id)
				  left join oc_city_municipality c on(a.city_municipality_id = c.city_municipality_id)
				  left join oc_barangays d on(a.barangay_id = d.barangay_id)
				  left join gui_status_tbl e on(a.payment_option = e.status_id)
				  left join gui_status_tbl f on(a.delivery_option = f.status_id)
				  left join oc_user g on(a.user_id = g.user_id) 
				  left join gui_status_tbl h on(a.status_id = h.status_id)
				  left join gui_status_tbl i on(a.mode_of_collection = i.status_id)
				  left join gui_status_tbl j on(a.send_to = j.status_id)
				  left join oc_user k on(a.user_id = k.user_id)
				 where 1 = 1 ";
				 
		if($data['type'] == "cancelledorders") {
			if($this->user->getUserGroupId() == 12 || $this->user->getUserGroupId() == 54) {
				$sql .= " and a.status_id in(19,113) ";
			} elseif($this->user->getUserGroupId() == 39) {
				$sql .= " and a.status_id in(19,113)  and a.operator_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 45) {
				$sql .= " and a.status_id in(19,113) and a.user_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 46) {
				$sql .= " and a.status_id in(19,113) and a.user_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 47) {
				$sql .= " and a.status_id in(19,113) and a.user_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 56) {
				$sql .= " and a.status_id in(19,113) and a.user_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 57) {
				$sql .= " and a.status_id in(19,113) and a.user_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 41) {
				$sql .= " and a.status_id in(19,113) and a.delivery_option = 97 ";
			} elseif($this->user->getUserGroupId() == 49) {
				$sql .= " and a.status_id in(19,113) and a.delivery_option = 109 ";
			} else {
				$sql .= " and a.status_id in(19,113) and a.delivery_option = 96 ";
			}
		} else if($data['type'] == "processedorders") {
			if($this->user->getUserGroupId() == 12 || $this->user->getUserGroupId() == 54) {
				$sql .= " and a.status_id in(112,119,125) and a.paid_flag = 1 ";
			} elseif($this->user->getUserGroupId() == 39) {
				$sql .= " and a.status_id in(112,119,125) and  a.operator_id = ".$this->user->getId()." and a.paid_flag = 1 ";
			} elseif($this->user->getUserGroupId() == 45) {
				$sql .= " and a.status_id in(112,119,125) and a.sales_rep_id = ".$this->user->getId()." and a.paid_flag = 1 ";
			} elseif($this->user->getUserGroupId() == 46) {
				$sql .= " and a.status_id in(112,119,125) and a.company_admin_id = ".$this->user->getId()." and a.paid_flag = 1 ";
			} elseif($this->user->getUserGroupId() == 47) {
				$sql .= " and a.status_id in(112,119,125) and a.user_id = ".$this->user->getId()." and a.paid_flag = 1 ";
			} elseif($this->user->getUserGroupId() == 56) {
				$sql .= " and a.status_id in(112,119,125) and a.user_id = ".$this->user->getId()." and a.paid_flag = 1 ";
			} elseif($this->user->getUserGroupId() == 57) {
				$sql .= " and a.status_id in(112,119,125) and a.user_id = ".$this->user->getId()." and a.paid_flag = 1 ";
			} elseif($this->user->getUserGroupId() == 41) {
				$sql .= " and a.status_id in(112,119,125) and a.delivery_option = 97 and a.paid_flag = 1 ";
			} elseif($this->user->getUserGroupId() == 49) {
				$sql .= " and a.status_id in(112,119,125) and a.delivery_option = 109 and a.paid_flag = 1 ";
			} else {
				$sql .= " and a.status_id in(112,119,125) and a.delivery_option = 96 and a.paid_flag = 1 ";
			}
		} else if($data['type'] == "orders") {
			if($this->user->getUserGroupId() == 12 || $this->user->getUserGroupId() == 54) {
				$sql .= " and a.status_id in(0,18,34,35,117,118,112,123,124,126,127) ";
			} elseif($this->user->getUserGroupId() == 39) {
				$sql .= " and a.status_id in(0,18,34,35,117,118,112,123,124,126,127) and a.operator_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 45) {
				$sql .= " and a.status_id in(0,18,34,35,117,118,112,123,124,126,127) and a.user_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 46) {
				$sql .= " and a.status_id in(0,18,34,35,117,118,112,123,124,126,127)and a.user_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 47) {
				$sql .= " and a.status_id in(0,18,34,35,117,118,112,123,124,126,127) and a.user_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 56) {
				$sql .= " and a.status_id in(0,18,34,35,117,118,112,123,124,126,127) and a.user_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 57) {
				$sql .= " and a.status_id in(0,18,34,35,117,118,112,123,124,126,127) and a.user_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 41) {
				$sql .= " and a.status_id in(0,18,34,35,117,118,112,123,124,126,127) and a.delivery_option = 97 ";
			} elseif($this->user->getUserGroupId() == 49) {
				$sql .= " and a.status_id in(0,18,34,35,117,118,112,123,124,126,127) and a.delivery_option = 109 ";
			} else {
				$sql .= " and a.status_id in(0,18,34,35,117,118,112,123,124,126,127) and a.delivery_option = 96 ";
			}
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
		
		if(isset($data['payment_option'])) {
			if(!empty($data['payment_option'])) {
				$sql .= " and a.payment_option = ".$data['payment_option'];
				
			}
		}		
		
		if(isset($data['order_id'])) {
			if(!empty($data['order_id'])) {
				$sql .= " and a.order_id in (".$this->db->escape(trim($data['order_id'],",")).")";
				
			}
		}
		
		if(isset($data['status_id'])) {
			if(!empty($data['status_id'])) {
				$sql .= " and a.status_id in (".$this->db->escape($data['status_id']).")";
				
			}
		}
		
		if(isset($data['datecreatedfrom'])) {
			if (!empty($data['datecreatedfrom'])) {
				$date_from = $data['datecreatedfrom'] . " 00:00:00";
				$sql .= " and a.date_added >= '".$date_from."' ";
			} else {
				$date_from = "2018-01-01 00:00:00";
			}
		} else {
			$date_from = "2018-01-01 00:00:00";
		}
		
		if(isset($data['datecreatedto'])) {
			if (!empty($data['datecreatedto'])) {
				$date_to = $data['datecreatedto'] . " 23:59:59";
				$sql .= " and a.date_added <= '".$date_to."' ";
			} else {
				$date_to = $this->user->nowDate(). " 23:59:59";
			}
		} else {
			$date_to = $this->user->nowDate(). " 23:59:59";
		}
		
		if(isset($data['packeddatefrom'])) {
			if (!empty($data['packeddatefrom'])) {
				$packed_date_from = $data['packeddatefrom'] . " 00:00:00";
				$sql .= " and a.packed_date >= '".$packed_date_from."' ";
			} else {
				$packed_date_from = "0000-00-00 00:00:00";
			}
		} else {
			$packed_date_from = "0000-00-00 00:00:00";
		}
		
		if(isset($data['packeddateto'])) {
			if (!empty($data['packeddateto'])) {
				$packed_date_to = $data['packeddateto'] . " 23:59:59";
				$sql .= " and a.packed_date <= '".$packed_date_to."' ";
			} else {
				$packed_date_to = $this->user->nowDate(). " 23:59:59";
			}
		} else {
			$packed_date_to = $this->user->nowDate(). " 23:59:59";
		}	
		
		if(isset($data['datepaidfrom'])) {
			if (!empty($data['datepaidfrom'])) {
				$paid_date_from = $data['datepaidfrom'] . " 00:00:00";
				$sql .= " and a.paid_date >= '".$paid_date_from."' ";
			} else {
				$paid_date_from = "0000-00-00 00:00:00";
			}
		} else {
			$paid_date_from = "0000-00-00 00:00:00";
		}
		
		if(isset($data['datepaidto'])) {
			if (!empty($data['datepaidto'])) {
				$paid_date_to = $data['datepaidto'] . " 23:59:59";
				$sql .= " and a.paid_date <= '".$paid_date_to."' ";
			} else {
				$paid_date_to = $this->user->nowDate(). " 23:59:59";
			}
		} else {
			$paid_date_to = $this->user->nowDate(). " 23:59:59";
		}	
		
		if(isset($data['deliverydatefrom'])) {
			if (!empty($data['deliverydatefrom'])) {
				$delivery_date_from = $data['deliverydatefrom'] . " 00:00:00";
				$sql .= " and a.delivery_date >= '".$delivery_date_from."' ";
			} else {
				$delivery_date_from = "0000-00-00 00:00:00";
			}
		} else {
			$delivery_date_from = "0000-00-00 00:00:00";
		}
		
		if(isset($data['deliverydateto'])) {
			if (!empty($data['deliverydateto'])) {
				$delivery_date_to = $data['deliverydateto'] . " 23:59:59";
				$sql .= " and a.delivery_date <= '".$delivery_date_to."' ";
			} else {
				$delivery_date_to = $this->user->nowDate(). " 23:59:59";
			}
		} else {
			$delivery_date_to = $this->user->nowDate(). " 23:59:59";
		}	
		
		if(isset($data['paid_flag'])) {
			if($data['paid_flag'] != "") {
				$sql .= " and a.paid_flag in (".$this->db->escape($data['paid_flag']).")";				
			}
		}
		
		if(isset($data['tracking'])) {
			if($data['tracking'] != "") {
				$sql .= " and a.tracking in (".$this->db->escape($data['tracking']).")";				
			}
		}
				  
		if($query_type == "data") {
			// $sql .= " and a.date_added >= '".$date_from."'
					  // and a.date_added <= '".$date_to."' 
					  // or a.status_id in(0,18,34,35,112,117,118,123,124,126,127)
					  // and a.packed_date >= '".$packed_date_from."'
					  // and a.packed_date <= '".$packed_date_to."'
					  // or a.status_id in(0,18,34,35,112,117,118,123,124,126,127)
					  // and a.paid_date >= '".$paid_date_from."'
					  // and a.paid_date <= '".$paid_date_to."'
					  // or a.status_id in(0,18,34,35,112,117,118,123,124,126,127)
					  // and a.delivery_date >= '".$delivery_date_from."'
					  // and a.delivery_date <= '".$delivery_date_to."' ";
			$sql .= " order by a.order_id desc ";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 6;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			// echo $sql."<br>";
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
			return $newRows;
		} else {
			$sqlt = "select count(1) total from (".$sql.") t ";
			$query = $this->db->query($sqlt);
			return $query->row['total'];
		}
	}
	
	public function getStatusByGrouping($grouping, $grouping2 = "") {
		
		$sql = "select status_id, description 
				  from gui_status_tbl 
				 where `grouping` = '".$grouping."' ";
				 
		if($grouping2 != "") {
			$sql .= " and upper(grouping2) = '".strtoupper($grouping2)."' ";
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function updateOrder($data) {
		if($this->user->isLogged()) {
			$user_id = $this->user->getId();
		} else {
			$user_id = 0;
		}
		
		$sql = "select delivery_option, payment_option from oc_order where order_id = ".$data['order_id'];
		$query = $this->db->query($sql);
		$delivery_option = $query->row['delivery_option'];
		$payment_option = $query->row['payment_option'];
		
		if($this->user->getUserGroupId() == 12 || $this->user->getUserGroupId() == 54) {
			// if($delivery_option == 98 || $delivery_option == 97) {
			if($payment_option == 89 || $payment_option == 90 || $payment_option == 91) {
				$sql = "update oc_order 
						   set reference = '".$this->db->escape($data['ref'])."',
							   tracking = '".$this->db->escape($data['tracking'])."',
							   payment_uploaded_date = '".$this->user->now()."',
							   status_id = 123
						 where order_id = ".$data['order_id'];
				$this->db->query($sql);
			
				$sql = "select order_hist_id, to_status_id
						from oc_order_hist_tbl
					  where order_id = ".$data['order_id']."
					  order by order_hist_id desc limit 1";
				$query = $this->db->query($sql);
				$from_status_id = $query->row['to_status_id'];
				
				$order_id = $data['order_id'];
				
				$sql = "insert into oc_order_hist_tbl
							set user_id = ".$user_id.",
								order_id = ".$order_id.",
								from_status_id = ".$from_status_id.",
								to_status_id = 123,
								remarks = 'Payment Uploaded',
								date_added = '".$this->user->now()."' ";
				$this->db->query($sql);
			} else {
				$sql = "update oc_order 
						   set reference = '".$this->db->escape($data['ref'])."',
							   tracking = '".$this->db->escape($data['tracking'])."'
						 where order_id = ".$data['order_id'];
				$this->db->query($sql);
			}
		} else {
			if($payment_option == 89 || $payment_option == 90 || $payment_option == 91) {
				$sql = "update oc_order 
						   set reference = '".$this->db->escape($data['ref'])."',
							   tracking = '".$this->db->escape($data['tracking'])."',
							   payment_uploaded_date = '".$this->user->now()."',
							   status_id = 123
						 where order_id = ".$data['order_id'];
				$this->db->query($sql);
			
				$sql = "select order_hist_id, to_status_id
						from oc_order_hist_tbl
					  where order_id = ".$data['order_id']."
					  order by order_hist_id desc limit 1";
				$query = $this->db->query($sql);
				$from_status_id = $query->row['to_status_id'];
				
				$order_id = $data['order_id'];
				
				$sql = "insert into oc_order_hist_tbl
							set user_id = ".$user_id.",
								order_id = ".$order_id.",
								from_status_id = ".$from_status_id.",
								to_status_id = 123,
								remarks = 'Payment Uploaded',
								date_added = '".$this->user->now()."' ";
				$this->db->query($sql);
			} else {
				$sql = "update oc_order 
						   set reference = '".$this->db->escape($data['ref'])."',
							   tracking = '".$this->db->escape($data['tracking'])."'
						 where order_id = ".$data['order_id'];
				$this->db->query($sql);
			}

		}
		
		return "Order Id ".$data['order_id']." successfully saved.";
	}
	
	public function reuploadPayment($data) {
		if($this->user->isLogged()) {
			$user_id = $this->user->getId();
		} else {
			$user_id = 0;
		}
			
		if($this->user->getUserGroupId() == 12 || $this->user->getUserGroupId() == 54) {
			$sql = "update oc_order 
					   set payment_reupload_date = '".$this->user->now()."',
						   status_id = 124,
						   extension = ''
					 where order_id = ".$data['order_id'];
			$this->db->query($sql);
			$order_id = $data['order_id'];
			
			$sql = "insert into oc_order_hist_tbl
						set user_id = ".$user_id.",
							order_id = ".$order_id.",
							from_status_id = 123,
							to_status_id = 124,
							remarks = 'Payment Reupload',
							date_added = '".$this->user->now()."' ";
			$this->db->query($sql);
		} else {
			$sql = "update oc_order 
					   set reference = '".$this->db->escape($data['reference'])."',
						   payment_reupload_date = '".$this->user->now()."',
						   status_id = 124,
						   extension = ''
					 where order_id = ".$data['order_id'];
			$this->db->query($sql);
			
			$order_id = $data['order_id'];
			
			$sql = "insert into oc_order_hist_tbl
						set user_id = ".$user_id.",
							order_id = ".$order_id.",
							from_status_id = 123,
							to_status_id = 124,
							remarks = 'Payment Reupload',
							date_added = '".$this->user->now()."' ";
			$this->db->query($sql);

		}
		
		return "Order Id ".$data['order_id']." changed status successfully.";
	}

	public function getOrdersExportToCSV($data) {	
		
		$sql = "select a.order_id, c.description status, a.date_added, a.packed_date, a.paid_date,
					   case when a.paid_flag = 1 then 'Paid' else 'Not Paid' end paid_flag,
					   a.delivery_date, a.customer_name, 
					   concat(a.address, ', ', k.description, ', ', j.description, ', ', i.description) address,
					   a.contact, e.description mod_desc, a.tracking, h.description moc, 
					   m.item_code, m.item_name, l.quantity, n.description send_to, a.delivery_fee, a.discount, a.amount total,
					   f.username admin_name, d.description payment_option_desc, a.reference, a.receiver,
					   g.description page, a.notes 
				  from oc_order a
				  left join oc_user b on(a.user_id = b.user_id) 
				  left join gui_status_tbl c on(a.status_id = c.status_id)
				  left join gui_status_tbl d on(a.payment_option = d.status_id)
				  left join gui_status_tbl e on(a.delivery_option = e.status_id)
				  left join oc_user f on(a.reseller_id = f.user_id)
				  left join gui_status_tbl g on(a.page_id = g.status_id)
				  left join gui_status_tbl h on(a.mode_of_collection = h.status_id)
				  left join oc_provinces i on(a.province_id = i.province_id)
		          left join oc_city_municipality j on(a.city_municipality_id = j.city_municipality_id)
		          left join oc_barangays k on(a.barangay_id = k.barangay_id)
				  left join oc_order_details l on(a.order_id = l.order_id)
				  left join gui_items_tbl m on(l.item_id = m.item_id)
				  left join gui_status_tbl n on(a.send_to = n.status_id)
				 where a.status_id > 0 ";
		
		if($data['type'] == "cancelledorders") {
			$sql .= " and a.status_id in(19,113) ";
		} else if($data['type'] == "processedorders") {
			$sql .= " and a.status_id in(35,112) ";
		} else if($data['type'] == "orders") {
			$sql .= " and a.status_id in(18,34) ";
		}
		
		if($this->user->getUserGroupId() == 39 or $this->user->getUserGroupId() == 13) {
			$sql .= " and a.user_id = ".$this->user->getId();
		}
		
		if($this->user->getUserGroupId() == 12) {
			$sql .= " and a.status_id not in(0,19) ";
		}

		if($this->user->getUserGroupId() == 41) {
			$sql .= " and a.payment_option in(92,100) ";
		}
		
		if($this->user->getUserGroupId() == 42) {
			$sql .= " and a.payment_option in(93,106) ";
		}
		
		if($this->user->getUserGroupId() == 43) {
			$sql .= " and a.payment_option in(108,89,90,91,94,95,107) ";
		}
		
		if(isset($data['order_id'])) {
			if(!empty($data['order_id'])) {
				$sql .= " and a.order_id in (".$this->db->escape(trim($data['order_id'],",")).")";
				
			}
		}
		
		if(isset($data['admin_id'])) {
			if(!empty($data['admin_id'])) {
				$sql .= " and a.user_id in (".$this->db->escape($data['admin_id']).")";
				
			}
		}
		
		if(isset($data['status_id'])) {
			if(!empty($data['status_id'])) {
				$sql .= " and a.status_id in (".$this->db->escape($data['status_id']).")";
				
			}
		}
		
		if(isset($data['paid_flag'])) {
			if($data['paid_flag'] != "") {
				$sql .= " and a.paid_flag in (".$this->db->escape($data['paid_flag']).")";				
			}
		}
		
		if(isset($data['page_id'])) {
			if(!empty($data['page_id'])) {
				$sql .= " and a.page_id in (".$this->db->escape($data['page_id']).")";
				
			}
		}
		
		if(isset($data['mode_of_deliveries'])) {
			if(!empty($data['mode_of_deliveries'])) {
				$sql .= " and a.delivery_option in (".$this->db->escape($data['mode_of_deliveries']).")";
				
			}
		}
		
		if(isset($data['payment_option'])) {
			if(!empty($data['payment_option'])) {
				$sql .= " and a.payment_option in (".$this->db->escape($data['payment_option']).")";
				
			}
		}
		
		if(isset($data['mode_of_collection'])) {
			if(!empty($data['mode_of_collection'])) {
				$sql .= " and a.mode_of_collection in (".$this->db->escape($data['mode_of_collection']).")";
				
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
		
		return $query->rows;
	}	
	
	public function updateFileExtension($order_id, $ext) {
		$sql = "update oc_order set extension = '".$ext."' where order_id = ".$order_id;
		$this->db->query($sql);
	}
	
	public function tagOrdersAsPaid($data) {
		// var_dump($data);
		$return_msg = "";
		$valid = 1;
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = " select a.order_id, a.user_id, a.operator_id, a.sales_rep_id, a.company_admin_id, 
							a.reseller_id, a.uni_comp_id, a.paid_flag, a.status_id, a.payment_option, c.user_group_id,
							a.username, a.password, a.firstname, a.lastname, b.item_id
							from oc_order a
							LEFT JOIN oc_order_details b
							ON (a.order_id = b.order_id)
							LEFT JOIN gui_items_tbl c
							ON (b.item_id = c.item_id)
							where b.order_id = ".$order_id;
				$query = $this->db->query($sql);
				$order = $query->row;
				
				if($order['paid_flag'] == 0) {
					$payment_option = $order['payment_option'];
					$status_id = $order['status_id'];
					if($payment_option == 90 || $payment_option == 91 || $payment_option == 100 || $payment_option == 106 || $payment_option == 107 || $payment_option == 108) {
						if($status_id == 35) {
							$this->insertNewUser($order['user_group_id'],$this->db->escape($order['username']),$this->db->escape(md5($order['password'])),$this->db->escape($order['firstname']),$this->db->escape($order['lastname']),$order['operator_id'],$order['user_id']);
							$this->distributeCommission($order);
							$this->performStatusUpdate($order, 119, 1);
							$return_msg .= "Order Id ".$order_id." tagged as paid.<br>";
						} elseif($status_id == 18) {
							$return_msg .= "Order Id ".$order_id." must be packed first.<br>";
						} elseif($status_id == 0) {
							$return_msg .= "Order Id ".$order_id." must be Checkout first.<br>";
						} else {
							$return_msg .= "Order Id ".$order_id." must be pickedup first.<br>";
						}
					}
					
					if($payment_option == 89 || $payment_option == 92 || $payment_option == 93 || $payment_option == 94 || $payment_option == 95) {
						if($status_id == 112) {
							$this->insertNewUser($order['user_group_id'],$this->db->escape($order['username']),$this->db->escape(md5($order['password'])),$this->db->escape($order['firstname']),$this->db->escape($order['lastname']),$order['operator_id'],$order['user_id']);
							$this->distributeCommission($order);
							$this->performStatusUpdate($order, 125, 1);
							$return_msg .= "Order Id ".$order_id." tagged as paid.<br>";
						} elseif($status_id == 18) {
							$return_msg .= "Order Id ".$order_id." must be packed first.<br>";
						} elseif($status_id == 0) {
							$return_msg .= "Order Id ".$order_id." must be Checkout first.<br>";
						} else {
							$return_msg .= "Order Id ".$order_id." must be delivered first.<br>";
						}
					}
				}
			}
		} else {
			$return_msg .= "No order selected to be tagged as paid.";
		}
		return $return_msg;
	}
	
	public function tagOrdersAsPaidApi($data) {
		$return_msg = "";
		$valid = 1;
		$order_id = $data['order_id'];
		$status_id = $data['status_id'];
		
		$sql = "select a.paid_flag, b.grouping2, a.operator_id, a.user_group_id,
						a.username, a.password, a.firstname, a.lastname, a.user_id
				  from oc_order a 
				  left join gui_status_tbl b on (a.payment_option = b.status_id)
				 where order_id = ".$order_id;
		$query = $this->db->query($sql);
		$paid_flag = $query->row['paid_flag'];
		$payment_option = $query->row['grouping2'];
		$order = $query->row;
				
		if($paid_flag == 0) {
			
			$sql = "update oc_order	
					   set paid_flag = 1
						  ,status_id = ".$status_id."
						  ,paid_date = '".$this->user->now()."'
					 where order_id = ".$order_id;
			$this->db->query($sql);
			
			$sql = "select user_id, operator_id, sales_rep_id, company_admin_id, 
						reseller_id, uni_comp_id
					  from oc_order
					 where order_id = ".$order_id;
			$query = $this->db->query($sql);
			$user_id = $query->row['user_id'];
			$operator_id = $query->row['operator_id'];
			$sales_rep_id = $query->row['sales_rep_id'];
			$reseller_id = $query->row['reseller_id'];
			$uni_comp_id = $query->row['uni_comp_id'];
			
			$sql = "select item_id
						from oc_order_details
					where order_id = ".$order_id;
			$query = $this->db->query($sql);
			$item_ids = $query->rows;
			
			$sql = "select cost
						from oc_order_details
					where order_id = ".$order_id;
			$query = $this->db->query($sql);
			$cost = $query->rows;
			
			$sql = "select item_id,item_name,unilevel1,rebates,points,epoints,
						eseller_income,sales_rep_income,operator_income,
						performance_points,discount_per_item
					from gui_items_tbl ";
			$query = $this->db->query($sql);
			$unilevel1 = $query->row['unilevel1'];
			$eseller_income = $query->row['eseller_income'];
			$sales_rep_income = $query->row['sales_rep_income'];
			$operator_income = $query->row['operator_income'];
			
			if($sales_rep_id > 0) {
		
				foreach($item_ids as $a) {
					$commission_type_id = 23;
					$sql = "select * from oc_commission_type where commission_type_id = ".$commission_type_id;
					$query = $this->db->query($sql);
					$commission_type_id = $query->row['commission_type_id'];
					$description = $query->row['description'];
					
					$sql = "select sales_rep_income 
								from gui_items_tbl
						  where item_id = ".$a['item_id'];
					$query = $this->db->query($sql);
					$sales_rep_income = $query->row['sales_rep_income'];
					
					$sql = "select quantity
								from oc_order_details
							where order_id = ".$order_id." and item_id = ".$a['item_id'];
					$query = $this->db->query($sql);
					$quantity = $query->row['quantity'];
					
					$sales_rep_income = $sales_rep_income * $quantity;
						
					$sql = "update oc_user set ewallet = ewallet + ".$sales_rep_income."
							 where user_id = ".$sales_rep_id;
					$query = $this->db->query($sql);
					
					$sql = "insert into oc_ewallet_hist 
								set user_id = ".$sales_rep_id.",
									order_id = ".$order_id.",
									source_user_id = ".$user_id.",
									commission_type_id = ".$commission_type_id.",
									credit = ".$sales_rep_income.",
									remarks = '".$description."',
									created_by = 8368,
									date_added = '".$this->user->now()."' ";
					$query = $this->db->query($sql);
					
					if($operator_id > 0) {
						$commission_type_id = 22;
						$sql = "select * from oc_commission_type where commission_type_id = ".$commission_type_id;
						$query = $this->db->query($sql);
						$commission_type_id = $query->row['commission_type_id'];
						$description = $query->row['description'];
						
						$sql = "select operator_income
									from gui_items_tbl
							  where item_id = ".$a['item_id'];
						$query = $this->db->query($sql);
						$operator_income = $query->row['operator_income'];
						
						$operator_income = $operator_income - $sales_rep_income;
						
						$sql = "select quantity
									from oc_order_details
								where order_id = ".$order_id." and item_id = ".$a['item_id'];
						$query = $this->db->query($sql);
						$quantity = $query->row['quantity'];
								
						$operator_income = $operator_income * $quantity;
						
						$sql = "update oc_user set ewallet = ewallet + ".$operator_income."
								 where user_id = ".$operator_id;
						$query = $this->db->query($sql);
						
						$sql = "insert into oc_ewallet_hist 
									set user_id = ".$operator_id.",
										order_id = ".$order_id.",
										source_user_id = ".$user_id.",
										commission_type_id = ".$commission_type_id.",
										credit = ".$operator_income.",
										remarks = '".$description."',
										created_by = 8368,
										date_added = '".$this->user->now()."' ";
						$query = $this->db->query($sql);
						$valid = 0;
					}
				}
			}
			
			if($reseller_id > 0) {
				
				foreach($item_ids as $a) {
					$commission_type_id = 24;
					$sql = "select * from oc_commission_type where commission_type_id = ".$commission_type_id;
					$query = $this->db->query($sql);
					$commission_type_id = $query->row['commission_type_id'];
					$description = $query->row['description'];
					
					$sql = "select eseller_income 
								from gui_items_tbl
						  where item_id = ".$a['item_id'];
					$query = $this->db->query($sql);
					$eseller_income = $query->row['eseller_income'];
					
					$sql = "select quantity
								from oc_order_details
							where order_id = ".$order_id." and item_id = ".$a['item_id'];
					$query = $this->db->query($sql);
					$quantity = $query->row['quantity'];
					
					$eseller_income = $eseller_income * $quantity;
						
					$sql = "update oc_user set ewallet = ewallet + ".$eseller_income."
							 where user_id = ".$reseller_id;
					$query = $this->db->query($sql);
					
					$sql = "insert into oc_ewallet_hist 
								set user_id = ".$reseller_id.",
									order_id = ".$order_id.",
									source_user_id = ".$user_id.",
									commission_type_id = ".$commission_type_id.",
									credit = ".$eseller_income.",
									remarks = '".$description."',
									created_by = 8368,
									date_added = '".$this->user->now()."' ";
					$query = $this->db->query($sql);
					$this->insertNewUser($order['user_group_id'],$this->db->escape($order['username']),$this->db->escape(md5($order['password'])),$this->db->escape($order['firstname']),$this->db->escape($order['lastname']),$order['operator_id'],$order['user_id']);
					
					if($operator_id > 0) {
						$sql = "select operator_income
									from gui_items_tbl
							  where item_id = ".$a['item_id'];
						$query = $this->db->query($sql);
						$operator_income = $query->row['operator_income'];
						
						$operator_income = $operator_income - $eseller_income;
						
						$sql = "select quantity
									from oc_order_details
								where order_id = ".$order_id." and item_id = ".$a['item_id'];
						$query = $this->db->query($sql);
						$quantity = $query->row['quantity'];
						
						$operator_income = $operator_income * $quantity;
						
						$sql = "update oc_user set ewallet = ewallet + ".$operator_income."
								 where user_id = ".$operator_id;
						$query = $this->db->query($sql);
						
						$sql = "insert into oc_ewallet_hist 
									set user_id = ".$operator_id.",
										order_id = ".$order_id.",
										source_user_id = ".$user_id.",
										commission_type_id = ".$commission_type_id.",
										credit = ".$operator_income.",
										remarks = '".$description."',
										created_by = 8368,
										date_added = '".$this->user->now()."' ";
						$query = $this->db->query($sql);
						$this->insertNewUser($order['user_group_id'],$this->db->escape($order['username']),$this->db->escape(md5($order['password'])),$this->db->escape($order['firstname']),$this->db->escape($order['lastname']),$order['operator_id'],$order['user_id']);
						$valid = 0;
					}
				}
			}
			
			if($valid == 1) {
				if($operator_id > 0) {
					
					$commission_type_id = 22;
					$sql = "select * from oc_commission_type where commission_type_id = ".$commission_type_id;
					$query = $this->db->query($sql);
					$commission_type_id = $query->row['commission_type_id'];
					$description = $query->row['description'];
					
					foreach($item_ids as $a) {
						$sql = "select operator_income
									from gui_items_tbl
							  where item_id = ".$a['item_id'];
						$query = $this->db->query($sql);
						$operator_income = $query->row['operator_income'];
						
						$sql = "select quantity
									from oc_order_details
								where order_id = ".$order_id." and item_id = ".$a['item_id'];
						$query = $this->db->query($sql);
						$quantity = $query->row['quantity'];
						
						$operator_income = $operator_income * $quantity;
						
						$sql = "update oc_user set ewallet = ewallet + ".$operator_income."
								 where user_id = ".$operator_id;
						$query = $this->db->query($sql);
						
						$sql = "insert into oc_ewallet_hist 
									set user_id = ".$operator_id.",
										order_id = ".$order_id.",
										source_user_id = ".$user_id.",
										commission_type_id = ".$commission_type_id.",
										credit = ".$operator_income." * ".$quantity.",
										remarks = '".$description."',
										created_by = 8368,
										date_added = '".$this->user->now()."' ";
						$query = $this->db->query($sql);
						$this->insertNewUser($order['user_group_id'],$this->db->escape($order['username']),$this->db->escape(md5($order['password'])),$this->db->escape($order['firstname']),$this->db->escape($order['lastname']),$order['operator_id'],$order['user_id']);
					}
				}
			}
			
			$return_msg .= "Order Id ".$order_id." is successfully tagged as paid.<br>";
		} else {
		$return_msg .= "Order Id ".$order_id." is already paid.<br>";
		}
		// return $return_msg;
	}
	
	public function paymentConfirmed($data){
		$sql = "update oc_order
					set status_id = 125,
						payment_confirmed_date = '".$this->user->now()."'
				  where order_id = ".$data['order_id'];
		$query = $this->db->query($sql);
		
		$sql = "select order_hist_id, to_status_id
					from oc_order_hist_tbl
				  where order_id = ".$data['order_id']."
				  order by order_hist_id desc limit 1";
		$query = $this->db->query($sql);
		$from_status_id = $query->row['to_status_id'];
		
		$order_id = $data['order_id'];
			
		if($this->user->isLogged()) {
			$user_id = $this->user->getId();
		} else {
			$user_id = 0;
		}
		
		$sql = "insert into oc_order_hist_tbl
					set user_id = ".$user_id.",
						order_id = ".$order_id.",
						from_status_id = ".$from_status_id.",
						to_status_id = 125,
						remarks = 'Payment Confirmed',
						date_added = '".$this->user->now()."' ";
		$this->db->query($sql);
		
		return "Order Id ".$data['order_id']." payment confirmed.<br>";
	}
	
	public function tagOrdersAsPacked($data) {
		$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select a.status_id, a.branch_id, b.grouping2, a.paid_flag, a.payment_option
							from oc_order a
							left join gui_status_tbl b on (a.payment_option = b.status_id) 
						where a.order_id = ".$order_id;
				$query = $this->db->query($sql);
				$status_id = $query->row['status_id'];
				$branch_id = $query->row['branch_id'];
				$payment_option = $query->row['payment_option'];
				$paid_flag = $query->row['paid_flag'];
				
				// if($status_id == 18 && $payment_option == "DROPSHIP" && $paid_flag == 1) {
				if($payment_option == 90 || $payment_option == 91 || $payment_option == 100 || $payment_option == 106
				|| $payment_option == 107 || $payment_option == 108 || $payment_option == 89 || $payment_option == 92
				|| $payment_option == 93 || $payment_option == 94 || $payment_option == 95) {
					
					if($status_id == 18) {
						
						$valid = $this->assembleItem($order_id, 34);
												
						if($valid == 1) {					
							//Update order status,
							$sql = "update oc_order	
									   set status_id = 34 
										  ,inventoriable = 1
										  ,packed_date = '".$this->user->now()."'
									 where order_id = ".$order_id;
							$this->db->query($sql);	

							$sql = "select order_hist_id, to_status_id
									from oc_order_hist_tbl
								  where order_id = ".$order_id."
								  order by order_hist_id desc limit 1";
							$query = $this->db->query($sql);
							$from_status_id = $query->row['to_status_id'];
							// $order_id = $data['order_id'];
							
							if($this->user->isLogged()) {
								$user_id = $this->user->getId();
							} else {
								$user_id = 0;
							}
							
							$sql = "insert into oc_order_hist_tbl
										set user_id = ".$user_id.",
											order_id = ".$order_id.",
											from_status_id = ".$from_status_id.",
											to_status_id = 34,
											remarks = 'Packed',
											date_added = '".$this->user->now()."' ";
							$this->db->query($sql);
							
							$return_msg .= "Order Id ".$order_id." is successfully processed.<br>";
						} else {
							$return_msg .= "Order Id ".$order_id." cannot be proccessed due to insufficient inventory.<br>";
						}
					} elseif($status_id == 0) {
						$return_msg .= "Order Id ".$order_id." must be Checkout first.<br>";
					} else {
						$return_msg .= "Order Id ".$order_id." is already packed.<br>";
					}
				} else {
					$return_msg .= "Order Id ".$order_id." packed status is not applicable for COP.<br>";
				}
			}
		} else {
			$return_msg .= "No order selected to pack.";
		}
		return $return_msg;
	}
	
	public function tagOrdersAsShipped($data) {
		$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select a.status_id, a.branch_id, b.grouping2, a.paid_flag, a.payment_option
							from oc_order a
							left join gui_status_tbl b on (a.payment_option = b.status_id) 
						where a.order_id = ".$order_id;
				$query = $this->db->query($sql);
				$status_id = $query->row['status_id'];
				$branch_id = $query->row['branch_id'];
				$payment_option = $query->row['payment_option'];
				$paid_flag = $query->row['paid_flag'];
				
				if($payment_option == 90 || $payment_option == 91 || $payment_option == 94 || $payment_option == 100 || $payment_option == 106 || $payment_option == 107 || $payment_option == 108) {
					
					if($status_id == 34) {
													
						$sql = "update oc_order	
								   set status_id = 126
									  ,shipped_date = '".$this->user->now()."'
								 where order_id = ".$order_id;
						$this->db->query($sql);
						
						if(isset($data['ship_tracking_header'])){
							foreach($data['ship_tracking_header'] as $key => $order_ids){
								$sql = "UPDATE oc_order SET tracking = '" . $this->db->escape($data['ship_tracking_input'][$key]) . "' WHERE order_id = " . $order_ids;
								$this->db->query($sql);
							}
						}
						
						$sql = "select order_hist_id, to_status_id
									from oc_order_hist_tbl
								  where order_id = ".$order_id."
								  order by order_hist_id desc limit 1";
						$query = $this->db->query($sql);
						$from_status_id = $query->row['to_status_id'];
						// $order_id = $data['order_id'];
						
						if($this->user->isLogged()) {
							$user_id = $this->user->getId();
						} else {
							$user_id = 0;
						}
						
						$sql = "insert into oc_order_hist_tbl
									set user_id = ".$user_id.",
										order_id = ".$order_id.",
										from_status_id = ".$from_status_id.",
										to_status_id = 126,
										remarks = 'Shipped',
										date_added = '".$this->user->now()."' ";
						$this->db->query($sql);
						
						$return_msg .= "Order Id ".$order_id." is successfully tagged as shipped.<br>";
						
					} elseif($status_id == 18) {
						$return_msg .= "Order Id ".$order_id." must be packed first.<br>";
					} elseif($status_id == 0) {
						$return_msg .= "Order Id ".$order_id." must be Checkout first.<br>";
					} else {
						$return_msg .= "Order Id ".$order_id." is already shipped.<br>";
					}
				} else {
					$return_msg .= "Order Id ".$order_id." shipped status is not applicable for COP.<br>";
				}
			}
		} else {
			$return_msg .= "No order selected to pack.";
		}
		// echo $data['tracking'].$order_id;
		return $return_msg;
	}
	
	public function tagOrdersAsCancelled($data) {
		$order_result = array();
		$return_msg = "";
		$valid = 0;
		
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, paid_flag, branch_id, inventoriable, payment_option
						from oc_order 
					  where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$status_id = $query->row['status_id'];
				$paid_flag = $query->row['paid_flag'];
				$branch_id = $query->row['branch_id'];
				$inventoriable = $query->row['inventoriable'];
				$payment_option = $query->row['payment_option'];
				
				if(($status_id == 18 or $status_id == 34) and ($paid_flag == 0)) {
					
					$sql = "update oc_order	
							   set status_id = 113 
								  ,cancel_date = '".$this->user->now()."'
						     where order_id = ".$order_id;
					$this->db->query($sql);
					
					$valid  = 1;
					$return_msg .= "Order ID ".$order_id." is cancelled.<br>";
					
					if($status_id == 34 and $inventoriable == 1) {
						$sql = "select item_id, quantity qty
								  from oc_order_details
								 where order_id = ".$order_id;
						$query = $this->db->query($sql);
						$order_items = $query->rows;
						
						foreach($order_items as $detail) {
							$sql = "UPDATE oc_inventory
								   set qty = qty + ".$detail['qty'].",
									   modified_by='".$this->user->getId()."',
									   date_added='".$this->user->now()."'
								 where branch_id = ".$branch_id."
								   and item_id = ".$detail['item_id'];
								   
							$this->db->query($sql);				
							$inventoryId = $this->db->getLastId();
							
							$sql = "INSERT INTO oc_inventory_history_tbl
										SET user_id = '".$this->user->getId()."',
											item_id = ".$detail['item_id'].",
											date_added = '".$this->user->now()."',
											sold = 0,
											re_stock = ".$detail['qty'].",
											branch_id = ".$branch_id.",
											inventory_id = ".$inventoryId.",
											status = 113, 
											remarks = 'CANCELLED',
											order_id = ".$order_id;
							$this->db->query($sql);	
						}
					}
					$sql = "select order_hist_id, to_status_id
								from oc_order_hist_tbl
							  where order_id = ".$order_id."
							  order by order_hist_id desc limit 1";
					$query = $this->db->query($sql);
					$from_status_id = $query->row['to_status_id'];
					
					if($this->user->isLogged()) {
						$user_id = $this->user->getId();
					} else {
						$user_id = 0;
					}
					
					$sql = "insert into oc_order_hist_tbl
								set user_id = ".$user_id.",
									order_id = ".$order_id.",
									from_status_id = ".$from_status_id.",
									to_status_id = 113,
									remarks = 'Cancelled',
									date_added = '".$this->user->now()."' ";
					$this->db->query($sql);
				} else {
					$return_msg .= "Cancellation of order id ".$order_id." failed. Only unpaid orders in the status of Checkout or Packed orders can be cancelled.<br>";
				}
				$order_result['payment_option'] = $payment_option;
				$order_result['order_id'] = $order_id;
				$order_result['valid'] = $valid;
				$order_result['msg'] = $return_msg;
			}
			// return $return_msg;
			return $order_result;
		} else {
			$order_result['msg'] = "No orders selected to cancel.<br>";
			return $order_result;
		}
	}
	
	public function tagOrdersAsReturned($data) {
		$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, paid_flag, branch_id, inventoriable from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$status_id = $query->row['status_id'];
				$paid_flag = $query->row['paid_flag'];
				$branch_id = $query->row['branch_id'];
				$inventoriable = $query->row['inventoriable'];
				if($status_id == 34) {
					$sql = "update oc_order	
							   set status_id = 118 
								  ,cancel_date = '".$this->user->now()."'
						     where order_id = ".$order_id;
					$this->db->query($sql);
					
					if($inventoriable == 1) {
						$sql = "select item_id, quantity qty
								  from oc_order_details
								 where order_id = ".$order_id;
						$query = $this->db->query($sql);
						$order_items = $query->rows;
						
						foreach($order_items as $detail) {
							$sql = "UPDATE oc_inventory
								   set qty = qty + ".$detail['qty'].",
									   modified_by='".$this->user->getId()."',
									   date_added='".$this->user->now()."'
								 where branch_id = ".$branch_id."
								   and item_id = ".$detail['item_id'];
								   
							$this->db->query($sql);				
							$inventoryId = $this->db->getLastId();
							
							$sql = "INSERT INTO oc_inventory_history_tbl
										SET user_id = '".$this->user->getId()."',
											item_id = ".$detail['item_id'].",
											date_added = '".$this->user->now()."',
											sold = 0,
											re_stock = ".$detail['qty'].",
											branch_id = ".$branch_id.",
											inventory_id = ".$inventoryId.",
											status = 18, 
											remarks = 'RETURNED',
											order_id = ".$order_id;
							$this->db->query($sql);	
						}
					}
					$sql = "select order_hist_id, to_status_id
								from oc_order_hist_tbl
							  where order_id = ".$order_id."
							  order by order_hist_id desc limit 1";
					$query = $this->db->query($sql);
					$from_status_id = $query->row['to_status_id'];
					// $order_id = $data['order_id'];
					
					if($this->user->isLogged()) {
						$user_id = $this->user->getId();
					} else {
						$user_id = 0;
					}
					
					$sql = "insert into oc_order_hist_tbl
								set user_id = ".$user_id.",
									order_id = ".$order_id.",
									from_status_id = ".$from_status_id.",
									to_status_id = 118,
									remarks = 'Returned',
									date_added = '".$this->user->now()."' ";
					$this->db->query($sql);
					
					$return_msg .= "Order ID ".$order_id." is returned.<br>";
				} elseif($status_id == 0) {
						$return_msg .= "Order Id ".$order_id." must be Checkout first.<br>";
				} else {
					$return_msg .= "Return of order id ".$order_id." failed. Only Packed orders can be returned.<br>";
				}
			}
			return $return_msg;
		} else {
			return "No orders selected to return.<br>";
		}
	}
	
	public function tagOrderAsCancelledByCustomer($data) {
		$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, paid_flag from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$status_id = $query->row['status_id'];
				$paid_flag = $query->row['paid_flag'];
				if(($status_id == 18 or $status_id == 34) and ($paid_flag == 0)) {
					$sql = "update oc_order	
							   set status_id = 19 
								  ,cancel_date = '".$this->user->now()."'
						     where order_id = ".$order_id;
					$this->db->query($sql);
					
					$sql = "select order_hist_id, to_status_id
								from oc_order_hist_tbl
							  where order_id = ".$order_id."
							  order by order_hist_id desc limit 1";
					$query = $this->db->query($sql);
					$from_status_id = $query->row['to_status_id'];
					// $order_id = $data['order_id'];
					
					if($this->user->isLogged()) {
						$user_id = $this->user->getId();
					} else {
						$user_id = 0;
					}
					
					$sql = "insert into oc_order_hist_tbl
								set user_id = ".$user_id.",
									order_id = ".$order_id.",
									from_status_id = ".$from_status_id.",
									to_status_id = 19,
									remarks = 'Cancelled by Customer',
									date_added = '".$this->user->now()."' ";
					$this->db->query($sql);
					
					$return_msg .= "Order ID ".$order_id." is cancelled.<br>";
				} else {
					$return_msg .= "Cancellation of order id ".$order_id." failed. Only unpaid orders in the status of Checkout or Packed orders can be cancelled.<br>";
				}
			}
			return $return_msg;
		} else {
			return "No orders selected to cancel.";
		}
	}
	
	public function tagOrderAsPickedUp($data) {
		$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, user_id, reseller_id, firstname, lastname, 
							   paid_flag, username, password, contact, email, address,
							   province_id, city_municipality_id, barangay_id, send_to, delivery_option, payment_option
						  from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$order = $query->row;
				$status_id = $query->row['status_id'];
				$user_id = $query->row['user_id'];
				$reseller_id = $query->row['reseller_id'];
				$paid_flag = $query->row['paid_flag'];
				$delivery_option = $query->row['delivery_option'];
				$payment_option = $query->row['payment_option'];
				
				if($payment_option == 89 || $payment_option == 93 || $payment_option == 94 || $payment_option == 95) {
					// if($paid_flag == 1){
					if($status_id == 126){
						$sql = "update oc_order	
								   set status_id = 112
									  ,pickedup_date = '".$this->user->now()."'
								 where order_id = ".$order_id;
						$this->db->query($sql);
						
						$sql = "select order_hist_id, to_status_id
									from oc_order_hist_tbl
								  where order_id = ".$order_id."
								  order by order_hist_id desc limit 1";
						$query = $this->db->query($sql);
						$from_status_id = $query->row['to_status_id'];
						// $order_id = $data['order_id'];
						
						if($this->user->isLogged()) {
							$user_id = $this->user->getId();
						} else {
							$user_id = 0;
						}
						
						$sql = "insert into oc_order_hist_tbl
									set user_id = ".$user_id.",
										order_id = ".$order_id.",
										from_status_id = ".$from_status_id.",
										to_status_id = 112,
										remarks = 'Picked-Up',
										date_added = '".$this->user->now()."' ";
						$this->db->query($sql);
						
						$return_msg .= "Order Id ".$order_id." is tagged as Pickedup.";
					} elseif($status_id == 112) {
						$return_msg .= "Order Id ".$order_id." is already Pickedup.";
					} else {
						$return_msg .= "Order Id ".$order_id." must be shipped first.";
					}
				} elseif($payment_option == 92) {
					if($status_id == 34){
						$sql = "update oc_order	
								   set status_id = 112
									  ,pickedup_date = '".$this->user->now()."'
								 where order_id = ".$order_id;
						$this->db->query($sql);
						
						$sql = "select order_hist_id, to_status_id
									from oc_order_hist_tbl
								  where order_id = ".$order_id."
								  order by order_hist_id desc limit 1";
						$query = $this->db->query($sql);
						$from_status_id = $query->row['to_status_id'];
						// $order_id = $data['order_id'];
						
						if($this->user->isLogged()) {
							$user_id = $this->user->getId();
						} else {
							$user_id = 0;
						}
						
						$sql = "insert into oc_order_hist_tbl
									set user_id = ".$user_id.",
										order_id = ".$order_id.",
										from_status_id = ".$from_status_id.",
										to_status_id = 112,
										remarks = 'Picked-Up',
										date_added = '".$this->user->now()."' ";
						$this->db->query($sql);
						
						$return_msg .= "Order Id ".$order_id." is tagged as Pickedup.";
					} elseif($status_id == 112) {
						$return_msg .= "Order Id ".$order_id." is already Pickedup.";
					} else {
						$return_msg .= "Order Id ".$order_id." must be shipped first.";
					}
				} else {
					$return_msg .= "Order Id ".$order_id." picked up status is not applicable for COD.";
				}
			}
		} else {
			$return_msg .= "No order selected to pick up.";
		}
		return $return_msg;
	}
	
	public function tagOrdersAsDelivered($data) {
		$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select a.status_id, a.user_id, a.reseller_id, a.firstname, a.lastname, 
							a.paid_flag, a.username, a.password, a.contact, a.email, a.address,
							a.province_id, a.city_municipality_id, a.barangay_id, a.send_to, b.grouping2, a.payment_option
							from oc_order a
							left join gui_status_tbl b on (a.payment_option = b.status_id) 
						where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$order = $query->row;
				$status_id = $query->row['status_id'];
				$user_id = $query->row['user_id'];
				$reseller_id = $query->row['reseller_id'];
				$paid_flag = $query->row['paid_flag'];
				$payment_option = $query->row['payment_option'];
				
				if($payment_option == 90 || $payment_option == 91 || $payment_option == 100 || $payment_option == 106 || $payment_option == 107 || $payment_option == 108) {
					if($status_id == 126) {
						$sql = "update oc_order	
							   set status_id = 35
								  ,actual_delivery_date = '".$this->user->now()."'
						     where order_id = ".$order_id;
						$this->db->query($sql);
						
						$sql = "select order_hist_id, to_status_id
									from oc_order_hist_tbl
								  where order_id = ".$order_id."
								  order by order_hist_id desc limit 1";
						$query = $this->db->query($sql);
						$from_status_id = $query->row['to_status_id'];
						// $order_id = $data['order_id'];
						
						if($this->user->isLogged()) {
							$user_id = $this->user->getId();
						} else {
							$user_id = 0;
						}
						
						$sql = "insert into oc_order_hist_tbl
									set user_id = ".$user_id.",
										order_id = ".$order_id.",
										from_status_id = ".$from_status_id.",
										to_status_id = 35,
										remarks = 'Delivered',
										date_added = '".$this->user->now()."' ";
						$this->db->query($sql);					
					
						$return_msg .= "Order Id ".$order_id." is tagged as Delivered.";
					} elseif($status_id == 18) {
						$return_msg .= "Order Id ".$order_id." must be packed first.";
					} elseif($status_id == 34) {
						$return_msg .= "Order Id ".$order_id." must be shipped first.";
					} elseif($status_id == 0) {
						$return_msg .= "Order Id ".$order_id." must be Checkout first.<br>";
					} else {
						$return_msg .= "Order Id ".$order_id." is already delivered.";
					}
				} else {
					$return_msg .= "Order Id ".$order_id." delivered status is not applicable for COP.";
				}
			}
		} else {
			$return_msg .= "No order selected to deliver.";
		}
		return $return_msg;
	}
	
	public function getRemarks($data){
		$sql = "select order_id from oc_order where ref = '" . $data['ref'] . "'";
		$query = $this->db->query($sql);
		$header_id = $query->row['order_id'];

		$sql = "select b.username, concat(b.firstname, ' ', b.lastname) fullname, a.remark, a.date_added
				  from oc_order_comments a 
				  join oc_user b on (a.user_id = b.user_id) 
				 where order_id = " . $header_id . " order by comment_id desc";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function addRemark($data){
		
		$this->load->model('account/ticket');
		
		$sql = "select order_id, user_id, ticket_id from oc_order where ref = '" . $data['ref'] . "'";
		// echo $sql."<br>";
		// var_dump($sql);
		$query = $this->db->query($sql);
		$header_id = $query->row['order_id'];
		$user_id = $query->row['user_id'];
		$ticket_id = $query->row['ticket_id'];
		$remarks = $this->db->escape($data['remark']);
		
		// $order_id = $data['order_id'];

		if($ticket_id > 0) {
			$data['first_time'] = "yes";
			$this->model_account_ticket->updateTicket($data);
		} else {
			if($this->user->isLogged()) {
				$user_id = $this->user->getId();
			} else {
				$sql = "select user_id from oc_user where lower(username) = 'guest' ";
				// echo $sql."<br>";
				// var_dump($sql);
				$query = $this->db->query($sql);
				$user_id = $query->row['user_id'];
			}

			$sql = "update oc_order set remarks = '".$remarks."' where order_id = ".$header_id;
			// echo $sql."<br>";
			// var_dump($sql);
			$query = $this->db->query($sql);
			
			$sql = "INSERT into oc_order_comments SET user_id = " . $user_id . ", order_id = ". $header_id .", remark = '". $remarks ."', date_added = '". $this->user->now() ."'";
			// echo $sql."<br>";
			// var_dump($sql);
			$query = $this->db->query($sql);
		}
	}
	
	public function getTotalBranchAmount($data){
		if(isset($data['type'])) {
			if($data['type'] == "orders"){
				$status_ids = "18,34,35,112,117,118,123,124,126,127";
			} elseif($data['type'] == "processedorders") {
				$status_ids = "119,125";
			}
		}
		
		if(isset($data['status_id'])) {
			if($data['status_id'] == 0) {
				$status_ids = $status_ids;
			} else {
				$status_ids = $data['status_id'];
			}
		}
		
		if($this->user->getUserGroupId() == 12) {
			$sql = "SELECT sum(amount) total FROM oc_order WHERE status_id IN (".$status_ids.") ";
		} elseif($this->user->getUserGroupId() == 39) {
			$sql = "SELECT sum(amount) total FROM oc_order WHERE status_id IN (".$status_ids.") and operator_id = ".$this->user->getId()." or user_id = ".$this->user->getId()." and status_id IN (".$status_ids.") ";
		} elseif($this->user->getUserGroupId() == 45) {
			$sql = "SELECT sum(amount) total FROM oc_order WHERE status_id IN (".$status_ids.") and sales_rep_id = ".$this->user->getId()." or user_id = ".$this->user->getId();
		} elseif($this->user->getUserGroupId() == 46 || $this->user->getUserGroupId() == 47) {
			$sql = "SELECT sum(amount) total FROM oc_order WHERE status_id IN (".$status_ids.") and user_id = ".$this->user->getId();
		} else {
			$sql = "SELECT sum(amount) total FROM oc_order WHERE status_id IN (".$status_ids.") AND branch_id = " . $this->user->getBranchId();
		}
		
		if(isset($data['order_id'])) {
			if(!empty($data['order_id'])) {
				$sql .= " and order_id in (".$this->db->escape(trim($data['order_id'],",")).")";
			}
		}
		
		if(isset($data['datecreatedfrom'])) {
			if (!empty($data['datecreatedfrom'])) {
				$sql .= " and date_added >= '" . $data['datecreatedfrom'] . " 00:00:00'";
			}
		}		
		
		if(isset($data['datecreatedto'])) {
			if (!empty($data['datecreatedto'])) {
				$sql .= " and date_added <= '" . $data['datecreatedto'] . " 23:59:59'";
			}
		}
		
		if(isset($data['packeddatefrom'])) {
			if (!empty($data['packeddatefrom'])) {
				$sql .= " and packed_date >= '" . $data['packeddatefrom'] . " 00:00:00'";
			}
		}		
		
		if(isset($data['packeddateto'])) {
			if (!empty($data['packeddateto'])) {
				$sql .= " and packed_date <= '" . $data['packeddateto'] . " 23:59:59'";
			}
		}
		
		if(isset($data['datepaidfrom'])) {
			if (!empty($data['datepaidfrom'])) {
				$sql .= " and paid_date >= '" . $data['datepaidfrom'] . " 00:00:00'";
			}
		}		
		
		if(isset($data['datepaidto'])) {
			if (!empty($data['datepaidto'])) {
				$sql .= " and paid_date <= '" . $data['datepaidto'] . " 23:59:59'";
			}
		}
		
		if(isset($data['deliverydatefrom'])) {
			if (!empty($data['deliverydatefrom'])) {
				$sql .= " and delivery_date >= '" . $data['deliverydatefrom'] . "'";
			}
		}		
		
		if(isset($data['deliverydateto'])) {
			if (!empty($data['deliverydateto'])) {
				$sql .= " and delivery_date <= '" . $data['deliverydateto'] . "'";
			}
		}
		
		if(isset($data['paid_flag'])) {
			if($data['paid_flag'] != "") {
				$sql .= " and paid_flag in (".$this->db->escape($data['paid_flag']).")";				
			}
		}
		
		if(isset($data['tracking'])) {
			if($data['tracking'] != "") {
				$sql .= " and tracking in (".$this->db->escape($data['tracking']).")";				
			}
		}
		// echo $sql."<br>";
		$query = $this->db->query($sql);
		return $query->row['total'];

	}	
	
	public function getTotalBranchOrders($data){
		
		if(isset($data['type'])) {
			if($data['type'] == "orders"){
				$status_ids = "18,34,35,112,117,118,123,124,126,127";
			} elseif($data['type'] == "processedorders") {
				$status_ids = "119,125";
			}
		}
		
		if(isset($data['status_id'])) {
			if($data['status_id'] == 0) {
				$status_ids = $status_ids;
			} else {
				$status_ids = $data['status_id'];
			}
		}
		
		if($this->user->getUserGroupId() == 12) {
			$sql = "SELECT count(1) count FROM oc_order WHERE status_id IN (".$status_ids.") ";
		} elseif($this->user->getUserGroupId() == 39) {
			$sql = "SELECT count(1) count FROM oc_order WHERE status_id IN (".$status_ids.") and operator_id = ".$this->user->getId()." or user_id = ".$this->user->getId()." and status_id IN (".$status_ids.") ";
		} elseif($this->user->getUserGroupId() == 45) {
			$sql = "SELECT count(1) count FROM oc_order WHERE status_id IN (".$status_ids.") and sales_rep_id = ".$this->user->getId()." or user_id = ".$this->user->getId();
		} elseif($this->user->getUserGroupId() == 46 || $this->user->getUserGroupId() == 47) {
			$sql = "SELECT count(1) count FROM oc_order WHERE status_id IN (".$status_ids.") and user_id = ".$this->user->getId();
		} else {
			$sql = "SELECT count(1) count FROM oc_order WHERE status_id IN (".$status_ids.") AND branch_id = " . $this->user->getBranchId();
		}
		
		if(isset($data['order_id'])) {
			if(!empty($data['order_id'])) {
				$sql .= " and order_id in (".$this->db->escape(trim($data['order_id'],",")).")";
			}
		}
		
		if(isset($data['datecreatedfrom'])) {
			if (!empty($data['datecreatedfrom'])) {
				$sql .= " and date_added >= '" . $data['datecreatedfrom'] . " 00:00:00'";
			}
		}		
		
		if(isset($data['datecreatedto'])) {
			if (!empty($data['datecreatedto'])) {
				$sql .= " and date_added <= '" . $data['datecreatedto'] . " 23:59:59'";
			}
		}
		
		if(isset($data['packeddatefrom'])) {
			if (!empty($data['packeddatefrom'])) {
				$sql .= " and packed_date >= '" . $data['packeddatefrom'] . " 00:00:00'";
			}
		}		
		
		if(isset($data['packeddateto'])) {
			if (!empty($data['packeddateto'])) {
				$sql .= " and packed_date <= '" . $data['packeddateto'] . " 23:59:59'";
			}
		}
		
		if(isset($data['datepaidfrom'])) {
			if (!empty($data['datepaidfrom'])) {
				$sql .= " and paid_date >= '" . $data['datepaidfrom'] . " 00:00:00'";
			}
		}		
		
		if(isset($data['datepaidto'])) {
			if (!empty($data['datepaidto'])) {
				$sql .= " and paid_date <= '" . $data['datepaidto'] . " 23:59:59'";
			}
		}
		
		if(isset($data['deliverydatefrom'])) {
			if (!empty($data['deliverydatefrom'])) {
				$sql .= " and delivery_date >= '" . $data['deliverydatefrom'] . "'";
			}
		}		
		
		if(isset($data['deliverydateto'])) {
			if (!empty($data['deliverydateto'])) {
				$sql .= " and delivery_date <= '" . $data['deliverydateto'] . "'";
			}
		}
		
		if(isset($data['paid_flag'])) {
			if($data['paid_flag'] != "") {
				$sql .= " and paid_flag in (".$this->db->escape($data['paid_flag']).")";				
			}
		}
		
		if(isset($data['tracking'])) {
			if($data['tracking'] != "") {
				$sql .= " and tracking in (".$this->db->escape($data['tracking']).")";				
			}
		}
		// echo $sql."<br>";
		
		$query = $this->db->query($sql);
		return $query->row['count'];
	}
	
	public function getLBCCodCopOrdersPerStatus($data){	
		/*
		LBC COD
		107 - For LBC Cash On Delivery		
		34  - Packed LBC COD
		117 - In-Transit
		35  - Delivered
		118 - Returned
		119 - Payment Received
		
		LBC COP
		94 - For LBC Cash On Pick-up
		34 - Packed LBC Pick-Up
		112 - Picked-Up		
		*/
		// $status_id = "18,34,117,118,123,124,126,127";
		// $status_id = "18,34,35,112,117,118,123,124,126,127";
		$data1 = "";
		
		if(isset($data['order_id'])) {
			if(!empty($data['order_id'])) {
				$data1 = $data['order_id'];
				$data = "order_id";				
			}
		}
		
		if(isset($data['datecreatedfrom'])) {
			if (!empty($data['datecreatedfrom'])) {
				$data1 = $data['datecreatedfrom'];
				$data = "datecreatedfrom";
			}
		}		
		
		if(isset($data['datecreatedto'])) {
			if (!empty($data['datecreatedto'])) {
				$data1 = $data['datecreatedto'];
				$data = "datecreatedto";
			}
		}
		
		if(isset($data['packeddatefrom'])) {
			if (!empty($data['packeddatefrom'])) {
				$data1 = $data['packeddatefrom'];
				$data = "packeddatefrom";
			}
		}		
		
		if(isset($data['packeddateto'])) {
			if (!empty($data['packeddateto'])) {
				$data1 = $data['packeddateto'];
				$data = "packeddateto";
			}
		}
		
		if(isset($data['datepaidfrom'])) {
			if (!empty($data['datepaidfrom'])) {
				$data1 = $data['datepaidfrom'];
				$data = "datepaidfrom";
			}
		}		
		
		if(isset($data['datepaidto'])) {
			if (!empty($data['datepaidto'])) {
				$data1 = $data['datepaidto'];
				$data = "datepaidto";
			}
		}
		
		if(isset($data['deliverydatefrom'])) {
			if (!empty($data['deliverydatefrom'])) {
				$data1 = $data['deliverydatefrom'];
				$data = "deliverydatefrom";
			}
		}		
		
		if(isset($data['deliverydateto'])) {
			if (!empty($data['deliverydateto'])) {
				$data1 = $data['deliverydateto'];
				$data = "deliverydateto";
			}
		}
		
		if(isset($data['paid_flag'])) {
			if($data['paid_flag'] != "") {
				$data1 = $data['paid_flag'];
				$data = "paid_flag";
			}
		}
		
		if(isset($data['tracking'])) {
			if($data['tracking'] != "") {
				$data1 = $data['tracking'];
				$data = "tracking";
			}
		}
		
		//LBC COD
		$return_array['for_lbc_cod'] = $this->getBranchOrdersPerStatusQry($data,$data1,'18','107');
		$return_array['packed_lbc_cod'] = $this->getBranchOrdersPerStatusQry($data,$data1,'34','107');
		$return_array['lbc_cod_in_transit'] = $this->getBranchOrdersPerStatusQry($data,$data1,'126','107');
		$return_array['lbc_cod_delivered'] = $this->getBranchOrdersPerStatusQry($data,$data1,'35','107');
		$return_array['lbc_cod_returned'] = $this->getBranchOrdersPerStatusQry($data,$data1,'118','107');
		$return_array['lbc_cod_payment_received'] = $this->getBranchOrdersPerStatusQry($data,$data1,'119','107');
		
		//LBC COP
		$return_array['for_lbc_cop'] = $this->getBranchOrdersPerStatusQry($data,$data1,'18','94');
		$return_array['packed_lbc_cop'] = $this->getBranchOrdersPerStatusQry($data,$data1,'34','94');
		$return_array['lbc_cop_in_transit'] = $this->getBranchOrdersPerStatusQry($data,$data1,'126','94');
		$return_array['lbc_cop_picked_up'] = $this->getBranchOrdersPerStatusQry($data,$data1,'112','94');
		$return_array['lbc_cop_returned'] = $this->getBranchOrdersPerStatusQry($data,$data1,'118','94');
		$return_array['lbc_cop_payment_received'] = $this->getBranchOrdersPerStatusQry($data,$data1,'119','94');
		
		return $return_array;
	}
	
	public function getManongOrdersPerStatus($data){	
		
		// $status_id = "18,34,117,118,123,124,126,127";
		// $status_id = "18,34,35,112,117,118,123,124,126,127";
		$data1 = "";
		
		if(isset($data['order_id'])) {
			if(!empty($data['order_id'])) {
				$data1 = $data['order_id'];
				$data = "order_id";				
			}
		}
		
		if(isset($data['datecreatedfrom'])) {
			if (!empty($data['datecreatedfrom'])) {
				$data1 = $data['datecreatedfrom'];
				$data = "datecreatedfrom";
			}
		}		
		
		if(isset($data['datecreatedto'])) {
			if (!empty($data['datecreatedto'])) {
				$data1 = $data['datecreatedto'];
				$data = "datecreatedto";
			}
		}
		
		if(isset($data['packeddatefrom'])) {
			if (!empty($data['packeddatefrom'])) {
				$data1 = $data['packeddatefrom'];
				$data = "packeddatefrom";
			}
		}		
		
		if(isset($data['packeddateto'])) {
			if (!empty($data['packeddateto'])) {
				$data1 = $data['packeddateto'];
				$data = "packeddateto";
			}
		}
		
		if(isset($data['datepaidfrom'])) {
			if (!empty($data['datepaidfrom'])) {
				$data1 = $data['datepaidfrom'];
				$data = "datepaidfrom";
			}
		}		
		
		if(isset($data['datepaidto'])) {
			if (!empty($data['datepaidto'])) {
				$data1 = $data['datepaidto'];
				$data = "datepaidto";
			}
		}
		
		if(isset($data['deliverydatefrom'])) {
			if (!empty($data['deliverydatefrom'])) {
				$data1 = $data['deliverydatefrom'];
				$data = "deliverydatefrom";
			}
		}		
		
		if(isset($data['deliverydateto'])) {
			if (!empty($data['deliverydateto'])) {
				$data1 = $data['deliverydateto'];
				$data = "deliverydateto";
			}
		}
		
		if(isset($data['paid_flag'])) {
			if($data['paid_flag'] != "") {
				$data1 = $data['paid_flag'];
				$data = "paid_flag";
			}
		}
		
		if(isset($data['tracking'])) {
			if($data['tracking'] != "") {
				$data1 = $data['tracking'];
				$data = "tracking";
			}
		}
		
		//MANONG COD
		$return_array['for_manong_cod'] = $this->getBranchOrdersPerStatusQry($data,$data1,'18','106');
		$return_array['packed_manong_cod'] = $this->getBranchOrdersPerStatusQry($data,$data1,'34','106');
		$return_array['manong_cod_in_transit'] = $this->getBranchOrdersPerStatusQry($data,$data1,'126','106');
		$return_array['manong_cod_delivered'] = $this->getBranchOrdersPerStatusQry($data,$data1,'35','106');
		$return_array['manong_cod_returned'] = $this->getBranchOrdersPerStatusQry($data,$data1,'118','106');
		$return_array['manong_cod_payment_received'] = $this->getBranchOrdersPerStatusQry($data,$data1,'119','106');
		
		//MANONG COP
		$return_array['for_manong_cop'] = $this->getBranchOrdersPerStatusQry($data,$data1,'127','93');
		$return_array['packed_manong_cop'] = $this->getBranchOrdersPerStatusQry($data,$data1,'34','93');
		$return_array['manong_cop_in_transit'] = $this->getBranchOrdersPerStatusQry($data,$data1,'117','93');
		$return_array['manong_cop_picked_up'] = $this->getBranchOrdersPerStatusQry($data,$data1,'112','93');
		$return_array['manong_cop_returned'] = $this->getBranchOrdersPerStatusQry($data,$data1,'118','93');
		$return_array['manong_cop_payment_received'] = $this->getBranchOrdersPerStatusQry($data,$data1,'119','93');
		
		return $return_array;
	}

	public function getBranchOrdersPerStatusQry($data = "", $data1 = "", $status_id = "", $payment_option = "") {
		$sql = "SELECT count(a.order_id) total 
				  FROM oc_order a 
				 WHERE 1 = 1 ";
				 
		if($this->user->getUserGroupId() == 49) {
			$sql .= " and a.branch_id = ".$this->user->getBranchId();
		} elseif($this->user->getUserGroupId() == 39) {
			$sql .= " and a.operator_id = ".$this->user->getId();
		} elseif($this->user->getUserGroupId() == 45) {
			$sql .= " and a.sales_rep_id = ".$this->user->getId();
		} elseif($this->user->getUserGroupId() == 46) {
			$sql .= " and a.company_admin_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 47) {
			$sql .= " and a.user_id = ".$this->user->getId();
		}

		if($status_id != "") {
			$sql .= " and a.status_id in (".$status_id.") ";
		}

		if($payment_option != "") {
			$sql .= " and a.payment_option = ".$payment_option;
		}
		
		if($data == "order_id") {
			$sql .= " and a.order_id in (".$this->db->escape(trim($data1,",")).")";
		}
		
		if($data == "datecreatedfrom") {
			$sql .= " and a.date_added >= '" . $data1 . " 00:00:00'";
		}		
		
		if($data == "datecreatedto") {
			$sql .= " and a.date_added <= '" . $data1 . " 23:59:59'";
		}
		
		if($data == "packeddatefrom") {
			$sql .= " and a.packed_date >= '" . $data1 . " 00:00:00'";
		}		
		
		if($data == "packeddateto") {
			$sql .= " and a.packed_date <= '" . $data1 . " 23:59:59'";
		}
		
		if($data == "datepaidfrom") {
			$sql .= " and a.paid_date >= '" . $data1 . " 00:00:00'";
		}		
		
		if($data == "datepaidto") {
			$sql .= " and a.paid_date <= '" . $data1 . " 23:59:59'";
		}
		
		if($data == "deliverydatefrom") {
			$sql .= " and a.delivery_date >= '" . $data1 . "'";
		}		
		
		if($data == "deliverydateto") {
			$sql .= " and a.delivery_date <= '" . $data1 . "'";
		}
		
		if($data == "paid_flag") {
			$sql .= " and a.paid_flag in (".$this->db->escape($data1).")";	
		}
		
		if($data == "tracking") {
			$sql .= " and a.tracking in (".$this->db->escape($data1).")";	
		}
		
		// echo "<br>".$sql."<br>";
		$query = $this->db->query($sql);
		if(isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	} 	
	
	public function getDropshipOrdersPerStatusQry($status_id) {
		$sql = "select count(1) total 
					from oc_order a 
					join gui_status_tbl b on (a.status_id = b.status_id) 
					join gui_status_tbl c on (a.payment_option = c.status_id) 
					join oc_user e on (a.user_id = e.user_id) 
					left join gui_branch_tbl f on (a.branch_id = f.branch_id) 
					left join oc_provinces g on (a.province_id = g.province_id) 
					left join oc_city_municipality h on (a.city_municipality_id = h.city_municipality_id) 
					left join oc_barangays i on (a.barangay_id = i.barangay_id) 
				WHERE a.status_id in (" . $status_id.") 
			ORDER BY a.order_id desc";
		
		$query = $this->db->query($sql);

		if(isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	} 
	
	public function distributeCommission($o) {
		// $order_log = "order_id = ".$o['order_id'].", user_id = ".$o['user_id'].", operator_id = ".$o['operator_id'].", sales_rep_id = ".$o['sales_rep_id'].", reseller_id = ".$o['reseller_id'].", company_admin_id = ".$o['company_admin_id'];
		// $this->log->write('log = '.$sql);
		
		$sql = "select a.quantity, b.item_id, b.item_name, b.unilevel1, b.rebates, b.points, 
				b.epoints, b.eseller_income, b.sales_rep_income, b.operator_income,
				b.performance_points, b.discount_per_item, b.company_admin_income, c.user_id
				from oc_order_details a
				join gui_items_tbl b on(a.item_id = b.item_id)
				left join oc_order c
				on (a.order_id = c.order_id)
				where a.order_id = ".$o['order_id'];
		$query = $this->db->query($sql);
		$order_details = $query->rows;
		
		foreach($order_details as $ord) {
			if($o['sales_rep_id'] > 0) {
				$this->insertEwallet($ord['quantity'] * $ord['sales_rep_income'], $o['sales_rep_id'], $o['sales_rep_id'], $o['order_id'], 23, 1);
				$this->insertEwallet($ord['quantity'] * ($ord['operator_income'] - $ord['sales_rep_income']), $o['operator_id'], $o['sales_rep_id'], $o['order_id'], 22, 1);
			} else if($o['reseller_id'] > 0) {
				$this->insertEwallet($ord['quantity'] * $ord['eseller_income'], $o['reseller_id'], $o['reseller_id'], $o['order_id'], 23, 1);
				$this->insertEwallet($ord['quantity'] * ($ord['operator_income'] - $ord['eseller_income']), $o['operator_id'], $o['reseller_id'], $o['order_id'], 22, 1);
			} else if($o['company_admin_id'] > 0) {
				$this->insertEwallet($ord['quantity'] * $ord['company_admin_income'], $o['company_admin_id'], $o['user_id'], $o['order_id'], 26, 1);
			} // } else {
				// $this->insertEwallet($ord['quantity'] * $ord['operator_income'], $o['operator_id'], $o['operator_id'], $o['order_id'], 22, 1);
			// }
		}
		
	}
	
	public function insertEwallet($income, $user_id, $source_user_id, $order_id, $commission_type_id, $created_by) {
		$sql = "select description
				  from oc_commission_type 
				 where commission_type_id = ".$commission_type_id;
		$query = $this->db->query($sql);
		$remarks = $query->row['description'];
		
		$sql = "update oc_user set ewallet = ewallet + ".$income."
				 where user_id = ".$user_id;
		$this->db->query($sql);
		
		$sql = "insert into oc_ewallet_hist 
					set user_id = ".$user_id.",
						order_id = ".$order_id.",
						source_user_id = ".$source_user_id.",
						commission_type_id = ".$commission_type_id.",
						credit = ".$income.",
						remarks = '".$remarks."',
						created_by = ".$created_by.",
						date_added = '".$this->user->now()."' ";
		$this->db->query($sql);
	}
	
	public function performStatusUpdate($o, $to_status_id, $paid_flag) {
		$sql = "update oc_order	
				   set paid_flag = ".$paid_flag." 
					  ,status_id = ".$to_status_id."
					  ,paid_date = '".$this->user->now()."'
				 where order_id = ".$o['order_id'];
		$this->db->query($sql);
		
		$sql = "select description
				  from gui_status_tbl 
				 where status_id = ".$to_status_id;
		$query = $this->db->query($sql);
		$status = $query->row['description'];
		
		if($this->user->isLogged()) {
			$user_id = $this->user->getId();
		} else {
			$user_id = 0;
		}
		
		$sql = "insert into oc_order_hist_tbl
					set user_id = ".$user_id.",
						order_id = ".$o['order_id'].",
						from_status_id = ".$o['status_id'].",
						to_status_id = ".$to_status_id.",
						remarks = '".$status."',
						date_added = '".$this->user->now()."' ";
		$this->db->query($sql);
	}
	
	public function insertNewUser($user_group_id,$username,$password,$firstname,$lastname,$operator_id,$sponsor_id) {
			
			$sql  = "INSERT INTO oc_user ";
			$sql .= "   SET user_group_id = ".$user_group_id;
			$sql .= "      ,username = '" . $username . "'";
			$sql .= "      ,password = '" . $password . "'";
			$sql .= "      ,firstname = UCASE('" . $firstname . "')";
			$sql .= "      ,lastname = UCASE('" . $lastname . "')";
			$sql .= "      ,operator_id = ".$operator_id;
			$sql .= "      ,refer_by_id = ".$sponsor_id;
			$sql .= "      ,status = 1 " ;
			$sql .= "      ,activation_flag = 1 " ;
			$sql .= "      ,sme_flag = 0 " ;
			$sql .= "      ,site = '".WEBSITE."' " ;
			$sql .= "      ,date_added = '".$this->user->now()."' ";
			
			// echo $sql."1<br>";
			
			// if($item_id == 5){
				// $sql .= " ,user_group_id = 56 ";
			// }elseif($item_id == 6){
				// $sql .= " ,user_group_id = 57 ";
			// }
			
			$this->db->query($sql);
			$user_id = $this->db->getLastId();
			
			$sql = "UPDATE oc_user SET id_no = concat('".IDPREFIX."',lpad(".$user_id.", 10, '0')) WHERE user_id = ".$user_id;
			$this->db->query($sql);
			
			$sql = "insert into oc_unilevel(user_id, sponsor_user_id, level, date_added)										
				         values (".$user_id.", ".$sponsor_id.", 1, '".$this->user->now()."')";			
			$this->db->query($sql);
			
			$sql = "insert into oc_unilevel(user_id, sponsor_user_id, level, date_added )					
				    select ".$user_id.", sponsor_user_id, (level + 1), '".$this->user->now()."'						  
					from oc_unilevel ou					 
					where user_id = ".$sponsor_id."						   
					and sponsor_user_id <> 0";			
			$this->db->query($sql);
	}
	
	public function assembleItem($order_id, $status_to) {
		$this->log->write("inside assembleItem");
		$assemble_inventory = 0;
		
		//kung status to ay packed
		if($status_to == 34) {
			$assemble_inventory = 1;
		}
		
		if($assemble_inventory == 1) { 
			//checking if complete lahat ng item inventory para sa order
			//summarize muna ung need na raw items
			$sql = "select case when f.item_name is not null then f.item_id else d.item_id end item_id,
						   case when f.item_name is not null then f.item_name else d.item_name end item_name,
						   case when f.item_name is not null then (c.quantity * e.item_quantity) else c.quantity end quantitys,
						   a.branch_id
					  from oc_order a
					  left join oc_order_details c on (a.order_id = c.order_id)
					  left join gui_items_tbl d on (c.item_id = d.item_id)
					  left join oc_promos_tbl e on(c.item_id = e.item_header_id)
					  left join gui_items_tbl f on (e.item_id = f.item_id)
					 where a.order_id = ".$order_id;
			$query = $this->db->query($sql);
			$raw_items = $query->rows;
			$branch_id = $query->row['branch_id'];
			$this->log->write($sql);
			//initialize checking variable = 1
			$check_assemble_inventory = 1;
			
			//loop sa raw items
			foreach($raw_items as $ri) {
				//check if sufficient pa sa inventory
				$quantity = 0;
				$sql = "select qty 
						  from oc_inventory 
						 where branch_id = ".$branch_id."
						 and item_id = ".$ri['item_id'];
				$query = $this->db->query($sql);
				$this->log->write($sql);
				if(isset($query->row['qty'])) {
					$quantity = $query->row['qty'];
				}
				
				$this->log->write("quantity = ".$quantity);
				$this->log->write("needed quantity from order = ".$ri['quantitys']);
				//kapag may isang sablay ang quantity gawing 0 ung checking variable
				if($quantity < $ri['quantitys']) {
					$check_assemble_inventory = 0;
					$assemble_inventory = 0;
				}
			}
			
			$this->log->write("check_assemble_inventory = ".$check_assemble_inventory);
			//if checking variable is 1
			if($check_assemble_inventory == 1) {
				$sql = "select item_id, quantity 
						  from oc_order_details 
						 where order_id = " . $order_id;			
				$query = $this->db->query($sql);
				$item_to_assemble = $query->rows;
				$this->log->write($sql);		
				foreach($item_to_assemble as $ita) {
					//count/check if nasa promos table
					$sql = "select count(1) total 
							  from oc_promos_tbl 
							 where item_header_id = ".$ita['item_id'];
					$query = $this->db->query($sql);
					$this->log->write($sql);
					$total = $query->row['total'];
					//if wala sa promos
					if($total == 0) {
						//minus ang quantity ng item sa inventory table
						$sql = "UPDATE oc_inventory
								SET qty = qty - ".$ita['quantity']."
									,modified_by = '".$this->user->getId()."'
									,date_added='".$this->user->now()."'
									WHERE branch_id = ".$branch_id."
									and item_id = " . $ita['item_id'];
						$this->db->query($sql);
						$this->log->write($sql);
						//magrecord nun sa inventory history
						$sql = "INSERT INTO oc_inventory_history_tbl 
								   SET user_id = '".$this->user->getId()."'
									 , item_id = ".$ita['item_id']."
									 , packed = ".$ita['quantity']."
									 , status = ". $status_to."
									 , order_id =  ".$order_id."
									 , date_added = '".$this->user->now()."'
									 , branch_id = ".$branch_id."
									 , remarks = 'ITEM PACKED'";
						$this->db->query($sql);
						$this->log->write($sql);
					//if nasa promos
					} else {
						//get exploded items sa promos
						$sql = "select item_id, item_quantity
								  from oc_promos_tbl 
								 where item_header_id = ".$ita['item_id'];
						$query = $this->db->query($sql);
						$promo_items = $query->rows;
						$this->log->write($sql);
						//loop sa exploded items sa promos
						foreach($promo_items as $pi) {
							//minus ang quantity ng item sa inventory table
							$sql = "UPDATE oc_inventory 
									   SET qty = qty - (".$ita['quantity']."*".$pi['item_quantity'].")
									 WHERE branch_id = ".$branch_id." 
									 and item_id = ".$pi['item_id'];
							$this->db->query($sql);
							$this->log->write($sql);
							//magrecord nun sa inventory history
							$sql = "INSERT INTO oc_inventory_history_tbl 
									   SET item_id = ".$pi['item_id']."
										 , assembled_from = (".$ita['quantity']."*".$pi['item_quantity'].")
										 , status = ". $status_to."
										 , order_id =  ".$order_id."
										 , date_added = '".$this->user->now()."'
										 , branch_id = ".$branch_id."
										 , remarks = 'ITEM ASSEMBLED TO PROMO'";
							$this->db->query($sql);	
							$this->log->write($sql);
						}
						
						//dagdagan ang quantity ng item promo sa inventory table
						$sql = "select count(1) as total
								  from oc_inventory 
								 where branch_id = ".$branch_id."
								 and item_id = " . $ita['item_id'];
						$query = $this->db->query($sql);
						$item_count = $query->row['total'];
						$this->log->write($sql);
						if($item_count == 0){
							$sql = "INSERT INTO oc_inventory 
									   SET qty = " . $ita['quantity'] ."
										  ,item_id = ".$ita['item_id'] . "
										  ,branch_id = ".$branch_id."
										  ,date_added = '".$this->user->now()."'";
							$this->db->query($sql);
							$this->log->write($sql);
						}else{
							$sql = "UPDATE oc_inventory
									   SET qty = qty + ".$ita['quantity']."
									 WHERE branch_id = ".$branch_id."
									 AND item_id = ".$ita['item_id'];
							$this->db->query($sql);
							$this->log->write($sql);
						}
						
						//magrecord nun sa inventory history (remarks ay assembled) 
						$sql = "INSERT INTO oc_inventory_history_tbl 
								   SET item_id = ".$ita['item_id']."
									 , assembled_to = ".$ita['quantity']."
									 , status = ". $status_to."
									 , order_id =  ".$order_id."
									 , date_added = '".$this->user->now()."'
									 , branch_id = ".$branch_id."
									 , remarks = 'PROMO ASSEMBLED'";
						$this->db->query($sql);		
						$this->log->write($sql);
						
						//bawasan ang quantity ng item promo sa inventory table
						$sql = "UPDATE oc_inventory
									   SET qty = qty - ".$ita['quantity']."
									 WHERE branch_id = ".$branch_id."
									 AND item_id = ".$ita['item_id'];
						$this->db->query($sql);
						$this->log->write($sql);
						
						//magrecord nun sa inventory history (packed)
						$sql = "INSERT INTO oc_inventory_history_tbl 
								   SET item_id = ".$ita['item_id']."
									 , packed = ".$ita['quantity']."
									 , status = ". $status_to."
									 , order_id =  ".$order_id."
									 , date_added = '".$this->user->now()."'
									 , branch_id = ".$branch_id."
									 , remarks = 'PROMO PACKED'";
						$this->db->query($sql);	
						$this->log->write($sql);
					}									
				}				
			} 
		}
		$this->log->write("leaving assembleItem");
		return $assemble_inventory;
	}
	
	public function disassembleItem($order_id, $status_to) {
		$this->log->write("inside disassembleItem");
		$return_inventory = 0;
		$sql = "select payment_option, status_id, inventoriable,  paid_flag, branch_id
				  from oc_order where order_id = ".$order_id;
		$query = $this->db->query($sql);
		$payment_option = $query->row['payment_option'];
		$branch_id = $query->row['branch_id'];
		$status_id = $query->row['status_id'];
		$inventoriable = $query->row['inventoriable'];
		
		//checkout, nakainventoriable, status to ay cancelled at return
		if($status_id == 34 && $inventoriable == 1 && ($status_to == 113 or $status_to == 120)) {
			$return_inventory = 1;
			$sql = "select description
					  from gui_status_tbl 
					 where status_id = ".$status_to;
			$query = $this->db->query($sql);
			$status_to_desc = $query->row['description'];
		}
		
		if($return_inventory == 1) { 
			
			$sql = "select item_id, quantity 
					  from oc_order_details 
					 where order_id = " . $order_id;			
			$query = $this->db->query($sql);
			$item_to_assemble = $query->rows;
				
			foreach($item_to_assemble as $ita) {
				//count/check if nasa promos table
				$sql = "select count(1) total 
						  from oc_promos_tbl 
						 where item_header_id = ".$ita['item_id'];
				$query = $this->db->query($sql);
				$total = $query->row['total'];
				//if wala sa promos
				if($total == 0) {
					//dagdagan ang quantity ng item sa inventory table
					$sql = "UPDATE oc_inventory
							   SET qty = qty + ".$ita['quantity']."
							 WHERE branch_id = ".$branch_id."
							 AND item_id = " . $ita['item_id'];
					$this->db->query($sql);
					$this->log->write($sql);
					//magrecord nun sa inventory history
					$sql = "INSERT INTO oc_inventory_history_tbl 
							   SET item_id = ".$ita['item_id']."
								 , returned = ".$ita['quantity']."
								 , status = ". $status_to."
								 , order_id =  ".$order_id."
								 , date_added = '".$this->user->now()."'
								 , branch_id = 1
								 , remarks = '".$status_to_desc." : ITEM DISASSEMBLED FROM PROMO'";
					$this->db->query($sql);
					$this->log->write($sql);
				//if nasa promos
				} else {
					//get exploded items sa promos
					$sql = "select item_id, item_quantity
							  from oc_promos_tbl 
							 where item_header_id = ".$ita['item_id'];
					$query = $this->db->query($sql);
					$promo_items = $query->rows;
					//loop sa exploded items sa promos
					foreach($promo_items as $pi) {
						//minus ang quantity ng item sa inventory table
						$sql = "UPDATE oc_inventory 
								   SET qty = qty + (".$ita['quantity']."*".$pi['item_quantity'].")
								 WHERE item_id = ".$pi['item_id'];
						$this->db->query($sql);
						$this->log->write($sql);
						//magrecord nun sa inventory history
						$sql = "INSERT INTO oc_inventory_history_tbl 
								   SET item_id = ".$pi['item_id']."
									 , returned = (".$ita['quantity']."*".$pi['item_quantity'].")
									 , status = ". $status_to."
									 , order_id =  ".$order_id."
									 , date_added = '".$this->user->now()."'
									 , branch_id = 1
									 , remarks = '".$status_to_desc." : ITEM DISASSEMBLED FROM PROMO'";
						$this->db->query($sql);	
						$this->log->write($sql);						
					}						
				}
			} 
		}
		$this->log->write("leaving dissambleItem");
	}
	
	public function tagSoldInventory($order_id, $status_to) {
		$this->log->write("inside tagSoldInventory");
		$sql = "select item_id, quantity 
				  from oc_order_details 
				 where order_id = ".$order_id;
		$query = $this->db->query($sql);	
		$this->log->write($sql);
		$items = $query->rows;
		
		$sql = "select description
				  from gui_status_tbl 
				 where status_id = ".$status_to;
		$query = $this->db->query($sql);
		$status_to_desc = $query->row['description'];		
		
		foreach($items as $i) {
			$sql = "INSERT INTO oc_inventory_history_tbl 
					   SET item_id = ".$i['item_id']."
						 , sold = ".$i['quantity']."
						 , status = ". $status_to."
						 , order_id =  ".$order_id."
						 , date_added = '".$this->user->now()."'
						 , branch_id = 1
						 , remarks = '".$status_to_desc." : TAG ITEM TO SOLD'";
			$this->db->query($sql);
		}
		$this->log->write("inside tagSoldInventory");
	}
	
	public function writeLogger($message, $order_id = 0) {
		$sql = "insert into temps(var1, var2, order_id)
				values('".$this->user->now()."', '".$this->db->escape($message)."', ".$order_id.")";
		$this->db->query($sql);
	}
	
	// public function getPaymentRemittedCOD(){
		// $sql = "select sum()";
	// }
}
?>