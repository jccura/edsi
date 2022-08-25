<?php 
class ControllerCommonHome extends Controller {
	private $error = array();
	private $redirect_page;
	
	public function index() {
	
    	$this->language->load('account/login');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			unset($this->session->data['guest']);
			//echo "<br>dito ";
			
			if($this->user->isLogged()){
				$this->data['logged'] = $this->user->isLogged();
				$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', ''), $this->user->getUserName());
				$this->data['user_group_id'] = $this->user->getUserGroupId();
			}
			
			// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) !== false || strpos($this->request->post['redirect'], HTTPS_SERVER) !== false)) {
				//echo "<br>dito 1";
				$this->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));			
			} else if(isset($this->redirect_page)) {
				//$this->redirect($this->url->link($this->redirect_page));
				$this->redirect($this->redirect_page);
				//echo $this->redirect_page;
			} 
    	} 

    	// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
		if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) !== false || strpos($this->request->post['redirect'], HTTPS_SERVER) !== false)) {
			$this->data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
      		$this->data['redirect'] = $this->session->data['redirect'];	  		
			unset($this->session->data['redirect']);		  	
    	} else {
			$this->data['redirect'] = '';
		}
		
		//echo "<br> ".$this->data['redirect'];

		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
    
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		if($this->user->isLogged()){
			$this->data['logged'] = $this->user->isLogged();
			$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', ''), $this->user->getUserName());
			$this->data['user_group_id'] = $this->user->getUserGroupId();
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/home.tpl';
		} else {
			$this->template = 'default/template/common/home.tpl';
		}
		
		$this->children = array(
			'common/footer',
			'common/header'	
		);
						
		$this->response->setOutput($this->render());
  	}
  
  	private function validate() {
		//echo "test username : ".$this->request->post['username']." <br>";
		//echo "test password: ".$this->request->post['password']." <br>";
		
		$this->data['err_msg'] = "";
		
		$serial_code_flag = 1; //validation flag for serial_code (1 -> if valid, 0 -> if not valid) 
    	if ((utf8_strlen($this->request->post['username']) < 1) || (utf8_strlen($this->request->post['username']) > 50)) {
			$serial_code_flag = 0;
			
    	}		
	    
		
		$serial_password_flag = 1; //validation flag for serial_password (1 -> if valid, 0 -> if not valid) 
    	if ((utf8_strlen($this->request->post['password']) < 1) ) {
			$serial_password_flag = 0;
    	} 
				
		if (($serial_password_flag == 1) && ($serial_code_flag == 1)) {

			    $this->user->login($this->request->post['username'], $this->request->post['password']);
				//echo "<br>1";
				if($this->user->isLogged()){

					$this->redirect_page = $this->user->getRedirectPage();
					return true;
				} else {
					return false;
				}				
    	} else {
			$this->data['err_msg'] .= "Wrong username or password.";
      		return false;
    	}
		
  	}
}
?>