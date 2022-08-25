<?php if($this->user->isLogged()){ ?>
	<?php echo $header; ?>
	<?php } else { ?>
<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
	<head>
		<!-- Required meta tags --> 
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title><?php echo WEBSITE_TITLE; ?></title>
		<base href="<?php echo LOCAL_PROD; ?>" />
		<!-- base:css -->
		<link rel="stylesheet" href="theme/regal/vendors/mdi/css/materialdesignicons.min.css">
		<link rel="stylesheet" href="theme/regal/vendors/feather/feather.css">
		<link rel="stylesheet" href="theme/regal/vendors/base/vendor.bundle.base.css">
		<!-- endinject -->
		<!-- plugin css for this page -->
		<link rel="stylesheet" href="theme/regal/vendors/flag-icon-css/css/flag-icon.min.css"/>
		<link rel="stylesheet" href="theme/regal/vendors/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="theme/regal/vendors/jquery-bar-rating/fontawesome-stars-o.css">
		<link rel="stylesheet" href="theme/regal/vendors/jquery-bar-rating/fontawesome-stars.css">
		<link rel="stylesheet" href="theme/regal/vendors/select2/select2.min.css">
		<link rel="stylesheet" href="theme/regal/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
		<!-- End plugin css for this page -->
		<!-- inject:css -->
		<link rel="stylesheet" href="theme/regal/css/style.css">
		<!-- endinject -->
		<link rel="icon" href="image/edsiicon2.png" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="stylesheet.css" />
		<link rel="stylesheet" type="text/css" href="css/summernote/summernote.css" />
		<link rel="stylesheet" href="css/jquery-ui.css" />
	</head>
<?php } ?>
<br>
<body>
<div class="container-fluid">
	<form action="" method="post" id="form" enctype="multipart/form-data">
		<input type="hidden" id="payment_option" name="payment_option" value="<?php if(isset($payment_option['payment_option'])) { echo $payment_option['payment_option'];} ; ?>"> 
		<input type="hidden" id="tracking" name="tracking" value="<?php if(isset($payment_option['tracking'])) { echo $payment_option['tracking'];} ; ?>">
		<input type="hidden" id="encoded_from" name="encoded_from" value="trackorder">
		<input type="hidden" id="ticket_id" name="ticket_id" value="<?php echo $track_order_header['ticket_id'];?>">
		<?php if(isset($track_order_header)) { ?>
			<!--<div class="row">-->	
				<!--<div class="row">-->
				<div class="row">
					<div class="col-md-12">
						<div class="text-center" style="background-color: #343434; padding: 30px; margin-top:0;">
							<h1 class="text-white"><?php echo WEBSITE_TITLE; ?></h1>
						</div>			
					</div>
				</div>
				<div class="col-md-12">
					<div>
						<div class="card-body">	
							<div class="card mt-2">
								<div class="card-header bg-turqouise text-white">
									<h3 class="h3 text-dark"><i class="fa fa-tasks"></i> Order Id# <?php echo $track_order_header['order_id']; ?> is in <?php echo $track_order_header['status_desc']; ?> status.</h3>
								</div>
								<div class="card-block">
									<div class="ml-3 mt-3 mr-3">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-3">				
													<label><b>Customer Name</b></label>
													<input type="text" readonly="" name="customerName" id="customerName" value="<?php if(isset($track_order_header['customer_name'])) { echo $track_order_header['customer_name'];} ; ?>" class="form-control">
													<br>
												</div>
												<div class="col-md-3">				
													<label><b>Contact Number</b></label>
													<input type="text" id="contactNo" readonly="" name="contactNo" value="<?php if(isset($track_order_header['contact'])) { echo $track_order_header['contact'];} ; ?>" class="form-control">
													<br>
												</div>
												<br>
												<div class="col-md-3">				
													<label><b>Email Address</b></label>
													<input type="text" id="emailAdd" readonly="" name="emailAdd" value="<?php if(isset($track_order_header['email'])) { echo $track_order_header['email'];} ; ?>" class="form-control">
													<br>
												</div>
												<div class="col-md-3">				
													<label><b>Admin</b></label>
													<input type="text" readonly="" name="admin" id="admin" value="<?php if(isset($track_order_header['admin'])) { echo $track_order_header['admin'];} ; ?>" class="form-control">
													<br>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-md-12">
													<label><b>Customer Address</b></label>
													<input type="text" id="custAddresss" name="custAddresss" value="<?php if(isset($track_order_header['address'])) { echo $track_order_header['address'];} ?>" class="form-control" readonly="">
													<br>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label><b>LandMark</b></label>
													<input type="text" id="landmark" name="landmark" value="<?php if(isset($track_order_header['landmark'])) { echo $track_order_header['landmark'];} ?>" class="form-control" readonly="">
													<br>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-md-3">				
													<label><b>Payment Status</b></label>
													<input type="text" id="paymentStat" readonly="" name="paymentStat" value="<?php if(isset($track_order_header['paid_desc'])) { echo $track_order_header['paid_desc'];} ; ?>" class="form-control">
													<br>
												</div>
												<div class="col-md-3">				
													<label><b>Delivery Fee</b></label>
													<input type="text" id="deliveryFee" readonly="" name="deliveryFee" value="Php <?php if(isset($track_order_header['delivery_fee'])) { echo $track_order_header['delivery_fee'];} ; ?>" class="form-control">
													<br>
												</div>
												<div class="col-md-3">				
													<label><b>Discount</b></label>
													<input type="text" id="paymentStat" readonly="" name="paymentStat" value="Php <?php if(isset($track_order_header['discount'])) { echo $track_order_header['discount'];} ; ?>" class="form-control">
													<br>
												</div>
												<div class="col-md-3">				
													<label><b>Payment Reference No.</b></label>
													<input type="text" id="paymentRef" readonly="" name="paymentRef" value="<?php if(isset($track_order_header['ref'])) { echo $track_order_header['ref'];} ; ?>" class="form-control">
													<br>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-md-2">				
													<label><b>Grand Total</b></label>
													<input type="text" readonly="" name="grandTotal" id="grandTotal" value="Php <?php if(isset($track_order_header['total'])) { echo $track_order_header['total'];} ; ?>" class="form-control">
													<br>
												</div>
												<div class="col-md-3">				
													<label><b>Mode of Delivery</b></label>
													<input type="text" id="modeofDelivery" readonly="" name="modeofDelivery" value="<?php if(isset($track_order_header['mod_desc'])) { echo $track_order_header['mod_desc'];} ; ?>" class="form-control">
													<br>
												</div>
												<div class="col-md-4">				
													<label><b>Payment Option</b></label>
													<input type="text" id="paymentOption" readonly="" name="paymentOption" value="<?php if(isset($track_order_header['payment_option_desc'])) { echo $track_order_header['payment_option_desc'];} ; ?>" class="form-control">
													<br>
												</div>
												<div class="col-md-3">				
													<label><b>Delivery Tracking No.</b></label>
													<input type="text" readonly="" name="dlvryTrackno" id="dlvryTrackno" value="<?php if(isset($track_order_header['tracking'])) { echo $track_order_header['tracking'];} ; ?>" class="form-control">
													<br>
												</div>
											</div>
											<br>
											<?php if($track_order_header['payment_option'] == 94) { ?>
											<div class="row">
												<div class="col-md-3">				
													<label><b>Receiving Branch Address</b></label>
													<input type="text" readonly="" name="rcvngBranch" id="rcvngBranch" value="<?php echo $track_order_header['receiving_branch']; ?>" class="form-control">
													<br>
												</div>
											</div>
											<?php } ?>
											<?php if($track_order_header['delivery_option'] == 96 && $track_order_header['paid_flag'] == 0 ) { ?>
												<div class="row">
													<div class="col-md-6">				
														<label><b>Proof of Payment</b></label>
														<input type="file" name="proof_of_payment" id="proof_of_payment" value="" class="form-control">
														<br>
														<?php if(isset($track_order_header['extension'])) { ?>
															<?php if($track_order_header['extension'] != '') { ?>
															
															<div class="row">
																<div class="col-md-12">
																	<img width="100%" class="img-responsive" src="paymentimages/paymentimages<?php echo $track_order_header['order_id'].".".$track_order_header['extension']; ?>">
																</div>
															</div>
															<br>
															<?php } ?>
														<?php } ?>		
													</div>
													<!--==========================================================-->
													<div class="col-md-6">
														<div class="card-body">	
															<div class="card mt-2">
																<div class="card-header bg-turqouise">
																	<h3 class="h3 text-dark"><i class="fa fa-money"></i> For this order to proceed please deposit payment and send to:</h3>
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
																								<?php if($track_order_header['payment_option'] == 90 || $track_order_header['payment_option'] == 91) {?>							
																									<h4><b>Palawan Express / LBC</b></h4>
																									<h5><b>Name</b>: APRIL JOY SUSON</h5>
																									<h5><b>Phone</b>: 0910-8672-352</h5>
																									<h5><b>Address</b>: Door 3, G.A. Esteban Bldg, <br>
																										   Lacson St, Brgy 19, Bacolod City, Negros Occidental 6100</h5>
																									<br>				
																								<?php } else if($track_order_header['payment_option'] == 89 or $track_order_header['payment_option'] == 145) { ?>
																									<h4>For Bank Transfer, Please call:</h4>
																									<h5><b>Name</b>: APRIL JOY SUSON</h5>
																									<h5><b>BDO</b>: 001960667732</h5>
																									
																									
																									<br>
																								<?php } ?>
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
											<?php } ?>
										</div>
									</div>						   	
								</div>
							</div>	
						</div>
					</div>
				</div>
				<!--==========================================================-->
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-10">
						<?php if($track_order_header['paid_flag'] == 0) { ?>
							<?php if($this->user->getUserGroupId() == 12 || $this->user->getUserGroupId() == 54 || $this->user->getUserGroupId() == 49) { ?>
								<br>
								<div class="row">
									<div class="col-md-2">
										<label>Delivery Tracking Number</label>
									</div>
									<div class="col-md-8">
										<input type="text" class="form-control input" id="tracking" name="tracking" value="<?php if(isset($track_order_header['tracking'])) { echo $track_order_header['tracking'];} ?>">
									</div>
								</div>
							<?php } ?>
							<br>
						<div class="row">
							<div class="col-md-2">
								<label>Notes</label>
							</div>
							<div class="col-md-8">
								<input type="text" class="form-control input" id="notes" name="notes" value="<?php if(isset($track_order_header['notes'])) { echo $track_order_header['notes'];} ?>">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-2">
								<label>Remarks</label>
							</div>
							<div class="col-md-8">
								<input type="text" class="form-control input" id="remarks" name="remarks" value="<?php if(isset($track_order_header['remarks'])) { echo $track_order_header['remarks'];} ?>">
							</div>
						</div>
					</div>
					<div class="col-md-1"></div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						<center>
							<input type="hidden" id="task" name="task" value="">
							<input type="hidden" id="order_id" name="order_id" value="<?php if(isset($track_order_header['order_id'])) { echo $track_order_header['order_id'];} ?>">
							<input type="hidden" id="ref" name="ref" value="<?php if(isset($track_order_header['ref'])) { echo $track_order_header['ref'];} ?>">
							<button class="btn btn-success" type="button" onclick="javascript: updateOrder('updateOrder');"><i class="fa fa-save"></i> Save</button>
							
							<?php if($this->user->getUserGroupId() == 54 && $track_order_header['status_id'] != 124) { ?>
								<input type="hidden" id="order_id" name="order_id" value="<?php if(isset($track_order_header['order_id'])) { echo $track_order_header['order_id'];} ?>">
								<button class="btn btn-warning" type="button" onclick="javascript: reuploadPayment('reuploadPayment');"><i class="fa fa-save"></i> Tag as Reupload Proof of Payment</button>
							
								<input type="hidden" id="order_id" name="order_id" value="<?php if(isset($track_order_header['order_id'])) { echo $track_order_header['order_id'];} ?>">
								<button class="btn btn-success" type="button" onclick="javascript: tagpaid('tagpaid');"><i class="fa fa-save"></i> Confirmed Payment</button>
							<?php } ?>
						</center>
						<?php } ?>
					</div>
				</div>						
				<br>
				<!--==========================================================-->
				<div class="col-md-12">
					<div>
						<div class="card-body">	
							<div class="card mt-2">
									<div class="card-header bg-screen text-white">
										<h3 class="h3 text-blight"><i class="fa fa-cube"></i> Products Ordered</h3>
									</div>
								<div class="card-block">
									<div class="ml-3 mt-3 mr-3">
										<div class="col-md-12">
											<div class="row">
												<div class="col-lg-12 grid-margin stretch-card">
													<div class="table-responsive">			
														<table class="table table-striped table-bordered">
															<thead>
																<tr class="table-info">
																	<th class="text-dark">Product</th>
																	<th class="text-dark">Quantity</th>
																	<th class="text-dark">Line Amount</th>
																	<th class="text-dark">Discount</th>
																</tr>
															</thead>						
															<tbody>
																
																	<?php if(isset($order_details)){ ?>
																		<?php foreach($order_details as $o){ ?>
																		<tr>
																			<td><?php echo $o['description']; ?></td>
																			<td><?php echo $o['quantity']; ?></td>
																			<td><?php echo number_format($o['amount'],2); ?></td>
																				<?php if($this->user->getUserGroupId() == 56){ ?>
																							<td><?php echo number_format($o['distributor_disc'],2); ?></td>
																				<?php } else if($this->user->getUserGroupId() == 46){ ?> 
																							<td><?php echo number_format($o['reseller_disc'],2); ?></td>
																				<?php } else {?> 
																							<td><?php echo number_format(0,2); ?></td>
																				<?php } ?>
																		</tr>
																		<?php } ?>  
																	<?php } ?>
															</tbody>					
														</table>
													</div>
												</div>	
											</div>
										</div>						   	
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="card-body">	
						<div class="card mt-2">
								<div class="card-header bg-turqouise text-dark">
									<h3 class="h3 text-dark"><i class="fa fa-money"></i> Remarks</h3>
								</div>
							<div class="card-block">
								<div class="ml-3 mt-3 mr-3">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-12">
												<div class="col-md-12">
													<textarea class="form-control" placeholder="Add Remark / Comment" rows="3" name="remark"></textarea>
												</div>
												<div class="col-md-12 mt-3 text-center">
													<?php if ($track_order_header['ticket_id']  == 0 ) {?>
														<?php if($this->user->isLogged()) {?>
															<button class="btn btn-success" type="button" onclick="javascript:createTicket('createTicket');">Create Ticket</button>
														<?php } else { ?>
															<button class="btn btn-success" type="button" onclick="javascript:addcomment('remarks');">Add Comment</button>
														<?php } ?>											
													<?php } else { ?>
														<button class="btn btn-success" type="button" onclick="javascript:addcomment('remarks');">Add Comment</button>
													<?php } ?>
												</div>
											</div>
											<div class="col-lg-12 grid-margin stretch-card">
												<div class="table-responsive">			
													<table class="table table-striped table-bordered">
													<br>
														<thead>
															<tr class="table-info">
																<th>#</th>
																<th class="text-dark">USERNAME</th>
																<th class="text-dark">SENDER</th>
																<th class="text-dark">COMMENT / REMARK</th>
																<th class="text-dark">DATE ADDED</th>
															<tr>
														</thead>
														<tbody id="customers_list">
															<?php if(isset($remarks)){
																$count = 1;
																foreach($remarks as $o){
																	?>
																	<tr>
																		<td class="w-10"><?php echo $count; ?></td>
																		<td class="w-20"><?php echo $o['username']; ?></td>
																		<td class="w-20"><?php echo $o['fullname']; ?></td>
																		<td class="w-50"><?php echo $o['remark']; ?></td>
																		<td class="w-20"><?php echo $o['date_added']; ?></td>
																	</tr>
																	<?php $count += 1;
																}
															} ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<br>
									</div>						   	
								</div>
							</div>							
						</div>
					</div>
				</div>
		<?php } ?>
		<!--==========================================================-->		
	</form>
</div>
<div id="dialog-message" title="Message" style="display:none; width: 400px;">
	<span id="msg"></span>
</div>
<br>
<?php if($this->user->isLogged()){ ?>
<?php echo $footer; ?>
<?php } else { ?>
        <!-- content-wrapper ends -->        
		<!-- partial:partials/_footer.html-->
		<footer class="footer" style="background-color: #ffffff; border-top: #eeeeee solid 1px;">
			<div class="text-center">
				<a href="#"><img src="image/homelogo.gif"></a>
				<p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved</p>
			</div>
        </footer>
  <!-- base:js -->
  <script src="theme/regal/vendors/base/vendor.bundle.base.js"></script>
  <script src="theme/star/node_modules/jquery/dist/jquery.min.js"></script>
  <script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.11.2.min.js"></script>
  <!--<script src="theme/star/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>-->
  
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="theme/regal/js/off-canvas.js"></script>
  <script src="theme/regal/js/hoverable-collapse.js"></script>
  <script src="theme/regal/js/template.js"></script>
  <!-- endinject -->
  <!-- plugin js for this page -->
  <script src="theme/regal/vendors/chart.js/Chart.min.js"></script>
  <script src="theme/regal/vendors/jquery-bar-rating/jquery.barrating.min.js"></script>
  <script src="theme/regal/vendors/typeahead.js/typeahead.bundle.min.js"></script>
  <script src="theme/regal/vendors/select2/select2.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- Custom js for this page-->
  <script src="theme/regal/js/dashboard.js"></script>
  <script src="theme/regal/js/file-upload.js"></script>
  <script src="theme/regal/js/typeahead.js"></script>
  <script src="theme/regal/js/select2.js"></script>
  <script type="text/javascript" src="css/summernote/summernote.js"></script>
  <script type="text/javascript" src="css/summernote/opencart.js"></script>
  <script src="css/jquery-ui.js"></script>
  <script type="text/javascript" src="catalog/view/ajax/ajax.js"></script>
  <!-- End custom js for this page-->
<?php } ?>
 <!--end of container fluid-->

</body>
</html>
<script type="text/javascript"><!--
var selected = [];
$(document).ready(function() {
	$('#datefrom').datepicker({dateFormat: 'yy-mm-dd'});
	$('#dateto').datepicker({dateFormat: 'yy-mm-dd'});
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

function updateOrder(task) {
	$('#task').val(task);
	$('form').attr('action', "trackingpage/<?php echo $track_order_header['ref']; ?>"); 
	// $('form').attr('action', 'trackingpage');
	$('form').submit();
}

function reuploadPayment(task) {
	$('#task').val(task);
	$('form').attr('action', "trackingpage/<?php echo $track_order_header['ref']; ?>");
	$('form').submit();
}

function tagpaid(task) {
	$('#task').val(task);
	$('form').attr('action', "trackingpage/<?php echo $track_order_header['ref']; ?>");
	$('form').submit();
}

function createTicket(task) {
	$('#task').val(task);
	$('form').attr('action', "trackingpage/<?php echo $track_order_header['ref']; ?>"); 
	$('form').submit();
}

function addcomment(task) {
	$('#task').val(task);
	$('form').attr('action', "trackingpage/<?php echo $track_order_header['ref']; ?>"); 
	$('form').submit();
}

//--></script>
