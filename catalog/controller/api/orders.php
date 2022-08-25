<?php 
class ControllerApiOrders extends Controller {
	
	public function index() {

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$this->load->model('api/orders');

			$data = $this->request->post;

			$task = $data['task'];

			switch ($task) {
				case 'get_orders':
					$result = $this->model_api_orders->getOrders($data);
					break;
			}
			
			header('Content-Type: application/json');
			header("HTTP/1.1 ".$result['status']);


			echo json_encode($result, JSON_PRETTY_PRINT); 
    		
    	}  
		
  	}
}
?>