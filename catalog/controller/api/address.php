<?php 
class ControllerApiAddress extends Controller {
	
	public function index() {

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$this->load->model('api/address');

			$task = $this->request->post['task'];

			if($task == 'get_countries'){
				$result = $this->model_api_address->getCountries($this->request->post);				
			} else if($task == 'get_provinces'){
				$result = $this->model_api_address->getProvinces($this->request->post);				
			} else if($task == 'get_cities'){
				$result = $this->model_api_address->getCities($this->request->post);				
			} else if($task == 'get_barangays'){
				$result = $this->model_api_address->getBarangays($this->request->post);				
			} 

			header('Content-Type: application/json');
			header("HTTP/1.1 ".$result['status']);

			echo json_encode($result, JSON_PRETTY_PRINT); 
    		
    	}  
		
  	}
}
?>