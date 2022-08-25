<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<i class="ni ni-money-coins ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp; Wallet</h6>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid mt--6">
	
	<div class="row">
		<div class="col-xl-12 col-md-12">
			<div class="card btn-outline-upper border-0">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12 text-center">
							<span class="shortcut-media avatar rounded-circle bg-gradient-info">
							  <i class="ni ni-credit-card"></i>
							</span>
						</div>
						<div class="col-md-12 text-right">
							<h1 class="card-title text-uppercase text-muted mb-0 text-white text-center">E-Wallet</h1>
							<h2 class="card-title text-uppercase text-muted mb-0 text-white text-center">Php <?php echo number_format($current_ewallet,2); ?></h5>
						</div>
					</div>
				</div>
			</div>
		</div>	
		
		<div class="col-xl-4 col-md-4">
			<div class="card btn-outline-upper border-0">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12 text-right">
							<h2 class="card-title text-uppercase text-muted mb-0 text-white text-center">Total Commissions</h1>
							<h3 class="card-title text-uppercase text-muted mb-0 text-white text-center">Php <?php echo number_format($total_coms,2); ?></h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-xl-4 col-md-4">
			<div class="card btn-outline-upper border-0">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12 text-right">
							<h2 class="card-title text-uppercase text-muted mb-0 text-white text-center">Order Commissions</h1>
							<h3 class="card-title text-uppercase text-muted mb-0 text-white text-center">Php <?php echo number_format($ord_coms,2); ?></h5>
						</div>
					</div>
				</div>
			</div>
		</div>	
		
		<div class="col-xl-4 col-md-4">
			<div class="card btn-outline-upper border-0">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12 text-right">
							<h2 class="card-title text-uppercase text-muted mb-0 text-white text-center">Referral Bonus</h1>
							<h3 class="card-title text-uppercase text-muted mb-0 text-white text-center">Php <?php echo number_format($referrals,2); ?></h5>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>	
	<div class="card">
		<!-- Card header -->
		<div class="card-header border-0">
		  <div class="row">
			<div class="col-6">
			  <h3 class="mb-0">List of E-Wallet</h3>
			</div>
		  </div>
		</div>
		<!-- Light table -->
		<div class="table-responsive">
		  <table class="table align-items-center table-flush">
			<thead class="thead-light">
			<tr class="table-primary">
				<th class="text-info" >Type</th>
				<th class="text-info" >From Username</th>
				<th class="text-info" >Order Id</th>
				<th class="text-info" >Request Id</th>
				<th class="text-info" >Debited</th>										
				<th class="text-info" >Net Credited</th>										
				<th class="text-info" >Tax</th>
				<th class="text-info" >Gross</th>				
				<th class="text-info" >Date</th>																	
			</tr>
			</thead>
			<tbody>
				<?php if (isset($wallets)) { ?>
					<?php foreach ($wallets as $w) { ?>
						<tr>
							<td><?php echo $w['com_type'];?></td>
							<td>
								<?php if(strtolower($this->user->getUsername()) == strtolower($w['source_username'])) { ?>
									<?php echo "Own";?>
								<?php } else { ?>
									<?php echo $w['source_username'];?>
								<?php } ?>
							</td>
							<td><?php echo $w['order_id'];?></td>
							<td><?php echo $w['request_id'];?></td>
							<td>Php <?php echo number_format($w['debit'],2);?></td>
							<td>Php <?php echo number_format($w['credit'],2);?></td>
							<td>Php <?php echo number_format($w['tax'],2);?></td>
							<td>Php <?php echo number_format($w['gross_amt'],2);?></td>
							<td><?php echo $w['date_added'];?></td>
						</tr>
					<?php } ?>	
				<?php } ?>
			</tbody>
		  </table>
		</div>
		<ul class="pagination">
			<div class="page-link bg-transparent border-0"><?php echo $pagination; ?></div>
		</ul>
	 </div>
	<!--end of card-->			
</div>

<?php echo $footer; ?>