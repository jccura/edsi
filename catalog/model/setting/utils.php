<?php 
class ModelSettingUtils extends Model {
	public function getObjectMapping($route, $status = null, $status_id = null) {
		 
		$sql = "SELECT * FROM oc_route_role_obj_map_tbl ";
		$sql.= "WHERE user_group_id = ".$this->user->getUserGroupId();
		$sql.= "  AND route = '".$route."' ";
		
		if(isset($status)) {
			$sql.= " AND status = '".$status."' ";
		}
		if(isset($status_id)) {
			$sql.= " AND status_id = ".$status_id." ";
		}
		
		//echo "<br>".$sql;
		$query = $this->db->query($sql);

		return $query->rows;
	}
}
?>