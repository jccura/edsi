<?php   
class ControllerAccountQuickDelivery extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'quickdelivery'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}

		$url = '';
		$page = 0;
		$page_limit = 20;
		$total = 0;
		
		$this->load->model('account/quickdelivery');
		
		$data = array();
		
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
			
			if(isset($data['quick_deliveries_id'])) {
				$this->data['quick_deliveries_id'] = $data['quick_deliveries_id'];
				$this->data['qd_details'] = $this->model_account_quickdelivery->getQDDetails($this->data['quick_deliveries_id']);
				$this->data['drop_off'] = $this->model_account_quickdelivery->getDODetails($this->data['quick_deliveries_id']);
			} 
		}
		
		$this->data['quick_del'] = $this->model_account_quickdelivery->getQuickDeliveryDetails($data, "data");
		$total = $this->model_account_quickdelivery->getQuickDeliveryDetails($data, "total");
		$this->data['areas'] = $this->model_account_quickdelivery->getArea();
		$this->data['riders'] = $this->model_account_quickdelivery->getRidernames();
		$this->data['totalcom'] = $this->model_account_quickdelivery->getTotalCommission($data);
		$this->data['currentwallet'] = $this->model_account_quickdelivery->getEwallet($data);
		$params = $this->assembleParams($data);
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/quickdelivery', $url . $params . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/quickdelivery.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());	
		
	}		
	
	public function quickdelcompleted() {

		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'quickdelcompleted'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    		}

		$url = '';
		$page = 0;
		$page_limit = 20;
		$total = 0;
		
		$this->load->model('account/quickdelivery');
		
		$data = array();
		
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
			
			if(isset($data['quick_deliveries_id'])) {
				$this->data['quick_deliveries_id'] = $data['quick_deliveries_id'];
				$this->data['qd_details'] = $this->model_account_quickdelivery->getQDDetails($this->data['quick_deliveries_id']);
				$this->data['drop_off'] = $this->model_account_quickdelivery->getDODetails($this->data['quick_deliveries_id']);
			} 
		}
		
		$data['status_id'] = '247';
		$this->data['quick_del'] = $this->model_account_quickdelivery->getQuickDeliveryDetails($data, "data");
		$total = $this->model_account_quickdelivery->getQuickDeliveryDetails($data, "total");
		$this->data['areas'] = $this->model_account_quickdelivery->getArea();
		$this->data['riders'] = $this->model_account_quickdelivery->getRidernames();
		$this->data['totalcom'] = $this->model_account_quickdelivery->getTotalCommission($data);
		$this->data['currentwallet'] = $this->model_account_quickdelivery->getEwallet($data);
		$params = $this->assembleParams($data);
		
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/quickdelivery/quickdelcompleted', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/quickdelcompleted.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function quickdelongoing() {

		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'quickdelongoing'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    		}

		$url = '';
		$page = 0;
		$page_limit = 20;
		$total = 0;
		
		$this->load->model('account/quickdelivery');
		
		$data = array();
		
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
			
			if(isset($data['quick_deliveries_id'])) {
				$this->data['quick_deliveries_id'] = $data['quick_deliveries_id'];
				$this->data['qd_details'] = $this->model_account_quickdelivery->getQDDetails($this->data['quick_deliveries_id']);
				$this->data['drop_off'] = $this->model_account_quickdelivery->getDODetails($this->data['quick_deliveries_id']);
			} 
		}
		
		$data['status_id'] = '302, 245, 244';
		$this->data['quick_del'] = $this->model_account_quickdelivery->getQuickDeliveryDetails($data, "data");
		$total = $this->model_account_quickdelivery->getQuickDeliveryDetails($data, "total");
		$this->data['areas'] = $this->model_account_quickdelivery->getArea();
		$this->data['riders'] = $this->model_account_quickdelivery->getRidernames();
		$this->data['totalcom'] = $this->model_account_quickdelivery->getTotalCommission($data);
		$this->data['currentwallet'] = $this->model_account_quickdelivery->getEwallet($data);
		$params = $this->assembleParams($data);
		
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/quickdelivery/quickdelongoing', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/quickdelongoing.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function quickdelcancelled() {

		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'quickdelcancelled'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    		}

		$url = '';
		$page = 0;
		$page_limit = 20;
		$total = 0;
		
		$this->load->model('account/quickdelivery');
		
		$data = array();
		
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
			
			if(isset($data['quick_deliveries_id'])) {
				$this->data['quick_deliveries_id'] = $data['quick_deliveries_id'];
				$this->data['qd_details'] = $this->model_account_quickdelivery->getQDDetails($this->data['quick_deliveries_id']);
				$this->data['drop_off'] = $this->model_account_quickdelivery->getDODetails($this->data['quick_deliveries_id']);
			} 
		}
		
		$data['status_id'] = '140, 323';
		$this->data['quick_del'] = $this->model_account_quickdelivery->getQuickDeliveryDetails($data, "data");
		$total = $this->model_account_quickdelivery->getQuickDeliveryDetails($data, "total");
		$this->data['areas'] = $this->model_account_quickdelivery->getArea();
		$this->data['riders'] = $this->model_account_quickdelivery->getRidernames();
		$this->data['totalcom'] = $this->model_account_quickdelivery->getTotalCommission($data);
		$this->data['currentwallet'] = $this->model_account_quickdelivery->getEwallet($data);
		$params = $this->assembleParams($data);
		
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/quickdelivery/quickdelcancelled', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/quickdelcancelled.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function quickdelpending() {

		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'quickdelpending'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    		}

		$url = '';
		$page = 0;
		$page_limit = 20;
		$total = 0;
		
		$this->load->model('account/quickdelivery');
		$this->load->model('account/booking');
		
		$data = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') { 
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;
			$data = $this->request->post;
			
			if($data['task'] == 'show_qd'){
				$this->model_account_booking->hideOrShowQuickDelivery($data);
			}
			
		} else {			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
			
			if(isset($data['quick_deliveries_id'])) {
				$this->data['quick_deliveries_id'] = $data['quick_deliveries_id'];
				$this->data['qd_details'] = $this->model_account_quickdelivery->getQDDetails($this->data['quick_deliveries_id']);
				$this->data['drop_off'] = $this->model_account_quickdelivery->getDODetails($this->data['quick_deliveries_id']);
			} 
		}
		
		$data['status_id'] = '138';
		$this->data['quick_del'] = $this->model_account_quickdelivery->getQuickDeliveryDetails($data, "data");
		$total = $this->model_account_quickdelivery->getQuickDeliveryDetails($data, "total");
		$this->data['areas'] = $this->model_account_quickdelivery->getArea();
		$this->data['riders'] = $this->model_account_quickdelivery->getRidernames();
		$this->data['totalcom'] = $this->model_account_quickdelivery->getTotalCommission($data);
		$this->data['currentwallet'] = $this->model_account_quickdelivery->getEwallet($data);
		$params = $this->assembleParams($data);
		
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/quickdelivery/quickdelpending', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->template = 'default/template/account/quickdelpending.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function exportCsv(){

  		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'quickdelivery'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}

  		$this->load->model('account/quickdelivery');
		
		$data = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			if($this->request->post['task'] == "exportCsv"){
				$data = $this->request->post;
				$list = array("Quick Delivery ID","Client","Rider","Rider","Order Amount (w/out SF)","Delivery Fee","Commission Amount (20%)","Status","Payment Method","Pickup","Province","City","Added Date","Modified Date","Pickup Contact Name","Pickup Contact Number","Pickup Location","Drop off Contact Name","Drop off Contact Number","Drop off Location");
				header('Content-type: application/csv');
				header('Content-Disposition: attachment; filename=quick_delivery_monitoring'.$this->user->nowDate().'.csv');  
				$fp = fopen('php://output', 'w');
				$this->user->exportToCSV($fp, $list, $this->model_account_quickdelivery->getQuickDeliveryDetails($data, "data") );
				
			}

		}
	}	
	
	function assembleParams($data) {
		$params = "";
		
		if(isset($data['quick_delivery_id'])){
			if(!empty($data['quick_delivery_id'])){
				$params .= "&quick_delivery_id=".$data['quick_delivery_id'];
			}
		}
		
		if(isset($data['rider_id'])){
			if(!empty($data['rider_id'])){
				$params .= "&rider_id=".$data['rider_id'];
			}
		}
		
		if(isset($data['rider_name'])){
			if(!empty($data['rider_name'])){
				$params .= "&rider_id=".$data['rider_name'];
			}
		}	
		
		if(isset($data['datefrom'])){
			if(!empty($data['datefrom'])){
				$params .= "&datefrom=".$data['datefrom'];
			}
		}			 		 
		 
		if(isset($data['dateto'])){
			if(!empty($data['dateto'])){
				$params .= "&dateto=".$data['dateto'];
			}
		}
		 
		 //echo $params."<br>";
		 return $params;
	}
	
}

?>