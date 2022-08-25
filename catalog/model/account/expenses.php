<?php
class ModelAccountExpenses extends Model {	
	//GET STATUS ID & DESCRIPTION
	public function getExpensesType(){		
		$grouping2 = "";
		
		// if($this->user->getUserGroupId() == 12) {
			// $grouping2 = "COMPANY";
		// } else if($this->user->getUserGroupId() == 36) {
			// $grouping2 = "COMPANY";
		// } else if($this->user->getUserGroupId() == 53) {
			// $grouping2 = "BRANCH";
		// } else if($this->user->getUserGroupId() == 39) {
			// $grouping2 = "OPERATOR";
		// }
		
		$sql = "SELECT expenses_type_id,description 
				  FROM oc_expenses_type 
				 where `grouping` = 'EXP_TYPE' 
				   AND active = 1";
		// if($grouping2 != "") {
			// $sql .= " and `grouping`2 = '".$grouping2."' ";
		// }
		
		$sql .= " order by expenses_type_id ";
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	//GET CONTENT OF OC_EXPENSES TABLE
	public function getExpensesDetails($data, $query_type){
					 
		$sql = "select a.expenses_id, a.amount, a.remarks, b.description exp_type, a.date_added, a.exp_user, concat(c.firstname,' ',c.lastname,'(',c.username,')') creator
					from oc_expenses a
					left join oc_expenses_type b on(a.exp_type = b.expenses_type_id)
					left join oc_user c on(a.created_by = c.user_id)
					where a.created_by = ".$this->user->getId();
		
		if(isset($data['datefrom'])) {
			if(!empty($data['datefrom'])) {
				$sql .= " and a.date_added >= '".$data['datefrom']."' ";
			}
		}
		if(isset($data['dateto'])) {
			if(!empty($data['dateto'])) {
				$sql .= " and a.date_added <= '".$data['dateto']."' ";
			}
		}
		
		if(isset($data['exp_type_search'])) {
			if(!empty($data['exp_type_search'])) {
				$sql .= " and a.exp_type = ".$data['exp_type_search'];
			}
		}
		

		if($query_type == "data") {
			$sql .= " ORDER BY expenses_id desc ";
			
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

	public function addExpense($data){
		$result = array();
		$result['err_msg'] = "";
		$result['expenses_id'] = 0;
		$valid = 1;
		// $err_msg = "";
		
		if(!isset($data['exp_type']) or $data['amount'] == ""){
				$valid = 0;
				$result['err_msg'] = "Type of expenses is required.<br/>";
		}
		if(!isset($data['exp_user']) or $data['exp_user'] == "" ){
				$valid = 0;
				$result['err_msg'] .= "Expense User is required.<br/>";
		}		
		if(!isset($data['amount'])or $data['amount'] <= 0 ){
				$valid = 0;
				$result['err_msg'] .= "Amount is required and greather than 0.<br/>";			
		}
		
		$exp_grp_type = 0;
		
		if($this->user->getUserGroupId() == 12) {
			$exp_grp_type = 1;
		} else if($this->user->getUserGroupId() == 36) {
					$exp_grp_type = 1;
		} else if($this->user->getUserGroupId() == 39) {
					$exp_grp_type = 3;
		} else if($this->user->getUserGroupId() == 53) {
					$exp_grp_type = 2;
		}
		
		if($valid == 1)	{
			try {
				$sql = " INSERT INTO oc_expenses SET
						 exp_grp_type = ".$exp_grp_type.",
						 exp_type = ".strtoupper($data['exp_type']).",
						 exp_user = '".$data['exp_user']."',
						 amount = ".$data['amount'].",
						 remarks = '".$data['remarks']."',
						 date_added = '".$this->user->now()."',
						 created_by = ".$this->user->getId();
				
				$this->db->query($sql);
				
				$expenses_id = $this->db->getLastId();
				$result['expenses_id'] = $expenses_id;
				$result['err_msg'] = "Successful";
			} catch (Exception $e) {
				$result['err_msg'] .= "Error adding expenses.";
			}
		}
		return $result;
	}
	
	public function addTypeExpenses($data) {
		$exp_type = "EXP_TYPE";
		$result = array();
		$result['err_msg'] = "";
		$result['expenses_type_id'] = 0;
		$valid = 1;
		
		if(!isset($data['description'])) {
			$valid = 0;
			$result['err_msg'] .= "Description is Mandatory. <br>";
		} else {
			if(empty($data['description'])) {
				$valid = 0;
				$result['err_msg'] .= "Description is Mandatory. <br>";
			}
		}
		
		if(!isset($data['expense_group'])) {
			$valid = 0;
			$result['err_msg'] .= "Expense Group is Mandatory. <br>";
		} else {
			if(empty($data['expense_group'])) {
				$valid = 0;
				$result['err_msg'] .= "Expense Group is Mandatory. <br>";
			}
		}
		
		if(!isset($data['active'])) {
			$valid = 0;
			$result['err_msg'] .= "Status is Mandatory. <br>";
		} else {
			if(empty($data['active'])) {
				$valid = 0;
				$result['err_msg'] .= "Status is Mandatory. <br>";
			}
		}

		if($valid == 1) {
				$sql  = "INSERT INTO oc_expenses_type 
						SET description = '" .strtoupper($this->db->escape($data['description'])) . "' 
					   ,active =  '".$data['active']."' 
					   ,`grouping` =  '".strtoupper($exp_type)."'
					   ,`grouping`2 =  '" .strtoupper($this->db->escape($data['expense_group'])) . "'
					   ,date_added = '".$this->user->now()."' ";

				$this->db->query($sql);
				
				$expenses_type_id = $this->db->getLastId();
				
				$result['expenses_type_id'] = $expenses_type_id;
				$result['err_msg'] .= "Successful Creation of Expense Type ".strtolower($data['description'])."<br>"; 	
		}
		
		return $result;
	}
	
	public function clearType($data) {
		$result['err_msg'] = "";
		
		if(isset($data['selected'])) {
			
			foreach($data['selected'] as $sel) {
				$sql = "delete from oc_expenses_type where expenses_type_id = " .$sel;
				$this->db->query($sql);	
			}
			
			$result['err_msg'] = "Successfully Remove!";
		} else {
			$result['err_msg'] = "Please select first to remove!";
		}
		return $result;
	}
	
	public function deleteExpense($data) {
		$result['err_msg'] = "";
		
		if(isset($data['selected'])) {
			foreach($data['selected'] as $sel) {
				$sql = "delete from oc_expenses where expenses_id = " .$sel;
				$this->db->query($sql);	
				$result['err_msg'] .= "Successful Removal of Expenses ID (".$sel.").<br>";
			}
		} else {
			$result['err_msg'] = "Please select first to remove.";
		}
		return $result;
	}
	
	public function disableType($data){
		
		$sql = "select description from oc_expenses_type where expenses_type_id = ".$data['expenses_type_id'];
		$query = $this->db->query($sql);
		$description = $query->row['description'];
		
		$sql  = "UPDATE oc_expenses_type ";
		$sql .= "   SET active = 0";
		$sql .= "   WHERE expenses_type_id = '".$data['expenses_type_id']."' ";
		$this->db->query($sql);
		return "Successful Disabling of Expenses Type (".$description.")";

	}
	
	public function enableType($data){
		
		$sql = "select description from oc_expenses_type where expenses_type_id = ".$data['expenses_type_id'];
		$query = $this->db->query($sql);
		$description = $query->row['description'];
		
		$sql  = "UPDATE oc_expenses_type ";
		$sql .= "   SET active = 1";
		$sql .= "   WHERE expenses_type_id = '".$data['expenses_type_id']."' ";
		$this->db->query($sql);
		return "Successful Enabling of Expenses Type (".$description.")";

	}
	public function submitEdit($data) {
		$result = array();
		$result['err_msg'] = "";
		$result['expenses_type_id'] = 0;
		$valid = 1;
		
		if(!isset($data['description'])) {
			$valid = 0;
			$result['err_msg'] .= "Description is Mandatory. <br>";
		} else {
			if(empty($data['description'])) {
				$valid = 0;
				$result['err_msg'] .= "Description is Mandatory. <br>";
			}
		}
		
		if(!isset($data['expense_group'])) {
			$valid = 0;
			$result['err_msg'] .= "Expense Group is Mandatory. <br>";
		} else {
			if(empty($data['expense_group'])) {
				$valid = 0;
				$result['err_msg'] .= "Expense Group is Mandatory. <br>";
			}
		}
		
		if(!isset($data['active'])) {
			$valid = 0;
			$result['err_msg'] .= "Status is Mandatory. <br>";
		}

		if($valid == 1) {
				$sql  = "UPDATE oc_expenses_type 
							SET description = '" .strtoupper($this->db->escape($data['description'])) . "' 
							   ,active =  '".$data['active']."' 
							   ,`grouping`2 =  '" .strtoupper($this->db->escape($data['expense_group'])) . "'
							   ,date_added = '".$this->user->now()."' 
					      WHERE expenses_type_id = ".$data['expenses_type_id'];
				
				$this->db->query($sql);
				
				$expenses_type_id = $this->db->getLastId();
				
				$result['expenses_type_id'] = $expenses_type_id;
				$result['err_msg'] .= "Successful Updating of Expense Type ".strtolower($data['description'])."<br>"; 	
		
		}
		
		
		return $result;
	}
	
	public function updateTypeDetails($data){
		//var_dump($data);
		$exp_type = "EXP_TYPE";
		
			
			$sql  = "update oc_expenses_type
						SET description ='".$this->db->escape(strtolower($data['description']))."' 
							,`grouping` = '".$exp_type."' 
							,`grouping`2 = '".$this->db->escape($data['expense_group'])."'
							,date_modified = '".$this->user->now()."' 
					where user_id = ".$data['user_id'];
					
			$this->db->query($sql);
		return "Successful Updating Operator Details.";
		
	}
		
	public function getTypeDetails($data){
			$sql = "select expenses_type_id, description,`grouping`2
					from oc_expenses_type";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getExpenseType($data, $query_type) {
		$exp_type = "EXP_TYPE";
		
		$sql = "select description, expenses_type_id, grouping2, case active when 1 then 'Enabled' else 'Disabled' 
					end active from oc_expenses_type	
					where `grouping` = '".$exp_type."' ";
			
		if(isset($data['description'])) {
			if(!empty($data['description'])) {
				$sql .= " and description = '".$data['description']."' ";
			}
		}
		
		if(isset($data['expense_group'])) {
			if(!empty($data['expense_group'])) {
				$sql .= " and `grouping`2 = '".$data['expense_group']."' ";
			}
		}
		
		if(isset($data['active'])) {
			if(!empty($data['active'])) {
				$sql .= " and active = '".$data['active']."' ";
			}
		}
		
		if($query_type == "data") {
			$sql .= " ORDER BY expenses_type_id desc ";
			
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
	
	public function getExpense($data) {
		$sql = "select * from oc_expenses 
				 where expenses_id = ".$data['expenses_id'];
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getExpenseTypeDetails($expenses_type_id) {
		$sql = "SELECT expenses_type_id, description, grouping2,active
                  FROM oc_expenses_type 
                 WHERE expenses_type_id = ".$expenses_type_id;
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function uploadExtension($expenses_id, $file_extension, $i){
		
		$sql = "update oc_expenses set extension_".$i." = '".strtolower($file_extension)."' 
			where expenses_id = ".$expenses_id;
			
		$this->db->query($sql);
	}

	public function getExpenseImages($data){
		$sql = "select *
				from oc_expenses 
			 WHERE expenses_id = ".$data['expenses_id'];
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function getExpenseGroup() {
		$sql = "select expenses_type_id, description
					from oc_expenses_type";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getExpenseToExport($data) {
		
		$sql  = " select a.expenses_id, concat(c.firstname,' ',c.lastname,'(',c.username,')') creator,  b.description exp_type, a.exp_user, a.amount, a.remarks,a.date_added
					from oc_expenses a
					left join oc_expenses_type b on(a.exp_type = b.expenses_type_id)
					left join oc_user c on(a.created_by = c.user_id)
					where a.created_by = ".$this->user->getId();	
		
		
		$sql .= " ORDER BY expenses_id ";
			
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
	

	
}

?>