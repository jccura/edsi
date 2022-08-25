<?php echo $header; ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBOXfEwS56A61L3_-5hh8ETPsfAnIy63Sc"></script> 
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
		  <i class="ni ni-bag-17 ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Barangay Maintenance</h6>
		</div>
	  </div>
	</div>
  </div>
</div>
<div class="container-fluid mt--6">
	<form action="" method="post" enctype="multipart/form-data" id="form">
		<input type="hidden" id="task" name="task" value="">		  
		<div class="card bg-default shadow">
			<div class="card-header bg-default">
				<h5 class="text-white"><i class="fa fa-pencil-square-o"></i> Actions</h5>
			</div>
			<div class="col-md-12">
				<p class="text-white"><i><small>Note: Choose a Operator below that you want to assign on the selected Barangays then Click Assign Button.</small></i></p>
				<div class="row">	
					<div class="col-md-4">
						<div class="form-group">
							<label class="text-white">Province:</label>
							<select class="form-control" id="checkout_provinces" name="checkout_provinces" onchange="getCitiesNewCustomer();">
							  <option value="0" selected readonly="readonly">Select Province</option>
								<?php if(isset($provinces)) { ?> 
									<?php foreach($provinces as $province) { ?>
										<?php if($pagedata['checkout_provinces'] == $province['province_id']) { ?>
											<option value="<?php echo $province['province_id']; ?>" selected><?php echo $province['description']; ?></option>
										<?php } else { ?>
											<option value="<?php echo $province['province_id']; ?>"><?php echo $province['description']; ?></option>
										<?php } ?>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group" id="city_hidden">
							<label class="text-white">City:</label>
							<select class="form-control" id="checkout_city" name="checkout_city" onchange="getBrgyRegister();">
								<?php if(isset($pagedata['checkout_city'])) { ?>
									<option value="<?php echo $pagedata['checkout_city'];?>" selected ><?php echo $pagedata['city_desc'];?></option>
								<?php } else { ?>
									<option value="0" readonly="readonly">Select City (Province First)</option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group" id="brgy_hidden">
							<label class="text-white">Barangay:</label>
							<select class="form-control" id="checkout_barangay" name="checkout_barangay"> 
								<?php if(isset($pagedata['checkout_barangay'])) { ?>
									<option value="<?php echo $pagedata['checkout_barangay'];?>" selected ><?php echo $pagedata['brgy_desc'];?></option>
								<?php } else {?>
									<option value="0" readonly="readonly">Select Brgy (City First)</option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">  
					<div class="col-md-3">
						<div class="form-group">
							<select class="form-control input" id="city_dist" name="city_dist" >
								<option value="0"> Select Area Operator</option>
								<?php if(isset($citydist)) { ?>
									<?php foreach($citydist as $cd) { ?>
										<?php if($pagedata['city_dist'] == $cd['user_id']) { ?>
											<option value="<?php echo $cd['user_id']?>" selected><?php echo $cd['username']?> (<?php echo $cd['firstname']." ".$cd['lastname'];?>)</option>
										<?php } else { ?>
											<option value="<?php echo $cd['user_id']?>"><?php echo $cd['username']?> (<?php echo $cd['firstname']." ".$cd['lastname'];?>)</option>
										<?php } ?>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<select class="form-control input" id="within_city" name="within_city">
								<option value="all">Select Proximity</option>
								<option value="1">Inside the City</option>
								<option value="0">Outside the City</option>
							</select>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
							<input class="btn btn-outline-user text-white btn" type="button" value="Search" onclick="javascript:search()">&nbsp;
							<button class="btn btn-success mr-1" type="button" onclick="javascript:assign()" ><i class="fa fa-check"></i> Assign</button>&nbsp;
							<button class="btn btn-danger mr-1" type="button" onclick="javascript:unassign()" ><i class="fa fa-check"></i> Un-Assign</button>
						</div>
					</div>
				</div>  
			</div>
		</div>
		<br>			
		<div class="row">
			<div class="col-md-12">
				<div align="col-md-12">
					<div class="table-responsive" style="width: 100%;">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
									<td class="left">Province</td>
									<td class="left">City</td>
									<td class="left">Barangays</td>
									<td class="left">Area Operator</td>
									<td class="left">Within City</td>
								</tr>
							</thead>
							<tbody>
								<?php if (isset($brgylist)) { ?>								
									<?php foreach ($brgylist as $c) { ?>											
										<tr>
											<td style="text-align: center;">
												<input type="checkbox" name="selected[]" value="<?php echo $c['barangay_id'];?>"/>
											</td>
											<td><?php echo $c['province']?>
											<td><?php echo $c['city'];?></td>
											<td><?php echo $c['barangays'];?></td>
											<td><?php echo $c['city_dist'];?></td>
											<td><?php if($c['city_distributor_id'] > 0) { echo $c['within_city']; } else { echo "Not Applicable"; }?></td>
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
	</form>
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
	
	$('#checkout_provinces').change(function() {
		getCitiesNewCustomer();
	});
	
	$('#checkout_provinces').change(function() {
		getBrgyRegister();
	});
	
});

function getBrgyRegister() {
	var city_id = $('#checkout_city').val().split("-");

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

				$("#checkout_barangay").html(brgy);
				
			}		
		}
	});
}

function search() {
    $('#task').val('search');
    $('form').attr('action', 'brgymaint');
    $('form').submit();
}


function assign(){
    $('#task').val('assign');
    $('form').attr('action', 'brgymaint');
    $('form').submit();
}

function unassign(){
    $('#task').val('unassign');
    $('form').attr('action', 'brgymaint');
    $('form').submit();
}

function unAssigned(){
    $('#task').val('unAssigned');
    $('form').attr('action', 'brgymaint');
    $('form').submit();
}
 

function addBranch(){
    $('#task').val('addBranch');
    $('form').attr('action', 'brgymaint');
    $('form').submit();
}

function updateBranchCoordinates(){
    $('#task').val('updateBranchCoordinates');
    $('form').attr('action', 'brgymaint');
    $('form').submit();
}

function addDesignatedUser(){
    $('#task').val('addDesignatedUser');
    $('form').attr('action', 'brgymaint');
    $('form').submit();
}

function updateBranchName(){
    $('#task').val('updateBranchName');
    $('form').attr('action', 'brgymaint');
    $('form').submit();
}

function getCitiesNewCustomer() {
	var province_id = $('#checkout_provinces').val();

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
				
				$("#checkout_city").html(cities);
				$("#checkout_barangay").html("<option value='0' selected disabled>Select Brgy</option>");
				$("#city_hidden").removeClass("hidden");
				document.getElementById("delivery_option_desc").value = "";
				document.getElementById("delivery_option").value = "";
				document.getElementById("payment_option").value = "";
				document.getElementById("payment_option").disabled = true;
				
			}		
		}
	});
}

function getBrgyRegister() {
	var city_id = $('#checkout_city').val().split("-");

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

				$("#checkout_barangay").html(brgy);
				document.getElementById("delivery_option_desc").value = "";
				document.getElementById("delivery_option").value = "";
				document.getElementById("payment_option").value = "";
				document.getElementById("payment_option").disabled = true;
				
			}		
		}
	});
}

//--></script>