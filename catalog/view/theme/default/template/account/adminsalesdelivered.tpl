<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
				  <i class="ni ni-building ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;My Admin Sales Delivered</h6>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container mt--6">
    <div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<form action="" method="post" enctype="multipart/form-data" id="form">
					<input type="hidden" id="task" name="task" value="">      
					<input type="hidden" id="user_id" name="user_id" value="">		
					<input type="hidden" id="user_group_id" name="user_group_id" value="">			
					<div class="card">
						<div class="card">
							<!-- Card header -->
							<div class="card-header bg-transparent border-0">
								<h3 class="mb-0">Actions</h3><br>
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-6">
											<div class="input-group">
												<input class="form-control" placeholder="Username" type="text" id="search_username" name="search_username" value=""/>
												<button class="btn btn-outline-user text-white" type="button" onclick="javascript: processTask('search')"><i class="fa fa-search"></i> Search</button>
											</div>
										</div>
										<!--<div class="col-md-6 text-right">
											<button class="btn bg-avgreen text-white " type="button" data-toggle="modal" data-target="#admin-modal" onclick="javascript: addNewAdminShow()"><i class="fa fa-plus"></i> Add Admin</button>
										</div>-->
									</div>
								</div>
							</div>
						</div>
						<!-- Card header -->
						<div class="card-header border-0">
							<div class="row">
								<div class="col-6">
									<h3 class="mb-0">List of Admin's Sales Delivered</h3>
								</div>
							</div>
						</div>
						<!-- Light table -->
						<div class="table-responsive">
							<table class="table align-items-center table-flush">
								<thead class="thead-dark">
									<tr>
										<th class="left text-white">Sales Delivered ID</th>
										<th class="left text-white">Username</th>
										<th class="left text-white">Item Name</th>
										<th class="left text-white">Today</th>
										<th class="left text-white">Yesterday</th>
										<th class="left text-white">2nd Day</th>
										<th class="left text-white">3rd Day</th>
										<th class="left text-white">This Week</th>
										<th class="left text-white">Previous Week</th>
										<th class="left text-white">2nd Week</th>
										<th class="left text-white">3rd Week</th>											  
										<th class="left text-white">This Month</th>											  
										<th class="left text-white">Previous Month</th>											  
										<th class="left text-white">2nd Month</th>											  
										<th class="left text-white">3rd Month</th>											  
										<th class="left text-white">This Year</th>											  
										<th class="left text-white">Previous Year</th>											  
										<th class="left text-white">2nd Year</th>											  
										<th class="left text-white">3rd Year</th>
										<th class="left text-white">Date</th>
									</tr>
								</thead>
								<tbody>
									<?php if (isset($admin_sales)) { ?>								
									   <?php foreach ($admin_sales as $admin_sale) { ?>											
									   <tr>
											<td><?php echo $admin_sale['sales_delivered_id'];?></td>
											<td><?php echo $admin_sale['username'];?></td>
											<td><?php echo $admin_sale['item_name'];?></td>
											<td><?php echo "Php ".number_format($admin_sale['sales_today'], 2);?></td>
											<td><?php echo "Php ".number_format($admin_sale['sales_yesterday'], 2);?></td>
											<td><?php echo "Php ".number_format($admin_sale['sales_second_day'], 2);?></td>
											<td><?php echo "Php ".number_format($admin_sale['sales_third_day'], 2);?></td>
											<td><?php echo "Php ".number_format($admin_sale['sales_week'], 2);?></td>
											<td><?php echo "Php ".number_format($admin_sale['prev_week'], 2);?></td>
											<td><?php echo "Php ".number_format($admin_sale['sales_second_week'], 2);?></td>
											<td><?php echo "Php ".number_format($admin_sale['sales_third_week'], 2);?></td>
											<td><?php echo "Php ".number_format($admin_sale['sales_month'], 2);?></td>
											<td><?php echo "Php ".number_format($admin_sale['prev_month'], 2);?></td>
											<td><?php echo "Php ".number_format($admin_sale['sales_second_month'], 2);?></td>
											<td><?php echo "Php ".number_format($admin_sale['sales_third_month'], 2);?></td>
											<td><?php echo "Php ".number_format($admin_sale['sales_year'], 2);?></td>
											<td><?php echo "Php ".number_format($admin_sale['prev_year'], 2);?></td>
											<td><?php echo "Php ".number_format($admin_sale['sales_second_year'], 2);?></td>
											<td><?php echo "Php ".number_format($admin_sale['sales_third_year'], 2);?></td>
											<td><?php echo $admin_sale['date_added'];?></td>
									   </tr>
									   <?php } ?>								
									<?php } ?>							  
								</tbody>
							</table>
						</div>
						<div class="pagination"><?php echo $pagination; ?></div>
					</div>
					<!--end of card-->
				</form>
			</div>
		</div>
	</div>
</div>
<div id="dialog-message" title="Message" style="display:none; width: 400px;">
  <span id="msg"></span>
</div>

<?php echo $footer; ?>
<script type="text/javascript"><!--
$(document).ready(function() {  
	<?php if(isset($err_msg)) { ?>
		<?php if(!empty($err_msg)) { ?>
			var msg = "<?php echo $err_msg; ?>";
			swal("Good Job!", msg, "success");
		<?php } ?>
	<?php } ?>
});

function processTask(task) {
  $('#task').val('search');
  $('form').attr('action', "adminsalesdelivered"); 
  $('form').submit();
}


//--></script>