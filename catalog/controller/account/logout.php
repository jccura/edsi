<?php 
class ControllerAccountLogout extends Controller {
	public function index() {
	
		//echo "ok";
		
		if ($this->user->isLogged()) {
      		$this->user->logout();
    	}
						
		$this->redirect('shop');	
  	}
}
?>