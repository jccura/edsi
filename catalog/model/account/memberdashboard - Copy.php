<?php
class ModelAccountMemberDashboard extends Model {

	public function getOverallEwallet(){
		$sql = "SELECT coalesce(sum(credit),0) overall_ewallet FROM oc_ewallet_hist WHERE user_id = " . $this->user->getId()." and commission_type_id not in(5,8)";
		$query = $this->db->query($sql);
		return $query->row['overall_ewallet']; 
	}

	public function getAvailableEwallet(){
		$sql = "SELECT ewallet from oc_user WHERE user_id = " . $this->user->getId();
		$query = $this->db->query($sql);
		return $query->row['ewallet']; 
	}

	public function getTotalWithdraw(){

		$sql = "select coalesce(sum(amount),0) total_withdraw from oc_withdraw_hist where status in(72,74) and user_id = " . $this->user->getId();
		$query = $this->db->query($sql);

		return $query->row['total_withdraw'];

	}
	
	public function getEwalletPerStatus($com_id) {
		
		if($com_id == "all") {
			$sql = "select coalesce(sum(credit),0) total 
					  from oc_ewallet_hist 
					 where user_id = " . $this->user->getId();
			$query = $this->db->query($sql);
		} else {
			$sql = "select coalesce(sum(credit),0) total 
					  from oc_ewallet_hist 
					 where user_id = " . $this->user->getId(). " 
					   and commission_type_id in(".$com_id.")";
			$query = $this->db->query($sql);		
		}
		return $query->row['total'];
	}
	
	public function getPersonalMonthlySales($com_id) {
		
		if($com_id == "all") {
			$sql = "select a.user_id, sum(c.cost * c.quantity) sales, date_format(a.paid_date, '%Y%m') monthly_sale,
				concat(b.firstname, ' ', b.lastname) fullname, b.username 
				from oc_order a
				left join oc_user b on (a.user_id = b.user_id)
				left join oc_order_details c on (a.order_id = c.order_id)
				where 1 = 1";
			
			
			if($this->user->getUserGroupId() == 56){
					$sql .= " AND a.user_id = '".$this->user->getId()."'  and date_format(a.paid_date, '%Y%m') = '".$this->user->nowym()."'
					           or a.distributor_id = '".$this->user->getId()."'  
							   and b.user_group_id = 47 and	date_format(a.paid_date,'%Y%m') = '".$this->user->nowym()."'";
 
				} elseif ($this->user->getUserGroupId() == 47){
					$sql .= " AND a.distributor_id = ".$this->user->getId()."  and date_format(a.paid_date, '%Y%m') = '".$this->user->nowym()."'";
				} elseif ($this->user->getUserGroupId() == 46){
					$sql .= " AND a.user_id = ".$this->user->getId()."  and date_format(a.paid_date, '%Y%m') = '".$this->user->nowym()."'";
				} 	
			
			$query = $this->db->query($sql);
		} else {
			$sql = "select coalesce(sum(credit),0) total 
					  from oc_ewallet_hist 
					 where user_id = " . $this->user->getId(). " 
					   and commission_type_id in(".$com_id.")";
			$query = $this->db->query($sql);		
		}
		return $query->row['sales'];
	}
	
	public function getTotalPersonalSales($com_id) {
		
		if($com_id == "all") {
			$sql = "select a.user_id, sum(c.cost * c.quantity) sales,
				concat(b.firstname, ' ', b.lastname) fullname, b.username 
				from oc_order a
				left join oc_user b on (a.user_id = b.user_id)
				left join oc_order_details c on (a.order_id = c.order_id)
				where a.paid_date >= '2020-07-01 00:00:00' 
				and a.paid_date <= '2020-10-31 23:59:59'";
			
			
			if($this->user->getUserGroupId() == 56){
					$sql .= " and a.distributor_id = '".$this->user->getId()."' and b.user_group_id != 46";
 
				} elseif ($this->user->getUserGroupId() == 47){
					$sql .= " AND a.distributor_id = '".$this->user->getId()."'";
				} elseif ($this->user->getUserGroupId() == 46){
					$sql .= " AND a.user_id = '".$this->user->getId()."'";
				} 	
			
			$query = $this->db->query($sql);
		} else {
			$sql = "select coalesce(sum(credit),0) total 
					  from oc_ewallet_hist 
					 where user_id = " . $this->user->getId(). " 
					   and commission_type_id in(".$com_id.")";
			$query = $this->db->query($sql);		
		}
		return $query->row['sales'];
	}
	
	public function getDownlineDistAdminSales($com_id) {
		
		if($com_id == "all") {
			$sql = "select a.user_id, sum(c.cost * c.quantity) sales, date_format(a.paid_date, '%Y%m') monthly_sale,
				concat(b.firstname, ' ', b.lastname) fullname, b.username 
				from oc_order a
				left join oc_user b on (a.user_id = b.user_id)
				left join oc_order_details c on (a.order_id = c.order_id)
				where a.paid_date >= '2020-07-01 00:00:00' 
				and a.paid_date <= '2020-10-31 23:59:59'";
			
			
				if($this->user->getUserGroupId() == 56){
					$sql .= " and a.distributor_id = '".$this->user->getId()."' and b.user_group_id != 47";
 
				} elseif ($this->user->getUserGroupId() == 47){
					$sql .= " AND a.distributor_id = '".$this->user->getId()."'";
				} elseif ($this->user->getUserGroupId() == 46){
					$sql .= " AND a.user_id = '".$this->user->getId()."'";
				} 	
			
			$query = $this->db->query($sql);
		} else {
			$sql = "select coalesce(sum(credit),0) total 
					  from oc_ewallet_hist 
					 where user_id = " . $this->user->getId(). " 
					   and commission_type_id in(".$com_id.")";
			$query = $this->db->query($sql);		
		}
		return $query->row['sales'];
	}
	
	public function getGroupSales($com_id) {
		$total_percentage = 0;
		$total_amount = 0;
		$sales = 0;
		$counter = 0;
		$total_amount_partition = 0;
		// $group_sales = 0;
		$not_counted = 0;
		
		if($com_id == "all") {	
			//get all the downline 
			// echo("user id ".$us['user_id']." ======> ".$personal_sales."<br>");
			
			$sql = "select a.user_id, b.user_group_id
						from oc_unilevel a
						left join oc_user b on (a.user_id = b.user_id)
					where a.sponsor_user_id = ".$this->user->getId();
			$query = $this->db->query($sql);
			$downline_user_id = $query->rows;
			
			foreach($downline_user_id as $dui) {
				if($dui['user_group_id'] == 56) {
					$dist_res_id2 = " distributor_id = ".$dui['user_id'];
				} else {
					$dist_res_id2 = " sales_rep_id = ".$dui['user_id'];
				}
				
				$sql ="select coalesce(sum(amount),0)-coalesce(sum(delivery_fee),0) + coalesce(sum(discount),0) amount
						 from oc_order 
						where ".$dist_res_id2."
						  and paid_date >= '2020-07-01 00:00:00' 
						  and paid_date <= '2020-10-31 23:59:59'";
				$query = $this->db->query($sql);
<<<<<<< HEAD
				$total_amount_partition = $query->row['amount'];
				
				// echo("--- downline ==> ".$dui['user_id']."_sales == ".$total_amount_partition."<br>");
				
				if($total_amount_partition >= 25000) {
					$counter += 1;
				} else {
					$sales += $total_amount_partition;
=======
				$user_id = $query->rows;
				// var_dump($user_id);
				//foreach sa bawat user id na nakuha
				foreach($user_id as $ui){
					// echo 'duaamdito <br>';
					//check if may record na order
					// var_dump ($ui['user_id']);
					if ($ui['user_group_id'] == 56) {
						$sql ="select count(1) total 
								 from oc_order a
								 left join oc_user b on (a.user_id = b.user_id)
								where a.user_id =  '".$ui['user_id']."'
								  and a.paid_date >= '2020-07-01 00:00:00' 
								  and a.paid_date <= '2020-10-31 23:59:59'
								  or a.distributor_id = '".$ui['user_id']."'
								  and b.user_group_id = 47
								  and a.paid_date >= '2020-07-01 00:00:00' 
								  and a.paid_date <= '2020-10-31 23:59:59'";
						
						$query = $this->db->query($sql);
						$total_order = $query->row['total'];
					} else {
						$sql ="select count(1) total 
								 from oc_order a
								 left join oc_user b on (a.user_id = b.user_id)
								where a.user_id = '".$ui['user_id']."'
								  and a.paid_date >= '2020-07-01 00:00:00' 
								  and a.paid_date <= '2020-10-31 23:59:59'";
						
						$query = $this->db->query($sql);
						$total_order = $query->row['total'];
					}
				// var_dump($total_order);
					if($total_order > 0) {
						// var_dump($total_order);
						// echo 'test';
						if ($ui['user_group_id'] == 56) {
							$sql = "select a.user_id, sum(c.cost * c.quantity) sales,
											 concat(b.firstname, ' ', b.lastname) fullname, b.username 
									  from oc_order a
									  left join oc_user b on (a.user_id = b.user_id)
									  left join oc_order_details c on (a.order_id = c.order_id)
									 where a.paid_date >= '2020-07-01 00:00:00' 
									   and a.paid_date <= '2020-10-31 23:59:59'
									   and  a.distributor_id = '".$ui['user_id']."'
									   and b.user_group_id != 46";
							
							
						} else {
							$sql = "select a.user_id, sum(c.cost * c.quantity) sales,
											 concat(b.firstname, ' ', b.lastname) fullname, b.username 
									  from oc_order a
									  left join oc_user b on (a.user_id = b.user_id)
									  left join oc_order_details c on (a.order_id = c.order_id)
									 where a.user_id = '".$ui['user_id']."'
									   and a.paid_date >= '2020-07-01 00:00:00' 
									   and a.paid_date <= '2020-10-31 23:59:59'";
							// echo 'dumaan dito <br>';
						}
						$query = $this->db->query($sql);
						$sales = $query->row['sales'];
						// var_dump ($sql,$query->row['level']);
						if($sales >= $total_amount_partition) {
							$counter += 1;
							// var_dump($counter,$query->row['username'],$sales);
						} else {
							
							$not_counted += $sales;
							$counter2 += 1;
							// var_dump($not_counted);
							
						}
						// var_dump($total_amount_partition,  $not_counted);
						$total_amount = ($total_amount_partition * $counter) +  $not_counted;
						$times_run = $counter + $counter2;
	
					} 
>>>>>>> 699427b33e431391483ed0ae2e784a7a2a4428f2
				}
				
			}
			if($counter >= 3) {
				$total_amount = ($total_amount_partition * $counter) +  $sales;
				// echo($this->user->getId()." TOTAL COUNTER SALES ===>> ".$total_amount."<br>");
			// } else {
				// $total_amount = $sales;
				// echo($this->user->getId()." TOTAL PARTITION SALES ===>> ".$total_amount."<br>");
			}
		}	
		return $total_amount;
		
	}

	
	public function getTotalPv() {
		
			$sql = "select current_cv total from oc_user where user_id = ".$this->user->getId()." ";
		    $query = $this->db->query($sql);	
		
		return $query->row['total'];
	}
	
	public function getLastMonthPv() {
		
			$sql = "select month1_cv total from oc_user where user_id = ".$this->user->getId()." ";
		    $query = $this->db->query($sql);	
		
		return $query->row['total'];
	}
	
	public function getTotalReferrals() {
			$sql = "select sum(credit) total from oc_ewallet_hist where commission_type_id in (1,2) and user_id = ".$this->user->getId()." ";
			$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function getUnilevelIncome() {
		$sql = "select sum(credit) total from oc_ewallet_hist where commission_type_id in (43,42) and user_id = ".$this->user->getId()." ";
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function getTotalRankBonus() {
		$sql = "select sum(credit) total from oc_ewallet_hist where commission_type_id = 44 and user_id = ".$this->user->getId()." ";
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
}
?>