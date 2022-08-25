<?php echo $header; ?>
	
	<style>
      /* Set the size of the div element that contains the map */
		#map {
			height: 400px;  /* The height is 400 pixels */
			width: 100%;  /* The width is the width of the web page */
			position: fixed;
			bottom: 10px;
			right: 10px;
		}

		.controls {
			margin-top: 10px;
			border: 1px solid transparent;
			border-radius: 2px 0 0 2px;
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			height: 32px;
			outline: none;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
		}

		#mode-selector {
			color: #fff;
			background-color: #4d90fe;
			margin-left: 12px;
			padding: 5px 11px 0px 11px;
		}

		#mode-selector label {
			font-family: Roboto;
			font-size: 13px;
			font-weight: 300;
		}
		
		.modal-footer2 {
			padding: 15px;
			text-align: right;
			border-bottom: 1px solid #e5e5e5;
		}

		.btn-block {
			padding: 2% 0;
		}
		
		div.sticky-pc {
		  position: fixed;
		  right: 0;
		  bottom: 0;
		  float: right;
		  padding: 5px;
		  background-color: #FFF0D6;
		  border: 2px solid #ef553a;
		  margin-bottom: 10px;
		  margin-right: 15px;
		}
		div.sticky-cp {
		  position: fixed;
		  right: 0;
		  bottom: 0;
		  float: right;
		  padding: 5px;
		  background-color: #FFF0D6;
		  border: 2px solid #ef553a;
		  margin-bottom: 10px;
		  margin-right: 15px;
		}
    </style>
	
<div class="header bg-gradient-darkorange pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
			<i class="ni ni-cart ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp; Book A Delivery</h6>
		</div>
	  </div>
	</div>
  </div>
</div>

<div class="container-fluid mt--6">
	<div class="panel">
		<form action="" method="post" enctype="multipart/form-data" id="form">
			<input type="hidden" id="task" name="task" value="">
			<input type="hidden" id="location_type" name="location_type" value="">
			<input type="hidden" id="merchant_id" name="merchant_id" value="0">
			<input type="hidden" id="counter" name="counter" value="0">
			<input type="hidden" id="current_qd_id" name="current_qd_id" value="<?php echo $currentBooking['current_qd_id'];?>">
			<input type="hidden" id="qd_location_id" name="qd_location_id" value="">
			<input type="hidden" id="trans_session_id" name="trans_session_id" value="<?php echo $trans_session_id;?>">
			<input type="hidden" id="qd_id" name="qd_id" value="<?php echo $currentBooking['quick_deliveries_id']; ?>">
			<input type="hidden" id="visibility" name="visibility" value="">
			<div id="dialog-message" title="Confirmation Message" style="display:none; width: 400px;">
			  <span id="msg"></span>
			</div>
			<?php if($currentBooking['current_qd_id'] == 0 && $segment1 != 'viewbooking') { ?>
			<div class="row">
				<div class="col-md-12">
					
					<?php if ($this->user->getUserGroupID() == 111) { ?>		
						<button class="btn btn-info" type="button" onclick="performTask('createQD')" title="Create Quick Delivery"><i class="fa fa-plus"></i> Create Quick Delivery (Regular)</button>
						<button class="btn btn-info" type="button" onclick="performTask('createQDS')" title="Create Special Quick Delivery"><i class="fa fa-plus"></i> Create Quick Delivery (Special)</button>
						<button class="btn btn-info" type="button" onclick="performTask('createQDM')" title="Create Special Quick Delivery"><i class="fa fa-plus"></i> Create Quick Delivery (Manual)</button>
					<?php } else { ?>
						<button class="btn btn-info" type="button" onclick="performTask('createQD')" title="Create Quick Delivery"><i class="fa fa-plus"></i> Create Quick Delivery</button>
					<?php } ?>
				</div>
			</div>
			<?php } else { ?>
			<?php if($segment1 == 'viewbooking') { ?>
				<button class="btn btn-info" type="button" onclick="showQD(<?php echo $currentBooking['quick_deliveries_id']; ?>);" title="Back to previous screen"><i class="fa fa-arrow-left"></i> Back to previous screen</button><br/><br/>
			<?php } ?>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<label><i class="fa fa-location-arrow "></i> Quick Delivery Information <?php if ($this->user->getUserGroupID() == 111 ) { if ($currentBooking['special_flag'] == 0) { echo "(Regular)"; } else if ($currentBooking['special_flag'] == 1) { echo "(Special)"; } else { echo "(Manual)"; } }?></label>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12" style="overflow:auto;">
									<table class="table table-bordered table-hover" id="pickup_tbl" name="pickup_tbl">
										<tbody>
											<tr>
												<td>
													Quick Delivery Order#: <b><?php echo $currentBooking['quick_deliveries_code'];?></b><br>
													Customer Name: <b><?php echo $currentBooking['customer_name'];?></b><br>
													Required E-Wallet: <b>Php <?php echo number_format($currentBooking['required_e_wallet'],2);?></b><br>
													Required Cash-Onhand: <b>Php <?php echo number_format($currentBooking['required_cash_on_hand'],2);?></b><br>
													Overall Distance: <b><?php echo $currentBooking['distance_in_km']; ?> km</b><br>
													Overall Waiting Time: <b><?php echo $currentBooking['total_waiting_time']; ?> minutes</b><br>
													Overall Waiting Fee: <b>Php <?php echo $currentBooking['total_waiting_fee']; ?></b><br>
													Status: <b><?php echo $currentBooking['status']; ?></b><br>
												</td>
											</tr>					  
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div><!-- panel-group -->
				<div class="col-md-12">
					<div class="card">
						<div class="panel-heading">
							<label><i class="fa fa-location-arrow "></i> Pick Up Location</label>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12" style="overflow:auto;">
									<table class="table table-bordered table-hover" id="pickup_tbl" name="pickup_tbl">
										<thead>
											<tr>
												<td class="left">Location</td>
												<td class="left">
													<?php if($segment1 != 'viewbooking') { ?>
													<!-- <button class="btn btn-info" type="button" data-toggle="modal" data-target="#area-merchant-modal" onclick="javascript: addFromAffiliatedMerchant();"><i class="fa fa-plus"></i> Add From Affiliated Merchant</button> -->
													<button class="btn btn-info" type="button" onclick="addLocation('PICKUP')" title="Edit"><i class="fa fa-plus"></i> Add Pickup Location</button>
													<?php } else { ?>
														&nbsp;
													<?php } ?>
												</td>
											</tr>
										</thead>
										<tbody>
											<?php if(isset($currentBooking['pickuplocations'])) { ?>
												<?php foreach($currentBooking['pickuplocations'] as $pickups) { ?>
													<tr>
														<td colspan="2">
															Establishment/Customer Name: <b><?php echo $pickups['contact_name']; ?></b><br>
															Contact Number: <b><?php echo $pickups['contact_number']; ?></b><br>
															Address: <b><?php echo $pickups['street']. ", ". $pickups['brgy_desc']. ", ". $pickups['city_town_desc']. ", ". $pickups['province_desc']; ?></b><br>
															Landmark: <b><?php echo $pickups['landmark']; ?></b><br>
															Pickup Instruction: <b><?php echo $pickups['instruction']; ?></b><br>
															Location Status: <b><?php echo $pickups['status']; ?></b><br>
															Distance From Last Location: <b><?php echo $pickups['dist_from_last_loc']; ?> km</b><br>
															<?php if ($pickups['location_type'] == 'PICKUP') { ?>
																<a href="<?php echo $segment1 != 'viewbooking' ? 'bookingaddlocation' : 'viewbookinglocation'; ?>/<?php echo $pickups['quick_deliveries_id']; ?>/<?php echo $pickups['qd_location_id']; ?>/PICKUP" class="btn btn-info"><i class="fa fa-search"></i> View Location</a>&nbsp;&nbsp;
															<?php } else { ?>
																<a href="<?php echo $segment1 != 'viewbooking' ? 'bookingaddlocationmerch' : 'viewbookinglocation'; ?>/<?php echo $pickups['quick_deliveries_id']; ?>/<?php echo $pickups['qd_location_id']; ?>/PICKUPMERCH/<?php echo $pickups['merchant_id']; ?>" class="btn btn-info"><i class="fa fa-search"></i> View Location</a>&nbsp;&nbsp;
															<?php } ?>
															<?php if($segment1 != 'viewbooking') { ?>
															<button class="btn btn-danger" type="button" onclick="removeLocationSubmit(<?php echo $pickups['qd_location_id']; ?>)" title="This will remove this location to the Quick Delivery"><i class="fa fa-minus"></i> Remove this location</button>
															<?php } ?>
															<?php if($segment1 == 'viewbooking' && $currentBooking['status_id'] != 138 && $pickups['status_id'] != 245 && $pickups['status_id'] == 244) { ?>
																<button class="btn btn-primary" type="button" onclick="processQD('itemPickedUp', <?php echo $pickups['qd_location_id']; ?>)" title="Picked Up Item"><i class="fa fa-save"></i> Item picked up</button>
															<?php } ?>
															<?php if($segment1 == 'viewbooking' && $currentBooking['status_id'] != 138 && $pickups['status_id'] != 245 && $pickups['status_id'] != 244 && $pickups['status_id'] == 138) { ?>
																<button class="btn btn-primary" type="button" onclick="processQD('imHere', <?php echo $pickups['qd_location_id']; ?>)" title="You are at the location"><i class="fa fa-save"></i> I'm here</button>
															<?php } ?>
															<br>
															<br>
															<table class="table table-bordered table-hover" id="pickup_tbl" name="pickup_tbl">
																<thead>
																	<tr>
																		<td class="left">Item</td>
																		<td class="left">Requested Quantity</td>
																		<td class="left">Estimated Price</td>
																		<td class="left">Actual Quantity</td>
																		<td class="left">Actual Price</td>
																		<td class="left">Notes</td>
																	</tr>
																</thead>
																<tbody>
																	<?php if(isset($pickups['items'])) {?>
																	<?php foreach($pickups['items'] as $it) {?>
																		<tr>
																			<td class="left"><?php echo $it['item_desc']; ?></td>
																			<td class="left"><?php echo $it['est_quantity']; ?></td>
																			<td class="left"><?php echo number_format($it['est_price'],2); ?></td>
																			<td class="left"><?php echo $it['actual_quantity']; ?></td>
																			<td class="left"><?php echo number_format($it['actual_price'],2); ?></td>
																			<td class="left"><?php echo $it['notes']; ?></td>
																		</tr>
																	<?php } ?>
																	<?php } ?>
																	<tr>
																		<td colspan="2">&nbsp;</td>
																		<td class="left">Estimated Total: <b>Php <?php echo number_format($pickups['total_amount'],2); ?></b></td>
																		<td colspan="2" class="right">Actual Total: <b>Php <?php echo number_format($pickups['actual_amount_collected'],2); ?></b></td>
																		<td >&nbsp;</td>
																	</tr>
																</tbody>
															</table>									
														</td>
													</tr>
												<?php } ?>
											<?php } else { ?>
												<tr>
													<td colspan="2">
														No Pickup location selected yet.
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div><!-- panel-group -->

				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<label><i class="fa fa-dropbox "></i> Drop Off Location/s</label>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12" style="overflow:auto;">
									<table class="table table-bordered table-hover" id="drop_off_tbl" name="drop_off_tbl">
										<thead>
												<tr>
													<td class="left">Location</td>
													<td class="left">
														<?php if($segment1 != 'viewbooking') { ?>
															<button class="btn btn-info" type="button" onclick="addLocation('DROPOFF')" title="Edit"><i class="fa fa-plus"></i> Add Drop-Off Location</button>
														<?php } ?>
													</td>
												</tr>
										</thead>
										<tbody>
											<?php if(isset($currentBooking['dropofflocations'])) { ?>
												<?php foreach($currentBooking['dropofflocations'] as $dropoffs) { ?>
													<tr>
														<td colspan="2">
															Establishment/Customer Name: <b><?php echo $dropoffs['contact_name']; ?></b><br>
															Contact Number: <b><?php echo $dropoffs['contact_number']; ?></b><br>
															Address: <b><?php echo $dropoffs['street']. ", ". $dropoffs['brgy_desc']. ", ". $dropoffs['city_town_desc']. ", ". $dropoffs['province_desc']; ?></b><br>
															Landmark: <b><?php echo $dropoffs['landmark']; ?></b><br>
															Pickup Instruction: <b><?php echo $dropoffs['instruction']; ?></b><br>
															<!-- Location Status: <b><?php echo $dropoffs['status']; ?></b><br> -->
															Distance From Last Location: <b><?php echo $dropoffs['dist_from_last_loc']; ?> km</b><br>
															<a href="<?php echo $segment1 != 'viewbooking' ? 'bookingaddlocation' : 'viewbookinglocation'; ?>/<?php echo $dropoffs['quick_deliveries_id']; ?>/<?php echo $dropoffs['qd_location_id']; ?>/DROPOFF" class="btn btn-info"><i class="fa fa-search"></i> View Location</a>&nbsp;&nbsp;
															<?php if($segment1 != 'viewbooking') { ?>
															<button class="btn btn-danger" type="button" onclick="removeLocationSubmit(<?php echo $dropoffs['qd_location_id']; ?>)" title="This will remove this location to the Quick Delivery"><i class="fa fa-minus"></i> Remove this location</button>
															<?php } ?>
														</td>
													</tr>
												<?php } ?>
											<?php } else { ?>
												<tr>
													<td colspan="2">
														No Dropoff location selected yet.
													</td>
												</tr>
											<?php } ?>
											<tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>	
				<?php if ($this->user->getUserGroupID() == 111) { if ($currentBooking['special_flag'] == 2) { ?>
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<label><i class="fa fa-pencil "></i> Input</label>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">	
										<label>Delivery Fee</label>
										<input class="form-control input" type="number" id="delivery_fee" name="delivery_fee">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">	
										<label>&nbsp;</label><br>
										<button class="btn btn-primary" type="button" onclick="javascript: performTask('saveDeliveryFee');"><i class="fa fa-plus"></i> Save Delivery Fee</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } } ?>
			</div>																																									
			<?php if(in_array($this->user->getUserGroupId(), [47])) { ?>
				<?php if($segment1 == 'viewbooking') { ?>
					<?php if($currentBooking['status_id'] == 138) { ?>
					<div class="row">
						<center>
							<div class="col-md-12">
								<input class="btn btn-danger btn-block" type="button" id="book_delivery" value="Cancel Booking" onclick="javascript: cancelBooking();" >
							</div>
						</center>
					</div>
					<?php } ?>
				<?php } else { ?>
					<div class="row">
						<center>
							<div class="col-md-12">
								<input class="btn btn-primary" type="button" id="book_delivery" value="Confirm and Book this delivery" onclick="javascript: bookDelivery();" >
							</div>
						</center>
					</div>
					<br>
					<div class="row">
						<center>
							<div class="col-md-12">
								<input class="btn btn-dangers" type="button" id="book_delivery" value="Cancel Booking" onclick="javascript: cancelBooking();" >
							</div>
						</center>
					</div>
				<?php } ?>
			<?php } else if($segment1 == 'viewbooking'){ ?>
				<?php if(in_array($this->user->getUserGroupId(), [86, 87])) { ?>
					<div class="row">
						<center>
						<div class="col-md-12">
							<?php if($currentBooking['status_id'] == 138){  // pending ?>
								<input class="btn btn-primary btn-block" type="button" id="accept_booking" value="Accept this Quick Delivery" onclick="javascript: performTask('acceptBooking');" >
							<?php } ?>

							<?php if($currentBooking['status_id'] == 302 && $currentBooking['cancelled_button_visibility'] == 1){ // accepted ?>
								<div class="col-md-12">
									<input class="btn btn-danger btn-block" type="button" id="cancelled_by_customer" value="Cancelled by customer" onclick="javascript: processQD('cancelledByCustomer');" >
								</div>
							<?php } ?>

							<?php if($currentBooking['delivered_button_visibility'] == 1){ // item picked up ?>
								<input class="btn btn-primary btn-block" type="button" id="delivered" value="Delivered" onclick="javascript: processQD('delivered');" >
							<?php } ?>
							</div>
						</center>
					</div>
				<?php } ?>
			<?php } ?>
			<?php } ?> 
			<?php if ($currentBooking['current_qd_id'] != 0 ) { ?>
				<div class="row pull-center">
					<div class="sticky-pc hidden-sm hidden-xs">
						<div class="col-md-12" style="overflow:auto;">
							<p style="font-size:14px;"><b>Total Delivery Fee:</b></p>
							<p style="font-size:13px;"><?php echo number_format($currentBooking['delivery_fee'],2);?></p>
						</div>
					</div>
					<div class="sticky-cp hidden-md hidden-lg hidden-xl">
						<div class="col-sm-12 col-xs-12" style="overflow:auto;">
							<p style="font-size:12px;"><b>Total Delivery Fee:</b></p>
							<p style="font-size:11px;"><?php echo number_format($currentBooking['delivery_fee'],2);?></p>
						</div>
					</div>
				</div>
			<?php } ?>
		</form>
	</div>
</div>

	<div class="col-md-4">
	  <div class="modal fade" id="modal-proceed" tabindex="-1" role="dialog" aria-labelledby="modal-proceed" aria-hidden="true">
		<div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
		  <div class="modal-content bg-gradient-darkorange">
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
			  <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
			</div>
		  </div>
		</div>
	  </div>
	</div>

<!-- Modals -->
  <div class="row">
	<div class="col-md-4">
	  <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
		<div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
		  <div class="modal-content bg-gradient-darkorange">
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
			$('#msg').html("<?php echo $err_msg; ?>");	
			$('#modal-notification').modal('show');
		<?php } ?>
	<?php } ?>
});

function performTask(task) {
	$('#task').val(task);
	$('form').attr('action', 'booking'); 
	$('form').submit();
}

<?php if(isset($currentBooking['quick_deliveries_id'])){ ?>
function processQD(task, qd_location_id = 0) {
	$('#task').val(task);
	$('#qd_location_id').val(qd_location_id);
	$('form').attr('action', 'viewbooking/' + <?php echo $currentBooking['quick_deliveries_id']; ?>); 
	$('form').submit();
}
<?php } ?>

function removeLocationSubmit(qd_location_id) {
	$('#qd_location_id').val(qd_location_id);
	$('#task').val("removeLocationSubmit");
	$('form').attr('action', 'booking'); 
	$('form').submit();
}

function addLocation(location_type) {
	$('#location_type').val(location_type);
	$('#task').val("addLocation");
	$('form').attr('action', 'bookingaddlocation'); 
	$('form').submit();
}

function addLocationmerch(location_type) {
	$('#location_type').val(location_type);
	$('#task').val("addLocationmerch");
	$('form').attr('action', 'bookingaddlocationmerch'); 
	$('form').submit();
}

// function cancelBooking() {
	// var msg = "Are you sure you wait to cancel the Booking?";
	// $('#msg').html(msg);
	// $(function() {
		// $( "#modal-notification" ).dialog({
		  // modal: true,
		  // width: 300,
		  // buttons: {
			// Ok: function() {
				// $('#task').val("cancelBooking");					
				// $('form').attr('action', 'booking'); 
				// $('form').submit();	
				// $( this ).dialog( "close" );
			// },
			// Cancel: function() {
				// $( this ).dialog( "close" );
			// }			
		  // }	
		// });
	// });
// }

function cancelBooking() {
	$('#msg_proceed').html("Are you sure you wait to cancel the Booking?");
	$('#div_buttons').html("<button type=\"button\" onclick=\"cancelBooking1();\" class=\"btn btn-white\">Yes, Proceed.</button>");		
	$('#modal-proceed').modal('show');
}

function cancelBooking1() {
	$('#task').val("cancelBooking");					
	$('form').attr('action', 'booking'); 
	$('form').submit();	
}

function bookDelivery() {
	$('#msg_proceed').html("Are you sure you want to book this delivery?");
	$('#div_buttons').html("<button type=\"button\" onclick=\"bookDelivery1();\" class=\"btn btn-white\">Yes, Proceed.</button>");		
	$('#modal-proceed').modal('show');
}

function bookDelivery1() {
	$('#task').val("bookDelivery");					
	$('form').attr('action', 'booking'); 
	$('form').submit();	
}

function submitFromAffiliatedMerchant() {
	$('#task').val("submitFromAffiliatedMerchant");			
	$('form').attr('action', 'booking'); 
	$('form').submit();	
}

function initMap() {
	
}

<?php if(isset($referror)){ ?>
function showQD(qd_id) {

  $('#task').val('show_qd');
  $('#qd_id').val(qd_id);
  $('#visibility').val('to_show');
  $('form').attr('action', '<?php echo $referror; ?>'); 
  $('form').submit();
  
}
<?php } ?>
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCPqcTzbj3Wc1YuqYYa8IKYHG6EEumTGSM&libraries=places&callback=initMap" async defer></script>
<?php //echo $footer; ?>