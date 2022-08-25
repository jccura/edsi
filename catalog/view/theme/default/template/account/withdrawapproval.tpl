<?php echo $header; ?>
<?php //if($this->user->getActivationFlag() == 1) { ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
			<i class="ni ni-check-bold ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp; Withdrawal Approval</h6>
		</div>
	  </div>
	</div>
  </div>
</div>
<div class="memberdashboardback container-fluid mt--6">
	<form action="" method="post" enctype="multipart/form-data" id="form">
		<div class="card card-margin">	  
			<div class="card-body">			
				<input type="hidden" id="task" name="task" value="" />
				<input type="hidden" id="total_cost_in_btc_hdn" name="total_cost_in_btc_hdn" value="0">
				<input type="hidden" id="total_cost_in_usd_hidden" name="total_cost_in_usd_hidden" value="0">
				<input type="hidden" id="trans_session_id" name="trans_session_id" value="<?php echo $trans_session_id; ?>" />
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-2"><label>Withdrawal Id:</label></div>
					<div class="col-md-4">
						<input type="text" class="form-control" id="withdrawal_ids" name="withdrawal_ids" value="">			
					</div>
					<div class="col-md-3"></div>
				</div>	
				<br>
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-2"><label>Date From:</label></div>
					<div class="col-md-4">
						<input type="date" class="form-control" id="datefrom" name="datefrom" value="">			
					</div>
					<div class="col-md-3"></div>
				</div>	
				<br>
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-2"><label>Date To:</label></div>
					<div class="col-md-4">
						<input type="date" class="form-control" id="dateto" name="dateto" value="">			
					</div>
					<div class="col-md-3"></div>
				</div>	
				<br>
				<div class="row">
					<div class="col-md-12">
						<center>
							<input class="btn btn-primary btn" type="button" id="search" value="Search" onclick="javascript: process('search');">
							&nbsp;&nbsp;<input class="btn bg-avgreen text-white btn" type="button" id="search" value="Release" onclick="javascript: process('release');">
							&nbsp;&nbsp;<input class="btn bg-avgreen text-white btn" type="button" id="search" value="Export To Csv" onclick="javascript: exportToCsv();">
						</center>
					</div>
				</div>						
			</div>
		</div>
		<br>	
		<div class="card card-margin">	  
			<div class="card-body">	
				<div class="row">
					<div class="col-md-12">
						<div align="center">
							<div style="width: 100%; overflow: auto;">	
								<table class="table table-striped">
									<thead class="thead-light">
									<tr class="table-primary">
										<td width="1" style="text-align: center;"><input style="width:25px;" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
										<th class="text-info" >Withdrawal #</th>
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
												<td style="text-align: center;">
													<input style="width:25px;" type="checkbox" name="selected[]" value="<?php echo $w['withdrawal_id'];?>"/>
												</td>
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
							<div class="pagination"><?php echo $pagination; ?></div>
						</div>
					</div>
				</div>
			</div>	
		</div>	
	</form>
	<hr>			
	<div id="dialog-message" title="Confirmation Message" style="display:none; width: 400px;">
	  <span id="msg"></span>
	</div>	
</div>
<?php //} else { ?>
<!--<div class="card">	  
    <div class="card-body">
		<div class="col-md-12">
			<h1>Only activated account can withdraw. <br>Please play at least once in the instawin section to be activated. <br>Kindly request a transfer of at least 1000 E-coins from designated bankers.</h1>
		</div>
	</div>
</div>-->
<?php //} ?>
<script type="text/javascript"><!--
$(document).ready(function() {

	<?php if(isset($err_msg)) { ?>
		<?php if(!empty($err_msg)) { ?>
			// $('#msg').html("<?php echo $err_msg; ?>");			      
			// $(function() {
				// $("#dialog-message").dialog({
					// modal: true,
					// width: 300,
					// buttons: {
						// Ok: function() {
							// $(this).dialog("close");
						// }
					// }
				// });
			// });
			var msg = "<?php echo $err_msg; ?>";
			swal("Good Job!", msg, "success");
		<?php } ?>
	<?php } ?>	
	
	$('#username').change(function(){
		getBankerInfo();
	});
	
	$('#amount').change(function(){
		var amount_in_ecoins = 50 * $('#amount').val();
		var total_cost_in_btc_hdn = $('#total_cost_in_btc_hdn').val();
		$('#amount_in_ecoins_disp').val(insertCommas(""+amount_in_ecoins) + " E-Coins");
		$('#amount_in_ecoins').val(amount_in_ecoins);
		$('#amount_in_btc').val(($('#amount').val() * total_cost_in_btc_hdn * (1-0.025)).toFixed(8));
	});
});

function getBankerInfo() {
	getBankerInformation($('#username').val());
}

<?php if($this->user->getUserGroupId() == 13 or $this->user->getUserGroupId() == 39 or $this->user->getUserGroupId() == 36) { ?>

function process(task) {
	$('#task').val(task);
	$('form').attr('action', 'withdrawapp'); 
	$('form').submit();
}

function exportToCsv() {
	$('form').attr('action', 'withdrawappexport'); 
	$('form').submit();
}

function numberOfTotalEcoinsChanged() {
	var totalEcoins = $('#totalEcoins').val();
	var total_cost_in_btc_hdn = $('#total_cost_in_btc_hdn').val();
	$('#total_cost_in_usd').val(insertCommas(""+(totalEcoins/50).toFixed(2)));
	$('#total_cost_in_usd_hidden').val((totalEcoins/50).toFixed(2));
	$('#total_cost_in_btc').val(((totalEcoins/50) * total_cost_in_btc_hdn).toFixed(8));
	
}

<?php } ?>

function insertCommas(s) {
    // get stuff before the dot
    var d = s.indexOf('.');
    var s2 = d === -1 ? s : s.slice(0, d);
    // insert commas every 3 digits from the right
    for (var i = s2.length - 3; i > 0; i -= 3)
      s2 = s2.slice(0, i) + ',' + s2.slice(i);
    // append fractional part
    if (d !== -1)
      s2 += s.slice(d);
    return s2;
}


//--> 
</script>
<?php echo $footer; ?>