<?php   
class ControllerAccountMemberDashboard extends Controller {   
	
	public function index() {
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'memberdashboard'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}

		$url = '';
		$page = 0;
		$page_limit = 20;
		$total = 0;
		
		
		$this->load->model('account/memberdashboard');
		$this->load->model('account/common');
		$this->load->model('account/customers');
		$this->load->model('account/withdraw');
		
		
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
		
		if($this->user->getUserGroupId() == 39){
			$com_id = 22;
		} elseif($this->user->getUserGroupId() == 45) {
			$com_id = 23;
		} elseif($this->user->getUserGroupId() == 47) {
			$com_id = 24;
		} else {
			$com_id = 0;
		}
		
		$this->data['ewallet'] = $this->model_account_withdraw->getTotalEwallet();
		$this->data['withdrawal'] = $this->model_account_withdraw->getTotalWithdrawal();
		$this->data['total_earnings'] = $this->model_account_memberdashboard->getEwalletPerStatus($com_id);
		$this->data['total_personal_rebates'] = $this->model_account_memberdashboard->getEwalletPerStatus($com_id);
		$this->data['total_referrals'] = $this->model_account_memberdashboard->getTotalReferrals();
		$this->data['monthly_sales'] = $this->model_account_memberdashboard->getPersonalMonthlySales($com_id);
		$this->data['total_sales'] = $this->model_account_memberdashboard->getTotalPersonalSales($com_id);
		$this->data['total_group_sales'] = $this->model_account_memberdashboard->getGroupSales($com_id);
		$this->data['downline_admin_sales'] = $this->model_account_memberdashboard->getDownlineDistAdminSales($com_id);
		$this->data['total_pv'] = $this->model_account_memberdashboard->getTotalPv();
		$this->data['total_month1_pv'] = $this->model_account_memberdashboard->getLastMonthPv();
		$this->data['unilevel_income'] = $this->model_account_memberdashboard->getUnilevelIncome();
		$this->data['total_rank_bonus'] = $this->model_account_memberdashboard->getTotalRankBonus();

		$this->data['ewallets'] = $this->model_account_customers->getEwallets($data, "data");			
		$this->data['total_ewallets'] = $this->model_account_customers->getEwallets($data, "total");			

		$pagination = new Pagination();
		$pagination->total = $this->data['total_ewallets'];
		$pagination->page = $page;
		$pagination->limit = 50;
		$pagination->text = PAGINATION_TEXT;
		$pagination->url = $this->url->link('account/memberdashboard', $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();	
		
		$this->template = 'default/template/account/memberdashboard.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
}
?>