<?php echo $header;?>

<div class="header bg-gradient-grayblack pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
			<i class="ni ni-square-pin ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp; Add Location</h6>
		</div>
	  </div>
	</div>
  </div>
</div>

<div class="container-fluid mt--6" id="add_location_div">
	<div class="panel">
		<form action="" method="post" enctype="multipart/form-data" id="form">
			<input type="hidden" id="task" name="task" value="">
			<input type="hidden" id="location_type" name="location_type" value="<?php echo $location_type; ?>">
			<input type="hidden" id="current_qd_id" name="current_qd_id" value="<?php echo $currentBooking['quick_deliveries_id'];?>">
			<input type="hidden" id="qd_location_id" name="qd_location_id" value="<?php echo $qd_location_id;?>">
			<input type="hidden" id="qd_location_item_id" name="qd_location_item_id" value="">
			<input type="hidden" id="merchant_id" name="merchant_id" value="<?php echo $merchant_id;?>">
			<input type="hidden" id="trans_session_id" name="trans_session_id" value="<?php echo $trans_session_id;?>">
			<!-- <div id="modal-notification" title="Confirmation Message" style="display:none; width: 400px;">
			  <span id="msg"></span>
			</div> -->
			<div class="row">
				<div class="col-md-12">	
					<div class="card">
						<div class="row">
							<div class="col-md-12">
								<div class="card-body">
									<div class="panel-heading">
										<label><i class="fa fa-location-arrow "></i> Quick Delivery Information</label>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-md-12" style="overflow:auto;">
												Quick Delivery Order#: <b><?php echo $currentBooking['quick_deliveries_code'];?></b><br>
												Customer Name: <b><?php echo $currentBooking['customer_name'];?></b><br>
												Required E-Wallet: <b>Php <?php echo number_format($currentBooking['required_e_wallet'],2);?></b><br>
												Required Cash-Onhand: <b>Php <?php echo number_format($currentBooking['required_cash_on_hand'],2);?></b><br>
												Location Type: <b><?php echo $location_type;?></b><br>
												Status: <b><?php echo $currentBooking['status']; ?></b><br>
												<?php if($segment1 == 'viewbookinglocation') { ?>
												Location Status: <b><?php echo $currentBookingLocation['status']; ?></b><br><br>
												<?php } ?>
												<input type="hidden" id="latitude" name="latitude" value="" >
												<input type="hidden" id="longitude" name="longitude" value="" >
											</div>		
										</div>
										<div class="row">
											<div class="col-md-12 text-right">
											<?php if($segment1 != 'viewbookinglocation') { ?>
												<button class="btn btn-primary" type="button" onclick="performTask2('')" title="Back to previous screen"><i class="fa fa-arrow-left"></i> Back to previous screen</button><br/><br/>
											<?php } ?>

											<?php if($segment1 == 'viewbookinglocation') { ?>
												<button class="btn btn-primary" type="button" onclick="javascript: window.location.href = '<?php echo LOCAL_PROD.'viewbooking/'.$currentBooking['quick_deliveries_id']; ?>';" title="Back to previous screen"><i class="fa fa-arrow-left"></i> Back to previous screen</button><br/><br/>
											<?php } ?>
											<?php if($segment1 != 'viewbookinglocation') { ?>
												<?php if($qd_location_id == 0) { ?>
													<button class="btn btn-info" type="button" onclick="performTask('addLocationSubmit')" title="This will add this location to the Quick Delivery"><i class="fa fa-plus"></i> Add this location</button>
												<?php } else { ?>
													<button class="btn btn-info" type="button" onclick="performTask('editLocationSubmit')" title="This will update this location to the Quick Delivery"><i class="fa fa-plus"></i> Update this location</button>
													<button class="btn btn-danger" type="button" onclick="performTask2('removeLocationSubmit')" title="This will remove this location to the Quick Delivery"><i class="fa fa-minus"></i> Remove this location</button>
												<?php } ?>
											<?php } ?>
											</div>
										</div>
										<br>
										
										<div class="row">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-6">							
														<div class="form-group row">
															<div class="col-sm-12">
																<label>Establishment/Customer Name:</label>
																<input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Customer Name" value="<?php if(isset($currentBookingLocation['contact_name'])) { echo $currentBookingLocation['contact_name']; } ?>" <?php if($segment1 == 'viewbookinglocation') { echo 'disabled'; } ?>/>
															</div>
														</div>
														
														<div class="form-group row">
															<div class="col-sm-12">
																<label>Contact Number:</label>
																<input type="text" class="form-control " id="contactno" name="contactno" placeholder="Contact no." value="<?php if(isset($currentBookingLocation['contact_number'])) { echo $currentBookingLocation['contact_number']; } ?>" <?php if($segment1 == 'viewbookinglocation') { echo 'disabled'; } ?> />
															</div>
														</div>
														<div class="form-group row">
															<div class="col-sm-12">
																<label>Landmarks:</label>
																<input type="text" class="form-control " id="landmarks" name="landmarks" placeholder="Landmarks" value="<?php if(isset($currentBookingLocation['landmark'])) { echo $currentBookingLocation['landmark']; } ?>" <?php if($segment1 == 'viewbookinglocation') { echo 'disabled'; } ?> />
															</div>
														</div>
														<div class="form-group row">
															<div class="col-sm-12">
																<label>Special Instruction:</label>
																<input type="text" class="form-control " id="instructions" name="instructions" placeholder="Landmarks" value="<?php if(isset($currentBookingLocation['instruction'])) { echo $currentBookingLocation['instruction']; } ?>" <?php if($segment1 == 'viewbookinglocation') { echo 'disabled'; } ?> />
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group row">
															<div class="col-sm-12">
																<label>Province:</label>
																<select class="form-control" id="new_province" name="new_province" onchange="getCitiesNewCustomer()" <?php if($segment1 == 'viewbookinglocation') { echo 'disabled'; } ?>>
																	<?php if($qd_location_id > 0) { ?>
																		<option value="<?php echo $currentBookingLocation['province']; ?>" selected readonly="readonly"><?php echo $currentBookingLocation['province_desc']; ?></option>
																	<?php } else { ?>
																		<option value="0" selected readonly="readonly">Select Province</option>
																	<?php } ?>
																	<?php if(isset($provinces)){
																	foreach($provinces as $p){ ?>
																		<option value="<?php echo $p['province_id']; ?>"><?php echo $p['description']; ?></option>
																	<?php }
																	} ?>
																</select>
															</div>
														</div>
														<div class="form-group row" id="city_hidden">
															<div class="col-sm-12">
																<label>City:</label>
																<select class="form-control" id="city_town" name="city_town" onchange="getBrgyRegister()" <?php if($segment1 == 'viewbookinglocation') { echo 'disabled'; } ?>>        
																	<?php if($qd_location_id > 0) { ?>
																		<option value="<?php echo $currentBookingLocation['city']; ?>" selected readonly="readonly"><?php echo $currentBookingLocation['city_town_desc']; ?></option>
																	<?php } else { ?>
																		<option value="0" selected readonly="readonly">Select City</option>
																	<?php } ?>
																</select>
															</div>
														</div>
														<div class="form-group row" id="brgy_hidden">
															<div class="col-sm-12">
																<label>Barangay:</label>
																<select class="form-control" id="barangay" name="barangay" <?php if($segment1 == 'viewbookinglocation') { echo 'disabled'; } ?>>
																	<?php if($qd_location_id > 0) { ?>
																		<option value="<?php echo $currentBookingLocation['barangay']; ?>" selected readonly="readonly"><?php echo $currentBookingLocation['brgy_desc']; ?></option>
																	<?php } else { ?>
																		<option value="0" selected readonly="readonly">Select Brgy</option>
																	<?php } ?>
																</select>
															</div>
														</div>
														<div class="form-group row" id="cust_address_hidden">
															<div class="col-sm-12">
																<label>Street:</label>
																<input type="text" class="form-control" id="cust_address" name="cust_address" placeholder="House No. / St. / Blk & Lot" value="<?php if(isset($currentBookingLocation['street'])) { echo $currentBookingLocation['street']; } ?>" <?php if($segment1 == 'viewbookinglocation') { echo 'disabled'; } ?> />
															</div>
														</div>		
													</div>
												</div>
											</div>
										</div>										
										<div class="row">
											<div class="col-md-12" style="overflow:auto;">
												<div id="map" style="height:400px; width:100%"></div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- panel-group -->
							<?php if($location_type == "PICKUP" and $qd_location_id > 0) { ?>
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<label><i class="fa fa-location-arrow "></i> Items for Pick-up</label>
									</div>
									<div class="panel-body" id="add_items_div">
										<div class="row">
											<div class="col-md-12 text-right">
											<?php if($segment1 != 'viewbookinglocation') { ?>
												<?php if(isset($currentBookingLocation['merchant_id'])) { ?>
													<?php if($currentBookingLocation['merchant_id'] > 0) { ?>
														<button class="btn btn-info" type="button" data-toggle="modal" data-target="#merchant-items-modal" ><i class="fa fa-plus"></i> Add Predefined Items</button>
													<?php } ?>
												<?php } ?>
												<button class="btn btn-info" type="button" data-toggle="modal" data-target="#manual-items-modal" ><i class="fa fa-plus"></i> Add Specific Items</button>
											<?php } ?>
											</div>
										</div>
										<br>
										<div class="row">
											<div class="col-md-12" style="overflow:auto;">
												<table class="table table-bordered table-hover" id="pickup_tbl" name="pickup_tbl">
													<thead>
														<tr>
															<td class="left">Item/Description</td>
															<td class="left">Quantity</td>
															<td class="left">Estimated Price</td>
															<td class="left">Notes for this Item</td>
															<?php if($segment1 == 'viewbookinglocation' && $currentBooking['status_id'] != 138) { ?>
																<?php if($currentBookingLocation['status_id'] == 244 || $currentBookingLocation['status_id'] == 245 || $currentBookingLocation['status_id'] == 247 || $currentBookingLocation['status_id'] == 140){ // item picked up or finished ?>
																	<td class="left">Actual Qty</td>
																	<td class="left">Actual Price</td>
																<?php } ?>
															<?php } ?>
															<?php if($currentBooking['status_id'] == 0) { ?>
																<td class="left">
																	&nbsp;
																</td>
															<?php } ?>
														</tr>
													</thead>
													<tbody>
														<?php if(isset($currentBookingLocation['items'])) { ?>
															<?php foreach($currentBookingLocation['items'] as $item) { ?>
																<tr>
																	<td class="left"><?php echo $item['item_desc']; ?></td>
																	<td class="left"><?php echo $item['est_quantity']; ?></td>
																	<td class="left"><?php echo number_format($item['est_price'],2); ?></td>
																	<td class="left"><?php echo $item['notes']; ?></td>
																	<?php if($segment1 == 'viewbookinglocation' && $currentBooking['status_id'] != 138) { ?>
																		<?php if($currentBookingLocation['status_id'] == 245 || $currentBooking['status_id'] == 247 || $currentBooking['status_id'] == 140){ // item picked up or finished ?>
																			<td class="left"><?php echo $item['actual_quantity']; ?></td>
																			<td class="left"><?php echo number_format($item['actual_price'],2); ?></td>
																		<?php } ?>
																	<?php } ?>
																	<?php if($segment1 == 'viewbookinglocation' && $currentBooking['status_id'] != 138) { ?>
																		<?php if($currentBookingLocation['status_id'] == 244){ // item picked up ?>
																			<input type="hidden" style="width:75px;" class="form-control" name="qd_location_item_id[]" value="<?php echo $item['qd_location_item_id']; ?>"/>
																			<td class="left"><input type="number" style="width:75px;" class="form-control" onchange="calculateItems();" name="actual_quantity[]" value="<?php echo empty($item['actual_quantity']) ? $item['est_quantity'] : $item['actual_quantity'];?>"/></td>
																			<td class="left"><input type="number" style="width:75px;" class="form-control" onchange="calculateItems();" name="actual_price[]" value="<?php echo $item['actual_price'] == 0 ? $item['est_price'] : $item['actual_price']; ?>"/></td>
																		<?php } ?>
																	<?php } ?>
																	<?php if($currentBooking['status_id'] == 0) { ?>
																		<td class="left">
																			<button class="btn btn-danger" type="button" onclick="removeItemSubmit(<?php echo $item['qd_location_item_id']; ?>)" title="This will remove this item from the Quick Delivery"><i class="fa fa-minus"></i> Remove</button>
																		</td>
																	<?php } ?>
																</tr>
															<?php } ?>
														<?php } ?>
														<tr>
															<td colspan="3" class="text-right">Estimated Total: Php <?php echo $currentBookingLocation['total_amount']; ?></td>
															<?php if($segment1 == 'viewbookinglocation' && $currentBooking['status_id'] != 138) { ?>
																<?php if($currentBookingLocation['status_id'] == 244 || $currentBookingLocation['status_id'] == 245 || $currentBookingLocation['status_id'] == 247 || $currentBookingLocation['status_id'] == 140){ // item picked up or finished or cancelled ?>
																	<td colspan="3" id="actual_total_amount" class="text-right">Actual Total: Php <?php echo  $currentBookingLocation['actual_amount_collected']; ?></td>
																<?php } ?>
															<?php } ?>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div><!-- panel-group -->
						</div>
							<?php if($segment1 == 'viewbookinglocation' && $currentBooking['status_id'] != 138){ ?>
								<?php if(in_array($this->user->getUserGroupId(), [86, 87])) { ?>
									<div class="row">
										<center>
										<div class="col-md-12">

											<?php if($currentBookingLocation['status_id'] == 138){ // pending ?>
												<input class="btn btn-primary btn-block" type="button" id="im_here" value="I'm here" onclick="javascript: processQD('imHere');" >
											<?php } ?>

											<?php if($currentBookingLocation['status_id'] == 244){ // im here ?>
												<div class="col-md-4 hide">
													<input class="btn btn-primary btn-block" type="button" id="transfer_delivery" value="Transfer Delivery" onclick="javascript: alert('transferDelivery');" >
												</div>
												<div class="col-md-6 hide">
													<input class="btn btn-danger btn-block" type="button" id="cancelled_by_customer" value="Cancelled by customer" onclick="javascript: processQD('cancelledByCustomer');" >
												</div>
												<div class="col-md-12">
													<input class="btn btn-primary btn-block" type="button" id="item_picked_up" value="Item picked up" onclick="javascript: processQD('itemPickedUp');" >
												</div>
											<?php } ?>

											</div>
										</center>
									</div>
								<?php } ?>
							<?php } ?>
						<?php } ?>																
					</div>										
				</div>
			</div>
			<!-- Modal of Merchants in the area -->
			<div id="merchant-items-modal" class="modal" role="dialog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-map"></i> Add Predefined Items</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>						
						<div class="modal-body">
							<br>
							<br>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
								<input class="btn btn-primary" type="button" id="delivered" value="Add this Location" onclick="javascript: submitFromAffiliatedMerchant();" >
							</div>
						</div>					 
					</div>
				</div>
			</div>
			<!-- End Modal of Merchants in the area -->	
			<!-- Modal of Merchants in the area -->
			<div id="manual-items-modal" class="modal" role="dialog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-map"></i> Add Specific Items</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>						
						<div class="modal-body">
							<br>
							<div class="col-md-12">
								<div class="row ">			
									<div class="col-md-12" >
										<table class="table table-bordered table-hover" id="pickup_tbl" name="pickup_tbl">
											<thead>
												<tr>
													<td class="left">#</td>
													<td class="left">Item/Description</td>
													<td class="left">Quantity</td>
													<td class="left">Estimated Price</td>
													<td class="left">Notes for this Item</td>
												</tr>
											</thead>
											<tbody>
												<?php for($i=1;$i<=10;$i++) { ?>
												<tr>
													<td class="left"><?php echo $i; ?></td>
													<td class="left"><input class="form-control" type="text" id="item_desc<?php echo $i; ?>" name="item_desc<?php echo $i; ?>" value=""></td>
													<td class="left"><input class="form-control" type="number" id="quantity<?php echo $i; ?>" name="quantity<?php echo $i; ?>" value="0.00"></td>
													<td class="left"><input class="form-control" type="number" id="estimated_price<?php echo $i; ?>" name="estimated_price<?php echo $i; ?>" value="0.00"></td>
													<td class="left"><input class="form-control" type="text" id="notes<?php echo $i; ?>" name="notes<?php echo $i; ?>" value=""></td>
												</tr>
												<?php } ?>
											</tbody>
										</table>																																					
									</div>
								</div>
							</div>
							<br>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
								<input class="btn btn-primary" type="button" id="delivered" value="Add Item/s" onclick="javascript: addLocationManualItems();" >
							</div>
						</div>					 
					</div>
				</div>
			</div>
			<!-- End Modal of Merchants in the area -->				
		</form>
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
	
	// $(function () {
	  // $("select").select2();
	// });
	
	<?php if(isset($move_to)) { ?>
		<?php if(!empty($move_to)) { ?>
			moveToLocation("#"+"<?php echo $move_to; ?>");
		<?php } ?>
	<?php } ?>

	<?php if($segment1 == 'viewbookinglocation' && $currentBooking['status_id'] != 138) { ?>
		<?php if($currentBookingLocation['status_id'] == 244){ // item picked up ?>
			calculateItems();
		<?php } ?>
	<?php } ?>
	
});

<?php if(in_array($this->user->getUserGroupId(), [86, 87])){ ?>
/*
function updateItems(task){
	
	$('#task').val(task);
	$('form').attr('action', 'viewbookinglocation'); 
	$('form').submit();

}
*/

function processQD(task) {

	$('#task').val(task);
	$('form').attr('action', 'viewbookinglocation'); 
	$('form').submit();

}

function calculateItems(){

	var allActualQuantities = document.querySelectorAll("input[name='actual_quantity[]']");
	var allActualAmounts = document.querySelectorAll("input[name='actual_price[]']");
	var totalActualAmount = 0.00;

	for(var i = 0; i < allActualQuantities.length; i++){
		totalActualAmount += parseInt(allActualQuantities[i].value) * parseFloat(allActualAmounts[i].value);
	}

	document.getElementById('actual_total_amount').innerHTML = `Actual Total: Php ${totalActualAmount.toFixed(2)}`;

}
<?php } ?>

function moveToLocation(move_to) {
    $("#add_location_div").scrollTop(300);
}

function getCitiesNewCustomer() {
	var province_id = $('#new_province').val();

	$.ajax({
		url: 'index.php?route=account/cart/getCities&province_id=' + province_id,
		type: 'get',
		dataType: 'json',
		success: function(json) {	 						
			if (json['status'] == "success") {
				var length = json['cities'].length;	
				var cities = "<option value='0' selected disabled>Select City</option>";
				for(var i = 0; i < length; i++){
					cities += "<option value='" + json['cities'][i]['city_municipality_id'] +"'>" + json['cities'][i]['description'] + "</td></tr>";
				}
				
				$("#city_town").html(cities);
				$("#barangay").html("<option value='0' selected disabled>Select Brgy</option>");
				$("#city_hidden").removeClass("hidden");
				
			}		
		}
	});
}

function getBrgyRegister() {
	var city_id = $('#city_town').val().split("-");

	$.ajax({
		url: 'index.php?route=account/cart/getBarangay&city_id=' + city_id,
		type: 'get',
		dataType: 'json',
		success: function(json) {	 						
			if (json['status'] == "success") {		
				var length = json['brgy'].length;	
				var brgy = "<option value='0' selected disabled>Select Brgy</option>";
				for(var i = 0; i < length; i++){
					brgy += "<option value='" + json['brgy'][i]['barangay_id'] +"'>" + json['brgy'][i]['description'] + "</td></tr>";
				}

				$("#barangay").html(brgy);
				
			}		
		}
	});
}


function performTask(task) {
	if(task == 'addLocationSubmit'){
		var customer_name = $("#customer_name").val();
		var contactno = $("#contactno").val();
		var new_province = $("#new_province").val();
		var city_town = $("#city_town").val();
		var barangay = $("#barangay").val();
		var cust_address = $("#cust_address").val();
		var proceed = 1;
		var msg = "";

		if(customer_name == ''){
			msg += "Customer name is required.\n";
			proceed = 0;
		}

		if(contactno == ''){
			msg += "Contact No. is required.\n";
			proceed = 0;
		}

		if(new_province == null || new_province == 0){
			msg += "Province is required.\n";
			proceed = 0;
		}

		if(city_town == null || city_town == 0){
			msg += "City is required.\n";
			proceed = 0;
		}

		if(barangay == null || barangay == 0){
			msg += "Province is required.\n";
			proceed = 0;
		}

		if(cust_address == ''){
			msg += "Street is required.\n";
			proceed = 0;
		}

		if(proceed == 0){

			msg = "Please check the following errors: \n\n" + msg;
			alert(msg);

		} else {

			$('#task').val(task);
			$('form').attr('action', 'bookingaddlocation'); 
			$('form').submit();
				
		}
	} else {
		$('#task').val(task);
		$('form').attr('action', 'bookingaddlocation'); 
		$('form').submit();
	}
	
}

function performTask2(task) {
	$('#task').val(task);
	$('form').attr('action', 'booking'); 
	$('form').submit();
}

function addLocation(location_type) {
	alert(location_type);
	$('#location_type').val(location_type);
	$('#task').val("addLocation");
	$('form').attr('action', 'bookingaddlocation'); 
	$('form').submit();
}

function refreshMap() {
	map = new google.maps.Map(document.getElementById('map'), {
          zoom: 17,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
        });
	infoWindow = new google.maps.InfoWindow;

	// Try HTML5 geolocation.
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			var pos = {
				lat: position.coords.latitude,
				lng: position.coords.longitude
			};

			document.getElementById("latitude").value = position.coords.latitude;
			document.getElementById("longitude").value = position.coords.longitude;
			
			var image = '<?php echo LOCAL_PROD;?>image/markers/jollibee.png';
			map.setCenter(pos);
			var marker = new google.maps.Marker({
				position: pos,
				map: map,
				icon: image,
				title: 'Move this marker to place to best position',
				draggable: <?php echo $segment1 == 'viewbookinglocation' ? 'false' : 'true'; ?>
			});
			
			google.maps.event.addListener(marker, 'dragend', function (event) {
				document.getElementById("latitude").value = this.getPosition().lat();
				document.getElementById("longitude").value = this.getPosition().lng();
				//alert("Latitude: " + this.getPosition().lat() + ", Longitude: " + this.getPosition().lng());
			});
		}, function() {
			handleLocationError(true, infoWindow, map.getCenter());
		});
	} else {
		// Browser doesn't support Geolocation
		handleLocationError(false, infoWindow, map.getCenter());
	}
}

function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {
          zoom: 17,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
        });

	// Try HTML5 geolocation.
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			//qd_location_id = <?php echo $qd_location_id;?>
			
			<?php if($qd_location_id > 0) { ?>
				var pos = {
					lat: <?php echo $currentBookingLocation['latitude'];?>,
					lng: <?php echo $currentBookingLocation['longitude'];?>
				};
				document.getElementById("latitude").value = <?php echo $currentBookingLocation['latitude'];?>;
				document.getElementById("longitude").value = <?php echo $currentBookingLocation['longitude'];?>;
			<?php } else { ?>
				var pos = {
					lat: position.coords.latitude,
					lng: position.coords.longitude
				};
				document.getElementById("latitude").value = position.coords.latitude;
				document.getElementById("longitude").value = position.coords.longitude;
			<?php } ?>	
			map.setCenter(pos);

			var marker = new google.maps.Marker({
				position: pos,
				map: map,
				title: 'Pointer Location',
				draggable: <?php echo $segment1 == 'viewbookinglocation' ? 'false' : 'true'; ?>,
				customInfo: "Manong Food Merchant A,12345"
			});
			
			google.maps.event.addListener(marker, 'dragend', function (event) {
				document.getElementById("latitude").value = this.getPosition().lat();
				document.getElementById("longitude").value = this.getPosition().lng();
				// window.alert(this.getPosition().lng());
				markerInfos = marker.customInfo.split(",");
			});
			
			google.maps.event.addListener(marker, 'click', function (event) {
				document.getElementById("latitude").value = this.getPosition().lat();
				document.getElementById("longitude").value = this.getPosition().lng();
				// window.alert(this.getPosition().lng());
				markerInfos = marker.customInfo.split(",");
				<?php if($segment1 != 'viewbookinglocation') { ?>
				addLocationSubmitWithMarkers(markerInfos[0], markerInfos[1]);
				<?php } ?>
			});
			
			<?php if(isset($currentBookingLocation['long_description'])){ ?>
				var long_description = `<?php echo addslashes($currentBookingLocation['long_description']);?>`;
			<?php } else { ?>
				var long_description = ``;
			<?php } ?>

			<?php if($qd_location_id > 0) { ?>
				var contentString = '<div id="content">'+
					  '<div id="siteNotice">'+
					  '</div>'+
					  '<h3 id="firstHeading" class="firstHeading"><?php echo $currentBookingLocation['contact_name'];?></h3>'+
					  '<div id="bodyContent">'+
					  '<p>'+long_description+'</p>'+
					  '</div>'+
					  '</div>';

				var infowindow = new google.maps.InfoWindow({
					content: contentString
				});
				
				infowindow.open(map, marker);
			<?php } ?>
			
		}, function() {
			handleLocationError(true, infoWindow, map.getCenter());
		});
	} else {
		// Browser doesn't support Geolocation
		handleLocationError(false, infoWindow, map.getCenter());
	}
}

function addLocationManualItems() {
	$('#task').val("addLocationManualItems");					
	$('form').attr('action', 'bookingaddlocation'); 
	$('form').submit();	
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
	infoWindow.setPosition(pos);
	infoWindow.setContent(browserHasGeolocation ?
	'Error: The Geolocation service failed.' :
	'Error: Your browser doesn\'t support geolocation.');
	infoWindow.open(map);
}

function removeItemSubmit(qd_location_item_id) {
	var msg = "Are you sure you want to remove this item from the quick delivery?";
	$('#msg').html(msg);
	$(function() {
		$( "#modal-notification" ).dialog({
		  modal: true,
		  width: 300,
		  buttons: {
			Ok: function() {
				$('#qd_location_item_id').val(qd_location_item_id);
				$('#task').val("removeItemSubmit");					
				$('form').attr('action', 'bookingaddlocation'); 
				$('form').submit();	
				$( this ).dialog( "close" );
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}			
		  }	
		});
	});
}

function addLocationSubmitWithMarkers(name, id) {
	
	$('#msg').html("Are you sure you want to add " + name + " as " + $('#location_type').val() + " location?");
	$(function() {
		$("#modal-notification").dialog({
			modal: true,
			width: 600,
			buttons: {
				Proceed: function() {
					$('#task').val("addLocationSubmitWithMarkers");
					$('#merchant_id').val(id);
					$('form').attr('action', 'bookingaddlocation'); 
					$('form').submit();
					$(this).dialog("close");
				},
				Cancel: function() {
					$(this).dialog("close");
				}
			}
		});
	});
}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCPqcTzbj3Wc1YuqYYa8IKYHG6EEumTGSM&libraries=places&callback=initMap" async defer></script>

<!-- </div>
</div>
</div>
</body>
</html> -->