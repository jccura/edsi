<?php 
class ControllerApiProfile extends Controller {
	
	public function index() {

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$this->load->model('api/profile');

			$task = $this->request->post['task'];

			if($task == 'update_profile'){
				$result = $this->model_api_profile->updateProfile($this->request->post);				
			} else if ($task == 'update_password'){
				$result = $this->model_api_profile->updatePassword($this->request->post);
			} else if ($task == 'update_picture'){
				$result = $this->model_api_profile->updatePicture($this->request->post);
			}
			
			header('Content-Type: application/json');
			header("HTTP/1.1 ".$result['status']);

			echo json_encode($result, JSON_PRETTY_PRINT); 
    		
    	}  
		
  	}
}
?>