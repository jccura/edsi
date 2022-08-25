<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<i class="ni ni-bag-17 ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Sales Inventory Report</h6>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid mt--6">	
	<form action="" method="post" enctype="multipart/form-data" id="form">
		<input type="hidden" id="task" name="task" value=""> 
		<div class="row">	
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">	
						<div class="row">
							<div class="col-md-4"></div>	
							<div class="col-md-2">
								<label>Date From</label>
								<input class="form-control input" type="date" id="datefrom" name="datefrom" value="">
							</div>	
							<div class="col-md-2">
								<label>Date To</label>
								<input class="form-control input" type="date" id="dateto" name="dateto" value="">
							</div>	
						</div>	
						<div class="row">
							<div class="col-md-4"></div>
							<div class="col-md-4">
								<center>
									<br><br>
									<button class="btn btn-outline-upper text-white" type="button" onclick="javascript: performTask('search');"><i class="fa fa-search"></i> Search</button>
									<button class="btn btn-outline-upper text-white" type="button" onclick="javascript: exporttocsv();"><i class="fa fa-search"></i> Export</button>
								</center>				
							</div>	
							<div class="col-md-4"></div>
						</div>
						<br>
						<?php if (isset($summary)) { ?>
						<div class="row card-margin">
							<div class="col-xl-3 col-md-3">
								<div class="card bg-gradient-lightbl border-0">
									<div class="card-body">
										<div class="row">
											<div class="col-md-12 text-right">
												<h1 class="card-title text-uppercase text-muted mb-0 text-white">P <?php echo number_format($summary['srp'], 2);?></h1>
												<h5 class="card-title text-uppercase text-muted mb-0 text-white">SRP TOTAL SALES</h5>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-3">
								<div class="card bg-gradient-lightbl border-0">
									<div class="card-body">
										<div class="row">
											<div class="col-md-12 text-right">
												<h1 class="card-title text-uppercase text-muted mb-0 text-white">P <?php echo number_format($summary['direct_referral'], 2);?></h1>
												<h5 class="card-title text-uppercase text-muted mb-0 text-white">DIRECT REFERRAL</h5>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-3">
								<div class="card bg-gradient-lightbl border-0">
									<div class="card-body">
										<div class="row">
											<div class="col-md-12 text-right">
												<h1 class="card-title text-uppercase text-muted mb-0 text-white">P <?php echo number_format($summary['shipping'], 2);?></h1>
												<h5 class="card-title text-uppercase text-muted mb-0 text-white">SHIPPING</h5>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-3">
								<div class="card bg-gradient-lightbl border-0">
									<div class="card-body">
										<div class="row">
											<div class="col-md-12 text-right">
												<h1 class="card-title text-uppercase text-muted mb-0 text-white">P <?php echo number_format($summary['service_fee'], 2);?></h1>
												<h5 class="card-title text-uppercase text-muted mb-0 text-white">SERVICE FEE</h5>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row card-margin">
							<div class="col-xl-3 col-md-3">
								<div class="card bg-gradient-lightbl border-0">
									<div class="card-body">
										<div class="row">
											<div class="col-md-12 text-right">
												<h1 class="card-title text-uppercase text-muted mb-0 text-white">P <?php echo number_format($summary['system_fee'], 2);?></h1>
												<h5 class="card-title text-uppercase text-muted mb-0 text-white">SYSTEM FEE/ IT</h5>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-3">
								<div class="card bg-gradient-lightbl border-0">
									<div class="card-body">
										<div class="row">
											<div class="col-md-12 text-right">
												<h1 class="card-title text-uppercase text-muted mb-0 text-white">P <?php echo number_format($summary['topup'], 2);?></h1>
												<h5 class="card-title text-uppercase text-muted mb-0 text-white">TOP UP</h5>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-3">
								<div class="card bg-gradient-lightbl border-0">
									<div class="card-body">
										<div class="row">
											<div class="col-md-12 text-right">
												<h1 class="card-title text-uppercase text-muted mb-0 text-white">P <?php echo number_format($summary['cv'], 2);?></h1>
												<h5 class="card-title text-uppercase text-muted mb-0 text-white">CV</h5>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-3">
								<div class="card bg-gradient-lightbl border-0">
									<div class="card-body">
										<div class="row">
											<div class="col-md-12 text-right">
												<h1 class="card-title text-uppercase text-muted mb-0 text-white">P <?php echo number_format($summary['tools'], 2);?></h1>
												<h5 class="card-title text-uppercase text-muted mb-0 text-white">TOOLS</h5>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row card-margin">
							<div class="col-xl-3 col-md-3">
								<div class="card bg-gradient-lightbl border-0">
									<div class="card-body">
										<div class="row">
											<div class="col-md-12 text-right">
												<h1 class="card-title text-uppercase text-muted mb-0 text-white">P <?php echo number_format($summary['tax'], 2);?></h1>
												<h5 class="card-title text-uppercase text-muted mb-0 text-white">TAX</h5>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-3">
								<div class="card bg-gradient-lightbl border-0">
									<div class="card-body">
										<div class="row">
											<div class="col-md-12 text-right">
												<h1 class="card-title text-uppercase text-muted mb-0 text-white">P <?php echo number_format($summary['cost'], 2);?></h1>
												<h5 class="card-title text-uppercase text-muted mb-0 text-white">PRODUCT COST</h5>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-3">
								<div class="card bg-gradient-lightbl border-0">
									<div class="card-body">
										<div class="row">
											<div class="col-md-12 text-right">
												<h1 class="card-title text-uppercase text-muted mb-0 text-white">P <?php echo number_format($summary['commissions'], 2);?></h1>
												<h5 class="card-title text-uppercase text-muted mb-0 text-white">COMMISSIONS</h5>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-3">
								<div class="card bg-gradient-lightbl border-0">
									<div class="card-body">
										<div class="row">
											<div class="col-md-12 text-right">
												<h1 class="card-title text-uppercase text-muted mb-0 text-white">P <?php echo number_format($summary['income'], 2);?></h1>
												<h5 class="card-title text-uppercase text-muted mb-0 text-white">INCOME</h5>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="table-responsive">
				<table class="table align-items-center table-flush">
					<thead class="thead-dark text-white">
						<input type="hidden" id="task" name="task" value="" />
						<input type="hidden" id="sales_inventory_id" name="sales_inventory_id" value="" />
						<tr class="table-primary">
							<th scope="col">Sales Inventory ID</th>
							<th scope="col">Username</th>
							<th scope="col">Order ID</th>
							<th scope="col">Request ID</th>
							<th scope="col">Product</th>
							<th scope="col">SRP</th>
							<th scope="col">Distributor Price</th>
							<th scope="col">Reseller Price</th>											  
							<th scope="col">Direct Referral</th>											  
							<th scope="col">Shipping</th>											  
							<th scope="col">Service Fee</th>											  
							<th scope="col">System Fee/ IT</th>											  
							<th scope="col">Top Up</th>												  
							<th scope="col">CV</th>											  
							<th scope="col">Tool</th>
							<th scope="col">Tax</th>
							<th scope="col">Cost</th>
							<th scope="col">Commissions</th>
							<th scope="col">Income</th>
							<th scope="col">Date Added</th>
						</tr>
					</thead>
					<tbody class="list">	
						<?php if (isset($salesinventory)) { ?>
							<?php foreach ($salesinventory as $sa) { ?>
								<tr>
									<td><?php echo $sa['sales_inventory_id'];?></td>
									<td><?php echo $sa['username'];?></td>
									<td><?php echo $sa['order_id'];?></td>
									<td><?php echo $sa['request_id'];?></td>
									<td><?php echo $sa['item_name'];?></td>
									<td><?php echo "Php ".number_format($sa['srp'], 2);?></td>
									<td><?php echo "Php ".number_format($sa['distributor_price'], 2);?></td>
									<td><?php echo "Php ".number_format($sa['reseller_price'], 2);?></td>
									<td><?php echo "Php ".number_format($sa['direct_referral'], 2);?></td>
									<td><?php echo "Php ".number_format($sa['shipping'], 2);?></td>
									<td><?php echo "Php ".number_format($sa['service_fee'], 2);?></td>
									<td><?php echo "Php ".number_format($sa['system_fee'], 2);?></td>
									<td><?php echo "Php ".number_format($sa['topup'], 2);?></td>
									<td><?php echo "Php ".number_format($sa['cv'], 2);?></td>
									<td><?php echo "Php ".number_format($sa['tools'], 2);?></td>
									<td><?php echo "Php ".number_format($sa['tax'], 2);?></td>
									<td><?php echo "Php ".number_format($sa['cost'], 2);?></td>
									<td><?php echo "Php ".number_format($sa['commissions'], 2);?></td>
									<td><?php echo "Php ".number_format($sa['income'], 2);?></td>
									<td><?php echo $sa['date_added'];?></td>
								</tr>
							<?php } ?>	
						<?php } ?>				
					</tbody>
				</table>
			</div>
		</div>	
	</form>		
</div>
<?php echo $footer; ?>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#datefrom').datepicker({dateFormat: 'yy-mm-dd'});
	$('#dateto').datepicker({dateFormat: 'yy-mm-dd'});
});

function performTask(task) {

	$('.task').val(task);
	$('form').attr('action', 'salesinv'); 
	$('form').submit();
	
}

function exporttocsv() {
	
	$('form').attr('action', 'salesinvexport'); 
	$('form').submit();
	
}

 function bodyOnLoad() {
	 
 }
</script>					