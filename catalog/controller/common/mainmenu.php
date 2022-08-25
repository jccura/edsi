<?php 
class ControllerCommonMainmenu extends Controller {
	private $error = array();
	private $redirect_page;
	
	public function index() {
	
		$this->load->model('account/users');
		
		$this->data['modules'] = $this->model_account_users->getModules($this->user->getUserGroupId());

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/mainmenu.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/mainmenu.tpl';
		} else {
			$this->template = 'default/template/common/mainmenu.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			//'common/column_right',
			//'common/content_top',
			//'common/content_bottom',
			'common/footer',
			'common/header'	
		);
						
		$this->response->setOutput($this->render());
  	}
  
}
?>