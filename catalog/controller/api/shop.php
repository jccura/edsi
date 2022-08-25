<?php 
class ControllerApiShop extends Controller {
	
	public function index() {

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$this->load->model('api/shop');

			$data = $this->request->post;

			$task = $data['task'];

			switch ($task) {
				case 'load_items':
					$result = $this->model_api_shop->loadItems($data);
					break;

				case 'get_item_details':
					$result = $this->model_api_shop->getItemDetails($data);
					break;

				case 'add_to_cart':
					$result = $this->model_api_shop->addToCart($data);
					break;
			}
			
			header('Content-Type: application/json');
			header("HTTP/1.1 ".$result['status']);


			echo json_encode($result, JSON_PRETTY_PRINT); 
    		
    	}  
		
  	}
}
?>