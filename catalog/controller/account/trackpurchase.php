<?php   
class ControllerAccountTrackPurchase extends Controller {   
	public function index() {

		$url = '';
		$this->data['action'] = $this->url->link('account/purhcase');
		$this->load->model('account/purchase');

				if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
					if(isset($this->request->post['reference'])){
						$check = $this->model_account_purchase->checkPurchaseRef($this->request->post['reference']);

						if($check == 1){
							$task = $this->request->post['task'];

							if($task == "upload") {
								//echo "add";
								
								//upload pic start
								$type = "purchaseimages";
								$source = "photo_remittance";
								
								if($type != "") {
									$target_path = DOC_ROOT.$type."/";
									$this->data['err_msg'] = "";
									$name = $_FILES[$source]['name'];
		
									if(!empty($name)) {

										$image_info = getimagesize($_FILES[$source]["tmp_name"]);
										$image_width = $image_info[0];
										$image_height = $image_info[1];
									
										$this->data['err_msg'] = "";
										$file_extension = pathinfo(basename( $_FILES[$source]['name']), PATHINFO_EXTENSION);

										$purchase_id = $this->model_account_purchase->getPurchaseId($this->request->post['reference']);

										$target_path = $target_path.$type.$purchase_id.".".strtolower($file_extension); 
										
										if(strtolower($file_extension) == 'jpg' or strtolower($file_extension) == 'jpeg') {
											if(move_uploaded_file($_FILES[$source]['tmp_name'], $target_path)) {
												$this->data['err_msg'] .= " Successful upload of file ".$_FILES[$source]['name'].". Please wait for approval of Purchase Id ".$purchase_id.".<br>";	
												$this->model_account_purchase->updateStatus(71, $this->request->post['reference']);
												$this->model_account_purchase->updateExtension(strtolower($file_extension), $this->request->post['reference']);
												$this->data['purchase_header'] = $this->model_account_purchase->getPurchaseRefDetails($this->request->post['reference']);
												$this->data['purchase_details'] = $this->model_account_purchase->getPurchaseItems($this->data['purchase_header'],"data");
												$this->data['remarks'] = $this->model_account_purchase->getRemarks($this->request->post['reference']);
											} else {
												$this->data['err_msg'] .= "Upload Result: Failed upload of file ".$_FILES[$source]['name'].".";
											}	
										
										} else {
											$this->data['err_msg'] .= "Pictures should be have an extension of jpg only.";
										}

									} else {
										$this->data['err_msg'] .= "Please select a picture to upload.";
									}			
								}
							
							}else if($task == "remarks"){
								$this->model_account_purchase->addRemark($this->request->post);
								$this->data['purchase_header'] = $this->model_account_purchase->getPurchaseRefDetails($this->request->post['reference']);
								$this->data['purchase_details'] = $this->model_account_purchase->getPurchaseItems($this->data['purchase_header'],"data");
								$this->data['remarks'] = $this->model_account_purchase->getRemarks($this->request->post['reference']);
							}

						}else{
								//$this->redirect($this->url->link('common/home', ''));
							echo "string";
						}

						}else{
							//$this->redirect($this->url->link('common/home', ''));
							echo "string1";
						}

				}else{

					if(isset($this->request->get['ref'])){
						$check = $this->model_account_purchase->checkPurchaseRef($this->request->get['ref']);
						if($check == 1){
							$this->data['purchase_header'] = $this->model_account_purchase->getPurchaseRefDetails($this->request->get['ref']);
							$this->data['supplier'] = $this->model_account_purchase->getSupplier($this->request->get['ref']);
							$this->data['purchase_details'] = $this->model_account_purchase->getPurchaseItems($this->data['purchase_header'],"data");
							//$this->data['remarks'] = $this->model_account_purchase->getRemarks($this->request->get['ref']);
						}
					
					}
				}

		$this->template = 'default/template/account/trackpurchase.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
		$this->response->setOutput($this->render());
  	}
}
?>