<?php   
class ControllerAccountManageOperator extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'manageoperator'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		
		$this->load->model('account/manageoperator');
		
		$url = '';
		$page = 0;
		$page_limit = 10;

		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}
				$this->request->post['start'] = ($page - 1) * $page_limit;
				$this->request->post['limit'] = $page_limit;
				$data = $this->request->post;	
			
			if(isset($data['task'])) {
				
				if($data['task'] == "addNewOperator"){
					$this->data['err_msg'] = $this->model_account_manageoperator->addNewOperator($data);
				}

				if($data['task'] == "updateOperatorDetails"){
					$this->data['err_msg'] = $this->model_account_manageoperator->updateOperatorDetails($data);
				}

				if($data['task'] == "disableOperator"){
					$this->data['err_msg'] = $this->model_account_manageoperator->disableOperator($data);
				}
				
				if($data['task'] == "enableOperator"){
					$this->data['err_msg'] = $this->model_account_manageoperator->enableOperator($data);
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
		
		$data = $this->request->post;
		$this->data['operators'] = $this->model_account_manageoperator->getOperator($data, "data");
		$user_total = $this->model_account_manageoperator->getOperator($data, "total");
		$this->data['provinces'] = $this->model_account_manageoperator->getProvince();
		$this->data['action'] = $this->url->link('account/manageoperator');
		
		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/manageoperator', $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();				
		$this->template = 'default/template/account/manageoperator.tpl';

		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());
  	}

	public function getOperatorDetails(){

		$this->load->model('account/manageoperator');

		$json = array();
		
		$json['admin'] = $this->model_account_manageoperator->getOperatorDetails($this->request->get);
		$json['status'] = "success";
		
		echo json_encode($json); 
	
	}
	
	public function checkUsername(){
  		$this->load->model('account/manageoperator');
		$json = array();
		$json['total_user'] = $this->model_account_manageoperator->getUsername($this->request->get);
		$json['status'] = "success";

		echo json_encode($json);
  	}
	
	public function checkContact(){
  		$this->load->model('account/manageoperator');
		$json = array();
		$json['total_user'] = $this->model_account_manageoperator->getContact($this->request->get);
		$json['status'] = "success";

		echo json_encode($json);
  	}
	
	public function checkEmail(){
  		$this->load->model('account/manageoperator');
		$json = array();
		$json['total_user'] = $this->model_account_manageoperator->getEmail($this->request->get);
		$json['status'] = "success";

		echo json_encode($json);
  	}
	
}
?>