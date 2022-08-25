<?php
class ModelAccountSalesEncodedCount extends Model {
	
	public function getSalesEncodedRepCount($data, $query_type) {

		$sql = "SELECT a.item_id,sum(ROUND(a.sales_today / c.price, 0)) sales_today,sum(ROUND(a.sales_yesterday / c.price, 0)) sales_yesterday
					  ,sum(ROUND(a.sales_second_day / c.price, 0)) sales_second_day,sum(ROUND(a.sales_third_day / c.price, 0)) sales_third_day
					  ,sum(ROUND(a.sales_week / c.price, 0)) sales_week,sum(ROUND(a.prev_week / c.price, 0)) prev_week
					  ,sum(ROUND(a.sales_second_week / c.price, 0)) sales_second_week,sum(ROUND(a.sales_third_week / c.price, 0)) sales_third_week
					  ,sum(ROUND(a.sales_month / c.price, 0)) sales_month,sum(ROUND(a.prev_month / c.price, 0)) prev_month
					  ,sum(ROUND(a.sales_second_month / c.price, 0)) sales_second_month,sum(ROUND(a.sales_third_month/ c.price, 0)) sales_third_month
					  ,sum(ROUND(a.sales_year / c.price, 0)) sales_year,sum(ROUND(a.prev_year / c.price, 0)) prev_year,.a.date_added
					  ,sum(ROUND(a.sales_second_year / c.price, 0)) sales_second_year,sum(ROUND(a.sales_third_year / c.price, 0)) sales_third_year ,c.item_name
				  FROM oc_sales_encoded a
				  JOIN gui_items_tbl c on (a.item_id = c.item_id)
				 GROUP BY a.item_id ";	
		
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
			
			$sql = "SELECT a.item_id,sum(ROUND(a.sales_today, 0)) sales_today,sum(ROUND(a.sales_yesterday, 0)) sales_yesterday
					  ,sum(ROUND(a.sales_second_day, 0)) sales_second_day,sum(ROUND(a.sales_third_day, 0)) sales_third_day
					  ,sum(ROUND(a.sales_week, 0)) sales_week,sum(ROUND(a.prev_week, 0)) prev_week
					  ,sum(ROUND(a.sales_second_week, 0)) sales_second_week,sum(ROUND(a.sales_third_week, 0)) sales_third_week
					  ,sum(ROUND(a.sales_month, 0)) sales_month,sum(ROUND(a.prev_month, 0)) prev_month
					  ,sum(ROUND(a.sales_second_month,0)) sales_second_month,sum(ROUND(a.sales_third_month, 0)) sales_third_month
					  ,sum(ROUND(a.sales_year, 0)) sales_year,sum(ROUND(a.prev_year, 0)) prev_year,.a.date_added
					  ,sum(ROUND(a.sales_second_year, 0)) sales_second_year,sum(ROUND(a.sales_third_year, 0)) sales_third_year ,c.item_name
				  FROM oc_sales_delivered a
				  JOIN gui_items_tbl c on (a.item_id = c.item_id)
				 GROUP BY item_id   ";

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