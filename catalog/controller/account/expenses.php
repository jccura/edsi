<?php   
class ControllerAccountExpenses extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'expenses'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}	
		
		$this->load->model('account/expenses');
		$page = 0; 
		$page_limit = 100; 
		$url = "";
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$data = $this->request->post;
			
			if(isset($data['task'])) {
				
				if($data['task'] == 'add') {
									
					$result = $this->model_account_expenses->addExpense($data);
					$this->data['err_msg'] = $result['err_msg'];
					$expenses_id = $result['expenses_id'];
									
					if($expenses_id != 0) {
						$this->data['err_msg'] = "";
							
						for($i=1;$i<=10;$i++) {
							if(isset($_FILES['proof_img'.$i]['name'])) {
								$file_extension = pathinfo(basename( $_FILES['proof_img'.$i]['name']), PATHINFO_EXTENSION);
								
								if(strtolower($file_extension) == 'jpg' || strtolower($file_extension) == 'jpeg' || strtolower($file_extension) == 'png') {	
									$target_path = DOC_ROOT."image/expenseimages/"."proof_img".$expenses_id."_".$i.".".strtolower($file_extension);
									
									if (file_exists($target_path)) {
										unlink($target_path);
									}
									
									if(move_uploaded_file($_FILES['proof_img'.$i]['tmp_name'], $target_path)) {
										$this->model_account_expenses->uploadExtension($expenses_id, $file_extension,$i);
										$this->data['err_msg'] .= " Proof image ".$i." is successfully uploaded.<br>" ;
									}else {
										$this->data['err_msg'] .= "Upload Result: Failed upload of file ".$_FILES['proof_img'.$i]['name'].".<br>";
									}							
								} else {
									$this->data['err_msg'] .= "No proof image ".$i.".<br>";
								}							
							}
						}					
					}				
				}
				
				if($data['task'] == 'delete') {
					$result = $this->model_account_expenses->deleteExpense($data);
					$this->data['err_msg'] = $result['err_msg'];
				}
				
			}
		} else {	
			$data = $this->request->get;			
		}

		if(!isset($data['task'])) {
			$data['task'] = "";
		}
		
			if($data['task'] == "view") {
				$this->data['view_images'] = $this->model_account_expenses->getExpenseImages($data['expenses_id']);
				$this->template = 'default/template/account/expenseview.tpl';
			} else {
				$this->data['exp_type'] = $this->model_account_expenses->getExpensesType();
				$this->data['expenseshistory'] = $this->model_account_expenses->getExpensesDetails($data, "data");
				$this->data['total'] = $this->model_account_expenses->getExpensesDetails($data, "total");
			
				$pagination = new Pagination();
				$pagination->total = $this->data['total'];
				$pagination->page = $page;
				$pagination->limit = $page_limit;
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->link('account/expenses', $url . '&page={page}', 'SSL');		
				$this->data['pagination'] = $pagination->render();
				
				$this->template = 'default/template/account/expenses.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/footer',
				'common/header'	
			);	
			$this->response->setOutput($this->render());
	}
	
	public function getExpensesImageDetails(){
		
		$this->load->model('account/expenses');
		$json = array();		
		$view_images = $this->model_account_expenses->getExpenseImages($this->request->get);

		$json['expenses_images'] = "<div class='row'>";
		for($i=1;$i<=10;$i++) {
			if(isset($view_images['extension_'.$i])) {
				$json['expenses_images'] .= "<div class='col-md-6'>";
				$json['expenses_images'] .=	"<label>Image ".$i.":</label>";
				$json['expenses_images'] .=	"<img width='100%' height='100%' src='image/expenseimages/proof_img".$view_images['expenses_id']."_".$i.".".$view_images['extension_'.$i]." 'class='img-fluid' />";
				$json['expenses_images'] .=	"<hr/>";
				$json['expenses_images'] .=	"</div>";
			}
		}
		$json['expenses_images'] .= "</div>";
		
		$json['status'] = "Success";
		
		echo json_encode($json); 
	
	}
	
	public function exporttocsv(){
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'expenses'))) {
	  		$this->redirect('home');
    	}
				
		$this->load->model('account/expenses');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$data = $this->request->post;
		} else {
			$data = $this->request->get;
		}

		$list = array("EXPENSES ID",
					"CREATED BY",
					"EXPENSE TYPE",
					"NAME",
					"AMOUNT",
					"REMARKS",
					"DATE CREATED");
					
		$export_data = $this->model_account_expenses->getExpenseToExport($data);
		
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=expenses_data_'.$this->user->nowDate().'.csv');  
		$fp = fopen('php://output', 'w');

		$this->user->exportToCSV($fp, $list, $export_data);
		
	}	
}
?>