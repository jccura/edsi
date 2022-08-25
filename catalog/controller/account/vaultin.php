<?php
class ControllerAccountVaultin extends Controller {
	
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'vaultin'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		$this->load->model('account/vaultin');
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
				if($data['task'] == "add") {
					if($this->validate($this->request->post)) {					
						$this->data['err_msg'] = $this->model_account_vaultin->addVaultIn($data);
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
		$this->data['vaultins'] = $this->model_account_vaultin->getVaultIns($data, 'data');
		$this->data['total'] = $this->model_account_vaultin->getVaultIns($data, 'count');
		
		$this->data['epoints'] = $this->model_account_instawin->getUserEpoints();
		$this->data['vaultin_total'] = $this->model_account_vaultin->getTotalVaultIns();
		$this->data['earnings'] = $this->model_account_vaultin->getTotalEarnings();
		

		$pagination = new Pagination();
		$pagination->total = $this->data['total'];
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = "vaultin/{page}/search/0/".$params;
			
		$this->data['pagination'] = $pagination->render();

		
		$this->template = 'default/template/account/vaultin.tpl';
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
			$this->data['err_msg'] .= "Amount of E-coins is mandatory.<br>";			
		}	
	
		return $ret;
	}		
	
}
?>