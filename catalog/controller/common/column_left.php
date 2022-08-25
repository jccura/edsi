<?php  
class ControllerCommonColumnLeft extends Controller {
	public function index() {
		
		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];
		} else {
			$route = 'common/home';
		}
		$this->language->load('common/header');
		
		/*
			$this->data['logged'] = $this->customer->isLogged();
			$this->data['user_group_id'] = $this->customer->getUserGroupId();
			$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account'), $this->customer->getUserName());
			$this->load->model('account/customer');	
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());		
			
			if (isset($customer_info)) {
				$this->data['username'] = $customer_info['username'];
			} else {
				$this->data['username'] = '';
			}
			
			if (isset($customer_info)) {
				if($customer_info['gender']=="M") {
					$this->data['pic'] = "data/he.png";
				} else {
					$this->data['pic'] = "data/she.png"; 
				}
			} else {
				$this->data['pic'] = 'data/he.png';
			}

			$customer_info = null;
		*/	
		if($this->user->isLogged()){
			$this->data['logged'] = $this->user->isLogged();
			$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account'), $this->user->getUserName());
			$this->data['user_group_id'] = $this->user->getUserGroupId();
			$this->load->model('account/users');
			$this->data['modules'] = $this->model_account_users->getModules($this->user->getUserGroupId());
		}
		
		$this->data['text_register'] = $this->language->get('text_register');
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_genealogy'] = $this->language->get('text_genealogy');
		$this->data['text_unilevel'] = $this->language->get('text_unilevel');
		$this->data['text_logout'] = $this->language->get('text_logout');
		$this->data['text_commission'] = $this->language->get('text_commission');
		$this->data['text_com_infinity'] = $this->language->get('text_com_infinity');
		$this->data['text_cashier'] = $this->language->get('text_cashier');
		$this->data['text_login'] = $this->language->get('text_login');
		$this->data['text_account_details'] = $this->language->get('text_account_details');
		
		$this->data['home'] = $this->url->link('common/home');
		$this->data['register'] = $this->url->link('account/register');
		$this->data['tree'] = $this->url->link('account/tree');
		$this->data['unilevel'] = $this->url->link('account/unilevel');
		$this->data['commission'] = $this->url->link('account/commission');
		$this->data['login'] = $this->url->link('account/login');
		$this->data['serials'] = $this->url->link('account/serialspayment');
		$this->data['productpayment'] = $this->url->link('account/productpayment');
		$this->data['orderpayment'] = $this->url->link('account/orderpayment');
		$this->data['logout'] = $this->url->link('account/logout');
		$this->data['edit'] = $this->url->link('account/edit');
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/column_left.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/column_left.tpl';
		} else {
			$this->template = 'default/template/common/column_left.tpl';
		}
								
		$this->render();
	}
}
?>