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
			<input type="hidden" id="task" name="task" value="">
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
												<option value="0" selected>Select Payment Option</option>
											<?php if(isset($payments)) { ?>
												<?php foreach ($payments as $pm) { ?>
													<?php if($rate_details['status_id'] == $pm['status_id']) { ?>
														<option value="<?php echo $pm['status_id'];?>" selected><?php echo $pm['description'];?></option>
													<?php } else { ?>
														<option value="<?php echo $pm['status_id'];?>"><?php echo $pm['description'];?></option>
													<?php } ?>
												<?php } ?>
											<?php } ?>
											</select>
										</div>					
									</div>
									<div class="col-md-4">
										<div class="form-group">	
											<label class="text-white">Delivery Points:</label>
											<input class="form-control input" type="number" name="quantity" id="quantity" min="0" value="<?php if(isset($rate_details['quantity'])) { echo $rate_details['quantity']; } ?>" />
										</div>
									</div>	
									<div class="col-md-4">
										<div class="form-group">	
											<label class="text-white">Rate:</label>
											<input class="form-control input" type="number" name="rate" id="rate" value="<?php if(isset($rate_details['rate'])) { echo $rate_details['rate']; } ?>" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<center>
											<input type="hidden" id="rate_id" name="rate_id" value="<?php if(isset($rate_details['rate_id'])) { echo $rate_details['rate_id']; } ?>">
											<input class="btn btn-outline-user text-white" type="submit" id="search" value="Save" onclick="javascript:processTask('submitedit');">													
										</center>
									</div>
								</div>
							</div>
						</div> 
					</div>
				</div>
			</div>
		</form>		
	</div>
</div>
<!--<div id="dialog-message" title="Message" style="display:none; width: 600px;">
  <span id="msg"></span>
</div>-->
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
	$('form').attr('action', 'shippingratemaint'); 
	$('form').submit();
}
//--></script>							