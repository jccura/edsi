<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
		  <i class="ni ni-send ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Sync Order</h6>
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
						<div class="card">
							<div class="card">
								<!-- Card header -->
								<div class="card-header bg-transparent border-0">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-6">
												 <div class="input-group">
													<input type="hidden" name="task" id="task" value=""/>
													<input class="form-control" placeholder="Order Id" type="text" id="order_id" name="order_id" value=""/>
													<button class="btn btn-outline-user text-white" type="button" onclick="javascript: processTask('submit')"><i></i> Submit</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
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

function processTask(task) {
  $('#task').val('submit');
  $('form').attr('action', "syncorder"); 
  $('form').submit();
}

$(document).ready(function() {
	<?php if(isset($err_msg)) { ?>
		<?php if(!empty($err_msg)) { ?>
			$('#msg').html("<?php echo $err_msg; ?>");			      
			$('#modal-notification').modal('show');
		<?php } ?>
	<?php } ?>
});


//--></script>							