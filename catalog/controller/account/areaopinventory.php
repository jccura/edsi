<?php   
class ControllerAccountAreaOpInventory extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'areaopinventory'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}	
		
		$this->load->model('account/areaopinventory');
		$page = 0; 
		$page_limit = 100; 
		$url = "";
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
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
		
		$this->data['areaoperator'] = $this->model_account_areaopinventory->getAreaOperator();
		$this->data['operatorinventory'] = $this->model_account_areaopinventory->getAreaOperatorInventory($data,'data');
		$this->data['total'] = $this->model_account_areaopinventory->getAreaOperatorInventory($data,'total');
	
		$pagination = new Pagination();
		$pagination->total = $this->data['total'];
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/areaopinventory', $url . '&page={page}', 'SSL');		
		$this->data['pagination'] = $pagination->render();
		$this->template = 'default/template/account/areaopinventory.tpl';
			
			
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);	
		$this->response->setOutput($this->render());
	}
	
	public function exporttocsv(){
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'areaopinventory'))) {
	  		$this->redirect('home');
    	}
				
		$this->load->model('account/areaopinventory');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$data = $this->request->post;
		} else {
			$data = $this->request->get;
		}

		$list = array("AREA OPERATOR",
					"ITEM(RAW)",
					"QUANTITY");
					
		$export_data = $this->model_account_areaopinventory->getAreaOpInventoryToExport($data);
		
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=expenses_data_'.$this->user->nowDate().'.csv');  
		$fp = fopen('php://output', 'w');

		$this->user->exportToCSV($fp, $list, $export_data);
		
	}	
}
?>