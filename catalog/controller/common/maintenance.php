<?php
class ControllerCommonMaintenance extends Controller {
    public function index() {
        $this->load->model('account/common');		
		$maintenance = $this->config->get('config_maintenance');
		if (isset($this->request->get['maint'])) {
			$this->request->get['maintenance'] = $this->request->get['maint'];
			$maintenance = $this->model_account_common->toggleMaintenance($this->request->get);		
		}				        
	
		if (isset($this->request->get['wdw'])) {
			$this->request->get['withdrawal'] = $this->request->get['wdw'];
			$maintenance = $this->model_account_common->toggleMaintenance($this->request->get);		
		}	
	
		if (isset($this->request->get['withdrawal'])) {
			$this->model_account_common->toggleWithdrawal($this->request->get);		
		}
		
		if (isset($this->request->get['enablePromo'])) {
			$this->model_account_common->togglePromo($this->request->get);		
		}
	
		if ($maintenance) {			
			return $this->forward('common/maintenance/info');					        
		} 
    }
		
    public function info() {
        $this->data['message'] = $this->config->get('config_message');
		$this->template = 'default/template/common/maintenance.tpl';
		$this->response->setOutput($this->render());
    }
}
?>