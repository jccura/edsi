<?php   
class ControllerAccountManageAdmin extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'manageadmin'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		
		$this->load->model('account/manageadmin');
		
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
					// echo "addNewAdmin";
					$this->data['err_msg'] = $this->model_account_manageadmin->addNewAdmin($data);
				}

				if($data['task'] == "updateAdminDetails"){
					$this->data['err_msg'] = $this->model_account_manageadmin->updateAdminDetails($data);
				}

				if($data['task'] == "disableAdmin"){
					$this->data['err_msg'] = $this->model_account_manageadmin->disableAdmin($data);
				}
				
				if($data['task'] == "enableAdmin"){
					$this->data['err_msg'] = $this->model_account_manageadmin->enableAdmin($data);
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
		$this->data['admins'] = $this->model_account_manageadmin->getAdmin($data, "data");
		$user_total = $this->model_account_manageadmin->getAdmin($data, "total");
		$this->data['provinces'] = $this->model_account_manageadmin->getProvince();
		$this->data['action'] = $this->url->link('account/manageadmin');
		
		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/manageadmin', $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();				
		$this->template = 'default/template/account/manageadmin.tpl';

		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());
  	}

	public function getAdminDetails(){

		$this->load->model('account/manageadmin');

		$json = array();
		
		$json['admin'] = $this->model_account_manageadmin->getAdminDetails($this->request->get);
		$json['status'] = "success";
		
		echo json_encode($json); 
	
	}
	
	public function checkUsername(){
  		$this->load->model('account/manageadmin');
		$json = array();
		$json['total_user'] = $this->model_account_manageadmin->getUsername($this->request->get);
		$json['status'] = $json['total_user'] > 0 ? "failed" : "success";

		echo json_encode($json);
  	}
	
	public function checkContact(){
  		$this->load->model('account/manageadmin');
		$json = array();
		$json['total_user'] = $this->model_account_manageadmin->getContact($this->request->get);
		$json['status'] = "success";

		echo json_encode($json);
  	}
	
	public function checkEmail(){
  		$this->load->model('account/manageadmin');
		$json = array();
		$json['total_user'] = $this->model_account_manageadmin->getEmail($this->request->get);
		$json['status'] = "success";

		echo json_encode($json);
  	}
	
}
?>