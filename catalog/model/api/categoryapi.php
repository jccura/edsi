<?php
class ModelApiCategoryApi extends Model {
	
	public function SyncCategory($data = array()){
		
		$sql = "select category_id, description, active
				from gui_category_tbl";
		$query = $this->db->query($sql);
		$category = $query->rows;
		
		foreach ($category as $cat) {

			$description = $cat['description'];
			$active = $cat['active'];
			$online_categories = 0;
			
			$access_key = MDE_ACCESS_KEY;
			$cInit = curl_init(MDE_SITE."/dev/categoryapi");
			curl_setopt_array($cInit, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'access_key' => $access_key,
					'site' => 'esthetiquedirectsales.com',
					'category_id' => $cat['category_id'],
					'description' => $description,
					'active' => $active,
					'online_categories' => $online_categories
				)
			));
			$result = curl_exec($cInit);
			$err = curl_errno($cInit);
			$errmsg = curl_error($cInit);
			// echo $access_key ."<br/>";
			//var_dump($cInit);
			// var_dump($result);
			// echo $result;
			//var_dump($err);
			//var_dump($errmsg);
			
		}
		return "Category Sync Successfully.";
	}
}
?>