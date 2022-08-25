<?php
class ModelAccountShippingRateMaint extends Model {
	public function addRate($data) {
		$result = array();
		$result['err_msg'] = "";
		$result['rate_id'] = 0;
		$valid = 1;
		
		if(!isset($data['status_id'])) {
			$valid = 0;
			$result['err_msg'] .= "Payment Option is Mandatory. <br>";
		} else {
			if(empty($data['status_id'])) {
				$valid = 0;
				$result['err_msg'] .= "Payment Option is Mandatory. <br>";
			}
		}
		
		if(!isset($data['rate'])) {
			$valid = 0;
			$result['err_msg'] .= "Rate is Mandatory. <br>";
		} else {
			if(empty($data['rate'])) {
				$valid = 0;
				$result['err_msg'] .= "Rate is Mandatory. <br>";
			}
		}
		
		if(!isset($data['quantity'])) {
			$valid = 0;
			$result['err_msg'] .= "Delivery Points is Mandatory. <br>";
		} else {
			if(empty($data['quantity'])) {
				$valid = 0;
				$result['err_msg'] .= "Delivery Points is Mandatory. <br>";
			}
		}
		
		if($valid == 1) {
			try {
				$sql  = "INSERT INTO oc_shipping_rates 
						SET rate = ".$data['rate']."
					   ,payment_option = ".$data['status_id']."
					   ,quantity = ".$data['quantity'];
			
				$this->db->query($sql);
				
				$rate_id = $this->db->getLastId();
				
				$result['rate_id'] = $rate_id;
				$result['err_msg'] .= "Successful Creation of Payment Option ".$data['status_id']."<br>"; 
			} catch (Exception $e) {
				$result['err_msg'] .= "Error in Creating Payment Option.";			
			}	
		}
		
		return $result;
	}
	
	public function submitEdit($data) {
		$result = array();
		$result['err_msg'] = "";
		$result['rate_id'] = 0;
		$valid = 1;
		
		if(!isset($data['status_id'])) {
			$valid = 0;
			$result['err_msg'] .= "Payment is Mandatory. <br>";
		} else {
			if(empty($data['status_id'])) {
				$valid = 0;
				$result['err_msg'] .= "Payment is Mandatory. <br>";
			}
		}
		
		if(!isset($data['quantity'])) {
			$valid = 0;
			$result['err_msg'] .= "Delivery Points is Mandatory. <br>";
		} else {
			if(empty($data['quantity'])) {
				$valid = 0;
				$result['err_msg'] .= "Delivery Points is Mandatory. <br>";
			}
		}
		
		if(!isset($data['rate'])) {
			$valid = 0;
			$result['err_msg'] .= "Rate is Mandatory. <br>";
		} else {
			if(empty($data['rate'])) {
				$valid = 0;
				$result['err_msg'] .= "Rate is Mandatory. <br>";
			}
		}

		if($valid == 1) {
			try {
				$sql  = "UPDATE oc_shipping_rates 
							SET rate = ".$data['rate']."
								,payment_option = ".$data['status_id']."
								,quantity = ".$data['quantity']."
						  WHERE rate_id = ".$data['rate_id'];
			
				$this->db->query($sql);
				
				$rate_id = $this->db->getLastId();
				
				$result['rate_id'] = $rate_id;
				$result['err_msg'] .= "Successful Update of Rate ID".$data['rate_id']."<br>"; 
			} catch (Exception $e) {
				$result['err_msg'] .= "Error in Updating Rate ".$data['rate_id'];			
			}	
		}
		
		return $result;
	}	

	public function removeRate($rate_id) {
		$sql = "select payment_option from oc_shipping_rates where rate_id = ".$rate_id;
		$query = $this->db->query($sql);
		$payment_option = $query->row['payment_option'];
		
		$sql = "delete from oc_shipping_rates where rate_id = ".$rate_id;
		$this->db->query($sql);
		
		return "Successful Delete of Shipping Rate ".$rate_id."<br>";
	}	
	
	public function getRates($data = array(), $query_type) {

		$sql  = "SELECT b.description payment_option, 
						a.rate, a.quantity,rate_id
				   FROM oc_shipping_rates a 
				   LEFT JOIN gui_status_tbl b on(a.payment_option = b.status_id)
				   WHERE 1 = 1  ";	
				   
		if(isset($data['status_id'])) {
			if (!empty($data['status_id'])) {
				$sql .= " AND lower(a.payment_option) like '%" . strtolower($data['status_id']). "%'";
			}
		}
		
		if(isset($data['quantity'])) {
			if (!empty($data['quantity'])) {
				$sql .= " AND lower(a.quantity) like '%" . strtolower($data['quantity']). "%'";
			}
		}
		
		if(isset($data['rate'])) {
			if (!empty($data['rate'])) {
				$sql .= " AND lower(a.rate) like '%" . strtolower($data['rate']). "%'";
			}
		}


		if($query_type == "data") {
			$sql .= " ORDER BY a.rate_id ";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 6;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
			return $query->rows;
		} else {
			$sqlt = "select count(1) total from (".$sql.") t ";
			$query = $this->db->query($sqlt);
			return $query->row['total'];
		}
	
	}
	
	public function getRatesExport($data) {
		
		$sql  = "SELECT b.description payment_option, 
						a.rate, a.quantity
				   FROM oc_shipping_rates a 
				   LEFT JOIN gui_status_tbl b on(a.payment_option = b.status_id)
				   WHERE 1 = 1  ";		
		//paymentoption
		
		//withincity
		
		$sql .= " ORDER BY a.rate_id ";
			
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 6;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);
		return $query->rows;
	
	}
	
	public function getPayments($data = array()) {
		$sql = "SELECT status_id, description 
				  FROM gui_status_tbl 
				 WHERE active = 1
				   and `grouping` = 'PAYMENT OPTION'
				   and `grouping`2 in ('EDSI','DROPSHIP')
				  ORDER BY description ";			
		$query = $this->db->query($sql);		
		return $query->rows;
	}


	public function getRateDetails($rate_id) {
		
		$sql  = "SELECT a.rate_id,b.description,b.status_id
				       ,a.rate, a.quantity
				   FROM oc_shipping_rates a 
				   LEFT JOIN gui_status_tbl b on(a.payment_option = b.status_id)
				   WHERE a.rate_id = ".$rate_id;	

		$query = $this->db->query($sql);
		//echo "<br/>".$sql;
		return $query->row;
	
	}

}
?>