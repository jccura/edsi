<?php 
class ControllerCommonItem extends Controller {
	private $error = array();
	private $redirect_page;
	
	public function index() {
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = $this->config->get('config_ssl');
		} else {
			$this->data['base'] = $this->config->get('config_url');
		}
		
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		
		$this->load->model('account/common');
		$this->load->model('account/itemsetup');
		$page = 0; 
		$page_limit = 10; 
		$url = "";
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}			
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;	
			$data = $this->request->post;	
			
			if(isset($data['task'])) {
				if($data['task'] == "addreview") {
					$result = $this->model_account_itemsetup->addReview($data);
					$this->data['err_msg'] = $result['err_msg'];
					$review_id = $result['review_id'];
					$source = "review_photo";
									
					if($review_id != 0) {
						$target_path = DOC_ROOT."image/reviews/";
						$this->data['err_msg'] = "";
						$name = $_FILES[$source]['name'];
						
						if(!empty($name)) {
							
							$image_info = getimagesize($_FILES[$source]["tmp_name"]);
							$image_width = $image_info[0];
							$image_height = $image_info[1];

							$file_extension = pathinfo(basename( $_FILES[$source]['name']), PATHINFO_EXTENSION);

							$target_path = $target_path."review".$review_id.".".strtolower($file_extension); 

							if(strtolower($file_extension) == 'jpg' || strtolower($file_extension) == 'jpeg' || strtolower($file_extension) == 'png') {
								
								if (file_exists($target_path)) {
									unlink($target_path);
								}
								
								if(move_uploaded_file($_FILES[$source]['tmp_name'], $target_path)) {
									$this->model_account_itemsetup->uploadPicReview($review_id,$file_extension);
								} else {
									$this->data['err_msg'] .= "Upload Result: Failed upload of file ".$_FILES[$source]['name'].".";
								}								
							} else {
								$this->data['err_msg'] .= "Pictures should be have an extension of jpg only.<br>";
							}
						} else {
							$this->data['err_msg'] .= "No Pic";
						}
					}
					
					$this->data['item'] = $this->model_account_common->getItem($data);
					$total = $this->model_account_itemsetup->getReviews($data, "total");
					$this->data['reviews'] = $this->model_account_itemsetup->getReviews($data, "data");
					$item_id = $data['item_id'];
					$this->data['err_msg'] = $result['err_msg'];
				}
			}
			
		} else {
			$data = $this->request->get;
			
			if (isset($data['page'])) {
				$page = $data['page'];
			}			
			$data['start'] = ($page - 1) * $page_limit;
			$data['limit'] = $page_limit;
			
			
			if(isset($data['item_id'])) {				
				$this->data['item'] = $this->model_account_common->getItem($data);
				$total = $this->model_account_itemsetup->getReviews($data, "total");
				$this->data['reviews'] = $this->model_account_itemsetup->getReviews($data, "data");
				$item_id = $data['item_id'];
			} else {
				$this->redirect($this->url->link('common/shop', ''));
			}
		}
		
		$this->template = 'default/template/common/item.tpl';
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = 'item/'.$item_id.'/{page}';		
		$this->data['pagination'] = $pagination->render();
		
		$this->children = array(
			'common/footer',
			'common/header'	
		);
		$this->response->setOutput($this->render());
  	}

}
?>