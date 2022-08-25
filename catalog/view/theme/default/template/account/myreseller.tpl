<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
			<h6 class="h1 text-white d-inline-block mb-0">&nbsp; My Resellers</h6>
		</div>
	  </div>
	</div>
  </div>
</div>
	
<div class="container-fluid mt--6">
	<div class="card card-margin">	  
		<div class="card-body">
			<div class="row">
				<div class="col-md-12">	
					<form action="" method="post" enctype="multipart/form-data" id="form">
						<input type="hidden" id="downline_user_id" name="downline_user_id" value="">
						<input type="hidden" name="task" id="task" value="" />
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">	
									<input class="form-control input" type="text" id="search_user" placeholder="Search Username" name="search_user" value="">
								</div>				
							</div>
							<div class="col-md-2">
								<div class="form-group">	
									<button class="btn bg-avgreen text-white" type="button" onclick="javascript: processTask('search')"><i class="fa fa-search"></i> Search</button>
								</div>				
							</div>				
						</div>
						
						<div class="row">
							<div class="col">
								<div class="card">
									<div class="card-header bg-transparent border-0">
									  <h3 class="mb-0">Resellers List</h3>
									</div>
									<div class="table-responsive">			
										<table class="table align-items-center table-flush">
											<thead>
											<tr>
												<!--<th class="text-info">Sponsor<br>Username</th>-->								
												<th class="text-info">Username</th>
												<th class="text-info">Name</th>
												<th class="text-info">Level</th>
												<th class="text-info">Active?</th>
												<th class="text-info">Date Encoded</th>
												<th class="text-info">Action</th>
											</tr>
											</thead>
											<tbody>
											<?php if (isset($resellers_list)) { ?>
												<?php foreach ($resellers_list as $rl) { ?>
													<tr>
														<td><?php echo $rl['dl_username'];?></td>
														<td><?php echo $rl['dl_desc'];?></td>
														<td><?php echo $rl['level'];?></td>
														<td>
															<?php if($rl['act_flag'] == "Active") { ?>
																<center><h6 style='color:green; font-weight: bold;'><?php echo $rl['act_flag'];?></h6>
															<?php } else { ?>
																<center><h6 style='color:red; font-weight: bold;'><?php echo $rl['act_flag'];?></h6>
															<?php } ?>
														</td>
														<td><?php echo $rl['date_added'];?></td>
														<td>
															<button type="button" class="btn bg-avgreen text-white btn-sm" onclick="addToCartForDownline(<?php echo $rl['dl_user_id'];?>)"><b>Create Order</b></button>
														</td>														
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
							</div>
						</div>					
					</form>					
				</div>		
			</div>
		</div>
	</div>
</div>	 



<?php echo $footer; ?>
<script type="text/javascript">

function addToCartForDownline(downline_user_id) {
	$('#downline_user_id').val(downline_user_id);
	$('#task').val('addToCartForDownline');
	$('form').attr('action', "cart"); 
	$('form').submit();
}
  
function processTask(task) {
  $('#task').val('search');
  $('form').attr('action', "myreseller"); 
  $('form').submit();
}
</script>