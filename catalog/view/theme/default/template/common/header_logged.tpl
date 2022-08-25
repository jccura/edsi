<!-- =========================================================
* Argon Dashboard PRO v1.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro
* Copyright 2019 Creative Tim (https://www.creative-tim.com)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 -->
<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
	<!-- Required meta tags --> 
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
	<title><?php echo WEBSITE_TITLE; ?></title>
	<base href="<?php echo LOCAL_PROD; ?>" />  
	<link rel="icon" href="image/edsiicon2.png" type="image/x-icon">
	<!-- Fonts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"/>
	<link rel="stylesheet" href="theme/argon/assets/vendor/sweetalert2/dist/sweetalert2.min.css"/>
	<!-- Icons -->
	<link rel="stylesheet" href="theme/argon/assets/vendor/nucleo/css/nucleo.css" type="text/css"/>
	<link rel="stylesheet" href="theme/argon/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css"/>
	<link href="theme/minimal/css/font-awesome.css" rel="stylesheet"/> 
	<link rel="stylesheet" type="text/css" href="css/summernote/summernote.css"/> 
	<link rel="stylesheet" href="css/jquery-ui.css" />
	<!-- Page plugins -->
	<!-- Argon CSS -->
	<link rel="stylesheet" href="theme/argon/assets/css/argon.css" type="text/css"/>
</head>

<body class="g-sidenav-show g-sidenav-hidden">
	<!-- Sidenav -->
	<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-gradient-grayblack text-white" id="sidenav-main">
		<div class="scrollbar-inner">
			<!-- Brand -->
			<div class="sidenav-header d-flex align-items-center">
				<div class="ml-auto">
					<!-- Sidenav toggler -->
					<div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
						<div class="sidenav-toggler-inner">
							<i class="sidenav-toggler-line"></i>
							<i class="sidenav-toggler-line"></i>
							<i class="sidenav-toggler-line"></i>
						</div>
					</div>
				</div>
			</div>
			<div align="center">
				<a class="navbar-brand" href="<?php echo $this->user->getRedirectPage(); ?>">
					<?php if($this->user->getExtension() != "") {?>
						<img src="profiles/<?php echo $this->user->getId(); ?>.<?php echo $this->user->getExtension(); ?>" title="Esthetique Direct Sales Inc." class="navbar-brand-img" style="border:1px solid black; border-radius:50%; width:150px; height:150px; !important;" alt="..."/>
					<?php } else {?>
						<img src="image/active.jpg" title="Esthetique Direct Sales Inc." class="navbar-brand-img" style="border-radius:50%; width:150px;" alt="..."/>
					<?php }?>
				</a>
			</div>
			<div class="navbar-inner">
				<!-- Collapse -->
				<div class="collapse navbar-collapse" id="sidenav-collapse-main">
					<br/>
					<h5 class="text-white">Name: <?php echo $this->user->getName(); ?></h5>
					<h5 class="text-white">Username: <?php echo $this->user->getUsername(); ?></h5>
					<h5 class="text-white">User Type: <?php echo $this->user->getUserGroup(); ?></h5>
					<?php if($this->user->getUserGroupId() == 46 or $this->user->getUserGroupId() == 56) { ?>
						<h5 class="text-white">Rank: <?php echo $this->user->getRank(); ?></h5>
					<?php } ?>
					<h5 class="text-white">ID Number: <?php echo $this->user->getIdNo(); ?></h5>
					<!-- Nav items -->
					<ul class="navbar-nav">
						<?php foreach($main_menu as $mm) { ?>
							<li class="nav-item text-white">
								<?php if($mm['counter'] > 0) { ?>
									<a class="nav-link text-white" href="<?php echo "#".$mm['description']; ?>" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="<?php echo $mm['description']; ?>">
										<i class="ni ni-<?php echo $mm['image']; ?> text-white"></i>
										<span class="nav-link-text"><?php echo $mm['description']; ?></span>
									</a>
								<?php $second_level_menu = $this->user->getSecondLevelMenu($mm['permission_id']); ?>
									<div class="collapse" id="<?php echo $mm['description']; ?>">
										<ul class="nav nav-sm flex-column">
											<?php foreach($second_level_menu as $slm) { ?>
												<li class="nav-item">
													<a href="<?php echo $slm['module']; ?>" class="nav-link">
														<i class="<?php echo $slm['image']; ?> text-white"></i> 
														<span class="title"><?php echo $slm['description']; ?></span>
													</a>
												</li>											
											<?php } ?>
										</ul>
									</div>
								<?php } else {?>
									<li class="nav-item text-white">
										<a class="nav-link" href="<?php echo $mm['module']; ?>">
											<i class="ni ni-<?php echo $mm['image']; ?> text-white"></i>
											<span class="nav-link-text"><?php echo $mm['description']; ?></span>
										</a>
									</li>
								<?php } ?>
								<!--<?php//if($mm['counter'] > 0) { ?>
								<?php //} ?>-->
							</li>       
						<?php } ?>
						<li class="nav-item">
							<a class="nav-link" href="logout">
								<i class="ni ni-button-power text-white"></i>
								<span class="nav-link-text">Logout</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</nav>
	<!-- Main content -->
	<div class="main-content" id="panel">
		<!-- Topnav -->
		<nav class="navbar navbar-top navbar-expand navbar-dark btn-outline-upper border-bottom">
			<div class="container-fluid">
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<!-- Navbar links -->
					<ul class="navbar-nav align-items-center ml-md-auto">
						<li class="nav-item d-xl-none">              
							<div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
								<div class="sidenav-toggler-inner">
									<i class="sidenav-toggler-line"></i>
									<i class="sidenav-toggler-line"></i>
									<i class="sidenav-toggler-line"></i>
								</div>
							</div>
						</li>
					</ul> 
					<ul class="navbar-nav align-items-center ml-auto ml-md-0">
						<li class="nav-item dropdown">
							<a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<div class="media align-items-center">
									<span class="avatar avatar-sm rounded-circle">
										<?php if($this->user->getExtension() != "") {?>
											<img src="profiles/<?php echo $this->user->getId(); ?>.<?php echo $this->user->getExtension(); ?>"  style=" border-radius:50%; width:40px; height:40px; !important;" alt="...">
										<?php } else {?>
											<br>
										<img src="image/active.jpg"  class="navbar-brand-img" style="border-radius:50%; width:40px; height:40px; " alt="...">
										<?php }?>
									</span>
									<div class="media-body ml-2 d-none d-lg-block">
										<span class="mb-0 text-md  text-dark font-weight-bold"><?php echo $this->user->getUsername(); ?></span>
									</div>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-right btn-outline-user">
								<div class="dropdown-header noti-title">
									<h6 class="text-overflow text-sm m-0 ">Welcome <?php echo $this->user->getUsername(); ?>!</h6>
								</div>
								<a href="profile" class="dropdown-item">
									<i class="ni ni-single-02"></i>
									<span>My profile</span>
								</a>
								<?php if ($this->user->isLogged()) { ?>
								<?php if ($this->user->getUserGroupId() == 12 or $this->user->getUserGroupId() == 46 ) { ?>
								<div class="dropdown-divider"></div>
								<a href="#" class="dropdown-item">
									<i class="ni ni-shop"></i>
									<span>Shop</span>
								</a>
								<?php } ?>
								<div class="dropdown-divider"></div>
								<a href="logout" class="dropdown-item">
									<i class="ni ni-user-run"></i>
									<span>Logout</span>
								</a>
								<?php } else { ?>
								<div class="dropdown-divider"></div>
								<a href="logout" class="dropdown-item">
									<i class="ni ni-user-run"></i>
									<span>Logout</span>
								</a>
								<?php } ?>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</nav>
    <!-- Header -->
  