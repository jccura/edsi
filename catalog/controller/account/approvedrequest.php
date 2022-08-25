<?php   
class ControllerAccountApprovedRequest extends Controller {   
	
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'approvedrequest'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}

		$url = '';
		$page = 0;
		$page_limit = 10;
		$total = 0;
		
		$this->load->model('account/request');
		
		if(isset($this->request->post['request_id'])){
			$this->data['request_details'] = $this->model_account_request->getRequestDetails($this->request->post['request_id']);
		}
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {			
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}	

			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;			
			
			if($this->request->post['task'] == "search"){
					$this->data['approvedrequests'] = $this->model_account_request->getApprovedRequestList($this->request->post, "data");
					$total = $this->model_account_request->getApprovedRequestList($this->request->post, "total");
			} else {
				$this->data['approvedrequests'] = $this->model_account_request->getApprovedRequestList($this->request->post, "data");
				$total = $this->model_account_request->getApprovedRequestList($this->request->post, "total");					
			}
				
		} else {
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}	

			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;			
			
			if(isset($this->request->get['request_id'])) {
				$this->data['request_id'] = $this->request->get['request_id'];
				$this->data['request_details'] = $this->model_account_request->getRequestDetails($this->data['request_id']);
				$this->data['request_items'] = $this->model_account_request->getRequestItems($this->request->get, "data");
				$this->data['totals'] = $this->model_account_request->getTotalItems($this->data['request_id']);
				$total = $this->model_account_request->getRequestItems($this->request->get, "total");			
			} else {		
				$this->data['approvedrequests'] = $this->model_account_request->getApprovedRequestList($this->request->get, "data");
				$total = $this->model_account_request->getApprovedRequestList($this->request->get, "total");	
			}
		}
		
		$this->data['total'] = $total;

		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/approvedrequest', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->template = 'default/template/account/approvedrequest.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
}
?>