<?php
class ControllerAccountTransfer extends Controller {
	
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'transfer'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		$this->load->model('account/transfer');
		$this->load->model('account/instawin');
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
				if($data['task'] == "transfer") {
					//var_dump($data);
					if($this->validate($data)) {						
						$this->data['err_msg'] = $this->model_account_transfer->transferEcoins($data);
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
			if (isset($data['task'])) {

			}
		}
		
		$params = $this->assembleParams($data);
		$this->data['trans_session_id'] = $this->user->Random(20);
		//var_dump($data);
		$this->data['transfers'] = $this->model_account_transfer->getTransferredEcoins($data, 'data');
		$this->data['total'] = $this->model_account_transfer->getTransferredEcoins($data, 'count');	

		$this->data['epoints'] = $this->model_account_instawin->getUserEpoints();
		$this->data['total_transfered'] = $this->model_account_transfer->getTotalTransfered();
		$this->data['total_received'] = $this->model_account_transfer->getTotalReceived();

		$pagination = new Pagination();
		$pagination->total = $this->data['total'];
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = "transfer/{page}/search/0/".$params;
			
		$this->data['pagination'] = $pagination->render();

		
		$this->template = 'default/template/account/transfer.tpl';
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
		if($data['user_id'] == "" or $data['user_id'] == "0") {
			$ret = false;
			$this->data['err_msg'] .= "User to Transfer is mandatory.<br>";			
		}	
	
		if($data['amount'] == "" or $data['amount'] == "0") {
			$ret = false;
			$this->data['err_msg'] .= "Amount of E-coins is mandatory.<br>";			
		}	
		
		return $ret;
	}
	
	public function getuserinfo() {

		$this->load->model('account/transfer');
		$user_details = $this->model_account_transfer->getUserDetails($this->request->get['username']);		
		$json['id_no'] = $user_details['id_no'];		
		$json['name'] = $user_details['name'];			
		$json['user_id'] = $user_details['user_id'];		
		$json['success'] = sprintf($user_details['success']);		
		$this->response->setOutput(json_encode($json));			
	
	}
	
}
?>