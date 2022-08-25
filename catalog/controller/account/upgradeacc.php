<?php   
class ControllerAccountUpgradeAcc extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'upgradeacc'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}	
		
		$this->load->model('account/upgradeacc');
		$data = $this->request->post;
		if(isset($data['task'])){
			if(!empty($data['task'])) {
				if($data['task'] == "upgradeAccount"){
					$result = $this->model_account_upgradeacc->upgradeAccount($data);
					if($result['result'] == 1) {							
						$this->user->login($data['username'], $data['password']);
						$this->redirect($this->user->getRedirectPage());
					} else {
						$this->data['err_msg'] = $result['result_msg'];
					}
				}
			}
		}
		$this->template = 'default/template/account/upgradeacc.tpl';
			
			
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);	
		$this->response->setOutput($this->render());
	}
	
}
?>