<?php echo $header; ?>
<div class="memberdashboardback">
	<br>
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<form action="" method="post" enctype="multipart/form-data" id="form">
				<input type="hidden" id="task" name="task" value="" />
				<input type="hidden" id="total_cost_in_btc_hdn" name="total_cost_in_btc_hdn" value="0">
				<input type="hidden" id="total_cost_in_usd_hidden" name="total_cost_in_usd_hidden" value="0">
				<div class="row">
					<div class="col-md-12">
						<center>
							<input type="hidden" id="trans_session_id" name="trans_session_id" value="<?php echo $trans_session_id; ?>" />						
							<input type="hidden" id="type" name="type" value="1000" />
							<button width="100px" class="playnowbtn" onclick="javascript: process('play');"><img width="100%" class="img-responsive" src="image/playnow.png"></button>
						</center>
					</div>
				</div>
			</form>			
		</div>
		<div class="col-md-3"></div>
	</div>
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
				<h4>Number of Plays</h4>
			</div>
			<div class="panel-footer box-vip-pad">
				<h4><?php echo $total; ?></h4>
				<h6>EC <?php echo number_format($total * 1000,2); ?></h6>
			</div>
		  </div>      
		</div> 
		<div class="col-sm-4 col-xs-6">
		  <div class="card panel panel-default text-center box_vip_border">
			<div class="panel-heading box-vip-dash2">
				<h4>Total Winnings</h4>
			</div>
			<div class="panel-footer box-vip-pad">
				<h4>$ <?php echo number_format($wins,2); ?></h4>
				<h6>EC <?php echo number_format($wins * 50,2); ?></h6>
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
						<div style="width: 100%; overflow:auto;">	
							<table class="list">
								<thead>
								<tr>
									<td class="left numeric" >Play Count</td>
									<td class="left numeric" >Bet E-Coins</td>										
									<td class="left numeric" >Status</td>										
									<td class="left numeric" >Date of Attempt</td>
								</tr>
								</thead>
								<tbody>
								<?php if (isset($instawins)) { ?>
									<?php foreach ($instawins as $ins) { ?>
										<tr>
											<td><?php echo $total;?></td>
											<td><?php echo $ins['amount'];?></td>
											<td>
												<?php if($ins['status'] == "Win") { ?>
													<center><h6 style='color:green; font-weight: bold;'><?php echo $ins['status'];?></h6>
												<?php } else { ?>
													<center><h6 style='color:red; font-weight: bold;'><?php echo $ins['status'];?></h6>
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
	
	<?php if(isset($winner)) { ?>
		<?php if($winner == 1) { ?>
			$('#msg').html("<center><h1 style='color:green; font-weight: bold;'>You win!</h1></center><br><h4>$40 has been credited to your ewallet.</h4>");			      
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
	$('form').attr('action', 'instawin'); 
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