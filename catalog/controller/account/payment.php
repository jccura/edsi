<?php   
class ControllerAccountPayment extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'payment'))) {
	  		$this->redirect($this->url->link('common/shop', ''));
    	}
		$this->load->model('account/orderlist');

		$this->template = 'default/template/account/payment.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
		
		$this->response->setOutput($this->render());
		
  	}
	
	public function paymentuploaded() {

		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'payment'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    		}

		$this->load->model('account/orderlist');
		$this->load->model('account/orders');
		
		$url = '';
		$page = 0;
		$page_limit = 10;
		$total = 0;
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') { 
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;
			$data = $this->request->post;
			if(isset($data['task'])) {
				if(!empty($data['task'])) {
					if($data['task'] == "tagPaymentConfirmed") {
						if(isset($data['selected'])) {
							foreach($data['selected'] as $order_id) {
								$data['status_id'] = 125;
								$data['order_id'] = $order_id;
								$this->data['err_msg'] = "";
								$this->data['err_msg'] = $this->model_account_orders->tagOrdersAsPaidApi($data);
								$this->data['err_msg'] = $this->model_account_orders->paymentConfirmed($data);
							}
						}						
					}
				}
			}
		} else {			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
		
		}
		
		$data['status_id'] = 123;
		$this->data['order_list'] = $this->model_account_orderlist->getOrderList($data, "data");
		$total = $this->model_account_orderlist->getOrderList($data, "total");
		$this->data['total'] = $total;
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/payment/paymentuploaded', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/paymentuploaded.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function paymentconfirmed() {
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'payment'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    		}

		$this->load->model('account/orderlist');
		$this->load->model('account/orders');
		
		$url = '';
		$page = 0;
		$page_limit = 10;
		$total = 0;
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') { 
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;
			$data = $this->request->post;
			
			if(isset($data['task'])) {
				if(!empty($data['task'])) {
					if($data['task'] == "tagshipped") {
						$this->data['err_msg'] = $this->model_account_orders->tagOrdersAsShipped($data);
					}
				}
			}
			
		} else {			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
		
		}
		
		// $data['status_id'] = "125,126,35";
		$data['status_id'] = 125;
		$this->data['order_list'] = $this->model_account_orderlist->getOrderList($data, "data");
		$total = $this->model_account_orderlist->getOrderList($data, "total");
		$this->data['total'] = $total;
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/payment/paymentconfirmed', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/paymentconfirmed.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function paymentshipped() {
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'payment'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    		}

		$this->load->model('account/orderlist');
		$this->load->model('account/orders');
		
		$url = '';
		$page = 0;
		$page_limit = 10;
		$total = 0;
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') { 
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;
			$data = $this->request->post;
			
			if(isset($data['task'])) {
				if(!empty($data['task'])) {
					if($data['task'] == "tagdelivered") {
						//$this->data['err_msg'] = $this->model_account_orderlist->tagOrdersAsDelivered($data);
						$this->data['err_msg'] = $this->model_account_orders->tagDropshipOrderAsDelivered($data);
					}
				}
			}
			
		} else {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
		
		}
		
		$data['status_id'] = 126;
		$this->data['order_list'] = $this->model_account_orderlist->getOrderList($data, "data");
		$total = $this->model_account_orderlist->getOrderList($data, "total");
		$this->data['total'] = $total;
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/payment/paymentshipped', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/paymentshipped.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function paymentdelivered() {
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'payment'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    		}

		$this->load->model('account/orderlist');
		
		$url = '';
		$page = 0;
		$page_limit = 10;
		$total = 0;
		
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
		
		$data['status_id'] = 35;
		$this->data['order_list'] = $this->model_account_orderlist->getOrderList($data, "data");
		$total = $this->model_account_orderlist->getOrderList($data, "total");
		$this->data['total'] = $total;
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/payment/paymentdelivered', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/paymentdelivered.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
}
?>