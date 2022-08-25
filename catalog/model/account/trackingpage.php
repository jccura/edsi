<?php
class ModelAccountTrackingpage extends Model {
	
	public function getTrackOrderHeader($data){
		
		if(isset($this->session->data['order_id'])) {
			$order_id = $this->session->data['order_id'];
		}
		
		if(isset($data['order_id'])) {
			if($data['order_id'] > 0) {
				$order_id = $data['order_id'];
			}
		}
		
		if(isset($data['ref'])) {
			if(!empty($data['ref'])) {
				$sql = "select order_id from oc_order where ref = '".$data['ref']."' ";
				$query = $this->db->query($sql);
				$order_id = $query->row['order_id'];
			}
		}
		
		$sql = "select a.order_id, a.payment_option, a.receiver, a.tracking, a.notes, a.landmark, a.delivery_option,
					   c.description status_desc,case when a.payment_option in(148,146,147) 
					   then concat(b.description,'(',i.username,')') else b.description end payment_option_desc, g.description mod_desc,
					   a.customer_name, concat(a.address, ', ', f.description, ', ', e.description, ', ', d.description) address,
					   h.username admin, a.reference, a.paid_flag, a.page_id, a.status_id, a.mode_of_collection, a.extension,
					   a.contact, a.email, case when a.paid_flag = 1 then 'Paid' else 'Not yet paid' end paid_desc,
					   a.amount total, a.discount, a.delivery_fee, a.receiving_branch, a.ref, a.remarks, a.ticket_id
				  from oc_order a
				  left join gui_status_tbl b on(a.payment_option = b.status_id)
				  left join gui_status_tbl c on(a.status_id = c.status_id)
				  left join oc_provinces d on(a.province_id = d.province_id)
				  left join oc_city_municipality e on(a.city_municipality_id = e.city_municipality_id)
				  left join oc_barangays f on(a.barangay_id = f.barangay_id)
				  left join gui_status_tbl g on(a.delivery_option = g.status_id)
				  left join oc_user h on(a.user_id = h.user_id)
			          left join oc_user i on(a.city_distributor_id = i.user_id)
				  where order_id =" .$order_id;
		$query = $this->db->query($sql);
		//echo $sql."<br>";
		return $query->row;
	}
	
	public function getOrderDetails($data){
		
		if(isset($this->session->data['order_id'])) {
			$order_id = $this->session->data['order_id'];
		}
		
		if(isset($data['order_id'])) {
			if($data['order_id'] > 0) {
				$order_id = $data['order_id'];
			}
		}
		
		if(isset($data['ref'])) {
			if(!empty($data['ref'])) {
				$sql = "select order_id from oc_order where ref = '".$data['ref']."' ";
				$query = $this->db->query($sql);
				$order_id = $query->row['order_id'];
			}
		}
		
		$sql = "select b.item_name description, a.quantity, b.price * a.quantity amount, 
					  (b.price * b.reseller_discount_per * a.quantity / 100) reseller_disc, 
					  (b.price * b.distributor_discount_per * a.quantity / 100) distributor_disc
				  from oc_order_details a
				  left join gui_items_tbl b on(a.item_id = b.item_id)
				 where order_id =" .$order_id;
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTrackOrders($data) {
		$order_id = $data['order_id'];
		
		$sql = "select b.item_name description, a.quantity, b.price * a.quantity amount
				  from oc_order_details a
				  left join gui_items_tbl b on(a.item_id = b.item_id)
				 where order_id =" .$order_id;
		$query = $this->db->query($sql);
		// echo $sql."<br>";
		
		$finalString = "";

		foreach ($query->rows as $key => $value) {
			$finalString .= "<tr>
								<td>". $value['description'] ."</td>
								<td>". $value['quantity'] ."</td>
								<td>". $value['amount'] ."</td>
							</tr>";
		}
		
		return $finalString;
	}
	
	public function getPaymentOption($data){
		
		if(isset($this->session->data['order_id'])) {
			$order_id = $this->session->data['order_id'];
			// echo $order_id ." 1<br>";
		}
		
		if(isset($data['order_id'])) {
			if($data['order_id'] > 0) {
				$order_id = $data['order_id'];
				// echo $order_id ." 2<br>";
			}
		}
		
		if(isset($data['ref'])) {
			if(!empty($data['ref'])) {
				$sql = "select order_id from oc_order where ref = '".$data['ref']."' ";
				$query = $this->db->query($sql);
				$order_id = $query->row['order_id'];
				// echo $order_id ."<br>";
				// echo $sql."<br>";
			}
		}
		
		$sql = "select payment_option 
				  from oc_order
				 where order_id = " .$order_id;
		$query = $this->db->query($sql);
		// echo $sql."<br>";
		return $query->row;
	}
}
?>