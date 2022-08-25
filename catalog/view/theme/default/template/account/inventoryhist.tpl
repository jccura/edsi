<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
			<i class="fa fa-shopping-cart ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp; Item History</h6>
		</div>
	  </div>
	</div>
  </div>
</div>
<div class="container-fluid mt--6">
	<div class="panel-body">
		<form action="" method="post" enctype="multipart/form-data" id="form">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">						
								<div class="row">
									<div class="col-md-12">
										<fieldset>
											<div class="panel panel-default">
												<div class="panel-heading right-header">
													<h3 class="card-title"> Item Details</h3>
												</div>
												<br>
												<div class="panel-body">
													<div class="form-group row">
														<label for="customerAddress" class="col-sm-2">Item: </label>
														<div class="col-sm-4">
															<input type="text" class="form-control" placeholder="Total Amount" value="<?php if(isset($item['item_name'])) { echo $item['item_name']; } ?>" readonly="readonly">
														</div>

														<label for="customerAddress" class="col-sm-2">Total Restock: </label>
														<div class="col-sm-4">
															<input type="text" class="form-control" value="<?php if(isset($item_historytotal['re_stock'])) { echo $item_historytotal['re_stock']; } ?>" readonly="readonly">
														</div>
													</div>
													  <?php if($this->user->getUserGroupId() == 44) { ?>
													<div class="form-group row">
														<label for="customerAddress" class="col-sm-2">Total Sold: </label>
														<div class="col-sm-4">
															<input type="text" class="form-control" placeholder="Status" value="<?php if(isset($item_historytotal['sold'])) { echo $item_historytotal['sold']; } ?>" readonly="readonly">
														</div>

														<label for="customerAddress" class="col-sm-2">Total Remaining: </label>
														<div class="col-sm-4">
															<input type="text" class="form-control" placeholder="Supplier" value="<?php if(isset($item_historytotal['re_stock']) && isset($item_historytotal['sold'])) { echo ($item_historytotal['re_stock'] - $item_historytotal['sold']); } ?>" readonly="readonly">
														</div>
													</div>
													  <?php } else if($this->user->getUserGroupId() == 39 || $this->user->getUserGroupId() == 43){?>
													<div class="form-group row">
														<label for="customerAddress" class="col-sm-2">Total Sold: </label>
														<div class="col-sm-4">
															<input type="text" class="form-control" placeholder="Status" value="<?php if(isset($item_historytotal['sold']) && isset($item_historytotal['assembled'])) { echo ($item_historytotal['sold'] + $item_historytotal['assembled']); } ?>" readonly="readonly">
														</div>

														<label for="customerAddress" class="col-sm-2">Total Remaining: </label>
														<div class="col-sm-4">
															<input type="text" class="form-control" placeholder="Supplier" value="<?php if(isset($item_historytotal['re_stock']) && isset($item_historytotal['total_stock']) && isset($item_historytotal['total_returned'])) { echo ($item_historytotal['re_stock'] - $item_historytotal['total_stock'] + $item_historytotal['total_returned']); } ?>" readonly="readonly">
														</div>
													</div>
													<div class="form-group row">
														<label for="customerAddress" class="col-sm-2">Floating Item: </label>
														<div class="col-sm-4">
															<input type="text" class="form-control" placeholder="Supplier" value="<?php  if(isset($item_historytotal['sold']) && isset($item_historytotal['total_packed']) && isset($item_historytotal['total_returned'])) { echo ($item_historytotal['total_packed'] - $item_historytotal['sold'] - $item_historytotal['total_returned']); } ?>" readonly="readonly">
														</div>
													</div>
													  <?php }?>
												</div>
											</div>
										 </fieldset>
									</div>
								</div>													
						</div>	
					</div>	
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">						
							<div class="row">
								<div class="col-md-12">
										<div class="table-responsive" style="width: 100%;">				
											<table class="table table-hover table-bordered">
											  <thead>
												<tr class="table-primary">
												  <td class="left">History Id</td>
												  <td class="left">Order Id</td>
												  <td class="left">Load Inventory Id</td>
												  <td class="left">Item Name</td>
												  <td class="left">Restock</td>
												  <td class="left">Assembly Source</td>
												  <td class="left">Assembly Destination</td>
												  <td class="left">Returned</td>
												  <td class="left">Packed</td>
												  <td class="left">Sold</td>	
												  <?php if($this->user->getUserGroupId() == 44) { ?>
												  <td class="left">Branch/Person Transacted With</td>	
												  <td class="left">Released To</td>		
												  <?php } else {?>
												  <td class="left">Branch/Person Transacted With</td>	
												  <?php } ?>
												  <td class="left">Date</td>								  
												  <td class="left">Remarks</td>								  
												</tr>
											  </thead>
											  <tbody>
												<?php if (isset($item_history)) { ?>
												<?php foreach ($item_history as $ih) { ?>			
												<tr>										
												  <td><?php echo $ih['inventory_history_id'];?></td>
												  <td><?php echo $ih['order_id'];?></td>
												  <td><?php echo $ih['load_inventory_id'];?></td>
												  <td><?php echo $ih['item_name'];?></td>
												  <td><?php echo $ih['re_stock'];?></td>
												  <td><?php echo $ih['assembled_from'];?></td>
												  <td><?php echo $ih['assembled_to'];?></td>
												  <td><?php echo $ih['returned'];?></td>
												  <td><?php echo $ih['packed'];?></td>
												  <td><?php echo $ih['sold'];?></td>
												  <?php if($this->user->getUserGroupId() == 44) { ?>
												  <td><?php echo $ih['affiliatedBranch'];?></td>
												  <td><?php echo $ih['received_by'];?></td>
												  <?php } else {?>
												  <td><?php echo $ih['affiliatedBranch'];?></td>
												  <?php } ?>
												  <td><?php echo $ih['date_added'];?></td>
												  <td><?php echo $ih['remarks'];?></td>
												</tr>
												<?php } ?>
												<?php } ?>
											  </tbody>
											</table>
										</div>
									<div class="pagination"><?php echo $pagination; ?></div>
								</div>
							</div>													
						</div>	
					</div>	
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>
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
					$( this ).dialog( "close" );
				}			
			  }
			});
		});			
	<?php } ?>
	
});

function addInventory() {
	var ask = confirm("Are you sure you want to update the Inventory?");

	if(ask == true){
		$('#task').val('updateinventory');
		$('form').attr('action', 'inventory'); $('form').submit();
	}

	
}

//--></script>							