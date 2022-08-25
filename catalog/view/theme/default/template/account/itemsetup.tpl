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
		<div class="panel-body">
			<div class="row">	
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">	
						
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">	
										<label>Item Name:</label>
										<input class="form-control input" type="text" name="item_name" id="item_name" value="" />
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">	
										<label>Item Code:</label>
										<input class="form-control input" type="text" id="item_code" name="item_code">
									</div>					
								</div>
								<div class="col-md-2">
									<div class="form-group">	
										<label>Category:</label>
										<select class="form-control input" id="category_id" name="category_id">
											<option value="0" selected>Select Category</option>
										<?php if(isset($categories)) { ?>
											<?php foreach ($categories as $cat) { ?>
												<option value="<?php echo $cat['category_id'];?>"><?php echo $cat['description'];?></option>
											<?php } ?>
										<?php } ?>
										</select>
									</div>					
								</div>
								<div class="col-md-2">
									<div class="form-group">	
										<label>Quantity of packs:</label>
										<input class="form-control input" type="number" step="0.01" name="quantity" id="quantity" min="0" value="1" />
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">	
										<label>Create user upon purchase:</label>
										<select class="form-control input" id="usergroup" name="usergroup">
											<option value="0" selected>No</option>
											<?php if(isset($usergroup)) { ?>
												<?php foreach ($usergroup as $ug) { ?>
													<option value="<?php echo $ug['user_group_id'];?>"><?php echo $ug['name'];?></option>
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
										<input class="form-control input" type="number" step="0.01" name="price" id="price" value="0" />
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">	
										<label>Status:</label>
										<select class="form-control input" id="active" name="active">
											<option value="" selected>Select Status</option>
											<option value="1" selected>Enabled</option>
											<option value="0" selected>Disabled</option>
										</select>
									</div>					
								</div>
								<div class="col-md-2">
									<div class="form-group">	
										<label>Sort:</label>
										<select class="form-control input" id="sort" name="sort">
											<?php for($i=0;$i<100;$i++) { ?>
												<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">	
										<label>CV:</label>
										<input class="form-control input" type="number" name="cv" id="cv" value="0" />
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">	
										<label>Points:</label>
										<input class="form-control input" type="number" name="points" id="points" value="0" />
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">	
										<label>Item Profit:</label>
										<input class="form-control input" type="number" step="0.01" name="item_profit_per" id="item_profit_per" min="0" value="0" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">	
										<label>Distributor Discount:</label>
										<input class="form-control input" type="number" step="0.01" name="distributor_discount_per" id="distributor_discount_per" min="0" value="0" />
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">	
										<label>Reseller Discount:</label>
										<input class="form-control input" type="number" step="0.01" name="reseller_discount_per" id="reseller_discount_per" min="0" value="0" />
									</div>
								</div>	
								<div class="col-md-3">
									<div class="form-group">	
										<label>Direct Referral:</label>
										<input class="form-control input" type="number" step="0.01" name="direct_referral" id="direct_referral" min="0" value="0" />
									</div>
								</div>	
								<div class="col-md-3">
									<div class="form-group">	
										<label>Advance Payment:</label>
										<input class="form-control input" type="number" step="0.01" name="advance_payment" id="advance_payment" min="0" value="0" />
									</div>
								</div>	
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">	
										<label>IT:</label>
										<input class="form-control input" type="number" step="0.01" name="system_fee" id="system_fee" min="0" value="0" />
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">	
										<label>Service Fee:</label>
										<input class="form-control input" type="number" step="0.01" name="service_fee" id="service_fee" min="0" value="0" />
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">	
										<label>Tool:</label>
										<input class="form-control input" type="number" step="0.01" name="tool" id="tool" min="0" value="0" />
									</div>
								</div>	
								<div class="col-md-3">
									<div class="form-group">	
										<label>Tax:</label>
										<input class="form-control input" type="number" step="0.01" name="tax" id="tax" min="0" value="0" />
									</div>
								</div>	
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">	
										<label>Shipping :</label>
										<input class="form-control input" type="number" step="0.01" name="shipping" id="shipping" min="0" value="0" />
									</div>
								</div>	
								<div class="col-md-3">
									<div class="form-group">	
										<label>Top Up:</label>
										<input class="form-control input" type="number" step="0.01" name="top_up" id="top_up" min="0" value="0" />
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">	
										<label>Cost:</label>
										<input class="form-control input" type="number" step="0.01" name="cost" id="cost" min="0" value="0" />
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">	
										<label>Income:</label>
										<input class="form-control input" type="number" step="0.01" name="income" id="income" min="0" value="0" />
									</div>
								</div>	
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">	
										<label>Short Description:</label>
										<textarea class="form-control summernote" id="short_description" name="short_description"></textarea>
									</div>
								</div>
							
								<div class="col-md-6">
									<div class="form-group">	
										<label>Long Description:</label>
										<textarea class="form-control summernote" id="description" name="description"></textarea>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">	
										<label><b>Main Photo:</b></label>
										<input type="file" id="photo_item" name="photo_item" class="form-control" />
									</div>					
								</div>
								
								<div class="col-md-6">
									<div class="form-group">	
										<label><b>Main Photo 2:</b></label>
										<input type="file" id="photo_item2" name="photo_item2" class="form-control" />
									</div>					
								</div>
								
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
								</div>									
							</div>
							<br>
							
							<div class="row">
								<div class="col-md-12">
									<center>
										<div class="template-demo">
											<input type="hidden" id="task" name="task" value="">
											<input class="btn btn-outline-user text-white btn" type="button" id="search" value="Search" onclick="$('#task').val('search'); $('form').attr('action', '<?php echo HTTP_SERVER;?>index.php?route=account/itemsetup'); $('form').submit();">						
											<?php if($this->user->getUserGroupId() == 12 or $this->user->getUserGroupId() == 38 or $this->user->getUserGroupId() == 48) {?>
											<input class="btn btn-outline-user text-white btn" type="button" id="add" value="Add" onclick="$('#task').val('add'); $('form').attr('action', '<?php echo HTTP_SERVER;?>index.php?route=account/itemsetup'); $('form').submit();">						
											<input class="btn btn-outline-user text-white btn" type="button" id="delete" value="Delete" onclick="$('#task').val('delete'); $('form').attr('action', '<?php echo HTTP_SERVER;?>index.php?route=account/itemsetup'); $('form').submit();">
											<?php } ?>
										</div>
									</center>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<br/>
		
		<div class="row">
			<div class="col">
				<div class="card">
					<div class="card-header border-0">
					  <h3 class="mb-0">List of Items</h3>
					</div>						
					<div class="col-md-12">
						<div class="row">
							<div class="table-responsive">
								<table class="table align-items-center table-flush">
									<thead class="thead-light">
										<tr class="table-primary">
											<th class="left text-info">Item Id</th>
											<th width="1" style="text-align: center;">
												<input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
											</th>
											<th scope="col">Photo</th>
											<th scope="col">Action</th>
											<th scope="col">Category</th>
											<th scope="col">Item Code</th>
											<th scope="col">Item Name</th>
											<th scope="col">Short Description</th>
											<th scope="col">Long Description</th>
											<th scope="col">Price</th>											  
											<th scope="col">Cost</th>											  
											<th scope="col">Income</th>											  
											<th scope="col">Service Fee</th>											  
											<th scope="col">IT</th>											  
											<th scope="col">Top Up</th>											  
											<th scope="col">Tool</th>											  
											<th scope="col">Shipping</th>											  
											<th scope="col">Tax</th>											  
											<th scope="col">CV</th>											  
											<th scope="col">Quantity of packs</th>
											<th scope="col">Item Profit</th>												  
											<th scope="col">Distributor Discounts</th>
											<th scope="col">Reseller Discounts</th>
											<th scope="col">Direct Referral</th>
											<th scope="col">Advance Payment</th>
											<th scope="col">Points</th>
											<th scope="col">Sort</th>
										</tr>
									</thead>
									<tbody class="list">
										<?php if (isset($items)) { ?>
										<?php foreach ($items as $it) { ?>			
										<tr>
											<td><?php echo $it['item_id'];?></td>
											<td style="text-align: center;">
												<input type="checkbox" name="selected[]" value="<?php echo $it['item_id'];?>"/>
											</td>
											<td>
												<img src="image/products/product<?php echo $it['item_id'];?>_main.<?php echo $it['main_extension'] ?>" class="img-responsive" style="width:100px; height:100px;">
											</td>
											<td><a class="btn btn-outline-user text-white btn-sm" href="index.php?route=account/itemsetup&task=edit&item_id=<?php echo $it['item_id'];?>" align="center">Edit</a></td>
											<td><?php echo $it['category'];?></td>
											<td><?php echo $it['item_code'];?></td>
											<td><?php echo $it['item_name'];?></td>
											<td width="300px"><?php echo substr(strip_tags(html_entity_decode($it['short_description'], ENT_QUOTES, 'UTF-8')), 0, 70) . '.....';?></td>
											<td width="300px"><?php echo substr(strip_tags(html_entity_decode($it['description'], ENT_QUOTES, 'UTF-8')), 0, 70) . '.....';?></td>
											<td><?php echo number_format($it['price'],2);?></td>
											<td><?php echo number_format($it['cost'],2);?></td>
											<td><?php echo number_format($it['income'],2);?></td>
											<td><?php echo number_format($it['service_fee'],2);?></td>
											<td><?php echo number_format($it['system_fee'],2);?></td>
											<td><?php echo number_format($it['top_up'],2);?></td>
											<td><?php echo number_format($it['tools'],2);?></td>
											<td><?php echo number_format($it['shipping'],2);?></td>
											<td><?php echo number_format($it['tax'],2);?></td>
											<td><?php echo number_format($it['cv'],2);?></td>
											<td><?php echo number_format($it['quantity']);?></td>
											<td><?php echo number_format($it['item_profit_per'],2);?></td>
											<td><?php echo number_format($it['distributor_discount_per'],2);?></td>
											<td><?php echo number_format($it['reseller_discount_per'],2);?></td>												  
											<td><?php echo number_format($it['direct_referral'],2);?></td>												  
											<td><?php echo number_format($it['advance_payment'],2);?></td>												  
											<td><?php echo number_format($it['points'],2);?></td>												  
											<td><?php echo $it['sort'];?></td>
										</tr>
										<?php } ?>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="pagination"><?php echo $pagination; ?></div>							
					</div>
				</div>	
			</div>	
		</div>	
	</form>		
</div>
	
	<!--<div id="dialog-message" title="Message" style="display:none; width: 600px;">
	  <span id="msg"></span>
	</div>-->
	<!-- Modals -->
		  <div class="row">
			<div class="col-md-4">
			  <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
				<div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
				  <div class="modal-content bg-gradient-danger">
					<div class="modal-header">
					  <h6 class="modal-title" id="modal-title-notification">Message</h6>
					  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					  </button>
					</div>
					<div class="modal-body">
					  <div class="py-3 text-center">
						<i class="ni ni-bell-55 ni-3x"></i>
						<h4 class="heading mt-4"><span id="msg"></span></h4>
						<p><span id="msg"></span></p>
					  </div>
					</div>
					<div class="modal-footer">
					  <button type="button" class="btn btn-outline-warning text-white ml-auto" data-dismiss="modal">Ok</button>
					</div>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
<?php echo $footer; ?>
<script type="text/javascript"><!--

$(document).ready(function() {
	<?php if(isset($err_msg)) { ?>
		<?php if(!empty($err_msg)) { ?>
			// $('#msg').html("<?php echo $err_msg; ?>");
			// $(function() {
				// $("#dialog-message").dialog({
					// modal: true,
					// width: 600,
					// buttons: {
						// Ok: function() {
							// $(this).dialog("close");
						// }
					// }
				// });
			// });
			$('#msg').html("<?php echo $err_msg; ?>");			      
			$('#modal-notification').modal('show');
		<?php } ?>
	<?php } ?>
});


//--></script>							