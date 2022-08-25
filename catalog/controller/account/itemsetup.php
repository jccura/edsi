<?php   
class ControllerAccountItemSetup extends Controller {   
	public function index() {
		if (!$this->user->isLogged() or !($this->user->checkScreenAuth($this->user->getUserGroupId(), 'itemsetup'))) {
	  		$this->redirect($this->url->link('common/home', ''));
    	}
		
		$this->load->model('account/itemsetup');
		$page = 0; 
		$page_limit = 20; 
		$url = "";
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			}			
			$this->request->post['start'] = ($page - 1) * $page_limit;
			$this->request->post['limit'] = $page_limit;	
			$data = $this->request->post;	
			
			if(isset($data['task'])) {
				if($data['task'] == "add") {
					$add_result = $this->model_account_itemsetup->addItem($data);
					$this->data['err_msg'] = $add_result['err_msg'];
					$item_id = $add_result['item_id'];
					$source = "photo_item";
									
					if($item_id != 0) {
						$target_path = DOC_ROOT."image/products/";
						$this->data['err_msg'] = "";
						$name = $_FILES[$source]['name'];
						
						if(!empty($name)) {
							
							$image_info = getimagesize($_FILES[$source]["tmp_name"]);
							$image_width = $image_info[0];
							$image_height = $image_info[1];

							$file_extension = pathinfo(basename( $_FILES[$source]['name']), PATHINFO_EXTENSION);

							$target_path = $target_path."product".$item_id."_main".".".strtolower($file_extension); 

							//if($image_width == $image_height) {
								if(strtolower($file_extension) == 'jpg' || strtolower($file_extension) == 'jpeg' || strtolower($file_extension) == 'png') {
									
									if (file_exists($target_path)) {
										unlink($target_path);
									}
									
									if(move_uploaded_file($_FILES[$source]['tmp_name'], $target_path)) {
										$this->model_account_itemsetup->uploadPic($item_id,$file_extension);
										$this->data['err_msg'] .= " Main Picture is successfully uploaded too.<br>";
									} else {
										$this->data['err_msg'] .= "Upload Result: Failed upload of file ".$_FILES[$source]['name'].".";
									}								
								} else {
									$this->data['err_msg'] .= "Pictures should be have an extension of jpg only.<br>";
								}								
							//} else {
							//	$this->data['err_msg'] .= "Length and width of the picture should be the same.";
							//}
							
						} else {
							$this->data['err_msg'] .= "No Main Picture uploaded.<br>";
						}
						
						$source = "photo_item2";
						$name = $_FILES[$source]['name'];
						if(!empty($name)) {
							
							$image_info = getimagesize($_FILES[$source]["tmp_name"]);
							$image_width = $image_info[0];
							$image_height = $image_info[1];

							$file_extension = pathinfo(basename( $_FILES[$source]['name']), PATHINFO_EXTENSION);

							$target_path = $target_path."product".$item_id."_main2".".".strtolower($file_extension); 

							//if($image_width == $image_height) {
								if(strtolower($file_extension) == 'jpg' || strtolower($file_extension) == 'jpeg' || strtolower($file_extension) == 'png') {
									
									if (file_exists($target_path)) {
										unlink($target_path);
									}
									
									if(move_uploaded_file($_FILES[$source]['tmp_name'], $target_path)) {
										$this->model_account_itemsetup->uploadPic2($item_id,$file_extension);
										$this->data['err_msg'] .= " Main Picture 2 is successfully uploaded.<br>";
									} else {
										$this->data['err_msg'] .= "Upload Result: Failed upload of file ".$_FILES[$source]['name'].".";
									}								
								} else {
									$this->data['err_msg'] .= "Pictures should be have an extension of jpg only.<br>";
								}								
							//} else {
							//	$this->data['err_msg'] .= "Length and width of the picture should be the same.";
							//}
							
						} else {
							$this->data['err_msg'] .= "No Main Picture 2 uploaded.<br>";
						}
							
						for($i=1;$i<=10;$i++) {
							if(isset($_FILES['photo_item_detail'.$i]['name'])) {
								$file_extension = pathinfo(basename( $_FILES['photo_item_detail'.$i]['name']), PATHINFO_EXTENSION);
								
								if(strtolower($file_extension) == 'jpg' || strtolower($file_extension) == 'jpeg' || strtolower($file_extension) == 'png')  	{	
									$target_path = DOC_ROOT."image/products/"."product".$item_id."_".$i.".".strtolower($file_extension);
									
									if (file_exists($target_path)) {
										unlink($target_path);
									}
									
									if(move_uploaded_file($_FILES['photo_item_detail'.$i]['tmp_name'], $target_path)) {
										$this->model_account_itemsetup->updaloadPicDetail($item_id, $file_extension, $i);
										$this->data['err_msg'] .= " Detail picture ".$i." is successfully uploaded too.<br>" ;
									}else {
										$this->data['err_msg'] .= "Upload Result: Failed upload of file ".$_FILES['photo_item_detail'.$i]['name'].".<br>";
									}							
								} else {
									$this->data['err_msg'] .= "No Detail photo ".$i.".<br>";
								}
							}
						}
						
					}
				}
				
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

						//if($image_width == $image_height) {
							if(strtolower($file_extension) == 'jpg' || strtolower($file_extension) == 'jpeg' || strtolower($file_extension) == 'png') {
								
								if (file_exists($target_path)) {
									unlink($target_path);
								}
								
								if(move_uploaded_file($_FILES[$source]['tmp_name'], $target_path)) {
									$this->model_account_itemsetup->uploadPicReview($review_id,$file_extension);
									$this->data['err_msg'] .= " Picture is successfully uploaded.<br>";
								} else {
									$this->data['err_msg'] .= "Upload Result: Failed upload of file ".$_FILES[$source]['name'].".";
								}								
							} else {
								$this->data['err_msg'] .= "Pictures should be have an extension of jpg only.<br>";
							}
							
						} else {
							$this->data['err_msg'] .= "No Picture uploaded.<br>";
						}
					}
				}
				
				if($data['task'] == "delete") {
					$this->data['err_msg'] = "";
					if (isset($data['selected'])) {
						foreach ($data['selected'] as $code_selected) {
							$this->data['err_msg'] .= $this->model_account_itemsetup->removeItem($code_selected);
						}	
					}
				}
				
				if($data['task'] == "deletereview") {
					$this->data['err_msg'] = "";
					if (isset($data['selected'])) {
						foreach ($data['selected'] as $review_id) {
							$this->data['err_msg'] .= $this->model_account_itemsetup->removeReview($review_id);
						}	
					}
				}				
				
				if($data['task'] == "submitedit") {
					$this->data['err_msg'] = "";
					$result = $this->model_account_itemsetup->editItem($data);
					$this->data['err_msg'] = $result['err_msg'];
					$source = "photo_item";
					$item_id = $data['item_id'];
					
					if($item_id != 0) {
						$target_path = DOC_ROOT."image/products/";
						$name = $_FILES[$source]['name'];
						
						if(!empty($name)) {
							
							$image_info = getimagesize($_FILES[$source]["tmp_name"]);
							$image_width = $image_info[0];
							$image_height = $image_info[1];

							$file_extension = pathinfo(basename( $_FILES[$source]['name']), PATHINFO_EXTENSION);

							$target_path = $target_path."product".$item_id."_main".".".strtolower($file_extension);  

							//if($image_width == $image_height) {
								if(strtolower($file_extension) == 'jpg' || strtolower($file_extension) == 'jpeg' || strtolower($file_extension) == 'png') {
									if (file_exists($target_path)) {
										unlink($target_path);
									}
									
									if(move_uploaded_file($_FILES[$source]['tmp_name'], $target_path)) {
										$this->model_account_itemsetup->uploadPic($item_id,$file_extension);
										$this->data['err_msg'] .= " Main Photo is successfully updated.<br/>";
									} else {
										$this->data['err_msg'] .= "Upload Result: Failed upload of file ".$_FILES[$source]['name'].".<br/>";
									}								
								} else {
									$this->data['err_msg'] .= "Pictures should be have an extension of jpg only.<br/>";
								}								
							///} else {
								//$this->data['err_msg'] .= "Length and width of the picture should be the same.";
							//}
							
						} else {
							$this->data['err_msg'] .= "Main Photo not updated.<br/>";
						}
						
						$source = "photo_item2";
						$name = $_FILES[$source]['name'];
						if(!empty($name)) {
							
							$image_info = getimagesize($_FILES[$source]["tmp_name"]);
							$image_width = $image_info[0];
							$image_height = $image_info[1];

							$file_extension = pathinfo(basename( $_FILES[$source]['name']), PATHINFO_EXTENSION);

							$target_path = $target_path."product".$item_id."_main2".".".strtolower($file_extension); 

							//if($image_width == $image_height) {
							if(strtolower($file_extension) == 'jpg' || strtolower($file_extension) == 'jpeg' || strtolower($file_extension) == 'png') {
								
								if (file_exists($target_path)) {
									unlink($target_path);
								}
								
								if(move_uploaded_file($_FILES[$source]['tmp_name'], $target_path)) {
									$this->model_account_itemsetup->uploadPic2($item_id,$file_extension);
									$this->data['err_msg'] .= " Main Picture 2 is successfully uploaded.<br>";
								} else {
									$this->data['err_msg'] .= "Upload Result: Failed upload of file ".$_FILES[$source]['name'].".";
								}								
							} else {
								$this->data['err_msg'] .= "Pictures should be have an extension of jpg only.<br>";
							}
							
						} else {
							$this->data['err_msg'] .= "No Main Picture 2 uploaded.<br>";
						}

						for($i=1;$i<=10;$i++) {
							if(isset($_FILES['photo_item_detail'.$i]['name'])) {
								$file_extension = pathinfo(basename( $_FILES['photo_item_detail'.$i]['name']), PATHINFO_EXTENSION);
								
								if(strtolower($file_extension) == 'jpg' || strtolower($file_extension) == 'jpeg' || strtolower($file_extension) == 'png')  	{	
									$target_path = DOC_ROOT."image/products/"."product".$item_id."_".$i.".".strtolower($file_extension);
									$ii = $i;
									if(move_uploaded_file($_FILES['photo_item_detail'.$i]['tmp_name'], $target_path)) {
										$this->model_account_itemsetup->updaloadPicDetail($item_id, $file_extension, $i);
										$this->data['err_msg'] .= " Detail picture ".$i." is successfully updated.<br>" ;
									}else {
										$this->data['err_msg'] .= "Upload Result: Failed upload of file ".$_FILES['photo_item_detail'.$i]['name'].".<br>";
									}							
								} else {
									$this->data['err_msg'] .= "Detail photo ".$i. ". not updated.<br>";
								}
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
		}	
		
		$this->data['categories'] = $this->model_account_itemsetup->getCategories();
		$this->data['usergroup'] = $this->model_account_itemsetup->getUserGroup();		
		
		if(!isset($data['task'])) {
			$data['task'] = "";
		}
		
		if($data['task'] == "edit") {
			$this->data['item_details'] = $this->model_account_itemsetup->getItemDetails($data['item_id']);
			$this->data['reviews'] = $this->model_account_itemsetup->getReviews($data, "data");
			$total = $this->model_account_itemsetup->getReviews($data, "total");
			$template = 'default/template/account/itemsetupedit.tpl';
		} else {	
			$this->data['items'] = $this->model_account_itemsetup->getItems($data, "data");			
			$total = $this->model_account_itemsetup->getItems($data, "total");
			$template = 'default/template/account/itemsetup.tpl';
		}
		
		$this->data['usergroup'] = $this->model_account_itemsetup->getUserGroup($data);
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = 'itemsetup/{page}';		
		$this->data['pagination'] = $pagination->render();	
		$this->template = $template;
		
		$this->children = array(
			'common/column_left',
			'common/footer',
			'common/header'	
		);
				
		$this->response->setOutput($this->render());
  	}
	
}
?>