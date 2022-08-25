<?php echo $header; ?>

	<div class="col-md-12">
			
		<form action="" method="post" enctype="multipart/form-data" id="form">
			<br>
			<h3 class="page-heading mb-4"><i class="fa fa-users"></i> Edit Users Screen</h3>
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-md-2"></div>
						<div class="col-md-4">
							<div class="form-group">	
								<label>First Name:</label>
								<input class="form-control input" type="text" id="firstname" name="firstname" value="<?php echo $user_details['firstname'];?>">
								<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_details['user_id'];?>" />
							</div>
							<div class="form-group">	
								<label>Middle Name:</label>
								<input class="form-control input" type="text" id="middlename" name="middlename" value="<?php echo $user_details['middlename'];?>">
							</div>
							<div class="form-group">	
								<label>Last Name:</label>
								<input class="form-control input" type="text" id="lastname" name="lastname" value="<?php echo $user_details['lastname'];?>">
							</div>
							<div class="form-group">	
								<label>Username:</label>
								<input class="form-control input" type="text" id="username" name="username" value="<?php echo $user_details['username'];?>">
							</div>
							<div class="form-group">	
								 <label>Team Leader:</label>
								 <select class="form-control" id="teamlead_id" name="teamlead_id">
									<option value="0" selected readonly="readonly">Select Team Leader</option>
									<?php if(isset($teamleaders)) { ?>
										<?php if(!empty($teamleaders)){ ?>
											<?php foreach($teamleaders as $tl) { ?>
												<?php if($user_details['teamlead_id'] == $tl['user_id']) { ?>
													<option value="<?php echo $tl['user_id'] ?>" selected ><?php echo $tl['teamleader'] ?></option>
												<?php } else { ?>	
													<option value="<?php echo $tl['user_id'] ?>"><?php echo $tl['teamleader'] ?></option>
												<?php } ?>
											<?php } ?>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">	
								<label>Date of Birth:</label>
								<input class="form-control input" type="text" name="birthdate" size="12" id="birthdate" value="<?php echo $user_details['birthdate'];?>" />
							</div>
							<div class="form-group">	
								<label>User Groups:</label>
								<select class="form-control input" id="user_group_id" name="user_group_id">
									<option value="<?php echo $user_details['user_group_id'];?>" selected><?php echo $user_details['group_name'];?></option>
									<?php if($this->user->getUserGroupId() == 12 or $this->user->getUserGroupId() == 1) {?>
									<?php foreach ($user_groups as $user_group) { ?>
									<option value="<?php echo $user_group['user_group_id'];?>"><?php echo $user_group['name'];?></option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">	
								<label>Email:</label>
								<input class="form-control input" type="text" id="email" name="email" value="<?php echo $user_details['email'];?>">
							</div>
							<div class="form-group">	
								<label>Phone:</label>
								<input class="form-control input" type="text" id="phone" name="phone" value="<?php echo $user_details['phone'];?>">
							</div>
							<div class="form-group">	
								 <label>Sub Leader:</label>
								 <select class="form-control" id="coordinator_id" name="coordinator_id">
									<option value="0" selected readonly="readonly">Select Sub Leader</option>
									<?php if(isset($subleaders)) { ?>
										<?php if(!empty($subleaders)){ ?>
											<?php foreach($subleaders as $sub) { ?>
												<?php if($user_details['coordinator_id'] == $sub['user_id']) { ?>
													<option value="<?php echo $sub['user_id'] ?>" selected><?php echo $sub['coordinator'] ?></option>
												<?php } else { ?>
													<option value="<?php echo $sub['user_id'] ?>"><?php echo $sub['coordinator'] ?></option>
												<?php } ?>									
											<?php } ?>
										<?php } ?>
									<?php } ?>
								</select>
							</div>				
						</div>
						<div class="col-md-2"></div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<center>
								<input type="hidden" id="task" name="task" value="submitedit">
								<input class="btn bg-avgreen text-white btn-lg" type="button" id="search" value="Save" onclick="$('form').attr('action', 'users'); $('form').submit();">											
							</center>
						</div>
					</div>				
				</div>
			</div>					
		</form>		
	</div>

<?php echo $footer; ?>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#birthdate').datepicker({dateFormat: 'yy-mm-dd'});
});

//--></script>							