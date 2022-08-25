<?php
class ModelAccountSyncOrder extends Model {
	
	public function syncorder($data) {
		$this->load->model('api/sendorder');
		// echo "nasa model syncOrder ako<br>";
		return $this->model_api_sendorder->sendOrderDetails($data);
	}
	
}
?>