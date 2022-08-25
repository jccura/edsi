<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
			<i class="ni ni-compass-04 ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp; Sync Promo</h6>
		</div>
	  </div>
	</div>
  </div>
</div>
<div class="container mt--6">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">	
					<form action="" method="post" enctype="multipart/form-data" id="form">									
						<div class="row">
							<div class="col-md-12">
								<center>
									<input type="hidden" id="task" name="task" value="">
								</center>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
									  <h5 class="panel-title"><i class="ni ni-ruler-pencil"></i> Input</h5>
									</div>
									<div class="panel-body">

										<div class="row">
											<div class="col-md-12">
												<div class="form-group">  
													<button class="btn btn-success pull-right" type="button" onclick="javascript:sync();" ><i class="fa fa-refresh"></i> &nbsp;Sync</button>
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
		</div>
	</div>
</div>
<!--<div id="dialog-message" title="Message" style="display:none; width: 400px;">
  <span id="msg"></span>
</div>-->
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
			  <button type="button" class="btn btn-white ml-auto" data-dismiss="modal">Close</button>
			</div>
		  </div>
		</div>
	  </div>
	</div>
  </div>	

<?php echo $footer; ?>

<script type="text/javascript"><!--
$(document).ready(function() {
	
	// <?php if(isset($err_msg)) { ?>
		// var msg = "<?php echo $err_msg; ?>";
		// $('#msg').html(msg);		
		// $(function() {
			// $( "#dialog-message" ).dialog({
			  // modal: true,
			  // width: 600,
			  // buttons: {
				// Ok: function() {
					// $( this ).dialog( "close" );
				// }			
			  // }
			// });
		// });			
	// <?php } ?>
	
	<?php if(isset($err_msg)) { ?>
		<?php if(!empty($err_msg)) { ?>
			$('#msg').html("<?php echo $err_msg; ?>");			      
			$('#modal-notification').modal('show');
		<?php } ?>
	<?php } ?>
});


function sync(){
	// $('#msg').html("Sync Promo(s) to Manong Express?");
	// $(function() {
		// $( "#dialog-message" ).dialog({
		  // modal: true,
		  // width: 600,
		  // buttons: {
			// Cancel: function() {
				// $( this ).dialog( "close" );
			// },
			// Proceed: function() {
				// $('#task').val('syncpromos');
				// $('#form').attr('action', 'promosapi'); 
				// $('#form').submit();
				// $( this ).dialog( "close" );
				// sendOrderId.dialog( "close" );
			// }			
		  // }
		// });
	// });
	$('#msg_proceed').html("Sync Promo(s) to Manong Express?");
	$('#div_buttons').html("<button type=\"button\" onclick=\"performTask('syncpromos');\" class=\"btn btn-danger\">Yes, Sync it.</button>");		
	$('#modal-proceed').modal('show');	
}

function performTask(task) {
	$('#task').val(task);
	$('form').attr('action', 'promosapi'); 
	$('form').submit();
}

//--></script>							