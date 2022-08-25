<?php   
class ControllerAccountShippingRateMaint extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'shippingratemaint'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		
		$this->load->model('account/shippingratemaint');

		
		$url = '';
		$page = 0;
		$page_limit = 50;

		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}
				$this->request->post['start'] = ($page - 1) * $page_limit;
				$this->request->post['limit'] = $page_limit;
				$data = $this->request->post;	
			
			if(isset($data['task'])) {
				if($data['task'] == "add") {
					$result = $this->model_account_shippingratemaint->addRate($data);
					$this->data['err_msg'] = $result['err_msg'];
					
				}
			}
				if($data['task'] == "delete") {
					$this->data['err_msg'] = "";
					if (isset($data['selected'])) {
						foreach ($data['selected'] as $code_selected) {
							$this->data['err_msg'] .= $this->model_account_shippingratemaint->removeRate($code_selected);
						}	
					}
				}
				
				if($data['task'] == "submitedit") {
					$result = $this->model_account_shippingratemaint->submitEdit($data);
					$this->data['err_msg'] = $result['err_msg'];
					
				}
		} else {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}				
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;	
			$data = $this->request->get;
			
			
		} 
		
		$this->data['payments'] = $this->model_account_shippingratemaint->getPayments();
		
		if(!isset($data['task'])) {
			$data['task'] = "";
		}
		
		if($data['task'] == "edit") {
			$this->data['rate_details'] = $this->model_account_shippingratemaint->getRateDetails($data['rate_id']);
			$template = 'default/template/account/shippingratemaintedit.tpl';
		} else {	
			$this->data['rates'] = $this->model_account_shippingratemaint->getRates($data, "data");			
			$template = 'default/template/account/shippingratemaint.tpl';
		}
		
		$total = $this->model_account_shippingratemaint->getRates($data, "total");
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = 'shippingratemaint/{page}';		
		$this->data['pagination'] = $pagination->render();	
		$this->template = $template;

		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());
  	}

	public function exporttocsv(){
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'shippingratemaint'))) {
	  		$this->redirect('home');
    	}
				
		$this->load->model('account/shippingratemaint');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$data = $this->request->post;
		} else {
			$data = $this->request->get;
		}

		$list = array("PAYMENT OPTION",
					"DELIVERY POINTS",
					"RATE");
					
		$export_data = $this->model_account_shippingratemaint->getRatesExport($data);
		
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=shipping_rate_'.$this->user->nowDate().'.csv');  
		$fp = fopen('php://output', 'w');

		$this->user->exportToCSV($fp, $list, $export_data);
		
	}	
	
}
?>