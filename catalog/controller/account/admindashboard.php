<?php   
class ControllerAccountAdmindashboard extends Controller {  

	public function index() {

		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'admindashboard'))) {
	  		$this->redirect("home");
    	}
		//sample
		$this->load->model('account/admindashboard');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$data = $this->request->post;
			$params = $this->assembleParams($this->request->post);
		} else {
			$data = $this->request->get;
			$params = $this->assembleParams($this->request->get);
		}

		$this->data['played_ecoins'] = $this->model_account_admindashboard->getInstawinPlayedEcoins();		
		$this->data['vaultin_total'] = $this->model_account_admindashboard->getVaultinEcoins();		
		$this->data['transfer_total'] = $this->model_account_admindashboard->getTransferredEcoins();		
		$this->data['overall_ecoins'] = $this->model_account_admindashboard->getTotalCreatedEcoins();	
		$this->data['instawin_winnings'] = $this->model_account_admindashboard->getInstawinWinnings();
		$this->data['vaultin_earnings'] = $this->model_account_admindashboard->getVaultinEarnings();
		


		$this->template = 'default/template/account/admindashboard.tpl';

		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);

		$this->response->setOutput($this->render());
  	}

	

	function assembleParams($data) {

		$params = "";
		if(isset($data['datefrom'])){
			if(!empty($data['datefrom'])){
				$params .= "&datefrom=".$data['datefrom'];
			}
		}

		if(isset($data['dateto'])){
			if(!empty($data['dateto'])){
				$params .= "&dateto=".$data['dateto'];
			}
		}

		if(isset($data['commission_type_id'])){
			if(!empty($data['commission_type_id'])){
				$params .= "&commission_type_id=".$data['commission_type_id'];
			}
		}
		
		return $params;

	}

}
?>