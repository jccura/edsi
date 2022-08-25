<?php
class ModelAccountOrders extends Model {	
		
	public function getProducts() {
		$sql = "select item_id, description from gui_items_tbl where active = 1 ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getOrders($data, $query_type = "data") {
		
		$sql = "select a.order_id, a.receipt_number, a.date_added,case when a.city_distributor_id then l.username else 'No Area Operator' end area_op, a.status_id, a.shipped_date, a.payment_option,
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
				  left join oc_user l on(a.city_distributor_id = l.user_id)
				 where 1 = 1 ";
				 
		if($data['type'] == "cancelledorders") {
			if($this->user->getUserGroupId() == 12 || $this->user->getUserGroupId() == 54) {
				$sql .= " and a.status_id in(19,113) ";
			} elseif($this->user->getUserGroupId() == 39) {
				$sql .= " and a.status_id in(19,113)  and a.city_distributor_id = ".$this->user->getId();
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
				$sql .= " and a.status_id in(112,119,125,35,152) and a.paid_flag = 1 ";
			} elseif($this->user->getUserGroupId() == 39) {
				$sql .= " and a.status_id in(35, 152) and a.city_distributor_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 45) {
				$sql .= " and a.status_id in(112,119,125) and a.sales_rep_id = ".$this->user->getId()." and a.paid_flag = 1 ";
			} elseif($this->user->getUserGroupId() == 46) {
				$sql .= " and a.status_id in(112,119,125,152,35) and a.user_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 47) {
				$sql .= " and a.status_id in(112,119,125,152,35) and a.user_id = ".$this->user->getId();
			} elseif($this->user->getUserGroupId() == 56) {
				$sql .= " and a.status_id in(112,119,125,152,35) and a.user_id = ".$this->user->getId();
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
			if($this->user->getUserGroupId() == 39) {
				$sql .= " and a.status_id not in(19, 113, 35, 152) and a.city_distributor_id = ".$this->user->getId();
			} 
			
			if($this->user->getUserGroupId() == 47) {
				$sql .= " and a.status_id not in(19, 113, 35, 152) and a.user_id = ".$this->user->getId();
			}
			
			if($this->user->getUserGroupId() == 56) {
				$sql .= " and a.status_id not in(19, 113, 35, 152) and a.distributor_id = ".$this->user->getId();
			}
			
			if($this->user->getUserGroupId() == 12) {
				$sql .= " and a.status_id not in(19, 113, 35, 152)";
			}
			
			if($this->user->getUserGroupId() == 46) {
				$sql .= "and a.status_id not in(19, 113, 35, 152) and a.user_id = ".$this->user->getId();
			}
		}
			
		if(isset($data['status_search'])){
			if(!empty($data['status_search'])) {
				$sql .= " and a.status_id in(".$data['status_search'].")";
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
			
			//echo $sql."<br>";
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
			if($payment_option == 89 || $payment_option == 90 || $payment_option == 91 or $payment_option == 145) {
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
			if($payment_option == 89 || $payment_option == 90 || $payment_option == 91 or $payment_option == 145) {
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
		
		$sql = "select a.order_id, a.customer_name, b.username order_username,c.description status
					   ,case when m.item_id then concat(m.item_name,'-',l.quantity) else concat(m.item_name,'-',l.quantity) end item			   
					   ,a.date_added,  a.paid_date,a.packed_date, a.actual_delivery_date	   
					   ,case when a.paid_flag = 1 then 'Paid' else 'Not Paid' end paid_flag
					   ,concat(a.address, ', ', k.description, ', ', j.description, ', ', i.description) address
					   ,a.contact, e.description mod_desc
					   ,n.description send_to, a.delivery_fee, a.discount, a.amount total
					   ,d.description payment_option_desc, a.ref,  a.notes ,a.remarks
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
				 where a.status_id > 0";
		
		if($data['type'] == "cancelledorders") {
			$sql .= " and a.status_id in(19,113) ";
		} else if($data['type'] == "processedorders") {
			$sql .= " and a.status_id in(35,112) ";
		} else if($data['type'] == "orders") {
			$sql .= " and a.status_id in(139) ";
		}
		
		if($this->user->getUserGroupId() == 39 or $this->user->getUserGroupId() == 13) {
			$sql .= " and a.city_distributor_id = ".$this->user->getId();
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
							$this->tagSoldInventory($order_id, 119);
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
							$this->tagSoldInventory($order_id, 125);
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
		// var_dump($data);
		$return_msg = "";
		$valid = 1;
		$order_id = $data['order_id'];
		
		$sql = "select a.status_id, a.paid_flag, b.grouping2, a.operator_id, a.user_group_id,
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
						  ,paid_date = '".$this->user->now()."'
					 where order_id = ".$order_id;
			$this->db->query($sql);
			$return_msg .= "Order Id ".$order_id." payment details is updated.<br>";
		} else {
			$return_msg .= "Order Id ".$order_id." is already paid.<br>";
		}
		// return $return_msg;
	}
	
	public function paymentConfirmed($data){
		
		$sql = "select status_id
				  from oc_order
				 where order_id = ".$data['order_id'];
		$query = $this->db->query($sql);
		$from_status_id = $query->row['status_id'];
		
		if($from_status_id == 123) {
			
			$order_id = $data['order_id'];
			
			$sql = "update oc_order
						set status_id = 125,
							payment_confirmed_date = '".$this->user->now()."'
					  where order_id = ".$order_id;
			$query = $this->db->query($sql);
			
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
			
			$order = array();
			$order['order_id'] = $order_id;
			$this->distributeCommission($order);
			
			return "Order Id ".$order_id." is updated to Payment Confirmed.<br>";
		}
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
							
							$return_msg .= "Order Id ".$order_id." is successfully Packed.<br>";
						} else {
							$return_msg .= "Order Id ".$order_id." cannot be proccessed due to insufficient inventory.<br>";
						}
					} elseif($status_id == 0) {
						$return_msg .= "Order Id ".$order_id." must be Checkout first.<br>";
					} else {
						$return_msg .= "Order Id ".$order_id." is already Packed.<br>";
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
				$valid = 1;
				if($payment_option == 90 || $payment_option == 91 || $payment_option == 94 
				|| $payment_option == 100 || $payment_option == 106 || $payment_option == 107 
				|| $payment_option == 108 || $payment_option == 145) {
					
					if($status_id == 125) {
						if(isset($data['ship_tracking_input'.$order_id])) {
							if(empty($data['ship_tracking_input'.$order_id])) {
								$valid = 0;
								$return_msg .= "Order Id ".$order_id." is not shipped because you did not put tracking number.<br>";
							}
						} else {
							$valid = 0;
							$return_msg .= "Order Id ".$order_id." because you did not put tracking number.<br>";
						}
						
						if($valid == 1) {
							$valid = $this->assembleItems($order_id, 126);
							//die("valid = ".$valid);
							if($valid == 0) {
								$return_msg .= "Order Id ".$order_id." is not shipped because of insufficient inventory.<br>";
							}
						}

						if($valid == 1) {
							$sql = "update oc_order	
									   set status_id = 126
										  ,shipped_date = '".$this->user->now()."'
										  ,tracking = '".$data['ship_tracking_input'.$order_id]."'
									 where order_id = ".$order_id;
							$this->db->query($sql);
							
							
							
							if($this->user->isLogged()) {
								$user_id = $this->user->getId();
							} else {
								$user_id = 0;
							}
							
							$sql = "insert into oc_order_hist_tbl
										set user_id = ".$user_id.",
											order_id = ".$order_id.",
											from_status_id = ".$status_id.",
											to_status_id = 126,
											remarks = 'Shipped',
											date_added = '".$this->user->now()."' ";
							$this->db->query($sql);
							
							$return_msg .= "Order Id ".$order_id." is successfully tagged as shipped.<br>";
						} 
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
				$sql = "select status_id,user_id, paid_flag, branch_id, inventoriable, payment_option,amount
						from oc_order  
					  where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$status_id = $query->row['status_id'];
				$paid_flag = $query->row['paid_flag'];
				$branch_id = $query->row['branch_id'];
				$inventoriable = $query->row['inventoriable'];
				$payment_option = $query->row['payment_option'];
				$refund_amount = $query->row['amount'];
				$user_refunded = $query->row['user_id'];
				
				
				if(($status_id == 18 or $status_id == 34 or $status_id == 127) and ($paid_flag == 0)) {
					
					if($status_id == 34 and $inventoriable == 1) {
						$this->log->write("calling disassembleItem");
						$this->disassembleItem($order_id, 113);	
					} 
					
					$sql = "update oc_order	
							   set status_id = 113
								,inventoriable = 0
								,cancel_date = '".$this->user->now()."'
						     where order_id = ".$order_id;
					$this->db->query($sql);
				
					$from_status_id = $status_id;
					
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
					
					if($payment_option == 157){ //COP PAYMENT_OPTION
						$this->insertEwallet($refund_amount, $user_refunded, $user_refunded, $order_id, 41, $this->user->getId(), 0);
						// insertEwallet($income, $user_id, $source_user_id, $order_id, $commission_type_id, $created_by, $tax_flag = 0) 
					}
				
					$return_msg .= "Order ID ".$order_id." is updated to Cancelled.<br>";
				} else {
					$return_msg .= "Cancellation of order id ".$order_id." failed. Only orders in the status for Pick Up can be cancelled.<br>";
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
					
					if($status_id == 34 and $inventoriable == 1) {
						$this->disassembleItem($order_id, 118);	
					}
						
					$sql = "update oc_order	
							   set status_id = 118 
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
				$sql = "select user_id,status_id, paid_flag,payment_option,amount from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$status_id = $query->row['status_id'];
				$paid_flag = $query->row['paid_flag'];
				$refund_amount = $query->row['amount'];
				$user_refunded = $query->row['user_id'];
				$payment_option = $query->row['payment_option'];
				
				if(($status_id == 18 or $status_id == 34 or $status_id == 138 or $status_id == 0 or $status_id == 127) and ($paid_flag == 0)) {
					$sql = "update oc_order	
							   set status_id = 19 
								  ,cancel_date = '".$this->user->now()."'
						     where order_id = ".$order_id;
					$this->db->query($sql);
					
					if($this->user->isLogged()) {
						$user_id = $this->user->getId();
					} else {
						$user_id = 0;
					}
					
					$sql = "insert into oc_order_hist_tbl
								set user_id = ".$user_id.",
									order_id = ".$order_id.",
									from_status_id = ".$status_id.",
									to_status_id = 19,
									remarks = 'Cancelled by Customer',
									date_added = '".$this->user->now()."' ";
					$this->db->query($sql);
					
					if($payment_option == 158 or $payment_option == 157){ //COP PAYMENT_OPTION or COD PAYMENT OPTION
						$this->insertEwallet($refund_amount, $user_refunded, $user_refunded, $order_id, 41, $this->user->getId(), 0);
						// insertEwallet($income, $user_id, $source_user_id, $order_id, $commission_type_id, $created_by, $tax_flag = 0) 
					}
					//kunin ang item_id
					$sql ="SELECT item_id from oc_order_details where order_id = ".$order_id;
					$query = $this->db->query($sql);
					$item_id = $query->row['item_id'];
					
					//magbabawas sa sales encoded ng user then iupdate nya rin ung date
					$sql ="update oc_sales_encoded
							  set sales_today = sales_today - ".$refund_amount."
								   ,sales_week  = sales_week - ".$refund_amount."
								   ,sales_month  = sales_month - ".$refund_amount."
								   ,sales_year  = sales_year - ".$refund_amount."
								   ,date_added ='".$this->user->now()."' 
							where user_id = ".$user_id."
					   	      and item_id = ".$item_id;
					$this->db->query($sql);
					
					$return_msg .= "Order ID ".$order_id." is cancelled.<br>";
				} else {
					$return_msg .= "Cancellation of order id ".$order_id." failed. Only Orders in the status of Pending can be cancelled.<br>";
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
							   province_id, city_municipality_id, barangay_id, send_to,amount, delivery_option, payment_option
						  from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$order = $query->row;
				$status_id = $query->row['status_id'];
				$user_id = $query->row['user_id'];
				$reseller_id = $query->row['reseller_id'];
				$paid_flag = $query->row['paid_flag'];
				$delivery_option = $query->row['delivery_option'];
				$payment_option = $query->row['payment_option'];
				$amount_total = $query->row['amount'];
				
				if($payment_option == 147 || $payment_option == 157) {
					// if($paid_flag == 1){
					if($status_id == 127){
						
						$valid = $this->assembleItems($order_id, 152);
						if($valid == 1) {
							
							if($payment_option == 157) { //kapag payment option nya is 158 ilalagay nya ung nabawas sa distributor o reseller at ilalagay kung sino mang operator
								$sql = "select a.status_id, a.user_id, a.payment_option, a.city_distributor_id,a.amount amount_total
										from oc_order a
										where order_id = ".$order_id;
								$query = $this->db->query($sql);
								$city_distributor_id = $query->row['city_distributor_id'];
								$order_from = $query->row['user_id'];
								
								$this->insertEwallet($amount_total, $city_distributor_id, $order_from, $order_id, 40, $this->user->getId(), 0);
								 // insertEwallet($income, $user_id, $source_user_id, $order_id, $commission_type_id, $created_by, $tax_flag = 0) 
							}
							
							$sql = "update oc_order	
									   set status_id = 152
										  ,paid_flag = 1
										  ,paid_date = '".$this->user->now()."'
										  ,pickedup_date = '".$this->user->now()."'
									 where order_id = ".$order_id;
							$this->db->query($sql);
						
							$from_status_id = $status_id;
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
											to_status_id = 152,
											remarks = 'Picked-Up',
											date_added = '".$this->user->now()."' ";
							$this->db->query($sql);
							
							$order = array();
							$order['order_id'] = $order_id;
							$this->distributeCommission($order);
							
							$this->recordInventorySold($order_id, 35);
							
							$return_msg .= "Order Id ".$order_id." is updated to Picked Up.<br>";
						}  else {
							$return_msg .= "Order Id ".$order_id." cannot be proccessed due to insufficient inventory.<br>";
						}
					} elseif($status_id == 152) {
						$return_msg .= "Order Id ".$order_id." is already Pickedup.<br>";
					} else {
						$return_msg .= "Order Id ".$order_id." must be shipped first.<br>";
					}
				} else {
					$return_msg .= "Order Id ".$order_id." picked up status is not applicable for COD.<br>";
				}
			}
		} else {
			$return_msg .= "No order selected to pick up.";
		}
		return $return_msg;
	}
	
	public function tagDropshipOrderAsDelivered($data) {
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
				
				if($payment_option == 90 || $payment_option == 91 || $payment_option == 100 
				  || $payment_option == 106 || $payment_option == 107 || $payment_option == 108 || $payment_option == 145) {
					if($status_id == 126) {
						$sql = "update oc_order	
							   set status_id = 35
								  ,actual_delivery_date = '".$this->user->now()."'
						     where order_id = ".$order_id;
						$this->db->query($sql);
						// echo $sql .'update sa deliver<br>';
						if($this->user->isLogged()) {
							$user_id = $this->user->getId();
						} else {
							$user_id = 0;
						}
						
						$sql = "insert into oc_order_hist_tbl
									set user_id = ".$user_id.",
										order_id = ".$order_id.",
										from_status_id = ".$status_id.",
										to_status_id = 35,
										remarks = 'Delivered',
										date_added = '".$this->user->now()."' ";
						$this->db->query($sql);					
						
						$this->recordInventorySold($order_id, 35);
						
						$return_msg .= "Order Id ".$order_id." is updated to Delivered.";
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
	
	public function tagOrderAsAccepted($data) {
			$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, user_id, payment_option
						  from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$order = $query->row;
				$status_id = $query->row['status_id'];
				$payment_option = $query->row['payment_option'];
				
				if($payment_option == 146 || $payment_option == 145 || $payment_option == 148 || $payment_option == 147 || $payment_option == 158) {
					// if($paid_flag == 1){
					if($status_id == 138){
						$sql = "update oc_order	
								   set status_id = 142
								 where order_id = ".$order_id;
						$this->db->query($sql);
						
						if($this->user->isLogged()) {
							$user_id = $this->user->getId();
						} else {
							$user_id = 0;
						}
						
						$sql = "insert into oc_order_hist_tbl
									set user_id = ".$user_id.",
										order_id = ".$order_id.",
										from_status_id = ".$status_id.",
										to_status_id = 142,
										remarks = 'Accepted',
										date_added = '".$this->user->now()."' ";
						$this->db->query($sql);
						// echo $sql .'accept<br>';
						$return_msg .= "Order Id ".$order_id." is updated to Accepted.<br>";
					} elseif($status_id == 142) {
						$return_msg .= "Order Id ".$order_id." is already Accepted.<br>";
					} else {
						$return_msg .= "Accepting of order id ".$order_id." failed. Only Pending Orders can be Accepted.<br>";
					}
				
				}
			}
		} else {
			$return_msg .= "No order selected to be accept.";
		}
		return $return_msg;
	}
	
	public function tagOrderAsConfirmed($data) {
			$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, user_id
						  from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$order = $query->row;
				$status_id = $query->row['status_id'];
				
					
					if($status_id == 142){
						$sql = "update oc_order	
								   set status_id = 151
								 where order_id = ".$order_id;
						$this->db->query($sql);
						
						if($this->user->isLogged()) {
							$user_id = $this->user->getId();
						} else {
							$user_id = 0;
						}
						
						$sql = "insert into oc_order_hist_tbl
									set user_id = ".$user_id.",
										order_id = ".$order_id.",
										from_status_id = ".$status_id.",
										to_status_id = 151,
										remarks = 'Confirmed',
										date_added = '".$this->user->now()."' ";
						$this->db->query($sql);
						// echo $sql .'confirm<br>';
						$return_msg .= "Order Id ".$order_id." is updated to Confirmed.<br>";
					} elseif($status_id == 151) {
						$return_msg .= "Order Id ".$order_id." is already Confirmed.<br>";
					} else {
						$return_msg .= "Confirm of order id ".$order_id." failed. Only Accepted Orders can be Confirmed.<br>";
					}
				
				
			}
		} else {
			$return_msg .= "No order selected to tag as Confirmed.<br>";
		}
		return $return_msg;
	
	}
	
	public function tagOrderAsImHere($data) {
			$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, user_id
						  from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$order = $query->row;
				$status_id = $query->row['status_id'];
				
					
					if($status_id == 151){
						$sql = "update oc_order	
								   set status_id = 144
								 where order_id = ".$order_id;
						$this->db->query($sql);
						
						if($this->user->isLogged()) {
							$user_id = $this->user->getId();
						} else {
							$user_id = 0;
						}
						
						$sql = "insert into oc_order_hist_tbl
									set user_id = ".$user_id.",
										order_id = ".$order_id.",
										from_status_id = ".$status_id.",
										to_status_id = 144,
										remarks = 'Im Here',
										date_added = '".$this->user->now()."' ";
						$this->db->query($sql);
						// echo $sql .'im here<br>';
						$return_msg .= "Order Id ".$order_id." is updated to Im Here.<br>";
					} elseif($status_id == 144) {
						$return_msg .= "Order Id ".$order_id." is already in Im Here status.<br>";
					} else {
						$return_msg .= "Tagging Im here of order id ".$order_id." failed. Only Confirmed Orders can be tag as Im Here.<br>";
					}
				
				
			}
		} else {
			$return_msg .= "No order selected to tag as Im Here.";
		}
		return $return_msg;
	}
	
	public function tagOrderAsItemPrepared($data) {
			$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "SELECT status_id, city_distributor_id
						  FROM oc_order 
						 WHERE order_id = ".$order_id;
				$query = $this->db->query($sql);
				$order = $query->row;
				$operator_id = $query->row['city_distributor_id'];
				$status_id = $query->row['status_id'];
				
					if($status_id == 144){
						
						$valid = $this->assembleItems($order_id, 150);
						if($valid == 1) {
							$sql = "update oc_order	
									   set status_id = 150
									  ,packed_date = '".$this->user->now()."'
									  ,inventoriable = 1
									 where order_id = ".$order_id;
							$this->db->query($sql);
							
							if($this->user->isLogged()) {
								$user_id = $this->user->getId();
							} else {
								$user_id = 0;
							}
							
							$sql = "insert into oc_order_hist_tbl
										set user_id = ".$user_id.",
											order_id = ".$order_id.",
											from_status_id = ".$status_id.",
											to_status_id = 150,
											remarks = 'Item Prepared',
											date_added = '".$this->user->now()."' ";
							$this->db->query($sql);
							// echo $sql .'itemprepare<br>';
							$return_msg .= "Order Id ".$order_id." is updated to Item Prepared.<br>";
						} else {
							$return_msg .= "Order Id ".$order_id." cannot be proccessed due to insufficient inventory.<br>";
						}
					} elseif($status_id == 150) {
						$return_msg .= "Order Id ".$order_id." is already in Item Prepared status.<br>";
					} else {
						$return_msg .= "Preparing Item of order id ".$order_id." failed. Only tag as Im Here Orders can be Prepared.<br>";
					}
			}
		} else {
			$return_msg .= "No order selected to tag as Item Prepare.";
		}
		return $return_msg;
	}
	
	public function tagOrderAsItemPickUp($data) {
			$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, user_id
						  from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$order = $query->row;
				$status_id = $query->row['status_id'];
				
					
					if($status_id == 150){
						$sql = "update oc_order	
								   set status_id = 139
								 where order_id = ".$order_id;
						$this->db->query($sql);
						
						if($this->user->isLogged()) {
							$user_id = $this->user->getId();
						} else {
							$user_id = 0;
						}
						
						$sql = "insert into oc_order_hist_tbl
									set user_id = ".$user_id.",
										order_id = ".$order_id.",
										from_status_id = ".$status_id.",
										to_status_id = 139,
										remarks = 'Item Pick up',
										date_added = '".$this->user->now()."' ";
						$this->db->query($sql);
					// echo $sql .'Item pick up<br>';
						$return_msg .= "Order Id ".$order_id." is updated to Item Pick Up.<br>";
					} elseif($status_id == 139) {
						$return_msg .= "Pick Up of order id ".$order_id." failed. Only Packed Orders can be Pick Up.<br>";
					} else {
						$return_msg .= "Order Id ".$order_id." must be in Item Prepared status.<br>";
					}
				
				
			}
		} else {
			$return_msg .= "No order selected to tag as Item Pick up.";
		}
		return $return_msg;
	}
	
	public function tagOrderAsReturned($data) {
			$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, city_distributor_id, inventoriable, paid_flag
						  from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$order = $query->row;
				$inventoriable = $query->row['inventoriable'];
				$paid_flag = $query->row['paid_flag'];
				$status_id = $query->row['status_id'];
				$operator_id = $query->row['city_distributor_id'];
				
				if(($status_id == 139 or $status_id = 150) and ($paid_flag == 0)) {
				
					if(($status_id == 139 or $status_id = 150) and $inventoriable == 1) {
						$this->log->write("calling disassembleItems");
						$this->disassembleItems($order_id, 118);	
					} 
					
					$sql = "update oc_order	
							   set status_id = 118
							   ,cancel_date = '".$this->user->now()."'
							 where order_id = ".$order_id;
					$this->db->query($sql);
					
					if($this->user->isLogged()) {
						$user_id = $this->user->getId();
					} else {
						$user_id = 0;
					}
					
					$sql = "insert into oc_order_hist_tbl
								set user_id = ".$user_id.",
									order_id = ".$order_id.",
									from_status_id = ".$status_id.",
									to_status_id = 118,
									remarks = 'Returned',
									date_added = '".$this->user->now()."' ";
					$this->db->query($sql);
					
					$sql = "update oc_order	
							   set status_id = 138
							   ,cancel_date = '".$this->user->now()."'
							 where order_id = ".$order_id;
					$this->db->query($sql);
					
					$sql = "insert into oc_order_hist_tbl
								set user_id = ".$user_id.",
									order_id = ".$order_id.",
									from_status_id = 118,
									to_status_id = 138,
									remarks = 'Returned',
									date_added = '".$this->user->now()."' ";
					$this->db->query($sql);
					
					$return_msg .= "Order Id ".$order_id." is updated to Returned and auto escalated to Pending.<br>";
				} elseif($status_id == 0) {
					$return_msg .= "Order Id ".$order_id." must be in Pending status.<br>";
				} elseif($status_id == 0) {
					$return_msg .= "Order Id ".$order_id." must be in Pending status.<br>";
				} elseif($status_id == 118) {
					$return_msg .= "Order Id ".$order_id." is already return.<br>";
				} else {
					$return_msg .= "Return of order id ".$order_id." failed. Only Item Prepared and Item Picked Up Orders can be Returned.<br>";
				}			
			}
		} else {
			$return_msg .= "No order selected to Return.";
		}
		return $return_msg;

	}
	
	public function tagOrderAsDelivered($data) {
		$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select a.order_id,a.status_id, a.user_id,a.paid_flag,a.amount amount_total, a.payment_option, a.city_distributor_id, 
							   a.distributor_id, a.sales_rep_id, a.company_admin_id, a.customer_flag,
							   a.username, a.password, a.firstname, a.lastname
							from oc_order a
						where order_id = ".$order_id;
				//die($sql);
				$query = $this->db->query($sql);
				
				$order = $query->row;
				
				if($order['paid_flag'] == 0) {
					$from_status_id = $query->row['status_id'];
					$user_id = $query->row['user_id'];
					$city_distributor_id = $query->row['city_distributor_id'];
					$distributor_id = $query->row['distributor_id'];
					$sales_rep_id = $query->row['sales_rep_id'];
					$company_admin_id = $query->row['company_admin_id'];
					$customer_flag = $query->row['customer_flag'];
					$payment_option = $query->row['payment_option'];
					$amount_total = $query->row['amount_total'];
					
					if($payment_option == 146 || $payment_option == 148 || $payment_option == 145 || $payment_option == 147 || $payment_option == 158) { 
						if($from_status_id == 139){
							if($payment_option == 158) { //kapag payment option nya is 158 ilalagay nya ung nabawas sa distributor o reseller at ilalagay kung sino mang operator
								$this->insertEwallet($amount_total, $city_distributor_id, $user_id, $order_id, 40, $this->user->getId(), 0);
								 // insertEwallet($income, $user_id, $source_user_id, $order_id, $commission_type_id, $created_by, $tax_flag = 0) 
							}
							
							$this->performStatusUpdate($order, 35, 1);
							$order = array();
							$order['order_id'] = $order_id;
							$this->distributeCommission($order);
							$this->recordInventorySold($order_id, 35);
							
							$return_msg .= "Order Id ".$order_id." is updated to Delivered.<br>";
						} elseif($from_status_id == 138) {
							$return_msg .= "Order Id ".$order_id." must be Accept first.<br>";
						} elseif($from_status_id == 151) {
							$return_msg .= "Order Id ".$order_id." must be in Confirmed status.<br>";
						} elseif($from_status_id == 144) {
							$return_msg .= "Order Id ".$order_id." must be in Im Here status.<br>";
						} elseif($from_status_id == 150) {
							$return_msg .= "Order Id ".$order_id." must be in Item Prepared status.<br>";
						} elseif($from_status_id == 118) {
							$return_msg .= "Order Id ".$order_id." can't be delivered because it is already returned.<br>";
						} elseif($from_status_id == 0) {
							$return_msg .= "Order Id ".$order_id." must be in Checkout status.<br>";
						} else {
							$return_msg .= "Order Id ".$order_id." is already Delivered.<br>";
						}
					}
				}
			}
		} else {
			$return_msg .= "No order selected to Deliver.";
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
				$status_ids = "138,142,139,151,118,150,144,124,126,127";
			} elseif($data['type'] == "processedorders") {
				$status_ids = "119,125,35,152";
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
			$sql = "SELECT sum(amount) total FROM oc_order WHERE status_id IN (".$status_ids.") and city_distributor_id = ".$this->user->getId()." or user_id = ".$this->user->getId()." and status_id IN (".$status_ids.") ";
		} elseif($this->user->getUserGroupId() == 45) {
			$sql = "SELECT sum(amount) total FROM oc_order WHERE status_id IN (".$status_ids.") and sales_rep_id = ".$this->user->getId()." or user_id = ".$this->user->getId();
		} elseif($this->user->getUserGroupId() == 56) {
			$sql = "SELECT sum(amount) total FROM oc_order WHERE status_id IN (".$status_ids.") and user_id = ".$this->user->getId();
		} elseif($this->user->getUserGroupId() == 46 || $this->user->getUserGroupId() == 47) {
			$sql = "SELECT sum(amount) total FROM oc_order WHERE status_id IN (".$status_ids.") and user_id = ".$this->user->getId()." and status_id IN (".$status_ids.") ";
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
				$status_ids = "138,142,139,151,118,150,144,124,126,127";
			} elseif($data['type'] == "processedorders") {
				$status_ids = "119,125,35,152";
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
			$sql = "SELECT count(1) count FROM oc_order WHERE status_id IN (".$status_ids.") and city_distributor_id = ".$this->user->getId()." or user_id = ".$this->user->getId()." and status_id IN (".$status_ids.") ";
		} elseif($this->user->getUserGroupId() == 45) {
			$sql = "SELECT count(1) count FROM oc_order WHERE status_id IN (".$status_ids.") and sales_rep_id = ".$this->user->getId()." or user_id = ".$this->user->getId();
		} elseif($this->user->getUserGroupId() == 56) {
			$sql = "SELECT count(1) count FROM oc_order WHERE status_id IN (".$status_ids.") and user_id = ".$this->user->getId();
		} elseif($this->user->getUserGroupId() == 46 || $this->user->getUserGroupId() == 47) {
			$sql = "SELECT count(1) count FROM oc_order WHERE status_id IN (".$status_ids.") and user_id = ".$this->user->getId()." and status_id IN (".$status_ids.") ";
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
		$order_id = $o['order_id'];
		if($this->user->isLogged()) {
			$created_by = $this->user->getId();
		} else {
			$created_by = 0;
		}
		
		$sql = "select a.status_id, a.user_id, a.payment_option, a.city_distributor_id, 
					   a.distributor_id, a.sales_rep_id, a.company_admin_id, a.customer_flag,
					   a.username, a.password, a.firstname, a.lastname, a.send_to
				  from oc_order a
				 where order_id = ".$order_id;
		$query = $this->db->query($sql);
		$order = $query->row;
		
		$from_status_id = $query->row['status_id'];
		$user_id = $query->row['user_id'];
		$city_distributor_id = $query->row['city_distributor_id'];
		$distributor_id = $query->row['distributor_id'];
		$sales_rep_id = $query->row['sales_rep_id'];
		$company_admin_id = $query->row['company_admin_id'];
		$customer_flag = $query->row['customer_flag'];
		$send_to = $query->row['send_to'];
		$payment_option = $query->row['payment_option'];
		
		$sql = "select b.price cost, a.quantity, a.date_added,b.category_id,c.description, c.city_percentage, b.advance_payment
					  ,b.distributor_discount_per,b.reseller_discount_per, b.user_group_id, b.item_id, b.direct_referral, b.cv
					  ,b.advance_payment
				from oc_order_details a
				left join gui_items_tbl b on (a.item_id = b.item_id)
				left join gui_category_tbl c on (b.category_id = c.category_id)
				where order_id = ".$order_id;
		$query = $this->db->query($sql);	
		$ord_dtls = $query->rows;
				
		foreach($ord_dtls as $ord) {
			$category_id = $ord['category_id'];
			$user_group_id = $ord['user_group_id'];
			$cost = $ord['cost'];
			$quantity = $ord['quantity'];
			$item_cv = $ord['quantity'] * $ord['cv'];
			$city_percentage = $ord['city_percentage'];
			$advance_payment = $ord['advance_payment'];
			$dist_disc_percentage = $ord['distributor_discount_per'];
			$res_disc_percentage = $ord['reseller_discount_per'];
			$dist_res_diff_percentage = $ord['distributor_discount_per'] - $ord['reseller_discount_per'];
			
			if($customer_flag == 1) { //kung customer or hindi nakalogin ang nagorder
				if($category_id == 2) { //if retail
					if($sales_rep_id > 0) { 
						//dahil customer walang discount so walang advance payment refund
						//pag may sales rep or reseller 
						//bigyan commission reseller
						if($res_disc_percentage > 0) {
							$this->insertEwallet(($quantity * $cost * $res_disc_percentage / 100), $sales_rep_id, $user_id, $order_id, 33, $created_by, 1);
						}
						//bigyan commission distributor
						if($dist_res_diff_percentage > 0) {
							$this->insertEwallet(($quantity * $cost * $dist_res_diff_percentage / 100), $distributor_id, $user_id, $order_id, 28, $created_by, 1);
						}
					} else if($company_admin_id > 0) { 
						//dahil customer walang discount so walang advance payment refund
						//pag may sales rep or reseller 
						//bigyan commission distributor	
						if($dist_disc_percentage > 0) {
							$this->insertEwallet(($quantity * $cost * $dist_disc_percentage / 100), $distributor_id, $user_id, $order_id, 25, $created_by, 1);
						}
					} else { //distributor at kasama na dito ung admin ng distributor
						//dahil customer walang discount so walang advance payment refund
						//bigyan commission distributor
						if($dist_disc_percentage > 0) {
							$this->insertEwallet(($quantity * $cost * $dist_disc_percentage / 100), $distributor_id, $user_id, $order_id, 30, $created_by, 1);
						}
					}
				} else if($category_id == 6) { //if promo
					//may advance payment refund, dahil discounted pagkabili dito, ung buong dist disc kc naibigay na ung sa reseller
					if($advance_payment > 0) {
						$this->insertEwallet(($quantity * $advance_payment), $city_distributor_id, $user_id, $order_id, 27, $created_by, 0);
					}
					if($sales_rep_id > 0) { 
						//dahil customer walang discount so walang advance payment refund
						//pag may sales rep or reseller 
						//bigyan commission reseller
						if($res_disc_percentage > 0) {
							$this->insertEwallet(($quantity * $cost * $res_disc_percentage / 100), $sales_rep_id, $user_id, $order_id, 33, $created_by, 1);
						}
						//bigyan commission distributor
						if($dist_res_diff_percentage > 0) {
							$this->insertEwallet(($quantity * $cost * $dist_res_diff_percentage / 100), $distributor_id, $user_id, $order_id, 28, $created_by, 1);
						}
					} else if($company_admin_id > 0) { 
						//dahil customer walang discount so walang advance payment refund
						//pag may sales rep or reseller 
						//bigyan commission distributor	
						if($dist_disc_percentage > 0) {
							$this->insertEwallet(($quantity * $cost * $dist_disc_percentage / 100), $distributor_id, $user_id, $order_id, 25, $created_by, 1);
						}
					} else { //distributor at kasama na dito ung admin ng distributor
						//dahil customer walang discount so walang advance payment refund
						//bigyan commission distributor
						if($dist_disc_percentage > 0) {
							$this->insertEwallet(($quantity * $cost * $dist_disc_percentage / 100), $distributor_id, $user_id, $order_id, 30, $created_by, 1);
						}
					}	
				} else { //if package
					//may advance payment refund, dahil discounted pagkabili dito, ung buong dist disc kc naibigay na ung sa reseller
					if($advance_payment > 0) {
						$this->insertEwallet(($quantity * $advance_payment), $city_distributor_id, $user_id, $order_id, 27, $created_by, 0);
					}
					//gumawa ng serials kung may dapat macreate na user ung package
					if($user_group_id > 0) {
						if($sales_rep_id > 0) {
							for($i=1; $i<=$ord['quantity']; $i++) {
								$serial = $this->user->Random("oc_serials", "serial_code", 8);
								$sql = "insert into oc_serials(serial_code, item_id, order_user_id, order_id, date_added)
										values ('".$serial."', ".$ord['item_id'].", ".$sales_rep_id.", ".$order_id.", '".$this->user->now()."')";
								$this->db->query($sql);
							}
						// } else if($company_admin_id > 0) { 
							// for($i=1; $i<=$ord['quantity']; $i++) {
								// $serial = $this->user->Random("oc_serials", "serial_code", 8);
								// $sql = "insert into oc_serials(serial_code, item_id, order_user_id, order_id, date_added)
										// values ('".$serial."', ".$ord['item_id'].", ".$company_admin_id.", ".$order_id.", '".$this->user->now()."')";
								// $this->db->query($sql);
							// }
						} else { //distributor at kasama na dito ung admin ng distributor
							for($i=1; $i<=$ord['quantity']; $i++) {
								$serial = $this->user->Random("oc_serials", "serial_code", 8);
								$sql = "insert into oc_serials(serial_code, item_id, order_user_id, order_id, date_added)
										values ('".$serial."', ".$ord['item_id'].", ".$distributor_id.", ".$order_id.", '".$this->user->now()."')";
								$this->db->query($sql);
							}
						}
					} else {
						if($sales_rep_id > 0) { 
							//dahil customer walang discount so walang advance payment refund
							//pag may sales rep or reseller 
							//bigyan commission reseller
							if($res_disc_percentage > 0) {
								$this->insertEwallet(($quantity * $cost * $res_disc_percentage / 100), $sales_rep_id, $user_id, $order_id, 33, $created_by, 1);
							}
							//bigyan commission distributor
							if($dist_res_diff_percentage > 0) {
								$this->insertEwallet(($quantity * $cost * $dist_res_diff_percentage / 100), $distributor_id, $user_id, $order_id, 28, $created_by, 1);
							}
						} else if($company_admin_id > 0) { 
							//dahil customer walang discount so walang advance payment refund
							//pag may sales rep or reseller 
							//bigyan commission distributor	
							if($dist_disc_percentage > 0) {
								$this->insertEwallet(($quantity * $cost * $dist_disc_percentage / 100), $distributor_id, $user_id, $order_id, 25, $created_by, 1);
							}
						} else { //distributor at kasama na dito ung admin ng distributor
							//dahil customer walang discount so walang advance payment refund
							//bigyan commission distributor
							if($dist_disc_percentage > 0) {
								$this->insertEwallet(($quantity * $cost * $dist_disc_percentage / 100), $distributor_id, $user_id, $order_id, 30, $created_by, 1);
							}
						}
					}
				}
			} else { //hindi customer, mga nakalogin na nagcreate ng order
				if($category_id == 2) { //if retail
					if($sales_rep_id > 0) { 
						//pag may sales rep or reseller 
						//if send to my customer
						if($send_to == 111) {
							//bigyan commission reseller
							if($res_disc_percentage > 0) {
								$this->insertEwallet(($quantity * $cost * $res_disc_percentage / 100), $sales_rep_id, $user_id, $order_id, 33, $created_by, 1);
							}
						}
						
						//bigyan commission distributor, discounted na ang reseller kaya instant, dist ang bibigyan ng commission
						if($dist_res_diff_percentage > 0) {
							$this->insertEwallet(($quantity * $cost * $dist_res_diff_percentage / 100), $distributor_id, $user_id, $order_id, 29, $created_by, 1);
						}	
					} else { //admin or dist own order
						//wala na commission kc discounted na
						//may advance payment refund, dahil discounted pagkabili dito
						if($send_to == 111) {
							//bigyan commission reseller
							if($dist_disc_percentage > 0) {
								$this->insertEwallet(($quantity * $cost * $dist_disc_percentage / 100), $distributor_id, $user_id, $order_id, 30, $created_by, 1);
							}
						}
					}

					if($send_to != 111) {
						//may advance payment refund, dahil discounted pagkabili dito, ung buong dist disc kc naibigay na ung sa reseller
						if($dist_disc_percentage > 0) {
							$this->insertEwallet(($quantity * $cost * $dist_disc_percentage / 100), $city_distributor_id, $user_id, $order_id, 27, $created_by, 0);
						}
					}
				} else if($category_id == 6) { //if promo
					//may advance payment refund, dahil discounted pagkabili dito, ung buong dist disc kc naibigay na ung sa reseller
					if($advance_payment > 0) {
						$this->insertEwallet(($quantity * $advance_payment), $city_distributor_id, $user_id, $order_id, 27, $created_by, 0);
					}
					
					if($sales_rep_id > 0) { 
						//pag may sales rep or reseller 
						//if send to my customer
						if($send_to == 111) {
							//bigyan commission reseller
							if($res_disc_percentage > 0) {
								$this->insertEwallet(($quantity * $cost * $res_disc_percentage / 100), $sales_rep_id, $user_id, $order_id, 33, $created_by, 1);
							}
						}
						
						//bigyan commission distributor, discounted na ang reseller kaya instant, dist ang bibigyan ng commission
						if($dist_res_diff_percentage > 0) {
							$this->insertEwallet(($quantity * $cost * $dist_res_diff_percentage / 100), $distributor_id, $user_id, $order_id, 29, $created_by, 1);
						}	
					} else { //admin or dist own order
						//wala na commission kc discounted na
						//may advance payment refund, dahil discounted pagkabili dito
						if($send_to == 111) {
							//bigyan commission reseller
							if($dist_disc_percentage > 0) {
								$this->insertEwallet(($quantity * $cost * $dist_disc_percentage / 100), $distributor_id, $user_id, $order_id, 30, $created_by, 1);
							}
						}
					}

					if($send_to != 111) {
						//may advance payment refund, dahil discounted pagkabili dito, ung buong dist disc kc naibigay na ung sa reseller
						if($dist_disc_percentage > 0) {
							$this->insertEwallet(($quantity * $cost * $dist_disc_percentage / 100), $city_distributor_id, $user_id, $order_id, 27, $created_by, 0);
						}
					}	
				} else { //if package
					//may advance payment refund, dahil discounted pagkabili dito, ung buong dist disc kc naibigay na ung sa reseller
					if($advance_payment > 0) {
						$this->insertEwallet(($quantity * $advance_payment), $city_distributor_id, $user_id, $order_id, 27, $created_by, 0);
					}
					//gumawa ng serials kung may dapat macreate na user ung package
					if($user_group_id > 0) {
						//resellers
						if($sales_rep_id > 0) {
							for($i=1; $i<=$ord['quantity']; $i++) {
								$serial = $this->user->Random("oc_serials", "serial_code", 8);
								$sql = "insert into oc_serials(serial_code, item_id, order_user_id, order_id, date_added)
										values ('".$serial."', ".$ord['item_id'].", ".$sales_rep_id.", ".$order_id.", '".$this->user->now()."')";
								$this->db->query($sql);
							}
						//admins ng distributor
						// } else if($company_admin_id > 0) { 
							// for($i=1; $i<=$ord['quantity']; $i++) {
								// $serial = $this->user->Random("oc_serials", "serial_code", 8);
								// $sql = "insert into oc_serials(serial_code, item_id, order_user_id, order_id, date_added)
										// values ('".$serial."', ".$ord['item_id'].", ".$company_admin_id.", ".$order_id.", '".$this->user->now()."')";
								// $this->db->query($sql);
							// }
						//distributors
						} else { //distributor at kasama na dito ung admin ng distributor
							for($i=1; $i<=$ord['quantity']; $i++) {
								$serial = $this->user->Random("oc_serials", "serial_code", 8);
								$sql = "insert into oc_serials(serial_code, item_id, order_user_id, order_id, date_added)
										values ('".$serial."', ".$ord['item_id'].", ".$distributor_id.", ".$order_id.", '".$this->user->now()."')";
								$this->db->query($sql);
							}
						}
					} else {
						if($sales_rep_id > 0) { 
							//pag may sales rep or reseller 
							//bigyan commission distributor, discounted na ang reseller kaya instant, dist ang bibigyan ng commission
							if($dist_res_diff_percentage > 0) {
								$this->insertEwallet(($quantity * $cost * $dist_res_diff_percentage / 100), $distributor_id, $user_id, $order_id, 29, $created_by, 1);
							}
						} else { //admin dist at admin parehas lang
							//wala na commission kc discounted na
							//may advance payment refund, dahil discounted pagkabili dito
							if($dist_disc_percentage > 0) {
								$this->insertEwallet(($quantity * $cost * $dist_disc_percentage / 100), $distributor_id, $user_id, $order_id, 30, $created_by, 1);
							}
						}
						//may advance payment refund, dahil discounted pagkabili dito, ung buong dist disc kc naibigay na ung sa reseller
						if($dist_disc_percentage > 0) {
							$this->insertEwallet(($quantity * $cost * $dist_disc_percentage / 100), $city_distributor_id, $user_id, $order_id, 27, $created_by, 0);
						}
					}
				}			
			}
			
			//record item_cv in user and order table
			if($item_cv > 0) {
				$sql = "update oc_user
						   set current_cv = current_cv + ".$item_cv."
						 where user_id = ".$user_id;
				$this->db->query($sql);
				
				$sql = "update oc_order
						   set total_cv = total_cv + ".$item_cv."
						 where order_id = ".$order_id;
				$this->db->query($sql);
				
				//determine the usergroup of the user
				$sql ="select user_id, user_group_id 
						 from oc_user 
						where user_id =	".$user_id;
				$query = $this->db->query($sql);
				$user_group = $query->row['user_group_id'];
				$user_of_order = $query->row['user_id'];
				
				//dapat ang usergroup ay 56 o 46
				if($user_group == 56 or $user_group == 46){
					//gagamitin ito para sa rank bonus ng mga higher ranking
					$sapphire_percentage = 0.05;
					$emerald_percentage = 0.05;
					$ruby_percentage = 0.03;
					$diamond_percentage = 0.02;
					
					
					//then mag bigyan sa ewallet ng as rebates , 20% item_cv of order
					$this->insertEwallet(($item_cv * 0.2), $user_of_order, $user_id, $order_id, 42, $created_by, 0);	
					//pag may distributor or reseller
					//bibigyan ng 10% ng cv ang 1st to 6th level of his/her upline 
					$sql = "select a.sponsor_user_id ,b.month1_cv, b.unilevel_req_exempted,b.username
							  from oc_unilevel a
							  join oc_user b on (a.sponsor_user_id = b.user_id)
							 where a.user_id = ".$user_id."
							   and (b.month1_cv > 105 or b.unilevel_req_exempted = 1 ) 
							 limit 6";
					$query = $this->db->query($sql);
					$sponsors = $query->rows;
					$level = 1;		
					
					foreach($sponsors as $sp){
						//bigyan ang mga sponsor upperline of the user
						$this->insertEwalletWithLevel(($item_cv * 0.1), $sp['sponsor_user_id'], $user_id, $order_id, 43, $created_by, 0,$level);	
						
						$level += 1;
					}
					
						//hahanapin nya ang sapphire executive
					$sql = "select count(1) total, a.sponsor_user_id
							  from oc_unilevel a
							  join oc_user b on (a.sponsor_user_id = b.user_id) 
							 where a.user_id = ".$user_id."
							   and b.rank_id = 2
							 order by level
							  limit 1";
							  
					$query = $this->db->query($sql);
					$sapphire_user_id = $query->row['sponsor_user_id'];
					$total_count = $query->row['total'];
					
					if($total_count > 0){
						//if meron bigay sa kanya ung rank bonus
						$this->insertEwallet(($item_cv * $sapphire_percentage), $sapphire_user_id, $user_id, $order_id, 44, $created_by, 0);	

						$sapphire_percentage = 0;//declare nya ang sapphire percentage sa zero if may nakuha na ung for sapphire executive
					}
					//hahanapin nya ang emerald executive
					$sql = "select count(1) total, a.sponsor_user_id
							  from oc_unilevel a
							  join oc_user b on (a.sponsor_user_id = b.user_id) 
							 where a.user_id = ".$user_id."
							   and b.rank_id = 3
							 order by level
							  limit 1";
							  
					$query = $this->db->query($sql);
					$emerald_user_id = $query->row['sponsor_user_id'];
					$total_count = $query->row['total'];
					
					if($total_count > 0){//if merong nakuhang sapphire executive
						//if meron bigay sa kanya ung rank bonus
						$emerald_percent = $emerald_percentage + $sapphire_percentage;
						$this->insertEwallet(($item_cv * $emerald_percent), $emerald_user_id, $user_id, $order_id, 44, $created_by, 0);	
						
						//declare nya ang sapphire at emerald percentage sa zero kasi nakuha na ni emerald
						$sapphire_percentage = 0;
						$emerald_percentage = 0;
					}
					//hahanapin naman nya ang ruby executive
					$sql = "select count(1) total, a.sponsor_user_id
							  from oc_unilevel a
							  join oc_user b on (a.sponsor_user_id = b.user_id) 
							 where a.user_id = ".$user_id."
							   and b.rank_id = 4
							 order by level
							  limit 1";
							  
					$query = $this->db->query($sql);
					$ruby_user_id = $query->row['sponsor_user_id'];
					$total_count = $query->row['total'];
					
					if($total_count > 0){//if merong nakitang ruby executive
						//if meron bigay sa kanya ung rank bonus
						$ruby_percent = $ruby_percentage + $emerald_percentage + $sapphire_percentage;
						$this->insertEwallet(($item_cv * $ruby_percent), $ruby_user_id, $user_id, $order_id, 44, $created_by, 0);	

						
						//declare nya ang sapphire at emerald at ruby percentage sa zero kasi nakuha na ni ruby
						$sapphire_percentage = 0;
						$emerald_percentage = 0;
						$ruby_percentage = 0;
					}
					//hahanapin naman nya ang diamond executive
					$sql = "select count(1) total, a.sponsor_user_id
							  from oc_unilevel a
							  join oc_user b on (a.sponsor_user_id = b.user_id) 
							 where a.user_id = ".$user_id."
							   and b.rank_id = 5
							 order by level
							  limit 1";
							  
					$query = $this->db->query($sql);
					$diamond_user_id = $query->row['sponsor_user_id'];
					$total_count = $query->row['total'];
					
					if($total_count > 0){//if merong diamond executive na nakuha 
						//if meron bigay sa kanya ung rank bonus
						$diamond_percent = $diamond_percentage + $ruby_percentage + $emerald_percentage + $sapphire_percentage;
						$this->insertEwallet(($item_cv * $diamond_percent), $diamond_user_id, $user_id, $order_id, 44, $created_by, 0);	
					}
				
				}
				
			}
			//pag may city distributor
			if($city_distributor_id > 0) {	
				//bigyan ang city distributor ng profit nila kahit ano pang type ng item yan
				if($city_percentage > 0) {
					$this->insertEwallet(($quantity * $cost * $city_percentage / 100), $city_distributor_id, $user_id, $order_id, 34, $created_by, 1);
				}
			}
		}
		if($from_status_id == 125){	
			//record sales inventory
			$sql = " insert into oc_sales_inventory(user_id, order_id, order_det_id, item_id, srp, tools, 
						service_fee, topup, cost, 
						income, tax, distributor_price, reseller_price, direct_referral, cv,system_fee,
						shipping, commissions, date_added)
					select  a.user_id, a.order_id, b.order_det_id, b.item_id, c.price*b.quantity srp, c.tools*b.quantity tools, 
						c.service_fee*b.quantity service_fee, c.top_up*b.quantity top_up, c.cost*b.quantity cost, 
						c.income*b.quantity income, c.tax*b.quantity tax,
						(c.price * (100 - c.distributor_discount_per) * b.quantity)/100 distributor_price, 
						(c.price * (100 - c.reseller_discount_per) * b.quantity)/100 reseller_price, 
						c.direct_referral*b.quantity direct_referral, c.cv*b.quantity cv,c.system_fee * b.quantity system_fee,
						c.shipping*b.quantity shipping, c.commissions*b.quantity commissions, a.payment_confirmed_date
					  from oc_order a
					  join oc_order_details b on (a.order_id = b.order_id)
					  join gui_items_tbl c on(b.item_id = c.item_id)
					 where a.order_id = ".$order_id;	 
			$this->db->query($sql);
			
			$sql = "select sum(c.system_fee * b.quantity) system_fee
					  from oc_order a
					  join oc_order_details b on (a.order_id = b.order_id)
					  join gui_items_tbl c on(b.item_id = c.item_id)
					 where a.order_id = ".$order_id;	 
			$query = $this->db->query($sql);
			$system_fee = $query->row['system_fee'];
			
			if($system_fee > 0) {
				$sql = "select user_id
						  from oc_user 
						 where user_group_id = 60
						   and `status` = 1 
						  limit 1 ";
				$query = $this->db->query($sql);
				if($query->row['user_id']) {
					$gniuse_admin_id = $query->row['user_id'];
					if($gniuse_admin_id > 0) {
						$this->insertEwallet($system_fee, $gniuse_admin_id, $user_id, $order_id, 46, $created_by, 0);
					}
				}
			}
		}
		
		//record to sales delivered status is delivered , pick up and payment confirmed
		$sql = "select a.user_id,b.item_id,c.price * b.quantity amount,d.user_group_id,a.send_to
						  ,ROUND(c.distributor_discount_per / 100 * c.price, 2)discount_dist
						  ,ROUND(c.reseller_discount_per / 100 * c.price, 2)discount_res
					  from oc_order a
					  join oc_order_details b on(a.order_id = b.order_id)
					  join gui_items_tbl c on(b.item_id = c.item_id)
					  join oc_user d on(a.user_id = d.user_id)
					 where a.order_id = ".$order_id;
		$query = $this->db->query($sql);
		$processed_orders = $query->rows;
		
		//check if may record na sa sales delivered ang user na umorder
		foreach($processed_orders as $po) {
			if($po['item_id'] == 16 or $po['item_id'] == 20) {
				//if distributor	
				if($po['user_group_id'] == 56 and $po['send_to'] == 110) {
					//check if may record na sa sales encoded ang user na umorder
					$sql = "select count(1) total
						  from oc_sales_delivered 	
						 where user_id =". $po['user_id']."
						 and item_id = ".$po['item_id'];
					$query = $this->db->query($sql);
					$total_encoded = $query->row['total'];
				//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_encoded == 0)	{
						$sql ="insert into oc_sales_delivered
								  set  user_id = ". $po['user_id']." 
									  ,item_id = ". $po['item_id']." 
									  ,sales_today = ". $po['amount']." 
									  ,sales_week = ". $po['amount']."  
									  ,sales_month = ". $po['amount']." 
									  ,sales_year = ". $po['amount']."  
									  ,date_added ='".$this->user->now()."' ";
						$this->db->query($sql);
					} else {//if meron nangnakarecord sa sales sales delivered na user
						$sql ="update oc_sales_delivered
								  set	sales_today = sales_today + ". $po['amount']." 
										,sales_week = sales_week + ". $po['amount']."  
										,sales_month = sales_month + ". $po['amount']."
										,sales_year = sales_year + ". $po['amount']."  
										,date_added ='".$this->user->now()."' 
								where user_id =". $po['user_id']."
								  and item_id = ". $po['item_id'];
						$this->db->query($sql);
					}
				} else if($po['user_group_id'] == 46 and $po['send_to'] == 110) {//if reseller
					//check if may record na sa sales encoded ang user na umorder
					$sql = "select count(1) total
						  from oc_sales_delivered 	
						 where user_id =". $po['user_id']."
						 and item_id = ".$po['item_id'];
					$query = $this->db->query($sql);
					$total_encoded = $query->row['total'];
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_encoded == 0)	{
						$sql ="insert into oc_sales_delivered
								  set  user_id = ". $po['user_id']." 
									  ,item_id = ". $po['item_id']."
									  ,sales_today = ". $po['amount']."
									  ,sales_week = ". $po['amount']." 
									  ,sales_month = ". $po['amount']."
									  ,sales_year = ". $po['amount']."
									  ,date_added ='".$this->user->now()."' ";
						$this->db->query($sql);
					} else {//if meron nangnakarecord sa sales sales delivered na user
						$sql ="update oc_sales_delivered
								  set	sales_today = sales_today + ". $po['amount']."
										,sales_week = sales_week + ". $po['amount']." 
										,sales_month = sales_month + ". $po['amount']."
										,sales_year = sales_year + ". $po['amount']."
										,date_added ='".$this->user->now()."' 
								where user_id =". $po['user_id']."
								  and item_id = ". $po['item_id'];
						$this->db->query($sql);
					}
				} else {
					//check if may record na sa sales encoded ang user na umorder
					$sql = "select count(1) total
						  from oc_sales_delivered 	
						 where user_id =". $po['user_id']."
						 and item_id = ".$po['item_id'];
					$query = $this->db->query($sql);
					$total_encoded = $query->row['total'];
					//kapag walang  sales encoded na nakarecord sa user na umorder
					if($total_encoded == 0)	{
						$sql ="insert into oc_sales_delivered
								  set  user_id = ". $po['user_id']."
									  ,item_id = ". $po['item_id']."
									  ,sales_today = ". $po['amount']."
									  ,sales_week = ". $po['amount']."
									  ,sales_month = ". $po['amount']."
									  ,sales_year = ". $po['amount']."
									  ,date_added ='".$this->user->now()."' ";
						$this->db->query($sql);
					} else {//if meron nangnakarecord sa sales sales delivered na user
						$sql ="update oc_sales_delivered
								  set	sales_today = sales_today + ". $po['amount']."
										,sales_week = sales_week + ". $po['amount']."
										,sales_month = sales_month + ". $po['amount']."
										,sales_year = sales_year + ". $po['amount']."
										,date_added ='".$this->user->now()."' 
								where user_id =". $po['user_id']."
								  and item_id = ". $po['item_id'];
						$this->db->query($sql);
					}
				}
			} else {
				//check if may record na sa sales encoded ang user na umorder
				$sql = "select count(1) total
					  from oc_sales_delivered 	
					 where user_id =". $po['user_id']."
					 and item_id = ".$po['item_id'];
				$query = $this->db->query($sql);
				$total_encoded = $query->row['total'];
				//kapag walang  sales encoded na nakarecord sa user na umorder
				if($total_encoded == 0)	{
					$sql ="insert into oc_sales_delivered
							  set  user_id = ". $po['user_id']."
								  ,item_id = ". $po['item_id']."
								  ,sales_today = ". $po['amount']."
								  ,sales_week = ". $po['amount']."
								  ,sales_month = ". $po['amount']."
								  ,sales_year = ". $po['amount']."
								  ,date_added ='".$this->user->now()."' ";
					$this->db->query($sql);
				} else {//if meron nangnakarecord sa sales sales delivered na user
					$sql ="update oc_sales_delivered
							  set	sales_today = sales_today + ". $po['amount']."
									,sales_week = sales_week + ". $po['amount']."
									,sales_month = sales_month + ". $po['amount']."
									,sales_year = sales_year + ". $po['amount']."
									,date_added ='".$this->user->now()."' 
							where user_id =". $po['user_id']."
							  and item_id = ". $po['item_id'];
					$this->db->query($sql);
				}
			}	
		}
		//if city distributor at mga cods, mamigay ng fees
		if($city_distributor_id > 0 and ($payment_option == 146 or $payment_option == 148 or $payment_option == 158)) {
			//marketing budget
			$sql = "select user_id
					  from oc_user 
					 where user_group_id = 58 
					   and `status` = 1 
					  limit 1 ";
			$query = $this->db->query($sql);
			if($query->row['user_id']) {
				$marketing_admin_id = $query->row['user_id'];
				if($marketing_admin_id > 0) {
					$this->insertEwallet(15, $marketing_admin_id, $user_id, $order_id, 35, $created_by, 0);
				}
			}
			
			//system upgrade
			$sql = "select user_id
					  from oc_user 
					 where user_group_id = 59 
					   and `status` = 1 
					  limit 1 ";
			$query = $this->db->query($sql);
			if($query->row['user_id']) {
				$sys_upgrade_admin = $query->row['user_id'];
				if($sys_upgrade_admin > 0) {
					$this->insertEwallet(3, $sys_upgrade_admin, $user_id, $order_id, 37, $created_by, 0);
				}
			}
			
			//gniuse
			$sql = "select user_id
					  from oc_user 
					 where user_group_id = 60
					   and `status` = 1 
					  limit 1 ";
			$query = $this->db->query($sql);
			if($query->row['user_id']) {
				$gniuse_admin_id = $query->row['user_id'];
				if($gniuse_admin_id > 0) {
					$this->insertEwallet(2, $gniuse_admin_id, $user_id, $order_id, 38, $created_by, 0);
				}
			}
			//check if part ng luzon
			$sql = "select province_id,barangay_id from oc_order where order_id =".$order_id ; 
			$query = $this->db->query($sql);
			$province_id = $query->row['province_id'];
			$barangay_id = $query->row['barangay_id'];
			
			
			$sql = "select island from oc_barangays where province_id =".$province_id."
					   and barangay_id = ".$barangay_id;
			$query = $this->db->query($sql);
			$island = $query->row['island'];
			
			if($island != 1){
				$this->deductEwallet(20, $city_distributor_id, $user_id, $order_id, 36, $created_by);
			}
			
			
			
		}
	}
	
	public function insertEwallet($income, $user_id, $source_user_id, $order_id, $commission_type_id, $created_by, $tax_flag = 0) {
		
		if($tax_flag == 1) {
			$tax = $income * 0.1;
			$net = $income * 0.9;
		} else {
			$tax = 0;
			$net = $income;
		}
		
		$sql = "select description
				  from oc_commission_type 
				 where commission_type_id = ".$commission_type_id;
		$query = $this->db->query($sql);
		$remarks = $query->row['description'];
		
		$sql = "update oc_user set ewallet = ewallet + ".$net."
				 where user_id = ".$user_id;
		$this->db->query($sql);
		
		$sql = "insert into oc_ewallet_hist 
					set user_id = ".$user_id.",
						order_id = ".$order_id.",
						source_user_id = ".$source_user_id.",
						commission_type_id = ".$commission_type_id.",
						credit = ".$net.",
						tax = ".$tax.",
						gross_amt = ".$income.",
						remarks = '".$remarks."',
						created_by = ".$created_by.",
						date_added = '".$this->user->now()."' ";
		$this->db->query($sql);
	}
	
	public function insertEwalletWithLevel($income, $user_id, $source_user_id, $order_id, $commission_type_id, $created_by, $tax_flag = 0,$level) {
		
		if($tax_flag == 1) {
			$tax = $income * 0.1;
			$net = $income * 0.9;
		} else {
			$tax = 0;
			$net = $income;
		}
		
		$sql = "select description
				  from oc_commission_type 
				 where commission_type_id = ".$commission_type_id;
		$query = $this->db->query($sql);
		$remarks = $query->row['description'];
		
		$sql = "update oc_user set ewallet = ewallet + ".$net."
				 where user_id = ".$user_id;
		$this->db->query($sql);
		
		$sql = "insert into oc_ewallet_hist 
					set user_id = ".$user_id.",
						order_id = ".$order_id.",
						source_user_id = ".$source_user_id.",
						commission_type_id = ".$commission_type_id.",
						credit = ".$net.",
						tax = ".$tax.",
						level = ".$level.",
						gross_amt = ".$income.",
						remarks = '".$remarks."',
						created_by = ".$created_by.",
						date_added = '".$this->user->now()."' ";
		$this->db->query($sql);
	}
	
	public function deductEwallet($deduct, $user_id, $source_user_id, $order_id, $commission_type_id, $created_by) {
		$sql = "select description
				  from oc_commission_type 
				 where commission_type_id = ".$commission_type_id;
		$query = $this->db->query($sql);
		$remarks = $query->row['description'];
		
		$sql = "update oc_user set ewallet = ewallet - ".$deduct."
				 where user_id = ".$user_id;
		$this->db->query($sql);
		
		$sql = "insert into oc_ewallet_hist 
					set user_id = ".$user_id.",
						order_id = ".$order_id.",
						source_user_id = ".$source_user_id.",
						commission_type_id = ".$commission_type_id.",
						debit = ".$deduct.",
						remarks = '".$remarks."',
						created_by = ".$created_by.",
						date_added = '".$this->user->now()."' ";
		$this->db->query($sql);
	}

	public function insertEwalletRequest($income, $user_id, $source_user_id, $request_id, $commission_type_id, $created_by, $tax_flag = 0) {
		
		if($tax_flag == 1) {
			$tax = $income * 0.1;
			$net = $income * 0.9;
		} else {
			$tax = 0;
			$net = $income;
		}
		
		$sql = "select description
				  from oc_commission_type 
				 where commission_type_id = ".$commission_type_id;
		$query = $this->db->query($sql);
		$remarks = $query->row['description'];
		
		$sql = "update oc_user set ewallet = ewallet + ".$net."
				 where user_id = ".$user_id;
		$this->db->query($sql);
		
		$sql = "insert into oc_ewallet_hist 
					set user_id = ".$user_id.",
						request_id = ".$request_id.",
						source_user_id = ".$source_user_id.",
						commission_type_id = ".$commission_type_id.",
						credit = ".$net.",
						tax = ".$tax.",
						gross_amt = ".$income.",
						remarks = '".$remarks."',
						created_by = ".$created_by.",
						date_added = '".$this->user->now()."' ";
		$this->db->query($sql);
	}

	public function deductEwalletRequest($deduct, $user_id, $source_user_id, $request_id, $commission_type_id, $created_by) {
		$sql = "select description
				  from oc_commission_type 
				 where commission_type_id = ".$commission_type_id;
		$query = $this->db->query($sql);
		$remarks = $query->row['description'];
		
		$sql = "update oc_user set ewallet = ewallet - ".$deduct."
				 where user_id = ".$user_id;
		$this->db->query($sql);
		
		$sql = "insert into oc_ewallet_hist 
					set user_id = ".$user_id.",
						request_id = ".$request_id.",
						source_user_id = ".$source_user_id.",
						commission_type_id = ".$commission_type_id.",
						debit = ".$deduct.",
						remarks = '".$remarks."',
						created_by = ".$created_by.",
						date_added = '".$this->user->now()."' ";
		$this->db->query($sql);
	}
	
	public function performStatusUpdate($o, $to_status_id, $paid_flag) {
		$sql = "update oc_order	
				   set paid_flag = ".$paid_flag." 
					  ,status_id = ".$to_status_id."
					  ,actual_delivery_date = '".$this->user->now()."'
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
		if($status_to == 34 or $status_to == 126) {
			$inv_user_id = 0;
			$sql = "select a.city_distributor_id, a.delivery_option
					  from oc_order a 
					 where a.order_id = ".$order_id;
			$query = $this->db->query($sql);
			$city_distributor_id = $query->row['city_distributor_id'];
			$delivery_option = $query->row['delivery_option'];
			//echo $delivery_option."<br>";
			if($delivery_option == 96) {
				$sql = "select user_id 
						  from oc_user 
						 where user_group_id =  43 
						   and `status` = 1
						 limit 1 ";
				$query = $this->db->query($sql);
				$inv_user_id = $query->row['user_id'];
			} else if($city_distributor_id > 0) {
				$inv_user_id = $city_distributor_id;
			}
			
			if($inv_user_id == 0) {
				$assemble_inventory = 0;
			} else {
				$assemble_inventory = 1;
			}
		}
		
		if($assemble_inventory == 1) { 
			//checking if complete lahat ng item inventory para sa order
			//summarize muna ung need na raw items
			$sql = "select case when f.item_name is not null then f.item_id else d.item_id end item_id,
						   case when f.item_name is not null then f.item_name else d.item_name end item_name,
						   case when f.item_name is not null then (c.quantity * e.item_quantity) else c.quantity end quantitys,
						   a.branch_id, a.city_distributor_id, a.delivery_option
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
						 where user_id = ".$inv_user_id."
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
									WHERE user_id = ".$inv_user_id."
									and item_id = " . $ita['item_id'];
						$this->db->query($sql);
						$this->log->write($sql);
						//magrecord nun sa inventory history
						$sql = "INSERT INTO oc_inventory_history_tbl 
								   SET item_id = ".$ita['item_id']."
									 , packed = ".$ita['quantity']."
									 , status = ". $status_to."
									 , order_id =  ".$order_id."
									 , date_added = '".$this->user->now()."'
									 , user_id = ".$inv_user_id."
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
									 WHERE user_id = ".$inv_user_id." 
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
										 , user_id = ".$inv_user_id."
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
										  , user_id = ".$inv_user_id."
										  ,date_added = '".$this->user->now()."'";
							$this->db->query($sql);
							$this->log->write($sql);
						}else{
							$sql = "UPDATE oc_inventory
									   SET qty = qty + ".$ita['quantity']."
									 , user_id = ".$inv_user_id."
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
									 , user_id = ".$inv_user_id."
									 , remarks = 'PROMO ASSEMBLED'";
						$this->db->query($sql);		
						$this->log->write($sql);
						
						//bawasan ang quantity ng item promo sa inventory table
						$sql = "UPDATE oc_inventory
									   SET qty = qty - ".$ita['quantity']."
									 WHERE user_id = ".$inv_user_id."
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
									 , user_id = ".$inv_user_id."
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
		if($status_id == 34 && $inventoriable == 1 && ($status_to == 113 or $status_to == 118)) {
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
								 , branch_id = ".$branch_id."
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
									 , branch_id = ".$branch_id."
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
		
		$sql = "select payment_option, status_id, inventoriable,  paid_flag, branch_id
				  from oc_order where order_id = ".$order_id;
		$query = $this->db->query($sql);
		$branch_id = $query->row['branch_id'];
		
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
						 , branch_id = ".$branch_id."
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
	public function assembleItems($order_id, $status_to) {
		$this->log->write("inside assembleItems");
		$assemble_inventory = 0;
		
		//kung status to ay packed
		if($status_to == 150 or $status_to == 152 or $status_to == 126) {
			$user_id = 0;
			$sql = "select a.city_distributor_id, a.delivery_option
					  from oc_order a 
					 where a.order_id = ".$order_id;
			$query = $this->db->query($sql);
			$city_distributor_id = $query->row['city_distributor_id'];
			$delivery_option = $query->row['delivery_option'];
			//echo $delivery_option."<br>";
			if($delivery_option == 96) {
				$sql = "select user_id 
						  from oc_user 
						 where user_group_id =  43 
						   and `status` = 1
						 limit 1 ";
				$query = $this->db->query($sql);
				$user_id = $query->row['user_id'];
			} else if($city_distributor_id > 0) {
				$user_id = $city_distributor_id;
			}
			
			if($user_id == 0) {
				$assemble_inventory = 0;
			} else {
				$assemble_inventory = 1;
			}
		}
		
		if($assemble_inventory == 1) { 
			//checking if complete lahat ng item inventory para sa order
			//summarize muna ung need na raw items
			$sql = "select t.item_id, sum(quantity) quantity from (
					select d.item_id promo_item_id, b.item_id ord_det_item_id, 
						   case when d.item_id is not null then d.item_id else b.item_id end item_id, 
						   case when d.promo_id is not null then d.item_quantity * b.quantity else b.quantity end quantity
					from oc_order a
					left join oc_order_details b on (a.order_id = b.order_id)
					left join gui_items_tbl c on (b.item_id = c.item_id)
					left join oc_promos_tbl d on(c.item_id = d.item_header_id)
					where a.order_id = ".$order_id."
					) t
					group by t.item_id ";
			$query = $this->db->query($sql);
			$raw_items = $query->rows;
			
			$this->log->write($sql);
			//initialize checking variable = 1
			$check_assemble_inventory = 1;
			// echo $sql .'select first sa ng details ng order<br>';
			//loop sa raw items
			foreach($raw_items as $ri) {
				//check if sufficient pa sa inventory
				$quantity = 0;
				$sql = "select qty 
						  from oc_inventory 
						 where user_id = ".$user_id."
						 and item_id = ".$ri['item_id'];
				$query = $this->db->query($sql);
				$this->log->write($sql);
				if(isset($query->row['qty'])) {
					$quantity = $query->row['qty'];
				}
				// echo $sql .'check if sufficient pa sa inventory<br>';
				$this->log->write("quantity = ".$quantity);
				$this->log->write("needed quantity from order = ".$ri['quantity']);
				//kapag may isang sablay ang quantity gawing 0 ung checking variable
				if($quantity < $ri['quantity']) {
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
									WHERE user_id = ".$user_id."
									and item_id = " . $ita['item_id'];
						$this->db->query($sql);
						$this->log->write($sql);
					// echo $sql .'inaupdate ang inventory <br>';
						//magrecord nun sa inventory history
						$sql = "INSERT INTO oc_inventory_history_tbl 
								   SET user_id = '".$this->user->getId()."'
									 , item_id = ".$ita['item_id']."
									 , packed = ".$ita['quantity']."
									 , status = ". $status_to."
									 , order_id =  ".$order_id."
									 , date_added = '".$this->user->now()."'
									 , remarks = 'ITEM PACKED'";
						$this->db->query($sql);
						$this->log->write($sql);
					//if nasa promos
					// echo $sql .'if wala sa promo<br>';
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
									 WHERE user_id = ".$user_id." 
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
										 , user_id = ".$user_id."
										 , remarks = 'ITEM ASSEMBLED TO PROMO'";
							$this->db->query($sql);	
							$this->log->write($sql);
						
							// echo $sql .'nag update at nag insert<br>';
						}
						
						//dagdagan ang quantity ng item promo sa inventory table
						$sql = "select count(1) as total
								  from oc_inventory 
								 where user_id = ".$user_id."
								 and item_id = " . $ita['item_id'];
						$query = $this->db->query($sql);
						$item_count = $query->row['total'];
						$this->log->write($sql);
						// echo $sql .' nag select para macheck kung may nakainventory na <br>';
						if($item_count == 0){
							$sql = "INSERT INTO oc_inventory 
									   SET qty = " . $ita['quantity'] ."
										  ,modified_by = '".$this->user->getId()."'
										  ,item_id = ".$ita['item_id'] . "
										  ,user_id = ".$user_id."
										  ,date_added = '".$this->user->now()."'";
							$this->db->query($sql);
							$this->log->write($sql);
						// echo $sql .' nag insert kung wala pang nakarecord<br>';
						}else{
							$sql = "UPDATE oc_inventory
									   SET qty = qty + ".$ita['quantity']."
									 WHERE user_id = ".$user_id."
									 AND item_id = ".$ita['item_id'];
							$this->db->query($sql);
							$this->log->write($sql);
						}
						// echo $sql .' nag update para magdagdag kung may nakarecord na<br>';
						//magrecord nun sa inventory history (remarks ay assembled) 
						$sql = "INSERT INTO oc_inventory_history_tbl 
								   SET item_id = ".$ita['item_id']."
									 , assembled_to = ".$ita['quantity']."
									 , status = ". $status_to."
									 , order_id =  ".$order_id."
									 , date_added = '".$this->user->now()."'
									 , user_id = ".$user_id."
									 , remarks = 'PROMO ASSEMBLED'";
						$this->db->query($sql);		
						$this->log->write($sql);
						// echo $sql .' nag insert sa may hist ng invent<br>';
						//bawasan ang quantity ng item promo sa inventory table
						$sql = "UPDATE oc_inventory
									   SET qty = qty - ".$ita['quantity']."
									 WHERE user_id = ".$user_id."
									 AND item_id = ".$ita['item_id'];
						$this->db->query($sql);
						$this->log->write($sql);
						// echo $sql .'update sa oc invent para magbawas<br>';
						//magrecord nun sa inventory history (packed)
						$sql = "INSERT INTO oc_inventory_history_tbl 
								   SET item_id = ".$ita['item_id']."
									 , packed = ".$ita['quantity']."
									 , status = ". $status_to."
									 , order_id =  ".$order_id."
									 , date_added = '".$this->user->now()."'
									 , user_id = ".$user_id."
									 , remarks = 'PROMO PACKED'";
						$this->db->query($sql);	
						$this->log->write($sql);
						
						// echo $sql .'insert ito sa inventory hist<br>';
					}									
				}			
					
			} 
		}
		$this->log->write("leaving assembleItems");
		return $assemble_inventory;
	}
	
	public function recordInventorySold($order_id, $status_to) {
		$user_id = 0;
		$sql = "select a.city_distributor_id, a.delivery_option
				  from oc_order a 
				 where a.order_id = ".$order_id;
		$query = $this->db->query($sql);
		$city_distributor_id = $query->row['city_distributor_id'];
		$delivery_option = $query->row['delivery_option'];
		//echo $delivery_option."<br>";
		if($delivery_option == 96) {
			$sql = "select user_id 
					  from oc_user 
					 where user_group_id =  43 
					   and `status` = 1
					 limit 1 ";
			$query = $this->db->query($sql);
			$user_id = $query->row['user_id'];
		} else if($city_distributor_id > 0) {
			$user_id = $city_distributor_id;
		}
		
		$sql = "select item_id, quantity 
				  from oc_order_details 
				 where order_id = ".$order_id;
		$query = $this->db->query($sql);	
		$order_details = $query->rows;		 
		
		foreach($order_details as $ord) {
			$sql = "INSERT INTO oc_inventory_history_tbl 
					   SET item_id = ".$ord['item_id']."
						 , sold = ".$ord['quantity']."
						 , status = ". $status_to."
						 , order_id =  ".$order_id."
						 , date_added = '".$this->user->now()."'
						 , user_id = ".$user_id."
						 , remarks = 'SOLD'";
			$this->db->query($sql);	
			$this->log->write($sql);
		}
	}
	
	public function disassembleItems($order_id, $status_to) {
		$this->log->write("inside disassembleItems");
		$return_inventory = 0;
		$sql = "select  status_id, inventoriable,  paid_flag, city_distributor_id
				  from oc_order where order_id = ".$order_id;
		$query = $this->db->query($sql);
		$operator_id = $query->row['city_distributor_id'];
		$status_id = $query->row['status_id'];
		$inventoriable = $query->row['inventoriable'];
		
		//checkout, nakainventoriable, status to ay cancelled at return
		if(($status_id == 139 or $status_id == 150) && $inventoriable == 1 && ($status_to == 118)) {
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
							 WHERE user_id = ".$operator_id."
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
								 , user_id = ".$operator_id."
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
								 WHERE user_id = ".$operator_id."
								   AND item_id = ".$pi['item_id'];
						$this->db->query($sql);
						$this->log->write($sql);
						//magrecord nun sa inventory history
						$sql = "INSERT INTO oc_inventory_history_tbl 
								   SET item_id = ".$pi['item_id']."
									 , returned = (".$ita['quantity']."*".$pi['item_quantity'].")
									 , status = ". $status_to."
									 , order_id =  ".$order_id."
									 , date_added = '".$this->user->now()."'
									 , user_id = ".$operator_id."
									 , remarks = '".$status_to_desc." : ITEM DISASSEMBLED FROM PROMO'";
						$this->db->query($sql);	
						$this->log->write($sql);						
					}						
				}
			} 
		}
		$this->log->write("leaving dissambleItems");
	}
	
	
}
?>