<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
			<i class="ni ni-fat-remove ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp; Cancelled Orders</h6>
		</div>
	  </div>
	</div>
  </div>
</div>

<div class="container-fluid mt--6">
	
	<form action="" method="post" enctype="multipart/form-data" id="form">
		<input type="hidden" id="task" name="task" value=""> 
		<?php if(isset($err_msg)) { ?>
			<hr/>
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<div class="form-group">
						<div class="err_msg">
							<label><?php echo $err_msg; ?></label>
						</div>
					</div>
				</div>
				<div class="col-md-2"></div>
			</div>
		<?php } ?>
		
		<div class="row">	
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">	
			
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">	
									<label>Date Created From</label>
									<input class="form-control input" type="date" id="datecreatedfrom" name="datecreatedfrom">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">	
									<label>Date Created To</label>
									<input class="form-control input" type="date" id="datecreatedto" name="datecreatedto">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">	
									<label>Packed Date From</label>
									<input class="form-control input" type="date" id="packeddatefrom" name="packeddatefrom">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">	
									<label>Packed Date To</label>
									<input class="form-control input" type="date" id="packeddateto" name="packeddateto">
								</div>
							</div>	
						</div>
					
						<div class="row">										
							<div class="col-md-3">
								<div class="form-group">	
									<label>Date Paid From</label>
									<input class="form-control input" type="date" id="datepaidfrom" name="datepaidfrom">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">	
									<label>Date Paid To</label>
									<input class="form-control input" type="date" id="datepaidto" name="datepaidto">
								</div>
							</div>	
							<div class="col-md-3">
								<div class="form-group">	
									<label>Delivery Date From</label>
									<input class="form-control input" type="date" id="deliverydatefrom" name="deliverydatefrom">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">	
									<label>Delivery Date To</label>
									<input class="form-control input" type="date" id="deliverydateto" name="deliverydateto">
								</div>
							</div>									
						</div>
					
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">	
									<label>Order ID</label>
									<input class="form-control input" type="text" id="order_id" name="order_id">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Status</label>
									<select class="form-control input-lg" id="status_id" name="status_id">				
										<option value="0">--Status Search--</option>
										<?php foreach($statuses as $st) {?>
											<option value="<?php echo $st['status_id'];?>"><?php echo $st['description'];?></option>
										<?php } ?>		
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Payment Options</label>
									<select class="form-control input-lg" id="payment_option" name="payment_option">				
										<option value="0">--Payment Option Search--</option>
										<?php foreach($payment_options as $po) {?>
											<option value="<?php echo $po['status_id'];?>"><?php echo $po['description'];?></option>
										<?php } ?>		
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Mode of Deliveries</label>
									<select class="form-control input" id="mode_of_deliveries" name="mode_of_deliveries">				
										<option value="0">--Mode of Delivery--</option>
										<?php foreach($mode_of_deliveries as $mod) {?>
											<option value="<?php echo $mod['status_id'];?>"><?php echo $mod['description'];?></option>
										<?php } ?>		
									</select>
								</div>
							</div>
						</div>
					
						<div class="row"><?php if($this->user->getUserGroupId() == 12){ ?>
							<div class="col-md-6">
								<div class="form-group">
								<label>Admins</label>
								<select class="form-control input" id="admin_id" name="admin_id">				
									<option value="0">--Admin Search--</option>
									<?php foreach($admins as $adm) {?>
										<option value="<?php echo $adm['user_id'];?>"><?php echo $adm['username'];?></option>
									<?php } ?>		
								</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								<label>Payment Flag</label>
								<select class="form-control input" id="paid_flag" name="paid_flag">				
									<option value="">--Paid Flag--</option>
									<option value="0">Not Paid</option>
									<option value="1">Paid</option>		
								</select>
								</div>
							</div>
							<?php } ?>
							<br/>
						</div>
						
						<div class="col-md-12">
							<center>
								<input type="hidden" id="type" name="type" value="cancelledorders">
								<button class="btn btn-outline-user text-white" type="button" onclick="javascript: performUpdate('search');"><i class="fa fa-search"></i> Search</button>&nbsp;
								<button class="btn btn-outline-user text-white" type="button" onclick="javascript: exportToCsv('orderexporttocsv');"><i class="fa fa-list"></i> Export All</button>
							</center>
						</div>
							
					</div>
				</div>
			</div>
		</div>
<!--==============================================================================================================================-->
		<div class="card">
				<!-- Card header -->
				<div class="card-header border-0">
				  <div class="row">
					<div class="col-6">
					  <h3 class="mb-0">List of Cancelled Orders</h3>
					</div>
					<!--<div class="col-6 text-right">
					  <a href="#" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="Edit product">
						<span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
						<span class="btn-inner--text">Export</span>
					  </a>
					</div>-->
				  </div>
				</div>
				<!-- Light table -->
				<div class="table-responsive">
				  <table class="table align-items-center table-flush">
					<thead class="thead-light">
						<tr>
							<th class="text-info">Action</th>
							<?php if($this->user->getUserGroupId() == 12 or $this->user->getUserGroupId() == 41
									or $this->user->getUserGroupId() == 42 or $this->user->getUserGroupId() == 43) { ?>
							<th width="1" style="text-align: center;">
								<input style="width:25px;" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
							</th>
							<?php } ?>
							<th class="text-info">Order Id</th>		
							<th class="text-info">Customer Name</th>						
							<th class="text-info">Order by Username</th>	
							<?php if($this->user->getUserGroupId() != 39) { ?> 
							<th class="text-info">Area Operator</th>	
							<?php }?>							
							<th class="text-info">Status</th>
							<th class="text-info">Item/Quantity</th>
							<th class="text-info">Tracking</th>	
							<th class="text-info">Date Created</th>	
							<th class="text-info">Date Paid</th>	
							<th class="text-info">Date Packed</th>
							<th class="text-info">Paid/NotPaid?</th>	
							<th class="text-info">Date Delivered</th>
							<th class="text-info">Address</th>	
							<th class="text-info">Contact</th>	
							<th class="text-info">Mode of Delivery</th>	
							<th class="text-info">Send To</th>							
							<th class="text-info">Delivery Fee</th>							
							<th class="text-info">Discount</th>							
							<th class="text-info">Total</th>							
							<!--<th class="text-dark">Reseller</th>-->					
							<th class="text-info">Payment Option</th>							
							<th class="text-info">Reference</th>							
							<th class="text-info">Notes</th>							
							<th class="text-info">Remarks</th>							
						</tr>
					</thead>
					<tbody>
							<?php if (isset($orders)) { ?>
								<?php foreach ($orders as $it) { ?>
									<tr>
										<td>
											<?php if($it['status_id'] == 0) { ?>
												<a class="btn btn-primary btn-md" href="cart/<?php echo $it['ref'];?>">View</a>
											<?php } else { ?>
												<a class="btn bg-avgreen text-white btn-md" href="trackingpage/<?php echo $it['ref'];?>">View Tracking</a>
											<?php } ?>
										</td>
										<?php if($this->user->getUserGroupId() == 12 or $this->user->getUserGroupId() == 41
												or $this->user->getUserGroupId() == 42 or $this->user->getUserGroupId() == 43) { ?>
										<td style="text-align: center;">
											<input style="width:25px;" type="checkbox" name="selected[]" value="<?php echo $it['order_id'];?>"/>
										</td>
										<?php } ?>
										<td><?php echo $it['order_id'];?></td>
										<td><?php echo $it['customer_name'];?></td>
										<td><?php echo $it['order_username'];?></td>
										<?php if($this->user->getUserGroupId() != 39 ){ ?> 
										<td><?php echo $it['area_op'];?></td>
										<?php } ?> 
										<td><?php echo $it['status'];?></td>
										<td><?php echo $it['items'];?></td>
										<td><?php echo $it['tracking'];?></td>
										<td><?php echo $it['date_added'];?></td>
										<td><?php echo $it['paid_date'];?></td>
										<td><?php echo $it['packed_date'];?></td>
										<td><?php echo $it['paid_flag'];?></td>
										<td><?php echo $it['actual_delivery_date'];?></td>
										<td><?php echo $it['address'];?></td>
										<td><?php echo $it['contact'];?></td>
										<td><?php echo $it['mod_desc'];?></td>
										<td><?php echo $it['send_to'];?></td>
										<td><?php echo $it['delivery_fee'];?></td>
										<td><?php echo $it['discount'];?></td>
										<td><?php echo $it['amount'];?></td>
										<!--<td><?php //echo $it['reseller'];?></td>-->
										<td><?php echo $it['payment_option_desc'];?></td>
										<td><?php echo $it['ref'];?></td>
										<td><?php echo $it['notes'];?></td>									
										<td><?php echo $it['remarks'];?></td>									
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
		<!--end of card-->
	</form>	
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
var selected = [];
$(document).ready(function() {
	$('#datefrom').datepicker({dateFormat: 'yy-mm-dd'});
	$('#dateto').datepicker({dateFormat: 'yy-mm-dd'});
	<?php if(isset($err_msg)) { ?>
		<?php if(!empty($err_msg)) { ?>
			$('#msg').html("<?php echo $err_msg; ?>");			      
			$('#modal-notification').modal('show');
		<?php } ?>
	<?php } ?>
});

function performUpdate(action) {
	$('#task').val(action); 
	$('form').attr('action', 'cancelledorders'); 
	$('form').submit();
}

function exportToCsv(action) { 
	$('form').attr('action', action); 
	$('form').submit();
}


//--></script>