<?php   
class ControllerAPISendOrder extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'sendorder'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		
		$this->load->model('api/sendorder');
				
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$data = $this->request->post;
			
			if ($data['task'] == "sendOrderDetails") {
				$this->data['err_msg'] = $this->model_api_sendorder->sendOrderDetails($data);
			}
			
		} else {
			$request_array = $this->request->get;
		}	
			
		$this->template = 'default/template/api/sendorder.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}

}
?>