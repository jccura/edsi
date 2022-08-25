<?php
class ModelApiStatusApi extends Model {
	
	public function updateStatusOrder($data) {
		$return_msg = "";
		$valid = 1;	
		
		if(isset($data['order_id'])) {
			if(empty($data['order_id'])){
				$valid = 0;
				$return_msg = "Order ID is required. <br>";
			}
		} else {
			$valid = 0;
			$return_msg = "Order ID is required. <br>";
		}
		
		if(isset($data['manong_status'])) {
			if(empty($data['manong_status'])){
				$valid = 0;
				$return_msg = "Status is required. <br>";
			}
		} else {
			$valid = 0;
			$return_msg = "Status is required. <br>";
		}
		
		if($valid == 1) {
			
			$sql = "select status_id, description from gui_status_tbl where manong_status = '".$data['manong_status']."' ";
			$query = $this->db->query($sql);
			$status_id = $query->row['status_id'];
			$description = $query->row['description'];

			$sql = "update oc_order 
					   set status_id = ".$status_id."
						  ,modified_date = '".$this->user->now()."'
					where order_id = ".$data['order_id'];			
			$query = $this->db->query($sql);
			
			// add order status history
			$sql = "select order_hist_id, to_status_id
						from oc_order_hist_tbl
					  where order_id = ".$this->db->escape($data['order_id'])."
					  order by order_hist_id desc limit 1";
			$query = $this->db->query($sql);
			$from_status_id = $query->row['to_status_id'];
			
			$sql = "select barangay_id from oc_order where order_id = ".$this->db->escape($data['order_id']);
			$query = $this->db->query($sql);
			$barangay_id = $query->row['barangay_id'];
			
			$sql = "select a.branch_id, b.user_id 
					  from oc_barangays a 
					  left join gui_branch_tbl b on (a.branch_id = b.branch_id)
					 where a.barangay_id = ".$this->db->escape($barangay_id);
			$query = $this->db->query($sql);
			$branch_id = $query->row['branch_id'];			
			$user_id = $query->row['user_id'];			
			
			$sql = "select description from gui_status_tbl where status_id = ".$status_id;
			$query = $this->db->query($sql);
			$description = $query->row['description'];
			
			$sql = "insert into oc_order_hist_tbl
						set user_id = ".$this->db->escape($user_id).",
						    branch_id = ".$this->db->escape($branch_id).",
							order_id = ".$this->db->escape($data['order_id']).",
							from_status_id = ".$this->db->escape($from_status_id).",
							to_status_id = ".$this->db->escape($status_id).",
							remarks = '".$this->db->escape($description)."',
							date_added = '".$this->user->now()."' ";
			$this->db->query($sql);
			
			if($status_id == 119) {
				$this->load->model('account/orders');
				$ordersArray = array();
				$ordersArray['order_id'] = $data['order_id'];
				$ordersArray['status_id'] = $status_id;
				$this->model_account_orders->tagOrdersAsPaidApi($ordersArray);
			}
			
			$sql = "SELECT user_id FROM oc_order WHERE order_id = ".$this->db->escape($data['order_id']);			

			$user_id = $this->db->query($sql)->row['user_id'];

			// add notification
			$sql = "INSERT INTO oc_notification
					SET title = 'Order #".$this->db->escape($data['order_id'])."'
					,	user_id = ".$this->db->escape($user_id)."
					,	description = 'Order #".$this->db->escape($data['order_id'])." status updated to \'".$this->db->escape(empty($data['sop_status']) ? $description : $data['sop_status'])."\''
					,	date_added = '".$this->user->now()."'";

				$query = $this->db->query($sql);
				
			return "Order Status Updated.";
			
		} else {			
			return $return_msg;
		}
	}

}
?>