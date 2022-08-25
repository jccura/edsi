<?php  
class ControllerAccountMycitydistributor extends Controller {
	public function index() {
	
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'mycitydistributor'))) {
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

			$this->data['total_citydistributor'] = $this->model_account_customers->getTotalCitydistributorList($i_user_id, $this->request->get);			
			$this->data['citydistributor_list'] = $this->model_account_customers->getCitydistributorList($i_user_id,$this->request->get);			

			
		} else {
			if (isset($this->request->post['upage'])) {
				$upage = $this->request->post['upage'];
			}		
			
			$this->request->post['start'] = ($upage - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;	
					
			if (isset($this->request->get['search'])) {
				if($this->request->get['search']==1) {
				
					$i_user_id = $this->model_account_customers->getCustomerId($this->request->post['search_user']);				
					if($i_user_id <> 0) {
						if(!$this->model_account_customers->checkIfDownline($this->user->getId(),$i_user_id)) {
							$i_user_id = $this->user->getId();
							//echo "false";
						}							
					} else {
						$i_user_id = $this->user->getId();
					}
					
					$this->data['search_user'] = $this->request->post['search_user'];
				}
			}
			
			$this->data['total_citydistributor'] = $this->model_account_customers->getTotalCitydistributorList($i_user_id, $this->request->post);			
			$this->data['citydistributor_list'] = $this->model_account_customers->getCitydistributorList($i_user_id,$this->request->post);			
			
		}
		
		$this->data['total_active_reseller'] = $this->model_account_customers->getTotalActiveMF();
		$this->data['total_inactive_reseller'] = $this->model_account_customers->getTotalInActiveMF();
		
		$this->template = 'default/template/account/mycitydistributor.tpl';
		$pagination = new Pagination();
		$pagination->total = $this->data['total_citydistributor'];
		$pagination->page = $upage;
		$pagination->limit = $page_limit;
		$pagination->text = PAGINATION_TEXT;
		$pagination->url = "mycitydistributor/{page}";
			
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