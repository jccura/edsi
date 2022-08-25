<?php   
class ControllerAccountOrderList extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'orderlist'))) {
	  		$this->redirect($this->url->link('common/shop', ''));
    	}
		$this->load->model('account/orderlist');

		$this->template = 'default/template/account/orderlist.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
		
		$this->response->setOutput($this->render());
		
  	}
	
	public function orderlistcheckout() {

		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'orderlist'))) {
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
			
			if(isset($data['task'])) {
				if(!empty($data['task'])) {
					if($data['task'] == "tagpacked") {
						$this->data['err_msg'] = $this->model_account_orderlist->tagOrdersAsPacked($data);
					}
					
					if($data['task'] == "cancelcustomer") {
						$this->data['err_msg'] = $this->model_account_orderlist->tagOrderAsCancelledByCustomer($data);
					}
					
					if($data['task'] == "cancel") {
						$this->data['err_msg'] = $this->model_account_orderlist->tagOrdersAsCancelled($data);
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
		
		$data['status_id'] = 18;
		$this->data['order_list'] = $this->model_account_orderlist->getOrderList($data, "data");
		$total = $this->model_account_orderlist->getOrderList($data, "total");
		$this->data['total'] = $total;
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/orderlist/orderlistcheckout', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/orderlistcheckout.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function orderlistpacked() {
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'orderlist'))) {
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
			
			if(isset($data['task'])) {
				if(!empty($data['task'])) {
					if($data['task'] == "tagintransit") {
						$this->data['err_msg'] = $this->model_account_orderlist->tagOrdersAsInTransit($data);
					}
					
					if($data['task'] == "cancelcustomer") {
						$this->data['err_msg'] = $this->model_account_orderlist->tagOrderAsCancelledByCustomer($data);
					}
					
					if($data['task'] == "cancel") {
						$this->data['err_msg'] = $this->model_account_orderlist->tagOrdersAsCancelled($data);
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
		
		$data['status_id'] = 34;
		$this->data['order_list'] = $this->model_account_orderlist->getOrderList($data, "data");
		$total = $this->model_account_orderlist->getOrderList($data, "total");
		$this->data['total'] = $total;
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/orderlist/orderlistpacked', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/orderlistpacked.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function orderlistintransit() {
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'orderlist'))) {
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
			
			if(isset($data['task'])) {
				if(!empty($data['task'])) {
					if($data['task'] == "tagdelivered") {
						$this->data['err_msg'] = $this->model_account_orderlist->tagOrdersAsDelivered($data);
					}
					
					if($data['task'] == "tagreturned") {
						$this->data['err_msg'] = $this->model_account_orderlist->tagOrdersAsReturned($data);
					}
					
					if($data['task'] == "cancel") {
						$this->data['err_msg'] = $this->model_account_orderlist->tagOrdersAsCancelled($data);
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
		
		$data['status_id'] = 117;
		$this->data['order_list'] = $this->model_account_orderlist->getOrderList($data, "data");
		$total = $this->model_account_orderlist->getOrderList($data, "total");
		$this->data['total'] = $total;
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/orderlist/orderlistintransit', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/orderlistintransit.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function orderlistreturned() {
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'orderlist'))) {
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
			
			if(isset($data['task'])) {
				if(!empty($data['task'])) {
					if($data['task'] == "tagcheckout") {
						$this->data['err_msg'] = $this->model_account_orderlist->tagOrdersAsCheckOut($data);
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
		
		$data['status_id'] = 118;
		$this->data['order_list'] = $this->model_account_orderlist->getOrderList($data, "data");
		$total = $this->model_account_orderlist->getOrderList($data, "total");
		$this->data['total'] = $total;
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/orderlist/orderlistreturned', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/orderlistreturned.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function orderlistcancelled() {
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'orderlist'))) {
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
			
			if(isset($data['task'])) {
				
			}
			
		} else {			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
		
		}
		
		$data['status_id'] = "19,113";
		$this->data['order_list'] = $this->model_account_orderlist->getOrderList($data, "data");
		$total = $this->model_account_orderlist->getOrderList($data, "total");
		$this->data['total'] = $total;
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/orderlist/orderlistcancelled', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/orderlistcancelled.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function orderlistorderreceiv() {
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'orderlist'))) {
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
			
			if(isset($data['task'])) {
				if(!empty($data['task'])) {
					if($data['task'] == "tagpaymentremitted") {
						$this->data['err_msg'] = $this->model_account_orderlist->tagOrdersAsPaymentRemitted($data);
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
		
		$data['status_id'] = 35;
		$this->data['order_list'] = $this->model_account_orderlist->getOrderList($data, "data");
		$total = $this->model_account_orderlist->getOrderList($data, "total");
		$this->data['total'] = $total;
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/orderlist/orderlistorderreceiv', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/orderlistorderreceived.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function orderlistpaymentremi() {
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'orderlist'))) {
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
			
			if(isset($data['task'])) {
				
			}
			
		} else {			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
		
		}
		
		$data['status_id'] = 119;
		$this->data['order_list'] = $this->model_account_orderlist->getOrderList($data, "data");
		$total = $this->model_account_orderlist->getOrderList($data, "total");
		$this->data['total'] = $total;
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/orderlist/orderlistpaymentremi', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/orderlistpaymentremitted.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function exporttocsv(){
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'orderslist'))) {
	  		$this->redirect('home');
    	}
				
		$this->load->model('account/orderlist');	

		$list = array("Order Id",
					"Status",
					"Date Created",
					"Date Packed",
					"Date Paid",
					"Paid/NotPaid?",
					"Date Delivered",
					"Customer Name",
					"Address",
					"Contact",
					"Mode of Delivery",
					"Tracking",
					"Mode of Collection",
					"SKU",
					"Item",
					"Quantity",
					"Send To",
					"Delivery Fee",
					"Discount",
					"Total",
					"Admin",
					"Payment Option",
					"Reference",
					"Receiver",
					"Page",
					"Notes");
					
		$export_data = $this->model_account_orderlist->getOrdersExportToCSV($this->request->post);
		
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=sales_report_'.$this->user->nowDate().'.csv');  
		$fp = fopen('php://output', 'w');

		$this->user->exportToCSV($fp, $list, $export_data);
		
	}
}
?>