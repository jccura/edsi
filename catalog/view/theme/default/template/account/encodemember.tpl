<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<i class="fa fa-credit-card text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Encode Member</h6>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid row">
	<div class="col-md-12">
		<form action="" method="post" enctype="multipart/form-data" id="form">
			<input type="hidden" id="task" name="task">
			</br>
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-credit-card"></i> Manual Encoding of Users</h3>
			</div>
		
			<div class="modal bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			  <div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="alert alert-success remove-margin add-success" role="alert">					   
					</div>					
				</div>
			  </div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<center>
						<hr>
						<input class="btn bg-turqouise btn text-white" type="button" id="search" value="Submit" onclick="javascript: submitMembers('manualCreate');">	
						<hr>
					</center>
				</div>
			</div>			
			
			<div class="row">
				<div class="col-md-12">
					<div align="center">
						<div style="">	
							<table class="table table-bordered table-hover" id="table1" name="table1">
								<thead>
									<tr class="table-primary">
										<td width="3%" class="left numeric green-bg" >Row</td>
										<td width="8%" class="left numeric green-bg" >Username</td>
										<td width="8%" class="left numeric green-bg" >Password</td>
										<td width="10%" class="left numeric green-bg" >Firstname</td>
										<td width="10%" class="left numeric green-bg" >Lastname</td>
										<td width="10%" class="left numeric green-bg" >Email</td>
										<td width="10%" class="left numeric green-bg" >Contact</td>
										<td width="8%" class="left numeric green-bg" >Sponsor</td>					
										<td width="14%" class="left numeric green-bg" >User Type</td>					
									</tr>
								</thead>
								<tbody>
									<?php for($i=1; $i<=10; $i++) { ?>
									<tr>
										<td class="border-right"><?php echo $i;?></td>
										<td class="border-right"><input class="form-control input" type="text" id="username<?php echo $i;?>" name="username<?php echo $i;?>" value="<?php if(isset($members['username'.$i])) { echo $members['username'.$i]; } ?>"></td>
										<td class="border-right"><input class="form-control input" type="text" id="password<?php echo $i;?>" name="password<?php echo $i;?>" value="<?php if(isset($members['password'.$i])) { echo $members['password'.$i]; } ?>"></td>
										<td class="border-right"><input class="form-control input" type="text" id="firstname<?php echo $i;?>" name="firstname<?php echo $i;?>" value="<?php if(isset($members['firstname'.$i])) { echo $members['firstname'.$i]; } ?>"></td>
										<td class="border-right"><input class="form-control input" type="text" id="lastname<?php echo $i;?>" name="lastname<?php echo $i;?>" value="<?php if(isset($members['lastname'.$i])) { echo $members['lastname'.$i]; } ?>"></td>
										<td class="border-right"><input class="form-control input" type="text" id="email<?php echo $i;?>" name="email<?php echo $i;?>" value="<?php if(isset($members['email'.$i])) { echo $members['email'.$i]; } ?>"></td>
										<td class="border-right"><input class="form-control input" type="text" id="contact<?php echo $i;?>" name="contact<?php echo $i;?>" value="<?php if(isset($members['contact'.$i])) { echo $members['contact'.$i]; } ?>"></td>
										<td class="border-right"><input class="form-control input" type="text" id="sponsor<?php echo $i;?>" name="sponsor<?php echo $i;?>" value="<?php if(isset($members['sponsor'.$i])) { echo $members['sponsor'.$i]; } ?>"></td>
										<td class="border-right">
											<select class="form-control input" id="user_group_id<?php echo $i;?>" name="user_group_id<?php echo $i;?>" >
												<option value="0">Select a User Types</option>
												<option value="56">Distributor</option>
												<!--<option value="49">City Admin</option>-->
												 <option value="46">Reseller</option>
											<select>
										</td>										
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>		
		</form>
	</div>
</div>
<!--==============================================================================================================================-->
<div id="dialog-message" title="Message" style="display:none; width: 400px;">
  <span id="msg"></span>
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
						<button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php echo $footer; ?>
<script type="text/javascript"><!--
// $(document).ready(function() {
	// <?php if(isset($err_msg)) { ?>
		// <?php if(!empty($err_msg)) { ?>
			// $('#msg').html("<?php echo $err_msg; ?>");			      
			// $('#modal-notification').modal('show');
		// <?php } ?>
	// <?php } ?>	
// });

$(document).ready(function() {  
	<?php if(isset($err_msg)) { ?>
		<?php if(!empty($err_msg)) { ?>
			// $('#msg').html("<?php echo $err_msg; ?>");
			// $(function() {
				// $("#dialog-message").dialog({
					// modal: true,
					// width: 600,
					// buttons: {
						// Ok: function() {
							// $(this).dialog("close");
						// }
					// }
				// });
			// });
			var msg = "<?php echo $err_msg; ?>";
			swal("Good Job!", msg, "success");
		<?php } ?>
	<?php } ?>
});


function submitMembers(task) {
  $('#task').val(task);
  $('form').attr('action', "encodemember"); 
  $('form').submit();

}
//--></script>