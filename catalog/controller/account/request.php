<?php   
class ControllerAccountRequest extends Controller {   
	
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'request'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}

		$url = '';
		$page = 0;
		$page_limit = 20;
		$total = 0;
		
		$this->load->model('account/request');
		
		$this->data['categories'] = $this->model_account_request->getCategories();

		if(isset($this->request->post['request_id'])){
			$this->data['request_details'] = $this->model_account_request->getRequestDetails($this->request->post['request_id']);
		}
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {			
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}	

			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;			
			
			$this->data['items'] = $this->model_account_request->getItems($this->request->post, "data");
			if(isset($this->request->post['task'])) {
				if($this->request->post['task'] == "additem") {
					if($this->validate($this->request->post)) {
						$this->data['err_msg'] = $this->model_account_request->addRequestItem($this->request->post);
						$this->data['request_id'] = $this->request->post['request_id'];
						$this->data['request_details'] = $this->model_account_request->getRequestDetails($this->data['request_id']);
						$this->data['request_items'] = $this->model_account_request->getRequestItems($this->request->post, "data");
						$total = $this->model_account_request->getRequestItems($this->request->post, "total");
						$this->data['totals'] = $this->model_account_request->getTotalItems($this->data['request_id']);
					}					
				//transfer inventory
				} else if($this->request->post['task'] == "transferinventory") {
						$result = $this->model_account_request->transferinventory($this->request->post);	
						$this->data['err_msg'] = $result['status_msg'];
						$this->data['request_id'] = $result['request_id'];
						$this->request->post['request_id'] = $result['request_id'];
						$this->session->data['request_id'] = $result['request_id'];
						$this->data['request_details'] = $this->model_account_request->getRequestDetails($result['request_id']);
						$this->data['request_items'] = $this->model_account_request->getRequestItems($this->request->post, "data");
						$total = $this->model_account_request->getRequestItems($this->request->post, "total");
						$this->data['totals'] = $this->model_account_request->getTotalItems($this->data['request_id']);
						$this->session->data['post'] = $this->request->post;
				//request inventory		
				} else if($this->request->post['task'] == "requestinventory") {
						$result = $this->model_account_request->requestinventory($this->request->post);	
						$this->data['err_msg'] = $result['status_msg'];
						$this->data['request_id'] = $result['request_id'];
						$this->request->post['request_id'] = $result['request_id'];
						$this->session->data['request_id'] = $result['request_id'];
						$this->data['request_details'] = $this->model_account_request->getRequestDetails($result['request_id']);
						$this->data['request_items'] = $this->model_account_request->getRequestItems($this->request->post, "data");
						$total = $this->model_account_request->getRequestItems($this->request->post, "total");
						$this->data['totals'] = $this->model_account_request->getTotalItems($this->data['request_id']);
						$this->session->data['post'] = $this->request->post;
						
				} else if($this->request->post['task'] == "deleteitem") {
					$this->data['err_msg'] = $this->model_account_request->deleteRequestItem($this->request->post);	
					$this->data['request_id'] = $this->request->post['request_id'];
					$this->data['request_details'] = $this->model_account_request->getRequestDetails($this->data['request_id']);
					$this->data['request_items'] = $this->model_account_request->getRequestItems($this->request->post, "data");
					$this->data['totals'] = $this->model_account_request->getTotalItems($this->data['request_id']);
					$total = $this->model_account_request->getRequestItems($this->request->post, "total");	
				} else if($this->request->post['task'] == "allocateInventory") {
					$this->data['err_msg'] = $this->model_account_request->allocateInventory($this->request->post);	
					$this->data['request_id'] = $this->request->post['request_id'];
					$this->data['request_details'] = $this->model_account_request->getRequestDetails($this->data['request_id']);
					$this->data['request_items'] = $this->model_account_request->getRequestItems($this->request->post, "data");
					$this->data['totals'] = $this->model_account_request->getTotalItems($this->data['request_id']);
					$total = $this->model_account_request->getRequestItems($this->request->post, "total");					
				} else if($this->request->post['task'] == "approve") {
					$this->data['err_msg'] = $this->model_account_request->approveRequest($this->request->post);
					$this->data['request_id'] = $this->request->post['request_id'];
					$this->data['request_details'] = $this->model_account_request->getRequestDetails($this->data['request_id']);
					$this->data['request_items'] = $this->model_account_request->getRequestItems($this->request->post, "data");
					$this->data['totals'] = $this->model_account_request->getTotalItems($this->data['request_id']);
					$total = $this->model_account_request->getRequestItems($this->request->post, "total");													
				} else if($this->request->post['task'] == "apewallet") {
					if($this->model_account_request->checkapewallet($this->request->post) == false){
						$this->data['err_msg'] = "Cannot proceed inventory transfer request due to insufficient AP eWallet.";
					} else {
						$this->model_account_request->updateStatusviaApewallet(3, $this->request->post);
						$this->data['err_msg'] = "Inventory transfer successfully processed via AP eWallet.";
					}
				} else if($this->request->post['task'] == "adjustInventory") {
					$this->data['err_msg'] = $this->model_account_request->adjustInventoryRequest($this->request->post);
					$this->data['request_id'] = $this->request->post['request_id'];
					$this->data['request_details'] = $this->model_account_request->getRequestDetails($this->data['request_id']);
					$this->data['request_items'] = $this->model_account_request->getRequestItems($this->request->post, "data");
					$this->data['totals'] = $this->model_account_request->getTotalItems($this->data['request_id']);
					$total = $this->model_account_request->getRequestItems($this->request->post, "total");		
				} else if($this->request->post['task'] == "search"){
					$this->data['requests'] = $this->model_account_request->getRequestList($this->request->post, "data");
					$total = $this->model_account_request->getRequestList($this->request->post, "total");
				} else if($this->request->post['task'] == "cancelRequest"){
					$this->data['err_msg'] = $this->model_account_request->cancelRequest($this->request->post);
					$this->data['requests'] = $this->model_account_request->getRequestList($this->request->post, "data");
					$total = $this->model_account_request->getRequestList($this->request->post, "total");
				} else if($this->request->post['task'] == "uploadpayment") {
					//upload pic start
					$type = "requestimages";
					$source = "proof_of_payment";
					$name = "";
					$data = $this->request->post;
					
					if(isset($_FILES[$source]['name'])) { 
						$target_path = DOC_ROOT.$type."/";
						$name = $_FILES[$source]['name'];
					}
					
					if(!empty($name)) {
						$image_width = 0;
						$image_height = 0;
						
						$this->data['err_msg'] = "";
						$file_extension = pathinfo(basename( $_FILES[$source]['name']), PATHINFO_EXTENSION);
						
						$target_path = $target_path.$type.$data['request_id'].".".strtolower($file_extension); 
						
						if(strtolower($file_extension) == 'jpg' or strtolower($file_extension) == 'jpeg' or strtolower($file_extension) == 'png') {
							
							if (file_exists($target_path)) {
								unlink($target_path);
							}
						
							if(move_uploaded_file($_FILES[$source]['tmp_name'], $target_path)) {
								$this->data['err_msg'] .= " Successful upload of the proof of payment. Please wait for the delivery of Order Id ".$data['request_id'].".<br>";
								$this->model_account_request->updateFileExtension($data['request_id'], $file_extension);
							} else {
								$this->data['err_msg'] .= "Upload Result: Failed upload of file ".$_FILES[$source]['name'].".";
							}	
						
						} else {
							$this->data['err_msg'] .= "Pictures should be have an extension of jpg only.";
						}
					} 									
					//upload pic end
					$this->data['request_id'] = $data['request_id'];
					$this->data['request_details'] = $this->model_account_request->getRequestDetails($this->data['request_id']);
					$this->data['request_items'] = $this->model_account_request->getRequestItems($this->request->post, "data");
					$this->data['totals'] = $this->model_account_request->getTotalItems($this->data['request_id']);
					$total = $this->model_account_request->getRequestItems($this->request->post, "total");		
				} else if($this->request->post['task'] == "reuploadpayment") {
					$data = $this->request->post;
					$file_extension = $this->model_account_request->getFileExt($data['request_id']);
					$target_path = DOC_ROOT."requestimages/requestimages".$data['request_id'].".".strtolower($file_extension); 
					if (file_exists($target_path)) {
						unlink($target_path);
					}
					$this->model_account_request->clearUploadedFiles($data['request_id']);
					$this->data['request_id'] = $data['request_id'];
					$this->data['request_details'] = $this->model_account_request->getRequestDetails($this->data['request_id']);
					$this->data['request_items'] = $this->model_account_request->getRequestItems($this->request->post, "data");
					$this->data['totals'] = $this->model_account_request->getTotalItems($this->data['request_id']);
					$total = $this->model_account_request->getRequestItems($this->request->post, "total");		
				}
			} else {
				$this->data['requests'] = $this->model_account_request->getRequestList($this->request->post, "data");
				$total = $this->model_account_request->getRequestList($this->request->post, "total");					
			}
					
		} else {
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}	

			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;			
			
			$this->data['items'] = $this->model_account_request->getItems($this->request->get, "data");
			if(isset($this->request->get['request_id'])) {
				$this->data['request_id'] = $this->request->get['request_id'];
				$this->data['request_details'] = $this->model_account_request->getRequestDetails($this->data['request_id']);
				$this->data['request_items'] = $this->model_account_request->getRequestItems($this->request->get, "data");
				$this->data['totals'] = $this->model_account_request->getTotalItems($this->data['request_id']);
				$total = $this->model_account_request->getRequestItems($this->request->get, "total");			
			} else {		
				$this->data['requests'] = $this->model_account_request->getRequestList($this->request->get, "data");
				$total = $this->model_account_request->getRequestList($this->request->get, "total");	
			}
		}
		
		$this->data['total'] = $total;

		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/request', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->template = 'default/template/account/request.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function exporttocsv(){
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'account/request'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		$this->load->model('account/request');
		
		$this->data['requests'] = $this->model_account_request->getRequestListForCSV($this->request->post);
		
		$list = array("Request ID", "Branch From","Branch To","Created By","Date Requested","Approved By","Date Approved", "Status", "Requested Items", "Requested Item Cost", "Allocated Items", "Allocated Item Cost");
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=transfer_'.$this->user->nowDate().'_transfer_requests.csv'); 
		$fp = fopen('php://output', 'w');
		$this->user->exportToCSV($fp, $list, $this->data['requests']);
	}
	

	public function validate($data) {
		$ret = true;
		
		$this->data['err_msg'] = "Failed to add the item to request, please check below issues:<br/>";
		
		if($data['product_id_sel'] == "") {
			$ret = false;
			$this->data['err_msg'] .= "You should select a product.<br/>";
		}			
		
		return $ret;
	}	
	
}
?>