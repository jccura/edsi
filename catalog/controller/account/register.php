<?php   
class ControllerAccountRegister extends Controller {
	public function index() {		
		$this->load->model('account/register');		
		if (($this->request->server['REQUEST_METHOD'] == 'GET')) { 	
			$data = $this->request->get;
			if(isset($data['sponsor'])) {				
				$this->data['sponsor'] = $data['sponsor'];				
				if(!empty($data['sponsor'])) {					
					$sponsor_name_array = explode("?",$data['sponsor']);					
					$sponsor_name_array2 = explode("&",$sponsor_name_array[0]);					
					$this->data['sponsor'] = strtolower($sponsor_name_array2[0]);				
				}			
			}								
		} else {
			$data = $this->request->post;
			if(isset($data['task'])){
				if(!empty($data['task'])) {
					if($data['task'] == "add"){
						$result = $this->model_account_register->addMember($data);
						if($result['result'] == 1) {							
							$this->user->login($data['username'], $data['password']);
							$this->redirect($this->user->getRedirectPage());
						} else {
							$this->data['err_msg'] = $result['result_msg'];
						}
					}
				}
			}
		}
		$this->template = 'default/template/account/register.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
		$this->response->setOutput($this->render());
  	}
	
	public function getsponsorinfo() {

		$this->load->model('account/register');
		$sponsor_name = "";
		$sponsor_details = $this->model_account_register->getSponsorDetails($this->request->get['username']);		
		$json['sponsor_id_no'] = $sponsor_details['id_no'];		
		$json['name'] = $sponsor_details['name'];			
		$json['sponsor_id'] = $sponsor_details['sponsor_id'];			
		$json['activation_flag'] = $sponsor_details['activation_flag'];			
		$json['success'] = sprintf($sponsor_details['success']);		
		$this->response->setOutput(json_encode($json));			
	
	}
}
?>