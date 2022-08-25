<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php //echo $direction; ?>" lang="<?php //echo $lang; ?>" xml:lang="<?php //echo $lang; ?>">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?php echo WEBSITE_TITLE; ?></title>
  <base href="<?php echo LOCAL_PROD; ?>" />
  <link rel="icon" href="image/edsiicon2.png" type="image/x-icon">
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <!-- Icons -->
  <link rel="stylesheet" href="theme/argon/assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="theme/argon/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <!-- Argon CSS -->
  <link rel="stylesheet" href="theme/argon/assets/css/argon.css" type="text/css"/>
<style>
  #notifications {
    position: fixed;
    bottom: 0;
    right: 0;
    z-index: 1100;
  }
</style>

</head>
<body class="bg-screen"	>
  <div class="bg-gradient-grayblack" style="width:100%; padding-top: 50vh; position: absolute;">
    <div class="container row">
    </div>
    <div class="separator separator-bottom separator-skew zindex-100">
      <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <polygon class="fill-black" points="2560 0 2560 100 0 100"></polygon>
      </svg>
    </div>
  </div>
<!-- Main content -->
<div class="main-content" id="panel">
<style>
  .big-image {
    height: 90vh;
    margin: auto;
    background-image: url(image/homelogo.png);
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
  }
</style>
<div class="container row col-lg-12 center">
  <div class="header py-3 py-lg-4 pt-lg-3 col-lg-12 justify-content-center" style="margin-top: 30px;">
    <!-- Header -->
    <div class="container">
      <div class="header-body text-center">
        <div class="row justify-content-center">
          <div>
            <img src="image/2.png" style="width:150px; height:150px;">
            <br>
            <br>
            <h1 class="text-white">Welcome to <?php echo WEBSITE_TITLE; ?></h1>
            <br>
            <!-- <p class="text-lead text-white">"May bibilhin ka ba sa Divisoria o sa Taytay? Ipasabuy mo na.."</p> -->
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="row justify-content-center">
      <div>
        <div class="card bg-secondary border-0 mb-0">
          <div class="card-body px-lg-5 py-lg-4">
            <div class="text-center text-muted mb-4">
              <small>Sign in with credentials</small>
            </div>
            <form role="form" action="" method="post" enctype="multipart/form-data" id="form">
				<div class="form-group mb-3">
					<div class="input-group input-group-merge input-group-alternative">
					  <div class="input-group-prepend">
						<span class="input-group-text"><i class="ni ni-badge"></i></span>
					  </div>
					  <input class="form-control" placeholder="Username"  type="text" id="username" name="username" value="">
					</div>
				</div>
				<div class="form-group">
					<div class="input-group input-group-merge input-group-alternative">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
						</div>
							<input class="form-control" placeholder="Password" type="password" id="password" name="password" value="">
					</div>
				</div>
				<div class="col-lg-12">
					<input class="btn btn-outline-user text-white btn-block" type="button" id="search" value="Sign In" onclick="javascript: login();">
					<a class="btn btn-outline-home text-white btn-block" href="shop">Shop</a>
				</div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
	<div class="col-12 col-sm-12 col-md-12 col-lg-12 order-1 order-md-0 order-lg-0 order-sm-1 copyright text-center">
		<p class="text-white">Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved</p>
	</div>
  <!-- <div class="big-image col-lg-6 d-none d-lg-block">
  </div> -->
</div>

<div class="row">
	<div class="col-md-4">
	  <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
		<div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
		  <div class="modal-content bg-gradient-homemodal">
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
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="theme/argon/assets/vendor/jquery/dist/jquery.min.js"></script>
  <script src="theme/argon/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="theme/argon/assets/vendor/js-cookie/js.cookie.js"></script>
  <script src="theme/argon/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
  <script src="theme/argon/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
  <!-- Argon JS -->
  <script src="theme/argon/assets/js/argon.js?v=1.1.0"></script>
  <!-- Demo JS - remove this in your project -->
  <script src="theme/argon/assets/js/demo.min.js"></script>
</body>

</html>

<script type="text/javascript"><!--

$(document).ready(function() {
	<?php if(isset($err_msg)) { ?>
		<?php if(!empty($err_msg)) { ?>
			$('#msg').html("<?php echo $err_msg; ?>");			      
			$('#modal-notification').modal('show');
		<?php } ?>
	<?php } ?>	
});

function login() {
	$("#task").val("add");
	$('#form').attr('action', 'home');
	$('#form').submit();
}

//--></script>
</html>
