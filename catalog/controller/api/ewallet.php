<?php 
class ControllerApiEwallet extends Controller {
	
	public function index() {

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$this->load->model('api/ewallet');

			$data = $this->request->post;

			$task = $data['task'];

			switch ($task) {
				case 'get_ewallet':
					$result = $this->model_api_ewallet->getEwallet($data);
					break;
			}
			
			header('Content-Type: application/json');
			header("HTTP/1.1 ".$result['status']);


			echo json_encode($result, JSON_PRETTY_PRINT); 
    		
    	}  
		
  	}
}
?>