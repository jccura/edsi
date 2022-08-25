<?php
class ControllerAccountWithdraw extends Controller {
	
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'withdraw'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}

		$this->load->model('account/withdraw');
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
				//var_dump($data);
				if($data['task'] == "requestwithdraw") {
					$this->data['err_msg'] = $this->model_account_withdraw->requestWithdrawal($data);
				} 	

				if($data['task'] == "release") {				
					$this->data['err_msg'] = $this->model_account_withdraw->releaseWithdrawal($data);
				}
			}
			
		} else  {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}	
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
		}
		
		$params = $this->assembleParams($data);
		$this->data['trans_session_id'] = $this->user->Random("", "", 20);
		$this->data['withdrawals'] = $this->model_account_withdraw->getWithdrawals($data, 'data');
		$this->data['total'] = $this->model_account_withdraw->getWithdrawals($data, 'count');
		
		$this->data['ewallet'] = $this->model_account_withdraw->getTotalEwallet();
		$this->data['withdrawal'] = $this->model_account_withdraw->getTotalWithdrawal();
		$this->data['banks'] = $this->model_account_withdraw->getBanksList();
		$this->data['details'] = $this->model_account_withdraw->getUserAccount();
		$this->data['bank_fee'] = $this->model_account_withdraw->getBankFees('all');
		$this->data['current_bank_fee'] = $this->model_account_withdraw->getBankFees('actual');
		

		$pagination = new Pagination();
		$pagination->total = $this->data['total'];
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = "vaultin/{page}/search/0/".$params;
			
		$this->data['pagination'] = $pagination->render();

		
		$this->template = 'default/template/account/withdraw.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	public function approval() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'withdrawapp'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}

		$this->load->model('account/withdraw');
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
				//var_dump($data);
				if($data['task'] == "add") {
					if($this->validate($this->request->post)) {					
						$this->data['err_msg'] = $this->model_account_withdraw->requestWithdrawal($data);
					} 
				} 	

				if($data['task'] == "release") {				
					$this->data['err_msg'] = $this->model_account_withdraw->releaseWithdrawal($data);
				}
			}
			
		} else  {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}	
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
			if (isset($data['task'])) {
				if($data['task'] == "receive") {				
					$this->data['err_msg'] = $this->model_account_withdraw->receiveWithdrawal($data);
				}
			}
		}
		
		$params = $this->assembleParams($data);
		$this->data['trans_session_id'] = $this->user->Random("", "", 20);
		$this->data['withdrawals'] = $this->model_account_withdraw->getWithdrawalsApp($data, 'data');
		$this->data['total'] = $this->model_account_withdraw->getWithdrawalsApp($data, 'count');
		
		$this->data['ewallet'] = $this->model_account_withdraw->getTotalEwallet();
		$this->data['withdrawal'] = $this->model_account_withdraw->getTotalWithdrawal();
		

		$pagination = new Pagination();
		$pagination->total = $this->data['total'];
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = "vaultin/{page}/search/0/".$params;
			
		$this->data['pagination'] = $pagination->render();

		
		$this->template = 'default/template/account/withdrawapproval.tpl';
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
		
		
		$params .= $datefrom."/".$dateto;
		
		return $params;
	}	
	
	public function validate($data) {
		$ret = true;
		
		$this->data['err_msg'] = "";
		
		$this->data['rem'] = $data;		
		if($data['amount'] == "" or $data['amount'] == "0") {
			$ret = false;
			$this->data['err_msg'] .= "Amount is mandatory.<br>";			
		}
	
		return $ret;
	}	

	public function getbankerinfo() {

		$this->load->model('account/withdraw');
		$user_details = $this->model_account_withdraw->getBankerDetails($this->request->get['username']);		
		$json['id_no'] = $user_details['id_no'];		
		$json['email'] = $user_details['email'];		
		$json['contact'] = $user_details['contact'];		
		$json['name'] = $user_details['name'];			
		$json['user_id'] = $user_details['user_id'];		
		$json['success'] = sprintf($user_details['success']);		
		$this->response->setOutput(json_encode($json));			
	
	}
	
	public function export(){
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'withdrawapp'))) {
	  		$this->redirect('home');
    	}
				
		$this->load->model('account/withdraw');	

		$list = array("Withdraw Id",
					"Amount Requested",
					"Transaction Fee",
					"Total Amount",
					"Status",
					"Requestor",
					"Type",
					"Account Name",
					"Account Number",
					"Date Submitted");
					
		$export_data = $this->model_account_withdraw->getWithdrawalsForExport($this->request->post);
		
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=withdraw_report_'.$this->user->nowDate().'.csv');  
		$fp = fopen('php://output', 'w');

		$this->user->exportToCSV($fp, $list, $export_data);
		
	}
	
	public function validateRequest($data){
		$ret = false;
		$this->data['err_msg'] = "";
		
		$this->data['err_msg'] .= $this->checkFields($data,'amount');

		if($data['amount'] < 500){
			$this->data['err_msg'] .= "Requested amount is below minimum.";
		}
		
		if($this->data['err_msg'] === ""){
			$ret = true;
		}
		
		return $ret;
	}
	
	public function checkFields($dataObj,$dataProperty){
		if($dataObj[$dataProperty] == "") {
			return $dataProperty." is mandatory \n";
		}
		return "";
	}
}
?>