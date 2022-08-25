<?php
class ModelAccountItemSetup extends Model {
	public function addItem($data) {
		$result = array();
		$result['err_msg'] = "";
		$result['item_id'] = 0;
		$valid = 1;
		
		if(!isset($data['item_code'])) {
			$valid = 0;
			$result['err_msg'] .= "Item Code is Mandatory. <br>";
		}
		
		if(isset($data['category_id'])){
			
		}
		
		if(!isset($data['item_name'])) {
			$valid = 0;
			$result['err_msg'] .= "Item Name is Mandatory. <br>";
		}
		
		if(!isset($data['short_description'])) {
			$valid = 0;
			$result['err_msg'] .= "Short Description is Mandatory. <br>";
		}
		
		if(!isset($data['description'])) {
			$valid = 0;
			$result['err_msg'] .= "Long Description is Mandatory. <br>";
		}
		
		if(!isset($data['price'])) {
			$valid = 0;
			$result['err_msg'] .= "Price is Mandatory. <br>";

		}

		
		if(!isset($data['active'])) {
			$valid = 0;
			$result['err_msg'] .= "Status is Mandatory. <br>";
		}
		
		if($valid == 1) {
			try {
				$sql  = "INSERT INTO gui_items_tbl 
						SET item_code = '" . $this->db->escape($data['item_code']) . "' 
					   ,item_name = '" . $this->db->escape($data['item_name']) . "'
					   ,cv = ".$data['cv']."
					   ,short_description = '" . $this->db->escape($data['short_description']) . "'
					   ,description = '" . $this->db->escape($data['description']) . "'
					   ,active = ".$data['active']."
					   ,price = ".$data['price']."
					   ,cost = ".$data['cost']."
					   ,system_fee = ".$data['system_fee']."
					   ,service_fee = ".$data['service_fee']."
					   ,top_up = ".$data['top_up']."
					   ,tools = ".$data['tool']."
					   ,tax = ".$data['tax']."
					   ,shipping = ".$data['shipping']."
					   ,income = ".$data['income']."
					   ,item_profit_per = ".$data['item_profit_per']."
					   ,distributor_discount_per = ".$data['distributor_discount_per']."
					   ,reseller_discount_per = ".$data['reseller_discount_per']."
					   ,direct_referral = ".$data['direct_referral']."
					   ,advance_payment = ".$data['advance_payment']."
					   ,points = ".$data['points']."
					   ,quantity = ".$data['quantity']."
					   ,sort = ".$data['sort']."
					   ,category_id = ".$data['category_id']."
					   ,date_added = '".$this->user->now()."'
					   ,user_group_id = ".$data['usergroup'];
			
				if(isset($data['category_id'])) {
					if($data['category_id'] == 2){
						$sql .= " ,raw = 1";
					}
				}
				
				$this->db->query($sql);
				
				$item_id = $this->db->getLastId();
				
				$result['item_id'] = $item_id;
				$result['err_msg'] .= "Successful Creation of Item ".$data['item_name']."<br>"; 
			} catch (Exception $e) {
				$result['err_msg'] .= "Error in Creating Item.";			
			}	
		}
		
		return $result;
	}
	
	public function addReview($data) {
		$result = array();
		$result['err_msg'] = "";
		$result['review_id'] = 0;
		$valid = 1;
		
		if(!isset($data['reviewed_by'])) {
			$valid = 0;
			$result['err_msg'] .= "Nickname is Mandatory. <br>";
		} else {
			if(empty($data['reviewed_by'])) {
				$valid = 0;
				$result['err_msg'] .= "Nickname is Mandatory. <br>";
			}
		}
		
		if(!isset($data['review'])) {
			$valid = 0;
			$result['err_msg'] .= "Review is Mandatory. <br>";
		} else {
			if(empty($data['review'])) {
				$valid = 0;
				$result['err_msg'] .= "Review is Mandatory. <br>";
			}
		}
		
		if($valid == 1) {
			try {
				$sql = "insert into oc_item_review
							set item_id = ".$data['item_id'].",
								reviewed_by = '" . $this->db->escape($data['reviewed_by']) . "',
								review = '" . $this->db->escape($data['review']) . "',
								date_added = '".$this->user->now()."' ";
								
				$this->db->query($sql);
				
				$review_id = $this->db->getLastId();
				
				$result['review_id'] = $review_id;
				$result['err_msg'] .= "Successful adding review.<br>"; 
			} catch (Exception $e) {
				$result['err_msg'] .= "Error adding review.<br/>";			
			}
		}
		return $result;
		
	}
	
	public function uploadPic($item_id, $file_extension){
		// $item_id = $this->db->getLastId();
		
		$sql  = "UPDATE gui_items_tbl
					SET main_flag = 1
						,main_extension = '".strtolower($file_extension)."'
				  WHERE item_id = ".$item_id;
		$this->db->query($sql);
	}
	
	public function uploadPic2($item_id, $file_extension){
		// $item_id = $this->db->getLastId();
		
		$sql  = "UPDATE gui_items_tbl
					SET main_flag2 = 1
						,main_extension2 = '".strtolower($file_extension)."'
				  WHERE item_id = ".$item_id;
		$this->db->query($sql);
	}
	
	public function uploadPicReview($review_id, $file_extension){
		// $item_id = $this->db->getLastId();
		
		$sql  = "UPDATE oc_item_review
					SET main_flag = 1
						,main_extension = '".strtolower($file_extension)."'
				  WHERE review_id = ".$review_id;
		$this->db->query($sql);
	}
	
	public function updaloadPicDetail($item_id, $file_extension, $i) {
		
		$sql  = "UPDATE gui_items_tbl
					SET flag_".$i." = 1
						,extension_".$i." = '".strtolower($file_extension)."'
				  WHERE item_id = ".$item_id;
		$this->db->query($sql);
	}
	
	public function editItem($data) {
		$result = array();
		$result['err_msg'] = "";
		$result['item_id'] = 0;
		$valid = 1;
		
		if(!isset($data['item_code'])) {
			$valid = 0;
			$result['err_msg'] .= "Item Code is Mandatory. <br>";
		} else {
			if(empty($data['item_code'])) {
				$valid = 0;
				$result['err_msg'] .= "Item Code is Mandatory. <br>";
			}
		}
		
		if(!isset($data['item_name'])) {
			$valid = 0;
			$result['err_msg'] .= "Item Name is Mandatory. <br>";
		} else {
			if(empty($data['item_name'])) {
				$valid = 0;
				$result['err_msg'] .= "Item Name is Mandatory. <br>";
			}
		}
		
		if(!isset($data['short_description'])) {
			$valid = 0;
			$result['err_msg'] .= "Short Description is Mandatory. <br>";
		} else {
			if(empty($data['short_description'])) {
				$valid = 0;
				$result['err_msg'] .= "Short Description is Mandatory. <br>";
			}
		}
		
		if(!isset($data['description'])) {
			$valid = 0;
			$result['err_msg'] .= "Long Description is Mandatory. <br>";
		} else {
			if(empty($data['description'])) {
				$valid = 0;
				$result['err_msg'] .= "Long Description is Mandatory. <br>";
			}
		}
		
		if(!isset($data['price'])) {
			$valid = 0;
			$result['err_msg'] .= "Price is Mandatory. <br>";
		} else {
			if(empty($data['price'])) {
				$valid = 0;
				$result['err_msg'] .= "Price is Mandatory. <br>";
			} else {
				if(!is_numeric($data['price'])) {
					$valid = 0;
					$result['err_msg'] .= "Price is a number. <br>";
				}
			}
		}
		
		
		if(!isset($data['active'])) {
			$valid = 0;
			$result['err_msg'] .= "Status is Mandatory. <br>";
		} 
		
		//var_dump($data);
		//return $result['err_msg'];
		
		if($valid == 1) {
			try {
				$sql  = "UPDATE gui_items_tbl 
							SET item_code = '" . $this->db->escape($data['item_code']) . "' 
							   ,item_name = '" . $this->db->escape($data['item_name']) . "'
							   ,cv = ".$data['cv']."
							   ,short_description = '" . $this->db->escape($data['short_description']) . "'
							   ,description = '" . $this->db->escape($data['description']) . "'
							   ,active = ".$data['active']."
							   ,price = ".$data['price']."	
							   ,cost = ".$data['cost']."
							   ,system_fee = ".$data['system_fee']."
							   ,service_fee = ".$data['service_fee']."
							   ,top_up = ".$data['top_up']."
							   ,tools = ".$data['tool']."
							   ,tax = ".$data['tax']."
							   ,shipping = ".$data['shipping']."
							   ,income = ".$data['income']."							   
							   ,item_profit_per = ".$data['item_profit_per']."
							   ,quantity = ".$data['quantity']."
							   ,distributor_discount_per = ".$data['distributor_discount_per']."
							   ,reseller_discount_per = ".$data['reseller_discount_per']."
							   ,direct_referral =".$data['direct_referral']."
							   ,advance_payment =".$data['advance_payment']."
							   ,points =".$data['points']."
							   ,sort = ".$data['sort']."
							   ,category_id = ".$data['category_id']."
							   ,date_added = '".$this->user->now()."' 
							   ,user_group_id = ".$data['usergroup']." 
						  WHERE item_id = ".$data['item_id'];
			
				$this->db->query($sql);
				
				if(isset($data['category_id'])){
					if($data['category_id'] != 2){
						$sql ="UPDATE gui_items_tbl
								  SET raw = 0
								WHERE item_id = ".$data['item_id'];
							$this->db->query($sql);
					}else{
						$sql ="UPDATE gui_items_tbl
								  SET raw = 1
								WHERE item_id = ".$data['item_id'];
							$this->db->query($sql);
					}
				}
				
				$item_id = $this->db->getLastId();
				
				$result['item_id'] = $item_id;
				$result['err_msg'] .= "Successful Update of Item ".$data['item_name']."<br>"; 
			} catch (Exception $e) {
				$result['err_msg'] .= "Error in Updating Item.";			
			}	
		}
		
		return $result;
	}	

	public function editUser ($data = array()) {

		$sql  = "SELECT user_group_id, status ";
		$sql .= "  FROM oc_user ";
		$sql .= " WHERE user_id = ".$data['user_id'];
		
		$query = $this->db->query($sql);
		
		$user_group_id = $query->row['user_group_id'];
		$status = $query->row['status'];
		
		$sql  = "UPDATE oc_user ";
		$sql .= "   SET username = '".$data['username']."' ";
		$sql .= "      ,firstname = '" . $data['firstname'] . "'";
		$sql .= "      ,middlename = '" . $data['middlename'] . "'";
		$sql .= "      ,lastname = '" . $data['lastname'] . "'";
		$sql .= "      ,birthdate = '" . $data['birthdate'] . "'";
		$sql .= "      ,email = LCASE('" . $data['email'] . "')";
		$sql .= "      ,user_group_id = " . $data['user_group_id'] ;
		
		if($user_group_id==13 and $status==1) {
			$sql .= "      ,status = ".$status;
		} else {
			$sql .= "      ,status = " . $data['status'] ;
		}		
		$sql .= " WHERE user_id = ".$data['user_id'];
		//echo $sql;
		$this->db->query($sql);
		
		$sql  = "SELECT branch_id ";
		$sql .= "  FROM gui_user_branch ";
		$sql .= " WHERE user_id = ".$data['user_id'];
		$sql .= "   AND status = 1 ";
		
		$query = $this->db->query($sql);
		
		$branch_id = $query->row['branch_id'];
		if($branch_id <> $data['branch_id']) {
			$sql  = "UPDATE gui_user_branch ";
			$sql .= "   SET status = 0, expiry_date = '".$this->user->now()."' ";
			$sql .= " WHERE user_id = ".$data['user_id'];
			$sql .= "   AND status = 1 ";
			$sql .= "   AND branch_id = ".$branch_id;
			
			$query = $this->db->query($sql);		

			$sql  = "INSERT INTO gui_user_branch ";
			$sql .= "   SET user_id = ".$data['user_id'];
			$sql .= "      ,branch_id = ".$data['branch_id'];
			$sql .= "      ,status = 1 ";
			$sql .= "      ,date_added = '".$this->user->now()."' ";
			$sql .= "      ,expiry_date = '9999-12-31' ";
			$this->db->query($sql);		
		}
	}

	public function resetUser($user_id) {
		$sql = "select username from oc_user where user_id = ".$user_id;
		$query = $this->db->query($sql);
		$username = $query->row['username'];
		
		$sql  = "UPDATE oc_user ";
		$sql .= "   SET password = '" . $this->db->escape(md5('12345')) . "'";
		$sql .= " WHERE user_id = ".$user_id;
		
		$this->db->query($sql);
		
		return "Successful Reset of user ".$username."<br>";
		
	}
	
	public function removeItem($item_id) {
		$sql = "select item_name from gui_items_tbl where item_id = ".$item_id;
		$query = $this->db->query($sql);
		$item = $query->row['item_name'];
		
		$sql = "delete from gui_items_tbl where item_id = ".$item_id;
		$this->db->query($sql);
		
		return "Successful Delete of item ".$item."<br>";
	}	
	
	public function removeReview($review_id) {
		
		$sql = "delete from oc_item_review where review_id = ".$review_id;
		$this->db->query($sql);
		
		return "Successful Delete of review ".$review_id."<br>";
	}	

	public function getItems($data = array(), $query_type) {

		$sql  = "SELECT b.description category, a.*
				   FROM gui_items_tbl a 
				   LEFT JOIN gui_category_tbl b on(a.category_id = b.category_id)
				   WHERE 1 = 1 ";		

		if(isset($data['item_code'])) {
			if (!empty($data['item_name'])) {
				$sql .= " AND lower(a.item_code) like '%" . strtolower($data['item_code']). "%'";
			}
		}
		
		if(isset($data['item_name'])) {
			if (!empty($data['item_name'])) {
				$sql .= " AND lower(a.item_name) like '%" . strtolower($data['item_name']). "%'";
			}
		}
		
		if(isset($data['active'])) {
			if (!empty($data['active'])) {
				$sql .= " AND a.active = " . $data['active'];
			}
		}
		
		if(isset($data['category_id'])) {
			if ($data['category_id'] != "0") {
				$sql .= " AND a.category_id = " . $data['category_id']. "";
			}
		}
		
		if($query_type == "data") {
			$sql .= " ORDER BY a.item_id ";
			
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
	
	public function getReviews($data = array(), $query_type) {

		$sql  = "select * 
					from oc_item_review 
				  where item_id = ".$data['item_id'];		
		
		if($query_type == "data") {
			$sql .= " ORDER BY review_id desc ";
			
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
	
	public function getCategories($data = array()) {
		$sql = "SELECT category_id, description 
				  FROM gui_category_tbl 
				  ORDER BY description ";			
		$query = $this->db->query($sql);		
		return $query->rows;
	}

	public function checkUsername($username) {
		$count = 0;
		$sql = "SELECT count(1) as total from oc_user where lower(username) = '".strtolower($username)."'";
		$query = $this->db->query($sql);
		$count = $query->row['total'];
		
		if ($count > 0) {
			return false;
		} else {
			return true;
		}
	}


	public function getBranches() {
		$sql  = "select branch_id, description from gui_branch_tbl";
		$query = $this->db->query($sql);
		return $query->rows;
	}	


	public function getItemDetails($item_id) {
		$sql  = "SELECT b.description category, a.*
				   FROM gui_items_tbl a 
				   LEFT JOIN gui_category_tbl b on(a.category_id = b.category_id)
				   WHERE a.item_id = ".$item_id;	

		$query = $this->db->query($sql);
		//echo "<br/>".$sql;
		return $query->row;
	
	}

	public function editPassword ($data = array()) {

		$count = 0;
		$sql = "SELECT count(1) as total from oc_user ";
		$sql .= " WHERE user_id = ".$data['user_id'];
		$sql .= "   AND password = '".$this->db->escape(md5($data['oldpassword']))."'";
		
		$query = $this->db->query($sql);
		$count = $query->row['total'];
		
		$sql  = "UPDATE oc_user ";
		$sql .= "   SET password = '".$this->db->escape(md5($data['password']))."'";
		$sql .= " WHERE user_id = ".$this->user->getId();
		$sql .= "   AND password = '".$this->db->escape(md5($data['oldpassword']))."'";
		
		//echo $sql;
		$this->db->query($sql);

		if ($count > 0) {
			return 'Success in updating password.';
		} else {
			return 'Correct old password is required.';
		}		
		
	}

	public function getModules ($user_group_id) {
		$sql  = "SELECT * from gui_scr_permission_tbl ";
		$sql .= " WHERE user_group_id = ".$user_group_id;
		$sql .= " ORDER BY sort";

		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getUserGroup() {
		$sql = "select user_group_id, name from oc_user_group where user_group_id in (46,56)";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
}
?>