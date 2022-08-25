<?php
class ModelAccountLeaderBoard extends Model {	

	public function getTopDistributor($data){
		$sql ="SELECT  UPPER(concat(b.firstname,' ',b.lastname))fullname,sum(a.amount) sales,a.status_id,a.user_id,c.name user_group
				 FROM oc_order a
				 JOIN oc_user b ON(a.user_id = b.user_id)
				 JOIN oc_order_details d ON(a.order_id = d.order_id)
			     JOIN oc_user_group c ON (b.user_group_id = c.user_group_id)
				WHERE 1 = 1 ";
				
			if(isset($data['month_leaderboard'])) {
				$sql .=" and date_format(a.paid_date,'%Y-%m') = '".$this->user->nowy()."-".$data['month_leaderboard']."'";
				
			} else {
				$sql .=" and date_format(a.paid_date,'%Y%m') = '".$this->user->nowym()."'";
			}
				 
		$sql .="  AND d.item_id in (17,18,21)
				GROUP BY a.user_id 
			    ORDER BY sum(a.amount) desc  
				LIMIT 10";
		// echo $sql."<br>";
		$query =$this->db->query($sql);
		return $query->rows; 
						
		
	}
	
	public function getTopAreaOperator($data){
		$sql ="SELECT  UPPER(concat(b.firstname,' ',b.lastname))fullname,sum(a.amount) sales
		              ,UPPER(b.username) username
				 FROM oc_order a
				 JOIN oc_user b ON(a.city_distributor_id = b.user_id)
				 JOIN oc_order_details d ON(a.order_id = d.order_id)
			     JOIN oc_user_group c ON (b.user_group_id = c.user_group_id)
				WHERE 1 = 1 ";
				
			if(isset($data['month_leaderboard'])) {
				$sql .=" and date_format(a.paid_date,'%Y-%m') = '".$this->user->nowy()."-".$data['month_leaderboard']."'";
				
			} else {
				$sql .=" and date_format(a.paid_date,'%Y%m') = '".$this->user->nowym()."'";
			}
		
		$sql .=" GROUP BY a.city_distributor_id 
			 ORDER BY sum(a.amount) desc 
				LIMIT 10";
				
		
		// echo $sql."<br>";
		$query =$this->db->query($sql);
		return $query->rows; 
						
		
	}
	
	public function getTopRequestor(){
		$sql ="SELECT  UPPER(concat(b.firstname,' ',b.lastname))fullname
				      ,sum(a.re_stock) total,UPPER(b.username) username
				 FROM oc_inventory_history_tbl a
			     JOIN oc_user b on (a.user_id = b.user_id)
				WHERE b.user_group_id = 39
			 GROUP BY b.user_id
			 ORDER BY sum(a.re_stock) desc
				LIMIT 10";
		$query =$this->db->query($sql);
		return $query->rows; 
						
		
	}

	
	public function getTopSeller($data){
		
		$sql =" SELECT  UPPER(concat(b.firstname,' ',b.lastname))fullname,sum(a.amount) sales,a.status_id,a.user_id,c.name user_group
				 FROM oc_order a
				 JOIN oc_user b ON(a.user_id = b.user_id)
				 JOIN oc_order_details d ON(a.order_id = d.order_id)
			     JOIN oc_user_group c ON (b.user_group_id = c.user_group_id)
				WHERE 1 = 1 ";
				
			if(isset($data['month_leaderboard'])) {
				$sql .=" and date_format(a.paid_date,'%Y-%m') = '".$this->user->nowy()."-".$data['month_leaderboard']."'";
				
			} else {
				$sql .=" and date_format(a.paid_date,'%Y%m') = '".$this->user->nowym()."'";
			}
			
		$sql .=" GROUP BY a.user_id 
			    ORDER BY sum(a.amount) desc 
				LIMIT 10";
		// echo date('F', strtotime('04'));
			// echo $sql."<br>";
		$query =$this->db->query($sql);
		return $query->rows; 
						
		
	}


}
?>