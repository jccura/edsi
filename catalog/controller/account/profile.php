<?php   
class ControllerAccountProfile extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'profile'))) {
	  		$this->redirect("home");
			//echo 'labas:'.$this->user->isLogged();
    	}
		
		$this->load->model('account/users');
		$this->load->model('account/manageadmin');
		
		$url = '';
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$data = $this->request->post;
			if(isset($data['task'])) {
				if($data['task'] == "editProfile") {
					$this->data['err_msg'] = $this->model_account_users->editUser($data);
				}
				
				if($data['task'] == "editPassword") {
					$this->data['err_msg'] = $this->model_account_users->editPassword($data);
				}
				
				if($data['task'] == "editContacts") {
					$this->data['err_msg'] = $this->model_account_users->editContacts($data);
				}
				
				if($data['task'] == "editAddress") {
					$this->data['err_msg'] = $this->model_account_users->editAddress($data);
				}
				
				if($data['task'] == "editAccount") {
					$this->data['err_msg'] = $this->model_account_users->editAccount($data);
				}
				
				if($data['task'] == "updatePassword") {
						$this->data['err_msg'] = $this->model_account_users->updatePassword($data);
				}
			}
		} else {
			$data = $this->request->get;
		}

	
		if(isset($data['ref'])){
			$template = 'default/template/account/identificationprint.tpl';
		} else {	
			$this->data['user'] = $this->model_account_users->getUserData();
			$this->data['reference'] = $this->model_account_users->getUserReference();
			$this->data['provinces'] = $this->model_account_manageadmin->getProvince();
			$this->data['details'] = $this->model_account_users->getUserAccount();
			$template = 'default/template/account/profile.tpl';
		}
				
		$this->template = $template;
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
		
  	}
	
	public function uploadprofilepic() {

		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'profile'))) {
	  		$this->redirect("home");
			//echo 'labas:'.$this->user->isLogged();
    	}
		
		$this->load->model('account/users');
		$this->load->model('account/manageadmin');
		
		$url = '';
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$data = $this->request->post;
			if(isset($data['task'])) {
		
				if($data['task'] == "uploadprofilepic"){
					$user_id = $this->user->getId();
					$source = "profile_pic";
									
					if($user_id != 0) {
						$target_path = DOC_ROOT."profiles/";
						$this->data['err_msg'] = "";
						$name = $_FILES[$source]['name'];
						
						if(!empty($name)) {
							
							$image_info = getimagesize($_FILES[$source]["tmp_name"]);
							$image_width = $image_info[0];
							$image_height = $image_info[1];

							$file_extension = pathinfo(basename( $_FILES[$source]['name']), PATHINFO_EXTENSION);

							$target_path = $target_path.$user_id.".".strtolower($file_extension); 

							//if($image_width == $image_height) {
								if(strtolower($file_extension) == 'jpg' || strtolower($file_extension) == 'jpeg') {
									
									if (file_exists($target_path)) {
										unlink($target_path);
									}
									
									if(move_uploaded_file($_FILES[$source]['tmp_name'], $target_path)) {
										$this->model_account_users->setProfilePicExt($file_extension,$user_id);
										$this->data['err_msg'] .= " Profile Picture is successfully uploaded.<br>";
									} else {
										$this->data['err_msg'] .= "Upload Result: Failed upload of file ".$_FILES[$source]['name'].".";
									}								
								} else {
									$this->data['err_msg'] .= "Pictures should be have an extension of jpg and jpeg only.<br>";
								}								
							//} else {
							//	$this->data['err_msg'] .= "Length and width of the picture should be the same.";
							//}
							
						} else {
							$this->data['err_msg'] .= "No Picture uploaded.<br>";
						}
						
					}
				
				}
			}
					
		} else {
			$data = $this->request->get;
		}
		
		$this->data['user'] = $this->model_account_users->getUserData();
		$this->data['provinces'] = $this->model_account_manageadmin->getProvince();
		$this->data['details'] = $this->model_account_users->getUserAccount();
		$template = 'default/template/account/profile.tpl';
	
		$this->template = $template;
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
		
  	}
}
?>