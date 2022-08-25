<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
		  <i class="ni ni-single-02 ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp;&nbsp;Users Maintenance</h6>
		</div>
	  </div>
	</div>
  </div>
</div>
	<div class="container mt--6">		
		<form action="" method="post" enctype="multipart/form-data" id="form">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-md-2"></div>
						<div class="col-md-8">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">	
										<label>First Name:</label>
										<input class="form-control input" type="text" id="firstname" name="firstname">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">	
										<label>Middle Name:</label>
										<input class="form-control input" type="text" id="middlename" name="middlename">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">	
										<label>Last Name:</label>
										<input class="form-control input" type="text" id="lastname" name="lastname">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">	
										<label>Username:</label>
										<input class="form-control input" type="text" id="username" name="username">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">	
										<label>User Groups:</label>
										<select required class="form-control input" id="user_group_id" name="user_group_id">
											<option value="" selected>SELECT GROUP</option>
											<?php if($this->user->getUserGroupId() == 12) {?>
											<?php foreach ($user_groups as $user_group) { ?>
											<option value="<?php echo $user_group['user_group_id'];?>"><?php echo $user_group['name'];?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">	
										<label>Date of Birth:</label>
										<input required class="form-control input" type="date" name="birthdate" size="12" id="birthdate" value="" />
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">	
										<label>Email:</label>
										<input required class="form-control input" type="text" id="email" name="email">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">	
										<label>Phone:</label>
										<input required class="form-control input" type="text" id="phone" name="phone">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<center>
										<input type="hidden" id="task" name="task" value="submitedit">
										<input class="btn btn-outline-user text-white btn" type="button" id="search" value="Search" onclick="javascript:processTask('search');">						
										<?php if($this->user->getUserGroupId() == 12 or $this->user->getUserGroupId() == 30 or $this->user->getUserGroupId() == 1 or $this->user->getUserGroupId() == 56) {?>
										<input class="btn btn-outline-user text-white btn" type="button" id="add" value="Add" onclick="javascript:processTask('add');">						
										<input class="btn btn-outline-user text-white btn" type="button" id="delete" value="Delete" onclick="javascript:processTask('delete');">						
										<input class="btn btn-outline-user text-white btn" type="button" id="reset" value="Reset" onclick="javascript:processTask('reset');">
										<?php } ?>					
									</center>
								</div>
							</div>
						</div>
						<div class="col-md-2"></div>
					</div>				
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">		
							<div class="table-responsive">
								<table class="table table-striped table-bordered">
								  <thead>
									<tr class="table-primary">
									 <th style="text-align: center;">
										<input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
									</th>
									  <th class="left">&nbsp;</th>
									  <th class="left">Username</th>
									  <th class="left">Usergroup</th>
									  <th class="left">Name</th>
									  <th class="left">Birthdate</th>
									  <th class="left">Email</th>
									  <th class="left">Phone</th>
									  <th class="left">Status</th>
									  <th class="left">Sponsor</th>
									</tr>
								  </thead>
								  <tbody>
									<?php if (isset($users)) { ?>
									<?php foreach ($users as $user) { ?>			
									<tr>
									  <td style="text-align: center;">
										<input type="checkbox" name="selected[]" value="<?php echo $user['user_id'];?>"/>
									  </td>	
									  <td class="left"><a class="btn bg-avgreen text-white btn-sm" href="users/0/edit/<?php echo $user['user_id'];?>" align="center">Edit</a></td>
									  <td class="left"><?php echo $user['username'];?></td>
									  <td class="left"><?php echo $user['user_group'];?></td>
									  <td class="left"><?php echo $user['name'];?></td>
									  <td class="left"><?php echo $user['birthdate'];?></td>
									  <td class="left"><?php echo $user['email'];?></td>
									  <td class="left"><?php echo $user['phone'];?></td>
									  <td class="left"><?php echo $user['status_desc'];?></td>
									  <td class="left"><?php echo $user['sponsor'];?></td>
									</tr>
									<?php } ?>
									<?php } ?>
								  </tbody>
								</table>
							</div>
							<div class="pagination"><?php echo $pagination; ?></div>
						</div>
					</div>
				</div>
			</div>
		</form>		
	</div>
	
	<!--<div id="dialog-message" title="Message" style="display:none; width: 600px;">
	  <span id="msg"></span>
	</div>-->
	
	<!-- Modals -->
  <div class="row">
	<div class="col-md-4">
	  <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
		<div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
		  <div class="modal-content bg-gradient-darkorange">
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

// $(document).ready(function() {
	// <?php if(isset($err_msg)) { ?>
		// <?php if(strpos($err_msg, "mandatory") == false) { ?>
			// // $('#msg').html("<?php echo $err_msg; ?>");
			// // $(function() {
				// // $("#dialog-message").dialog({
					// // modal: true,
					// // width: 600,
					// // buttons: {
						// // Ok: function() {
							// // $(this).dialog("close");
						// // }
					// // }
				// // });
			// // });
			// var msg =  "<?php echo $err_msg; ?>";
			// swal("Good Job!", msg, "success");
		// <?php } else { ?>
			// var msg =  "<?php echo $err_msg; ?>";
			// swal("Error!", msg, "error");
		// <?php } ?>
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

function processTask(task) {
	$('#task').val(task); 
	$('form').attr('action', 'users'); 
	$('form').submit();
}

//--></script>							