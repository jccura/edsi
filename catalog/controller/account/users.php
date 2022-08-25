<?php   
class ControllerAccountUsers extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'users'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		
		$this->load->model('account/users');
		$page = 0; 
		$page_limit = 20; 
		$url = "";
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}			
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;	
			$data = $this->request->post;	
			
			if(isset($data['task'])) {
				if($data['task'] == "add") {
					// echo "add";
					$this->data['err_msg'] = $this->model_account_users->addUser($data);
				}
				
				if($data['task'] == "delete") {
					// echo "delete";
					$this->data['err_msg'] = "";
					if (isset($data['selected'])) {
						foreach ($data['selected'] as $code_selected) {
							$this->data['err_msg'] .= $this->model_account_users->deleteUser($code_selected);
						}	
					}
				}
				
				if($data['task'] == "reset") {
					// echo "reset";
					$this->data['err_msg'] = "";
					if (isset($data['selected'])) {
						foreach ($data['selected'] as $code_selected) {
							$this->data['err_msg'] .= $this->model_account_users->resetUser($code_selected);
						}	
					}
				}
				
				if($data['task'] == "submitedit") {
					// echo "submitedit";
					$this->data['err_msg'] = $this->model_account_users->editUser($data);
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
		
		$this->data['user_groups'] = $this->model_account_users->getUserGroups();
		
		if(!isset($data['task'])) {
			$data['task'] = "";
		}
		
		if($data['task'] == "edit") {
			// echo "edit";
			$this->data['user_details'] = $this->model_account_users->getUserDetails($data['user_id']);
			$this->template = 'default/template/account/usersedit.tpl';
		} else {	
			$this->data['users'] = $this->model_account_users->getUsers($data, "data");
			$user_total = $this->model_account_users->getUsers($data, "total");
			
			$pagination = new Pagination();
			$pagination->total = $user_total;
			$pagination->page = $page;
			$pagination->limit = $page_limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('account/users', $url . '&page={page}', 'SSL');		
			$this->data['pagination'] = $pagination->render();				
			$this->template = 'default/template/account/users.tpl';
		}
		
		
		
		
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
}
?>