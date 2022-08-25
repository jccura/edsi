<?php   
class ControllerAccountLoadInventory extends Controller {   
	
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'loadinventory'))) {
	  		$this->redirect($this->url->link('common/shop', ''));
    	}

		$url = '';
		$page = 0;
		$page_limit = 20;
		$total = 0;
		
		$this->load->model('account/loadinventory');
		
		$this->data['categories'] = $this->model_account_loadinventory->getCategories();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->data['items'] = $this->model_account_loadinventory->getItems($this->request->post, "data");
			if(isset($this->request->post['task'])) {
				if($this->request->post['task'] == "saveItem") {
					if($this->validate($this->request->post)) {
						$this->data['notNew'] = "true";
						$this->data['err_msg'] = $this->model_account_loadinventory->addInventory($this->request->post);
						$this->data['load_inventory_id'] = $this->request->post['load_inventory_id'];
						$this->data['purchase_details'] = $this->model_account_loadinventory->getInventoryDetails($this->data['load_inventory_id']);
						$this->data['purchase_items'] = $this->model_account_loadinventory->getInventoryItems($this->request->post, "data");
						$total = $this->model_account_loadinventory->getInventoryItems($this->request->post, "total");
					}					
				} else if($this->request->post['task'] == "createInventory") {
					$result = $this->model_account_loadinventory->createInventory($this->request->post);	
					$this->data['err_msg'] = $result['status_msg'];
					$this->data['load_inventory_id'] = $result['load_inventory_id'];
					$this->request->post['load_inventory_id'] = $result['load_inventory_id'];
					$this->session->data['load_inventory_id'] = $result['load_inventory_id'];
					$this->session->data['post'] = $this->request->post;
					
					$this->data['purchase_details'] = $this->model_account_loadinventory->getInventoryDetails($this->data['load_inventory_id']);
					$this->data['suppliers'] = $this->model_account_loadinventory->getMerchants();
					$this->data['purchase_items'] = $this->model_account_loadinventory->getInventoryItems($this->request->post, "data");
					$total = $this->model_account_loadinventory->getInventoryItems($this->request->post, "total");

				} else if($this->request->post['task'] == "removeItem") {
					$this->data['notNew'] = "true";
					$this->data['err_msg'] = $this->model_account_loadinventory->deleteInventoryItem($this->request->post);	
					$this->data['load_inventory_id'] = $this->request->post['load_inventory_id'];
					$this->data['purchase_details'] = $this->model_account_loadinventory->getInventoryDetails($this->data['load_inventory_id']);
					$this->data['purchase_items'] = $this->model_account_loadinventory->getInventoryItems($this->request->post, "data");
					$total = $this->model_account_loadinventory->getInventoryItems($this->request->post, "total");					
				} else if($this->request->post['task'] == "approve") {
					$this->data['notNew'] = "true";
					$this->data['err_msg'] = $this->model_account_loadinventory->approveInventory($this->request->post);
					$this->data['loadinventories'] = $this->model_account_loadinventory->getLoadInventoryList($this->request->post, "data");
					$total = $this->model_account_loadinventory->getLoadInventoryList($this->request->post, "total")	;											
				} else if($this->request->post['task'] == "cancelinventory") {
					$this->data['notNew'] = "true";
					$this->data['err_msg'] = $this->model_account_loadinventory->cancelInventory($this->request->post);
					$this->data['loadinventories'] = $this->model_account_loadinventory->getLoadInventoryList($this->request->post, "data");
					$total = $this->model_account_loadinventory->getLoadInventoryList($this->request->post, "total");
				}
			} else {
				$this->data['loadinventories'] = $this->model_account_loadinventory->getLoadInventoryList($this->request->post, "data");
				$total = $this->model_account_loadinventory->getLoadInventoryList($this->request->post, "total");					
			}
			
					
		} else {
			$this->data['suppliers'] = $this->model_account_loadinventory->getMerchants();
			$this->data['items'] = $this->model_account_loadinventory->getItems($this->request->get, "data");
			
			if(isset($this->request->get['load_inventory_id'])) {
				$this->data['load_inventory_id'] = $this->request->get['load_inventory_id'];
				$this->data['purchase_details'] = $this->model_account_loadinventory->getInventoryDetails($this->data['load_inventory_id']);
				$this->data['purchase_items'] = $this->model_account_loadinventory->getInventoryItems($this->request->get, "data");
				$total = $this->model_account_loadinventory->getInventoryItems($this->request->get, "total");		
			} else {		
				$this->data['loadinventories'] = $this->model_account_loadinventory->getLoadInventoryList($this->request->get, "data");
				$total = $this->model_account_loadinventory->getLoadInventoryList($this->request->get, "total");	
			}
		}
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/loadinventory', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->template = 'default/template/account/loadinventory.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	

	public function validate($data) {
		$ret = true;
		
		$this->data['err_msg'] = "Failed to add the purchase, please check below issues:<br/>";
		
		if($data['product_id_sel'] == "") {
			$ret = false;
			$this->data['err_msg'] .= "You should select a product.<br/>";
		}			
		
		return $ret;
	}	
	
}
?>