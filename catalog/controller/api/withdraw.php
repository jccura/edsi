<?php 
class ControllerApiWithdraw extends Controller {
	
	public function index() {

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$this->load->model('api/withdraw');

			$data = $this->request->post;

			$task = $data['task'];

			switch ($task) {
				case 'get_withdraw':
					$result = $this->model_api_withdraw->getWithdraw($data);
					break;

				case 'withdraw':
					$result = $this->model_api_withdraw->withdraw($data);
					break;
			}
			
			header('Content-Type: application/json');
			header("HTTP/1.1 ".$result['status']);


			echo json_encode($result, JSON_PRETTY_PRINT); 
    		
    	}  
		
  	}
}
?>