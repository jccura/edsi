<?php   
class ControllerAccountSalesDeliveredReport extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'salesdeliveredreport'))) {
	  		$this->redirect("home");
    	}
		
		$this->load->model('account/salesdeliveredreport');

		$url = '';
		$page = 0;
		$page_limit = 20;
			

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
		
		$this->data['salesdeliveredreport'] = $this->model_account_salesdeliveredreport->getSalesDeliveredRep($data, "data");
		$this->data['summary'] = $this->model_account_salesdeliveredreport->getSalesDeliveredRep($data, "summary");		
		$total = $this->data['total'] = $this->model_account_salesdeliveredreport->getSalesDeliveredRep($data, "total");
			
		$pagination = new Pagination();
		$pagination->total = $this->data['total'];
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/salesdeliveredreport', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();	
		
		$this->template = 'default/template/account/salesdeliveredreport.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}

	
}
?>