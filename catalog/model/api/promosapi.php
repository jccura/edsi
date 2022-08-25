<?php
class ModelApiPromosApi extends Model {
	
	public function SyncPromos($data = array()){
		
		$sql = "select promo_id, item_header_id, item_id, item_quantity, free_flag
				from oc_promos_tbl";
		$query = $this->db->query($sql);
		$promo_id = $query->rows;
		
		foreach ($promo_id as $promo) {
			$item_header_id = $promo['item_header_id'];
			$item_id = $promo['item_id'];
			$item_quantity = $promo['item_quantity'];
			$free_flag = $promo['free_flag'];
			
			$access_key = MDE_ACCESS_KEY;
			$cInit = curl_init(MDE_SITE."/dev/promosapi");
			
			curl_setopt_array($cInit, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'access_key' => $access_key,
					'site' => 'esthetiquedirectsales.com',
					'promo_id' => $promo['promo_id'],
					'item_header_id' => $item_header_id,
					'item_id' => $item_id,
					'item_quantity' => $item_quantity,
					'free_flag' => $free_flag
				)
			));
			$result = curl_exec($cInit);
			$err = curl_errno($cInit);
			$errmsg = curl_error($cInit);
			//echo $access_key ."<br/>";
			//var_dump($cInit);
			// var_dump($result);
			// echo $result;
			//var_dump($err);
			//var_dump($errmsg);
			
		}
		return "Promo(s) Sync Successfully.";
	}

}
?>