<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
		  <i class="ni ni-bag-17 ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Item Maintenance</h6>
		</div>
	  </div>
	</div>
  </div>
</div>
	<div class="container-fluid mt--6">			
		<form action="" method="post" enctype="multipart/form-data" id="form">
			<input type="hidden" id="task" name="task" value="">
			<div class="panel-body">
				<div class="card">
					<div class="card-body">	
						
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">	
									<ul class="nav nav-tabs" role="tablist" id="myTabs">
									  <li class="nav-item tab-header-links">
										<a class="nav-link mr-4 pb-1 pl-0 pr-0 active" data-toggle="tab" href="#edit" role="tab"><i class="fa fa-pencil"></i> Edit</a>
									  </li>	
									  <li class="nav-item tab-header-links">
										<a class="nav-link mr-4 pb-1 pl-0 pr-0" data-toggle="tab" href="#images" role="tab"><i class="icon-image"></i> Images </a>
									  </li>
									  <li class="nav-item tab-header-links">
										<a class="nav-link mr-4 pb-1 pl-0 pr-0" data-toggle="tab" href="#reviews" role="tab"><i class="icon-bar-graph-2"></i> Reviews </a>
									  </li>
									</ul>
								</div>
							</div>
						</div>

						<div class="tab-content"> <!-- start tab-content -->
							
							<div class="tab-pane active" id="edit" role="tabpanel">
						
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">	
											<label>Item Name:</label>
											<input class="form-control input" type="text" name="item_name" id="item_name" value="<?php if(isset($item_details['item_name'])) { echo $item_details['item_name']; } ?>" />
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">	
											<label>Item Code:</label>
											<input class="form-control input" type="text" id="item_code" name="item_code" value="<?php if(isset($item_details['item_code'])) { echo $item_details['item_code']; } ?>">
										</div>					
									</div>
									<div class="col-md-2">
										<div class="form-group">	
											<label>Category:</label>
											<select class="form-control input" id="category_id" name="category_id">
												<option value="0" selected>Select Category</option>
											<?php if(isset($categories)) { ?>
												<?php foreach ($categories as $cat) { ?>
													<?php if($item_details['category_id'] == $cat['category_id']) { ?>
														<option value="<?php echo $cat['category_id'];?>" selected><?php echo $cat['description'];?></option>
													<?php } else { ?>
														<option value="<?php echo $cat['category_id'];?>"><?php echo $cat['description'];?></option>
													<?php } ?>
												<?php } ?>
											<?php } ?>
											</select>
										</div>					
									</div>
									<div class="col-md-2">
										<div class="form-group">	
											<label>Quantity of packs:</label>
											<input class="form-control input" type="number" step="0.01" name="quantity" id="quantity" min="0" value="<?php if(isset($item_details['quantity'])) { echo $item_details['quantity']; } ?>" />
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">	
											<label>Create user upon purchase:</label>
											<select class="form-control input" id="usergroup" name="usergroup">
												<option value="0" selected>No</option>
												<?php if(isset($usergroup)) { ?>
													<?php foreach ($usergroup as $ug) { ?>
														<?php if($item_details['user_group_id'] == $ug['user_group_id']) { ?>
															<option value="<?php echo $ug['user_group_id'];?>" selected><?php echo $ug['name'];?></option>
														<?php } else { ?>
															<option value="<?php echo $ug['user_group_id'];?>"><?php echo $ug['name'];?></option>
														<?php } ?>
													<?php } ?>
												<?php } ?>
											</select>
										</div>					
									</div>								
								</div>
								<div class="row">
									<div class="col-md-2">
										<div class="form-group">	
											<label>Price:</label>
											<input class="form-control input" type="number" step="0.01" name="price" id="price" value="<?php if(isset($item_details['price'])) { echo $item_details['price']; } ?>" />
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">	
											<label>Status:</label>
											<select class="form-control input" id="active" name="active">
												<?php if($item_details['active'] == 1) { ?>
													<option value="1" selected>Enabled</option>
													<option value="0" >Disabled</option>
												<?php } else { ?>
													<option value="1" >Enabled</option>
													<option value="0" selected>Disabled</option>
												<?php } ?>
											</select>
										</div>					
									</div>
									<div class="col-md-2">
										<div class="form-group">	
											<label>Sort:</label>
											<select class="form-control input" id="sort" name="sort">
												<?php for($i=0;$i<100;$i++) { ?>
													<?php if($item_details['sort'] == $i) { ?>
														<option value="<?php echo $i; ?>" selected><?php echo $i; ?></option>
													<?php } else { ?>
														<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">	
											<label>CV:</label>
											<input class="form-control input" type="number" name="cv" id="cv" value="<?php if(isset($item_details['cv'])) { echo $item_details['cv']; } ?>" />
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">	
											<label>Points:</label>
											<input class="form-control input" type="number" name="points" id="points" value="<?php if(isset($item_details['points'])) { echo $item_details['points']; } ?>" />
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">	
											<label>Item Profit:</label>
											<input class="form-control input" type="number" step="0.01" name="item_profit_per" id="item_profit_per" min="0" value="<?php if(isset($item_details['item_profit_per'])) { echo $item_details['item_profit_per']; } ?>" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">	
											<label>Distributor Discount:</label>
											<input class="form-control input" type="number" step="0.01" name="distributor_discount_per" id="distributor_discount_per" min="0" value="<?php if(isset($item_details['distributor_discount_per'])) { echo $item_details['distributor_discount_per']; } ?>" />
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">	
											<label>Reseller Discount:</label>
											<input class="form-control input" type="number" step="0.01" name="reseller_discount_per" id="reseller_discount_per" min="0" value="<?php if(isset($item_details['reseller_discount_per'])) { echo $item_details['reseller_discount_per']; } ?>" />
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">	
											<label>Direct Referral:</label>
											<input class="form-control input" type="number" step="0.01" name="direct_referral" id="direct_referral" min="0" value="<?php if(isset($item_details['direct_referral'])) { echo $item_details['direct_referral']; } ?>" />
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">	
											<label>Advance Payment:</label>
											<input class="form-control input" type="number" step="0.01" name="advance_payment" id="advance_payment" min="0" value="<?php if(isset($item_details['advance_payment'])) { echo $item_details['advance_payment']; } ?>" />
										</div>
									</div>
								</div>	
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">	
											<label>IT:</label>
											<input class="form-control input" type="number" step="0.01" name="system_fee" id="system_fee" min="0" value="<?php if(isset($item_details['system_fee'])) { echo $item_details['system_fee']; } ?>" />
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">	
											<label>Service Fee:</label>
											<input class="form-control input" type="number" step="0.01" name="service_fee" id="service_fee" min="0" value="<?php if(isset($item_details['service_fee'])) { echo $item_details['service_fee']; } ?>" />
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">	
											<label>Tool:</label>
											<input class="form-control input" type="number" step="0.01" name="tool" id="tool" min="0" value="<?php if(isset($item_details['tools'])) { echo $item_details['tools']; } ?>" />
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">	
											<label>Tax:</label>
											<input class="form-control input" type="number" step="0.01" name="tax" id="tax" min="0" value="<?php if(isset($item_details['tax'])) { echo $item_details['tax']; } ?>" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">	
											<label>Shipping :</label>
											<input class="form-control input" type="number" step="0.01" name="shipping" id="shipping" min="0" value="<?php if(isset($item_details['shipping'])) { echo $item_details['shipping']; } ?>" />
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">	
											<label>Top Up:</label>
											<input class="form-control input" type="number" step="0.01" name="top_up" id="top_up" min="0" value="<?php if(isset($item_details['top_up'])) { echo $item_details['top_up']; } ?>" />
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">	
											<label>Cost:</label>
											<input class="form-control input" type="number" step="0.01" name="cost" id="cost" min="0" value="<?php if(isset($item_details['cost'])) { echo $item_details['cost']; } ?>" />
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">	
											<label>Income:</label>
											<input class="form-control input" type="number" step="0.01" name="income" id="income" min="0" value="<?php if(isset($item_details['income'])) { echo $item_details['income']; } ?>" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">	
											<label>Short Description:</label>
											<textarea data-toggle="quill" class="form-control summernote" id="short_description" name="short_description"><?php if(isset($item_details['short_description'])) { echo html_entity_decode($item_details['short_description'], ENT_QUOTES, 'UTF-8'); } ?></textarea>
										</div>
									</div>
								
									<div class="col-md-6">
										<div class="form-group">	
											<label>Description:</label>
											<textarea class="form-control summernote" id="description" name="description"><?php if(isset($item_details['description'])) { echo html_entity_decode($item_details['description'], ENT_QUOTES, 'UTF-8'); } ?></textarea>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<center>
											<input type="hidden" id="item_id" name="item_id" value="<?php if(isset($item_details['item_id'])) { echo $item_details['item_id']; } ?>">
											<input class="btn btn-outline-edit text-white btn-block" type="submit" id="search" value="Save" onclick="javascript:processTask('submitedit');">	
										</center>
									</div>
								</div>
							</div>
							
							<div class="tab-pane" id="images" role="tabpanel">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">	
											<label><b>Main Photo 1:</b></label>
											<input type="file" id="photo_item" name="photo_item" class="form-control" />
										</div>					
									</div>
									
									<div class="col-md-6">
										<div class="form-group">	
											<label><b>Main Photo 2:</b></label>
											<input type="file" id="photo_item2" name="photo_item2" class="form-control" />
										</div>					
									</div>
									<hr/>
									
									<div class="col-md-12">
										<div class="row">
											<?php for($i=1; $i<=10; $i++) { ?>
												<div class="col-md-2">
													<label>Detail Photo <?php echo $i; ?>:</label>
												</div>
												<div class="col-md-4 mb-2">
												<input class="form-control input" type="file" id="photo_item_detail<?php echo $i; ?>" name="photo_item_detail<?php echo $i; ?>" value="">
												</div> 
											<?php } ?>
										</div>
										<hr/>
										<br/>
									</div>
										
									<div class="row">	
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-6">
													<?php if($item_details['main_flag']=="1"){ ?>
														<label>Main Photo 1:</label>
														<img width="100%" height="100%" src="image/products/product<?php echo $item_details['item_id'] ?>_main.<?php echo $item_details['main_extension'] ?>" class="img-fluid" />
													<?php } ?>
												</div>
												
												<div class="col-md-6">
													<?php if($item_details['main_flag2']=="1"){ ?>
														<label>Main Photo 2:</label>
														<img width="100%" height="100%" src="image/products/product<?php echo $item_details['item_id'] ?>_main.<?php echo $item_details['main_extension2'] ?>" class="img-fluid" />
													<?php } ?>
												</div>
											</div>
											<hr/>
										</div>
										
										<div class="col-md-12">
											<div class="row">
												<?php for($i=1;$i<=10;$i++) { ?>
													<?php if($item_details['flag_'.$i] == 1) { ?>
														<div class="col-md-6">
															<label>Detail Photo <?php echo $i; ?>:</label>
															<img width="100%" height="100%" src="image/products/product<?php echo $item_details['item_id'] ?>_<?php echo $i; ?>.<?php echo $item_details['extension_'.$i] ?>" class="img-fluid" />
															<hr/>
														</div>
													<?php } ?>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<center>
											<input type="hidden" id="item_id" name="item_id" value="<?php if(isset($item_details['item_id'])) { echo $item_details['item_id']; } ?>">
											<input class="btn bg-avgreen text-white btn-block" type="submit" id="search" value="Save" onclick="javascript:processTask('submitedit');">													
										</center>
									</div>
								</div>
							</div>
							
							<div class="tab-pane" id="reviews" role="tabpanel">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">	
											<label>Nickname:</label>
											<input class="form-control input" type="text" id="reviewed_by" name="reviewed_by"></textarea>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">	
											<label>Review:</label>
											<textarea class="form-control summernote" id="review" name="review"></textarea>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">	
											<label><b>Photo:</b></label>
											<input type="file" id="review_photo" name="review_photo" class="form-control" />
										</div>					
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-12">
										<center>
											<input class="btn bg-avgreen text-white btn" type="button" id="addreview" value="Add Review" onclick="javascript:processTask('addreview');">
											<input class="btn btn-danger btn" type="button" id="deletereview" value="Delete" onclick="javascript:processTask('deletereview');">
										</center>
									</div>
								</div>
								<br/>
								
								<div class="table-responsive">			
									<table class="table table-striped">
									  <thead>
										<tr class="table-primary">
										  <th class="text-dark">Review Id</th>
										  <th width="1" style="text-align: center;">
											<input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
										  </th>
										  <th class="text-dark hidden">Item Id</th>
										  <th class="text-dark">Reviewed By</th>
										  <th class="text-dark">Review</th>
										  <th class="text-dark">Photo</th>
										  <th class="text-dark">Date Added</th>
										</tr>
									  </thead>
									  <tbody>
										<?php if (isset($reviews)) { ?>
										<?php foreach ($reviews as $review) { ?>			
										<tr>
										  <td><?php echo $review['review_id'];?></td>
										  <td style="text-align: center;">
											<input type="checkbox" name="selected[]" value="<?php echo $review['review_id'];?>"/>
										  </td>
										  <td class="hidden"><?php echo $review['item_id'];?></td>
										  <td><?php echo $review['reviewed_by'];?></td>
										  <td width="300px">
											<?php echo html_entity_decode($review['review'], ENT_QUOTES, 'UTF-8');?>
										  </td>
										  <td>
											<img src="image/reviews/review<?php echo $review['review_id'];?>.<?php echo $review['main_extension'] ?>" class="img-responsive" style="width:100px; height:100px;">
										  </td>
										  <td><?php echo $review['date_added'];?></td>										  
										</tr>
										<?php } ?>
										<?php } ?>
									  </tbody>
									</table>
								</div>
								<div class="pagination"><?php echo $pagination; ?></div>
							</div>
							
							<br/>
							
						</div> <!-- end tab-content -->

					</div>
				</div>
			</div>
				
		</form>		
	</div>
</div>
<div id="dialog-message" title="Message" style="display:none; width: 600px;">
  <span id="msg"></span>
</div>
<?php echo $footer; ?>
<script type="text/javascript"><!--
$(document).ready(function() {
	<?php if(isset($err_msg)) { ?>
		<?php if(!empty($err_msg)) { ?>
			$('#msg').html("<?php echo $err_msg; ?>");
			$(function() {
				$("#dialog-message").dialog({
					modal: true,
					width: 600,
					buttons: {
						Ok: function() {
							$(this).dialog("close");
						}
					}
				});
			});
		<?php } ?>
	<?php } ?>
});

function processTask(task) {
	$('#task').val(task); 
	$('form').attr('action', 'itemsetup'); 
	$('form').submit();
}
//--></script>							