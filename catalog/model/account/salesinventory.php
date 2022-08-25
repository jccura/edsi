<?php
class ModelAccountSalesInventory extends Model {
	
	public function getSalesInventory($data, $query_type) {

		$sql = "SELECT a.sales_inventory_id,b.username,a.order_id,a.order_det_id,a.request_id,c.item_name,a.srp
					  ,a.distributor_price,a.reseller_price,a.service_fee,a.tools,a.tax
					  ,a.shipping,a.system_fee,a.direct_referral,a.topup,a.cv,a.cost
					  ,a.commissions, a.income, a.date_added
				  FROM oc_sales_inventory a
				  JOIN oc_user b on (a.user_id = b.user_id)
				  JOIN gui_items_tbl c on (a.item_id = c.item_id)
				 WHERE 1 = 1 
				";	
		
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