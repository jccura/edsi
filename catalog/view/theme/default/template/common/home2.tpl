<?php echo $header; ?>
	<form action="" method="post" enctype="multipart/form-data" id="form" class="homeform form-box">
		<input type="hidden" name="task" id="task">
		<div class="row" style="margin-top: 15%; margin-bottom: 10%;">
			<div class="col-md-3"></div>
			<div class="col-md-6">
				<div class="panel panel-default" style="background-color: rgba(33,33,33,0.8); color: white;">
					<div class="panel-heading" style="background-color: rgba(33,33,33,0.8); color: white !important; font-size: 26px;"><b>Welcome to <?php echo WEBSITE_TITLE; ?>, please provide login information.</b></div>
					<br>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-3" style="margin-top: 10px;">
										<b>Username:</b>
									</div>
									<div class="col-md-8">
										<input class="form-control input-lg" type="text" id="username" name="username" value="<?php if(!isset($autofill)) { if(isset($customer['username'])) { echo $customer['username']; } } ?>">
									</div>
								</div>				
							</div>
						</div>
						<br>							
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-3" style="margin-top: 10px;">
										<b>Password:</b>
									</div>
									<div class="col-md-8">
										<input class="form-control input-lg" type="password" id="password" name="password" value="<?php if(!isset($autofill)) { if(isset($customer['password'])) { echo $customer['password']; } } ?>">
									</div>
								</div>				
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-4" style="margin-top:20px;">
								<center>
									<input type="hidden" id="downline" name="downline" value="1">
									<input type="hidden" id="group_code" name="group_code" value="">
									<input class="btn btn-success btn-lg" style="width:100%;" type="button" id="search" value="Login" onclick="javascript: login();">
								</center>
							</div>
							<div class="col-md-4" style="margin-top:20px;">
								<center>
									<a class="btn btn-warning btn-lg" style="width:100%;" href="register">Register</a>
								</center>
							</div>
							<div class="col-md-4" style="margin-top:20px;">
								<center>
									<a class="btn btn-info btn-lg" style="width:100%;" href="/front">Home</a>
								</center>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-12" style="margin-top:10px;">
								<center>
									<a href="#"><i class="fa fa-facebook-square fa-3x"></i></a>&nbsp;&nbsp;
									<a href="#"><i class="fa fa-twitter-square fa-3x"></i></a>&nbsp;&nbsp;
									<a href="#"><i class="fa fa-youtube-square fa-3x"></i></a>
								</center>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3"></div>
		</div>
	</form>		

	<div id="dialog-message" title="Message" style="display:none; width: 600px;">
	  <span id="msg"></span>
	</div>

<?php echo $footer; ?>

<script type="text/javascript"><!--

$(document).ready(function() {
	$('#birthdate').datepicker({dateFormat: 'yy-mm-dd'});
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

function login() {
	$("#task").val("add");
	$('#form').attr('action', 'home');
	$('#form').submit();
}

//--></script>