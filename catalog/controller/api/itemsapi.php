<?php   
class ControllerApiItemsApi extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'itemsync'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		
		$url = '';
		$page = 0;
		$page_limit = 25;
		
		$this->load->model('api/itemsapi');
				
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;
			$data = $this->request->post;
			
			if ($this->request->post['task'] == "syncitems") {
				$this->data['err_msg'] = $this->model_api_itemsapi->SyncItems($data);
				

			}
		} else {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
		}
		
		$this->data['item_info'] = $this->model_api_itemsapi->getItems($data, "data");
		$total = $this->model_api_itemsapi->getItems($data, "total");
		
		
		$this->template = 'default/template/api/itemsapi.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}

}
?>