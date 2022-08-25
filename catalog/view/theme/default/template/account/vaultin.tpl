<?php echo $header; ?>
<?php if($this->user->getActivationFlag() == 1) { ?>
<div class="memberdashboardback">
	<br>
	<div class="row card-margin">   
		<div class="col-sm-4 col-xs-6">
		  <div class="card panel panel-default text-center box_vip_border">
			<div class="panel-heading box-vip-dash">
				<h4>Available E-Coins</h4>
			</div>
			<div class="panel-footer box-vip-pad">
				<h4>EC <?php echo number_format($epoints,2); ?></h4>
				<h6>$ <?php echo number_format($epoints/50,2); ?></h6>
			</div>
		  </div>      
		</div> 
		<div class="col-sm-4 col-xs-6">
		  <div class="card panel panel-default text-center box_vip_border">
			<div class="panel-heading box-vip-dash3">
				<h4>Total Vault-ins</h4>
			</div>
			<div class="panel-footer box-vip-pad">
				<h4>EC <?php echo number_format($vaultin_total,2); ?></h4>
				<h6>$ <?php echo number_format($vaultin_total/50,2); ?></h6>
			</div>
		  </div>      
		</div> 
		<div class="col-sm-4 col-xs-6">
		  <div class="card panel panel-default text-center box_vip_border">
			<div class="panel-heading box-vip-dash2">
				<h4>Total Earnings</h4>
			</div>
			<div class="panel-footer box-vip-pad">
				<h4>$ <?php echo number_format($earnings,2); ?></h4>
				<h6>EC <?php echo number_format($earnings*50,2); ?></h6>
			</div>
		  </div>      
		</div>
	</div>
	<br>
	<div class="card card-margin">	  
		<div class="card-body">	
			<form action="" method="post" enctype="multipart/form-data" id="form">
				<input type="hidden" id="task" name="task" value="" />
				<input type="hidden" id="total_cost_in_btc_hdn" name="total_cost_in_btc_hdn" value="0">
				<input type="hidden" id="total_cost_in_usd_hidden" name="total_cost_in_usd_hidden" value="0">
				<input type="hidden" id="trans_session_id" name="trans_session_id" value="<?php echo $trans_session_id; ?>" />
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-2"><label>Amount of E-Coins<br>To Vault-in:</label></div>
					<div class="col-md-4">
						<div class="form-group">
							<select class="form-control" name="amount" id="amount" >
								<option value='0'>Select One Amount</option>
								<?php for($i=1000;$i<=1000000;) { ?>
									<option value="<?php echo $i;?>"> <?php echo number_format($i,0);?> E-Coins</option>
									<?php $i += 1000; ?>
								<?php } ?>
							</select>
						</div>					
					</div>
					<div class="col-md-3">
						<input class="btn btn-primary btn" type="button" id="search" value="Proceed" onclick="javascript: process('add');">
					</div>
				</div>
			</form>			
		</div>
	</div>
	<br>

	<div class="row card-margin">
		<div class="col-md-12">
			<div align="center">
				<div style="width:100%; overflow:auto;">	
					<table class="list">
						<thead>
						<tr>
							<td>Vault-in Number</td>
							<td>Vault-in Amount<br>in E-Coins</td>										
							<td>Vault-in Amount<br>in Dollar</td>																				
							<td>Week1 Profit</td>										
							<td>Week2 Profit</td>										
							<td>Week3 Profit</td>										
							<td>Week4 Profit</td>										
							<td>Week5 Profit</td>										
							<td>Week6 Profit</td>										
							<td>Total Profit</td>										
							<td>Processing</td>										
							<td>Status</td>										
							<td>Date Submitted</td>
						</tr>
						</thead>
						<tbody>
						<?php if (isset($vaultins)) { ?>
							<?php foreach ($vaultins as $ins) { ?>
								<tr>
									<td><?php echo $ins['vaultin_id'];?></td>
									<td><?php echo $ins['amount'];?></td>
									<td><b>$ <?php echo $ins['amount']/50;?></b></td>
									<td><b>$ <?php echo number_format($ins['week1_roi'],2); ?></b></td>
									<td><b>$ <?php echo number_format($ins['week2_roi'],2); ?></b></td>
									<td><b>$ <?php echo number_format($ins['week3_roi'],2); ?></b></td>
									<td><b>$ <?php echo number_format($ins['week4_roi'],2); ?></b></td>
									<td><b>$ <?php echo number_format($ins['week5_roi'],2); ?></b></td>
									<td><b>$ <?php echo number_format($ins['week6_roi'],2); ?></b></td>
									<td><b>$ <?php echo number_format($ins['roi_amount'],2); ?></b></td>
									<td>
										Day: <b><?php echo $ins['days'];?></b><br>
										Date: <b><?php echo $ins['dates'];?></b><br>
										Number of Days: <b><?php echo $ins['cycle_number'];?></b><br>
									</td>
									<td>
										<?php if($ins['status'] == "Completed") { ?>
											<center><h6 style='color:green; font-weight: bold;'><?php echo $ins['status'];?></h6>
										<?php } else { ?>
											<center><h6 style='color:orange; font-weight: bold;'><?php echo $ins['status'];?></h6>
										<?php } ?>
									</td>
									<td><?php echo $ins['date_added'];?></td>
									<?php $total = $total - 1; ?>
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
	
	<hr>			
	<div id="dialog-message" title="Confirmation Message" style="display:none; width: 400px;">
	  <span id="msg"></span>
	</div>	
</div>
<?php } else { ?>
<div class="card">	  
    <div class="card-body">
		<div class="col-md-12">
			<h1>Only activated account can vault-in. <br>Please play at least once in the instawin section to be activated. <br>Kindly request a transfer of at least 1000 E-coins from designated bankers.</h1>
		</div>
	</div>
</div>
<?php } ?>
<script type="text/javascript"><!--
$(document).ready(function() {	

	<?php if(isset($err_msg)) { ?>
		<?php if(!empty($err_msg)) { ?>
			$('#msg').html("<?php echo $err_msg; ?>");			      
			$(function() {
				$("#dialog-message").dialog({
					modal: true,
					width: 300,
					buttons: {
						Ok: function() {
							$(this).dialog("close");
						}
					}
				});
			});
		<?php } ?>
	<?php } ?>	
	
	$('#totalEcoins').change(function(){
		numberOfTotalEcoinsChanged();
	});	
});

<?php if($this->user->getUserGroupId() == 13 or $this->user->getUserGroupId() == 39 or $this->user->getUserGroupId() == 36) { ?>

function process(task) {
	$('#task').val(task);
	$('form').attr('action', 'vaultin'); 
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