<?php 
class ControllerCommonShop extends Controller {
	public function index() {		/*		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'shop'))) {	  		$this->redirect("home");    	}*/
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = $this->config->get('config_ssl');
		} else {
			$this->data['base'] = $this->config->get('config_url');
		}
		
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');		
		$this->load->model('account/common');
		$page = 0; 
		$page_limit = 16; 
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
		
		$this->data['resellerpackage'] = $this->model_account_common->getResellerPackage($data, "data");
		$this->data['distributor_packages'] = $this->model_account_common->getDistributorPackage($data, "data");
		$this->data['allitems'] = $this->model_account_common->getActiveItems($data, "data");
		$this->data['retail'] = $this->model_account_common->getItemRetail($data, "data");
		$total = $this->model_account_common->getActiveItems($data, "total");
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = PAGINATION_TEXT;
		$pagination->url = 'shop/{page}';			
		$this->data['pagination'] = $pagination->render();

    	$this->template = 'default/template/common/shop.tpl';
		$this->children = array(
			'common/footer',
			'common/header'	
		);
		$this->response->setOutput($this->render());
  	}
}
?>