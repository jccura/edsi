<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
		  <i class="ni ni-money-coins ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Expenses Type Maintenance</h6>
		</div>
	  </div>
	</div>
  </div>
</div>
<div class="container-fluid mt--6">	
	<form action="" method="post" enctype="multipart/form-data" id="form">
	<input type="hidden" id="task" name="task" value="">
			<div class="card bg-default shadow">
				<!-- Card header -->
				<div class="card-header bg-transparent border-0">
				  <h3 class="mb-0 text-white">Actions</h3><br>
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-4">
								<input class="form-control input" placeholder="Description" type="text" id="description" name="description">
								<br>
							</div>
							<div class="col-md-4">
								<input class="form-control input" placeholder="Expenses Group" type="text" id="expense_group" name="expense_group">
								<br><br>
							</div>
							<div class="col-md-4">
								<select class="form-control input" id="active" name="active">
									<option value="" selected>Select Status</option>
									<option value="1" selected>Enabled</option>
									<option value="0" selected>Disabled</option>
								</select>
							</div>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-2"></div>
									<div class="col-md-2">
										<input class="btn bg-gradient-primary text-white btn-block" type="button" id="addTypeExpenses" value="Add" onclick="submitTask('addTypeExpenses')">
									</div>
									<div class="col-md-2">
										<input class="btn bg-gradient-primary text-white btn-block" type="button" id="clearType" value="Delete" onclick="submitTask('clearType')">
									</div>
									<div class="col-md-2">
										<input class="btn bg-gradient-primary text-white btn-block" type="button" id="search" value="Search" onclick="submitTask('');"/>
									</div>
									<div class="col-md-2"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<div class="row">	
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<!--<div class="card card-margin">
							<div class="card-body">-->
								<div class="table-responsive" style="width: 100%; overflow: auto;">	
									<table class="table table-striped table-bordered table-hover">
										<thead>
											<tr class="table-primary">
												<th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
												<th class="text-dark">Action</th>	
												<th class="text-dark">Expense Type Id</th>	
												<th class="text-dark">Description</th>
												<th class="text-dark">Group</th>
												<th class="text-dark">Active Status</th>
											</tr>
										</thead>
										<tbody>
											<?php if (isset($expense_type)) { ?>
												<?php foreach ($expense_type as $et) { ?>			
												<tr>
													<td style="text-align: center;">
														<input type="checkbox" name="selected[]" value="<?php echo $et['expenses_type_id'];?>"/>
													</td>
													 <td><a class="btn btn-outline-user text-white btn-sm" href="index.php?route=account/expensesmaint&task=edit&expenses_type_id=<?php echo $et['expenses_type_id'];?>" align="center">Edit</a></td>
													<td>&nbsp;<?php echo $et['expenses_type_id'];?></td>
													<td><?php echo $et['description'];?></td>
													<td><?php echo $et['grouping2'];?></td>
													<td><?php echo $et['active'];?></td>
												</tr>
												<?php } ?>
											<?php } ?>
										</tbody>
									</table>
								</div>
								<div class="pagination"><?php echo $pagination; ?></div>
							<!--</div>
						</div>-->
					
					</div>
				</div>
			</div>
		</div>
	</form>
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
	<div class="col-md-4">
	  <div class="modal fade" id="modal-proceed" tabindex="-1" role="dialog" aria-labelledby="modal-proceed" aria-hidden="true">
		<div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
		  <div class="modal-content bg-gradient-info">
			<div class="modal-header">
			  <h6 class="modal-title" id="modal-title-notification">You are about to proceed.</h6>
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
			  </button>
			</div>
			<div class="modal-body">
			  <div class="py-3 text-center">
				<i class="ni ni-bell-55 ni-3x"></i>
				<h4 class="heading mt-4"><span id="msg"></span></h4>
				<p><span id="msg_proceed"></span></p>
			  </div>
			</div>
			<div class="modal-footer">
			  <div id="div_buttons"></div>	
			  <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
			</div>
		  </div>
		</div>
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
			$('#msg').html("<?php echo $err_msg; ?>");			      
			$('#modal-notification').modal('show');
		<?php } ?>
	<?php } ?>
});

function submitTask(task) {
	$('#task').val(task);
	$('form').attr('action', 'expensesmaint');
	$('form').submit();
}

function disableType(expenses_type_id) {

  $("#expenses_type_id").val(expenses_type_id);
  $('#task').val('disableType');
  $('form').attr('action', "expensesmaint"); 
  $('form').submit();

}

function enableType(expenses_type_id) {

  $("#expenses_type_id").val(expenses_type_id);
  $('#task').val('enableType');
  $('form').attr('action', "expensesmaint"); 
  $('form').submit();

}


//--></script>