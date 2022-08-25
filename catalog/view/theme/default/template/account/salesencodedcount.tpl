<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<i class="ni ni-money-coins ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Sales Encoded Count Report </h6>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid mt--6">	
	<form action="" method="post" enctype="multipart/form-data" id="form">
		<input type="hidden" id="task" name="task" value=""> 
		<br/>
		<div class="row">
			<div class="col-md-12">
				<div class="card" style="background-color: rgba(0 , 0 , 0 , 0.6);">
					<div class="card-body">	
						<div class="table-responsive">
							<table class="table align-items-center table-flush" style="color:#fff;">
								<thead class="thead-dark text-white">
									<input type="hidden" id="task" name="task" value="" />
									<input type="hidden" id="sales_inventory_id" name="sales_inventory_id" value="" />
									<tr class="table-primary">
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
									</tr>
								</thead>
								<tbody class="list">	
									<?php if (isset($salesencodedreport)) { ?>
										<?php foreach ($salesencodedreport as $ser) { ?>
											<tr>
												<td class="left text-white"><?php echo $ser['item_name'];?></td>
												<td style="text-align:center;"><?php echo ($ser['sales_today']);?></td>
												<td style="text-align:center;"><?php echo ($ser['sales_yesterday']);?></td>
												<td style="text-align:center;"><?php echo ($ser['sales_second_day']);?></td>
												<td style="text-align:center;"><?php echo ($ser['sales_third_day']);?></td>
												<td style="text-align:center;"><?php echo ($ser['sales_week']);?></td>
												<td style="text-align:center;"><?php echo ($ser['prev_week']);?></td>
												<td style="text-align:center;"><?php echo ($ser['sales_second_week']);?></td>
												<td style="text-align:center;"><?php echo ($ser['sales_third_week']);?></td>
												<td style="text-align:center;"><?php echo ($ser['sales_month']);?></td>
												<td style="text-align:center;"><?php echo ($ser['prev_month']);?></td>
												<td style="text-align:center;"><?php echo ($ser['sales_second_month']);?></td>
												<td style="text-align:center;"><?php echo ($ser['sales_third_month']);?></td>
												<td style="text-align:center;"><?php echo ($ser['sales_year']);?></td>
												<td style="text-align:center;"><?php echo ($ser['prev_year']);?></td>
												<td style="text-align:center;"><?php echo ($ser['sales_second_year']);?></td>
												<td style="text-align:center;"><?php echo ($ser['sales_third_year']);?></td>
											</tr>
										<?php } ?>	
									<?php } ?>				
								</tbody>
							</table>
						</div>
					</div>
				</div>
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
	$('form').attr('action', 'salesencreport'); 
	$('form').submit();
	
}

function exporttocsv() {
	
	$('form').attr('action', 'salesencodeexport'); 
	$('form').submit();
	
}

 function bodyOnLoad() {
	 
 }
</script>					