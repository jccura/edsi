<?php 
class ControllerAccountCart extends Controller {
	private $error = array();
	private $redirect_page;
	
	public function index() {
		
		$this->load->model('account/common');
		$this->load->model('api/sendorder');
		$this->load->model('account/trackingpage');
		$url = '';
		$page = 0;
		$page_limit = 100;
		
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->get['page'];
			}	
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;
			$data = $this->request->post;

			if(isset($data['task'])) {		

				$this->data['result'] = "";
				if($data['task'] == "addToCart") {
					$result = $this->model_account_common->addToCart($data);
					$this->data['err_msg'] = $result['result_msg'];
				}
				
				if($data['task'] == "addToCartForDownline") {
					$result = $this->model_account_common->addToCartForDownline($data);
					$this->data['err_msg'] = $result['result_msg'];
				}	
				
				if($data['task'] == "submitOrder") {
					$result = $this->model_account_common->submitOrder($data);
					$this->data['err_msg'] = $result['msg'];
					
					if($data['delivery_option'] == 96 || $data['delivery_option'] == 97 || $data['delivery_option'] == 98 || $data['delivery_option'] == 109) {
						if($result['msg'] == "Order Successful"){
							$allItems = $this->model_account_trackingpage->getTrackOrders($data);
							// $allItems = $this->model_account_trackingpage->getTrackOrders($this->data['allItems']);
							
							$to = $this->request->post['email'];
							$subject = "".WEBSITE_TITLE.", New Order#".$result['order_id'];

							$message = "<html>
										<head>
											<title>".WEBSITE_TITLE." - Thank you for purchasing!</title>
											
											<style>
												table {
												  font-family: arial, sans-serif;
												  border-collapse: collapse;
												  width: 100%;
												}

												td, th {
												  border: 1px solid #dddddd;
												  text-align: left;
												  padding: 8px;
												}

												tr:nth-child(even) {
												  background-color: #dddddd;
												}
											</style>
										</head>
										<link rel='stylesheet' type='text/css' href='".LOCAL_PROD."css/font-awesome/css/font-awesome.css' />
										<style type='text/css'>
											body{
												font-family: century gothic;
												margin: 0;
											}
										</style>
										<body>
											<div style='text-align: center' >
												<img src='".LOCAL_PROD."image/logo7.png' style='width: 30%;'>
												<br>
												<div style='width: 100%; background-color: #FE4400; color: white;'>
													<h1 style='padding: 20px;'>Thank you, ". ucfirst(strtolower($data['firstname'])) . ' ' . ucfirst(strtolower($data['lastname'])) . "!</h1>
												</div>
												<div style='font-size: 20px;'>
													<p>Your Order is being processed</p>
												</div>
												<div style='font-size: 18px;'>
													<p>To track your order, please click the button below:</p>
													<a href='".LOCAL_PROD."trackingpage/". $result['reference'] ."' style='color: white; background-color: #3B4ABC; border-radius: 5px; text-decoration: none; padding: 18px;' target='_blank'>Track Order</a>
												</div>
												<br>
												<br>
												<div style='width: 100%; background-color: #FE4400; color: white;'>
													<h2 style='padding: 10px;'>Order details</h2>
												</div>
												<div style='font-size: 17px;'>
													<table>
														<thead>
															<tr style='background-color: #FE4400; color: white;'>
																<th>Product</td>
																<th>Quantity</td>
																<th>Amount</td>
															</tr>
														</thead>
														<tbody>
															". $allItems ."
														</tbody>
													</table>
													<br>
												</div>
												<br>									
												<br>
												<br>
												<div>
													<div>
														<p>
														Copyright © 2020 All rights reserved</p>
													</div>
													<div>
														<h1><img src='".LOCAL_PROD."image/logo7.png' style='width: 250px;'></h1>
													</div>
												</div>
												
											</div>
										</body>
									</html>";
							
							// Always set content-type when sending HTML email
							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

							// More headers
							$headers .= 'From: <noreply@esthetiquedirectsales.com>' . "\r\n";

							mail($to,$subject,$message,$headers);

							$this->data['err_msg'] = "Congratulations! Your order now is being processed, please check your email " . $to . " for reference.<br>";
							
							if($data['payment_option'] == 106 || $data['payment_option'] == 93) {
								if($result['msg'] == "Order Successful"){
									// $this->data['order_result'] = $result['order_id'];
									$this->data['err_msg'] .= $this->model_api_sendorder->sendOrderDetails($this->request->post);
								} else {
									$this->data['err_msg'] .= $result['msg'];
								}
								
							}

							$this->data['err_msg'] .= "<br><a href='".LOCAL_PROD."trackingpage/". $result['reference'] ."' style='color: white; background-color: #4A9DFD; text-decoration: none; padding: 15px;' target='_blank'>Track Order</a>";

							if(isset($this->data['result']['reference'])) {
								$this->redirect("trackingpage/" . $this->data['result']['reference'] . "/" . $to);
							}
						} 
					}
				}
			}
		} else {
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			}	
			$this->request->get['start'] = ($page - 1) * $page_limit;
			$this->request->get['limit'] = $page_limit;
			$data = $this->request->get;
			if(isset($data['task'])) {				
				if($data['task'] == "removeitem") {
					$this->data['err_msg'] = $this->model_account_common->removeItemFromCart($data);
				}
				
				if($data['task'] == "cancel") {
					$this->data['err_msg'] = $this->model_account_common->cancelOrder($data);
				}				
			}
		}
		
		if(isset($data['task'])) {
			$this->data['task'] = $data['task'];
		} else {
			$this->data['task'] = "";
		}
		
		$this->data['cart'] = $this->model_account_common->getCart($data);
		// var_dump($this->data['cart']);
		if($this->user->isLogged()) {
			$this->data['flag'] = $this->model_account_common->getSmeFlag();
		}
		$this->data['provinces'] = $this->model_account_common->getProvinces();
		$this->data['delivery_options'] = $this->model_account_common->getdeliveryOptions();
		// echo $this->user->getOperator()."<br>";
		// echo $this->user->getSalesRep()."<br>";
		// echo $this->user->getReseller()."<br>";
		// echo $this->user->getAdmin()."<br>";
		
    	$this->template = 'default/template/common/cart.tpl';
		$this->children = array(
			// 'common/column_left',
			// 'common/column_right',
			// 'common/content_top',
			// 'common/content_bottom',
			'common/footer',
			'common/header'	
		);
						
		$this->response->setOutput($this->render());
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

	public function determineshippingfee() {
		
		$json = array();
		$this->load->model('account/common');
		$data = $this->request->get;
		
		$json['fee'] = $this->model_account_common->getDeliveryFee($data['order_id'], $data['payment_option'], $data['brgy_id']);
		$json['success'] = "success";
		echo json_encode($json); 
	}
	
	public function getPaymentOption(){
  		$this->load->model('account/common');
		$json = array();
		$json['payment'] = $this->model_account_common->getPaymentOptions1($this->request->get);
		$json['status'] = "success";
		echo json_encode($json);
  	}

}
?>
