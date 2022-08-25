<?php   
class ControllerAccountRankHist extends Controller {   
	
	public function index() {
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'rankhist'))) {
	  		$this->redirect($this->url->link('common/shop', ''));
    	}

		$url = '';
		$page = 0;
		$page_limit = 50;
		$total = 0;
		
		$this->load->model('account/rankhist');

		$data = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;
			$data = $this->request->post;			
		} else {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;	
		}

		$this->data['rank_history'] = $this->model_account_rankhist->getRankHist();
		
		$pagination = new Pagination();
		// $pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = 'rankhist/{page}';
			
		$this->data['pagination'] = $pagination->render();	
		
		$this->template = 'default/template/account/rankhist.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
}
?>