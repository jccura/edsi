<?php
class ModelApiItemsApi extends Model {
	
	public function SyncItems($data = array()){
		
		$sql = "select item_id, price, category_id, item_name, item_code,0 eseller_income ,0 operator_income, 0 sales_rep_income,
						sort,0 epoints,0 points, active,0 unilevel1
				from gui_items_tbl";
		$query = $this->db->query($sql);
		$item_id = $query->rows;
		
		foreach ($item_id as $items) {
			
			$cost = $items['price'];
			$category_id = $items['category_id'];
			$description = $items['item_name'];
			$barcode = $items['item_code'];
			$sort = $items['sort'];
			$admin_price = $items['sales_rep_income'];
			$aff_profit = $items['eseller_income'];
			$dist_profit = $items['operator_income'];
			$epoints = $items['epoints'];
			$points = $items['points'];
			$active = $items['active'];
			$unilevel = $items['unilevel1'];
			$extension = 0;
			$permission_level = 0;
			$raw = 0;
			$include_in_shipping_counter = 0;
			$intl_shipping_disc = 0;
			$package_type = 0;
			$raw_item_id = 0;
			$flash = 0;
			$show_add_cart = 0;
			$mystery = 0;
			$featured_flag = 0;
			$before_price = 0;
			$with_additional_sf = 0;
			$additional_sf = 0;
			$usage_order = 0;
			$cost_display = 0;
			$purchase_price = 0;
			$product_cost = 0;
			$branch_price = 0;
			$branch_profit = 0;
			$franchisee_price = 0;
			$reseller_price = 0;
			$cv = 0;
			$bv = 0;
			$cargo_points = 0;
			$max_pairs = 0;
			$popular = 0;
			$drp_extra_shipping_fee = 0;
			$free_shipping = 0;
			$special_shipping_fee_set_up = 0;
			$shipping_shouldered_by_dist = 0;
			$online_product_flag = 0;
			$faststar = 0;
			$binary_cost = 0;
			$logistic_cv = 0;
			$globalpool = 0;
			$supplier_tax = 0;
			$log_budg_frm_supplier = 0;
			$delivery_cost = 0;
			$demoshop_fee = 0;
			$incentive = 0;
			$tools = 0;
			$tax = 0;
			$leader = 0;
			$admin = 0;
			$net_profit = 0;
			$main_flag = 0;
			$flag_1 = 0;
			$flag_2 = 0;
			$flag_3 = 0;
			$flag_4 = 0;
			$item_id1 = 0;
			$item_id2 = 0;
			$item_id3 = 0;
			$content_prov_user_id = 0;
			$faststart = 0;
			
			$access_key = MDE_ACCESS_KEY;
			$cInit = curl_init(MDE_SITE."/dev/itemsapi");
			curl_setopt_array($cInit, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'access_key' => $access_key,
					'site' => 'esthetiquedirectsales.com',
					'item_id' => $items['item_id'],
					'cost' => $cost,
					'category_id' => $category_id,
					'description' => $description,
					'sort' => $sort,
					'admin_price' => $admin_price,
					'barcode' => $barcode,
					'aff_profit' => $aff_profit,
					'dist_profit' => $dist_profit,
					'epoints' => $epoints,
					'points' => $points,
					'active' => $active,
					'unilevel' => $unilevel,
					'extension' => $extension,
					'permission_level' => $permission_level,
					'raw' => $raw,
					'include_in_shipping_counter' => $include_in_shipping_counter,
					'intl_shipping_disc' => $intl_shipping_disc,
					'package_type' => $package_type,
					'raw_item_id' => $raw_item_id,
					'flash' => $flash,
					'show_add_cart' => $show_add_cart,
					'mystery' => $mystery,
					'featured_flag' => $featured_flag,
					'before_price' => $before_price,
					'with_additional_sf' => $with_additional_sf,
					'additional_sf' => $additional_sf,
					'usage_order' => $usage_order,
					'cost_display' => $cost_display,
					'purchase_price' => $purchase_price,
					'product_cost' => $product_cost,
					'branch_price' => $branch_price,
					'branch_profit' => $branch_profit,
					'franchisee_price' => $franchisee_price,
					'reseller_price' => $reseller_price,
					'cv' => $cv,
					'bv' => $bv,
					'cargo_points' => $cargo_points,
					'max_pairs' => $max_pairs,
					'popular' => $popular,
					'drp_extra_shipping_fee' => $drp_extra_shipping_fee,
					'free_shipping' => $free_shipping,
					'special_shipping_fee_set_up' => $special_shipping_fee_set_up,
					'shipping_shouldered_by_dist' => $shipping_shouldered_by_dist,
					'online_product_flag' => $online_product_flag,
					'faststar' => $faststar,
					'binary_cost' => $binary_cost,
					'logistic_cv' => $logistic_cv,
					'globalpool' => $globalpool,
					'supplier_tax' => $supplier_tax,
					'log_budg_frm_supplier' => $log_budg_frm_supplier,
					'delivery_cost' => $delivery_cost,
					'demoshop_fee' => $demoshop_fee,
					'incentive' => $incentive,
					'tools' => $tools,
					'tax' => $tax,
					'leader' => $leader,
					'admin' => $admin,
					'net_profit' => $net_profit,
					'main_flag' => $main_flag ,
					'flag_1' => $flag_1,
					'flag_2' => $flag_2,
					'flag_3' => $flag_3,
					'flag_4' => $flag_4,
					'item_id1' => $item_id1,
					'item_id2' => $item_id2,
					'item_id3' => $item_id3,
					'content_prov_user_id' => $content_prov_user_id,
					'faststart' => $faststart
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
		return "Items Sync Successfully.";
	}
	
	
	public function getItems($data, $query_type = "data"){
		$sql = "SELECT a.item_id, b.description 'category', a.item_code, a.item_name, a.price, a.date_added
							FROM gui_items_tbl a 
							LEFT JOIN gui_category_tbl b ON (a.category_id = b.category_id)";	

		if($query_type == "data") {

			$sql .= " order by a.item_id ";
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
				if ($data['limit'] < 1) {
					$data['limit'] = 5;
				}			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);
			return $query->rows;
		} else {
			$sqlt = "select count(1) as total from (".$sql.") t ";
			$query = $this->db->query($sqlt);
			return $query->row['total'];
		}	
	}
}
?>