<?php  
class ControllerAccountMyreseller extends Controller {
	public function index() {
	
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'myreseller'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}	

		$url = '';
		$upage = 0;
		$this->load->model('account/customers');
		$page_limit = 50;
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTPS_SERVER;
		}
		
		$i_user_id = $this->user->getId();
		
		if (($this->request->server['REQUEST_METHOD'] == 'GET')) {
			if(isset($this->request->get['u'])){
				if($this->request->get['u']<>"0") {
					$i_user_id = $this->request->get['u'];
				}
			}
			
			$upage = 1;
			if (isset($this->request->get['upage'])) {
				$upage = $this->request->get['upage'];
			}		
			
			$this->request->get['start'] = ($upage - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;	

			$this->data['total_reseller'] = $this->model_account_customers->getTotalResellerList($i_user_id, $this->request->get);			
			$this->data['resellers_list'] = $this->model_account_customers->getResellerList($i_user_id,$this->request->get);	

			
		} else {
			if (isset($this->request->post['upage'])) {
				$upage = $this->request->post['upage'];
			}		
			
			$this->request->post['start'] = ($upage - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;	
			
			$this->data['total_reseller'] = $this->model_account_customers->getTotalResellerList($i_user_id, $this->request->post);			
			$this->data['reseller_list'] = $this->model_account_customers->getResellerList($i_user_id,$this->request->post);			
			
		}
		
		$this->template = 'default/template/account/myreseller.tpl';
		$pagination = new Pagination();
		$pagination->total = $this->data['total_reseller'];
		$pagination->page = $upage;
		$pagination->limit = $page_limit;
		$pagination->text = PAGINATION_TEXT;
		$pagination->url = "mywholesaler/{page}";
			
		$this->data['pagination'] = $pagination->render();		
		
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'
		);
										
		$this->response->setOutput($this->render());
	}
}
?>