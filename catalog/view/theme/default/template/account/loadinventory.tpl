<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
			<i class="fa fa-shopping-cart ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp; Load Inventory</h6>
		</div>
	  </div>
	</div>
  </div>
</div>
<div class="container-fluid mt--6">
	<div class="row">	
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">		
					<form action="" method="post" enctype="multipart/form-data" id="form">
						<input type="hidden" id="task" name="task" value="">
						<input type="hidden" id="product_id_sel" name="product_id_sel" value="">
						<input type="hidden" id="quantity_sel" name="quantity_sel" value="1">
						<input type="hidden" id="load_inventory_detail_id" name="load_inventory_detail_id" value="0">
						<input type="hidden" id="session_id" name="session_id" value="<?php echo $session_id;?>">

						<?php if(!isset($load_inventory_id)) { ?>
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-outline-upper text-white pull-right" type="button"  onclick="javascript: performTask('createInventory');"><i class="fa fa-plus"></i> Create Inventory</button>
								</div>
							</div>
							<br/>
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive" style="width: 100%;">				
										<table class="table table-hover table-bordered">
										  <thead>
											<tr>
											  <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
											  <td class="left">Inventory Id</td>								  
											  <td class="left">Total Cost</td>								  
											  <td class="left">Total Quantity</td>
											  <td class="left">Status</td>
											  <td class="left">Created By</td>								  
											  <td class="left">Date Added</td>
											  <td class="left">&nbsp;</td>
											</tr>
										  </thead>
										  <tbody>
											<?php if (isset($loadinventories)) { ?>
												<?php foreach ($loadinventories as $ol) { ?>			
													<tr>
													  <td style="text-align: center;">
														<input type="checkbox" name="selected[]" value="<?php echo $ol['load_inventory_id'];?>"/>
													  </td>
													  <td><?php echo $ol['load_inventory_id'];?></td>
													  <td>Php <?php echo number_format($ol['total_amount'],2);?></td>
													  <td><?php echo $ol['total_qty'];?></td>
													  <td><?php echo $ol['status_desc'];?></td>
													  <td><?php echo $ol['created_by'];?></td>
													  <td><?php echo $ol['date_added'];?></td>
													  <?php if ($ol['status']==='114') { ?>
														<td class="left"><a class="btn btn-info" href="loadinventory/<?php echo $ol['load_inventory_id'];?>"><i class="fa fa-pencil"></i> Modify</a></td>
													  <?php }else { ?>
														<td class="left"><a class="btn btn-success" href="loadinventory/<?php echo $ol['load_inventory_id'];?>"><i class="fa fa-eye"></i> View</a></td>
													  <?php } ?>			  
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
							<div class="row">
								<div class="col-md-12">
									<fieldset>
										<input type="hidden" id="load_inventory_id" name="load_inventory_id" value="<?php if(isset($load_inventory_id)) { echo $load_inventory_id; } else { echo 0; } ?>" >
										<div class="panel panel-default">
											<div class="panel-heading right-header">
												<div class="card-title"><h2>Inventory Details <?php if(isset($load_inventory_id)) { echo "ID No.: " . $load_inventory_id; } else { echo "ID No.: " . 0; } ?>
												</h2></div>
											</div>
											<hr/>
											<div class="panel panel-default">
												<div class="form-group row">
													<label for="customerAddress" class="col-sm-2">Total Amount: </label>
													<div class="col-sm-4">
														<input type="text" class="form-control" placeholder="Total Amount" value="Php <?php if(isset($purchase_details['total_amount'])) { echo $purchase_details['total_amount']; } else { echo 0.00; } ?>" readonly="readonly">
													</div>

													<label for="customerAddress" class="col-sm-2">Total Quantity: </label>
													<div class="col-sm-4">
														<input type="text" class="form-control" placeholder="Total Quantity" value="<?php if(isset($purchase_details['total_qty'])) { echo $purchase_details['total_qty']; } else { echo 0; } ?>" readonly="readonly">
													</div>
												</div>

												<div class="form-group row">
													<label for="customerAddress" class="col-sm-2">Status: </label>
													<div class="col-sm-4">
														<input type="text" class="form-control" placeholder="Status" value="<?php if(isset($purchase_details['status'])) { echo $purchase_details['stats']; } else { echo 'Ongoing'; } ?>" readonly="readonly">
													</div>
												</div>
											</div>
										</div>
										<hr/>
										<div class="panel panel-default">
											<div class="panel-heading right-header">
												<div class="card-title">Inventory Actions</div>
											</div>
											<div class="panel-body">
												<?php if(isset($purchase_details['load_inventory_id'])) { 
													if($purchase_details['status'] == 114) {?>
														<button class="btn btn-warning" type="button" onclick="javascript: additem();"><i class="fa fa-plus"></i> Add Item</button>
														<button class="btn btn-danger" type="button" onclick="javascript: cancelinventory();"><i class="fa fa-times"></i> Cancel</button>
														<button class="btn btn-primary" type="button" onclick="javascript: approve();"><i class="fa fa-check"></i> Approve</button>
													<?php }?>
												<?php }?>
											</div>
										</div>
										<hr/>
									</fieldset>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive" style="width: 100%;" id="customItemFields">				
										<table class="table table-bordered table-hover">
										  <thead>
											<tr>
											  <td class="left">Category</td>
											  <td class="left">Item</td>					  
											  <td class="left">Quantity</td>	
											  <td class="left">Amount</td>											  
											  <td class="left">Date Added</td>
											  <?php if(isset($purchase_details['load_inventory_id'])) { 
												if($purchase_details['status'] == 114) {?>
													<td class="left">&nbsp;</td>
												<?php }
											  }?>
											  
											</tr>
										  </thead>
										  <tbody id="tr_item_row_1">	
											<?php if (isset($purchase_items)) { ?>
											<?php foreach ($purchase_items as $item) { ?>			
												<tr>										
												  <td><?php echo $item['category'];?></td>
												  <td><?php echo $item['item'];?></td>
												  <td><?php echo $item['total_qty'];?></td>
												  <td>Php <?php echo number_format($item['cost'],2);?></td>
												  <td><?php echo $item['date_added'];?></td>
												  <?php if(isset($purchase_details['load_inventory_id'])) { 
													if($purchase_details['status'] == 114) {?>
														<td class="left">
															<button class="btn btn-danger" type="button" onclick="javascript: removeItem(<?php echo $item['load_inventory_detail_id'];?>, <?php echo $item['item_id'];?>, '<?php echo $item['item'];?>');"><i class="fa fa-trash"></i> Remove Item</button>
														</td>
													<?php }
												  }?>
												  
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
						
						
<!--=================================================================================================-->
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
							<!--Modal proceed-->
							<div class="col-md-4">
							  <div class="modal fade" id="modal-proceed" tabindex="-1" role="dialog" aria-labelledby="modal-proceed" aria-hidden="true">
								<div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
								  <div class="modal-content bg-gradient-danger">
									<div class="modal-header">
									  <h6 class="modal-title" id="modal-title-notification">You are about to proceed.</h6>
									  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">×</span>
									  </button>
									</div>
									<div class="modal-body">
									  <div class="py-3 text-center">
										<i class="ni ni-bell-55 ni-3x"></i>
										<h4 class="heading mt-4"><span id="msg"></span></h4>
										<p><span id="msg_proceed"></span></p>
									  </div>
									</div>
									<div class="modal-footer">
									  <div id="div_buttons"></div>	
									 <div class="row">
										<button type="button" class="btn btn-white ml-auto" data-dismiss="modal">Close</button>
									</div>
									</div>
								  </div>
								</div>
							  </div>
							</div>
							<!--Modal proceed-->
							<!--Delete Item Modal-->
							<div class="col-md-4">
							  <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-delete" aria-hidden="true">
								<div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
								  <div class="modal-content bg-gradient-danger">
									<div class="modal-header">
									  <h6 class="modal-title" id="modal-title-notification">You are about to proceed.</h6>
									  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">×</span>
									  </button>
									</div>
									<div class="modal-body">
									  <div class="py-3 text-center">
										<i class="ni ni-bell-55 ni-3x"></i>
										<h4 class="heading mt-4"><span id="msg"></span></h4>
										<p><span id="msg_delete"></span></p>
									  </div>
									</div>
									<div class="modal-footer">
									  <div id="div_buttons"></div>	
									  <div class="row">
											<button type="button" class="btn btn-danger text-white ml-auto" onclick="javascript: performTaskDelete('removeItem',<?php echo $item['load_inventory_detail_id'];?>, <?php echo $item['item_id'];?>, '<?php echo $item['item'];?>');">Delete Item</button>
											<button type="button" class="btn btn-white ml-auto" data-dismiss="modal">Cancel</button>
										</div>
									</div>
								  </div>
								</div>
							  </div>
							</div>
							<!--Delete Item Modal-->
							<!--Add Item Modal-->
							<div class="col-md-4">
							  <div class="modal fade" id="dialog-additem" tabindex="-1" role="dialog" aria-labelledby="dialog-additem" aria-hidden="true">
								<div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
								  <div class="modal-content bg-gradient-info">
									<div class="modal-header">
									  <h6 class="modal-title" id="modal-title-notification">Add Item Form</h6>
									  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">×</span>
									  </button>
									</div>
									<div class="modal-body">
									  <div class="py-3 text-center">
										<i class="ni ni-bell-55 ni-3x"></i>
										<h4 class="heading mt-4"><span id="msg"></span></h4>
										<p><span>Kindly fill up the form:</span></p><br>
										
											<div class="row">
												<div class="col-md-2"></div>
												<div class="col-md-2">
													<label>Items:</label>
												</div>				
												<div class="col-md-6" id="item_div">
													<select class="form-control input" id="product_id" name="product_id" onChange="selectProduct(this.value);">
														<option value="" selected>Select Item...</option>
														<?php if(isset($items)) { ?>
														<?php foreach($items as $item) { ?>
															<option value="<?php echo $item['item_id']?>"><?php echo $item['item_name']."(Php ".number_format($item['cost']).")"?></option>
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
													<label>Quantity:</label>
												</div>				
												<div class="col-md-6" id="item_div">
													<input class="form-control input" type="number" id="quantity" name="quantity" value="1" min="1" onChange="selectQuantity(this.value);">					
												</div>
												<div class="col-md-2"></div>
											</div>
									  </div>
									</div>
									<div class="modal-footer">
									  <div id="div_buttons"></div>
										<div class="row">
											<button type="button" class="btn btn-info text-white ml-auto" onclick="javascript: saveItem('save');">Add Item</button>
											<button type="button" class="btn btn-danger text-white ml-auto" data-dismiss="modal">Cancel</button>
										</div>
									</div>
								  </div>
								</div>
							  </div>
							</div>
							<!--Add Item Modal-->
						  </div>						
<!--=================================================================================================-->						
					</form>
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

function openMerchants(){
	$(function() {
		$( "#dialog-select-supplier" ).dialog({
		  modal: true,
		  width: 600,
		  buttons: {
			"Update Supplier": function() {
				updateMerchant();
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}			
		  }
		});
	});		
}



function additem() {
		
		
		$(function() {
			$('#dialog-additem').modal('show');
		});		
}

function saveItem(task) {
	var product_id_sel = $("#product_id_sel").val();
	var quantity_sel = $("#quantity_sel").val();
	var proceed = 1;
	var msg = "";


	if(product_id_sel == ""){
		msg += "Select item first. <br>";
		proceed = 0;
	}

	if(quantity_sel == ""){
		msg += "Quantity is mandatory. <br>";
		proceed = 0;
	}

	if(proceed == 0){
	  msg = "Please check the following errors: <br><br>" + msg;
	  swal ( "Oops!",  msg,  "error" );
	}else{
		$('#task').val('saveItem');
		// swal ( "Good Job!",  "Succesfully added to item.",  "success" );
		<?php if(isset($load_inventory_id)) { ?>
			$('form').attr('action', 'loadinventory/<?php echo $load_inventory_id; ?>'); 
			$('form').submit();
		<?php } ?>
	}
}

function removeItem(load_inventory_detail_id, item, item_desc) {
		
		
			$('#msg_delete').html("Are you sure you want to remove "+item_desc+"?");
			$('#div_buttons').html("<button type=\"button\" onclick=\"performTaskDelete('"+load_inventory_detail_id+"," +item+"," +item_desc+"');\" class=\"btn btn-white\">Yes, Proceed.</button>");		
			$('#modal-delete').modal('show');
			
}

function approve() {
		
		
		$('#msg_proceed').html("Are you sure you want to approve this inventory?");
		$('#div_buttons').html("<button type=\"button\" onclick=\"performTask('approve');\" class=\"btn btn-danger\">Yes, Approve it.</button>");		
		$('#modal-proceed').modal('show');
}

function cancelinventory() {
		// var msg = "Are you sure you want cancel this inventory?";
		// $('#msg').html(msg);		
		// $(function() {
			// $( "#dialog-message" ).dialog({
			  // modal: true,
			  // width: 600,
			  // buttons: {
				// Ok: function() {
					// <?php if(isset($load_inventory_id)) { ?>
						// $('#task').val("cancelinventory");					
						// $('form').attr('action', 'loadinventory'); $('form').submit();
					// <?php } ?>					
					// $( this ).dialog( "close" );
				// },
				// Cancel: function() {
					// $( this ).dialog( "close" );
				// }			
			  // }	
			// });
		// });
		$('#msg_proceed').html("Are you sure you want to cancel this inventory?");
		$('#div_buttons').html("<button type=\"button\" onclick=\"performTask('cancelinventory');\" class=\"btn btn-danger\">Yes, Cancel it.</button>");		
		$('#modal-proceed').modal('show');
}

// function performTaskCancel(task) {
	// $('#task').val("cancelinventory");
	// $('form').attr('action', 'loadinventory'); 
	// $('form').submit();
// }

// function selectSupplier(supplierId){
	// $('#supplier_id').val(supplierId);
// }

function selectProduct(product_id) {
	$('#product_id_sel').val(product_id);
}

function selectQuantity(quantity) {
	$('#quantity_sel').val(quantity);
}

function performTask(task) {
	$('#task').val(task);
	$('form').attr('action', 'loadinventory'); 
	$('form').submit();
}

function performTaskDelete(task, load_inventory_detail_id, item, item_desc) {
	// alert ("performTaskDelete");
	$('#load_inventory_detail_id').val(load_inventory_detail_id);
	$('#product_id_sel').val(item);
	$('#task').val(task);					
	$('form').attr('action', 'loadinventory'); 
	$('form').submit();
}

//--></script>							