<?php echo $header; ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBOXfEwS56A61L3_-5hh8ETPsfAnIy63Sc"></script> 
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
		  <i class="ni ni-money-coins ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Branch Maintainance</h6>
		</div>
	  </div>
	</div>
  </div>
</div>
<div class="container-fluid mt--6">
    <div class="col-md-12">
         <div class="row">
               <form action="" method="post" enctype="multipart/form-data" id="form">
                  <input type="hidden" id="task" name="task" value="">	
                  <div class="card bg-default shadow">
                    <div class="card-header bg-default">
                      <h5 class="text-white"><i class="fa fa-plus"></i> Add Branch</h5>
                    </div>
                    <div class="col-md-12">
					<p class="text-white"><i><small>Note: Input Branch name.</small></i></p>
						<div class="row">
                          <div class="col-md-8">
                            <div class="form-group">  
                              <input class="form-control input" type="text" value="<?php if(isset($city)){echo $city;} ?>" placeholder="Branch Name" id="branch_name" name="branch_name">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <button class="btn btn-primary mr-1" type="button" onclick="javascript:addBranch()" ><i class="fa fa-plus"></i> Add Branch</button>
                            </div>
                          </div>
                        </div>
                    </div>  
                  </div>

                  <div class="card bg-default shadow">
                    <div class="card-header bg-default">
                      <h5 class="text-white"><i class="fa fa-check"></i> Update Branch Name</h5>
                    </div>
                      <div class="col-md-12">
					    <p class="text-white"><i><small>Note: Choose Branch name and input new name on textbox.</small></i></p>
                        <div class="row">      
                          <div class="col-md-4">
                            <div class="form-group">
                              <select class="form-control input" id="branch_code_update_name" name="branch_code_update_name">
                                <?php if(isset($branches)) { ?>
                                  <?php foreach($branches as $branchee) { ?>  
                                    <option value="<?php echo $branchee['branch_id']?>"><?php echo $branchee['description']?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <input type="text" class="form-control" id="new_branch_name" name="new_branch_name">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <button class="btn btn-success mr-1" type="button" onclick="javascript:updateBranchName()" ><i class="fa fa-check"></i> Update Branch Name</button>
                            </div>
                          </div>
                        </div>  
                      </div>
                  </div>

                  <div class="card bg-default shadow">
                    <div class="card-header bg-default">
                      <h5 class="text-white"><i class="fa fa-check"></i> Assign Branch to User</h5>
                    </div>
                      <div class="col-md-12">
					   <p class="text-white"><i><small>Note: Choose Branch name and assign it to designated user.</small></i></p>
                        <div class="row">      
                          <div class="col-md-4">
                            <div class="form-group">
                              <select class="form-control input" id="branch_code_id" name="branch_code_id" onchange="selectSupplier(this.value)">                              
                                <?php if(isset($branches)) { ?>
                                  <?php foreach($branches as $branchee) { ?>  
                                    <option value="<?php echo $branchee['branch_id']?>"><?php echo $branchee['description']?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <select class="form-control input" id="assigned_user" name="assigned_user">                               
                                <?php if(isset($users)) { ?>
                                  <?php foreach($users as $user) { ?>  
                                    <option value="<?php echo $user['user_id']?>"><?php echo $user['username']?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <button class="btn btn-info mr-1" type="button" onclick="javascript:addDesignatedUser()" ><i class="fa fa-check"></i> Assign Branch</button>
                            </div>
                          </div>
                        </div>  
                      </div>
                  </div>

                  <div class="card bg-default shadow">
                    <div class="card-header bg-default">
                      <h5 class="text-white"><i class="fa fa-check"></i> Update Branch Coordinates</h5>
                    </div>
                      <div class="col-md-12">
						<p class="text-white"><i><small>Note: Choose Branch name and locate the branch address in the map, drag and drop Marker to get the coordinates.</small></i></p>
                        <div class="row">      
                          <div class="col-md-3">
                            <div class="form-group">
                              <select class="form-control input" id="branch_code_coordinates" name="branch_code_coordinates" onchange="selectSupplier(this.value)">                               
                                <?php if(isset($branches)) { ?>
                                  <?php foreach($branches as $branchee) { ?>
                                    <option value="<?php echo $branchee['branch_id']?>"><?php echo $branchee['description']?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group">
                              <input type="text" class="form-control" name="longitude" id="longitude" readonly="readonly">
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group">
                              <input type="text" class="form-control" name="latitude" id="latitude" readonly="readonly">
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group">
                              <button class="btn btn-warning mr-1" type="button" onclick="javascript:updateBranchCoordinates()" ><i class="fa fa-map-marker"></i> Update Branch</button>
                            </div>
                          </div>
                          <input id="address" type="text" value="Sydney, NSW">
							<input type="button" value="Encode" onclick="codeAddress()">
                          <div id="current"></div>
                          <div id="map" style="width:100%;height:300px"></div>
                        </div>  
                      </div>
                  </div>

                  <div class="card bg-default shadow">
                    <div class="card-header bg-default">
                      <h5 class="text-white"><i class="fa fa-filter"></i> Filter</h5>
                    </div>
                      <div class="col-md-12">
                        <div class="row">      
                          <div class="form-group">
							  <div class="col-sm-12">
							  <label class="text-white">Province:</label>
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

							<div class="form-group" id="city_hidden">
							  <div class="col-sm-12">
							  <label class="text-white">City:</label>
								<select class="form-control" id="checkout_city" name="checkout_city" onchange="getBrgyRegister();">        
									<option value="0" selected readonly="readonly">Select City (Province First)</option>
									<?php if($this->user->getCityMunicipalityId() > 0) { ?>
										<option value="<?php echo $this->user->getCityMunicipalityId(); ?>"><?php echo $this->user->getCityMunicipalityDesc(); ?></option>
									<?php } ?>
								</select>
							  </div>
							</div>
							
							<div class="form-group" id="brgy_hidden">
							  <div class="col-sm-12">
							  <label class="text-white">Barangay:</label>
								<select class="form-control" id="checkout_barangay" name="checkout_barangay">                                      
									<option value="0" selected readonly="readonly">Select Brgy (City First)</option>
									<?php if($this->user->getBarangayId() > 0) { ?>
										<option value="<?php echo $this->user->getBarangayId(); ?>"><?php echo $this->user->getBarangayDesc(); ?></option>
									<?php } ?>
								</select>
							  </div>
							</div>							
                          <div class="col-md-3">
                            <div class="form-group">  
							<label class="text-white">Branch:</label><br/>
                              <input class="form-control input" type="text" value="<?php if(isset($branch)){echo $branch;} ?>" placeholder="Branch" id="branch" name="branch">
                            </div>
                          </div> 						 
                        </div>  
                        <div class="row">
                          <div class="col-md-12 mb-3 text-right">  
                            <button class="btn btn-warning mr-1" type="button" onclick="javascript:search()" ><i class="fa fa-search"></i> Search</button>
                          </div>  
                        </div>
                      </div>
                  </div>
				  
                  <div class="card bg-default shadow">
                    <div class="card-header bg-default">
                      <h5 class="text-white"><i class="fa fa-pencil-square-o"></i> Actions</h5>
                    </div>
                      <div class="col-md-12">
					  <p class="text-white"><i><small>Note: Choose a Branch below that you want to assign on the selected Cities then Click Assign Button.</small></i></p>
                        <div class="row">                                
                          <div class="col-md-4">
                            <div class="form-group">
                              <select class="form-control input" id="branch_code" name="branch_code" onchange="selectSupplier(this.value)">
                                <option value="0"> Select Branch</option>
                                <?php if(isset($branches)) { ?>
                                  <?php foreach($branches as $branch) { ?>    
                                    <option value="<?php echo $branch['branch_id']?>"><?php echo $branch['description']?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-2">
                            <div class="form-group">
                              <button class="btn btn-success mr-1" type="button" onclick="javascript:assign()" ><i class="fa fa-check"></i> Assign</button>
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
                                       <td class="left">City</td>
                                       <td class="left">Barangays</td>
                                       <td class="left">Branch</td>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php if (isset($cities)) { ?>								
                                       <?php foreach ($cities as $c) { ?>											
                                       <tr>
                                          <td style="text-align: center;">
                                          <input type="checkbox" name="selected[]" value="<?php echo $c['barangay_id'];?>"/>
                                          </td>
                                          <td><?php echo $c['city'];?></td>
                                          <td><?php echo $c['barangays'];?></td>
                                          <td><?php echo $c['branch'];?></td>
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
    </div>
</div>
<div id="dialog-message" title="Info" style="display:none; width: 400px;">
  <span id="msg"></span>
</div>
<?php echo $footer; ?><script type="text/javascript"><!--
var geocoder;
  var map;
  var marker;

$(document).ready(function() {
    <?php if(isset($err_msg)) { ?>
    var msg = "<?php echo $err_msg; ?>";
    $('#msg').html(msg);
    $(function() {
        $("#dialog-message").dialog({
            modal: true,
            width: 600,
            buttons: {
                Ok: function() {
                    $(this).dialog("close");
                }
            }
        });
    });
    <?php } ?>

    geocoder = new google.maps.Geocoder();

    var mapOptions = {
        center: new google.maps.LatLng(15.46946187, 120.97989881),
        zoom: 18,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map"), mapOptions);

    var myLatLng = {lat: 15.46946187, lng: 120.97989881};
    var image = 'http://morphsys.com.ph/pictures/map-marker.png';

    marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          icon: image,
          draggable: true,
          title: 'Morphsys Inc.'
        });

    google.maps.event.addListener(marker, 'dragend', function(evt){
        $("#longitude").val(evt.latLng.lng().toFixed(8));
        $("#latitude").val(evt.latLng.lat().toFixed(8));
        document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(8) + ' Current Lng: ' + evt.latLng.lng().toFixed(8) + '</p>';
    });

    google.maps.event.addListener(marker, 'dragstart', function(evt){
        document.getElementById('current').innerHTML = '<p>Currently dragging marker...</p>';
    });

    map.setCenter(marker.position);
    marker.setMap(map);

});

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

function codeAddress() {
    var address = document.getElementById("address").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        var image = 'http://morphsys.com.ph/pictures/map-marker.png';
        marker = new google.maps.Marker({
          position: results[0].geometry.location,
          map: map,
          icon: image,
          draggable: true,
          title: 'Morphsys Inc.'
        });

        google.maps.event.addListener(marker, 'dragend', function(evt){
        $("#longitude").val(evt.latLng.lng().toFixed(8));
        $("#latitude").val(evt.latLng.lat().toFixed(8));
        document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(8) + ' Current Lng: ' + evt.latLng.lng().toFixed(8) + '</p>';
        });

        google.maps.event.addListener(marker, 'dragstart', function(evt){
            document.getElementById('current').innerHTML = '<p>Currently dragging marker...</p>';
        });

        map.setCenter(marker.position);
        marker.setMap(map);

      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
}

function search() {
    $('#task').val('search');
    $('form').attr('action', 'branchmaintainance');
    $('form').submit();
}


function assign(){
    $('#task').val('assign');
    $('form').attr('action', 'branchmaintainance');
    $('form').submit();
}
 

function addBranch(){
    $('#task').val('addBranch');
    $('form').attr('action', 'branchmaintainance');
    $('form').submit();
}

function updateBranchCoordinates(){
    $('#task').val('updateBranchCoordinates');
    $('form').attr('action', 'branchmaintainance');
    $('form').submit();
}

function addDesignatedUser(){
    $('#task').val('addDesignatedUser');
    $('form').attr('action', 'branchmaintainance');
    $('form').submit();
}

function updateBranchName(){
    $('#task').val('updateBranchName');
    $('form').attr('action', 'branchmaintainance');
    $('form').submit();
}

//--></script>