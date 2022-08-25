<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
		  <i class="ni ni-bag-17 ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Item Maintenance</h6>
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
								<div class="form-group">	
									<label>Description:</label>
									<input class="form-control input" type="text" name="description" id="description" value="<?php if(isset($expenses_type_details['description'])) { echo $expenses_type_details['description']; } ?>" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">	
									<label>Expenses Group:</label>
									<input class="form-control input" type="text" id="expense_group" name="expense_group" value="<?php if(isset($expenses_type_details['grouping2'])) { echo $expenses_type_details['grouping2']; } ?>">
								</div>					
							</div>
							<div class="col-md-4">
								<div class="form-group">	
									<label>Status:</label>
									<select class="form-control input" id="active" name="active">
										<?php if($expenses_type_details['active'] == 1) { ?>
											<option value="1" selected>Enabled</option>
											<option value="0" >Disabled</option>
										<?php } else { ?>
											<option value="1" >Enabled</option>
											<option value="0" selected>Disabled</option>
										<?php } ?>
									</select>
								</div>					
							</div>
						</div>
						<div class="row">
							<div class="col-md-3"></div>
							<div class="col-md-6">
								<center>
									<input type="hidden" id="expenses_type_id" name="expenses_type_id" value="<?php if(isset($expenses_type_details['expenses_type_id'])) { echo $expenses_type_details['expenses_type_id']; } ?>">
									<input class="btn bg-avgreen text-white btn-block" type="submit" id="search" value="Save" onclick="javascript:processTask('submitEdit');">													
								</center>
							</div>
							<div class="col-md-3"></div>
						</div>
					</div>
				</div>
			</div>
		</form>		
	</div>
</div>
<div id="dialog-message" title="Message" style="display:none; width: 600px;">
  <span id="msg"></span>
</div>
<?php echo $footer; ?>
<script type="text/javascript"><!--
$(document).ready(function() {
	<?php if(isset($err_msg)) { ?>
		<?php if(!empty($err_msg)) { ?>
			$('#msg').html("<?php echo $err_msg; ?>");
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
	<?php } ?>
});

function processTask(task) {
	$('#task').val(task); 
	$('form').attr('action', 'expensesmaint'); 
	$('form').submit();
}
//--></script>							