<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<i class="fa fa-credit-card text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Approved Request</h6>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid row">
	<div class="col-md-12">
		<form action="" method="post" enctype="multipart/form-data" id="form">
			<input type="hidden" class="task" name="task" value="">
			<input type="hidden" id="product_id_sel" name="product_id_sel" value="">
			<input type="hidden" id="quantity_sel" name="quantity_sel" value="1">
			<input type="hidden" id="request_detail_id" name="request_detail_id" value="0">
			<input type="hidden" id="request_id" name="request_id" value="<?php if(isset($request_id)){ echo $request_id; } else { echo 0; }?>">
			<br><br>
			<?php if(!isset($request_id)) { ?>
				<div class="row card-margin">
					<div class="col-md-12">
						<div class="well">
							<div class="row mt-12">			
								<div class="col-md-2">
									<div class="form-group">	
										<input class="form-control input" type="text"  placeholder="Request ID" id="request_id_search" name="request_id_search">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">	
										<input class="form-control input" type="text" placeholder="Request Date From" id="datefrom" name="datefrom">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">	
										<input class="form-control input" type="text"  placeholder="Request Date To" id="dateto" name="dateto">
									</div>
								</div>
							</div>	
						</div>
						<div class="row">
							<div class="col-md-12 mb-3 text-right">	
								<button class="btn btn-warning mr-1" type="button" onclick="javascript:search()" ><i class="fa fa-search"></i> Search</button>
							</div>	
						</div>
					</div>
				</div>	
				<br>
				<div class="row card-margin">
					<div class="col-md-12">
						<div class="table-responsive" style="width: 100%;">				
							<table class="table table-hover table-bordered">
							  <thead>
								<tr>
								  <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
								  <td class="left">&nbspAction</td>
								  <td class="left">Status</td>
								  <td class="left">Request Id</td>
								  <td class="left">Requested By</td>								  
								  <td class="left">Payment Option</td>								  
								  <td class="left">Item & Qty Requested </td>
								  <td class="left">Cost of Requested Items</td>
								  <td class="left">Item & Qty Alloted</td>
								  <td class="left">Cost of Alloted Items</td>
								  <td class="left">Date Requested</td>
								  <td class="left">Approved By</td>								  
								  <td class="left">Date Approved</td>
								</tr>
							  </thead>
							  <tbody>
								<?php if (isset($approvedrequests)) { ?>
									<?php foreach ($approvedrequests as $areq) { ?>			
										<tr>
										  <td style="text-align: center;">
											<input type="checkbox" name="selected[]" value="<?php echo $areq['request_id'];?>"/>
										  </td>
										  <td class="left"><a class="btn btn-success" href="request/<?php echo $areq['request_id'];?>" ><i class="fa fa-eye"></i> View</a></td>
										  <td><?php echo $areq['status'];?></td>
										  <td>#<?php echo $areq['request_id'];?></td>
										  <td><?php echo $areq['created_by'];?> (<?php echo $areq['creator_fullname'];?>)</td>
										  <td><?php echo $areq['payment_option_desc'];?></td>
										  <td><?php echo $areq['qty'];?></td>
										  <td><?php echo number_format($areq['total_cost'],2);?></td>
										  <td><?php echo $areq['qty_on_hand'];?></td>
										  <td><?php echo number_format($areq['total_cost_on_hand'],2);?></td>
										  <td><?php echo $areq['date_added'];?></td>
										  <td><?php echo $areq['approved_by'];?></td>
										  <td><?php echo $areq['approval_date'];?></td>
											</tr>
									<?php } ?>
								<?php } ?>
							  </tbody>
							</table>
						</div>
						<div class="pagination"><?php echo $pagination; ?></div>
					</div>
				</div>				
			<?php } else { ?>
				<br>
				<div class="row">
					<div class="col-md-12 card-margin">
						<fieldset>
							<!--<input type="hidden" id="request_id" name="request_id" value="<?php //if(isset($request_id)) { echo $request_id; } else { echo 0; } ?>" >-->
							<div class="panel panel-default">
								<div class="panel-heading right-header">
									<h3 class="panel-title"><i class="fa fa-list"></i> Request Details <?php if(isset($request_id)) { echo " ID No.: " . $request_id; } else { echo " ID No.: " . 0; } ?></h3>
								</div>
								<div class="panel-body">
									<div class="form-group row">
										<label for="customerAddress" class="col-sm-2">Total Quantity: </label>
										<div class="col-sm-4">
											<input type="text" class="form-control" placeholder="Total Quantity" value="<?php if(isset($request_details['qty'])) { echo $request_details['qty']; } else { echo 0; } ?>" readonly="readonly">
										</div>
										<label for="customerAddress" class="col-sm-2">Status: </label>
										<div class="col-sm-4">
											<input type="text" class="form-control" placeholder="Status" value="<?php if(isset($request_details['description'])) { echo $request_details['description']; } else { echo 'Requested'; } ?>" readonly="readonly">
										</div>
									</div>

									<div class="form-group row">
										<label for="customerAddress" class="col-sm-2">Payment Option: </label>
										<div class="col-sm-4">
											<input type="text" class="form-control" placeholder="Payment Option" value="<?php if(isset($request_details['payment_option_desc'])) { echo $request_details['payment_option_desc']; } ?>" readonly="readonly">
										</div>

										<label for="customerAddress" class="col-sm-2">Total Items: </label>
										<div class="col-sm-4">
											<input type="text" class="form-control" placeholder="Total Items" value="<?php echo $totals['qty']; ?>" readonly="readonly">
										</div>
									</div>

									<div class="form-group row">
										<label for="customerAddress" class="col-sm-2">Total Allocated Items: </label>
										<div class="col-sm-4">	
											<input type="text" class="form-control" placeholder="Total Allocated Items" value="<?php echo $totals['qtyoh']; ?>" readonly="readonly">
										</div>

										<label for="customerAddress" class="col-sm-2">Total Amount to be Paid: </label>
										<div class="col-sm-4">
											<input type="text" class="form-control" placeholder="Total Amount to be Paid" value="Php <?php echo  number_format($totals['totalAmount'],2); ?>" readonly="readonly">
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading right-header">
									<h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> Request Actions</h3>
								</div>
								<div class="panel-body">
									<div class="col-md-12">
										<?php if(isset($request_details['request_id'])) { ?> 
											<?php if($request_details['status'] == 72) { ?>
												<?php if($this->user->getUserGroupId()== 39 or $this->user->getUserGroupId()== 43) { ?>
													<input type="hidden" id="branch_from" name="branch_from" value="<?php if(isset($branch_id)){echo $branch_id;} ?>">	
													<br>
													<div class="row">
														<div class="col-md-2"></div>
														<div class="col-md-2">
															<label>Items</label>
														</div>				
														<div class="col-md-6" id="item_div">
															<select class="form-control input" id="product_id" name="product_id" onChange="selectProduct(this.value);">
																<option value="" selected>ALL</option>
																<?php if(isset($items)) { ?>
																	<?php foreach($items as $item) { ?>
																		<option value="<?php echo $item['item_id']?>"><?php echo $item['item_name']."(Php ".number_format($item['price']).")"?></option>
																	<?php } ?>
																<?php } ?>
															</select>						
														</div>
														<div class="col-md-2"></div>
													</div>		
													<br>
													<div class="row">
														<div class="col-md-2"></div>
														<div class="col-md-2">
															<label>Quantity</label>
														</div>				
														<div class="col-md-6" id="item_div">
															<input class="form-control input" type="number" id="quantity" name="quantity" value="0" min="1" onChange="selectQuantity(this.value);">					
														</div>
														<div class="col-md-2"></div>
													</div>
													<br>
													<div class="row">
														<div class="col-md-12">
															<button class="btn btn-info" id="addItemBtn" type="button" onclick="javascript: additem();"><i class="fa fa-plus"></i> Add Item</button>
														</div>
													</div>
												<?php }?>
												<?php if($this->user->getUserGroupId()== 44) { ?>
													<div class="row">
														<div class="col-md-12">
															<input class="btn btn-info" id="addItemBtn" type="button" value="Allocate Inventory" onclick="javascript: performTask2('allocateInventory', 'Are you sure you the inventory allocated is correct?');">
														</div>
													</div>
												<?php }?>
											<?php } else if (sizeOf($request_items) > 0 && $request_details['status'] == 75) { ?>
												<?php if($request_details['paid_flag'] == 0 and $request_details['payment_option'] == 153 and $request_details['ext'] == '') { ?>
													<div class="row">
														<div class="col-md-6">				
															<label><b>Proof of Payment</b></label>
															<input type="file" name="proof_of_payment" id="proof_of_payment" value="" class="form-control">
															<br>	
														</div>
														<!--==========================================================-->
														<div class="col-md-6">
															<div class="card-body">	
																<div class="card mt-2">
																	<div class="card-header bg-turqouise">
																		<h3 class="h3 text-dark"><i class="fa fa-money"></i> For this inventory request to proceed upload deposit payment from the following Mode of Payment:</h3>
																	</div>
																	<div class="card-block">
																		<div class="ml-3 mt-3 mr-3">
																			<div class="col-md-12">
																				<div class="row">
																					<table class="list">
																						<thead>
																							<tr>
																								<td>Remittance Payment Center</td>
																							</tr>
																						</thead>						
																						<tbody>
																							<tr>
																								<td>
																										<h4><b>Palawan Express / LBC</b></h4>
																										<h5><b>Name</b>: APRIL JOY SUSON</h5>
																										<h5><b>Phone</b>: 0910-8672-352</h5>
																										<h5><b>Address</b>: Door 3, G.A. Esteban Bldg, <br>
																											   Lacson St, Brgy 19, Bacolod City, Negros Occidental 6100</h5>
																										<br>				
																								</td>
																							</tr>
																						</tbody>					
																					</table>
																				</div>
																			</div>						   	
																		</div>
																	</div>							
																</div>
															</div>
														</div>
														<!--==========================================================-->
													</div>
													<div class="row">
														<button class="btn btn-success" type="button" onclick="javascript: performTask2('uploadpayment', 'Please make sure the proof of payment is clear and correct.');"><i class="fa fa-check"></i> Upload Payment</button>
													</div>										
												<?php } else { ?>
													<?php if($request_details['payment_option'] == 154) { ?>
														<div class="row">
															<div class="col-md-12">	
																<center>
																	<label><b>Payment via eWallet will automatically decuct payment from requestor if approved.</b></label>	
																</center>
															</div>
														</div>
													<?php }  ?>
													<?php if($this->user->getUserGroupId()== 44 ){ ?>
														<br>
														<div class="row">
															<?php if($request_details['payment_option'] == 153) { ?>
																<div class="col-md-3">
																	<button class="btn btn-danger" type="button" onclick="javascript: performTask2('reuploadpayment', 'Are you sure you want to request for reupload?');"><i class="fa fa-check"></i> Request for Re-upload</button>
																</div>
																<br><br>
															<?php }?>
															<div class="col-md-3">
																<button class="btn btn-success" type="button" onclick="javascript: performTask2('approve', 'Are you sure you want to approve this request?');"><i class="fa fa-check"></i> Approve</button>
															</div>
														</div>
														<br><br>
													<?php }?>
												<?php }?>
											<?php } ?>
											<?php if(isset($request_details['ext'])) { ?>
												<?php if($request_details['ext'] != '') { ?>
													<div class="row">
														<div class="col-md-3"></div>
														<div class="col-md-6">
															<img width="100%" class="img-responsive" src="requestimages/requestimages<?php echo $request_details['request_id'].".".$request_details['ext']; ?>">
														</div>
														<div class="col-md-3"></div>
													</div>
													<br>
												<?php } ?>
											<?php } ?>
										<?php }?>
									</div>	
								</div>
							</div>
						</fieldset>
					</div>
				</div>
		
				<br>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive" style="width: 100%;" id="customItemFields">				
							<table class="table table-bordered table-hover">
							  <thead>
								<tr>
								  <td class="left">Category</td>
								  <td class="left">Item</td>  						  
								  <td class="left">Quantity</td>
								  <td class="left">Amount Per Item</td>										  
								  <td class="left">Date Added</td>
								  <td class="left">Allocated Quantity</td>
								  <td class="left">Allocated Quantity Amount</td>
								  <?php if($request_details['status'] == '72') { ?>
									<td class="left">Action</td>
								  <?php } ?>
								</tr>
							  </thead>
							  <tbody id="tr_item_row_1">	
								<?php if (isset($request_items)) { ?>
									<?php foreach ($request_items as $item) { ?>			
										<tr>										
										  <td><?php echo $item['category'];?></td>
										  <td><?php echo $item['item'];?></td>
										  <td><?php echo $item['qty'];?></td>
										  <td>Php <?php echo number_format($item['amount'],2);?></td>
										  <td><?php echo $item['date_added'];?></td>
										  <?php if($this->user->getUserGroupId()== 44 && $request_details['status'] == '72'){?>
											<td>
												<input type="number" class="form-control" id="qty_on_hand<?php echo $item['request_detail_id'];?>" 
													name="qty_on_hand<?php echo $item['request_detail_id'];?>" value="<?php echo  $item['qty_on_hand']; ?>" required />
											</td>
										  <?php } else if($this->user->getUserGroupId()==44 && $request_details['status'] == '1' && $request_details['created_by'] != $this->user->isLogged()){?>
											<td>
												<input type="number" class="form-control" id="qty_on_hand<?php echo $item['request_detail_id'];?>" 
													name="qty_on_hand<?php echo $item['request_detail_id'];?>" value="<?php echo  $item['qty_on_hand']; ?>" required />
											</td>	
										  <?php }else{?>
												<td><input type="number" class="form-control" id="qty_on_hand<?php echo $item['request_detail_id'];?>" 
													name="qty_on_hand<?php echo $item['request_detail_id'];?>" value="<?php echo  $item['qty_on_hand']; ?>" disabled /></td>
										  <?php }?>
										  <td>
											<input type="text" class="form-control" id="allocatedQtyAmount" name="allocatedQtyAmount" 
											value="<?php echo number_format(($item['amount']*$item['qty_on_hand']),2) ;?>" disabled />
										</td>
										<?php if($request_details['status'] == '72') { ?>	
										<td class="left">					  
												<button class="btn btn-danger" type="button" onclick="deleteitem(<?php echo $item['request_detail_id'];?>, '<?php echo $item['item'];?>');"><i class="fa fa-trash"></i> Remove Item</button>													  
										</td>
										 <?php }?>
										</tr>
									<?php } ?>
								<?php } ?>
							  </tbody>
							</table>
						</div>
						<div class="pagination"><?php echo $pagination; ?></div>
					</div>
				</div>
			<?php } ?>
		</form>
	</div>
</div>
<!--==============================================================================================================================-->
<div id="dialog-message" title="Message" style="display:none; width: 400px;">
  <span id="msg"></span>
</div>

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
							<h4 class="heading mt-4"><span id="notif-msg"></span></h4>
						 </div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
			<div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
				<div class="modal-content bg-gradient-danger">
					<div class="modal-header">
						<h6 class="modal-title" id="modal-title-notification">Choose Payment Option</h6>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						 <div class="py-3 text-center">
							<i class="ni ni-bell-55 ni-3x"></i>
							<h4 class="heading mt-4"><span id="modal-msg"></span></h4>
							<form action="" method="post" enctype="multipart/form-data" id="form-of-modal">
								<div class="create_form hidden" class="row">
									<div class="col-md-12">
										<input type="hidden" class="task" name="task" value="">
										<select class="form-control" id="payment_option" name="payment_option">
											<option value="0">Choose a Payment Option</option>
											<option value="153">Deposit</option>
											<option value="154">Ewallet</option>
										</select>
									</div>
								</div>
							</form>
						 </div>
					</div>
					<div class="modal-footer">
						<div id="div_buttons"></div>
						<button type="button" class="btn btn-warning text-white ml-auto" data-dismiss="modal">Close</button>
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
			var msg = "<?php echo $err_msg; ?>";
			swal("Good Job!", msg, "success");
		<?php } ?>
	<?php } ?>
});

function exportToCSV(){
	$('#form').attr('action', "transferExportToCSV"); 
	$('#form').submit();
}

function search() {
	$('.task').val('search');
	$('#form').attr('action', "approvedrequest"); 
	$('#form').submit();
}

function clearFilter() {
	$('.task').val('clear');
	$('#form').attr('action', "request"); 
	$('#form').submit();
}

function selectSupplier(){
	$(function() {
		$( "#dialog-select-supplier" ).dialog({
		  modal: true,
		  width: 600,
		  buttons: {
			"Select Supplier": function() {
				$(function() {
					$( "#dialog-message" ).dialog({
					  modal: true,
					  width: 600,
					  buttons: {
						Ok: function() {
							$( this ).dialog( "close" );
						}			
					  }
					});
				});	
			}				
		  }
		});
	});	
}

function additem() {
	if($('#product_id_sel').val() == "") {
		var msg = "You must select an item first.";
		$('#notif-msg').html(msg);
		$('#modal-notification').modal('show');						
	} else {
		$('.task').val("additem");
		<?php if(isset($request_id)) { ?>
			$('#form').attr('action', 'request/<?php echo $request_id; ?>'); 
			$('#form').submit();
		<?php } ?>
	}
}

function deleteitem(request_detail_id, description) {
	var msg = "Are you sure you want to remove "+description+" from transfer request?";
	$('#modal-msg').html(msg);
	$('#modal-form').modal('show');
	$('.create_form').hide();
	$('#div_buttons').html('<button type="button" class="btn btn-success text-white ml-auto" data-dismiss="modal" onclick="javascript: sumbitRemoveItem('+request_detail_id+');">Proceed</button>');
}

function sumbitRemoveItem(request_detail_id) {	
	if(request_detail_id == 0) {
		$('#notif-msg').html("No item to delete.");
		$('#modal-notification').modal('show');
	} else {
		$('#request_detail_id').val(request_detail_id);
		$('.task').val("deleteitem");
		<?php if(isset($request_id)) { ?>
			$('#request_id').val(<?php echo $request_id; ?>);
			$('#form').attr('action', 'request/<?php echo $request_id; ?>'); 
			$('#form').submit();
		<?php } ?>
	}
}

function approve() {
	var msg = "Are you sure you want approve this inventory transfer request?";
	$('#msg').html(msg);		
	$(function() {
		$( "#dialog-message" ).dialog({
		  modal: true,
		  width: 600,
		  buttons: {
			Ok: function() {
				<?php if(isset($request_id)) { ?>
					$('.task').val("approve");					
					$('#form').attr('action', 'request/<?php echo $request_id; ?>'); $('form').submit();
				<?php } ?>					
				$( this ).dialog( "close" );
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}			
		  }	
		});
	});
}

function checkBranch(){
	var branchTo = $('#branch_to').val();
	console.log(branchTo);
	if(branchTo !== ""){
		$('#addItemBtn').prop('disabled',false);
	}else{
		$('#addItemBtn').prop('disabled',true);
	}
}

function selectProduct(product_id) {
	$('#product_id_sel').val(product_id);
}

function selectQuantity(quantity) {
	if(quantity > 0) {
		$('#quantity_sel').val(quantity);
	} else {
		alert("Quantity should a positive number, more than 0.");
		$('#quantity_sel').val("0");
		$('#quantity').val("0");
	}
}

function creatRequest() {
	$('#modal-msg').html("Please choose a payment option.");
	$('#modal-form').modal('show');
	$('.create_form').show();
	$('#div_buttons').html('<button type="button" class="btn btn-success text-white ml-auto" data-dismiss="modal" onclick="javascript: sumbitRequestInventory();">Proceed</button>');
}

function performTask2(task, msg) {
	$('#modal-msg').html(msg);
	$('#modal-form').modal('show');
	$('.create_form').hide();
	$('#div_buttons').html('<button type="button" class="btn btn-success text-white ml-auto" data-dismiss="modal" onclick="javascript: sumbitPerformTask(\''+task+'\');">Proceed</button>');
}

function sumbitPerformTask(task) {
	$('.task').val(task);
	<?php if(isset($request_id)) { ?>
		$('#form').attr('action', 'request/<?php echo $request_id; ?>'); 
		$('#form').submit();
	<?php } else { ?>
		$('#form').attr('action', 'request'); 
		$('#form').submit();
	<?php } ?>
}

function sumbitRequestInventory() {
	var payment_option = $('#payment_option').val();
	
	if(payment_option == 0) {
		$('#notif-msg').html("Please choose a payment option.");
		$('#modal-notification').modal('show');
	} else {
		performTask('requestinventory');
	}
}


function performTask(task) {
	$('.task').val(task);
	<?php if(isset($request_id)) { ?>					
		$('#form-of-modal').attr('action', 'request/<?php echo $request_id; ?>'); 
	<?php } else { ?>
		$('#form-of-modal').attr('action', 'request'); 
	<?php } ?>
	$('#form-of-modal').submit();
}

//--></script>