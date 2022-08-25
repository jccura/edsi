<?php   
class ControllerAccountManageSalesRep extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'managesalesrep'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		
		$this->load->model('account/managesalesrep');
		
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
				
				if($data['task'] == "addNewSalesRep"){
					$this->data['err_msg'] = $this->model_account_managesalesrep->addNewSalesRep($data);
				}

				if($data['task'] == "updateSalesRepDetails"){
					$this->data['err_msg'] = $this->model_account_managesalesrep->updateSalesRepDetails($data);
				}

				if($data['task'] == "disableSalesRep"){
					$this->data['err_msg'] = $this->model_account_managesalesrep->disableSalesRep($data);
				}
				
				if($data['task'] == "enableSalesRep"){
					$this->data['err_msg'] = $this->model_account_managesalesrep->enableSalesRep($data);
				}
			}
		} else {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}				
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;	
			$data = $this->request->get;
			
			if(isset($data['task'])) {
				if($data['task'] == "search") {
					$this->data['admins'] = $this->model_account_manageadmin->getAdmin($data, "data");
				}
			}
			
		}	
		
		$data = $this->request->post;
		$this->data['salesreps'] = $this->model_account_managesalesrep->getSalesRep($data, "data");
		$user_total = $this->model_account_managesalesrep->getSalesRep($data, "total");
		$this->data['provinces'] = $this->model_account_managesalesrep->getProvince();
		$this->data['action'] = $this->url->link('account/managesalesrep');
		
		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/managesalesrep', $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();				
		$this->template = 'default/template/account/managesalesrep.tpl';

		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());
  	}

	public function getSalesRepDetails(){

		$this->load->model('account/managesalesrep');

		$json = array();
		
		$json['salesrep'] = $this->model_account_managesalesrep->getSalesRepDetails($this->request->get);
		$json['status'] = "success";
		
		echo json_encode($json); 
	
	}
	
	public function checkUsername(){
  		$this->load->model('account/managesalesrep');
		$json = array();
		$json['total_user'] = $this->model_account_managesalesrep->getUsername($this->request->get);
		$json['status'] = "success";

		echo json_encode($json);
  	}
	
	public function checkContact(){
  		$this->load->model('account/managesalesrep');
		$json = array();
		$json['total_user'] = $this->model_account_managesalesrep->getContact($this->request->get);
		$json['status'] = "success";

		echo json_encode($json);
  	}
	
	public function checkEmail(){
  		$this->load->model('account/managesalesrep');
		$json = array();
		$json['total_user'] = $this->model_account_managesalesrep->getEmail($this->request->get);
		$json['status'] = "success";

		echo json_encode($json);
  	}
	
}
?>