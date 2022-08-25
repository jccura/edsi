<?php
class ModelAccountRequest extends Model {
	
	public function computeRequest($request_id, $product_id_sel = 0) {
		
		if($product_id_sel > 0) {
			$sql = "select coalesce(sum(b.price * a.qty), 0) cost, coalesce(sum(a.qty),0) quantity, a.qty, b.price
					  from oc_request_details a
					  join gui_items_tbl b on(a.item_id = b.item_id)
					 where request_id = ".$request_id."
					 and a.item_id = ".$product_id_sel;
			$query = $this->db->query($sql);
			
			$qty = $query->row['qty'];
			$cost = $query->row['cost'];
			$price = $query->row['price'];
			$quantity = $query->row['quantity'];
			
			$sql = "update oc_request_details set amount = ".$price.", qty = ".$quantity." 
						where request_id = ".$request_id."
						and item_id = ".$product_id_sel;
			$this->db->query($sql);	
		}
		
		$sql = "select coalesce(sum(b.price * a.qty), 0) cost, coalesce(sum(a.qty),0) quantity, a.qty, b.price, a.qty_on_hand
					from oc_request_details a
					join gui_items_tbl b on(a.item_id = b.item_id)
				where request_id = ".$request_id;
		$query = $this->db->query($sql);
			
		$cost = $query->row['cost'];
		$quantity = $query->row['quantity'];

		$sql = "update oc_request set total_amount = ".$cost.", qty = ".$quantity." 
					where request_id = ".$request_id;
		$this->db->query($sql);			
	}

	public function getCategories() {
		$sql  = "select category_id, description, active
                   from gui_category_tbl
				  where active = 1 
				  order by description";

		$query = $this->db->query($sql);
		return $query->rows;			
	}
	
	public function getBranches() {
		$sql = "select branch_id, description from gui_branch_tbl where status = 1 order by description";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getUserBranch($userId){
		$sql  = "SELECT branch_id FROM gui_branch_tbl where user_id=".$userId;
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getItems($data) {
			$sql = "select item_code, item_id, item_name, raw, description, price from gui_items_tbl 
					 where raw = 1";
		if(isset($data['category_id'])) {
			if($data['category_id'] != "") {
				$sql .= "and category_id = ".$data['category_id']; 
			}			
		}

		$sql .= " order by description ";
		$query = $this->db->query($sql);
		return $query->rows;
	}	
	
	public function addRequestItem($data) {
		$sql = "SELECT * from oc_request where request_id = '".$data['request_id']."'";
		$query = $this->db->query($sql);
		$parentReq = $query->rows;
		
		if($data['quantity_sel'] > 0) {
			if(isset($data['product_id_sel']) && sizeOf($parentReq)==1) {
				$sql = "select count(1) cnt from oc_request_details where request_id = ".$data['request_id']." and item_id = ".$data['product_id_sel'];
				$query = $this->db->query($sql);
				$count = $query->row['cnt'];
				if($count == 1){
					if($data['product_id_sel'] != "0") {
						$sql  = "select qty from oc_request_details where request_id = ".$data['request_id']." and item_id = ".$data['product_id_sel'];
						$query = $this->db->query($sql);
						$qty = $query->row['qty'];
						$sql  = "UPDATE oc_request_details ";
						$sql .= "   SET qty = ".($qty + $data['quantity_sel']);
						$sql .= "      ,date_added = '".$this->user->now()."' ";
						$sql .= "   where request_id = ".$data['request_id']." and item_id = ".$data['product_id_sel'];
						$this->db->query($sql);
						$this->computeRequest($data['request_id'],$data['product_id_sel']);
						// echo $sql;
					}
				}
				else{
					if($data['product_id_sel'] != "0") {
						$sql  = "INSERT INTO oc_request_details ";
						$sql .= "   SET request_id = ".$data['request_id']." ";
						$sql .= "      ,item_id = ".$data['product_id_sel']." ";
						$sql .= "      ,qty = ".$data['quantity_sel']." ";
						$sql .= "      ,date_added = '".$this->user->now()."' ";
						$this->db->query($sql);
						$this->computeRequest($data['request_id'],$data['product_id_sel']);
						
					}
				}
				
				$sql = "UPDATE oc_request ";
					$sql .= "SET created_by='".$this->user->getId()."' ";
					$sql .= ", date_added='".$this->user->now()."' ";
					$sql .= ", qty=".($parentReq[0]['qty']+$data['quantity_sel']);
					$sql .= " WHERE request_id=".$data['request_id'];
				$this->db->query($sql);
				
			}else{
				return "Request not existing.#".$data['request_id'];
			}				
		} else {
			return "Quantity must be a positive number, greater than 100.";
		}

		return "Successful addition to Request.";
	}
	
	public function getRequestDetails($request_id) {
		$sql = "select a.*, b.description, a.request_from,
					   c.description payment_option_desc
				  from oc_request a
				  left join gui_status_tbl b on (a.status = b.status_id)
				  left join gui_status_tbl c on (a.payment_option = c.status_id)
				 where a.request_id = ".$request_id;
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getRequestItems($data = array(), $query_type = "data") {
		$sql = "SELECT	a.request_id, a.request_detail_id, a.qty_on_hand, 
					    a.date_added, a.qty, a.amount, 
					b.item_id, b.description, b.item_name 'item', b.price, c.description 'category'
				FROM oc_request_details a
				LEFT JOIN gui_items_tbl b on (a.item_id = b.item_id)
				LEFT JOIN gui_category_tbl c on (b.category_id = c.category_id)
				WHERE	a.request_id = ".$data['request_id'];			 
				 
		if($query_type == "data") {
			$query = $this->db->query($sql);
			return $query->rows;			
		} else {
			$sqlt = "select count(1) total from (".$sql.") t ";
			$query = $this->db->query($sqlt);
			return $query->row['total'];			
		}
	} 
	
	public function getTotalItems($requestId){
		$sql = "SELECT coalesce(SUM(qty),0) qty, coalesce(SUM(qty_on_hand),0) qtyoh, coalesce(SUM(b.price * a.qty_on_hand),0) totalAmount 
					FROM oc_request_details a INNER JOIN gui_items_tbl b ON a.item_id=b.item_id WHERE request_id=".$requestId;
		$query = $this->db->query($sql);
		return $query->row;			
	}
	
	public function requestinventory($data) {
		$result = array();
		$user_group_id = $this->user->getUserGroupId();
		$result['status_msg'] = "Cannot Create new Request";
		
		$sql = "select user_id from oc_user where user_group_id = 44 and `status` = 1 limit 1 ";
		$query = $this->db->query($sql);
		$invofficer_id = $query->row['user_id'];
		
		try {
			
			$sql  = "INSERT INTO oc_request 
					   SET created_by = ".$this->user->getId()."
						 , date_added = '".$this->user->now()."' 
						 , payment_option = '".$data['payment_option']."' 
						 , request_from = ".$this->user->getId()."
						 , user_group_from = ".$this->user->getUserGroupId()."
						 , request_to = ".$invofficer_id."
						 , user_group_id = 44
						 , status = 72";
			$this->db->query($sql);
			$requestId = $this->db->getLastId();
			
			$sql = "INSERT into oc_request_hist
					   SET request_id = ".$requestId.",
					       user_id = ".$this->user->getId().",
						   status_from = 1,
						   status_to = 72,
						   date_added = '".$this->user->now()."' ";
			$this->db->query($sql);
			
			$sql = "UPDATE oc_request SET transfer_reference = '".$this->db->escape(sha1(IDPREFIX.$requestId))."' where request_id = ".$requestId;
			$this->db->query($sql);
			
			$result['request_id'] = $requestId;
			$result['status'] = "success";
			$result['status_msg'] = "You have successfully created a request.# ".$requestId;			
		} catch (Exception $e) {
			$result['request_id'] = 0;
			$result['status'] = "failed";
			$result['status_msg'] = "Error in Creating Inventory Request.";			
		}		
	
		return $result;
	}
	
	 public function getBranch() {
		 
		$sql  = "select	a.branch_id, a.description, b.status, a.warehouse_id, b.username, b.user_group_id
					from	gui_branch_tbl a
					left join	oc_user b on (a.user_id = b.user_id)
					left join	oc_warehouse c on (a.warehouse_id = c.warehouse_id)
					where b.status = 1 
					order by description asc";

		$query = $this->db->query($sql);
		return $query->rows;			
	}
	
	public function getWarehouse() {
		$sql  = "select	a.warehouse_id, concat(a.description, ' - ' ,a.island) as wdescription, b.status, b.username
					from	oc_warehouse a
					left join	oc_user b on (a.user_id = b.user_id)
					where	b.status = 1 and b.user_group_id = 78 ";

		$query = $this->db->query($sql);
		return $query->rows;			
	}
	
	public function getMerchant(){
		$sql  = "select a.merchant_id, a.description, a.user_id, 
						b.username, b.branch_id, b.status, b.user_group_id
					From oc_merchant a
					Left Join oc_user b on (a.user_id = b.user_id)
					where b.status = 1 and b.user_group_id = 77
					order by a.description asc";

		$query = $this->db->query($sql);
		return $query->rows;	
	}
	
	public function deleteRequestItem($data) {
		//var_dump($data);
		if(isset($data['request_detail_id'])) {
			if($data['request_detail_id'] != "0") {
				$sql = "delete from oc_request_details where request_detail_id = ".$data['request_detail_id']." AND request_id=".$data['request_id'];
				$this->db->query($sql);
				$this->computeRequest($data['request_id'], 0);
				return "Successful removal of Item.";
			}
		}
		return "Error in deletion of item.";
	}
	
	public function allocateInventory($data) {
		if(isset($data['request_id'])) {
			if($data['request_id'] != "0") {
				
				$sql = "select request_detail_id From oc_request_details Where request_id = ".$data['request_id'];
				$query = $this->db->query($sql);
				$request_items = $query->rows;
				
				$proceed = 1;

				//var_dump($data);

				foreach($request_items as $row) {
					if($data['qty_on_hand'.$row['request_detail_id']] < 1) {
						$proceed = 0;
					}
				}
				
				if($proceed == 1) {
					
					foreach($request_items as $row) {
						
						$sql = "UPDATE oc_request_details 
									SET qty_on_hand = ".$data['qty_on_hand'.$row['request_detail_id']]."
								WHERE request_detail_id = ".$row['request_detail_id'];
						$query = $this->db->query($sql);
					
					}
					
					$sql = "INSERT into oc_request_hist
							   SET request_id = ".$data['request_id'].",
							       user_id = ".$this->user->getId().",
								   status_from = 1,
								   status_to = 75,
								   date_added = '".$this->user->now()."' ";
					$this->db->query($sql);
					
					$sql = "select coalesce(sum(b.price * a.qty_on_hand),0) cost, coalesce(sum(a.qty),0) quantity, coalesce(a.qty,0) qty, coalesce(b.price,0) price, coalesce(sum(a.qty_on_hand),0) qtyonhand
								from oc_request_details a
								join gui_items_tbl b on(a.item_id = b.item_id)
							where request_id = ".$data['request_id'];
								 // and b.item_id = ".$product_id_sel;
					$query = $this->db->query($sql);
					
					$qty = $query->row['qty'];
					$cost = $query->row['cost'];
					$price = $query->row['price'];
					$quantity = $query->row['quantity'];
					$qtyonhand = $query->row['qtyonhand'];
					
					$sql = "UPDATE oc_request 
								SET status = 75,
									qty = ".$qtyonhand.",
									total_amount = ".$cost."
							WHERE request_id=".$data['request_id'];
					$query = $this->db->query($sql);
					
					return "Successful modification of request quantity.";	
				} else {

					return "You should allocate a positive number, more than 0.";	
				}
			}
		}
		return "Error in allocation of item.";		
			
	}
	
	public function approveRequest($data) {
		
		if(isset($data['request_id'])) {
			$sql = "select request_from, status, payment_option, paid_flag, total_amount from oc_request where status = 75 and request_id = ".$data['request_id'];
			
			$query = $this->db->query($sql);
			$status = 0;
			
			if(isset($query->row['status'])) {
				$status = $query->row['status'];
				$payment_option = $query->row['payment_option'];
				$paid_flag = $query->row['paid_flag'];
				$request_from = $query->row['request_from'];
				$total_amount = $query->row['total_amount'];
				
				if($payment_option == 154) {
					$sql = "select ewallet
							  from oc_user 
							 where user_id = ".$request_from;
					$query = $this->db->query($sql);
					$ewallet_from = $query->row['ewallet'];
					
					if($ewallet_from >= $total_amount) {
						$this->load->model('account/orders');
						
						$this->model_account_orders->deductEwalletRequest($total_amount, $request_from, $request_from, $data['request_id'], 39, $this->user->getId());
						
						$sql = "select user_id 
								  from oc_user 
								 where user_group_id = 36 
								   and status = 1 
								 limit 1 ";
						$query = $this->db->query($sql);
						$fin_user_id = $query->row['user_id'];
						
						$this->model_account_orders->insertEwalletRequest($total_amount, $fin_user_id, $request_from, $data['request_id'], 39, $this->user->getId(), 0);
						
						$sql = "update oc_request
								   set paid_flag = 1
								 where request_id = ".$data['request_id'];							 
						$this->db->query($sql);
						$paid_flag = 1;
					} else {
						return "Insufficient Ewallet"; 
					}
				}
				
				if($status == 75 and $paid_flag = 1){
					
					$sql = "SELECT a.request_from, a.request_to, a.user_group_id, a.user_group_from, a.created_by,
								   b.item_id, b.qty_on_hand
							  FROM oc_request a
							  Left join oc_request_details b on (a.request_id = b.request_id)
							 Where a.request_id = ".$data['request_id'];
								
					$query = $this->db->query($sql);
					$details = $query->rows;
					
					foreach ($details as $detail) {

						$sql = "select count(item_id) total, qty from oc_inventory
								 where item_id = ".$detail['item_id'];							
						if ($detail['user_group_id'] == 47) {
							$sql .= " and branch_id = ".$detail['request_to'];	
						} else if ($detail['user_group_id'] == 78) {
							$sql .= " and warehouse_id = ".$detail['request_to'];
						} else if ($detail['user_group_id'] == 77) {
							$sql .= " and merchant_id = ".$detail['request_to'];
						} else if($detail['user_group_id'] == 44) {
							$sql .= " and user_id = ".$detail['request_to'];
						}
							
						$query = $this->db->query($sql);
						$qty1 = $query->row['qty'];
						
						// echo $sql .'<br>';
						
						if ($qty1 > $detail['qty_on_hand']) {
							
							$sql = "select count(1) total from oc_inventory
									 where 1=1 AND item_id = ".$detail['item_id'];
								if ($detail['user_group_from'] == 39 or $detail['user_group_from'] == 43) {
									$sql .= " and user_id = ".$detail['request_from'];	
								
								} else if ($detail['user_group_from'] == 47) {
									$sql .= " and branch_id = ".$detail['request_from'];	
								}
							
							$query = $this->db->query($sql);
							$total = $query->row['total'];
							// echo $sql .'<br>';
							if ($total == 0) {
								$sql = "INSERT INTO oc_inventory 
											set qty = ".$detail['qty_on_hand'].", 
												modified_by='".$this->user->getId()."', 
												item_id = ".$detail['item_id'].", 
												date_added='".$this->user->now()."' ";
												
											if ($detail['user_group_from'] == 47) {
												$sql .= ", branch_id = ".$detail['request_from'];	
											} else if ($detail['user_group_from'] == 78) {
												$sql .= ", warehouse_id = ".$detail['request_from'];
											} else if ($detail['user_group_from'] == 77) {
												$sql .= ", merchant_id = ".$detail['request_from'];
											} else if($detail['user_group_from'] == 39 or $detail['user_group_from'] == 43) {
												$sql .= ",user_id = ".$detail['request_from'];
											}
										// echo $sql .'add <br>' ;			
								$this->db->query($sql);
								
								$sql = "UPDATE oc_inventory
										   set qty = qty - ".$detail['qty_on_hand'].",
											   modified_by = '".$this->user->getId()."',
											   date_added = '".$this->user->now()."' ";
												
									if ($detail['user_group_id'] == 47) {
										$sql .= " where branch_id = ".$detail['request_to'];	
									} else if ($detail['user_group_id'] == 78) {
										$sql .= " where warehouse_id = ".$detail['request_to'];
									} else if ($detail['user_group_id'] == 77) {
										$sql .= " where merchant_id = ".$detail['request_to'];
									} else if($detail['user_group_id'] == 44) {
										$sql .= " where user_id = ".$detail['request_to'];
									} 
								$sql .= "   and item_id = ".$detail['item_id'];
								// echo $sql .'update <br />';
								$this->db->query($sql);
							
							} else {
								
								$sql = "UPDATE oc_inventory
										   set qty = qty + ".$detail['qty_on_hand'].",
											   modified_by = '".$this->user->getId()."',
											   date_added = '".$this->user->now()."' ";
											   
									if ($detail['user_group_from'] == 47) {
										$sql .= " Where branch_id = ".$detail['request_from'];	
									} else if ($detail['user_group_from'] == 78) {
										$sql .= " Where warehouse_id = ".$detail['request_from'];
									} else if ($detail['user_group_from'] == 77) {
										$sql .= " Where merchant_id = ".$detail['request_from'];
									} else if($detail['user_group_from'] == 39 or $detail['user_group_from'] == 43) {
										$sql .= " where user_id = ".$detail['request_from'];
									}
								$sql .= " and item_id = ".$detail['item_id'];
								// echo $sql .'update kasi meron na <br />';				   
								$this->db->query($sql);
								
								$sql = "UPDATE oc_inventory
										   set qty = qty - ".$detail['qty_on_hand'].",
											   modified_by = '".$this->user->getId()."',
											   date_added = '".$this->user->now()."' ";
											   
									if ($detail['user_group_id'] == 47) {
										$sql .= " where branch_id = ".$detail['request_to'];	
									} else if ($detail['user_group_id'] == 78) {
										$sql .= " where warehouse_id = ".$detail['request_to'];
									} else if ($detail['user_group_id'] == 77) {
										$sql .= " where merchant_id = ".$detail['request_to'];
									} else if($detail['user_group_id'] == 44) {
										$sql .= " where user_id = ".$detail['request_to'];
									} 
								 $sql .= "   and item_id = ".$detail['item_id'];
								// echo $sql .'update 2 <br />';			   
								$this->db->query($sql);
					
							}
					
						} else {
							return "Failed, not enough inventory to have a transaction.";
						}
						
						if($this->user->getUserGroupId() == 47){
							$trasfer_from = $this->user->getBranchId();
						} else if($this->user->getUserGroupId() == 77){
							$trasfer_from = $this->user->getMerchantId();
						} else if($this->user->getUserGroupId() == 78){
							$trasfer_from = $this->user->getWarehouseId();
						} else if($detail['user_group_id'] == 44) {
							$sql .= " and user_id = ".$detail['request_to'];
						}
						$sql = "INSERT INTO oc_inventory_history_tbl
										SET user_id = '".$detail['created_by']."',
											item_id = ".$detail['item_id'].",
											date_added = '".$this->user->now()."',
											re_stock = ".$detail['qty_on_hand'].",
											status = 76";
						$this->db->query($sql);
						
						$sql = "INSERT INTO oc_inventory_history_tbl
										SET user_id = '".$this->user->getId()."',
											item_id = ".$detail['item_id'].",
											recieved_by = ".$detail['request_from'].",
											date_added = '".$this->user->now()."',
											sold = ".$detail['qty_on_hand'].",
											status = 77";
						$this->db->query($sql);
					}
					
					$sql = "INSERT INTO oc_request_hist
								SET request_id = ".$data['request_id'].",
									user_id = ".$this->user->getId().",
									date_added = '".$this->user->now()."',
									status_from = 75,
									status_to = 77 ";
									
					$this->db->query($sql);

					$sql = "update oc_request 
							   set status = 77 
								   , approved_by = ".$this->user->getId()."
								   , approval_date = '".$this->user->now()."'
							 where request_id = ".$data['request_id'];
					
					$this->db->query($sql);
					
					if($detail['user_group_from'] == 39) {
						//record sa sales inventory
						$sql = "insert into oc_sales_inventory(user_id, request_id, request_detail_id, item_id, srp, tools, 
								service_fee, topup, cost, 
								income, tax, distributor_price, reseller_price, direct_referral, cv,system_fee,
								shipping, commissions, date_added)
							SELECT  a.request_from user_id, a.request_id, b.request_detail_id, b.item_id, c.price*b.qty_on_hand srp, c.tools*b.qty_on_hand tools, 
								c.service_fee*b.qty_on_hand service_fee, c.top_up*b.qty_on_hand top_up, c.cost*b.qty_on_hand cost, 
								c.income*b.qty_on_hand income, c.tax*b.qty_on_hand tax,
								(c.price * (100 - c.distributor_discount_per) * b.qty_on_hand)/100 distributor_price, 
								(c.price * (100 - c.reseller_discount_per) * b.qty_on_hand)/100 reseller_price, 
								c.direct_referral*b.qty_on_hand direct_referral, c.cv*b.qty_on_hand cv,c.system_fee * b.qty_on_hand system_fee,
								c.shipping * b.qty_on_hand shipping, c.commissions * b.qty_on_hand commissions, a.approval_date
							  FROM oc_request a
							  join oc_request_details b on (a.request_id = b.request_id)
							  join gui_items_tbl c on(b.item_id = c.item_id)
							 where a.request_id = ".$data['request_id'];
						$this->db->query($sql);
						
						$sql = "SELECT  sum(c.system_fee * b.qty_on_hand) system_fee
								  FROM oc_request a
								  join oc_request_details b on (a.request_id = b.request_id)
								  join gui_items_tbl c on(b.item_id = c.item_id)
								 where a.request_id = ".$data['request_id'];	 
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
									$sql = "select description
											  from oc_commission_type 
											 where commission_type_id = 45 ";
									$query = $this->db->query($sql);
									$remarks = $query->row['description'];
									
									$sql = "update oc_user set ewallet = ewallet + ".$system_fee."
											 where user_id = ".$gniuse_admin_id;
									$this->db->query($sql);
									
									$sql = "insert into oc_ewallet_hist 
												set user_id = ".$gniuse_admin_id.",
													request_id = ".$data['request_id'].",
													source_user_id = ".$detail['request_from'].",
													commission_type_id = 45,
													credit = ".$system_fee.",
													tax = 0,
													gross_amt = ".$system_fee.",
													remarks = '".$remarks."',
													created_by = ".$this->user->getId().",
													date_added = '".$this->user->now()."' ";
									$this->db->query($sql);
								}
							}
						}
					}
				} else {
					return $return_msg;
				}				
			} else {
				return "Failed, transaction is not available.";
			}
		}
		return "Successful transaction of inventory.";
	}
	
	public function getRequestList($data = array(), $query_type = "data") {
		$sql = "select a.request_id,a.qty, b.username 'created_by', a.request_to,
					a.date_added,e.username 'approved_by',a.approval_date, f.description status, a.transfer_reference,
					a.user_group_id, a.user_group_from,a.request_from,
					concat(b.firstname, ' ', b.lastname) creator_fullname,
					a.payment_option, g.description payment_option_desc
				  from oc_request a
				  inner join oc_user b on (a.created_by=b.user_id)
				  left join oc_user e on (a.approved_by = e.user_id)
				  left join gui_status_tbl f on(a.status = f.status_id) 
				  left join gui_status_tbl g on(a.payment_option = g.status_id) 
				WHERE 1 = 1  ";
		
		if($this->user->getUserGroupId() == 39 or $this->user->getUserGroupId() == 43 ) {
			$sql .= " and b.user_id = ".$this->user->getId()." and a.status in (72,75)";
		} elseif($this->user->getUserGroupId() == 44) {
			$sql .= " and a.request_to = ".$this->user->getId()." and a.status in (72,75) and a.total_amount > 0" ;
		} elseif($this->user->getUserGroupId() == 77) {
			$sql .= " and a.user_group_id = 77 and j.merchant_id = ".$this->user->getMerchantId()." or a.user_group_from = 77 and b.user_id = ".$this->user->getId();
		} elseif($this->user->getUserGroupId() == 78) {
			$sql .= " and a.user_group_id = 78 and h.warehouse_id = ".$this->user->getWarehouseId()." or a.user_group_from = 78 and b.user_id = ".$this->user->getId();
		}
		
		if(isset($data['request_id_search'])){
			if(!empty($data['request_id_search'])){
				$sql .= " AND a.request_id=".$data['request_id_search'];
			}
		}
		
		if(isset($data['datefrom'])){
			if (!empty($data['datefrom'])) {
				$sql .= " AND a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
		}
		
		if(isset($data['dateto'])){
			if (!empty($data['dateto'])) {
				$sql .= " AND a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}

		if(isset($data['branch_id'])){
			if (!empty($data['branch_id'])) {
				$sql .= " AND a.request_to = " . $data['branch_id'];
			}
		}

		if(isset($data['status_id'])) {
			if ($data['status_id'] != "0") {
				$sql .= " and a.status = " . $data['status_id'];
			}
		}
		// echo $sql .'search';
		if($query_type == "data") {
			
			$sql .= " ORDER BY a.date_added desc";

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
			$returnFinal = array();
			$ctr = 0;
			foreach($query->rows as $request){
				
				$sql = "SELECT a.qty, a.qty_on_hand, b.price, b.item_name name 
						  FROM oc_request_details a 
						  JOIN gui_items_tbl b on (a.item_id = b.item_id) 
						 WHERE request_id = " . $request['request_id'];
				$query = $this->db->query($sql);
				
				$items = "";
				$items_on_hand = "";
				$cost = 0.00;
				$cost_on_hand = 0.00;
				
				foreach($query->rows as $item){
					$items .= $item['name'] . " (" . $item['qty'] . ") <br>";
					$items_on_hand .= $item['name'] . " (" . $item['qty_on_hand'] . ") <br>";
					$cost += $item['qty'] * $item['price'];
					$cost_on_hand += $item['qty_on_hand'] * $item['price'];
				}
				$return = array();
				$return['request_id'] = $request['request_id'];
				$return['user_group_id'] = $request['user_group_id'];
				$return['user_group_from'] = $request['user_group_from'];
				$return['created_by'] = $request['created_by'];
				$return['creator_fullname'] = $request['creator_fullname'];
				$return['date_added'] = $request['date_added'];
				$return['payment_option'] = $request['payment_option'];
				$return['payment_option_desc'] = $request['payment_option_desc'];
				$return['approved_by'] = $request['approved_by'];
				$return['approval_date'] = $request['approval_date'];
				$return['status'] = $request['status'];
				$return['transfer_reference'] = $request['transfer_reference'];
				$return['qty'] = $items;
				$return['qty_on_hand'] = $items_on_hand;
				$return['total_cost'] = $cost;
				$return['total_cost_on_hand'] = $cost_on_hand;
				$returnFinal[$ctr] = $return;
				$ctr++;

			}

			return $returnFinal;			
		} else {
			$sqlt = "select count(1) total from (".$sql.") t ";
			$query = $this->db->query($sqlt);
			return $query->row['total'];			
		}
	}
	public function getApprovedRequestList($data = array(), $query_type = "data") {
		$sql = "select a.request_id,a.qty, b.username 'created_by', a.request_to,
					a.date_added,e.username 'approved_by',a.approval_date, f.description status, a.transfer_reference,
					a.user_group_id, a.user_group_from,a.request_from,
					concat(b.firstname, ' ', b.lastname) creator_fullname,
					a.payment_option, g.description payment_option_desc
				  from oc_request a
				  inner join oc_user b on (a.created_by=b.user_id)
				  left join oc_user e on (a.approved_by = e.user_id)
				  left join gui_status_tbl f on(a.status = f.status_id) 
				  left join gui_status_tbl g on(a.payment_option = g.status_id) 
				WHERE 1 = 1  ";
		
		if($this->user->getUserGroupId() == 39 or $this->user->getUserGroupId() == 43 ) {
			$sql .= " and b.user_id = ".$this->user->getId()." and a.status in (77)";
		} elseif($this->user->getUserGroupId() == 44) {
			$sql .= " and a.request_to = ".$this->user->getId()." and a.status in (77)";
		} elseif($this->user->getUserGroupId() == 77) {
			$sql .= " and a.user_group_id = 77 and j.merchant_id = ".$this->user->getMerchantId()." or a.user_group_from = 77 and b.user_id = ".$this->user->getId();
		} elseif($this->user->getUserGroupId() == 78) {
			$sql .= " and a.user_group_id = 78 and h.warehouse_id = ".$this->user->getWarehouseId()." or a.user_group_from = 78 and b.user_id = ".$this->user->getId();
		}
		
		if(isset($data['request_id_search'])){
			if(!empty($data['request_id_search'])){
				$sql .= " AND a.request_id=".$data['request_id_search'];
			}
		}
		
		if(isset($data['datefrom'])){
			if (!empty($data['datefrom'])) {
				$sql .= " AND a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
		}
		
		if(isset($data['dateto'])){
			if (!empty($data['dateto'])) {
				$sql .= " AND a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}

		if(isset($data['branch_id'])){
			if (!empty($data['branch_id'])) {
				$sql .= " AND a.request_to = " . $data['branch_id'];
			}
		}
		// echo $sql .'search';
		if($query_type == "data") {
			
			$sql .= " ORDER BY a.approval_date desc";

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
			$returnFinal = array();
			$ctr = 0;
			foreach($query->rows as $request){
				
				$sql = "SELECT a.qty, a.qty_on_hand, b.price, b.item_name name 
						  FROM oc_request_details a 
						  JOIN gui_items_tbl b on (a.item_id = b.item_id) 
						 WHERE request_id = " . $request['request_id'];
				$query = $this->db->query($sql);
				
				$items = "";
				$items_on_hand = "";
				$cost = 0.00;
				$cost_on_hand = 0.00;
				
				foreach($query->rows as $item){
					$items .= $item['name'] . " (" . $item['qty'] . ") <br>";
					$items_on_hand .= $item['name'] . " (" . $item['qty_on_hand'] . ") <br>";
					$cost += $item['qty'] * $item['price'];
					$cost_on_hand += $item['qty_on_hand'] * $item['price'];
				}
				$return = array();
				$return['request_id'] = $request['request_id'];
				$return['user_group_id'] = $request['user_group_id'];
				$return['user_group_from'] = $request['user_group_from'];
				$return['created_by'] = $request['created_by'];
				$return['creator_fullname'] = $request['creator_fullname'];
				$return['date_added'] = $request['date_added'];
				$return['payment_option'] = $request['payment_option'];
				$return['payment_option_desc'] = $request['payment_option_desc'];
				$return['approved_by'] = $request['approved_by'];
				$return['approval_date'] = $request['approval_date'];
				$return['status'] = $request['status'];
				$return['transfer_reference'] = $request['transfer_reference'];
				$return['qty'] = $items;
				$return['qty_on_hand'] = $items_on_hand;
				$return['total_cost'] = $cost;
				$return['total_cost_on_hand'] = $cost_on_hand;
				$returnFinal[$ctr] = $return;
				$ctr++;

			}

			return $returnFinal;			
		} else {
			$sqlt = "select count(1) total from (".$sql.") t ";
			$query = $this->db->query($sqlt);
			return $query->row['total'];			
		}
	}
	public function checkapewallet($req_id){
		$return_flag = true;
		
		$sql = "select ap_ewallet from oc_user where user_id = " . $this->user->getId();
		$query = $this->db->query($sql);
		$ap_ewallet = $query->row['ap_ewallet'];
		
		$sql = "select sum(a.qty_on_hand * b.price) total_cost 
				 from oc_request_details a 
				 join gui_items_tbl b on(a.item_id = b.item_id)
				where request_id =  '" . $req_id['request_id'] . "'";
		$query = $this->db->query($sql);
		$total_cost = $query->row['total_cost'];
		
		if($total_cost > $ap_ewallet){ 	
			$return_flag = false;
		} 
		return $return_flag;
	}
	
	public function updateStatusviaApewallet($status, $req_id){
		
		$sql = "select coalesce(sum(a.qty_on_hand * b.price),0) total_cost 
				 from oc_request_details a 
				 join gui_items_tbl b on(a.item_id = b.item_id)
				where request_id =  '" . $req_id['request_id'] . "'";
		$query = $this->db->query($sql);
		$total_cost = $query->row['total_cost'];
		
		$sql = "update oc_request 
					set status = " . $status . "
					,   ap_ewallet = ". $total_cost."
					,	mode_of_payment = 163
					where request_id = '" . $req_id['request_id'] . "'";
		$this->db->query($sql);
		
		$sql = "UPDATE oc_user 
				   SET ap_ewallet = ap_ewallet - ". $total_cost."
				 WHERE user_id = " . $this->user->getId();
		$this->db->query($sql);
		
		$sql = "INSERT INTO ap_ewallet_hist 
				   SET user_id = ". $this->user->getId()."
					 , commission_type_id = 44
					 , debit = ".$total_cost."
					 , req_id = '" . $req_id['request_id'] . "'
					 , remarks = 'DEDUCTION FROM INVENTORY TRANSFER'
					 , created_by = ".$this->user->getBranchId()."
					 , date_added = '".$this->user->now()."' ";		
		$this->db->query($sql);
	}
	
	// public function adjustInventoryRequest($data){
		// $sql = "SELECT item_id FROM oc_request_details WHERE transfer_detail_id=".$data['request_detail_id'];
		// $query = $this->db->query($sql);
		// $itemId = $query->row['item_id'];
		
		// $sql = "SELECT count(1) count FROM gui_inventory_tbl WHERE branch_id=17 AND item_id=".$itemId;
		// $query = $this->db->query($sql);
		// $count = $query->row['count'];
		
		// if($count ==0){
			// return "You have no available stock for this item.";
		// }
		
		// $sql = "UPDATE oc_request_details SET qty_on_hand=".$data['quantity_sel']." WHERE transfer_detail_id=".$data['request_detail_id'];
		// $query = $this->db->query($sql);
		
		// $sql = "UPDATE oc_request SET status=75 WHERE request_id=".$data['request_id'];
		// $query = $this->db->query($sql);
		
		// return "Successful modification of request quantity.";
	// }

	
	public function getSmeflag() {
		$sql = "select sme_flag from gui_branch_tbl where branch_id = ".$this->user->getBranchId();
		$query = $this->db->query($sql);
		return $query->row['sme_flag'];
	}
	
	public function getRequestListForCSV($data) {
		
		$sql = "select a.request_id,c.description 'branchFrom',d.description 'branchTo',a.qty,b.username 'created_by',
					a.date_added,e.username 'approved_by',a.approval_date, f.description status
				  from oc_request a
				  inner join oc_user b on a.created_by=b.user_id
				  left join oc_user e on a.approved_by = e.user_id
				  left join gui_branch_tbl c on a.request_from=c.branch_id
				  left join gui_branch_tbl d on a.request_to=d.branch_id
				  left join gui_status_tbl f on(a.status = f.status_id) 
				  where 1 = 1 ";		

		if($this->user->getBranchId()!=17){
			$sql .= " and a.request_to=".$this->user->getBranchId();
		}
		
		if(isset($data['request_id_search'])){
			if(!empty($data['request_id_search'])){
				$sql .= " AND a.request_id=".$data['request_id_search'];
			}
		}
		
		if(isset($data['datefrom'])){
			if (!empty($data['datefrom'])) {
				$sql .= " AND a.date_added >= '" . $data['datefrom'] . " 00:00:00'";
			}	
		}
		
		if(isset($data['dateto'])){
			if (!empty($data['dateto'])) {
				$sql .= " AND a.date_added <= '" . $data['dateto'] . " 23:59:59'";
			}
		}

		if(isset($data['branch_id'])){
			if (!empty($data['branch_id'])) {
				$sql .= " AND a.request_to = " . $data['branch_id'];
			}
		}

		if(isset($data['status_id'])){
			if (!empty($data['status_id'])) {
				$sql .= " AND a.status = " . $data['status_id'];
			}
		}
		
		
		$sql .= " ORDER BY a.date_added desc";
		//echo $sql."<br>";		
				
		$query = $this->db->query($sql);
				$returnFinal = array();
		$ctr = 0;
		foreach($query->rows as $request){
			
			$sql = "SELECT a.qty, a.qty_on_hand, b.price, b.description name 
					  FROM oc_request_details a 
					  JOIN gui_items_tbl b on (a.item_id = b.item_id) 
					 WHERE request_id = " . $request['request_id'];
			$query = $this->db->query($sql);
			
			$items = "";
			$items_on_hand = "";
			$cost = 0.00;
			$cost_on_hand = 0.00;
			
			foreach($query->rows as $item){
				$items .= $item['name'] . " (" . $item['qty'] . ") , ";
				$items_on_hand .= $item['name'] . " (" . $item['qty_on_hand'] . ") ,";
				$cost += $item['qty'] * $item['cost'];
				$cost_on_hand += $item['qty_on_hand'] * $item['cost'];
			}
			$return = array();
			$return['request_id'] = $request['request_id'];
			$return['branchFrom'] = $request['branchFrom'];
			$return['branchTo'] = $request['branchTo'];
			$return['created_by'] = $request['created_by'];
			$return['date_added'] = $request['date_added'];
			$return['approved_by'] = $request['approved_by'];
			$return['approval_date'] = $request['approval_date'];
			$return['status'] = $request['status'];
			$return['qty'] = $items;			
			$return['total_cost'] = $cost;
			$return['qty_on_hand'] = $items_on_hand;
			$return['total_cost_on_hand'] = $cost_on_hand;
			$returnFinal[$ctr] = $return;
			$ctr++;

		}
	}
		
	public function checkTransferRef($ref){
		$sql = "select count(1) count from oc_request where transfer_reference = '" . $ref . "'";
		$query = $this->db->query($sql);

		return $query->row['count'];
	}
	
	public function getTransferId($ref){

		$sql = "select request_id from oc_request where transfer_reference = '" . $ref . "'";
		$query = $this->db->query($sql);
		return $query->row['request_id'];

	}
	
	public function updateStatus($status, $ref){
		$sql = "update oc_request 
							set status = " . $status . " 
							,	mode_of_payment = 138
							where transfer_reference = '" . $ref . "'";
		$this->db->query($sql);

	}
	
	public function updateExtension($ext, $ref){

		$sql = "update oc_request set extension = '" . '.' . $ext . "' where transfer_reference = '" . $ref . "'";
		$this->db->query($sql);

	}
	
	public function addRemark($data){

		$sql = "select request_id from oc_request where transfer_reference = '" . $data['reference'] . "'";
		$query = $this->db->query($sql);
		$header_id = $query->row['request_id'];

		$sql = "INSERT into oc_transfer_comments SET user_id = " . $this->user->getId() . ", request_id = ". $header_id .", remark = '". $data['remark'] ."', date_added = '". $this->user->now() ."'";
		$query = $this->db->query($sql);

	}
	
	public function getTransferRefDetails($ref) {
		$sql = "select a.request_id, a.qty, a.status, b.description ,a.transfer_reference, a.extension
				  from oc_request a
				  join gui_status_tbl b on (b.status_id = a.status)
				 where a.transfer_reference= '".$ref."'";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getRemarks($ref){

		$sql = "select request_id from oc_request where transfer_reference = '" . $ref . "'";
		$query = $this->db->query($sql);
		$header_id = $query->row['request_id'];

		$sql = "select concat(b.firstname, ' ', b.lastname) fullname, a.remark, a.date_added from oc_transfer_comments a join oc_user b on (a.user_id = b.user_id) where request_id = " . $header_id . " order by comment_id desc";
		$query = $this->db->query($sql);
		return $query->rows;

	}
	
	public function getRequestingBranchDetails($data){
		$sql = "SELECT a.*,b.description 'requestingBranch' FROM oc_request a INNER JOIN gui_branch_tbl b on (a.request_to=b.branch_id) WHERE a.request_id=".$data['request_id'];
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function seeifpaidbyAPewallet($data){
		$sql = "SELECT ap_ewallet FROM oc_request  WHERE request_id=".$data['request_id'];
		$query = $this->db->query($sql);
		return $query->row['ap_ewallet'];
	}
	
	public function updateFileExtension($request_id, $ext) {
		$sql = "update oc_request 
				   set ext = '".$ext."'
				 where request_id = ".$request_id;
		$this->db->query($sql);
	}
	
	public function clearUploadedFiles($req_id) {
		$sql = "update oc_request 
				   set ext = null
				 where request_id = ".$req_id;
		$this->db->query($sql);
	}
	
	public function getFileExt($req_id) {
		$sql = "select ext
				  from oc_request 
				 where request_id = ".$req_id;
		$query = $this->db->query($sql);
		return $query->row['ext'];
	}
	
	public function cancelRequest($data) {
		//var_dump($data);
		$return_msg = "";
		if(isset($data['selected'])) {
			foreach($data['selected'] as $sel) {
				$sql = "select `status` cur_status
						  from oc_request 
						 where request_id = ".$sel;
				$query = $this->db->query($sql);
				$cur_status = $query->row['cur_status'];
				
				if($cur_status == 72) {
					$sql = "update oc_request 
							   set `status` = 155 
							 where request_id = ".$sel;
					$this->db->query($sql);
					$return_msg .= "Inventory Request# ".$sel." is is cancelled.<br>";
				} else {
					$return_msg .= "Inventory Request# ".$sel." is not cancelled because it is not in Requested status.<br>";
				}
			}
		} else {
			$return_msg .= "No request for cancellation.";
		}
		
		return $return_msg;
	}
	
}
?>