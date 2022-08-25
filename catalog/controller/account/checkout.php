<?php   
class ControllerAccountCheckout extends Controller {   
	public function index() {
		// if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'checkout'))) {
	  		// $this->redirect($this->url->link('common/home', ''));
    	// }
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = $this->config->get('config_ssl');
		} else {
			$this->data['base'] = $this->config->get('config_url');
		}
		
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		
		// $this->load->model('account/common');
		// $this->load->model('api/sendorder');
		// $this->load->model('account/trackingpage');
		$page = 0; 
		$page_limit = 20; 
		$url = "";
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}			
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;	
			$data = $this->request->post;

		} else {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}			
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
		}	
		
		$this->template = 'default/template/common/checkout.tpl';
		
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
}
?>