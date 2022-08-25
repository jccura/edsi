<?php echo $header; ?>
<div class="memberdashboardback">
	<br>
	<div class="row card-margin">   
		<div class="col-sm-4 col-xs-6">
		  <div class="card panel panel-default text-center box_vip_border">
			<div class="panel-heading box-vip-dash">
				<h4>Available E-Coins</h4>
			</div>
			<div class="panel-footer box-vip-pad">
				<h4><?php echo number_format($epoints,0); ?></h4>
			</div>
		  </div>      
		</div> 
		<div class="col-sm-4 col-xs-6">
		  <div class="card panel panel-default text-center box_vip_border">
			<div class="panel-heading box-vip-dash2">
				<h4>Total Transfered</h4>
			</div>
			<div class="panel-footer box-vip-pad">
				<h4><?php echo number_format($total_transfered,0); ?></h4>
			</div>
		  </div>      
		</div> 
		<div class="col-sm-4 col-xs-6">
		  <div class="card panel panel-default text-center box_vip_border">
			<div class="panel-heading box-vip-dash">
				<h4>Total Received</h4>
			</div>
			<div class="panel-footer box-vip-pad">
				<h4><?php echo number_format($total_received,0); ?></h4>
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
					<div class="col-md-2"><label>Username<br>To Transfer:</label></div>
					<div class="col-md-4">
						<div class="form-group">
							<input type="text" class="form-control" id="username" name="username" value="">
						</div>					
					</div>
					<div class="col-md-3"></div>
				</div>
				<div class="row user_info hidden">
					<br>
					<div class="col-md-3"></div>
					<div class="col-md-2"><label>Name:</label></div>
					<div class="col-md-4">
						<div class="form-group">
							<input type="text" class="form-control" id="user_name" name="user_name" value="" readonly>
						</div>					
					</div>
					<div class="col-md-3"></div>
				</div>				
				<div class="row user_info hidden">
					<br>
					<div class="col-md-3"></div>
					<div class="col-md-2"><label>User Id Number:</label></div>
					<div class="col-md-4">
						<div class="form-group">
							<input type="hidden" id="user_id" name="user_id" value="0">
							<input type="text" class="form-control" id="id_no" name="id_no" value="" readonly>
						</div>					
					</div>
					<div class="col-md-3"></div>
				</div>
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-2"><label>Amount of E-Coins<br>To Transfer:</label></div>
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
						<input class="btn btn-primary btn" type="button" id="search" value="Proceed" onclick="javascript: process('transfer');">
					</div>
				</div>
			</form>			
		</div>
	</div>
	<br>
	<div class="card card-margin">	  
		<div class="card-body">	
			<div class="row">
				<div class="col-md-12">
					<div align="center">
						<div style="width: 100%; overflow: auto;">	
							<table class="list">
								<thead>
								<tr>
									<td class="left numeric" >Transfer Count</td>										
									<td class="left numeric" >Transfer To</td>										
									<td class="left numeric" >Debited E-Coin</td>									
									<td class="left numeric" >Credited E-Coin</td>									
									<td class="left numeric" >Date Added</td>
								</tr>
								</thead>
								<tbody>
								<?php if (isset($transfers)) { ?>
									<?php foreach ($transfers as $tr) { ?>
										<tr>
											<td><?php echo $total;?></td>
											<td><?php echo $tr['username'];?></td>
											<td><?php echo $tr['debit'];?></td>
											<td><?php echo $tr['credit'];?></td>
											<td><?php echo $tr['date_added'];?></td>
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
		</div>	
	</div>	
	<hr>			
	<div id="dialog-message" title="Confirmation Message" style="display:none; width: 400px;">
	  <span id="msg"></span>
	</div>	
</div>
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
	
	$('#username').change(function(){
		getUserInfo();
	});
});

function getUserInfo() {
	getUserInformation($('#username').val());
}

<?php if($this->user->getUserGroupId() == 13 or $this->user->getUserGroupId() == 39 or $this->user->getUserGroupId() == 36) { ?>

function process(task) {
	$('#task').val(task);
	$('form').attr('action', 'transfer'); 
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