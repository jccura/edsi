<?php   
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->data['title'] = $this->document->getTitle();
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = $this->config->get('config_ssl');
		} else {
			$this->data['base'] = $this->config->get('config_url');
		}
		
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
				
		if($this->user->isLogged()){
			$this->data['logged'] = $this->user->isLogged();
			$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account'), $this->user->getUserName());
			$this->data['user_group_id'] = $this->user->getUserGroupId();
			$this->load->model('account/users');
			// $this->data['modules'] = $this->model_account_users->getModules($this->user->getUserGroupId());
			$this->data['main_menu'] = $this->model_account_users->getMainMenu($this->user->getUserGroupId());
		}

		if (!isset($this->request->get['route'])) {
			$this->data['redirect'] = $this->url->link('common/home');
		} else {
			$data = $this->request->get;
			
			unset($data['_route_']);
			
			$route = $data['route'];
			
			unset($data['route']);
			
			$url = '';
			
			if ($data) {
				$url = '&' . urldecode(http_build_query($data, '', '&'));
			}			
			
			$this->data['redirect'] = $this->url->link($route, $url);
		}

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['language_code'])) {
			$this->session->data['language'] = $this->request->post['language_code'];
		
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->link('common/home'));
			}
    	}		
			
		if($this->user->isLogged()){
			$this->template = 'default/template/common/header_logged.tpl';
		} else {
			$this->template = 'default/template/common/header.tpl';
		}
		
    	$this->render();
	} 	
}
?>