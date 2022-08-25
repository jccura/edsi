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
			$sql = "select a.user_id, sum(d.price * c.quantity) sales, date_format(a.paid_date, '%Y%m') monthly_sale,
				concat(b.firstname, ' ', b.lastname) fullname, b.username 
				from oc_order a
				left join oc_user b on (a.user_id = b.user_id)
				left join oc_order_details c on (a.order_id = c.order_id)
				left join gui_items_tbl d on (c.item_id = d.item_id)
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
			$sql = "select a.user_id, sum(d.price * c.quantity) sales,
				concat(b.firstname, ' ', b.lastname) fullname, b.username 
				from oc_order a
				left join oc_user b on (a.user_id = b.user_id)
				left join oc_order_details c on (a.order_id = c.order_id)
				left join gui_items_tbl d on (c.item_id = d.item_id)
				where a.paid_date >= '2020-10-01 00:00:00' 
				and a.paid_date <= '2020-12-31 23:59:59'";
			
			
			if($this->user->getUserGroupId() == 56){
					$sql .= " and a.user_id = '".$this->user->getId()."'";
 
				} elseif ($this->user->getUserGroupId() == 47){
					$sql .= " AND a.user_id = '".$this->user->getId()."'";
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
			$sql = "select a.user_id, sum(d.price * c.quantity) sales, date_format(a.paid_date, '%Y%m') monthly_sale,
				concat(b.firstname, ' ', b.lastname) fullname, b.username 
				from oc_order a
				left join oc_user b on (a.user_id = b.user_id)
				left join oc_order_details c on (a.order_id = c.order_id)
				left join gui_items_tbl d on (c.item_id = d.item_id)
				where a.paid_date >= '2020-10-01 00:00:00' 
				and a.paid_date <= '2020-12-31 23:59:59'";
			
			
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
				$sql ="select sum(sales_month) + sum(prev_month) + sum(sales_second_month) three_month_sales from oc_sales_delivered
						where user_id = ".$dui['user_id'];
				$query = $this->db->query($sql);
				$total_amount_partition = $query->row['three_month_sales'];
				
				// echo("--- downline ==> ".$dui['user_id']."_sales == ".$total_amount_partition."<br>");
				
				if($total_amount_partition >= 25000) {
					$counter += 1;
				} else {
					$sales += $total_amount_partition;
				}
				
			}
			if($counter >= 3) {
				$total_amount = ($total_amount_partition * $counter) +  $sales;
				// echo($this->user->getId()." TOTAL COUNTER SALES ===>> ".$total_amount."<br>");
			} else {
				$total_amount = $sales;
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