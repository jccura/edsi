<?php 
class ControllerApiRegister extends Controller {
	
	public function index() {

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$this->load->model('api/register');

			$data = $this->request->post;

			switch ($data['task']) {
				case 'register':
					$result = $this->model_api_register->register($data);
					break;
				
				case 'get_sponsor_details':
					$result = $this->model_api_register->getSponsorDetails($data);
					break;
			}

			header('Content-Type: application/json');
			header("HTTP/1.1 ".$result['status']);


			echo json_encode($result, JSON_PRETTY_PRINT); 
    		
    	}  
		
  	}
}
?>