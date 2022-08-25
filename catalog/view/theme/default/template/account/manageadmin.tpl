<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<i class="ni ni-building ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Manage Admin</h6>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container mt--6">
    <div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
			    <form action="" method="post" enctype="multipart/form-data" id="form">
					<input type="hidden" id="task" name="task" value="">      
					<input type="hidden" id="user_id" name="user_id" value="">		
					<input type="hidden" id="user_group_id" name="user_group_id" value="">			
					<!-- Modal for new Admin -->
					<div id="admin-modal" class="modal" role="dialog">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header bg-info">
									<h5 class="modal-title text-white" id="exampleModalLabel"><i class="fa fa-user-plus"></i> Add Admin</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<input type="hidden" value="0" id="valid-username">
									<div class="alert alert-danger alert-dismissible collapse" role="alert" id="username-checker">
										<span class="alert-icon"><i class="fa fa-exclamation-triangle"></i></span>
										<span class="alert-text"><strong>Error:</strong> Username not available or already been used.</span>
									</div>
									<input type="hidden" value="0" id="valid-contact">
									<div class="alert alert-danger alert-dismissible collapse" role="alert" id="contact-checker">
										<span class="alert-icon"><i class="fa fa-exclamation-triangle"></i></span>
										<span class="alert-text"><strong>Error:</strong> Contact Number not available or already been used.</span>
									</div>
									<input type="hidden" value="0" id="valid-email">
									<div class="alert alert-danger alert-dismissible collapse" role="alert" id="email-checker">
										<span class="alert-icon"><i class="fa fa-exclamation-triangle"></i></span>
										<span class="alert-text"><strong>Error:</strong> Email not available or already been used.</span>
									</div>
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-6">
												<h3>Admin User Info</h3>
												<hr />
												<div class="form-group row">
													<div class="col-sm-12">
														<label>Firstname:</label>
														<input type="text" class="form-control" id="firstname" name="firstname" required />
													</div>
												</div>
												<div class="form-group row">
													<div class="col-sm-12">
														<label>Middlename:</label>
														<input type="text" class="form-control " id="middlename" name="middlename" required />
													</div>
												</div>
												<div class="form-group row">
													<div class="col-sm-12">
														<label>Lastname:</label>
														<input type="text" class="form-control " id="lastname" name="lastname" required />
													</div>
												</div>
												<div class="form-group row">
													<div class="col-sm-12">
														<label>Contact No:</label>
														<input type="text" class="form-control " id="contactno" name="contactno" onchange="javascript: checkContact();" required />
													</div>
												</div>
												<div class="form-group row">
													<div class="col-sm-12">
														<label>Email:</label>
														<input type="text" class="form-control " id="email" name="email" onchange="javascript: checkEmail();" required />
													</div>
												</div>
												<div class="form-group row">
													<div class="col-sm-12">
														<label>Birthdate:</label>
														<input type="date" class="form-control " id="birthdate" name="birthdate" required />
													</div>
												</div>
												<div class="form-group row">
													<div class="col-sm-12">
														<label>Gender:</label>
														<select class="form-control" id="gender" name="gender" required>
															<option value=" " selected readonly="readonly">Select Gender</option>
															<option value="M">Male</option>
															<option value="F">Female</option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<h3>Login Info</h3>
												<hr />
												<div class="form-group row">
													<div class="col-sm-12">
														<label>Username:</label>
														<input type="text" class="form-control danger" id="username" name="username" onchange="javascript: checkUsername();" required   />
													</div>
												</div>
												<div class="form-group row">
													<div class="col-sm-12">
														<label>Password:</label>
														<input type="password" class="form-control" id="password" name="password" required />
													</div>
												</div>
												<div class="form-group row">
													<div class="col-sm-12">
														<label>Confirm Password:</label>
														<input type="password" class="form-control" id="c_password" name="c_password" required />
													</div>
												</div>
												<br/>
												<h3>Address Info</h3>
												<hr />
												<div class="form-group row">
													<div class="col-sm-12">
														<label>Province:</label>
														<select class="form-control" id="checkout_provinces" name="checkout_provinces" onchange="getCitiesNewCustomer();">
															<option value="0" selected readonly="readonly">Select Province</option>
															<?php if(isset($provinces)) { ?> 
																<?php foreach($provinces as $province) { ?>
																	<?php if($this->user->getProvinceId() == $province['province_id']) { ?>
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
															<option value="0" selected readonly="readonly">Select City</option>
															<?php if($this->user->getCityMunicipalityId() > 0) { ?>
																<option value="<?php echo $this->user->getCityMunicipalityId(); ?>"><?php echo $this->user->getCityMunicipalityDesc(); ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="form-group row" id="brgy_hidden">
													<div class="col-sm-12">
														<label>Barangay:</label>
															<select class="form-control" id="checkout_barangay" name="checkout_barangay">                                      
																<option value="0" selected readonly="readonly">Select Brgy</option>
																<?php if($this->user->getBarangayId() > 0) { ?>
																	<option value="<?php echo $this->user->getBarangayId(); ?>"><?php echo $this->user->getBarangayDesc(); ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="form-group row" id="cust_address_hidden">
													<div class="col-sm-12">
														<label>Address:</label>
														<input type="text" class="form-control" id="cust_address" name="cust_address" placeholder="House No. / St. / Blk & Lot" required />
													</div>
												</div> 
										
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
									<span id="modal-button-admin"></span>
								</div>
							</div>
						</div>
					</div>
					<!--  CUT  -->
					<div class="card">
						<!-- Card header -->
						<div class="card-header bg-transparent border-0">
							<h3 class="mb-0">Actions</h3><br>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-6">
										 <div class="input-group">
											<input class="form-control" placeholder="Username" type="text" id="search_username" name="search_username" value=""/>
											<button class="btn bg-info text-white" type="button" onclick="javascript: processTask('search')"><i class="fa fa-search"></i> Search</button>
										</div>
									</div>
									<div class="col-md-6 text-right">
										<button class="btn bg-info text-white " type="button" data-toggle="modal" data-target="#admin-modal" onclick="javascript: addNewAdminShow()"><i class="fa fa-plus"></i> Add Admin</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--==============================================================================================================================-->
					<div class="card">
						<div class="card-header border-0">
							<div class="row">
								<div class="col-6">
									<h3 class="mb-0">List of Admins</h3>
								</div>
							</div>
						</div>
						<!-- Light table -->
						<div class="table-responsive">
							<table class="table align-items-center table-flush">
								<thead class="thead-light">
									<tr>
									   <th class="left">User ID</th>
									   <th class="left">Username</th>
									   <th class="left">ID Number</th>
									   <th class="left">Full Name</th>
									   <th class="left">Email</th>
									   <th class="left">Contact Number</th>
									   <th class="left">Address</th>
									   <th class="left">Status</th>
									   <th class="left">Date Added</th>
									   <th class="left">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if (isset($admins)) { ?>								
										<?php foreach ($admins as $wh) { ?>											
											<tr>
												<td><?php echo $wh['user_id'];?></td>
												<td><?php echo $wh['username'];?></td>
												<td><?php echo $wh['id_no'];?></td>
												<td><?php echo $wh['fullname'];?></td>
												<td><?php echo $wh['email'];?></td>
												<td><?php echo $wh['contact'];?></td>
												<td><?php echo $wh['address'];?></td>
												<td><?php echo $wh['status'];?></td>
												<td><?php echo $wh['date_added'];?></td>
												<td>
													<button class="btn btn-info btn-sm" type="button" data-toggle="modal" data-target="#admin-modal" onclick="javascript: getAdminDetails(<?php echo $wh['user_id'] ?>)"><i class="ni ni-ruler-pencil text-white"></i> Edit</button>
													<?php if ($wh['status'] == 'Enabled') { ?>
														<button class="btn btn-danger btn-sm" type="button" onclick="javascript: disableAdmin(<?php echo $wh['user_id'] ?>)"><i class="ni ni-fat-remove text-white"></i> Disable</button>
													<?php } else { ?>
														<button class="btn btn-success btn-sm" type="button" onclick="javascript: enableAdmin(<?php echo $wh['user_id'] ?>)"><i class="fa fa-check"></i> Enable</button>
													<?php } ?>
												</td>
											</tr>
										<?php } ?>								
									<?php } ?>							  
								</tbody>
							</table>
						</div>
						<div class="pagination"><?php echo $pagination; ?></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!--==============================================================================================================================-->
<div id="dialog-message" title="Message" style="display:none; width: 400px;">
  <span id="msg"></span>
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

function addNewAdmin(user_group_id) {

  var checkUsername = $("#valid-username").val();
  var firstname = $("#firstname").val();
  var middlename = $("#middlename").val();
  var lastname = $("#lastname").val();
  var contactno = $("#contactno").val();
  var email = $("#email").val();
  var birthdate = $("#birthdate").val();
  var gender = $("#gender").val();
  var password = $("#password").val();
  var confirmPass = $("#c_password").val();
  var checkout_provinces = $("#checkout_provinces").val();
  var checkout_city = $("#checkout_city").val();
  var checkout_barangay = $("#checkout_barangay").val();
  var proceed = 1;
  var msg = "";


  if(checkUsername == "0"){
    msg += "Username not available.<br>";
    proceed = 0;
  }

  if(firstname == ""){
    msg += "Firstname is required.<br>";
    proceed = 0;
  }
	
  if(middlename == ""){
    msg += "Middlename is required.<br>";
    proceed = 0;
  }
  
  if(lastname == ""){
    msg += "Lastname is required.<br>";
    proceed = 0;
  }
  
  if(contactno == ""){
		msg += "Contact Number is required<br>";
		proceed = 0;
	}else if((contactno.length < 11) || (contactno.length > 11)){
		msg += "Please check your Contact Number<br>";
		proceed = 0;
	}
	
  if(email == ""){
    msg += "Email is required.<br>";
    proceed = 0;
  }
  
  if(birthdate == ""){
    msg += "Birthdate is required.<br>";
    proceed = 0;
  }
  
  if(gender == ""){
    msg += "Gender is required.<br>";
    proceed = 0;
  }
  
  if(checkout_provinces == ""){
    msg += "Province is required.<br>";
    proceed = 0;
  }
  
  if(checkout_city == ""){
    msg += "City is required.<br>";
    proceed = 0;
  }
  
  if(checkout_barangay == ""){
    msg += "Barangay is required.<br>";
    proceed = 0;
  }

  if(password == ""){
    msg += "Password is required.<br>";
    proceed = 0;
  }  
  
  if(password != confirmPass){
    msg += "Password do not match.<br>";
    proceed = 0;
  }

  if(proceed == 0){
	msg = "Please check the following errors: <br><br>" + msg;	
	// swal.fire({
	// title: 'Error!',
	// text: msg,
	// icon: 'error',
	// confirmButtonText: 'Ok'
	// });
	swal ( "Oops!" ,  msg ,  "error" );
  }else{
    $('#user_group_id').val(user_group_id);
    $('#task').val('addNewAdmin');
    $('form').attr('action', "manageadmin"); 
    $('form').submit();
  }
}

function updateAdminDetails() {
  var firstname = $("#firstname").val();
  var middlename = $("#middlename").val();
  var lastname = $("#lastname").val();
  var contactno = $("#contactno").val();
  var email = $("#email").val();
  var birthdate = $("#birthdate").val();
  var gender = $("#gender").val();
  var checkout_provinces = $("#checkout_provinces").val();
  var checkout_city = $("#checkout_city").val();
  var checkout_barangay = $("#checkout_barangay").val();
  var proceed = 1;
  var msg = "";


  if(firstname == ""){
    msg += "Firstname is required.<br>";
    proceed = 0;
  }
	
  if(middlename == ""){
    msg += "Middlename is required.<br>";
    proceed = 0;
  }
  
  if(lastname == ""){
    msg += "Lastname is required.<br>";
    proceed = 0;
  }
  
  if(contactno == ""){
		msg += "Contact Number is required<br>";
		proceed = 0;
	}else if((contactno.length < 11) || (contactno.length > 11)){
		msg += "Please check your Contact Number<br>";
		proceed = 0;
	}
	
  if(email == ""){
    msg += "Email is required.<br>";
    proceed = 0;
  }
  
  if(birthdate == ""){
    msg += "Birthdate is required.<br>";
    proceed = 0;
  }
  
  if(gender == ""){
    msg += "Gender is required.<br>";
    proceed = 0;
  }
  
  if(checkout_provinces == ""){
    msg += "Province is required.<br>";
    proceed = 0;
  }
  
  if(checkout_city == ""){
    msg += "City is required.<br>";
    proceed = 0;
  }
  
  if(checkout_barangay == ""){
    msg += "Barangay is required.<br>";
    proceed = 0;
  }
  
  if(proceed == 0){
	msg = "Please check the following errors: <br>" + msg;
	swal ( "Oops!",  msg,  "error" );
  }else{
  
  $('#task').val('updateAdminDetails');
  $('form').attr('action', "manageadmin"); 
  $('form').submit();
  }

}

function disableAdmin(user_id) {

  $("#user_id").val(user_id);
  $('#task').val('disableAdmin');
  $('form').attr('action', "manageadmin"); 
  $('form').submit();

}

function enableAdmin(user_id) {

  $("#user_id").val(user_id);
  $('#task').val('enableAdmin');
  $('form').attr('action', "manageadmin"); 
  $('form').submit();

}

function processTask(task) {
  $('#task').val('search');
  $('form').attr('action', "manageadmin"); 
  $('form').submit();
}

function getAdminDetails(user_id) {
	
  $.ajax({
    url: 'getadmindetails/' + user_id,
    type: 'get',
    dataType: 'json',
    success: function(json) {             
      if (json['status'] == "success") {

        $("#user_id").val(json['admin']['user_id']);
        $("#username").val(json['admin']['username']);
        $("#firstname").val(json['admin']['firstname']);
        $("#middlename").val(json['admin']['middlename']);
        $("#lastname").val(json['admin']['lastname']);
        $("#contactno").val(json['admin']['contact']);
        $("#email").val(json['admin']['email']);
        $("#birthdate").val(json['admin']['birthdate']);
        $("#gender").val(json['admin']['gender']);
		
		$("#checkout_provinces").val(json['admin']['province_id']);
		
		var city = "<option value='"+ json['admin']['city_municipality_id'] +"' selected >"+ json['admin']['city'] +"</option>";
        $("#checkout_city").html(city);

        var brgy = "<option value='"+ json['admin']['barangay_id'] +"' selected >"+ json['admin']['barangay'] +"</option>";
        $("#checkout_barangay").html(brgy);

        $("#cust_address").val(json['admin']['address']);

        $("#modal-button-admin").html("<button type=\"button\" class=\"btn bg-info text-white\" onclick=\"updateAdminDetails()\" ><i class=\"fa fa-floppy-o\"></i> Update Admin</button>");
        // $("#admin-modal").modal('toggle');
        
      }   
    }
  });
}

function checkUsername() {
  var username = $("#username").val();
  $.ajax({
    url: 'checkusername/' + username,
    type: 'get',
    dataType: 'json',
    success: function(json) {         
console.log(json['status']);    
      if (json['status'] == "success") {
		$("#username-checker").addClass("collapse");         
		$("#valid-username").val("1");
      } else {
		$("#username-checker").removeClass("collapse");
		$("#valid-username").val("0");
		$("#username").val("");
	  }		  
    }
  });
}

// function checkContact() {
  // var contact = $("#contactno").val();
  // $.ajax({
    // url: 'checkcontact/' + contact,
    // type: 'get',
    // dataType: 'json',
    // success: function(json) {             
      // if (json['status'] == "success") {
        // if(json['total_user'] == "0"){
		  // $("#contact-checker").addClass("collapse");         
          // $("#valid-contact").val("1");
        // }else{ 	
		  // $("#contact-checker").removeClass("collapse");
          // $("#valid-contact").val("0");
		  // $("#contactno").val("");
        // }
      // }   
    // }
  // });
// }

// function checkEmail() {
  // var email = $("#email").val();
  // $.ajax({
    // url: 'checkemail/' + email,
    // type: 'get',
    // dataType: 'json',
    // success: function(json) {             
      // if (json['status'] == "success") {
        // if(json['total_user'] == "0"){
		  // $("#email-checker").addClass("hidden");         
          // $("#valid-email").val("1");
        // }else{ 	
		  // $("#email-checker").removeClass("hidden");
          // $("#valid-email").val("0");
		  // $("#email").val("");
        // }
      // }   
    // }
  // });
// }

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

function addNewAdminShow(){
  $("#username").val("");
  $("#firstname").val("");
  $("#middlename").val("");
  $("#lastname").val("");
  $("#contactno").val("");
  $("#birthdate").val("");
  $("#email").val("");
  $("#checkout_provinces").val("0");
  $("#checkout_city").html("<option value='0' selected readonly='readonly'>Select City (Province First)</option>");
  $("#barangay").html("<option value='0' selected readonly='readonly'>Select Brgy (City First)</option>");
  $("#cust_address").val("");
  $("#modal-button-admin").html("<button type=\"button\" class=\"btn bg-info text-white\" onclick=\"addNewAdmin(47);\" ><i class=\"fa fa-plus\"></i> Add Admin</button>");
  // $("#modal-notification").modal('toggle');
}


//--></script>