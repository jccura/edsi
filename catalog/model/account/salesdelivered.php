<?php
class ModelAccountSalesDelivered extends Model {
	
	public function getSalesDelivered($data, $query_type) {

		$sql = "SELECT a.*,c.item_name
				  FROM oc_sales_delivered a
				  JOIN gui_items_tbl c on (a.item_id = c.item_id)
				 WHERE 1 = 1 
				   AND a.user_id = ".$this->user->getId();	
		
		if($query_type == "data") {					
			
			if (!empty($data['datefrom'])) {
				$sql .= " AND a.date_added >= '" . $data['datefrom'] ." 00:00:00'";
			}

			if (!empty($data['dateto'])) {
				$sql .= " AND a.date_added <= '" . $data['dateto'] ." 23:59:59'";
			}			
			
			$sql .= " order by a.date_added desc ";
			
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
		}		
		
	}	
}
?>