<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
			<i class="ni ni-app ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp; Withdrawal</h6>
		</div>
	  </div>
	</div>
  </div>
</div>

<div class="container-fluid mt--6">	
	<br>
	<div class="row card-margin">  
	
		<div class="col-sm-4 stretch-card">
			<div class="card bg-avgreen border-0">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12 text-center">
							<h1 class="card-title text-uppercase text-muted mb-0 text-white">Php <?php echo number_format($ewallet + $withdrawal,2); ?></h1>
							<h5 class="card-title text-uppercase text-muted mb-0 text-white">Total Earnings</h5>
						</div>
					</div>
				</div>
			</div>
		</div>	
		
		<div class="col-sm-4 stretch-card">
			<div class="card bg-avgreen border-0">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12 text-center">
							<h1 class="card-title text-uppercase text-muted mb-0 text-white">Php <?php echo number_format($withdrawal,2); ?></h1>
							<h5 class="card-title text-uppercase text-muted mb-0 text-white">Total Withdrawal</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-sm-4 stretch-card">
			<div class="card bg-avgreen border-0">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12 text-center">
							<h1 class="card-title text-uppercase text-muted mb-0 text-white">Php <?php echo number_format($ewallet,2); ?></h1>
							<h5 class="card-title text-uppercase text-muted mb-0 text-white">Available Ewallet</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	<br>
	<div class="card card-margin">	  
		<div class="card-body">
			<h5><i class="fa fa-pencil"></i> Inputs</h5>
			<br>
			<form action="" method="post" enctype="multipart/form-data" id="form">
				<input type="hidden" id="task" name="task" value="" />
				<input type="hidden" id="trans_session_id" name="trans_session_id" value="<?php echo $trans_session_id; ?>" />
				<div class="row">
					<div class="col-md-4">
						<div align="left">
							<label for="amount">Amount:(P500 Minimum)</label>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="amount" id="amount" placeholder="Amount" onchange="javascript: computeNet()" value="0"/>
						</div>
					</div>
					<div class="col-md-4">
						<div align="left">
							<label for="charge">Transaction Fee:</label>
						</div>
						<div class="form-group">
							<input type="text" value="0" class="form-control" name="charge" id="charge" readonly/>
						</div>
					</div>
					<div class="col-md-4">
						<div align="left">
							<label for="net">Net Amount:</label>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="net" id="net" readonly></input>
						</div>
					</div>
					<div class="col-md-3"></div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div align="left">
							<label for="bank">Withdrawal Option:</label>
						</div>
						<div class="form-group">
							<select class="form-control" name="bank" id="bank" >
								<option value="128" selected="">BDO</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div align="left">
							<label for="account">Account Name:</label>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" value="<?php echo $details['account_name'] ?>" name="account_name" id="account_name" />
						</div>
					</div>
					<div class="col-md-4">
						<div align="left">
							<label for="account_number">Account #:</label>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" value="<?php echo $details['account_no'] ?>" name="account_no" id="account_no" />
						</div>
					</div>
					
					<div class="col-md-12 text-center">
						<center>
						<div class="form-group">
							<button type="button" class="btn bg-redorange text-white btn-block" onclick="requestWithdrawal();" ><i class="ni ni-credit-card text-white"></i>&nbsp; Withdraw</button>
							
							<!--<button type="button" class="btn btn-success" onclick="saveWithdrawDetails();" ><i class="fa fa-floppy-o"></i> Save Details</button>-->
						</div>
						</center>
					</div>
				</div>
			</form>			
		</div>
	</div>
	<br>
	<div class="card">
		<!-- Card header -->
		<div class="card-header border-0">
		  <div class="row">
			<div class="col-6">
			  <h3 class="mb-0">List of Withdrawals</h3>
			</div>
		  </div>
		</div>
		<!-- Light table -->
		<div class="table-responsive">
		  <table class="table align-items-center table-flush">
			<thead class="thead-light">
			<tr class="table-primary">
				<th class="text-info" >Withdraw#</th>
				<th class="text-info" >Status</th>
				<th class="text-info" >Amount Requested</th>										
				<th class="text-info" >Transaction Fee</th>								
				<th class="text-info" >Total Amount</th>
				<th class="text-info" >Requestor</th>
				<th class="text-info" >Type</th>
				<th class="text-info" >Account Name</th>								
				<th class="text-info" >Account Number</th>								
				<th class="text-info" >Date Submitted</th>
			</tr>
			</thead>
			<tbody>
				<?php if (isset($withdrawals)) { ?>
					<?php foreach ($withdrawals as $w) { ?>
						<tr>
							<td><?php echo $w['withdrawal_id'];?></td>									
							<td>
								<?php if($w['status'] == "Requested") { ?>
									<center><h6 style='color:orange; font-weight: bold;'><?php echo $w['status'];?></h6>
								<?php } else if($w['status'] == "Released") { ?>
									<center><h6 style='color:blue; font-weight: bold;'><?php echo $w['status'];?></h6>
								<?php } else if($w['status'] == "Received") { ?>
									<center><h6 style='color:green; font-weight: bold;'><?php echo $w['status'];?></h6>
								<?php } else if($w['status'] == "Cancelled") { ?>
									<center><h6 style='color:red; font-weight: bold;'><?php echo $w['status'];?></h6>
								<?php } ?>
							</td>
							<td><b>Php <?php echo number_format($w['amount_requested'],2);?></b></td>
							<td><b>Php <?php echo number_format($w['proc_fee'],2);?></b></td>
							<td><b>Php <?php echo number_format($w['amount'],2);?></b></td>
							<td><?php echo $w['requestor'];?></td>
							<td><?php echo $w['bank_name'];?></td>
							<td><?php echo $w['account_name'];?></td>
							<td><?php echo $w['account_no'];?></td>
							<td><?php echo $w['date_added'];?></td>
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
	
	$('#amount').change(function(){
		var gross = parseFloat($('#amount').val());
		var net = gross - 50;
		$('#charge').val(50);
		$('#net').val(net);
	});
});

function requestWithdrawal(){

	var gross = $('#amount').val();
	var charge = $('#charge').val();
	var net = $('#net').val();
	var name = $('#account').val();
	var number = $('#account_number').val();
	var proceed = 1;
	var msg = "";

	if(name == ""){
		msg += "Account name is required! <br>";
		proceed = 0;
	}

	if(name == ""){
		msg += "Account number is required! <br>";
		proceed = 0;
	}
	
	if(gross < 500){
		msg += "Sorry, but minimum net amount of Withdrawal is 500.00 <br>";
		proceed = 0;
	}

	if(proceed == 1) {		
		$('#msg_proceed').html("Are you sure you declared the correct withdrawal amount and BDO Account Number? Please note that the name of your profile is the name that will be used in the deposit slip.");			      
		$('#div_buttons').html("<button type=\"button\" onclick=\"proceed();\" class=\"btn btn-white\">Yes, Proceed.</button>");		
		$('#modal-proceed').modal('show');
	} else {
		$('#msg').html(msg);			      
		$('#modal-notification').modal('show');
	}
}

function proceed(task) {
	$('#task').val("requestwithdraw");
	$('form').attr('action', 'withdraw'); 
	$('form').submit();
}

//--> 
</script>