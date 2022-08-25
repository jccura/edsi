<?php
class ModelApiRemarksApi extends Model {
	
	public function SendRemarks($data = array()){
		
		$sql = "select order_id, user_id, ticket_id from oc_order where ref = '" . $data['ref'] . "'";
		// echo $sql."<br>";
		// var_dump($sql);
		$query = $this->db->query($sql);
		$header_id = $query->row['order_id'];
		$user_id = $query->row['user_id'];
		$ticket_id = $query->row['ticket_id'];
		$remarks = $this->db->escape($data['remark']);
		
		$sql = "select payment_option from oc_order where order_id = ".$header_id;
		$query = $this->db->query($sql);
		$payment_option = $query->row['payment_option'];
		
		if($payment_option == 93 || $payment_option == 106) {
			$order_id = $this->db->escape($header_id);
			$remarks = $this->db->escape($data['remark']);
			
			$access_key = "a428d34900a57eaecda71bceeb94c320";
			$cInit = curl_init("http://manongexpress.com/demoshop/remarksapi");
			curl_setopt_array($cInit, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'task' => "receiveremarks",
					'access_key' => $access_key,
					'remarks' => $remarks,
					'order_id' => $order_id
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
		}
	
	}
	
	public function ReceiveRemarks($data) {
		$return_msg = "";
		$valid = 1;	
		$order_id = $this->db->escape($data['order_id']);
		$remarks = $this->db->escape($data['remarks']);
		
		if(isset($data['order_id'])) {
			if(empty($data['order_id'])){
				$valid = 0;
				$return_msg = "Order ID is required. <br>";
			}
		} else {
			$valid = 0;
			$return_msg = "Order ID is required. <br>";
		}
		
		
		if($valid == 1) {
			$sql = "select branch_id, user_id, ticket_id from oc_order where order_id = ".$data['order_id'];
			$query = $this->db->query($sql);
			$ticket_id = $query->row['ticket_id'];
			$user_id = $query->row['user_id'];
			$branch_id = $query->row['branch_id'];
			
			if($ticket_id == 0) {
				// $sql = "update oc_order set ticket_id = 2 where order_id = ".$order_id;
				// $this->db->query($sql);
				
				$sql = "insert into oc_ticket_header
							set source = 8568
								,recipient = ".$user_id."
								,branch_id = ".$branch_id."
								,concern_content = '".$remarks."'
								,date_added = '".$this->user->now()."'
								,order_id = ".$order_id;
				$this->db->query($sql);
				$ticket_id = $this->db->getLastId();
				
				$sql = "insert into oc_ticket_content
							set ticket_id = ".$ticket_id."
								,source = 8568
								,concern_content = '".$remarks."'
								,date_added = '".$this->user->now()."' ";
				$this->db->query($sql);
				
				$sql = "insert into oc_order_comments
							set user_id = 8568
								,order_id = ".$order_id."
								,remark = '".$remarks."'
								,date_added = '".$this->user->now()."' ";
				$this->db->query($sql);
				
				$sql = "update oc_order 
							set remarks = '".$remarks."'
								,ticket_id = ".$ticket_id."
						  where order_id = ".$order_id;
				$this->db->query($sql);
			} else {
				$sql = "update oc_order set remarks = '".$remarks."' where order_id = ".$order_id;
				$query = $this->db->query($sql);
				
				$sql = "INSERT into oc_order_comments SET user_id = 8568, order_id = ". $order_id .", remark = '". $remarks ."', date_added = '". $this->user->now() ."'";
				$query = $this->db->query($sql);
			}
			
		} else {		
			return $return_msg;
		}
	}
	
	public function getAccessKey($data){
		$access_key = $data['access_key'];
		$sql = "select merchant_id
				  from oc_merchant 
				  where access_key = '".$access_key."' ";
		$query = $this->db->query($sql);				
		if(isset($query->row['merchant_id'])) {			
			return $query->row['merchant_id'];		
		} else {			
			return 0;		
		}
	}
}
?>