<?php echo $header; ?>

<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
			<i class="ni ni-cart ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp; Quick Delivery Cancelled</h6>
		</div>
	  </div>
	</div>
  </div>
</div>

<div class="panel panel-default">
	<form action="" method="post" enctype="multipart/form-data" id="form">
		<div class="row mb-4">
			<div class="col-md-12">
				<input type="hidden" id="task" name="task" value="">
				<input type="hidden" id="qd_id" name="qd_id" value="">
				<input type="hidden" id="referror" name="referror" value="">
				<input type="hidden" id="quick_deliveries_id" name="quick_deliveries_id" value="<?php if(isset($quick_deliveries_id)){ echo $quick_deliveries_id; } else { echo 0; }?>">
				
				<?php if(!isset($quick_deliveries_id)) { ?>
				
				<div class="container-fluid mt--6">
					<div class="row">
						<?php if ($this->user->getUserGroupId() == 111 OR $this->user->getUserGroupId() == 112 OR $this->user->getUserGroupId() == 87 OR $this->user->getUserGroupId() == 86) { ?>
						<div class="col-xl-6 col-md-6">
							<div class="card bg-gradient-darkred border-0">
								<div class="panel-heading box-vip-dash3">
								  <div class="card-body">
									  <h3 class="mb-0 text-md-3 text-white">TOTAL DELIVERY FEE</h3>
									  <h4 class="text-nowrap text-white font-weight-600">Php <?php echo number_format($totalcom['delivery_fee'],2);?></h4>
								  </div>
								</div>
							</div>
						</div>
						<?php } ?>
						<div class="col-xl-6 col-md-6">
							<div class="card bg-gradient-info border-0">
								<div class="panel-heading box-vip-dash3">
								  <div class="card-body">
									  <h3 class="mb-0 text-md-3 text-white">TOTAL NO. OF COMPLETED TRANSACTION</h3>
									  <h4 class="text-nowrap text-white font-weight-600"><?php echo $totalcom['total']; ?></h4>
								  </div>
								</div>
							</div>
						</div>
						
						<div class="col-xl-6 col-md-6">
							<div class="card bg-gradient-info border-0">
								<div class="panel-heading box-vip-dash3">
								  <div class="card-body">
									  <h3 class="mb-0 text-md-3 text-white">CURRENT E-WALLET</h3>
									  <h4 class="text-nowrap text-white font-weight-600">Php <?php echo $currentwallet['ewallet']; ?></h4>
								  </div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
					
		<div class="flex-column flex-xl-row col-md-12">
			
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-filter"></i> Filter</h3>
			</div>
			<div class="card">	  
				<div class="card-body">
					<div class="col-md-12">
				
						<div class="row">							
							<div class="col-md-4">
							<label>Quick Delivery ID:</label>
								<div class="input-group mb-2 mr-sm-2 bm-sm-0">
									<input class="form-control input" value=""  type="text" name="quick_delivery_id" id="quick_delivery_id" >
								</div>
							</div>
							<?php if ($this->user->getUserGroupId() != 86 || $this->user->getUserGroupId() != 87) { ?>
								<div class="col-md-4">
								<label>Rider ID:</label>
									<div class="input-group mb-2 mr-sm-2 bm-sm-0">
										<input class="form-control input" value=""  type="text" name="rider_id" id="rider_id" >
									</div>
								</div>
								<div class="col-md-4">
								<label>Rider Name:</label>
									<select class="form-control" id="rider_name" name="rider_name">
									  <option value="0" selected readonly="readonly">Select Rider</option>
										<?php if(isset($riders)){
										foreach($riders as $r){ ?>
											<option value="<?php echo $r['rider_id']; ?>"><?php echo $r['ridername']; ?></option>
										<?php }
									  } ?>
									</select>
								</div>
							<?php } ?>
							<div class="col-md-4">
							<label>Date From:</label>
								<div class="input-group mb-2 mr-sm-2 bm-sm-0">
									<input class="form-control input" value=""  type="date" id="datefrom_search" placeholder="Order Date From" name="datefrom_search" title="Order Date From" >
								</div>
							</div>		
							<div class="col-md-4">
							<label>Date To:</label>
								<div class="input-group mb-2 mr-sm-2 bm-sm-0">
									<input class="form-control input" type="date" value=""  id="dateto_search" placeholder="Order Date To" name="dateto_search" title="Order Date To" >
								</div>													
							</div>	
							<div class="col-md-4">
							<label>Modified Date From:</label>
								<div class="input-group mb-2 mr-sm-2 bm-sm-0">
									<input class="form-control input" value=""  type="date" id="mdatefrom_search" placeholder="Order Date From" name="mdatefrom_search" title="Order Date From" >
								</div>
							</div>		
							<div class="col-md-4">
							<label>Modified Date To:</label>
								<div class="input-group mb-2 mr-sm-2 bm-sm-0">
									<input class="form-control input" type="date" value=""  id="mdateto_search" placeholder="Order Date To" name="mdateto_search" title="Order Date To" >
								</div>													
							</div>	
						</div>
						<div class="row">
							<div class="col-md-3 pull-right">
							<label>&nbsp;</label>
								<button class="btn btn-primary btn-block" type="button" onclick="javascript: performTask('search');"> <i class="fa fa-search "></i> Search</button>													
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
					
		<div class="flex-column flex-xl-row col-md-12">	
			<div class="card">
				<div class="card-body">
					<div class="col-md-12">
						
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">			
									<table class="table align-items-center table-flush">
									  <thead class="thead-light">
										<tr>
										  <th class="left">&nbsp;</th>
										  <th class="left">Quick Delivery ID</th>
										  <th class="left">Delivery Type</th>
										  <th class="left">Pickup Location</th>
										  <th class="left">No. of Pickup Location</th>
										  <th class="left">No. of Dropoff Location</th>
										  <th class="left">Client</th>
										  <th class="left">Rider</th>
										  <th class="left">Delivery Fee</th>
										  <?php if ($this->user->getUserGroupId() == 111 OR $this->user->getUserGroupId() == 112) { ?>
											<th class="left">Commission Amount (20%)</th>
										  <?php } ?>
										  <th class="left">Status</th>
										  <th class="left">Payment Method</th>
										  <th class="left">Area</th>
										  <th class="left">Date Added</th>
										  <th class="left">Modified Date</th>
										</tr>
									  </thead>
									  <tbody>
										<?php if (isset($quick_del)) { ?>
										<?php foreach ($quick_del as $qd) { ?>			
										<tr>
										  <td class="left">
												<a class="btn btn-success" onclick="viewQD(<?php echo $qd['quick_deliveries_id'];?>);"><i class="fa fa-eye"></i> View</a>
										  </td>
										  <td class="left"><?php echo $qd['quick_deliveries_id'];?></td>
										  <td class="left"><?php echo $qd['special_flag'];?></td>
										  <td class="left"><?php echo $qd['contact_name'];?></td>
										  <td class="left"><?php echo $qd['pickup'];?></td>
										  <td class="left"><?php echo $qd['dropoff'];?></td>
										  <td class="left"><?php echo $qd['user'];?></td>
										  <td class="left"><?php echo $qd['rider'];?></td>
										  <td class="left">Php <?php echo number_format($qd['delivery_fee'],4);?></td>
										  <?php if ($this->user->getUserGroupId() == 111 OR $this->user->getUserGroupId() == 112) { ?>
											<td class="left">Php <?php echo number_format($qd['commission_amount'],4);?></td>
										  <?php } ?>
										  <td class="left"><?php echo $qd['stats'];?></td>
										  <td class="left"><?php echo $qd['method'];?></td>
										  <td class="left"><?php echo $qd['area'];?></td>
										  <td class="left"><?php echo $qd['date_added'];?></td>
										  <td class="left"><?php echo $qd['modified_date'];?></td>
										</tr>
										<?php } ?>
										<?php } ?>
									  </tbody>
									</table>
								</div>
								<br>
								<ul class="pagination">
									<div class="page-link bg-transparent border-0"><?php echo $pagination; ?></div>
								</ul>
							</div>
						</div>	
					</div>
				</div>
			</div>	
		</div>	
					
		<?php } else { ?>
				
	<div class="flex-column flex-xl-row col-md-12">	
		<div class="card">
				
			<div class="row">
				<div class="col-md-12">
					<fieldset>
						<input type="hidden" id="quick_deliveries_id" name="quick_deliveries_id" value="<?php if(isset($quick_deliveries_id)) { echo $quick_deliveries_id; } else { echo 0; } ?>" >
						<div class="panel panel-default">
							<div class="panel-heading right-header">
								<h3 class="panel-title" style="color:#FFFFFF"><i class="fa fa-list" ></i> Quick Delivery <?php if(isset($quick_deliveries_id)) { echo " ID No.: " . $quick_deliveries_id; } else { echo " ID No.: " . 0; } ?></h3>
							</div>
							<div class="panel-body">

								<div class="form-group row">
									<label class="col-sm-3" >Client: </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" placeholder="" value="<?php if(isset($qd_details['user'])) { echo $qd_details['user']; } else { echo ''; } ?>" readonly="readonly">
									</div>
									
									<label  class="col-sm-3" >Rider: </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" placeholder="" value="<?php if(isset($qd_details['rider'])) { echo $qd_details['rider']; } else { echo ''; } ?>" readonly="readonly">
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-sm-3">Pickup Contact Name: </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" placeholder=" " value="<?php if(isset($qd_details['pickname'])) { echo $qd_details['pickname']; } else { echo ''; } ?>" readonly="readonly">
									</div>
									
									<label class="col-sm-3">Pickup Contact Number: </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" placeholder=" " value="<?php if(isset($qd_details['pickcontact'])) { echo $qd_details['pickcontact']; } else { echo ''; } ?>" readonly="readonly">
									</div>
								</div>
								
								<div class="form-group row">
									<label  class="col-sm-3">Pickup Location: </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" placeholder=" " value="<?php if(isset($qd_details['picklocation'])) { echo $qd_details['picklocation']; } else { echo ''; } ?>" readonly="readonly">
									</div>
									
									<label class="col-sm-3">Customer Type: </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" placeholder=" " value="<?php if(isset($qd_details['custype'])) { echo $qd_details['custype']; } else { echo ''; } ?>" readonly="readonly">
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-sm-3">Payment Method: </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" placeholder=" " value="<?php if(isset($qd_details['method'])) { echo $qd_details['method']; } else { echo ''; } ?>" readonly="readonly">
									</div>
									
									<label class="col-sm-3">Status: </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" placeholder=" " value="<?php if(isset($qd_details['stats'])) { echo $qd_details['stats']; } else { echo ''; } ?>" readonly="readonly">
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-sm-3">Required E-wallet: </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" placeholder=" " value="<?php if(isset($qd_details['delivery_fee'])) { echo number_format($qd_details['delivery_fee'],2); } else { echo 0; } ?>" readonly="readonly">
									</div>
									
									<label class="col-sm-3">Delivery Fee: </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" placeholder=" " value="<?php if(isset($qd_details['delivery_fee'])) { echo number_format($qd_details['delivery_fee'],2); } else { echo 0; } ?>" readonly="readonly">
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-sm-3">Schedule of Delivery: </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" placeholder=" " value="<?php if(isset($qd_details['schedule_of_delivery'])) { echo $qd_details['schedule_of_delivery']; } else { echo ''; } ?>" readonly="readonly">
									</div>
									
									<label class="col-sm-3">Date Added: </label>
									<div class="col-sm-3">
										<input type="text" class="form-control" placeholder=" " value="<?php if(isset($qd_details['date_added'])) { echo $qd_details['date_added']; } else { echo ''; } ?>" readonly="readonly">
									</div>
								</div>
								<hr>
								<div class="form-group row">
									<div class="col-md-12">
										<div class="table-responsive">			
											<table class="table table-hover table-bordered">
											  <thead>
												<tr>
												  <td class="left">Drop Off ID</td>
												  <td class="left">Item Details</td>
												  <td class="left">Amount</td>
												  <td class="left">Drop off Contact Name</td>
												  <td class="left">Drop off Contact Number</td>
												  <td class="left">Drop off Location</td>
												</tr>
											  </thead>
											  <tbody>
												<?php if (isset($drop_off)) { ?>
												<?php foreach ($drop_off as $do) { ?>			
												<tr>
												  <td class="left"><?php echo $do['drop_off_id'];?></td>
												  <td class="left"><?php echo $do['item_details'];?></td>
												  <td class="left">Php <?php echo number_format($do['amount'],2);?></td>
												  <td class="left"><?php echo $do['drop_off_contact_name'];?></td>
												  <td class="left"><?php echo $do['drop_off_contact_number'];?></td>
												  <td class="left"><?php echo $do['drop_off_location'];?></td>
												</tr>
												<?php } ?>
												<?php } ?>
											  </tbody>
											</table>
											<div class="pagination"><div class="results"><?php echo $pagination; ?></div></div>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	</form>
</div>

<div id="dialog-message" title="Message" style="display:none; width: 400px;">
  <span id="msg"></span>
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
	$('form').attr('action', 'quickdelcancelled'); 
	$('form').submit();
}

function viewQD(qd_id) {

	$('#task').val('view');
	$('#qd_id').val(qd_id);
	$('#referror').val('quickdelcancelled');
	$('form').attr('action', 'viewbooking/' + qd_id); 
	$('form').submit();

}

//--></script>							