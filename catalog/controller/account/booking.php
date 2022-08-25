<?php   
class ControllerAccountBooking extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'booking'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}

		$this->load->model('account/booking') ;
		$this->load->model('api/sendbooking') ;
		$url = '';
		$page = 0;
		$page_limit = 25;		
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}
				$this->request->post['start'] = ($page - 1) * $page_limit;
				$this->request->post['limit'] = $page_limit;
				$data = $this->request->post;	
					
				if(isset($data['task'])){
					if($data['task'] == 'deliverNow'){
						$this->data['err_msg'] = $this->model_account_booking->addDeliveries($data);
					}
					
					if($data['task'] == 'createQD'){
						$this->data['err_msg'] = $this->model_account_booking->createQD($data);
						$this->redirect('booking');
					}
					
					if($data['task'] == 'removeLocationSubmit'){
						$this->data['err_msg'] = $this->model_account_booking->removeLocationSubmit($data);
					}

					if($data['task'] == 'view'){
						if(isset($data['visibility'])){
							$result = $this->model_account_booking->hideOrShowQuickDelivery($data);
							
							if($result != 'QD Information successfully loaded.'){
								$this->data['err_msg'] = 'Quick Delivery not available someone is viewing this Quick Delivery.';
								$_SESSION['err_msg'] = $this->data['err_msg'];
								$this->redirect('../quickdelpending');
							}
						}
						$this->data['referror'] = $data['referror'];
					}
					
					if($data['task'] == 'submitFromAffiliatedMerchant'){
						$return_array = $this->model_account_booking->addLocationSubmitWithMarkers($data);
						$this->data['err_msg'] = $return_array['qd_location_msg'];
						$this->data['qd_location_id'] = $return_array['qd_location_id'];
						//$data['current_qd_id'] = $return_array['current_qd_id'];
					}
					
					if($data['task'] == 'acceptBooking'){
						$this->data['err_msg'] = $this->model_account_booking->acceptBooking($data);
						if($this->data['err_msg'] == 'Successfully claimed quick delivery!'){
							$this->redirect('quickdelongoing');
						} else {
							$_SESSION['err_msg'] = $this->data['err_msg'];
							$this->redirect('viewbooking/'.$data['qd_id']);
						}
					}

					if($data['task'] == 'cancelledByCustomer'){
						$this->data['err_msg'] = $this->model_account_booking->cancelledByCustomer($data);
					}

					if($data['task'] == 'imHere'){
						$this->data['err_msg'] = $this->model_account_booking->imHere($data);
					}

					if($data['task'] == 'itemPickedUp'){
						$this->data['err_msg'] = $this->model_account_booking->itemPickedUp($data);
					}

					if($data['task'] == 'delivered'){
						$this->data['err_msg'] = $this->model_account_booking->delivered($data);
					}
					
					if($data['task'] == 'createQDS'){
						$this->data['err_msg'] = $this->model_account_booking->createQDS($data);
						$this->redirect('booking');
					}
					
					if($data['task'] == 'createQDM'){
						$this->data['err_msg'] = $this->model_account_booking->createQDM($data);
						$this->redirect('booking');
					}
					
					if($data['task'] == 'bookDelivery'){
						$result = $this->model_account_booking->bookDelivery($data);
						$this->data['err_msg'] = $result['msg'];
						echo $result['msg'];
						
						if ($result['success'] > 0){
							$this->redirect('quickdelpending');
						}
					}

					if($data['task'] == 'cancelBooking'){
						$this->data['err_msg'] = $this->model_account_booking->cancelBooking($data);
						$this->redirect('booking');
					}
					
					if($data['task'] == 'saveDeliveryFee'){
						$this->data['err_msg'] = $this->model_account_booking->saveDeliveryFee($data);
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
		
		$currentBooking['current_qd_id'] = isset($data['qd_id']) ? $data['qd_id'] : $this->model_account_booking->getCurrentBooking()['current_qd_id'];
		// if(isset($currentBooking['current_qd_id'])){
		// 	$this->model_account_booking->computeTotals($currentBooking['current_qd_id']);
		// 	$this->model_account_booking->computeDistances($currentBooking['current_qd_id']);
		// }

		$this->data['segment1'] = explode('/', $_SERVER['REQUEST_URI'])[2];
		// $this->data['merchants'] = $this->model_account_booking->getMerchants();
		$this->data['currentBooking'] = $currentBooking;
		$this->data['trans_session_id'] = $this->user->Random("oc_quick_delivery", "trans_session_id", 20);
		//var_dump($currentBooking);
		if($currentBooking['current_qd_id'] > 0) {
			$this->data['currentBooking'] = $this->model_account_booking->getCurrentBookingData($currentBooking);
		}
		
		if(isset($_SESSION['err_msg'])){
			$this->data['err_msg'] = $_SESSION['err_msg'];
		}
		$this->template = 'default/template/account/booking.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());	
	}	

	public function addlocation() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'booking'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}

		$this->load->model('account/booking') ;
		$this->load->model('account/manageadmin');
		$url = '';
		$page = 0;
		$page_limit = 25;		
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;
			$data = $this->request->post;	
				
			if(isset($data['task'])){
				if($data['task'] == 'addLocationSubmit'){
					$return_array = $this->model_account_booking->addLocationQD($data);
					$this->data['err_msg'] = $return_array['qd_location_msg'];
					$this->data['qd_location_id'] = $return_array['qd_location_id'];
					$data['current_qd_id'] = $return_array['current_qd_id'];
				}
				
				if($data['task'] == 'editLocationSubmit'){
					$return_array = $this->model_account_booking->editLocationQD($data);
					$this->data['err_msg'] = $return_array['qd_location_msg'];
					$this->data['qd_location_id'] = $return_array['qd_location_id'];
					$data['current_qd_id'] = $return_array['current_qd_id'];
					$this->data['move_to'] = "location_div";
				}
				
				if($data['task'] == 'addLocationManualItems'){
					$return_array = $this->model_account_booking->addLocationManualItems($data);
					$this->data['err_msg'] = $return_array['qd_location_msg'];
					$this->data['qd_location_id'] = $return_array['qd_location_id'];
					$data['current_qd_id'] = $return_array['current_qd_id'];
					$this->data['move_to'] = "add_items_div";
				}
				
				if($data['task'] == 'addLocationItems'){
					$return_array = $this->model_account_booking->addLocationItems($data);
					$this->data['err_msg'] = $return_array['qd_location_msg'];
					$this->data['qd_location_id'] = $return_array['qd_location_id'];
					$data['current_qd_id'] = $return_array['current_qd_id'];
					$this->data['move_to'] = "add_items_div";
				}
				
				if($data['task'] == 'addLocationSubmitWithMarkers'){
					$return_array = $this->model_account_booking->addLocationSubmitWithMarkers($data);
					$this->data['err_msg'] = $return_array['qd_location_msg'];
					$this->data['qd_location_id'] = $return_array['qd_location_id'];
					$data['current_qd_id'] = $return_array['current_qd_id'];
				}

				if($data['task'] == 'updateItems'){
					$this->data['err_msg'] = $this->model_account_booking->updateItems($data);
				}
				
				if($data['task'] == 'removeItemSubmit'){
					$return_array = $this->model_account_booking->removeItemSubmit($data);
					$this->data['err_msg'] = $return_array['qd_location_msg'];
					$this->data['qd_location_id'] = $return_array['qd_location_id'];
					$data['current_qd_id'] = $return_array['current_qd_id'];
				}

				if($data['task'] == 'imHere'){
					$this->data['err_msg'] = $this->model_account_booking->imHere($data);
				}

				if($data['task'] == 'transferDelivery'){
					$this->data['err_msg'] = $this->model_account_booking->transferDelivery($data);
				}

				if($data['task'] == 'itemPickedUp'){
					$this->data['err_msg'] = $this->model_account_booking->itemPickedUp($data);
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

		$this->data['segment1'] = explode('/', $_SERVER['REQUEST_URI'])[2];
		
		if(isset($this->data['qd_location_id'])){
			$this->data['qd_location_id'] = $this->data['qd_location_id'];
		} else if(isset($data['qd_location_id'])){
			$this->data['qd_location_id'] = $data['qd_location_id'];
		} else {
			$this->data['qd_location_id'] = 0;
		}
		
		if(isset($this->data['merchant_id'])){
			$this->data['merchant_id'] = $this->data['merchant_id'];
		} else if(isset($data['merchant_id'])){
			$this->data['merchant_id'] = $data['merchant_id'];			
		} else {
			$this->data['merchant_id'] = 0;
		}
		
		if(isset($data['location_type'])){
			$this->data['location_type'] = $data['location_type'];
    }
    
    if(isset($data['referror'])){
			$this->data['referror'] = $data['referror'];
		}
		
		$this->data['provinces'] = $this->model_account_manageadmin->getProvince();
		// $this->data['merchants'] = $this->model_account_booking->getMerchants();
		$this->data['trans_session_id'] = $this->user->Random("oc_qd_location", "trans_session_id", 20);
		//var_dump($data);
		if($data['current_qd_id'] > 0) {
			$this->data['currentBooking'] = $this->model_account_booking->getCurrentBookingData($data);
			if($this->data['qd_location_id'] > 0) {
				$this->data['currentBookingLocation'] = $this->model_account_booking->getCurrentBookingLocation($this->data['qd_location_id']);
				//var_dump($this->data['currentBookingLocation']);
			}
		}
		
		if(isset($_SESSION['err_msg'])){
			$this->data['err_msg'] = $_SESSION['err_msg'];
		}
		$this->template = 'default/template/account/bookingaddlocation.tpl';
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());	
		unset($_SESSION['err_msg']);
	}		
	
	public function getMerchantInfo(){
  		$this->load->model('account/booking');
		$data = $this->request->get;
		$json = array();
		$json['merchant'] = $this->model_account_booking->getMerchantInfo($data);
		$json['status'] = "success";
		die(json_encode($json));
  	}
	
	public function getItemInfo(){
		$json = array();
  		$this->load->model('account/booking');
		
		$data = $this->request->get;
			
		if(isset($this->data['merchant_id'])){
			$this->data['merchant_id'] = $this->data['merchant_id'];
		} else if(isset($data['merchant_id'])){
			$this->data['merchant_id'] = $data['merchant_id'];			
		} else {
			$this->data['merchant_id'] = 0;
		}
		
		$json['item'] = $this->model_account_booking->getItemInfo($this->data['merchant_id'], $data);
		$json['status'] = "success";
		die(json_encode($json));
  	}
}

?>