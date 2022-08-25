<?php
class ModelAccountOrderList extends Model {
		
	public function getOrderList($data, $query_type = "data") {
		$dropshipcashier = 43;
		$codcop = 49;
		$remittanceofficer = 54;
		$sql = "select status_id from gui_status_tbl where upper(grouping) = 'PAYMENT OPTION' ";
		
		if($this->user->getUserGroupId() == $dropshipcashier){
			$sql .= " and upper(grouping2) = 'DROPSHIP' ";
		} else if($this->user->getUserGroupId() == $codcop){
			$sql .= " and upper(grouping2) != 'DROPSHIP' ";
		} else if($this->user->getUserGroupId() == $remittanceofficer){
			$sql .= " and upper(grouping2) = 'DROPSHIP' ";
		}
		
		$query = $this->db->query($sql);
		$status_id = $query->rows;
		
		foreach ($status_id as $status_id) {
			$si[] = $status_id['status_id'];
		}
			
		$si = implode(',', $si);
		
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
				 where 1 = 1 AND a.payment_option in(".$si.") AND a.delivery_option not in (98) ";
			
			if(isset($data['status_id'])){
				if(!empty($data['status_id'])){
					$sql .= " AND a.status_id in (".$data['status_id'].")";
				}
			}
			
			if(isset($data['order_id'])) {
				if(!empty($data['order_id'])) {
					$sql .= " and a.order_id in (".$this->db->escape(trim($data['order_id'],",")).")";
					
				}
			}
			
			if(isset($data['cust_name_search'])){
				if(!empty($data['cust_name_search'])){
					$sql .= " and a.customer_name = '".$data['cust_name_search']."' ";
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
			
			if(isset($data['ref'])) {
				if (!empty($data['ref'])) {
					$sql .= " and a.ref = '" . $data['ref'] . "' ";
				}
			}
		
		if($query_type == "data") {
			//echo "<br><br><br><br><br>".$data['type'];
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
	
	public function tagOrdersAsPacked($data) {
		$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, branch_id from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$status_id = $query->row['status_id'];
				$branch_id = $query->row['branch_id'];
				if($status_id == 18) {
					
					$sql = "select item_id, quantity qty
							  from oc_order_details
							 where order_id = ".$order_id;
					$query = $this->db->query($sql);
					$order_items = $query->rows;
					
					$valid = 1;
					
					foreach($order_items as $detailcheck) {
						$sql = "select coalesce(qty, 0) qty 
								  from oc_inventory 
								where branch_id = ".$branch_id."
								  and item_id = ".$detailcheck['item_id'];
						$query = $this->db->query($sql);
						$qty = 0;
						if(isset($query->row['qty'])) {
							$qty = $query->row['qty'];
						}
						
						if($qty < $detailcheck['qty']) {
							$valid = 0;
						}
					}
					
					if($valid == 1) {					
						foreach($order_items as $detail) {
							$sql = "UPDATE oc_inventory
								   set qty = qty - ".$detail['qty'].",
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
											re_stock = 0,
											sold = ".$detail['qty'].",
											branch_id = ".$branch_id.",
											inventory_id = ".$inventoryId.",
											status = 34, 
											remarks = 'PACKED',
											order_id = ".$order_id;
							$this->db->query($sql);	
						}
						
						$sql = "update oc_order	
								   set status_id = 34 
									  ,inventoriable = 1
									  ,packed_date = '".$this->user->now()."'
								 where order_id = ".$order_id;
						$this->db->query($sql);		
						$return_msg .= "Order Id ".$order_id." is successfully processed.<br>";
					} else {
						$return_msg .= "Order Id ".$order_id." cannot be proccessed due to insufficient inventory.<br>";
					}
				} else {
					$return_msg .= "Order Id ".$order_id." is not in proper status.<br>";
				}
			}
		} else {
			$return_msg .= "No order selected to pack.";
		}
		return $return_msg;
	}
	
	public function tagOrdersAsInTransit($data) {
		$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, branch_id from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$status_id = $query->row['status_id'];
				$branch_id = $query->row['branch_id'];
				if($status_id == 34) {
				
					$sql = "update oc_order	
							   set status_id = 117 
								  ,inventoriable = 1
								  ,packed_date = '".$this->user->now()."'
							 where order_id = ".$order_id;
					$this->db->query($sql);		
					$return_msg .= "Order Id ".$order_id." is successfully processed.<br>";

				} else {
					$return_msg .= "Order Id ".$order_id." is not in proper status.<br>";
				}
			}
		} else {
			$return_msg .= "No order selected to tag as in transit.";
		}
		return $return_msg;
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
				// if($status_id == 34) {
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
											status = 118, 
											remarks = 'RETURNED',
											order_id = ".$order_id;
							$this->db->query($sql);	
						}
					}
					$return_msg .= "Order ID ".$order_id." is returned.<br>";
				// } else {
					// $return_msg .= "Return of order id ".$order_id." failed. Only Packed orders can be returned.<br>";
				// }
			}
			return $return_msg;
		} else {
			return "No orders selected to return.<br>";
		}
	}
	
	public function tagOrdersAsDelivered($data) {
		$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, user_id, reseller_id, firstname, lastname, 
							   paid_flag, username, password, contact, email, address,
							   province_id, city_municipality_id, barangay_id, send_to   
						  from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$order = $query->row;
				$status_id = $query->row['status_id'];
				$user_id = $query->row['user_id'];
				$reseller_id = $query->row['reseller_id'];
				$paid_flag = $query->row['paid_flag'];
				
				// if($status_id == 117 and $paid_flag == 1) {
				if($status_id == 117 || $status_id == 126) {
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
					
					$sql = "insert into oc_order_hist_tbl
								set user_id = ".$this->user->getId().",
									order_id = ".$order_id.",
									from_status_id = ".$from_status_id.",
									to_status_id = 35,
									remarks = 'Delivered',
									date_added = '".$this->user->now()."' ";
					$this->db->query($sql);
					
					$sql = "select user_group_id from oc_user where user_id = ".$user_id;
					$query = $this->db->query($sql);
					$user_group_id = $query->row['user_group_id'];
					
					if($reseller_id != 0){
						$sql = "select refer_by_id from oc_user where user_id = ".$reseller_id;
						$query = $this->db->query($sql);
						$refer_by_id1 = $query->row['refer_by_id'];
						
						$sql = "select refer_by_id, username from oc_user where user_id = ".$refer_by_id1;
						$query = $this->db->query($sql);
						$refer_by_id2 = $query->row['refer_by_id'];
						$sponsor_username = $query->row['username'];
						
					} else {
						$reseller_id = 0;
						$refer_by_id1 = 0;
						$refer_by_id2 = 0;
						$sponsor_username = "";
					}
					
					$this->load->model('account/common');
					$with_package = $this->model_account_common->determineIfWithPackage($order_id);
					
					if($user_group_id == 13) {						
						if($with_package == 1) {
							$sql = "update oc_user 
									   set activation_flag = 1
										  ,user_group_id = 39 
										  ,address = '".$this->db->escape($order['address'])."'
										  ,province_id = ".$order['province_id']."
										  ,city_municipality_id = ".$order['city_municipality_id']."
										  ,barangay_id = ".$order['barangay_id']."
									 where user_id = ".$user_id;
							$this->db->query($sql);
						}	
					} else {
						//echo "with package";
						if($with_package == 1) {
							$user = array();
							$user['username'] = $order['username'];
							$user['sponsor'] = $sponsor_username;
							$user['firstname'] = $order['firstname'];
							$user['middlename'] = $order['firstname'];
							$user['lastname'] = $order['lastname'];
							$user['mobile'] = $order['contact'];
							$user['email'] = $order['email'];
							$user['password'] = $order['password'];
							$user['address'] = $order['address'];
							$user['province_id'] = $order['province_id'];
							$user['city_municipality_id'] = $order['city_municipality_id'];
							$user['barangay_id'] = $order['barangay_id'];
							$user['gender'] = "F";
							$user['autoactivate'] = 1;
							//var_dump($user);
							$this->load->model('account/register');	
							$result = $this->model_account_register->addMember($user);
							//var_dump($result);
						}
					}
					
					$sql = "select b.unilevel1, b.unilevel2, b.rebates, a.quantity
									from oc_order_details a
									join gui_items_tbl b on(a.item_id = b.item_id)
									where a.order_id = ".$order_id;
					$query = $this->db->query($sql);
					$items = $query->rows;
				
					// foreach($items as $it) {						
						// if($user_group_id == 13) {
							// if($it['rebates'] > 0) {
								// $this->insertEwallet($reseller_id, $reseller_id, 21, $it['rebates'] * $it['quantity']);
							// }
						// } else {
							// if($order['send_to'] == 111) {
								// if($it['rebates'] > 0) {
									// $this->insertEwallet($reseller_id, $reseller_id, 21, $it['rebates'] * $it['quantity']);
								// }
							// }
						// }
						
						// if($it['unilevel1'] > 0 && $refer_by_id1 > 0) {
							// $this->insertEwallet($refer_by_id1, $reseller_id, 19, $it['unilevel1'] * $it['quantity']);
						// }
						
						// if($it['unilevel2'] > 0 && $refer_by_id2 > 0) {
							// $this->insertEwallet($refer_by_id2, $reseller_id, 20, $it['unilevel2'] * $it['quantity']);
						// }
					// }
					$return_msg .= "Order Id ".$order_id." is tagged as Delivered.";
				} else {
					$return_msg .= "Order Id ".$order_id." must be paid already.";
				}
			}
		} else {
			$return_msg .= "No order selected to deliver.";
		}
		return $return_msg;
	}
	
	public function tagOrdersAsCancelled($data) {
		$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, paid_flag, branch_id, inventoriable from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$status_id = $query->row['status_id'];
				$paid_flag = $query->row['paid_flag'];
				$branch_id = $query->row['branch_id'];
				$inventoriable = $query->row['inventoriable'];
				if(($status_id == 18 or $status_id == 34) and ($paid_flag == 0)) {
					$sql = "update oc_order	
							   set status_id = 113 
								  ,cancel_date = '".$this->user->now()."'
						     where order_id = ".$order_id;
					$this->db->query($sql);
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
				} else {
					$return_msg .= "Cancellation of order id ".$order_id." failed. Only unpaid orders in the status of Checkout or Packed orders can be cancelled.<br>";
				}
			}
			return $return_msg;
		} else {
			return "No orders selected to cancel.<br>";
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
	
	public function tagOrdersAsCheckOut($data) {
		$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, branch_id from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$status_id = $query->row['status_id'];
				$branch_id = $query->row['branch_id'];
				if($status_id == 118) {
				
					$sql = "update oc_order	
							   set status_id = 18 
							 where order_id = ".$order_id;
					$this->db->query($sql);		
					$return_msg .= "Order Id ".$order_id." is successfully processed.<br>";

				} else {
					$return_msg .= "Order Id ".$order_id." is not in proper status.<br>";
				}
			}
		} else {
			$return_msg .= "No order selected to tag as Check Out.";
		}
		return $return_msg;
	}
	
	public function tagOrdersAsPaymentRemitted($data) {
		$return_msg = "";
		
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, branch_id from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$status_id = $query->row['status_id'];
				$branch_id = $query->row['branch_id'];
				if($status_id == 35) {
				
					$sql = "update oc_order	
							   set status_id = 119 
								  ,paid_flag = 1 
								  ,paid_date = '".$this->user->now()."'
							 where order_id = ".$order_id;
					$this->db->query($sql);		
					$return_msg .= "Order Id ".$order_id." is successfully processed.<br>";

				} else {
					$return_msg .= "Order Id ".$order_id." is not in proper status.<br>";
				}
			}
		} else {
			$return_msg .= "No order selected to tag as Payment Remitted.";
		}
		return $return_msg;
	}
	
	public function tagOrderAsShipped($data) {
		$return_msg = "";
		
		if(isset($data['selected'])) {
			foreach($data['selected'] as $order_id) {
				$sql = "select status_id, branch_id from oc_order where order_id = ".$order_id;
				$query = $this->db->query($sql);
				$status_id = $query->row['status_id'];
				$branch_id = $query->row['branch_id'];
				if($status_id == 125) {			
					
					$sql = "select item_id, quantity qty
							  from oc_order_details
							 where order_id = ".$order_id;
					$query = $this->db->query($sql);
					$order_items = $query->rows;
					
					$valid = 1;
					
					foreach($order_items as $detailcheck) {
						$sql = "select coalesce(qty, 0) qty 
								  from oc_inventory 
								where branch_id = ".$branch_id."
								  and item_id = ".$detailcheck['item_id'];
						$query = $this->db->query($sql);
						$qty = 0;
						if(isset($query->row['qty'])) {
							$qty = $query->row['qty'];
						}
						
						if($qty < $detailcheck['qty']) {
							$valid = 0;
						}
					}
					
					if($valid == 1) {					
						foreach($order_items as $detail) {
							$sql = "UPDATE oc_inventory
								   set qty = qty - ".$detail['qty'].",
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
											re_stock = 0,
											sold = ".$detail['qty'].",
											branch_id = ".$branch_id.",
											inventory_id = ".$inventoryId.",
											status = 126, 
											remarks = 'Shipped',
											order_id = ".$order_id;
							$this->db->query($sql);	
						}
						
						$sql = "select order_hist_id, to_status_id
								from oc_order_hist_tbl
							  where order_id = ".$order_id."
							  order by order_hist_id desc limit 1";
						$query = $this->db->query($sql);
						$from_status_id = $query->row['to_status_id'];
						
						$sql = "insert into oc_order_hist_tbl
									set user_id = ".$this->user->getId().",
										order_id = ".$order_id.",
										from_status_id = ".$from_status_id.",
										to_status_id = 126,
										remarks = 'Shipped',
										date_added = '".$this->user->now()."' ";
						$this->db->query($sql);
						
						$sql = "update oc_order	
								   set status_id = 126
									  ,shipped_date = '".$this->user->now()."'
								 where order_id = ".$order_id;
						$this->db->query($sql);		
						$return_msg .= "Order Id ".$order_id." is successfully processed.<br>";
					} else {
						$return_msg .= "Order Id ".$order_id." cannot be proccessed due to insufficient inventory.<br>";
					}
				} else {
					$return_msg .= "Order Id ".$order_id." is not in proper status.<br>";
				}
			} 
		} else {
			$return_msg .= "No order selected to tag as Payment Remitted.";
		}
			return $return_msg;
	}	

}
?>