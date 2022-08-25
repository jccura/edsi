<?php
class ModelAccountRankHist extends Model {
	public function getRankHist () {
		$sql = "select * from oc_rank_hist where 1 = 1";
		$query = $this->db->query($sql);
	
	
		return $query->rows;
	}
	
}
?>