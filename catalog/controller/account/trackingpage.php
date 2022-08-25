<?php 
class ControllerAccountTrackingpage extends Controller {
	private $error = array();
	private $redirect_page;
	
	public function index() {
		
		$this->load->model('account/trackingpage');
		$this->load->model('account/customers');
		$this->load->model('account/orders');
		$this->load->model('api/remarksapi');
		
		$url = '';
		$page = 0;
		$page_limit = 100;
		// $template = 'default/template/account/trackingpage.tpl';
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->get['page'];
			}	
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;
			$data = $this->request->post;
			
			if(isset($data['task'])) {
				if(!empty($data['task'])) {
					if($data['task'] == "updateOrder") {
						$this->data['err_msg'] = "";
						$this->data['err_msg'] = $this->model_account_orders->updateOrder($data);
						
						if($data['payment_option'] == 106 || $data['payment_option'] == 93) {
							// $data['order_id'] = 25;
							$this->load->model('api/sendorder');
							$this->model_api_sendorder->updateRemarksApi($data);
						}
						
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
					
					if($data['task'] == "createTicket") {
						$this->model_account_orders->addRemark($this->request->post);
						$this->request->post['first_time'] = "yes";
						$return_array = $this->model_account_ticket->insertTicket($this->request->post);	
						$this->data['err_msg'] = $return_array['err_msg'];
						$this->model_api_remarksapi->SendRemarks($data);
					}
					
					if($data['task'] == "remarks"){
						$this->model_account_orders->addRemark($this->request->post);
						$this->model_api_remarksapi->SendRemarks($data);
					}
					
					if($data['task'] == "tagpaid") {
						$data['status_id'] = 125;
						$this->data['err_msg'] = $this->model_account_orders->tagOrdersAsPaidApi($data);
						$this->data['err_msg'] = $this->model_account_orders->paymentConfirmed($data);
					}
					
					if($data['task'] == "reuploadPayment") {
						$this->data['err_msg'] = $this->model_account_orders->reuploadPayment($data);
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
	
		// if(!isset($data['ref'])) {
			// $this->redirect('trackingpage/'.$data['ref']);
		// }
		
		$this->data['payment_option'] = $this->model_account_trackingpage->getPaymentOption($data);
		$this->data['track_order_header'] = $this->model_account_trackingpage->getTrackOrderHeader($data);
		$this->data['order_details'] = $this->model_account_trackingpage->getOrderDetails($data);
		$this->data['statuses'] = $this->model_account_customers->getStatusByGrouping("TRANSACTION");
		$this->data['mode_of_deliveries'] = $this->model_account_customers->getStatusByGrouping("DELIVERY OPTION");	
		$this->data['mode_of_collection'] = $this->model_account_customers->getStatusByGrouping("COLLECTION OPTION");	
		$this->data['pages'] = $this->model_account_customers->getStatusByGrouping("PAGES OPTION");
		$this->data['remarks'] = $this->model_account_orders->getRemarks($data);
		
    	$this->template = 'default/template/account/trackingpage.tpl';
		$this->children = array(
			'common/footer',
			'common/header'	
		);
						
		$this->response->setOutput($this->render());
  	}
	


}
?>