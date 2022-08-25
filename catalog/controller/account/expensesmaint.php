<?php   
class ControllerAccountExpensesMaint extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'expensesmaint'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}	
		
		$this->load->model('account/expenses');
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
				
				if($data['task'] == 'addTypeExpenses') {
					$result = $this->data['err_msg'] = $this->model_account_expenses->addTypeExpenses($data);
					$this->data['err_msg'] = $result['err_msg'];
					
				}
				if($data['task'] == 'clearType') {
					$result = $this->data['err_msg'] = $this->model_account_expenses->clearType($data);
					$this->data['err_msg'] = $result['err_msg'];
					
				}
				
				if($data['task'] == "updateTypeDetails"){
					$this->data['err_msg'] = $this->model_account_expenses->updateTypeDetails($data);
				}
				
				if($data['task'] == "disableType"){
					$this->data['err_msg'] = $this->model_account_expenses->disableType($data);
				}
				
				if($data['task'] == "enableType"){
					$this->data['err_msg'] = $this->model_account_expenses->enableType($data);
				}
				
				if($data['task'] == "submitEdit"){
					$result = $this->data['err_msg'] = $this->model_account_expenses->submitEdit($data);
					$this->data['err_msg'] = $result['err_msg'];
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
	

		if(!isset($data['task'])) {
			$data['task'] = "";
		}
		
		if($data['task'] == "edit") {
			$this->data['expenses_type_details'] = $this->model_account_expenses->getExpenseTypeDetails($data['expenses_type_id']);
			$template = 'default/template/account/expensesmaintedit.tpl';
		} else {
			$this->data['expense_type'] = $this->model_account_expenses->getExpenseType($data, "data");
			// $this->data['total'] = $this->model_account_expenses->getExpenseType($data, "total");
			$template = 'default/template/account/expensesmaint.tpl';
		}
		$this->data['total'] = $this->model_account_expenses->getExpenseType($data, "total");
		$this->data['expense_group'] = $this->model_account_expenses->getExpenseGroup();
	
		$pagination = new Pagination();
		$pagination->total = $this->data['total'];
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/expensesmaint', $url . '&page={page}', 'SSL');		
		$this->data['pagination'] = $pagination->render();
		$this->template = $template;
			
			
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);	
		$this->response->setOutput($this->render());
	}
	public function getTypeDetails(){

		$json = array();
		
		$json['admin'] = $this->model_account_expenses->getTypeDetails($this->request->get);
		$json['status'] = "success";
		
		echo json_encode($json); 
	
	}
}
?>