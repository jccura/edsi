<?php   
class ControllerAccountInventory extends Controller {   
	
	public function index() {
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'inventory'))) {
	  		$this->redirect($this->url->link('common/shop', ''));
    	}

		$url = '';
		$page = 0;
		$page_limit = 50;
		$total = 0;
		
		$this->load->model('account/inventory');

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
		
		$this->data['products'] = $this->model_account_inventory->getProductList($data, "data");
		$total = $this->model_account_inventory->getProductList($data, "total");		
		$this->data['items'] = $this->model_account_inventory->getActiveItems();
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = 'inventory/{page}';
			
		$this->data['pagination'] = $pagination->render();	
		
		$this->template = 'default/template/account/inventory.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}

	public function history() {
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'inventory'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}

		$url = '';
		$page = 0;
		$page_limit = 50;
		$total = 0;
		
		$this->load->model('account/inventory');
		
		$this->data['item_history'] = $this->model_account_inventory->getItemHistory($this->request->get,"data");
		$this->data['item_historytotal'] = $this->model_account_inventory->getItemHistoryTotal($this->request->get,"data");
		$total = $this->model_account_inventory->getItemHistory($this->request->get,"total");
		
		$this->data['total_restock'] = 0;
		$this->data['total_sold'] = 0;
		
		foreach($this->data['item_history'] as $ih) {
			$this->data['total_restock'] += $ih['re_stock'];
			$this->data['total_sold'] += $ih['sold'];
		}
		
		$this->data['total_remaining'] = $this->data['total_restock'] - $this->data['total_sold'];		
		
		if(isset($this->request->get['item_id'])) {
			$this->data['item'] = $this->model_account_inventory->getItem($this->request->get['item_id']);
		}
		
		//var_dump($this->data);
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = 'inventoryhist/'.$this->request->get['item_id'].'/{page}';
			
		$this->data['pagination'] = $pagination->render();	
		
		$this->template = 'default/template/account/inventoryhist.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}	
}
?>