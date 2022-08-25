<?php   
class ControllerAccountBrgyMaint extends Controller {   
	
	public function index() {
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'brgymaint'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		
		$this->load->model('account/brgymaint');
		$this->load->model('account/common');

		$url = '';
		$page = 0;
		$page_limit = 40;
		$total = 0;
		

		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}	

			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;
			$data = $this->request->post;
			
			if(isset($data['task'])) {
				if($this->request->post['task'] == "assign") {
					$this->data['err_msg'] = $this->model_account_brgymaint->assignToBrgy($data);
				}

				if($this->request->post['task'] == "unassign") {
					$this->data['err_msg'] = $this->model_account_brgymaint->unAssignBrgy($data);
				}
			}
			
		} else {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
	
			$data = $this->request->get;	
			
		}
		
		$this->data['branches'] = $this->model_account_brgymaint->getBranches();
		$this->data['citydist'] = $this->model_account_brgymaint->getCityDistributor();
		$this->data['wholesaler'] = $this->model_account_brgymaint->getWholeSaler();
		$this->data['users'] = $this->model_account_brgymaint->getBranchUsers();
		$this->data['pagedata'] = $data;
		
		if(isset($this->data['pagedata']['checkout_city'])) {
			if(!empty($this->data['pagedata']['checkout_barangay'])) {
				if($this->data['pagedata']['checkout_city'] != "all") {
					$this->data['pagedata']['city_desc'] = $this->model_account_brgymaint->getCityDesc($this->data['pagedata']['checkout_city']);
				} else {
					$this->data['pagedata']['city_desc'] = "Select Province First";
				}
			} else {
				$this->data['pagedata']['city_desc'] = "Select City First";
			}
		}
		
		if(isset($this->data['pagedata']['checkout_barangay'])) {
			if(!empty($this->data['pagedata']['checkout_barangay'])) {
				if($this->data['pagedata']['checkout_barangay'] != "all") {
					$this->data['pagedata']['brgy_desc'] = $this->model_account_brgymaint->getBrgyDesc($this->data['pagedata']['checkout_barangay']);
				} else {
					$this->data['pagedata']['brgy_desc'] = "Select City First";
				}
			} else {
				$this->data['pagedata']['brgy_desc'] = "Select City First";
			}
		}
		
		$params = $this->assembleParams($data);
		
		$this->data['brgylist'] = $this->model_account_brgymaint->getBrgyList($data, "data");
		$total = $this->model_account_brgymaint->getBrgyList($data,"total");
		
		$this->data['branches'] = $this->model_account_brgymaint->getBranches();
		$this->data['citydist'] = $this->model_account_brgymaint->getCityDistributor();
		$this->data['wholesaler'] = $this->model_account_brgymaint->getWholeSaler();
		$this->data['users'] = $this->model_account_brgymaint->getBranchUsers();
		$this->data['provinces'] = $this->model_account_brgymaint->getProvince();
		$this->data['action'] = $this->url->link('account/brgymaint');
		
	
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = "brgymaint/{page}/".$params;
			
		$this->data['pagination'] = $pagination->render();	
		
		$this->template = 'default/template/account/brgymaint.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	function assembleParams($data) {
		$params = "";
		
		if(isset($data['checkout_provinces'])) {
			if(!empty($data['checkout_provinces'])) {
				$params .= $data['checkout_provinces']."/";
			} else {
				$params .= "all/";
			}
		}
		
		
		if(isset($data['checkout_city'])) {
			if(!empty($data['checkout_city'])) {
				$params .= $data['checkout_city']."/";
			} else {
				$params .= "all/";
			}
		}
		
		if(isset($data['checkout_barangay'])) {
			if(!empty($data['checkout_barangay'])) {
				$params .= $data['checkout_barangay']."/";
			} else {
				$params .= "all/";
			}
		}
		
		
		if(isset($data['city_dist'])) {
			if(!empty($data['city_dist'])) {
				$params .= $data['city_dist']."/";
			} else {
				$params .= "all/";
			}
		}
		
		if(isset($data['within_city'])) {
			if(!empty($data['within_city'])) {
				$params .= $data['within_city']."/";
			} else {
				$params .= "all/";
			}
		}
		//echo $params;
		return $params;
	}
	
	public function getCities(){
  		$this->load->model('account/common');
		$json = array();
		$json['cities'] = $this->model_account_common->getCitiesNewCustomer($this->request->get);
		$json['status'] = "success";
		echo json_encode($json);
  	}
	
	public function getBarangay(){
  		$this->load->model('account/common');
		$json = array();
		$json['brgy'] = $this->model_account_common->getBrgyNewCustomer($this->request->get);
		$json['status'] = "success";
		echo json_encode($json);
  	}
}
?>