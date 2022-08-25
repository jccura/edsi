<?php   
class ControllerApiRemarksApi extends Controller {   
	public function index() {
		$json = array();
		$json_obj = file_get_contents('php://input');
		$data = json_decode($json_obj, true);
		$this->load->model('api/remarksapi') ;
		
		if(($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$data = $this->request->post;
		}
		
		$key = 'a428d34900a57eaecda71bceeb94c320';
		
		if(isset($data)) {
			// $merchant_id = $this->model_api_deliveryapi->getAccessKey($data);
			if($key == $data['access_key']){
				// $data['merchant_id'] = $merchant_id;
				if ($data['task'] == "sendremarks") {
					$json = $this->model_api_remarksapi->SendRemarks($data);
				}
				
				if ($data['task'] == "receiveremarks") {
					$json = $this->model_api_remarksapi->ReceiveRemarks($data);
				}
			} else {
				$json['user_id'] = 0;
				$json['status'] = "failed";
				$json['status_msg'] = "Unauthorized access.";
			}
		} else {
			$json['user_id'] = 0;
			$json['status'] = "failed";
			$json['status_msg'] = "No data of registration.";
		}		
		echo json_encode($json, JSON_PRETTY_PRINT);
  	}

}
?>