<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
			</div>
		</div>
	</div>
</div>
	<div class="container-fluid mt--6">		
		<div class="row">
			<div class="col-lg-12 ">
				<div class="card">
					<form action="" method="post" enctype="multipart/form-data" id="form">
						<input type="hidden" id="task" name="task" value=""/>	
						<div  class="card-header  bg-gradient-info">
							<i class="ni ni-ui-04 ni-2x text-dark"></i><h6 class="h1 text-dark d-inline-block mb-0">&nbsp;&nbsp;Upgrade Account</h6>
						</div>
						<div class="card-body btn-outline-upper">
							<div class="card card-margin bg-gradient-info">	  
								<div class="card-body ">
									<div class = "row">
										<div class="col-md-3">
											<b class="text-dark">Serial Code:</b>
										</div>
										<div class="col-md-6">
											<input class="form-control input" type="text" id="serial_code" name="serial_code" value="<?php if(isset($customer['serial_code'])) { echo $customer['serial_code']; } ?>">
										</div>
									</div>	
									<br>
									<div class="row">
									<div class="col-md-3"></div>
										<div class="col-md-6">
											<center>
												<input class="btn btn-outline-user text-white btn" type="button" id="search" value="Upgrade Account" onclick="javascript:submitTask('upgradeAccount');"/>	
											</center>
										</div>
									</div>
								</div>
							</div>	
						</div>
					</form>	
				</div>
			</div>
		</div>	
	</div>

<div id="dialog-message" title="Message" class="text-white" style="display:none; width:400px;">
	<span id="msg"></span>
</div>

<?php echo $footer; ?>
<script type="text/javascript"><!--
var selected = [];
$(document).ready(function() {
	<?php if(isset($err_msg)) { ?>
		var msg = "<?php echo $err_msg; ?>";
		swal(msg);
	<?php } ?>
});

function submitTask(task) {
	$('#task').val(task);
	$('form').attr('action', 'upgradeacc');
	$('form').submit();
}

function exportToCsv() {
	$('form').attr('action', 'areainventoryexport'); 
	$('form').submit();
}

//--></script>