<?php   
class ControllerAccountManageWholeSaler extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'managewholesaler'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		
		$this->load->model('account/managewholesaler');
		
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
				
				if($data['task'] == "addNewWholeSaler"){
					$this->data['err_msg'] = $this->model_account_managewholesaler->addNewWholeSaler($data);
				}

				if($data['task'] == "updateholeSalerDetails"){
					$this->data['err_msg'] = $this->model_account_managewholesaler->getWholeSalerDetails($data);
				}

				if($data['task'] == "disableWholeSaler"){
					$this->data['err_msg'] = $this->model_account_managewholesaler->disableWholeSaler($data);
				}
				
				if($data['task'] == "enableWholeSaler"){
					$this->data['err_msg'] = $this->model_account_managewholesaler->enableWholeSaler($data);
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
		$this->data['wholesalers'] = $this->model_account_managewholesaler->getWholeSaler($data, "data");
		$user_total = $this->model_account_managewholesaler->getWholeSaler($data, "total");
		$this->data['provinces'] = $this->model_account_managewholesaler->getProvince();
		$this->data['action'] = $this->url->link('account/managewholesaler');
		
		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/managewholesaler', $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();				
		$this->template = 'default/template/account/managewholesaler.tpl';

		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());
  	}

	public function getWholeSalerDetails(){

		$this->load->model('account/managewholesaler');

		$json = array();
		
		$json['admin'] = $this->model_account_managewholesaler->getWholeSalerDetails($this->request->get);
		$json['status'] = "success";
		
		echo json_encode($json); 
	
	}
	
	public function checkUsername(){
  		$this->load->model('account/managewholesaler');
		$json = array();
		$json['total_user'] = $this->model_account_managewholesaler->getUsername($this->request->get);
		$json['status'] = "success";

		echo json_encode($json);
  	}
	
	public function checkContact(){
  		$this->load->model('account/managewholesaler');
		$json = array();
		$json['total_user'] = $this->model_account_managewholesaler->getContact($this->request->get);
		$json['status'] = "success";

		echo json_encode($json);
  	}
	
	public function checkEmail(){
  		$this->load->model('account/managewholesaler');
		$json = array();
		$json['total_user'] = $this->model_account_managewholesaler->getEmail($this->request->get);
		$json['status'] = "success";

		echo json_encode($json);
  	}
	
}
?>