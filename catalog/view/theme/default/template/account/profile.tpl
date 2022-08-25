<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<i class="ni ni-circle-08 ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Profile</h6>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="container-fluid mt--6">
		<div class="row">
			<div class="col-lg-6">
				<div class="card">
					<div class="card-header">
						<h1 class="mb-0">Informations</h1>
					</div>
					<div class="card-body">
						<form action="" method="post" enctype="multipart/form-data" id="form1">
							<input type="hidden" class="task" name="task" value="" />
							<div class="row">
								<div class="col-md-3"><label>Username:</label></div>
								<div class="col-md-9"><b><?php echo $this->user->getUsername(); ?></b></div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3"><label>Name:</label></div>
								<div class="col-md-9"><b><?php echo $this->user->getName(); ?></b></div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3"><label>Email:</label></div>
								<div class="col-md-9">
									<b><input type="text" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email" value="<?php echo $this->user->getEmail(); ?>"></b>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3"><label>Contact:</label></div>
								<div class="col-md-9">
									<b><input type="text" class="form-control" id="contact" name="contact" aria-describedby="emailHelp" placeholder="Enter Contact" value="<?php echo $this->user->getContact(); ?>"></b>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3"><label></label></div>
								<div class="col-md-9">
									<input type="hidden" class="form-control" id="btc_ewallet_address" name="btc_ewallet_address" aria-describedby="emailHelp" placeholder="Enter BTC E-Wallet Address" value="<?php echo $this->user->getContact(); ?>">
									<input class="btn btn-outline-user text-white btn-block" type="button" id="search" value="Update Profile"  onclick="javascript: processTask('editContacts','#form1');">
								</div>
							</div>
							<br>
						</form>
						<!--<br>-->
						<form action="" method="post" enctype="multipart/form-data" id="form2">
							<input type="hidden" class="task" name="task" value="" />
							<br>
							<div class="row">
								<div class="col-md-3"><label>Password:</label></div>
								<div class="col-md-9">
									<b><input type="password" class="form-control" id="password" name="password" aria-describedby="emailHelp" placeholder="Enter Password" value=""></b>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3"><label></label></div>
								<div class="col-md-9">
									<input class="btn btn-outline-user text-white btn-block" type="button" id="search" value="Update Password" onclick="javascript: processTask('editPassword','#form2');">
								</div>
							</div>
						</form>
					</div>
					<div class="card-header">
						<h1 class="mb-0">Bank Details</h1>
					</div>
					<div class="card-body">
						<form action="" method="post" enctype="multipart/form-data" id="form4">
							<input type="hidden" class="task" name="task" value="" />
							<div class="row">
								<div class="col-md-3"><label>Bank Name:</label></div>
								<div class="col-md-9">
									<select class="form-control" name="bank_name" id="bank_name" >
										<option value="128" ="">BDO</option>
									</select>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3"><label>BDO Account Name:</label></div>
								<div class="col-md-9">
									<input type="text" class="form-control" id="account_name"  name="account_name" value="<?php echo $details['account_name'] ?>">
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3"><label>BDO Account#:</label></div>
								<div class="col-md-9">
									<input type="text" class="form-control" value="<?php echo $details['account_no'] ?>" name="account_no" id="account_no" >
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3"><label></label></div>
								<div class="col-md-9">
									<input class="btn btn-outline-user text-white btn-block" type="button" id="search" value="Update Bank Account" onclick="javascript: processTask('editAccount','#form4');">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card">
					<div class="card-header">
						<h1 class="mb-0">Identification</h1>
					</div>
					<div class="card-body">
						<form action="" method="post" enctype="multipart/form-data" name="form" id="form5">
							<input type="hidden" class="task" name="task" value="" />
							<input type="hidden" class="ref" name="ref" id="ref" value="" />
							<div class="row">
								<div class="col-md-3"></div>
								<div class="col-md-5">
									<div class="form-group">	
										<label><b>Profile Picture</b></label>
										<input type="file" name="profile_pic" id="profile_pic" value=""  class="form-control" />
									</div>					
								</div>
							</div>
							<div class="row">
								<div class="col-md-2"></div>
								<div class="col-md-4">
									<a class="btn btn-outline-user text-white btn-sm" href="profile/<?php echo $reference['ref'];?>">Download ID</a>
								</div>
								<div class="col-md-5">
									<button class="btn btn-outline-user btn-sm text-white " type="button" onclick="javascript: processTask2('uploadprofilepic','#form5');">Upload Profile</button>
								</div>
							</div>
						</form>
					</div>
					<div class="card-header">
					  <h1 class="mb-0">Address</h1>
					</div>
					<div class="card-body">
						<form action="" method="post" enctype="multipart/form-data" id="form3">
							<input type="hidden" class="task" name="task" value="" />
							<div class="form-group row">
								<div class="col-sm-12">
									<label>Province:</label>
									<select class="form-control" id="checkout_provinces" name="checkout_provinces" onchange="getCitiesNewCustomer();">
									  <option value="0" selected readonly="readonly">Select Province</option>
										<?php if(isset($provinces)) { ?> 
											<?php foreach($provinces as $province) { ?>
												<?php if($this->user->getProvinceDesc() == $province['description']) { ?>
													<option value="<?php echo $province['province_id']; ?>" selected><?php echo $province['description']; ?></option>
												<?php } else { ?>
													<option value="<?php echo $province['province_id']; ?>"><?php echo $province['description']; ?></option>
												<?php } ?>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group row" id="city_hidden">
								<div class="col-sm-12">
									<label>City:</label>
									<select class="form-control" id="checkout_city" name="checkout_city" onchange="getBrgyRegister();">   
										<?php if($this->user->getCityMunicipalityId() > 0) { ?>
											<option value="<?php echo $this->user->getCityMunicipalityId(); ?>"><?php echo $this->user->getCityMunicipalityDesc(); ?></option>
										<?php } else { ?>
											<option value="0" selected readonly="readonly">Select City</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group row" id="brgy_hidden">
								<div class="col-sm-12">
									<label>Barangay:</label>
									<select class="form-control" id="checkout_barangay" name="checkout_barangay">                                      
										<?php if($this->user->getBarangayId() > 0) { ?>
											<option value="<?php echo $this->user->getBarangayId(); ?>"><?php echo $this->user->getBarangayDesc(); ?></option>
										<?php } else { ?>
											<option value="0" selected readonly="readonly">Select Brgy</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group row" id="cust_address_hidden">
								<div class="col-sm-12">
									<label>Address:</label>
									<input type="text" class="form-control" id="cust_address" name="cust_address" placeholder="House No. / St. / Blk & Lot" required value="<?php echo $this->user->getAddress();?>" />
								</div>
							</div> 
							<div class="form-group row" id="landmark_hidden">
								<div class="col-sm-12">
									<label>LandMark:</label>
									<input type="text" class="form-control" id="land_mark" name="land_mark" placeholder="" required value="<?php echo $this->user->getLandmark();?>"/>
								</div>
							</div> 
							<div class="row">
								<div ><label></label></div>
								<div class="col-sm-12">
									<input class="btn btn-outline-user text-white btn-block" type="button" id="search" value="Update Address" onclick="javascript: processTask('editAddress','#form3');">
								</div>
							</div>
						</form>
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
					<div class="modal-content bg-gradient-info">
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
		<div class="col-md-4">
			<div class="modal fade" id="modal-proceed" tabindex="-1" role="dialog" aria-labelledby="modal-proceed" aria-hidden="true">
				<div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
					<div class="modal-content bg-gradient-info">
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

function processTask(task, form) {
	var valid = 1;	
	var msg = "";
	
	if(task == "editContacts") {
		var email = $('#email').val();
		var contact = $('#contact').val();
		if(email == "") {
			valid = 0;
			msg = msg + "Email is mandatory.<br>";
		}
		
		if(contact == "0") {
			valid = 0;
			msg = msg + "Contact is mandatory.<br>";
		}
	}
	
	if(task == "editAccount") {
		var account_name = $('#account_name').val();
		var account_no = $('#account_no').val();
		if(email == "") {
			valid = 0;
			msg = msg + "Account Name is mandatory.<br>";
		}
		
		if(contact == "0") {
			valid = 0;
			msg = msg + "Account Number is mandatory.<br>";
		}
	}
	
	
	if(task == "editPassword") {
		var password = $('#password').val();
		if(password == "") {
			valid = 0;
			msg = msg + "Passwords is mandatory.<br>";
		}
	}
	
	
	if(valid == 1) {
		if(task == "editContacts") {
			$('#msg_proceed').html("Are you sure on the email and contact declared?");
		} else if(task == "editPassword") {
			$('#msg_proceed').html("Are you sure on the password declared?");
		} else if(task == "editAddress") {
			$('#msg_proceed').html("Are you sure on the Address declared?");
		} else if(task == "editAccount") {
			$('#msg_proceed').html("Are you sure on the Account declared?");
		}
		$('#div_buttons').html("<button type=\"button\" onclick=\"proceed('"+task+"', '"+form+"');\" class=\"btn btn-white\">Yes, Proceed.</button>");		
		$('#modal-proceed').modal('show');
	} else {
			$('#msg').html(msg);			      
			$('#modal-notification').modal('show');	
	} 
}

function processTask2(task, form) {
	var valid = 1;	
	var msg = "";
	
	if(task == "uploadprofilepic") {
		var profile_pic = $('#profile_pic').val();
		if(profile_pic == "") {
			valid = 0;
			msg = msg + "Please choose picture to upload.<br>";
		}
	}
	
	if(valid == 1) {
		if(task == "uploadprofilepic") {
			$('#msg_proceed').html("You want to upload your picture?");
		}
		$('#div_buttons').html("<button type=\"button\" onclick=\"proceed2('"+task+"', '"+form+"');\" class=\"btn btn-white\">Yes, Proceed.</button>");		
		$('#modal-proceed').modal('show');
	} else {
			$('#msg').html(msg);			      
			$('#modal-notification').modal('show');	
	} 
}

function editProfile(task, form) {
	$('#task').val("editProfile");
	$('form').attr('action', 'profile'); 
	$('form').submit();
}

function updatePassword() {
	$('#task').val("updatePassword");
	$('form').attr('action', 'profile'); 
	$('form').submit();
}

function copyToClipboard(url) {
	var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(url).select();
    document.execCommand("copy");
    $temp.remove();
	alert("URL copied.");
	return false;
}

function proceed(task, form) {
	$('.task').val(task);
	$(form).attr('action', 'profile'); 
	$(form).submit();
}

function proceed2(task, form) {
	$('.task').val(task);
	$(form).attr('action', 'uploadprofilepic'); 
	$(form).submit();
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
				$("#city_hidden").removeClass("hidden");
				
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
				
			}		
		}
	});
}
//--></script>
