<?php   
class ControllerAccountPurchase extends Controller {   
	
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'account/purchase'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}

		$url = '';
		$page = 0;
		$page_limit = 20;
		$total = 0;
		
		//$this->load->model('account/cart');
		//$this->load->model('account/items');
		$this->load->model('account/purchase');
		
		//$this->data['categories'] = $this->model_account_items->getCategories();
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->data['items'] = $this->model_account_purchase->getItems($this->request->post, "data");
			if(isset($this->request->post['task'])) {
				if($this->request->post['task'] == "additem") {
					if($this->validate($this->request->post)) {
						$this->data['notNew'] = "true";
						$this->data['err_msg'] = $this->model_account_purchase->addPurchase($this->request->post);
						$this->data['purchase_id'] = $this->request->post['purchase_id'];
						$this->data['purchase_details'] = $this->model_account_purchase->getPurchaseDetails($this->data['purchase_id']);
						$this->data['purchase_items'] = $this->model_account_purchase->getPurchaseItems($this->request->post, "data");
						$total = $this->model_account_purchase->getPurchaseItems($this->request->post, "total");
					}					
				} else if($this->request->post['task']== "addSupplier"){
					$this->model_account_purchase->updateSupplier($this->request->post);
					$this->data['purchase_id'] = $this->request->post['purchase_id'];
					$this->data['purchase_details'] = $this->model_account_purchase->getPurchaseDetails($this->data['purchase_id']);
					$this->data['purchase_items'] = $this->model_account_purchase->getPurchaseItems($this->request->post, "data");
					$total = $this->model_account_purchase->getPurchaseItems($this->request->post, "total");
				} else if($this->request->post['task'] == "createpurchase") {
					$result = $this->model_account_purchase->createPurchase($this->request->post);	
					$this->data['err_msg'] = $result['status_msg'];
					$this->data['purchase_id'] = $result['purchase_id'];
					$this->request->post['purchase_id'] = $result['purchase_id'];
					$this->session->data['purchase_id'] = $result['purchase_id'];
					$this->session->data['post'] = $this->request->post;
					
					$this->data['purchase_details'] = $this->model_account_purchase->getPurchaseDetails($this->data['purchase_id']);
					$this->data['suppliers'] = $this->model_account_purchase->getSuppliers();
					$this->data['purchase_items'] = $this->model_account_purchase->getPurchaseItems($this->request->post, "data");
					$total = $this->model_account_purchase->getPurchaseItems($this->request->post, "total");

				} else if($this->request->post['task'] == "deleteitem") {
					$this->data['notNew'] = "true";
					$this->data['err_msg'] = $this->model_account_purchase->deletePurchaseItem($this->request->post);	
					$this->data['purchase_id'] = $this->request->post['purchase_id'];
					$this->data['purchase_details'] = $this->model_account_purchase->getPurchaseDetails($this->data['purchase_id']);
					$this->data['purchase_items'] = $this->model_account_purchase->getPurchaseItems($this->request->post, "data");
					$total = $this->model_account_purchase->getPurchaseItems($this->request->post, "total");					
				} else if($this->request->post['task'] == "approve") {
					$this->data['notNew'] = "true";
					$this->data['err_msg'] = $this->model_account_purchase->approvePurchase($this->request->post);
					$this->data['purchases'] = $this->model_account_purchase->getPurchaseList($this->request->post, "data");
					$total = $this->model_account_purchase->getPurchaseList($this->request->post, "total");												
				}
			} else {
				$this->data['purchases'] = $this->model_account_purchase->getPurchaseList($this->request->post, "data");
				$total = $this->model_account_purchase->getPurchaseList($this->request->post, "total");					
			}
			
					
		} else {
			$this->data['suppliers'] = $this->model_account_purchase->getSuppliers();
			$this->data['items'] = $this->model_account_purchase->getItems($this->request->get, "data");
			if(isset($this->request->get['purchase_id'])) {
				$this->data['purchase_id'] = $this->request->get['purchase_id'];
				$this->data['purchase_details'] = $this->model_account_purchase->getPurchaseDetails($this->data['purchase_id']);
				$this->data['purchase_items'] = $this->model_account_purchase->getPurchaseItems($this->request->get, "data");
				$total = $this->model_account_purchase->getPurchaseItems($this->request->get, "total");			
			} else {		
				$this->data['purchases'] = $this->model_account_purchase->getPurchaseList($this->request->get, "data");
				$total = $this->model_account_purchase->getPurchaseList($this->request->get, "total");	
			}
		}
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/purchase', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->template = 'default/template/account/purchase.tpl';
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