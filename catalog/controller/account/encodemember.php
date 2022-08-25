<?php   
class ControllerAccountEncodeMember extends Controller {   
	
	public function index() {
		/*manul creation of user*/
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'encodemember'))) {
	  		$this->redirect($this->url->link('common/home', ''));	
    	}
		
		$this->load->model('account/encodemember');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) { 
			$data = $this->request->post;
			if($data['task'] == "manualCreate"){
				$this->data['members'] = $data;
				$this->data['err_msg'] = "";
				for($i=1; $i<=10; $i++) {
					$member = array();
					$member['row'] = $i;
					$member['username'] = $data['username'.$i];
					$member['password'] = $data['password'.$i];
					$member['firstname'] = $data['firstname'.$i];
					$member['lastname'] = $data['lastname'.$i];
					$member['email'] = $data['email'.$i];
					$member['contact'] = $data['contact'.$i];
					$member['sponsor'] = $data['sponsor'.$i];
					$member['user_group_id'] = $data['user_group_id'.$i];
					$this->data['err_msg'] .= $this->model_account_encodemember->manualCreate($member);
				}
			}
			
			if($data['task'] == "manualCreate2"){
				$this->data['members'] = $data;
				$this->data['err_msg'] = "";
				$i = 1;
				
				$to_define = $this->model_account_encodemember->getToDefine();
				foreach($to_define as $td) {
					$member = array();
					$member['row'] = $i;
					$member['username'] = $td['username'];
					$member['password'] = $td['password'];
					$member['firstname'] = $td['firstname'];
					$member['lastname'] = $td['lastname'];
					$member['email'] = $td['email'];
					$member['contact'] = $td['contact'];
					$member['sponsor'] = $td['sponsor'];
					$member['user_group_id'] = $td['user_group_id'];
					$member['country_id'] = $td['country_id'];
					$member['address'] = $td['address'];
					$member['password'] = $td['password'];
					$member['province_id'] = $td['province_id'];
					$member['city_municipality_id'] = $td['city_municipality_id'];
					$member['barangay_id'] = $td['barangay_id'];
					$member['date_added'] = $td['date_added'];
					$member['contact'] = $td['contact'];
					$this->data['err_msg'] .= $this->model_account_encodemember->manualCreate2($member);
					$i++;
				}
			}
		}		
		$this->template = 'default/template/account/encodemember.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
		
}
?>