<?php
class ControllerAccountDeposit extends Controller {
	
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'deposit'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		$this->load->model('account/deposit');
		$url = '';
		$page = 0;
		$total = 0;
		$page_limit = 50;
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}	
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;
			$data = $this->request->post;
			if(isset($data['task'])) {
				if($data['task'] == "add") {
					if($this->validate($this->request->post)) {					
						$remittance_id =  $this->model_account_deposit->depositInScreen($data);
						
						if($remittance_id ==  0) {
							$this->data['err_msg'] = "You already submitted this fund endorsement.";
						} else {
							//upload pic start
							$type = "remittanceb";
							$source = "photo_remittance";
							
							if($type != "") {
								$target_path = DOC_ROOT.$type."/";
								$this->data['err_msg'] = "";
								$name = $_FILES[$source]['name'];
								$this->load->model('account/customers');
								
								if(!empty($name)) {
									$image_width = 0;
									$image_height = 0;
									if(!empty($_FILES[$source]["tmp_name"])) {
										$image_info = getimagesize($_FILES[$source]["tmp_name"]);
										$image_width = $image_info[0];
										$image_height = $image_info[1];
									}
									
									$this->data['err_msg'] = "";
									$file_extension = pathinfo(basename( $_FILES[$source]['name']), PATHINFO_EXTENSION);
									
									$target_path = $target_path.$type.$remittance_id.".".strtolower($file_extension); 
									
									if(strtolower($file_extension) == 'jpg' or strtolower($file_extension) == 'jpeg' or strtolower($file_extension) == 'png') {
									
										if(move_uploaded_file($_FILES[$source]['tmp_name'], $target_path)) {
											
											$this->data['err_msg'] .= " Successful upload of the proof of payment. Please wait for approval of Endorsement Id ".$remittance_id.".<br>";
											$this->data['err_msg'] .= " Please proceed in the <a class='btn btn-primary btn' href='pendingdeposit'>Pending Deposit Screen.</a>";
											
											$this->model_account_deposit->updateFileExtension($remittance_id, $file_extension);
											
										} else {
											$this->data['err_msg'] .= "Upload Result: Failed upload of file ".$_FILES[$source]['name'].".";
										}	
									
									} else {
										$this->model_account_deposit->cancelRemittance($remittance_id);
										$this->data['err_msg'] .= "Pictures should be have an extension of jpg only.";
									}
								} else {
									$this->model_account_deposit->cancelRemittance($remittance_id);
									$this->data['err_msg'] .= "Please select a picture to upload.";
								}			
							} else {
								$this->model_account_deposit->cancelRemittance($remittance_id);
							}						
							//upload pic end
						}
					} 
				} 
				
				if($data['task'] == "delete") {
					if (isset($data['selected'])) {
						foreach ($data['selected'] as $code_selected) {
							$this->model_account_deposit->deleteRemittance($code_selected);
						}
						$this->data['err_msg'] = "Successful Deletion of Cashier Remittance";
					}
				} 
				
				if($data['task'] == "approve") {
					if (isset($data['cashier_rem_id'])) {
						$this->data['err_msg'] = $this->model_account_deposit->approveRemittance($data);
					}
				} 
				
				if($data['task'] == "deny") {
					if (isset($data['cashier_rem_id'])) {
						$this->data['err_msg'] = $this->model_account_deposit->denyRemittance($data);
					}						
				} 
								
			}
			
		} else  {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}	
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
			//var_dump($data);
			if (isset($data['task'])) {
				if($data['task'] == "cancel") {
					if (isset($data['deposit_id'])) {
						$this->data['err_msg'] = $this->model_account_deposit->cancelRemittance($data['deposit_id']);
					}
				}
				
				if($data['task'] == "approve") {
					if (isset($data['deposit_id'])) {
						$this->data['err_msg'] = $this->model_account_deposit->approveRemittance($data);
					}
				} 
				
				if($data['task'] == "deny") {
					if (isset($data['deposit_id'])) {
						$this->data['err_msg'] = $this->model_account_deposit->denyRemittance($data);
					}						
				}
			}
		}
		
		$params = $this->assembleParams($data);
		$this->data['trans_session_id'] = $this->user->Random(20);
		
		//var_dump($data);
		
		$this->data['remittances'] = $this->model_account_deposit->getEndorsedFunds($data, 'data');
		$this->data['total'] = $this->model_account_deposit->getEndorsedFunds($data, 'count');
		$pagination = new Pagination();
		$pagination->total = $this->data['total'];
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = "deposit/{page}/search/0/".$params;
			
		$this->data['pagination'] = $pagination->render();
		
		$this->template = 'default/template/account/deposit.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	function assembleParams($data) {
		$params = "";
		
		$datefrom = "";
		if(isset($data['datefrom'])){
			if(!empty($data['datefrom'])){
				$datefrom = $data['datefrom'];
			} 
		}
		
		$dateto = "";
		if(isset($data['dateto'])){
			if(!empty($data['dateto'])){
				$dateto = $data['dateto'];
			} 
		}
		
		$deposit_slip_datetime = "";
		if(isset($data['deposit_slip_datetime'])){
			if(!empty($data['deposit_slip_datetime'])){
				$deposit_slip_datetime = $data['deposit_slip_datetime'];
			} 
		}
		
		$mode_of_remittance = "";
		if(isset($data['mode_of_remittance'])){
			if(!empty($data['mode_of_remittance'])){
				$mode_of_remittance = $data['mode_of_remittance'];
			} 
		}					  		
		
		$status_flag = "";
		if(isset($data['status_flag'])){
			if(!empty($data['status_flag'])){
				$status_flag = $data['status_flag'];
			} 
		}
		
		$receipt_counter = "";
		if(isset($data['receipt_counter'])){
			if(!empty($data['receipt_counter'])){
				$receipt_counter = $data['receipt_counter'];
			} 
		}
		
		$params .= $datefrom."/".$dateto."/".$deposit_slip_datetime."/".$mode_of_remittance."/".$status_flag."/".$receipt_counter;
		
		return $params;
	}	
	
	public function pendingdeposit() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'pendingdeposit'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		$this->load->model('account/deposit');
		$url = '';
		$page = 0;
		$total = 0;
		$page_limit = 100;
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}	
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;
			$data = $this->request->post;
			$data['status_flag'] = 1;

		} else  {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}	
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
			$data['status_flag'] = 1;
		}
		
		$this->data['remittances'] = $this->model_account_deposit->getEndorsedFunds($data, 'data');
		$this->data['total'] = $this->model_account_deposit->getEndorsedFunds($data, 'count');
		$this->data['trans_session_id'] = $this->user->Random(20);
		
		$pagination = new Pagination();
		$pagination->total = $this->data['total'];
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = "donation/{page}";
			
		$this->data['pagination'] = $pagination->render();		
		
		$this->template = 'default/template/account/pendingdeposit.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function view() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'donation'))) {
	  		$this->redirect("home");
    	}
		
		$this->load->model('account/deposit');
		
		$this->data['rem_header'] = $this->model_account_deposit->getEndorsedFundHeader($this->request->get['cashier_rem_id']);
		
		$this->template = 'default/template/account/deposit_det.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function validate($data) {
		$ret = true;
		
		$this->data['err_msg'] = "";
		
		$this->data['rem'] = $data;		
		if($data['total'] == "" or $data['total'] == "0") {
			$ret = false;
			$this->data['err_msg'] .= "<h4>Amount Deposited is mandatory. </h4><br>";			
		}		
		if($data['mode_of_remittance'] == "") {
			$ret = false;
			$this->data['err_msg'] .= "<h4>Mode of Remittance is mandatory. </h4><br>";			
		}
		if(isset($_FILES['photo_remittance']['name'])) {
			if($_FILES['photo_remittance']['name'] == "") {
				$ret = false;
				$this->data['err_msg'] .= "<h4>You need to upload the proof of payment. </h4><br>";			
			}
		} else {
			$ret = false;
			$this->data['err_msg'] .= "<h4>You need to upload the proof of payment. </h4><br>";				
		}		
		return $ret;
	}	
	public function export() {
	
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'account/deposit'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}	
		
		$this->load->model('account/deposit');
		$i_user_id = $this->user->getId();		
		$list = array("Id", "Group Trader Username", "Group Trader Name", "Area", "Amount Deposited", "Mode Of Remittance", "Date Submitted", "Status", "Approved By");
		$report = $this->model_account_deposit->getEndorsedFundsForExport($this->request->post, 'data');
		$total = $this->model_account_deposit->getTotalEndorsedFundsForExport($this->request->post, 'data');
		$total_list = array("Total",number_format($total, 2));
		
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=onecashtrading_'.$this->user->nowDate().'deposits.csv');		
		$fp = fopen('php://output', 'w');
		$this->user->exportToCSVWithTotal($fp, $list, $report, $total_list);
		
	}
	
	public function checkapproval(){
		
		$data = $this->request->get;
		
		$this->load->model('account/deposit');
		
		$result = $this->model_account_deposit->checkapproval($data);
		
		//$json['data'] = $result['data'];					
		$json['msg'] = $result['msg'];					
		$json['valid'] = sprintf($result['valid']);	
		$this->response->setOutput(json_encode($json));	
	}	
	
	public function updateDT(){
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'account/deposit'))) {
	  		$this->redirect($this->url->link('common/home', ''));
			//echo 'labas:'.$this->user->isLogged();
    	}
		
		$this->load->model('account/deposit');
		
		$this->data['rem_details'] = $this->model_account_deposit->updateDateTime($this->request->post);
		$this->data['rem_details'] = $this->model_account_deposit->getEndorsedFundDetails($this->request->get['cashier_rem_id']);
		$this->data['rem_header'] = $this->model_account_deposit->getEndorsedFundHeader($this->request->get['cashier_rem_id']);
		
		$this->template = 'default/template/account/deposit_det.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
	}	
	
}
?>