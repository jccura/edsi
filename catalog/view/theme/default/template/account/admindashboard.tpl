<?php echo $header; ?>
<div class="memberdashboardback">
	<br>
	<h1 class="page-heading mb-4 text-center white-text">ADMIN DASHBOARD</h1>
	<div class="row">
		<div class="col-md-12">
		   <form action="" method="post" enctype="multipart/form-data" id="form">
				<input type="hidden" id="task" name="task" value="">      
				<input type="hidden" id="commission_type_id" name="commission_type_id" value="">      
				<input type="hidden" id="datefrom" name="datefrom" value="">      
				<input type="hidden" id="dateto" name="dateto" value="">			
				<br>
				<div class="row">
					<div class="col-sm-1 col-md-2 col-xs-6"></div>     
					<div class="col-sm-4 col-xs-6">
						<div class="card panel panel-default text-center box_vip_border">
							<div class="panel-heading box-vip-dash">
								<h4>INSTAWIN E-COINS PLAYED</h4>
							</div>
							<div class="panel-footer box-vip-pad">
								<h4>EC <?php echo number_format($played_ecoins,0);?></h4>
								<h4>$ <?php echo number_format($played_ecoins/50,2);?></h4>
							</div>
						</div>      
					</div> 
					<div class="col-sm-4 col-xs-6">
						<div class="card panel panel-default text-center box_vip_border">
							<div class="panel-heading box-vip-dash2">
								<h4>VAULT-IN E-COINS</h4>
							</div>
							<div class="panel-footer box-vip-pad">
								<h4>EC <?php echo number_format($vaultin_total,0);?></h4>
								<h4>$ <?php echo number_format($vaultin_total/50,2);?></h4>							
							</div>
						</div>      
					</div> 
					<div class="col-sm-1 col-md-2 col-xs-6"></div>
				</div>	
				<div class="row">
					<div class="col-sm-1 col-md-2 col-xs-6"></div>     
					<div class="col-sm-4 col-xs-6">
						<div class="card panel panel-default text-center box_vip_border">
							<div class="panel-heading box-vip-dash3">
								<h4>TRANSFERRED ECOINS</h4>
							</div>
							<div class="panel-footer box-vip-pad">
								<h4>EC <?php echo number_format($transfer_total,0);?></h4>
								<h4>$ <?php echo number_format($transfer_total/50,2);?></h4>
							</div>
						</div>      
					</div> 
					<div class="col-sm-4 col-xs-6">
						<div class="card panel panel-default text-center box_vip_border">
							<div class="panel-heading box-vip-dash4">
								<h4>TOTAL ECOINS GENERATED</h4>
							</div>
							<div class="panel-footer box-vip-pad">
								<h4>EC <?php echo number_format($overall_ecoins,0);?></h4>
								<h4>$ <?php echo number_format($overall_ecoins/50,2);?></h4>
							</div>
						</div>      
					</div> 
					<div class="col-sm-1 col-md-2 col-xs-6"></div>
				</div>
				<div class="row">
					<div class="col-sm-1 col-md-2 col-xs-6"></div>     
					<div class="col-sm-4 col-xs-6">
						<div class="card panel panel-default text-center box_vip_border">
							<div class="panel-heading box-vip-dash">
								<h4>TOTAL INSTAWIN WINNINGS</h4>
							</div>
							<div class="panel-footer box-vip-pad">
								<h4>EC <?php echo number_format($instawin_winnings,0);?></h4>
								<h4>$ <?php echo number_format($instawin_winnings/50,2);?></h4>
							</div>
						</div>      
					</div> 
					<div class="col-sm-4 col-xs-6">
						<div class="card panel panel-default text-center box_vip_border">
							<div class="panel-heading box-vip-dash2">
								<h4>VAULT-IN EARNINGS</h4>
							</div>
							<div class="panel-footer box-vip-pad">
								<h4>EC <?php echo number_format($vaultin_earnings * 50,2);?></h4>
								<h4>$ <?php echo number_format($vaultin_earnings,2);?></h4>
							</div>
						</div>      
					</div> 
					<div class="col-sm-1 col-md-2 col-xs-6"></div>
				</div>				
			</form>
		</div>
	</div>
</div>
<?php echo $footer; ?>

<script type="text/javascript"><!--

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

});

//--></script>