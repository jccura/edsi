	<?php  
class ControllerAccountLeaderBoard extends Controller {
	public function index() {
	if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'leaderboard'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		
		$this->load->model('account/leaderboard');
		$page = 0; 
		$page_limit = 10; 
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
		
		// var_dump($data);
		$this->data['topdistributor'] = $this->model_account_leaderboard->getTopDistributor($data);
		$this->data['topareqoperator'] = $this->model_account_leaderboard->getTopAreaOperator($data);
		$this->data['topseller'] = $this->model_account_leaderboard->getTopSeller($data);
		$this->data['toprequestor'] = $this->model_account_leaderboard->getTopRequestor($data);
		$this->data['data'] = $data;
		// $this->data['month'] = date('F', strtotime($data['month_leaderboard']);
		// echo $this->user->nowym();
			
		$pagination = new Pagination();
		// $pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = 'leaderboard/{page}';		
		$this->data['pagination'] = $pagination->render();	
		$this->template = 'default/template/account/leaderboard.tpl';		
		
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'
		);
		
										
		$this->response->setOutput($this->render());
	}
	
	// public function exporttocsv(){
		
		// if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'leaderboard'))) {
	  		// $this->redirect('home');
    	// }
				
		// $this->load->model('account/leaderboard');
		
		// if($this->request->server['REQUEST_METHOD'] == 'POST') {
			// $data = $this->request->post;
		// } else {
			// $data = $this->request->get;
		// }

		// $list = array("USER ID",
					// "SPONSOR DOWNLINE",
					// "DOWNLINE USERNAME",
					// "DOWNLINE NAME",
					// "LEVEL",
					// "DATE ENCODED",
					// "ACTIVE");
					
		// $export_data = $this->model_account_customers->getNetworkExport($data);
		
		// header('Content-type: application/csv');
		// header('Content-Disposition: attachment; filename=network_downline_'.$this->user->nowDate().'.csv');  
		// $fp = fopen('php://output', 'w');

		// $this->user->exportToCSV($fp, $list, $export_data);
		
	// }	
}
?>