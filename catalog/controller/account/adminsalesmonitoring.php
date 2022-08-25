<?php   
class ControllerAccountAdminsalesmonitoring extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'adminsalesmonitoring'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		
		$this->load->model('account/adminsalesmonitoring');
		
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
				
				if($data['task'] == "addNewAdmin"){
					$this->data['err_msg'] = $this->model_account_adminsalesmonitoring->addNewAdmin($data);
				}

				if($data['task'] == "updateAdminDetails"){
					$this->data['err_msg'] = $this->model_account_adminsalesmonitoring->updateAdminDetails($data);
				}

				if($data['task'] == "disableAdmin"){
					$this->data['err_msg'] = $this->model_account_adminsalesmonitoring->disableAdmin($data);
				}
				
				if($data['task'] == "enableAdmin"){
					$this->data['err_msg'] = $this->model_account_adminsalesmonitoring->enableAdmin($data);
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
		$this->data['admin_sales'] = $this->model_account_adminsalesmonitoring->getAdminSales($data, "data");
		$user_total = $this->model_account_adminsalesmonitoring->getAdminSales($data, "total");
		$this->data['provinces'] = $this->model_account_adminsalesmonitoring->getProvince();
		$this->data['action'] = $this->url->link('account/adminsalesmonitoring');
		
		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/adminsalesmonitoring', $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();				
		$this->template = 'default/template/account/adminsalesmonitoring.tpl';

		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());
  	}

	public function getAdminDetails(){

		$this->load->model('account/adminsalesmonitoring');

		$json = array();
		
		$json['admin'] = $this->model_account_adminsalesmonitoring->getAdminDetails($this->request->get);
		$json['status'] = "success";
		
		echo json_encode($json); 
	
	}
	
	public function checkUsername(){
  		$this->load->model('account/adminsalesmonitoring');
		$json = array();
		$json['total_user'] = $this->model_account_adminsalesmonitoring->getUsername($this->request->get);
		$json['status'] = $json['total_user'] > 0 ? "failed" : "success";

		echo json_encode($json);
  	}
	
	public function checkContact(){
  		$this->load->model('account/adminsalesmonitoring');
		$json = array();
		$json['total_user'] = $this->model_account_adminsalesmonitoring->getContact($this->request->get);
		$json['status'] = "success";

		echo json_encode($json);
  	}
	
	public function checkEmail(){
  		$this->load->model('account/adminsalesmonitoring');
		$json = array();
		$json['total_user'] = $this->model_account_adminsalesmonitoring->getEmail($this->request->get);
		$json['status'] = "success";

		echo json_encode($json);
  	}
	
}
?>