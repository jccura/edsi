<?php
class ModelAccountUpgradeAcc extends Model {	

	public function upgradeAccount($data){
		
		$return_array = array();
		$valid = 1;
		$return_msg = "";
		
		if(isset($data['serial_code'])) {			
			if(empty($data['serial_code'])) {				
				$valid = 0;					
				$return_msg .= "Serial Code is mandatory. ";			
			} else {
				$sql = "select count(1) total, a.used_flag, a.item_id, a.serial_id, a.order_id ,b.package_type
						  from oc_serials a
						  left join gui_items_tbl b on (a.item_id = b.item_id)
						 where lower(a.serial_code) = '".strtolower($this->db->escape($data['serial_code']))."'";
				$query = $this->db->query($sql);
				$serial_details = $query->row;
				if($serial_details['package_type'] != 2) {
					$valid = 0;					
					$return_msg .= "Only Distributor package serial can be used. ";
				} else {
					if($serial_details['total'] == 0) {
						$valid = 0;					
						$return_msg .= "Serial Code is not existing. ";
					} else {
						if($serial_details['used_flag'] == 1) {
							$valid = 0;				
							$return_msg .= "Serial Code is already used. ";
						} else {
							$item_id = $serial_details['item_id'];
							$package_type = $serial_details['package_type'];
							$serial_id = $serial_details['serial_id'];
							$order_id = $serial_details['order_id'];
							$sql = "select user_group_id, direct_referral,package_type
									  from gui_items_tbl 
									 where item_id = ".$item_id;
							$query = $this->db->query($sql);
							$user_group_id = $query->row['user_group_id'];
							$direct_referral = $query->row['direct_referral'];
						}
					}
				}	
			}		
		} else {			
			$valid = 0;		
			$return_msg .= "Serial Code is mandatory. ";		
		}
		
		if($valid == 1){			
			$sql  = "UPDATE oc_user 
						SET user_group_id = ".$user_group_id."
						   ,item_id = ".$item_id."					   
						   ,date_modified = '".$this->user->now()."'
					  WHERE user_id = ".$this->user->getId();
			
			
			$this->db->query($sql);						
			$user_id = $this->user->getId();	
			
			$sql = "update oc_serials 
					   set user_id = ".$user_id."
						  ,used_flag = 1
						  ,date_used = '".$this->user->now()."'
					 where serial_id = ".$serial_id;
			$this->db->query($sql);				
			$return_msg = "Registration Successful";

			//distribute referral commission
				
			$sql = "select sponsor_user_id 
					  from oc_unilevel 
					 where user_id =".$user_id;
			$query = $this->db->query($sql);				
			$sponsor_id = $query->row['sponsor_user_id'];				
			
			if($direct_referral > 0 and $package_type == 2) {
				$this->load->model('account/orders');
				$this->model_account_orders->insertEwallet($direct_referral, $sponsor_id, $user_id, $order_id,2, 1, 1);
			}
			$return_msg .= "Account was Successfully Upgrade";
		}			
		
		$return_array['result'] = $valid;		
		$return_array['result_msg'] = $return_msg;		
		return $return_array;	
	}
		
}

?>