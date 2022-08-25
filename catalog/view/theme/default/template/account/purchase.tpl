<?php echo $header; ?>
<div class="panel panel-default">
	<div class="panel-heading right-header">
		<h3 class="panel-title"><i class="fa fa-shopping-cart"></i> Purchase </h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<form action="" method="post" enctype="multipart/form-data" id="form">
					<input type="hidden" id="task" name="task" value="">
					<input type="hidden" id="supplier_id" name="supplier_id" value="">
					<input type="hidden" id="product_id_sel" name="product_id_sel" value="">
					<input type="hidden" id="quantity_sel" name="quantity_sel" value="1">
					<input type="hidden" id="purchase_det_id" name="purchase_det_id" value="0">
					<input type="hidden" id="session_id" name="session_id" value="<?php echo $session_id;?>">

					<?php if(!isset($purchase_id)) { ?>
					<br />
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-primary pull-right" type="button"  onclick="javascript: performTask('createpurchase');"><i class="fa fa-plus"></i> Create Purchase</button>
								<br />
								<br />
								<br />
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
									<div class="table-responsive" style="width: 100%;">				
										<table class="table table-hover table-bordered">
										  <thead>
											<tr>
											  <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
											  <td class="left">Purchase Id</td>								  
											  <td class="left">Total Cost</td>								  
											  <td class="left">Total Quantity</td>
											  <td class="left">Status</td>
											  <td class="left">Created By</td>								  
											  <td class="left">Date Added</td>
											  <td class="left">&nbsp;</td>
											</tr>
										  </thead>
										  <tbody>
											<?php if (isset($purchases)) { ?>
												<?php foreach ($purchases as $ol) { ?>			
													<tr>
													  <td style="text-align: center;">
														<input type="checkbox" name="selected[]" value="<?php echo $ol['cart_id'];?>"/>
													  </td>
													  <td><?php echo $ol['purchase_id'];?></td>
													  <td>Php <?php echo number_format($ol['total'],2);?></td>
													  <td><?php echo $ol['total_qty'];?></td>
													  <td><?php echo $ol['status'];?></td>
													  <td><?php echo $ol['created_by'];?></td>
													  <td><?php echo $ol['date_created'];?></td>
													  <?php if ($ol['status']==='70') { ?>
														<td class="left"><a class="btn btn-info" href="index.php?route=account/purchase&purchase_id=<?php echo $ol['purchase_id'];?>"><i class="fa fa-pencil"></i> Modify</a></td>
													  <?php }else { ?>
														<td class="left"><a class="btn btn-success" href="index.php?route=account/purchase&purchase_id=<?php echo $ol['purchase_id'];?>"><i class="fa fa-eye"></i> View</a></td>
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
						<br>
						<div class="row">
							<div class="col-md-12">
								<fieldset>
									<input type="hidden" id="purchase_id" name="purchase_id" value="<?php if(isset($purchase_id)) { echo $purchase_id; } else { echo 0; } ?>" >
									<div class="panel panel-default">
										<div class="panel-heading right-header">
											<h3 class="panel-title"><i class="fa fa-list"></i> Purchase Details <?php if(isset($purchase_id)) { echo "ID No.: " . $purchase_id; } else { echo "ID No.: " . 0; } ?></h3>
										</div>
										<div class="panel-body">

											<div class="form-group row">
												<label for="customerAddress" class="col-sm-2">Total Amount: </label>
												<div class="col-sm-4">
													<input type="text" class="form-control" placeholder="Total Amount" value="Php <?php if(isset($purchase_details['total'])) { echo $purchase_details['total']; } else { echo 0.00; } ?>" readonly="readonly">
												</div>

												<label for="customerAddress" class="col-sm-2">Total Quantity: </label>
												<div class="col-sm-4">
													<input type="text" class="form-control" placeholder="Total Quantity" value="<?php if(isset($purchase_details['total_qty'])) { echo $purchase_details['total_qty']; } else { echo 0; } ?>" readonly="readonly">
												</div>
											</div>

											<div class="form-group row">
												<label for="customerAddress" class="col-sm-2">Status: </label>
												<div class="col-sm-4">
													<input type="text" class="form-control" placeholder="Status" value="<?php if(isset($purchase_details['status'])) { echo $purchase_details['description']; } else { echo 'Ongoing'; } ?>" readonly="readonly">
												</div>

												<label for="customerAddress" class="col-sm-2">Supplier: </label>
												<div class="col-sm-4">
													<input type="text" class="form-control" placeholder="Supplier" value="<?php if(isset($purchase_details['supplier'])) { echo $purchase_details['supplier']; } ?>" readonly="readonly">
												</div>
											</div>

										</div>
									</div>
									<div class="panel panel-default">
										<div class="panel-heading right-header">
											<h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> Purchase Actions</h3>
										</div>
										<div class="panel-body">
											<?php if(isset($purchase_details['purchase_id'])) { 
												if($purchase_details['status'] == 70) {?>
													<!--<button class="btn btn-success" type="button" onclick="javascript: openSuppliers();"><i class="fa fa-floppy-o"></i> Update Supplier</button>-->
													<button class="btn btn-warning" type="button" onclick="javascript: additem();"><i class="fa fa-plus"></i> Add Item</button>
													<?php if(sizeOf($purchase_items)>0) {?>
														<a class="btn btn-info" href="index.php?route=account/trackpurchase&ref=<?php echo $purchase_details['purchase_reference']; ?>"> <i class="fa fa-upload"></i> Upload Proof of Payment</a>
													<?php } ?>
												<?php }else if($purchase_details['status'] == 71) {?>
													<button class="btn btn-primary" type="button" onclick="javascript: approve();"><i class="fa fa-check"></i> Approve</button>
													<a class="btn btn-info" href="trackpurchase/<?php echo $purchase_details['purchase_reference']; ?>" > <i class="fa fa-eye"></i> View Proof of Payment</a>
												<?php }?>
											<?php }?>
										</div>
									</div>
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
											  <?php if(isset($purchase_details['purchase_id'])) { 
												if($purchase_details['status'] == 70) {?>
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
												  <td><?php echo $item['item_name'];?></td>
												  <td><?php echo $item['total_qty'];?></td>
												  <td>Php <?php echo number_format($item['cost'],2);?></td>
												  <td><?php echo $item['date_added'];?></td>
												  <?php if(isset($purchase_details['purchase_id'])) { 
													if($purchase_details['status'] == 70) {?>
														<td class="left">
															<button class="btn btn-danger" type="button" onclick="deleteitem(<?php echo $item['purchase_detail_id'];?>, '<?php echo $item['item_name'];?>');"><i class="fa fa-trash"></i> Remove Item</button>
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
					<div id="dialog-message" title="Message" style="display:none; width: 400px;">
					  <span id="msg"></span>
					</div>
					<div id="dialog-select-supplier" title="Message" style="display:none; width: 400px;">
					  <span id="msg"></span>
						<div class="row">
							<div class="col-md-2"></div>
							<div class="col-md-2">
								<label>Supplier</label>
							</div>				
							<div class="col-md-6" id="item_div">
								<select class="form-control input" id="supplier" name="supplier" onchange="selectSupplier(this.value)">
									<option value="" selected></option>
									<?php if(isset($suppliers)) { ?>
										<?php foreach($suppliers as $supplier) { ?>
											<option value="<?php echo $supplier['supplier_id']?>"><?php echo $supplier['description']?></option>
										<?php } ?>
									<?php } ?>
								</select>						
							</div>
							<div class="col-md-2"></div>
						</div>	
						<br>
						<br>
						<br>
					</div>
					<div id="dialog-additem" title="Message" style="display:none; width: 400px;">	
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
								<label>Quantity</label>
							</div>				
							<div class="col-md-6" id="item_div">
								<input class="form-control input" type="number" id="quantity" name="quantity" value="1" min="1" onChange="selectQuantity(this.value);">					
							</div>
							<div class="col-md-2"></div>
						</div>				
					</div>			
				</form>
			</div>
		</div>
	</div>
</div>
<?php //echo $footer; ?>
<script type="text/javascript"><!--

$(document).ready(function() {
	
	<?php if(isset($err_msg)) { ?>
		var msg = "<?php echo $err_msg; ?>";
		$('#msg').html(msg);		
		$(function() {
			$( "#dialog-message" ).dialog({
			  modal: true,
			  width: 600,
			  buttons: {
				Ok: function() {
					<?php if (!isset($notNew)){?>
						openSuppliers();
					<?php }?>
					$( this ).dialog( "close" );
				}			
			  }
			});
		});			
	<?php } ?>
	
});

function openSuppliers(){
	$(function() {
		$( "#dialog-select-supplier" ).dialog({
		  modal: true,
		  width: 600,
		  buttons: {
			"Update Supplier": function() {
				updateSupplier();
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}			
		  }
		});
	});		
}

function updateSupplier(){
	if($('#supplier_id').val() == "") {
		var msg = "You must select a supplier first.";
		$('#msg').html(msg);
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
	}else{
		$('#task').val("addSupplier");
		<?php if(isset($purchase_id)) { ?>
			var purchse_id = <?php echo $purchase_id; ?>;
			$('form').attr('action', "index.php?route=account/purchase&purchase_id="+purchse_id); 
			$('form').submit();
		<?php } ?>
		$( this ).dialog( "close" );
	}
}

function additem() {
		$(function() {
			$( "#dialog-additem" ).dialog({
			  modal: true,
			  width: 600,
			  buttons: {
				"Add Item": function() {
					if($('#product_id_sel').val() == "") {
						var msg = "You must select an item first.";
						$('#msg').html(msg);		
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
					} else {
						$('#task').val("additem");
						<?php if(isset($purchase_id)) { ?>
							var purchse_id = <?php echo $purchase_id; ?>;
							$('form').attr('action', "index.php?route=account/purchase&purchase_id="+purchse_id); 
							$('form').submit();
						<?php } ?>
					}
				},
				Cancel: function() {
					$('#product_id_sel').val("");
					$('#quantity_sel').val(1);
					$( this ).dialog( "close" );
				}				
			  }
			});
		});	
}

function deleteitem(purchase_det_id, description) {
		var msg = "Are you sure you want to remove "+description+"?";
		$('#msg').html(msg);		
		$(function() {
			$( "#dialog-message" ).dialog({
			  modal: true,
			  width: 600,
			  buttons: {
				Ok: function() {
					<?php if(isset($purchase_id)) { ?>
						var purchse_id = <?php echo $purchase_id; ?>;
						$('#purchase_det_id').val(purchase_det_id);
						$('#task').val("deleteitem");					
						$('form').attr('action', "index.php?route=account/purchase&purchase_id="+purchse_id); 
						$('form').submit();
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

function approve() {
		var msg = "Are you sure you want approve this purchase?";
		$('#msg').html(msg);		
		$(function() {
			$( "#dialog-message" ).dialog({
			  modal: true,
			  width: 600,
			  buttons: {
				Ok: function() {
					<?php if(isset($purchase_id)) { ?>
						$('#task').val("approve");					
						$('form').attr('action', 'purchase/<?php echo $purchase_id; ?>'); $('form').submit();
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

function selectSupplier(supplierId){
	$('#supplier_id').val(supplierId);
}

function selectProduct(product_id) {
	$('#product_id_sel').val(product_id);
}

function selectQuantity(quantity) {
	$('#quantity_sel').val(quantity);
}

function performTask(task) {
	$('#task').val(task);
	$('form').attr('action', '<?php echo HTTP_SERVER;?>index.php?route=account/purchase'); $('form').submit();
}

//--></script>							