<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<i class="ni ni-bag-17 ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Sales Encoded</h6>
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
									<!--<button class="btn btn-outline-upper text-white" type="button" onclick="javascript: exporttocsv();"><i class="fa fa-search"></i> Export</button>-->
								</center>				
							</div>	
							<div class="col-md-4"></div>
						</div>
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
						<input type="hidden" id="sales_encoded_id" name="sales_encoded_id" value="" />
						<tr class="table-primary">
							<th class="left text-white">Sales Encoded ID</th>
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
					<tbody class="list">	
						<?php if (isset($salesencoded)) { ?>
							<?php foreach ($salesencoded as $se) { ?>
								<tr>
									<td><?php echo $se['sales_encoded_id'];?></td>
									<td><?php echo $se['item_name'];?></td>
									<td><?php echo "Php ".number_format($se['sales_today'], 2);?></td>
									<td><?php echo "Php ".number_format($se['sales_yesterday'], 2);?></td>
									<td><?php echo "Php ".number_format($se['sales_second_day'], 2);?></td>
									<td><?php echo "Php ".number_format($se['sales_third_day'], 2);?></td>
									<td><?php echo "Php ".number_format($se['sales_week'], 2);?></td>
									<td><?php echo "Php ".number_format($se['prev_week'], 2);?></td>
									<td><?php echo "Php ".number_format($se['sales_second_week'], 2);?></td>
									<td><?php echo "Php ".number_format($se['sales_third_week'], 2);?></td>
									<td><?php echo "Php ".number_format($se['sales_month'], 2);?></td>
									<td><?php echo "Php ".number_format($se['prev_month'], 2);?></td>
									<td><?php echo "Php ".number_format($se['sales_second_month'], 2);?></td>
									<td><?php echo "Php ".number_format($se['sales_third_month'], 2);?></td>
									<td><?php echo "Php ".number_format($se['sales_year'], 2);?></td>
									<td><?php echo "Php ".number_format($se['prev_year'], 2);?></td>
									<td><?php echo "Php ".number_format($se['sales_second_year'], 2);?></td>
									<td><?php echo "Php ".number_format($se['sales_third_year'], 2);?></td>
									<td><?php echo $se['date_added'];?></td>
								</tr>
							<?php } ?>	
						<?php } ?>				
					</tbody>
				</table>
			</div>
			<div class="pagination"><?php echo $pagination; ?></div>
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
	$('form').attr('action', 'salesencoded'); 
	$('form').submit();
	
}

function exporttocsv() {
	
	$('form').attr('action', 'salesencodedexport'); 
	$('form').submit();
	
}

 function bodyOnLoad() {
	 
 }
</script>					