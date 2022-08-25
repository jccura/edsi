<?php   
class ControllerAccountSalesEncReport extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'salesencreport'))) {
	  		$this->redirect("home");
    	}
		
		$this->load->model('account/salesencreport');

		$url = '';
		$page = 0;
		$page_limit = 20;
			

		if (($this->request->server['REQUEST_METHOD'] == 'GET')) {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
		} else {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;
			$data = $this->request->post;			
		}
		
		$this->data['salesencodedreport'] = $this->model_account_salesencreport->getSalesEncodedRep($data, "data");
		$this->data['summary'] = $this->model_account_salesencreport->getSalesEncodedRep($data, "summary");		
		$total = $this->data['total'] = $this->model_account_salesencreport->getSalesEncodedRep($data, "total");
			
		$pagination = new Pagination();
		$pagination->total = $this->data['total'];
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/salesencreport', $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();	
		
		$this->template = 'default/template/account/salesencreport.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}

	public function exporttocsv(){
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'salesencreport'))) {
	  		$this->redirect('home');
    	}
				
		$this->load->model('account/salesencreport');
		
		if (($this->request->server['REQUEST_METHOD'] == 'GET')) {
			$data = $this->request->get;
		} else {
			$data = $this->request->post;			
		}
		
		$list = array("Sales Inventory ID",
					"Username",
					"Order ID",
					"Order Detail ID",
					"Request ID",
					"Product",
					"SRP",
					"Distributor Price",
					"Reseller Price",
					"Direct Referral",
					"Shipping",
					"Service Fee",
					"System Fee/ IT",
					"Top Up",
					"CV",
					"Tool",
					"Tax",
					"Cost",
					"Commissions",
					"Income",
					"Date Added");
		
		$export_data = $this->model_account_salesencreport->getSalesEncodedRep($data, "data");
		$summary = $this->model_account_salesencreport->getSalesEncodedRep($data, "summary");		
		$totals = array("",
					"",
					"",
					"",
					"",
					"Totals",
					number_format($summary['srp'], 2),
					number_format($summary['distributor_price'], 2),
					number_format($summary['reseller_price'], 2),
					number_format($summary['direct_referral']),
					number_format($summary['shipping'], 2),
					number_format($summary['service_fee'], 2),
					number_format($summary['system_fee'], 2),
					number_format($summary['topup'], 2),
					number_format($summary['cv'], 2),
					number_format($summary['tools'], 2),
					number_format($summary['tax'], 2),
					number_format($summary['cost'], 2),
					number_format($summary['commissions'], 2),
					number_format($summary['income'], 2),
					"");
		
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=sales_encoded_report'.$this->user->nowDate().'.csv');  
		$fp = fopen('php://output', 'w');

		$this->user->exportToCSVWithTotalOnTop($fp, $list, $export_data, $totals);
		
	}	
	
}
?>