<?php
class ControllerAccountWallet extends Controller {
	
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'wallet'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		$this->load->model('account/wallet');
		$url = '';
		$page = 0;
		$total = 0;
		$page_limit = 20;
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}	
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;
			$data = $this->request->post;			
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
		//var_dump($data);
		$this->data['wallets'] = $this->model_account_wallet->getWallets($data, 'data');
		$this->data['total'] = $this->model_account_wallet->getWallets($data, 'count');
		
		$this->data['current_ewallet'] = $this->model_account_wallet->getCurrentEwallet();
		$this->data['total_coms'] = $this->model_account_wallet->getTotalEwallet();
		$this->data['ord_coms'] = $this->model_account_wallet->getOrderCommissions();
		$this->data['referrals'] = $this->model_account_wallet->getReferrals();

		$pagination = new Pagination();
		$pagination->total = $this->data['total'];
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = "wallet/{page}/search/0/".$params;
			
		$this->data['pagination'] = $pagination->render();

		
		$this->template = 'default/template/account/wallet.tpl';
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
	
	public function pendingdeposit() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'pendingdeposit'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		$this->load->model('account/deposit');
		$url = '';
		$page = 0;
		$total = 0;
		$page_limit = 20;
		
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
		if($data['amount'] == "" or $data['amount'] == "0") {
			$ret = false;
			$this->data['err_msg'] .= "Amount of E-coins is mandatory.<br>";			
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