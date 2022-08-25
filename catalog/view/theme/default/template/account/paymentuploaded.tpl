<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
			<i class="ni ni-credit-card ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp; Payment Uploaded</h6>
		</div>
	  </div>
	</div>
  </div>
</div>
<div class="container mt--6">
	<form action="" method="post" enctype="multipart/form-data" id="form">
		<input type="hidden" id="task" name="task" value=""> 
		<input type="hidden" name="reference" id="reference">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">					
							<h3 class="card-title"><i class="fa fa-filter"></i> Filter</h3>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">	
										<label>Order ID:</label>
										<input class="form-control input" type="text" id="order_id" name="order_id">
									</div>
								</div>
								<div class="col-md-8">
									<div class="form-group">	
										<label>Customer Name:</label>
										<input class="form-control input" type="text" id="cust_name_search" name="cust_name_search">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">	
										<label>Date Created From:</label>
										<input class="form-control input" type="date" id="datecreatedfrom" name="datecreatedfrom">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">	
										<label>Date Created To:</label>
										<input class="form-control input" type="date" id="datecreatedto" name="datecreatedto">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">	
										<label>Reference:</label>
										<input class="form-control input" type="text" id="ref" name="ref">
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<center>
									<!--<div class="template-demo">-->
									<div class="row">
										<input type="hidden" id="type" name="type" value="orders">
										<div class="col-md-2">
											<button class="btn btn-outline-info" type="button" onclick="javascript: performTask('search');"><i class="fa fa-search"></i> Search</button>
										</div>
										<div class="col-md-3">
											<button class="btn btn-outline-success" type="button" onclick="javascript: performTask('tagPaymentConfirmed');"><i class="fa fa-search"></i> Confirm Payment</button>
										</div>
									</div>
									<!--</div>-->
								</center>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive" style="overflow: auto;">				
										<table class="table table-bordered table-hover">
											<thead>
												<tr class="table-primary">
													<th>&nbsp;</th>
													<th width="1" style="text-align: center;">
														<input style="width:25px !important;" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
													</th>
													<th class="text-dark">Order Id</th>							
													<th class="text-dark">Status</th>
													<th class="text-dark">Item/Quantity</th>
													<th class="text-dark">Date Created</th>	
													<th class="text-dark">Date Paid</th>	
													<th class="text-dark">Date Packed</th>
													<th class="text-dark">Paid/NotPaid?</th>	
													<th class="text-dark">Date Delivered</th>	
													<th class="text-dark">Customer Name</th>	
													<th class="text-dark">Address</th>	
													<th class="text-dark">Contact</th>	
													<th class="text-dark">Mode of Delivery</th>	
													<th class="text-dark">Tracking</th>	
													<th class="text-dark">Send To</th>							
													<th class="text-dark">Delivery Fee</th>							
													<th class="text-dark">Discount</th>							
													<th class="text-dark">Total</th>							
													<th class="text-dark">Reseller</th>							
													<th class="text-dark">Payment Option</th>							
													<th class="text-dark">Reference</th>							
													<th class="text-dark">Notes</th>							
												</tr>
											</thead>
											<tbody>
												<?php if (isset($order_list)) { ?>
													<?php foreach ($order_list as $it) { ?>
														<tr>
															<td>
																<?php if($it['status_id'] == 0) { ?>
																	<a class="btn btn-primary btn-md" href="cart/<?php echo $it['ref'];?>">View</a>
																<?php } else { ?>
																	<a class="btn bg-avgreen text-white btn-md" href="trackingpage/<?php echo $it['ref'];?>">View Tracking</a>
																<?php } ?>
															</td>
															<td style="text-align: center;">
																<input style="width:25px;" type="checkbox" name="selected[]" value="<?php echo $it['order_id'];?>"/>
															</td>
															<td><?php echo $it['order_id'];?></td>
															<td><?php echo $it['status'];?></td>
															<td><?php echo $it['items'];?></td>
															<td><?php echo $it['date_added'];?></td>
															<td><?php echo $it['paid_date'];?></td>
															<td><?php echo $it['packed_date'];?></td>
															<td><?php echo $it['paid_flag'];?></td>
															<td><?php echo $it['actual_delivery_date'];?></td>
															<td><?php echo $it['customer_name'];?></td>
															<td><?php echo $it['address'];?></td>
															<td><?php echo $it['contact'];?></td>
															<td><?php echo $it['mod_desc'];?></td>
															<td><?php echo $it['tracking'];?></td>
															<td><?php echo $it['send_to'];?></td>
															<td><?php echo $it['delivery_fee'];?></td>
															<td><?php echo $it['discount'];?></td>
															<td><?php echo $it['amount'];?></td>
															<td><?php echo $it['reseller'];?></td>
															<td><?php echo $it['payment_option_desc'];?></td>
															<td><?php echo $it['reference'];?></td>
															<td><?php echo $it['notes'];?></td>									
														</tr>	
													<?php } ?>
												<?php } ?>
											</tbody>
										</table>
									</div>
									<div class="pagination"><div class="results"><?php echo $pagination; ?></div></div>
								</div>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>	
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
	
</div>

<!--<div id="dialog-message" title="Message" style="display:none; width: 600px;">
  <span id="msg"></span>
</div>-->

<?php echo $footer; ?>

<script type="text/javascript"><!--
$(document).ready(function() {
    
	<?php if(isset($err_msg)) { ?>
    $('#msg').html("<?php echo $err_msg; ?>");			      
	$('#modal-notification').modal('show');
    <?php } ?>
});

function performTask(task) {

	$('#task').val(task);
	$('form').attr('action', 'paymentuploaded'); 
	$('form').submit();
	
}

//--></script>							