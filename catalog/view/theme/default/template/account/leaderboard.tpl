<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<i class="ni ni-trophy ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;LeaderBoard</h6>
				</div>
			</div>
		</div>
	</div>
</div>	
<div class="container-fluid mt--6">
	<form action="" method="post" enctype="multipart/form-data" id="form">
		<div class="card card-margin">	  
			<div class="card-body">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">	
								<select required class="form-control input" id="month_leaderboard" name="month_leaderboard">
									<option value="" selected>Select Month</option>
									<option value="01">January</option>
									<option value="02">February</option>
									<option value="03">March</option>
									<option value="04">April</option>
									<option value="05">May</option>
									<option value="06">June</option>
									<option value="07">July</option>
									<option value="08">August</option>
									<option value="09">September</option>
									<option value="10">October</option>
									<option value="11">November</option>
									<option value="12">December</option>
								</select>
							</div>	
						</div>	
						<br>						
						<br>
						<div class="col-md-3">						
							<div class="form-group">	
								<input class="btn btn-outline-upper btn text-white" type="button" id="search" value="Filter Result" onclick="javascript:processTask('search');">												
							</div>			
						</div>			
					</div>	
				</div>	
				<br>
				<br>
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-6">
								<div class="card card btn-outline-upper shadow">
									<div class="dropdown">	
										<div class="card-header bg-transparent border-0">
											<center>
												 <h3 class="text-white mb-0"><i class="ni ni-trophy text-white"></i> Top Distributor <i class="ni ni-trophy text-white"></i></h3>
												<i class="text-white" style=" font-size:13px;">(Based on the total amount of order packages of<br> a Distributor/Reseller Month of <?php if(isset($data['month_leaderboard'])) { echo date('F', strtotime("2020-".$data['month_leaderboard']."-01")); } else  {  echo date('F', strtotime($this->user->now())); } ?> </i>
											</center>
										</div>
										<div class="table-responsive">
											<table class="table align-items-center table-flush">
												<thead class="thead-dark">
												<tr>
													<th class="text-info" style="text-align:center">Ranking</th>	
													<th class="text-info" style="text-align:center">Name</th>
													<?php if($this->user->getUserGroupId() == 36) { ?>
														<th class="text-info">Sales</th>
													<?php } ?>
												</tr>
												</thead>
												<tbody>
												<?php $i = 1; ?>
												<?php if (isset($topdistributor)) { ?>
													<?php foreach ($topdistributor as $td) { ?>
														<tr>
															<?php if($i == 1){ ?>
																<td class="text-white" style="text-align:center"><i class="ni ni-trophy ni-4x text-gold"></i></td>
															<?php } else if($i == 2){ ?>
																<td class="text-white" style="text-align:center"><i class="ni ni-trophy ni-3x text-silver"></i></td>
															<?php } else if($i == 3){ ?>
																<td class="text-white" style="text-align:center"><i class="ni ni-trophy ni-2x text-bronze"></i></td>
															<?php } else { ?>
																<td class="text-white" style="text-align:center"><i class="ni ni-trophy  text-white"></i></td>
															<?php } ?>
															<td class="text-white"  style="text-align:center"><?php echo $td['fullname'];?><br>(<?php echo $td['user_group'];?>)</td>
															<?php if($this->user->getUserGroupId() == 36) { ?>
																<td class="text-white"><?php echo "Php ".number_format($td['sales'], 2);?></td>
															<?php } ?>
														</tr>
														<?php $i = $i + 1; ?>	
													<?php } ?>	
												<?php } ?>
												</tbody>			
											</table>
										</div>
									</div>
									<br>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card card btn-outline-upper shadow">
									<div class="card-header bg-transparent border-0">
										<center>
										 <h3 class="text-white mb-0"><i class="ni ni-trophy text-white"></i> Top Seller <i class="ni ni-trophy text-white"></i></h3>
										 <i class="text-white" style=" font-size:12px;">(Based on the Personal Sales of a <br>Distributor/Reseller Month of  <?php if(isset($data['month_leaderboard'])) { echo date('F', strtotime("2020-".$data['month_leaderboard']."-01")); } else  {  echo date('F', strtotime($this->user->now())); } ?> )</i>
										</center>
									</div>
									<div class="table-responsive">			
										<table class="table align-items-center table-flush">
											<thead class="thead-dark">
											<tr>		
												<th class="text-info" style="text-align:center">Ranking</th>
												<th class="text-info" style="text-align:center">Name</th>
												<?php if($this->user->getUserGroupId() == 36) { ?>
													<th class="text-info" style="text-align:center">Sales</th>
												<?php } ?>	
											</tr>
											</thead>
											<tbody>
											<?php $i = 1; ?>
											<?php if (isset($topseller)) { ?>
												<?php foreach ($topseller as $ts) { ?>
													<tr>
														<?php if($i == 1){ ?>
															<td class="text-white" style="text-align:center"><i class="ni ni-trophy ni-4x text-gold"></i></td>
														<?php } else if($i == 2){ ?>
															<td class="text-white" style="text-align:center"><i class="ni ni-trophy ni-3x text-silver"></i></td>
														<?php } else if($i == 3){ ?>
															<td class="text-white" style="text-align:center"><i class="ni ni-trophy ni-2x text-bronze"></i></td>
														<?php } else { ?>
															<td class="text-white" style="text-align:center"><i class="ni ni-trophy  text-white"></i></td>
														<?php } ?>
														<td class="text-white" style="text-align:center"><?php echo $ts['fullname'];?><br>(<?php echo $ts['user_group'];?>)</td>
														<?php if($this->user->getUserGroupId() == 36) { ?>
															<td class="text-white" style="text-align:center"><?php echo "Php ".number_format($ts['sales'], 2);?></td>
														<?php } ?>
													</tr>
													<?php $i = $i + 1; ?>	
												<?php } ?>	
											<?php } ?>
											</tbody>			
										</table>
									</div>
									<br>
								</div>
							</div>
						</div>									
					</div>		
				</div>
			</div>
		</div>
		<div class="card card-margin">	  
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-6">
								<div class="card card btn-outline-upper shadow">
									<div class="dropdown">	
										<div class="card-header bg-transparent border-0">
											<center>
												<h3 class="text-white mb-0"><i class="ni ni-trophy text-white"></i> Top Restock(Area Operator) <i class="ni ni-trophy text-white"></i></h3>
												<i class="text-white" style=" font-size:12px;">(Based on the total stocks delivered to the area operator)</i>
											</center>
										</div>
										<div class="table-responsive">
											<table class="table align-items-center table-flush">
												<thead class="thead-dark">
												<tr>
													<th class="text-info" style="text-align:center">Ranking</th>
													<th class="text-info" style="text-align:center">Name</th>
													<?php if($this->user->getUserGroupId() == 36) { ?>
														<th class="text-info" style="text-align:center">Total</th>
													<?php } ?>
												</tr>
												</thead>
												<tbody>
												<?php $i = 1; ?>
												<?php if (isset($toprequestor)) { ?>
													<?php foreach ($toprequestor as $tr) { ?>
														<tr>
														<?php if($i == 1){ ?>
															<td class="text-white" style="text-align:center"><i class="ni ni-trophy ni-4x text-gold"></i></td>
														<?php } else if($i == 2){ ?>
															<td class="text-white" style="text-align:center"><i class="ni ni-trophy ni-3x text-silver"></i></td>
														<?php } else if($i == 3){ ?>
															<td class="text-white" style="text-align:center"><i class="ni ni-trophy ni-2x text-bronze"></i></td>
														<?php } else { ?>
															<td class="text-white" style="text-align:center"><i class="ni ni-trophy  text-white"></i></td>
														<?php } ?>
															<td class="text-white" style="text-align:center"><?php echo $tr['fullname'];?><br>(<?php echo str_replace("AREAOPERATOR", "", $tr['username']);?>)</td>
															<?php if($this->user->getUserGroupId() == 36) { ?>
																<td class="text-white" style="text-align:center"><?php echo $tr['total'];?></td>
															<?php } ?>
														</tr>
														<?php $i = $i + 1; ?>	
													<?php } ?>	
												<?php } ?>
												</tbody>			
											</table>
										</div>
									</div>
									<br>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card card btn-outline-upper shadow">
									<div class="card-header bg-transparent border-0">
										<center>
											<h3 class="text-white mb-0"><i class="ni ni-trophy text-white"></i> Top Area Operator <i class="ni ni-trophy text-white"></i></h3>
											<i class="text-white" style=" font-size:10px;">(Based on the total amount of order processed of the operators Month of  <?php if(isset($data['month_leaderboard'])) { echo date('F', strtotime("2020-".$data['month_leaderboard']."-01")); } else  {  echo date('F', strtotime($this->user->now())); } ?> )</i>
										</center>
									</div>
									<div class="table-responsive">			
										<table class="table align-items-center  table-flush">
											<thead class="thead-dark">
											<tr>
												<th class="text-info" style="text-align:center" >Ranking</th>
												<th class="text-info" style="text-align:center" >Name</th>	
												<?php if($this->user->getUserGroupId() == 36) { ?>
													<th class="text-info" style="text-align:center">Sales</th>
												<?php } ?>
											</tr>
											</thead>
											<tbody>
											<?php $i = 1; ?>
											<?php if (isset($topareqoperator)) { ?>
													<?php foreach ($topareqoperator as $tap) { ?>
														<tr>
															<?php if($i == 1){ ?>
																<td class="text-white" style="text-align:center"><i class="ni ni-trophy ni-4x text-gold"></i></td>
															<?php } else if($i == 2){ ?>
																<td class="text-white" style="text-align:center"><i class="ni ni-trophy ni-3x text-silver"></i></td>
															<?php } else if($i == 3){ ?>
																<td class="text-white" style="text-align:center"><i class="ni ni-trophy ni-2x text-bronze"></i></td>
															<?php } else { ?>
																<td class="text-white" style="text-align:center"><i class="ni ni-trophy  text-white"></i></td>
															<?php } ?>
																<td class="text-white" style="text-align:center" ><?php echo $tap['fullname'];?><br>(<?php echo str_replace("AREAOPERATOR", "", $tap['username']);?>)</td>
															<?php if($this->user->getUserGroupId() == 36) { ?>
																<td class="text-white" style="text-align:center"><?php echo "Php ".number_format($tap['sales'], 2);?></td>
															<?php } ?>
														</tr>
														<?php $i = $i + 1; ?>	
													<?php } ?>	
											<?php } ?>
											</tbody>			
										</table>
									</div>
									<br>
								</div>
							</div>
						</div>									
					</div>		
				</div>
			</div>
		</div>
	</form>
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
});

function processTask(task) {
	// alert('');
	$('#task').val(task); 
	$('form').attr('action', 'leaderboard'); 
	$('form').submit();
} 


//--></script>	