<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<i class="ni ni-bag-17 ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Shipping Rate Maintenance</h6>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid mt--6">	
	<form action="" method="post" enctype="multipart/form-data" id="form">
		<div class="panel-body">
			<div class="row">	
				<div class="col-md-12">
					<div class="card bg-default shadow">
						<div class="card-body">	
							<div class="row">						
								<div class="col-md-4">
									<div class="form-group">	
										<label class="text-white">Payment Option:</label>
										<select class="form-control input" id="status_id" name="status_id">
											<option value="0" selected>Select Payment</option>
										<?php if(isset($payments)) { ?>
											<?php foreach ($payments as $pm) { ?>
												<option value="<?php echo $pm['status_id'];?>"><?php echo $pm['description'];?></option>
											<?php } ?>
										<?php } ?>
										</select>
									</div>					
								</div>
								<div class="col-md-4">
									<div class="form-group">	
										<label class="text-white">Delivery Points:</label>
										<input class="form-control input" type="number" name="quantity" id="quantity" value="" />
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">	
										<label class="text-white">Rate:</label>
										<input class="form-control input" type="number" id="rate" name="rate">
									</div>					
								</div>
							</div>
							<br>
							
							<div class="row">
								<div class="col-md-12">
									<center>
										<div class="template-demo">
											<input type="hidden" id="task" name="task" value="">
											<input class="btn btn-outline-user text-white btn" type="button" id="search" value="Search" onclick="javascript: performTask('search');">						
											<input class="btn btn-outline-user text-white btn" type="button" id="add" value="Add" onclick="javascript: performTask('search');">						
											<input class="btn btn-outline-user text-white btn" type="button" id="delete" value="Delete" onclick="javascript: performTask('delete');">
											<input class="btn btn-outline-user text-white btn" type="button" id="add" value="Export" onclick="javascript: exportToCsv();">	
										</div>
									</center>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<br/>
		
		<div class="row">
			<div class="col">
					<div class="card">
						<div class="card-header border-0">
						  <h3 class="mb-0">List of Items</h3>
						</div>						
						<div class="col-md-12">
							<div class="row">
								<div class="table-responsive">
									<table class="table align-items-center table-flush">
										<thead class="thead-light">
											<tr class="table-primary">
											  <th width="1" style="text-align: center;">
												<input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
											  </th>
											  <th scope="col">Action</th>
											  <th class="left text-info">Rate Id</th>
											  <th scope="col">Payment Option</th>
											  <th scope="col">Delivery Points</th>
											  <th scope="col">Rate</th>
											</tr>
										</thead>
										  <tbody class="list">
											<?php if (isset($rates)) { ?>
											<?php foreach ($rates as $rs) { ?>			
											<tr>
											  <td style="text-align: center;">
												<input type="checkbox" name="selected[]" value="<?php echo $rs['rate_id'];?>"/>
											  </td>
											  <td><a class="btn btn-outline-user text-white btn-sm" href="index.php?route=account/shippingratemaint&task=edit&rate_id=<?php echo $rs['rate_id'];?>" align="center">Edit</a></td>
											  <td><?php echo $rs['rate_id'];?></td>
											  <td><?php echo $rs['payment_option'];?></td>
											  <td><?php echo $rs['quantity'];?></td>
											  <td><?php echo $rs['rate'];?></td>
											</tr>
											<?php } ?>
											<?php } ?>
										  </tbody>
										</table>
								</div>
							</div>
						<div class="pagination"><?php echo $pagination; ?></div>							
					</div>
				</div>	
			</div>	
		</div>	
	</form>		
</div>
<!-- Modals -->
<div class="row">
	<div class="col-md-4">
		<div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
			<div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
				<div class="modal-content bg-gradient-danger">
					<div class="modal-header">
						<h6 class="modal-title" id="modal-title-notification">Message</h6>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="py-3 text-center">
							<i class="ni ni-bell-55 ni-3x"></i>
							<h4 class="heading mt-4"><span id="msg"></span></h4>
							<p><span id="msg"></span></p>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-warning text-white ml-auto" data-dismiss="modal">Ok</button>
					</div>
				</div>
			</div>
		</div>
	</div>
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

function performTask(task) {
	$('#task').val(task); 
	$('form').attr('action', 'shippingratemaint'); 
	$('form').submit();
} 

function exportToCsv() {
	$('form').attr('action', 'shiprateexport'); 
	$('form').submit();
}

//--></script>							