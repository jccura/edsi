<?php   
class ControllerAccountBranchMaintainance extends Controller {   
	
	public function index() {
		
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'branchmaintainance'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		
		$this->load->model('account/branchmaintainance');

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
					$this->data['err_msg'] = $this->model_account_branchmaintainance->assignBranch($this->request->post);
				}else if($this->request->post['task'] == "addBranch"){
					$this->data['err_msg'] = $this->model_account_branchmaintainance->addBranch($this->request->post);
				}else if($this->request->post['task'] == "addDesignatedUser"){
					$this->data['err_msg'] = $this->model_account_branchmaintainance->addDesignatedUser($this->request->post);
				}else if($this->request->post['task'] == "updateBranchCoordinates"){
					$this->data['err_msg'] = $this->model_account_branchmaintainance->updateBranchCoordinates($this->request->post);
				}else if($this->request->post['task'] == "updateBranchName"){
					$this->data['err_msg'] = $this->model_account_branchmaintainance->updateBranchName($this->request->post);
				}
			}
			
			if(isset($this->request->post['new_province'])){
				$this->data['new_province'] = $this->request->post['new_province'];
				$this->data['citi'] = $this->model_account_branchmaintainance->getCities($this->request->post['new_province']);
			}
			
			if(isset($this->request->post['city_town'])){
				$this->data['city_town'] = $this->request->post['city_town'];
				$this->data['brgy'] = $this->model_account_branchmaintainance->getBrgy($this->request->post['city_town']);
			}
			
			$this->data['branch'] = $this->request->post['branch'];
			$this->data['branches'] = $this->model_account_branchmaintainance->getBranches();
			$this->data['users'] = $this->model_account_branchmaintainance->getBranchUsers();
			$params = $this->assembleParams($this->request->post);
			
		} else {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
	
			$data = $this->request->get;	
			
			$this->data['branches'] = $this->model_account_branchmaintainance->getBranches();
			$this->data['users'] = $this->model_account_branchmaintainance->getBranchUsers();
			
			$params = $this->assembleParams($this->request->get);
			
		}
		
		$this->data['new_province'] = 0;
		
		if(isset($data['new_province'])) {
			if(!empty($data['new_province'])) {
				$this->data['province_id'] = $data['new_province'];
			}
		}
		
		$this->data['new_province'] = 0;
		
		if(isset($data['new_province'])) {
			if(!empty($data['new_province'])) {
				$this->data['province_id'] = $data['new_province'];
			}
		}
		
		$this->data['city_town'] = 0;
		
		if(isset($data['city_town'])) {
			if(!empty($data['city_town'])) {
				$this->data['city_town'] = $data['city_town'];
			}
		}
		
		$this->data['baranggay'] = 0;
		
		if(isset($data['baranggay'])) {
			if(!empty($data['baranggay'])) {
				$this->data['baranggay'] = $data['baranggay'];
			}
		}
		
		$this->data['branch'] = "";
		
		if(isset($data['branch'])) {
			if(!empty($data['branch'])) {
				$this->data['branch'] = $data['branch'];
			}
		}
		
		$this->data['cities'] = $this->model_account_branchmaintainance->getCityMunicipality($data, "data");
		$total = $this->model_account_branchmaintainance->getCityMunicipality($data,"total");
		$this->data['branches'] = $this->model_account_branchmaintainance->getBranches();
		$this->data['users'] = $this->model_account_branchmaintainance->getBranchUsers();
		$this->data['provinces'] = $this->model_account_branchmaintainance->getProvince();
		$this->data['action'] = $this->url->link('account/branchmaintainance');
		
	
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/branchmaintainance', $url . $params .'&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();	
		
		$this->template = 'default/template/account/branchmaintainance.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
	function assembleParams($data) {
		$params = "";
		
		
		if(isset($data['new_province'])) {
			if(!empty($data['new_province'])) {
				$params .= "&new_province=".$data['new_province'];
			}
		}
		
		
		if(isset($data['city_town'])) {
			if(!empty($data['city_town'])) {
				$params .= "&city_town=".$data['city_town'];
			}
		}
		
		
		if(isset($data['baranggay'])) {
			if(!empty($data['baranggay'])) {
				$params .= "&baranggay=".$data['baranggay'];
			}
		}
		
		
		if(isset($data['branch'])) {
			if(!empty($data['branch'])) {
				$params .= "&branch=".$data['branch'];
			}
		}
		
		 return $params;
	}
}
?>