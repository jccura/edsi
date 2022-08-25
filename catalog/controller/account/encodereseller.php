<?php   
class ControllerAccountEncodeReseller extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'encodereseller'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		
		$this->load->model('account/register');
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
				if($data['task'] == "encode") {
					$this->data['err_msg'] = "<br><br>";
					for($i=1;$i<=20;$i++) {
						$user = array();
						$user['row'] = $i;
						$user['username'] = $data['username'.$i];
						$user['firstname'] = $data['firstname'.$i];
						$user['middlename'] = $data['middlename'.$i];
						$user['lastname'] = $data['lastname'.$i];
						$user['mobile'] = $data['mobile'.$i];
						$user['email'] = $data['email'.$i];
						$user['sponsor'] = $data['sponsor'.$i];
						$user['password'] = "11111111";
						$result = $this->model_account_register->addManualMember($user);
						$this->data['err_msg'] .= $result['result_msg']."<br>";
					}
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
		
		$this->template = 'default/template/account/encodereseller.tpl';
		
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
}
?>