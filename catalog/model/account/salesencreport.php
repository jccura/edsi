<?php
class ModelAccountSalesEncReport extends Model {
	
	public function getSalesEncodedRep($data, $query_type) {

		$sql = "SELECT a.item_id,sum(a.sales_today)sales_today,sum(a.sales_yesterday)sales_yesterday,sum(a.sales_second_day)sales_second_day
							    ,sum(a.sales_third_day)sales_third_day,sum(a.sales_week)sales_week,sum(a.prev_week)prev_week
							    ,sum(a.sales_second_week)sales_second_week,sum(a.sales_third_week)sales_third_week
							    ,sum(a.sales_month)sales_month,sum(a.prev_month)prev_month,sum(a.sales_second_month)sales_second_month
							    ,sum(a.sales_third_month)sales_third_month,sum(a.sales_year)sales_year,sum(a.prev_year)prev_year
							    ,sum(a.sales_second_year)sales_second_year,sum(a.sales_third_year)sales_third_year,c.item_name
				  FROM oc_sales_encoded a
				  JOIN gui_items_tbl c on (a.item_id = c.item_id)
				 WHERE 1 = 1
				 GROUP BY a.item_id	";	
		
		if($query_type == "data") {					
			
			if (!empty($data['datefrom'])) {
				$sql .= " AND a.date_added >= '" . $data['datefrom'] ." 00:00:00'";
			}

			if (!empty($data['dateto'])) {
				$sql .= " AND a.date_added <= '" . $data['dateto'] ." 23:59:59'";
			}			
			
			$sql .= " order by a.item_id asc ";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
				if ($data['limit'] < 1) {
					$data['limit'] = 5;
				}			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}		
			//echo $sql."<br>";
			$query = $this->db->query($sql);
			return $query->rows;	
		} else if($query_type == "export") {					
			
			if (!empty($data['datefrom'])) {
				$sql .= " AND a.date_added >= '" . $data['datefrom'] ." 00:00:00'";
			}

			if (!empty($data['dateto'])) {
				$sql .= " AND a.date_added <= '" . $data['dateto'] ." 23:59:59'";
			}			
			
			$sql .= " order by a.date_added desc ";
			
			$query = $this->db->query($sql);
			return $query->rows;				
		} else if($query_type == "total"){

			if (!empty($data['datefrom'])) {
				$sql .= " AND a.date_added >= '" . $data['datefrom'] ." 00:00:00'";
			}

			if (!empty($data['dateto'])) {
				$sql .= " AND a.date_added <= '" . $data['dateto'] ." 23:59:59'";
			}
			
			$sqlt = "select count(1) total from (".$sql.") t ";
			
			$query = $this->db->query($sqlt);
			
			//echo $sqlt."<br>";
			return $query->row['total'];
		} else if($query_type == "summary"){
			
			$sql = "SELECT sum(a.srp) srp, sum(a.distributor_price) distributor_price, sum(a.reseller_price) reseller_price
							,case when a.item_id is not null then 'ALL ITEMS' else 0 end item_name
							,sum(a.direct_referral) direct_referral, sum(a.service_fee) service_fee
							,sum(a.tools) tools, sum(a.tax) tax, sum(a.shipping) shipping, sum(a.system_fee) system_fee
							,sum(a.direct_referral) direct_referral, sum(a.topup) topup,sum(a.cv) cv,sum(a.cost) cost
							,sum(a.commissions) commissions, sum(a.income) income
					   FROM oc_sales_inventory a
				      WHERE 1 = 1  ";

			if (!empty($data['datefrom'])) {
				$sql .= " AND a.date_added >= '" . $data['datefrom'] ." 00:00:00'";
			}

			if (!empty($data['dateto'])) {
				$sql .= " AND a.date_added <= '" . $data['dateto'] ." 23:59:59'";
			}
			
			$query = $this->db->query($sql);
			
			//echo $sqlt."<br>";
			return $query->row;
		}		
		
	}	
}
?>