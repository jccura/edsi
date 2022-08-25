<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?php echo WEBSITE_TITLE;?> - Cart</title>
	<base href="<?php echo LOCAL_PROD; ?>" />
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Favicons -->
	<link rel="icon" href="image/edsiicon2.png" type="image/x-icon">

	<!-- Google font (font-family: 'Roboto', sans-serif; Poppins ; Satisfy) -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet"> 
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,600i,700,700i,800" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
	<link rel="stylesheet" href="theme/argon/assets/vendor/sweetalert2/dist/sweetalert2.min.css">

	<!-- Stylesheets -->
	<link rel="stylesheet" href="theme/boighr/css/bootstrap.min.css">
	<link rel="stylesheet" href="theme/boighr/css/plugins.css">
	<link rel="stylesheet" href="theme/boighr/css/style.css">
	<link rel="stylesheet" href="css/jquery-ui.css" />

	<!-- Cusom css -->
    <link rel="stylesheet" href="theme/boighr/css/custom.css">
	<link rel="stylesheet" type="text/css" href="stylesheet.css" />
	<!-- Modernizer js -->
	<script src="theme/boighr/js/vendor/modernizr-3.5.0.min.js"></script>
</head>
<!--<body onload="getPaymentOption();">-->
<body>
	<!-- Main wrapper -->
	<div class="wrapper" id="wrapper">
		<!-- Header -->
		<div id="wn__header" class="header__area header__absolute sticky__header">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6 col-sm-6 col-6 col-lg-2">
						<div class="logo">
							<a href="shop">
								<img src="image/reallogo.png" alt="logo" style="width:80px; height:90px; padding-bottom:20px;"/>
							</a>
						</div>
					</div>
					<div class="col-lg-10 d-none d-lg-block">
						<nav class="mainmenu__nav">
							<ul class="meninmenu d-flex justify-content-start">
								<li class="drop with--one--item"><a href="shop">&nbsp;Shop </a></li>
								<li class="drop with--one--item"><a href="cart">&nbsp;Cart </a></li>
								<?php if($this->user->isLogged()){ ?>
									<li class="drop with--one--item"><a href="orders">&nbsp;Orders</a></li>	
									<li class="drop with--one--item"><a href="profile"></i>&nbsp;Profile</a></li>	
									<li class="drop with--one--item"><a href="logout">&nbsp;Logout (<?php echo $this->user->getUsername(); ?>)</a></li>								
								<?php } else { ?>
									<li class="drop with--one--item"><a href="home">&nbsp;Log In</a></li>
									<li class="drop with--one--item"><a href="register">Register</a></li>
								<?php } ?>
							</ul>
						</nav>
					</div>
				</div>
				<!-- Start Mobile Menu -->
				<div class="row d-none">
					<div class="col-lg-12 d-none">
						<nav class="mobilemenu__nav">
							<ul class="meninmenu">
								<li class="drop with--one--item"><a href="shop"><!--<i class="fa fa-cart-plus">--></i>&nbsp;Shop</a></li>
								<li class="drop with--one--item"><a href="cart"><!--<i class="fa fa-cart-plus">--></i>&nbsp;Cart</a></li>
								<?php if($this->user->isLogged()){ ?>
									<li class="drop with--one--item"><a href="orders"><!--<i class="fa fa-lock">--></i>&nbsp;Orders</a></li>
									<li class="drop with--one--item"><a href="profile"><!--<i class="fa fa-user">--></i>&nbsp;Profile</a></li>	
									<li class="drop with--one--item"><a href="logout"><!--<i class="fa fa-lock">--></i>&nbsp;Logout (<?php echo $this->user->getUsername(); ?>)</a></li>
								<?php } else { ?>
									<li class="drop with--one--item"><a href="home"><!--<i class="fa fa-lock">--></i>&nbsp;Log In</a></li>
								<?php } ?>
							</ul>
						</nav>
					</div>
				</div>
				<!-- End Mobile Menu -->
	            <div class="mobile-menu d-block d-lg-none">
	            </div>
	            <!-- Mobile Menu -->	
			</div>		
		</div>
		<!-- //Header -->
        <!-- Start main Content -->
        <div class="maincontent bg--white pt--80 pb--55">
        	<div class="container">
				<form action="" method="post" id="form" enctype="multipart/form-data">
					<br>
					<div class="container"> 
						<div class="panel panel-default">
								<?php $order_id = 0;
									if(isset($this->session->data['order_id'])) { 
										$order_id = $this->session->data['order_id']; 
									}
									
									if(isset($cart['header']['order_id'])) {
										$order_id = $cart['header']['order_id'];
									}
								?>
								<?php if($order_id > 0) { ?>
								<h3 class="page-heading alert alert-info bg-blackgray text-white"><i class="fa fa-envelope"></i> Order Id <?php echo $order_id; ?> Details</h3>			
								<div class="panel-body">
									<input type="hidden" id="package" name="package" value="0" />
									<input type="hidden" id="task" name="task" value="" />
									<!--input type="hidden" id="whole_saler_or_distributor" name="whole_saler_or_distributor" value="<?php //echo $whole_saler_or_distributor; ?>" />-->
									<input type="hidden" id="order_id" name="order_id" value="<?php echo $order_id; ?>" />
									<?php if($task == "checkout") { ?>
										<?php if($this->user->isLogged()) { ?>
											<input type="hidden" id="sme_flag" name="sme_flag" value="<?php echo $flag['sme_flag']; ?>">
											<?php if($this->user->getUserGroupId() == 47 or $this->user->getUserGroupId() == 46 or $this->user->getUserGroupId() == 56) { ?>
												<div class="row">
													<div class="col-md-1">
														<label>Send To:</label>
													</div>
													<div class="col-md-3">
														<div class="form-group">
															<select class="form-control" id="send_to" name="send_to" onchange="sendToOptionSelected();">
																<option value="110" selected>My Address</option>
																<option value="111">My Customer</option>
															</select>
														</div>
													</div>						
												</div>
											<?php } else { ?>
												<input type="hidden" id="send_to" name="send_to" value="110" />
											<?php } ?>
											<div class="row">
												<div class="col-md-1">
													<label>Firstname:</label>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<input type="text" class="form-control" id="firstname" name="firstname" readonly value="<?php echo $this->user->getFirstName(); ?>" />
													</div>
												</div>
												<div class="col-md-1">
													<label>Lastname:</label>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<input type="text" class="form-control" id="lastname" name="lastname" readonly value="<?php echo $this->user->getLastName(); ?>" />
													</div>
												</div>							
											</div>
										<?php } else { ?>
											<input type="hidden" id="sme_flag" name="sme_flag" value="0">
											<div class="row">
												<div class="col-md-1">
													<label>Firstname:</label>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<input type="text" class="form-control" id="firstname" name="firstname" value="" />
														<input type="hidden" id="send_to" name="send_to" value="110" />
													</div>
												</div>
												<div class="col-md-1">
													<label>Lastname:</label>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<input type="text" class="form-control" id="lastname" name="lastname" value="" />
													</div>
												</div>
												<div class="col-md-1">
													<label>Sponsor:</label>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<input type="text" class="form-control" id="sponsor" name="sponsor" value="" />
													</div>
												</div>
											</div>
										<?php } ?>
										<?php if($cart['with_usergroup'] > 0) { ?>
											<input type="hidden" id="package" name="package" value="1" />
											<input type="hidden" id="usergroup" name="usergroup" value="1" />
											<input type="hidden" id="username" name="username" value="default" />
											<input type="hidden" id="password" name="password" value="default" />
										<?php } ?>
										<input type="hidden" id="country_id" name="country_id" value="168" />
										<div class="row">
											<div class="col-md-1">
												<label>Province:</label>
											</div>
											<div class="col-md-3">
												<select class="form-control" name="checkout_provinces" id="checkout_provinces" onchange="javascript: getCitiesNewCustomer();">
													<option value="">Select Province</option>
													<?php if(isset($provinces)) { ?> 
														<?php foreach($provinces as $province) { ?>
															<?php if($this->user->getProvinceId() == $province['province_id']) { ?>
																<option value="<?php echo $province['province_id']; ?>" selected><?php echo $province['description']; ?></option>
															<?php } else { ?>
																<option value="<?php echo $province['province_id']; ?>"><?php echo $province['description']; ?></option>
															<?php } ?>
														<?php } ?>
													<?php } ?>
												</select>
											</div>
											<div class="col-md-1">
												<label>City:</label>
											</div>
											<div class="col-md-3">
												<select class="form-control" name="checkout_city" id="checkout_city" onchange="javascript: getBrgyRegister();">
													<?php if($this->user->getCityMunicipalityId() > 0) { ?>
														<option value="<?php echo $this->user->getCityMunicipalityId(); ?>"><?php echo $this->user->getCityMunicipalityDesc(); ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="col-md-1">
												<label>Barangay:</label>
											</div>
											<div class="col-md-3">
												<select class="form-control" name="checkout_barangay" id="checkout_barangay" onchange="javascript: getPaymentOption();">
													<?php if($this->user->getBarangayId() > 0) { ?>
														<option value="<?php echo $this->user->getBarangayId(); ?>"><?php echo $this->user->getBarangayDesc(); ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<br>
										<div class="row">
											<div class="col-md-1">
												<label>Address:</label>
											</div>
											<div class="col-md-11">
												<div class="form-group">
													<input type="text" class="form-control" id="address" name="address" placeholder="House No. / St. / Blk & Lot" required value="<?php echo $this->user->getAddress();?>" />
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-1">
												<label>Landmark:</label>
											</div>
											<div class="col-md-11">
												<div class="form-group">
													<input type="text" class="form-control" id="order_landmark" name="order_landmark" placeholder="" required value="<?php echo $this->user->getLandmark();?>"/>
												</div>
											</div>
										</div>
										<?php if($this->user->isLogged()) { ?>
										<div class="row">
											<div class="col-md-1">
												<label>Contact #:</label>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<input type="text pull left" class="form-control" maxlength="11" id="contact" name="contact" value="<?php echo $this->user->getContact(); ?>" />
												</div>
											</div>
											<div class="col-md-1">
												<label>Email:</label>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<input type="text pull left" class="form-control" id="email" name="email" value="<?php echo $this->user->getEmail(); ?>" />
												</div>
											</div>
										</div>
										<?php } else { ?>
										<div class="row">
											<div class="col-md-1">
												<label>Contact #:</label>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<input type="text pull left" class="form-control" maxlength="11" id="contact" name="contact" value="" />
												</div>
											</div>
											<div class="col-md-1">
												<label>Email:</label>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<input type="text pull left" class="form-control" id="email" name="email" value="" />
												</div>
											</div>
										</div>
										<?php } ?>
									<?php } else { ?>
										<?php if($cart['header']['status_id'] > 0) { ?>
										<div class="row">
											<div class="col-md-2">
												<label>Customer Name:</label>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<b><?php echo $cart['header']['customer_name'];?></b>
												</div>
											</div>
										</div>
											<div class="row">
												<div class="col-md-2">
													<label>Contact Number:</label>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<b><?php echo $cart['header']['contact'];?></b>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-2">
													<label>Email:</label>
												</div>
												<div class="col-md-5">
													<div class="form-group">
														<b><?php echo $cart['header']['email'];?></b>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-2">
													<label>Address:</label>
												</div>
												<div class="col-md-10">
													<div class="form-group">
														<b><?php echo $cart['header']['address'];?></b>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-2">
													<label>Landmark:</label>
												</div>
												<div class="col-md-10">
													<div class="form-group">
														<b><?php echo $cart['header']['landmark'];?></b>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-2">
													<label>Payment Option:</label>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<b><?php echo $cart['header']['payment_option'];?></b>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-2">
													<label>Delivery Option:</label>
												</div>
												<div class="col-md-5">
													<div class="form-group">
														<b><?php echo $cart['header']['delivery_option'];?></b>
													</div>
												</div>
											</div>
										<?php } ?>
									<?php } ?>
									<div class="row">
										<div class="col-sm-12 col-md-12">
											<table class="list">
												<thead>
													<tr>
														<td>Item</td>
														<td>Amount</td>
														<td>Quantity</td>
														<td>&nbsp;</td>
													</tr>
												</thead>
												<tbody>
													<?php foreach($cart['details'] as $item) { ?>
													<tr>
														<td><?php echo $item['item_name'];?></td>
														<td>Php <?php echo number_format($item['amount'],2);?></td>
														<td><?php echo $item['quantity'];?></td>
														<td>
															<?php if($cart['header']['status_id'] == 0) { ?>
																<a class="btn btn-danger btn-md" href="cart/<?php echo $cart['header']['ref']; ?>/removeitem/<?php echo $cart['header']['order_id']; ?>/<?php echo $item['order_det_id']; ?>"><i class="fa fa-trash"></i></a>
															<?php } ?>
														</td>	
													</tr>
													<?php } ?>						
												</tbody>
											</table>
										</div>
									</div> 
									<?php if($task == "checkout") { ?>
									<div class="row">
										<div class="col-sm-6 col-md-6">											
											<br>
											<div class="row">						
												<div class="col-md-4">
													<label>Payment Option</label>
												</div>
												<div class="col-md-6">
													<select class="form-control" name="payment_option" id="payment_option" onchange="javascript: paymentOptionSelected();" disabled>
														<option value="">Select Option</option>
													</select>
												</div>
											</div>
											<br>
											<div class="row">						
												<div class="col-md-4">
													<label>Mode of Delivery</label>
												</div>
												<div class="col-md-6">
													<input type="text" class="form-control" id="delivery_option_desc" name="delivery_option_desc" value="" readonly />
													<input type="hidden" class="form-control" id="delivery_option" name="delivery_option" value="" />
												</div>
											</div>
											<br>
											<div class="receiving row hidden">						
												<div class="col-md-4">
													<label>Receiving Branch Address</label>
												</div>
												<div class="col-md-6">
													<textarea class="form-control" rows="1" id="receiving_branch" name="receiving_branch" value="" /></textarea>
												</div>
											</div>
											<br>
										</div>
										<?php } ?>
										<div class="col-sm-6 col-md-6">
											<table class="list">
												<tbody>
													<tr>
														<td>Total Quantity: <?php echo $cart['header']['total'];?></td>
													</tr>
													<?php if($task == "checkout") { ?>
													<tr>
														<td>
															<input type="hidden" id="delivery_fee" name="delivery_fee" value="<?php echo $cart['header']['delivery_fee'];?>">
															Delivery Fee: <span id="delivery_fee_disp">Php <?php echo $cart['header']['delivery_fee'];?></span>
														</td>
													</tr>
													<tr>
														<td>
															<input type="hidden" id="discount" name="discount" value="<?php echo $cart['discount']; ?>">
															Discount: Php <span id="discount_disp"><?php echo $cart['discount']; ?></span>
														</td>
													</tr>
													<tr>
														<td>
															<input type="hidden" id="grand_total" name="grand_total" value="<?php echo $cart['header']['amount'] + $cart['header']['delivery_fee'] - $cart['discount'];?>">
															Grand Total: Php <span id="grand_total_disp"><?php echo $cart['header']['amount'] + $cart['header']['delivery_fee'] - $cart['discount'] ?></span>
														</td>
													</tr>
													<?php } else { ?>
													<tr>
														<td>
															Delivery Fee: <span id="delivery_fee_disp">Php <?php echo $cart['header']['delivery_fee'];?></span>
														</td>
													</tr>
													<tr>
														<td>
															Discount: Php <span id="discount_disp"><?php echo $cart['discount']; ?></span>
														</td>
													</tr>
													<tr>
														<td>
															<input type="hidden" id="grand_total" name="grand_total" value="<?php echo $cart['header']['amount'];?>">
															Grand Total: Php <span id="grand_total_disp"><?php echo $cart['header']['amount'];?></span>
														</td>
													</tr>
													<?php } ?>
													<?php if($task == "checkout") { ?>
													<tr>
														<td>
															<center>
																<button class="btn bg-turqouise text-white btn-lg" onclick="javascript:submitOrder();">Submit Order</button>
															</center>
														</td>
													</tr>
													<?php } else { ?>
													<tr>
														<td>
															<center>
																<?php if($cart['header']['status_id'] > 0) { ?>
																	<?php if($cart['header']['status_id'] == 138) { ?>
																		<a class="btn btn-danger text-dark btn-lg" href="cart/<?php echo $cart['header']['ref']; ?>/cancel/<?php echo $cart['header']['order_id']; ?>">Cancel Order</a>&nbsp;&nbsp;
																		<a href="shop" class="btn bg-turqouise text-dark btn-lg" >Continue Shopping</a>
																	<?php } ?>
																<?php } else { ?>
																	<button class="btn bg-primary text-dark btn-lg" onclick="javascript:checkout();">Checkout</button>
																	<a href="shop" class="btn bg-turqouise text-dark btn-lg" >Continue Shopping</a>
																<?php } ?>				
															</center>
														</td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>	
								</div>
							<?php } else { ?>	
								<div class="panel-heading">
									Nothing in cart. <a href="shop" class="btn bg-turqouise text-dark btn-lg" >Go to shop</a>
								</div>
							<?php } ?>
						</div>
					</div>
					<div id="dialog-message" title="Message" style="display:none; width: 400px;">
						<span id="msg"></span>
					</div>	
				</form>
        	</div>
        </div>
        <!-- End main Content -->
		<!-- Footer Area -->
		<footer id="wn__footer" class="footer__area bg__cat--8 brown--color" style="background-color: #ffffff; border-top: #eeeeee solid 1px;">
			<div class="copyright__wrapper">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="copyright">
								<div class="copy__right__inner text-center" style="padding: 20px;">
									<a href="#"><img src="image/homelogo.gif"></a>
									<p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</footer>
		<!-- //Footer Area -->
	</div>
	<!-- //Main wrapper -->

	<!-- JS Files -->
	<script src="theme/boighr/js/vendor/jquery-3.2.1.min.js"></script>
	<script src="theme/boighr/js/popper.min.js"></script>
	<script src="theme/boighr/js/bootstrap.min.js"></script>
	<script src="theme/boighr/js/plugins.js"></script>
	<script src="theme/boighr/js/active.js"></script>
	<script src="theme/argon/assets/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
	
	<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.11.2.min.js"></script>
	<script src="theme/regal/vendors/jquery-bar-rating/jquery.barrating.min.js"></script>
	<script src="css/jquery-ui.js"></script>
	
	<script type="text/javascript">

	$(document).ready(function() {

		$("#quantity").keydown(function (e) {
			// Allow: backspace, delete, tab, escape, enter and .
			if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
				 // Allow: Ctrl+A, Command+A
				(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
				 // Allow: home, end, left, right, down, up
				(e.keyCode >= 35 && e.keyCode <= 40)) {
					 // let it happen, don't do anything
					 return;
			}
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});
		
	});

	function addToCart() {
		var valid = 1;
		var msg = "";
		
		// <?php if(isset($cart['with_package'])) { ?>
			// <?php if($cart['with_package'] > 1) { ?>
				// valid = 0;
				// msg = "msg dito";
			// <?php } ?>
		// <?php } ?>
			// if(valid == 0){
				// alert(msg);
			// }else {
				// $('#task').val('addToCart');
				// $('form').attr('action', 'cart'); 
				// $('form').submit();
			// }
	}

	</script>	

<script type="text/javascript">
$(document).ready(function() {

    $("#quantity").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	
	<?php if(isset($err_msg)) { ?>
		var msg = "<?php echo str_replace('"', '', $err_msg); ?>";
		$('#msg').html(msg);		
		$(function() {
			$('#dialog-message').dialog({
			  modal: true,
			  width: 600,
			  buttons: {
				Ok: function() {
					<?php if(isset($order_result)) { ?>
						var order_id = <?php echo $order_result; ?>;
						$('form').attr('action', "index.php?route=account/trackingpage&order_id=" + order_id ); 
						$('form').submit();
					<?php } else { ?>
						$( this ).dialog( "close" );
					<?php } ?>
				}			
			  }
			});
		});			
	<?php } ?>
	<?php if($this->user->isLogged()) { ?>
		getPaymentOption();
	<?php } ?>
});

function checkout() {
	var valid = 1;
	var msg = "";
	<?php if(isset($cart['with_package'])) { ?> 
		<?php if($cart['with_package'] > 1){ ?>
			valid = 0;
			msg = "Ordering 2 different type of package is not allowed!";
		<?php } ?>
	<?php } ?>
	if(valid == 0){
		// Swal.fire({
			// title: "Message",
			// text: msg,
			// showOkButton: true  
		// })
		alert(msg);
	}else {
		$('#task').val('checkout');
		$('form').attr('action', 'cart'); 
		$('form').submit();
	}
}

function submitOrder() {
	$('#task').val('submitOrder');
	$('form').attr('action', 'cart'); 
	$('form').submit();
}

function sendToOptionSelected() {
	var send_to = $('#send_to').val();
	paymentOptionSelected();
	if(send_to == 111) {
		$("#firstname").removeAttr("readonly"); 
		$("#lastname").removeAttr("readonly"); 
	} else {
		$("#firstname").attr('readonly', 'readonly');;
		$("#lastname").attr('readonly', 'readonly');;
	}
}

function paymentOptionSelected() {
	var send_to = $('#send_to').val();
	var payment_option = $('#payment_option').val();
	var brgy_id = $('#checkout_barangay').val()
	
	if(payment_option == 89 || payment_option == 90 || payment_option == 91 || payment_option == 145) {
		$('#delivery_option').val(96);
		$('#delivery_option_desc').val("Dropship");
		$(".receiving").addClass("hidden");
	} else if(payment_option == 146 || payment_option == 147 || payment_option == 148 || payment_option == 157 || payment_option == 158) {
		$('#delivery_option').val(97);
		$('#delivery_option_desc').val("Area Operator");
	} else if(payment_option == 94) {
		$('#delivery_option').val(109);
		$('#delivery_option_desc').val("LBC Express");
		$(".receiving").removeClass("hidden");
	} else if(payment_option == 107) {
		$('#delivery_option').val(109);
		$('#delivery_option_desc').val("LBC Express");
		$(".receiving").addClass("hidden");
	}
	
	var delivery_fee = 0;
	var discount = <?php echo $cart['discount'];?>;
	var amount = <?php echo $cart['header']['amount'];?>;
	var grand_total = 0;
	
	 <?php if($order_id > 0) { ?>
		if(payment_option != 0) {
			$.ajax({
				url: 'determineshippingfee/' + payment_option + "/" + <?php echo $order_id; ?> + "/" + brgy_id,
				type: 'get',
				dataType: 'json',
				success: function(json) {		
					if(send_to == 111) {
						discount = 0.00;
					}
					if (json['success'] == "success") {	
						delivery_fee = json['fee'];
						$('#delivery_fee').val(delivery_fee);
						$('#delivery_fee_disp').html(addCommas(delivery_fee));
						$('#discount').val(discount);
						$('#discount_disp').html(addCommas(discount+""));
						grand_total = parseFloat(amount) + parseFloat(delivery_fee) - parseFloat(discount)
						$('#grand_total').val(grand_total);
						$('#grand_total_disp').html(addCommas(grand_total));
					}		
				}
			});
		}
	 <?php } ?>
}

function getCitiesNewCustomer() {
	var province_id = $('#checkout_provinces').val();

	$.ajax({
		url: 'index.php?route=account/cart/getCities&province_id=' + province_id,
		type: 'get',
		dataType: 'json',
		success: function(json) {	 						
			if (json['status'] == "success") {		
				var length = json['cities'].length;	
				var cities = "<option value='0' selected disabled>Select City</option>";
				for(var i = 0; i < length; i++){
					cities += "<option value='" + json['cities'][i]['city_municipality_id'] +"'>" + json['cities'][i]['description'] + "</td></tr>";
				}
				
				$("#checkout_city").html(cities);
				$("#checkout_barangay").html("<option value='0' selected disabled>Select Brgy</option>");
				$("#city_hidden").removeClass("hidden");
				document.getElementById("delivery_option_desc").value = "";
				document.getElementById("delivery_option").value = "";
				document.getElementById("payment_option").value = "";
				document.getElementById("payment_option").disabled = true;
				
			}		
		}
	});
}

function getBrgyRegister() {
	var city_id = $('#checkout_city').val().split("-");

	$.ajax({
		url: 'index.php?route=account/cart/getBarangay&city_id=' + city_id,
		type: 'get',
		dataType: 'json',
		success: function(json) {	 						
			if (json['status'] == "success") {		
				var length = json['brgy'].length;	
				var brgy = "<option value='0' selected disabled>Select Brgy</option>";
				for(var i = 0; i < length; i++){
					brgy += "<option value='" + json['brgy'][i]['barangay_id'] +"'>" + json['brgy'][i]['description'] + "</td></tr>";
				}

				$("#checkout_barangay").html(brgy);
				document.getElementById("delivery_option_desc").value = "";
				document.getElementById("delivery_option").value = "";
				document.getElementById("payment_option").value = "";
				document.getElementById("payment_option").disabled = true;
				
			}		
		}
	});
}

function getPaymentOption() {
	
	var brgy_id = $('#checkout_barangay').val()
	var sme_flag = $('#sme_flag').val()
	console.log('getPaymentOption index.php?route=account/cart/getPaymentOption&brgy_id=' + brgy_id + '&sme_flag=' + sme_flag);	
	$.ajax({
		url: 'index.php?route=account/cart/getPaymentOption&brgy_id=' + brgy_id + '&sme_flag=' + sme_flag,
		type: 'get',
		dataType: 'json',
		success: function(json) {
			if (json['status'] == "success") {
				var length = json['payment'].length;	
				var payment = "<option value='' selected disabled>Select Option</option>";
				for(var i = 0; i < length; i++){
					payment += "<option value='" + json['payment'][i]['status_id'] +"'>" + json['payment'][i]['description'] + "</td></tr>";
				}
			}
			$('#payment_option').html(payment);
			document.getElementById("delivery_option_desc").value = "";
			document.getElementById("delivery_option").value = "";
			document.getElementById("payment_option").disabled = false;
		}
	});
}

function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
</script>

</body>
</html>
