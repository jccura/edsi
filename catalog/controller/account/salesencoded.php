<?php   
class ControllerAccountSalesEncoded extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'salesencoded'))) {
	  		$this->redirect("home");
    	}
		
		$this->load->model('account/salesencoded');

		$url = '';
		$page = 0;
		$page_limit = 50;
			

		if (($this->request->server['REQUEST_METHOD'] == 'GET')) {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
		} else {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;
			$data = $this->request->post;			
		}
		
		$this->data['salesencoded'] = $this->model_account_salesencoded->getSalesEncoded($data, "data");
		$total = $this->data['total'] = $this->model_account_salesencoded->getSalesEncoded($data, "total");
			
		$pagination = new Pagination();
		$pagination->total = $this->data['total'];
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/salesencoded', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();	
		
		$this->template = 'default/template/account/salesencoded.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}

	public function exporttocsv(){
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'salesencoded'))) {
	  		$this->redirect('home');
    	}
				
		$this->load->model('account/salesencoded');
		
		if (($this->request->server['REQUEST_METHOD'] == 'GET')) {
			$data = $this->request->get;
		} else {
			$data = $this->request->post;			
		}
		
		$list = array("Sales Encoded ID",
					"Item Name",
					"Sales Today",
					"Sales Yesterday",
					"Weekly Sales",
					"Previous Weekly Sales",
					"Monthly Sales",
					"Previous Monthly Sales",
					"Annual Sales",
					"Previous Annual Sales",
					"Date Added");
		
		$export_data = $this->model_account_salesencoded->getSalesEncoded($data, "data");
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=sales_encoded'.$this->user->nowDate().'.csv');  
		$fp = fopen('php://output', 'w');

		$this->user->exportToCSVWithTotalOnTop($fp, $list, $export_data, $totals);
		
	}	
	
}
?>