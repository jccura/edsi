<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<i class="ni ni-shop ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp&nbspMember Dashboard</h6>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="memberdashboardback">
	<div class="row mb-4">
		<div class="col-md-12">
		   <form action="" method="post" enctype="multipart/form-data" id="form">
				<input type="hidden" id="task" name="task" value="">      
				<input type="hidden" id="commission_type_id" name="commission_type_id" value="">      
				<input type="hidden" id="datefrom" name="datefrom" value="">      
				<input type="hidden" id="dateto" name="dateto" value="">
				<div class="container-fluid mt--6">
					<div class="row">
						<div class="col-xl-6 col-md-6">
							<div class="card btn-outline-upper border-0">
								<div class="panel-heading box-vip-dash3">
									<div class="card-body">
										<h3 class="mb-0 text-md-3 text-white">Total Personal Sales</h3>
										<h4 class="text-nowrap text-white font-weight-600">Php <?php echo number_format($total_sales,2); ?></h4>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-6">
							<div class="card btn-outline-upper border-0">
								<div class="panel-heading box-vip-dash3">
									<div class="card-body">
										<h3 class="mb-0 text-md-3 text-white">Personal Sales</h3>
										<h4 class="text-nowrap text-white font-weight-600">Php <?php echo number_format($monthly_sales,2); ?></h4>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-6">
							<div class="card btn-outline-upper border-0">
								<div class="panel-heading box-vip-dash3">
									<div class="card-body">
										<h3 class="mb-0 text-md-3 text-white">Group Sales</h3>
										<h4 class="text-nowrap text-white font-weight-600">Php <?php echo number_format($total_group_sales,2); ?></h4>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-3 col-md-6">
							<div class="card btn-outline-upper border-0">
								<div class="panel-heading box-vip-dash3">
									<div class="card-body">
										<h3 class="mb-0 text-md-3 text-white">Available E-Wallet</h3>
										<h4 class="text-nowrap text-white font-weight-600">Php <?php echo number_format($ewallet,2); ?></h4>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-6">
							<div class="card btn-outline-upper border-0">
								<div class="card-body">
									<h3 class="mb-0 text-md-3 text-white">Unilevel Income</h3>
									<h4 class="text-nowrap text-white font-weight-600">Php <?php echo number_format($unilevel_income,2); ?></h4>
								</div>
							</div>      
						</div>
						<div class="col-xl-3 col-md-6">
							<div class="card btn-outline-upper  border-0">
								<div class="panel-heading box-vip-dash3">
									<div class="card-body">
										<h3 class="mb-0 text-md-3 text-white">Rank Bonus </h3>
										<h4 class="text-nowrap text-white font-weight-600">Php <?php echo number_format($total_rank_bonus,2); ?></h4>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-6">
							<div class="card btn-outline-upper border-0">
								<div class="panel-heading box-vip-dash3">
									<div class="card-body">
										<h3 class="mb-0 text-md-3 text-white">Referral Income</h3>
										<h4 class="text-nowrap text-white font-weight-600">Php <?php echo number_format($total_referrals,2); ?></h4>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-3 col-md-6">
							<div class="card btn-outline-upper border-0">
								<div class="panel-heading box-vip-dash3">
									<div class="card-body">
										<h3 class="mb-0 text-md-3 text-white">Total E-Wallet</h3>
										<h4 class="text-nowrap text-white font-weight-600">Php <?php echo number_format($total_earnings,2); ?></h4>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-6">
							<div class="card btn-outline-upper border-0">
								<div class="card-body">
									<h3 class="mb-0 text-md-3 text-white">Total Withdrawal</h3>
									<h4 class="text-nowrap text-white font-weight-600">Php <?php echo number_format($withdrawal,2); ?></h4>
								</div>
							</div>      
						</div>
						<div class="col-xl-3 col-md-6">
							<div class="card btn-outline-upper border-0">
								<div class="panel-heading box-vip-dash3">
									<div class="card-body">
										<h3 class="mb-0 text-md-3 text-white">Current PV</h3>
										<h4 class="text-nowrap text-white font-weight-600"> <?php echo number_format($total_pv,0); ?> CV</h4>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-6">
							<div class="card btn-outline-upper  border-0">
								<div class="panel-heading box-vip-dash3">
									<div class="card-body">
										<h3 class="mb-0 text-md-3 text-white">Last Month PV</h3>
										<h4 class="text-nowrap text-white font-weight-600">Php <?php echo number_format($total_month1_pv,2); ?></h4>
									</div>
								</div>
							</div>
						</div>
					</div>
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

function copyToClipboard(url) {
	var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(url).select();
    document.execCommand("copy");
    $temp.remove();
	alert("URL copied.");
	return false;
}

//--></script>