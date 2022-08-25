<?php 
class ControllerApiMyCart extends Controller {
	
	public function index() {

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$this->load->model('api/mycart');

			$data = $this->request->post;

			$task = $data['task'];

			switch ($task) {
				case 'get_my_cart':
					$result = $this->model_api_mycart->getMyCart($data);
					break;

				case 'remove_item':
					$result = $this->model_api_mycart->removeItem($data);
					break;

				case 'get_payment_options':
					$result = $this->model_api_mycart->getPaymentOptions($data);
					break;

				case 'get_delivery_options':
					$result = $this->model_api_mycart->getDeliveryOption($data);
					break;

				case 'get_calculations':
					$result = $this->model_api_mycart->getCalculations($data);
					break;

				case 'update_item':
					$result = $this->model_api_mycart->updateItemQuantity($data);
					break;

				case 'checkout':
					$result = $this->model_api_mycart->checkout($data);
					break;
			}
			
			header('Content-Type: application/json');
			header("HTTP/1.1 ".$result['status']);


			echo json_encode($result, JSON_PRETTY_PRINT); 
    		
    	}  
		
  	}
}
?>