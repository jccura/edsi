<?php
class ModelAccountAreaOpInventory extends Model {	

	public function getAreaOperator() {
		$sql = "select * from oc_user
					where user_group_id = 39
					and status = 1 
					order by username asc";
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getAreaOperatorInventory($data,$query_type) {
		$sql = "select a.username,concat(a.firstname, ' ',a.lastname) ao_name, 
					   c.item_name,b.qty
				from oc_user a
				left join oc_inventory b on (a.user_id = b.user_id)
				left join gui_items_tbl c on (b.item_id = c.item_id)
				where a.user_group_id = 39
				and c.raw = 1";
		$query = $this->db->query($sql);
		
		if(isset($data['city_dist'])) {
			if ($data['city_dist'] != "0") {
				$sql .= " and a.user_id = " . $data['city_dist'];
			}
		}

		if($query_type == "data") {
			$sql .= " order by a.username asc ";
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 6;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			// echo $sql;
			$query = $this->db->query($sql);
			return $query->rows;
	    } else if($query_type == "total") {
			$sqlt = "select count(1) total from (".$sql.") t";
			$query = $this->db->query($sqlt);
			return $query->row['total'];
		}
	}
	
	public function getAreaOpInventoryToExport($data) {
		$sql = "select case when a.user_id then concat(a.username,'(',a.firstname, ' ',a.lastname,')') else a.username end  ao_name, 
					   c.item_name,b.qty
				from oc_user a
				left join oc_inventory b on (a.user_id = b.user_id)
				left join gui_items_tbl c on (b.item_id = c.item_id)
				where a.user_group_id = 39
				and c.raw = 1";

			$sql .= " ORDER BY a.username asc ";
			
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