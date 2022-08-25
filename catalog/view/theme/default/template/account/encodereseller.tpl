<?php echo $header; ?>
	<div class="header bg-gradient-primary pb-6">
	  <div class="container-fluid">
		<div class="header-body">
		  <div class="row align-items-center py-4">
			<div class="col-lg-6 col-7">
			  <i class="ni ni-ruler-pencil ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Encode Operator</h6>
			</div>
		  </div>
		</div>
	  </div>
	</div>
	<div class="container mt--6">	
		<form action="" method="post" enctype="multipart/form-data" id="form">
			<input type="hidden" id="task" name="task">
			<!--<div class="modal bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			  <div class="modal-dialog modal-lg">
			    <div class="modal-content">
			      	<div class="alert alert-success remove-margin add-success" role="alert">					   
					</div>					
			    </div>
			  </div>
			</div>-->			
			<!--<div id="dialog-message" title="Confirmation Message" style="display:none; width: 400px;">
				<span id="msg"></span>
			</div>-->

			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">
							<center>
								<input class="btn btn-primary btn" type="button" id="search" value="Submit" onclick="javascript: submitMembers();">	
							</center>
						</div>
					</div>
				</div>
			</div>			
			
			 <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
					<div class="table-responsive pt-3">
						<div class="row">
							<div class="col-md-12">
							
								<!--<div align="center">
									<div style="width: 100%; ">-->
										<table class="table table-striped">
											<thead>
												<tr class="table-primary">
													<th width="3%" class="text-dark" >Row</th>
													<th width="8%" class="text-dark" >Username</th>
													<th width="10%" class="text-dark" >Firstname</th>
													<th width="10%" class="text-dark" >Middlename</th>
													<th width="10%" class="text-dark" >Lastname</th>
													<th width="10%" class="text-dark" >Contact#</th>
													<th width="10%" class="text-dark" >Email</th>
													<th width="8%" class="text-dark" >Sponsor</th>					
												</tr>
											</thead>
											<tbody>
												<?php for($i=1; $i<=20; $i++) { ?>
												<tr>
													<th class="border-right"><?php echo $i;?></th>
													<th class="border-right"><input class="form-control input" type="text" id="username<?php echo $i;?>" name="username<?php echo $i;?>" value="<?php if(isset($members['username'.$i])) { echo $members['username'.$i]; } ?>"></th>
													<th class="border-right"><input class="form-control input" type="text" id="firstname<?php echo $i;?>" name="firstname<?php echo $i;?>" value="<?php if(isset($members['firstname'.$i])) { echo $members['firstname'.$i]; } ?>"></th>
													<th class="border-right"><input class="form-control input" type="text" id="middlename<?php echo $i;?>" name="middlename<?php echo $i;?>" value="<?php if(isset($members['middlename'.$i])) { echo $members['middlename'.$i]; } ?>"></th>
													<th class="border-right"><input class="form-control input" type="text" id="lastname<?php echo $i;?>" name="lastname<?php echo $i;?>" value="<?php if(isset($members['lastname'.$i])) { echo $members['lastname'.$i]; } ?>"></th>
													<th class="border-right"><input class="form-control input" type="text" id="mobile<?php echo $i;?>" name="mobile<?php echo $i;?>" value="<?php if(isset($members['mobile'.$i])) { echo $members['mobile'.$i]; } ?>"></th>
													<th class="border-right"><input class="form-control input" type="text" id="email<?php echo $i;?>" name="email<?php echo $i;?>" value="<?php if(isset($members['email'.$i])) { echo $members['email'.$i]; } ?>"></th>
													<th class="border-right"><input class="form-control input" type="text" id="sponsor<?php echo $i;?>" name="sponsor<?php echo $i;?>" value="<?php if(isset($members['sponsor'.$i])) { echo $members['sponsor'.$i]; } ?>"></th>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									<!--</div>
								</div>-->
							
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
		</form>
		
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
	</div>

<?php echo $footer; ?>

<script type="text/javascript">
// $(document).ready(function() {
	
	// $('#birthdate').datepicker({dateFormat: 'yy-mm-dd'});
	// <?php if(isset($err_msg)) { ?>
		// // msg = "<?php echo $err_msg; ?>";
		// // $('#msg').html(msg);		
		// // $(function() {
			// // $( "#dialog-message" ).dialog({
			  // // modal: true,
			  // // width: 600,
			  // // buttons: {
				// // Ok: function() {
					// // $( this ).dialog( "close" );
				// // }			
			  // // }
			// // });
		// // });
			// $('#msg').html("<?php echo $err_msg; ?>");			      
			// $('#modal-notification').modal('show');		
	// <?php } ?>	

// });

$(document).ready(function() {
	<?php if(isset($err_msg)) { ?>
		<?php if(!empty($err_msg)) { ?>
			$('#msg').html("<?php echo $err_msg; ?>");			      
			$('#modal-notification').modal('show');
		<?php } ?>
	<?php } ?>	
});

$('#birthdate').datepicker({dateFormat: 'yy-mm-dd'});
	
function submitMembers(){
	$('#task').val("encode");
	$('form').attr('action', 'encodereseller'); 
	$('form').submit();	
}
</script>


