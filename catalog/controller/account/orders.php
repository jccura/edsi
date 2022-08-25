<?php   
class ControllerAccountOrders extends Controller {

	public function index() {

		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'orders'))) {
	  		$this->redirect("shop");
    	}		
		$this->load->model('account/orders');
		$this->load->model('account/common');
		$this->load->model('api/sendorder');
		$page = 0; 
		$page_limit = 20; 
		$url = "";
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}			
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;	
			$data = $this->request->post;	
			
			if(isset($data['task'])) {
				if(!empty($data['task'])) {
					if($data['task'] == "prepared") {
						$stats = '34';
						$this->data['err_msg'] = $this->model_account_orders->statusUpdate($data,$stats);
					}
					
					if($data['task'] == "tagpaid") {
						$this->data['err_msg'] = $this->model_account_orders->tagOrdersAsPaid($data);
					}
					
					if($data['task'] == "tagpacked") {
						$this->data['err_msg'] = $this->model_account_orders->tagOrdersAsPacked($data);
					}
					
					if($data['task'] == "tagshipped") {
						$this->data['err_msg'] = $this->model_account_orders->tagOrdersAsShipped($data);
					}
					
					if($data['task'] == "tagdelivered") {
						$this->data['err_msg'] = $this->model_account_orders->tagDropshipOrderAsDelivered($data);
					}
					
					if($data['task'] == "pickedup") {
						$this->data['err_msg'] = $this->model_account_orders->tagOrderAsPickedUp($data);
					}
					
					if($data['task'] == "tagOrderAsAccepted") {
						$this->data['err_msg'] = $this->model_account_orders->tagOrderAsAccepted($data);
					}
					
					if($data['task'] == "tagOrderAsConfirmed") {
						$this->data['err_msg'] = $this->model_account_orders->tagOrderAsConfirmed($data);
					}
					
					if($data['task'] == "tagOrderAsImHere") {
						$this->data['err_msg'] = $this->model_account_orders->tagOrderAsImHere($data);
					}
					
					if($data['task'] == "tagOrderAsItemPrepared") {
						$this->data['err_msg'] = $this->model_account_orders->tagOrderAsItemPrepared($data);
					}
					
					if($data['task'] == "tagOrderAsItemPickUp") {
						$this->data['err_msg'] = $this->model_account_orders->tagOrderAsItemPickUp($data);
					}
					
					if($data['task'] == "tagOrderAsReturned") {
						$this->data['err_msg'] = $this->model_account_orders->tagOrderAsReturned($data);
					}
					
					if($data['task'] == "tagOrderAsDelivered") {
						$this->data['err_msg'] = $this->model_account_orders->tagOrderAsDelivered($data);
					}
					
					if($data['task'] == "cancel") {
						$result = $this->model_account_orders->tagOrdersAsCancelled($data);
						$this->data['err_msg'] = $result['msg'];
						
						/*
						if(isset($data['selected'])){
							foreach($data['selected'] as $order_id){
								if($result['payment_option'] == 106 || $result['payment_option'] == 93) {
									if($result['valid'] == 1){
										$data['order_id'] = $order_id;
										$this->model_api_sendorder->sendOrderDetails($data);
									} else {
										$this->data['err_msg'] .= $result['msg'];
									}
									
								}
							}
						}
						*/
					}
					
					if($data['task'] == "tagreturned") {
						$this->data['err_msg'] = $this->model_account_orders->tagOrdersAsReturned($data);
						
					}
					
					if($data['task'] == "cancelcustomer") {
						$this->data['err_msg'] = $this->model_account_orders->tagOrderAsCancelledByCustomer($data);
					}
					
					
					if($data['task'] == "updateOrder") {
						$this->data['err_msg'] = "";
						$this->data['err_msg'] = $this->model_account_orders->updateOrder($data);
						
						//upload pic start
						$type = "paymentimages";
						$source = "proof_of_payment";
						$name = "";
						
						if(isset($_FILES[$source]['name'])) { 
							$target_path = DOC_ROOT.$type."/";
							$name = $_FILES[$source]['name'];
							$this->load->model('account/customers');
						}
						
						if(!empty($name)) {
							$image_width = 0;
							$image_height = 0;
							
							$this->data['err_msg'] = "";
							$file_extension = pathinfo(basename( $_FILES[$source]['name']), PATHINFO_EXTENSION);
							
							$target_path = $target_path.$type.$data['order_id'].".".strtolower($file_extension); 
							
							if(strtolower($file_extension) == 'jpg' or strtolower($file_extension) == 'jpeg' or strtolower($file_extension) == 'png') {
								
								if (file_exists($target_path)) {
									unlink($target_path);
								}
							
								if(move_uploaded_file($_FILES[$source]['tmp_name'], $target_path)) {
									
									$this->data['err_msg'] .= " Successful upload of the proof of payment. Please wait for the delivery of Order Id ".$data['order_id'].".<br>";
									
									$this->model_account_orders->updateFileExtension($data['order_id'], $file_extension);
									
								} else {
									$this->data['err_msg'] .= "Upload Result: Failed upload of file ".$_FILES[$source]['name'].".";
								}	
							
							} else {
								$this->data['err_msg'] .= "Pictures should be have an extension of jpg only.";
							}
						} 									
						//upload pic end
					}
				}
			}
			
		} else {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}			
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
		}
		
		if(isset($this->request->get['order_status'])){
			$this->data['button_actions'] = $this->request->get['status_search'];
		}
		
		$data['type'] = "orders";
		
		$grouping2 = "";
		if($this->user->getUserGroupId() == 41) {
			$grouping2 = "Aling V";
		} else if($this->user->getUserGroupId() == 42) {
			$grouping2 = "MANONGEXPRESS";
		} else if($this->user->getUserGroupId() == 43) {
			$grouping2 = "DROPSHIP";
		} else if($this->user->getUserGroupId() == 49) {
			$grouping2 = "CODCOP";
		} 
		
		$this->data['manong_orders_per_status'] = $this->model_account_orders->getManongOrdersPerStatus($data);
		$this->data['orders_per_status'] = $this->model_account_orders->getLBCCodCopOrdersPerStatus($data);
		$this->data['total_orders'] = $this->model_account_orders->getTotalBranchOrders($data);
		$this->data['total_amount'] = $this->model_account_orders->getTotalBranchAmount($data);
		$this->data['statuses'] = $this->model_account_orders->getStatusByGrouping("TRANSACTION");
		$this->data['products'] = $this->model_account_orders->getProducts();
		$this->data['payment_options'] = $this->model_account_common->getPaymentOptions($grouping2);
		$this->data['delivery_options'] = $this->model_account_common->getdeliveryOptions($grouping2);
		$this->data['mode_of_deliveries'] = $this->model_account_common->getdeliveryOptions($grouping2);
		$this->data['orders'] = $this->model_account_orders->getOrders($data, "data");
		$total = $this->model_account_orders->getOrders($data, "total");
		$params = $this->assembleParams($data);
		// $ref = $data['ref'];
		// $order_id = $data['selected'];
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = PAGINATION_TEXT;
		// $pagination->url = 'orders/'.$ref.'/'.$order_id.'/{page}';
		$pagination->url = $this->url->link('account/orders', $url. $params. '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->template = 'default/template/account/orders.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
		
		$this->response->setOutput($this->render());
  	}
	
	public function cancelledorders() {

		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'orders'))) {
	  		$this->redirect("home");
    	}		
		$this->load->model('account/orders');
		$this->load->model('account/common');
		$page = 0; 
		$page_limit = 20; 
		$url = "";
		
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
		
		if(isset($this->request->get['order_status'])){
			$this->data['button_actions'] = $this->request->get['status_search'];
		}
		
		$data['type'] = "cancelledorders";
		
		$grouping2 = "";
		if($this->user->getUserGroupId() == 41) {
			$grouping2 = "KACHIMOGO";
		} else if($this->user->getUserGroupId() == 42) {
			$grouping2 = "MANONGEXPRESS";
		} else if($this->user->getUserGroupId() == 43) {
			$grouping2 = "DROPSHIP";
		} 
		
		$this->data['statuses'] = $this->model_account_orders->getStatusByGrouping("TRANSACTION");
		$this->data['products'] = $this->model_account_orders->getProducts();
		$this->data['payment_options'] = $this->model_account_common->getPaymentOptions($grouping2);
		$this->data['delivery_options'] = $this->model_account_common->getdeliveryOptions($grouping2);
		$this->data['mode_of_deliveries'] = $this->model_account_common->getdeliveryOptions($grouping2);
		$this->data['orders'] = $this->model_account_orders->getOrders($data, "data");
		$total = $this->model_account_orders->getOrders($data, "total");
		
		//var_dump($this->data['orders']);
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = PAGINATION_TEXT;
		$pagination->url = 'cancelledorders/0/0/{page}';
			
		$this->data['pagination'] = $pagination->render();
		
		$this->template = 'default/template/account/cancelledorders.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
		
		$this->response->setOutput($this->render());
  	}

	public function processedorders() {

		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'orders'))) {
	  		$this->redirect("home");
    	}		
		$this->load->model('account/orders');
		$this->load->model('account/common');
		$page = 0; 
		$page_limit = 20; 
		$url = "";
		
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
		
		if(isset($this->request->get['order_status'])){
			$this->data['button_actions'] = $this->request->get['status_search'];
		}
		
		$data['type'] = "processedorders";
		
		//var_dump($data);
		$grouping2 = "";
		if($this->user->getUserGroupId() == 39) {
			$grouping2 = "EDSI";
		} else if($this->user->getUserGroupId() == 42) {
			$grouping2 = "MANONGEXPRESS";
		} else if($this->user->getUserGroupId() == 43) {
			$grouping2 = "DROPSHIP";
		} 
		
		$this->data['total_orders'] = $this->model_account_orders->getTotalBranchOrders($data);
		$this->data['total_amount'] = $this->model_account_orders->getTotalBranchAmount($data);
		$this->data['statuses'] = $this->model_account_orders->getStatusByGrouping("TRANSACTION");
		$this->data['products'] = $this->model_account_orders->getProducts();
		$this->data['payment_options'] = $this->model_account_common->getPaymentOptions($grouping2);
		$this->data['delivery_options'] = $this->model_account_common->getdeliveryOptions($grouping2);
		$this->data['mode_of_deliveries'] = $this->model_account_common->getdeliveryOptions($grouping2);
		$this->data['orders'] = $this->model_account_orders->getOrders($data, "data");
		$total = $this->model_account_orders->getOrders($data, "total");
		
		//var_dump($this->data['orders']);
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = PAGINATION_TEXT;
		$pagination->url = 'processedorders/0/0/{page}';
			
		$this->data['pagination'] = $pagination->render();
		
		$this->template = 'default/template/account/processedorders.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
		
		$this->response->setOutput($this->render());
  	}	
	
	public function validateRequest($data) {
		$ret = true;
		
		$this->data['err_msg'] = "<h3>Failed selling of products, please check below issues:</h3> <br>";
		$this->data['customer'] = array();
		$this->data['customer'] = $data;

		if($this->model_account_orders->checkUsername($data['username']) == true) {
			$ret = false;
			$this->data['err_msg'] .= "Username is not existing.<br/>";
		}
		
		return $ret;
	}

	public function exporttocsv(){
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'orders'))) {
	  		$this->redirect('home');
    	}
				
		$this->load->model('account/orders');	

		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$data = $this->request->post;
		} else {
			$data = $this->request->get;
		}
		
		$list = array("Order Id",
					"Customer Name",
					"Order by Username",
					"Status",
					"Item/Quantity",
					"Date Created",
					"Date Paid",
					"Date Packed",
					"Date Delivered",
					"Paid/NotPaid?",
					"Address",
					"Contact",
					"Mode of Delivery",
					"Send To",
					"Delivery Fee",
					"Discount",
					"Total",
					"Payment Option",
					"Reference",
					"Notes",
					"Remarks");
					
		$export_data = $this->model_account_orders->getOrdersExportToCSV($data);
		
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=sales_report_'.$this->user->nowDate().'.csv');  
		$fp = fopen('php://output', 'w');
		
		$this->user->exportToCSV($fp, $list, $export_data);
		
	}
	
	public function orderexporttocsvbysup(){
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'orders'))) {
	  		$this->redirect('home');
    	}
				
		$this->load->model('account/customers');	

		$list = array("Supplier Code",
					"Item",
					"Quantity");
					
		$export_data = $this->model_account_orders->orderexporttocsvbysup($this->request->post);
		
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=pink_report_by_supplier_'.$this->user->nowDate().'.csv');  
		$fp = fopen('php://output', 'w');

		$this->user->exportToCSV($fp, $list, $export_data);
		
	}
	
	function assembleParams($data) {
		$params = "";
		
		if(isset($data['ref'])){
			if(!empty($data['ref'])){
				$params .= "&ref=".$data['ref'];
			} 
		}
		
		if(isset($data['order_id'])){
			if(!empty($data['order_id'])){
				$params .= "&order_id=".$data['order_id'];
			} 
		}		
		 
		 //echo $params."<br>";
		 return $params;
	}
	
	
}
?>