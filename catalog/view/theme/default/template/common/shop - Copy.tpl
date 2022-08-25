<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<!-- belle/home2-default.html   11 Nov 2019 12:22:28 GMT -->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />																																									
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?php echo WEBSITE_TITLE;?></title>
	<base href="<?php echo LOCAL_PROD; ?>" />
	<meta name="description" content="description">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Favicon -->
	<!--<link rel="shortcut icon" href="theme/belle/assets/images/favicon.png">-->
	<link rel="icon" href="image/avicon.png" type="image/x-icon">
	<!-- Plugins CSS -->
	<link rel="stylesheet" href="theme/belle/assets/css/plugins.css">
	<!-- Bootstap CSS -->
	<link rel="stylesheet" href="theme/belle/assets/css/bootstrap.min.css">
	<!-- Main Style CSS -->
	<link rel="stylesheet" href="theme/belle/assets/css/style.css">
	<link rel="stylesheet" href="theme/belle/assets/css/responsive.css">
</head>
<body class="template-index home2-default"">
<div id="pre-loader">
    <img src="theme/belle/assets/images/avloading.gif" alt="Loading..." />
</div>
<div class="pageWrapper">
    <!--Header-->
    <div class="header-wrap animated d-flex border-bottom">
    	<div class="container-fluid">        
            <div class="row align-items-center">
            	<!--Desktop Logo-->
                <div class="logo col-md-2 col-lg-2 d-none d-lg-block">
                    <a href="shop">
                    	<img src="image/logo.png" alt="Manyblessings Direct Sale Inc." title="Manyblessings Direct Sales Inc."/>
                    </a>
                </div>
                <!--End Desktop Logo-->
                <div class="col-2 col-sm-3 col-md-3 col-lg-8">
                	<div class="d-block d-lg-none">
                        <button type="button" class="btn--link site-header__menu js-mobile-nav-toggle mobile-nav--open">
                        	<i class="icon anm anm-times-l"></i>
                            <i class="anm anm-bars-r"></i>
                        </button>
                    </div>
                	<!--Desktop Menu-->
						<nav class="grid__item" id="AccessibleNav"><!-- for mobile -->
							<ul id="siteNav" class="site-nav medium center hidearrow">							
								<li class="lvl1 parent megamenu"><a href="shop">Shop</a></li>
								<?php if($this->user->isLogged()){ ?>
									<?php if($this->user->getUserGroupId() == 12 || $this->user->getUserGroupId() == 13 || $this->user->getUserGroupId() == 36 || $this->user->getUserGroupId() == 39 || $this->user->getUserGroupId() == 41 || $this->user->getUserGroupId() == 42
									 || $this->user->getUserGroupId() == 43 || $this->user->getUserGroupId() == 45 || $this->user->getUserGroupId() == 46 || $this->user->getUserGroupId() == 47 || $this->user->getUserGroupId() == 49 || $this->user->getUserGroupId() == 52 || $this->user->getUserGroupId() == 53
									 || $this->user->getUserGroupId() == 54 ) { ?>
									<li class="lvl1 parent megamenu"><a href="orders">Orders</a></li>
									<?php } ?>
									<li class="lvl1 parent megamenu"><a href="profile">Profile</a></li>
									<li class="lvl1 parent megamenu"><a href="logout">Logout</a></li>									
								<?php } else { ?>
									<li class="lvl1 parent megamenu"><a href="home">Login</a></li>
								<?php } ?>
								<!--<li class="lvl1"><a href="#"><b>Buy Now!</b> <i class="anm anm-angle-down-l"></i></a></li>-->
							</ul>
						</nav>
                    <!--End Desktop Menu-->
                </div>
                <!--Mobile Logo-->
                <div class="col-6 col-sm-6 col-md-6 col-lg-2 d-block d-lg-none mobile-logo">
                	<div class="logo">
                        <a href="shop">
                            <img style="width:50%; height:auto;" src="image/logo.png" alt="Manyblessings Direct Sales Inc." title="Manyblessings Direct Sales Inc." />
                        </a>
                    </div>
                </div>
                <!--Mobile Logo-->
                <!--<div class="col-4 col-sm-3 col-md-3 col-lg-2">              	
                    <div class="user-header__user">
                    	<!--<a href="home"><i class="icon anm anm-user-al"></i></a>-->
						<!--<ul id="siteNav">-->
						<?php //if($this->user->isLogged()){ ?>
							<!--<li><a href="logout"><i class="icon anm anm-user"></i><b> Logout</b></a></li>-->
							<?php //} else { ?>
							<!--<li><a href="home"><i class="icon anm anm-user"></i><b> Login</b></a></li>-->
						<?php //} ?>
							<!--<li class="lvl1"><a href="#"><b>Buy Now!</b> <i class="anm anm-angle-down-l"></i></a></li>-->
						<!--</ul>
                    </div>
                </div>-->
        	</div>
        </div>
    </div>
    <!--End Header-->
    
    <!--Mobile Menu-->
    <div class="mobile-nav-wrapper" role="navigation">
		<div class="closemobileMenu"><i class="icon anm anm-times-l pull-right"></i> Close Menu</div>
        <ul id="MobileNav" class="mobile-nav">
        	<li class="lvl1 parent megamenu"><a href="shop">Shop</a></li>
			<?php if($this->user->isLogged()){ ?>
				<?php if($this->user->getUserGroupId() == 12 || $this->user->getUserGroupId() == 13 || $this->user->getUserGroupId() == 36 || $this->user->getUserGroupId() == 39 || $this->user->getUserGroupId() == 41 || $this->user->getUserGroupId() == 42
				 || $this->user->getUserGroupId() == 43 || $this->user->getUserGroupId() == 45 || $this->user->getUserGroupId() == 46 || $this->user->getUserGroupId() == 47 || $this->user->getUserGroupId() == 49 || $this->user->getUserGroupId() == 52 || $this->user->getUserGroupId() == 53
				 || $this->user->getUserGroupId() == 54 ) { ?>
				<li class="lvl1 parent megamenu"><a href="orders">Orders</a></li>
				<?php } ?>
				<li class="lvl1 parent megamenu"><a href="profile">Profile</a></li>
				<li class="lvl1 parent megamenu"><a href="logout">Logout</a></li>									
			<?php } else { ?>
				<li class="lvl1 parent megamenu"><a href="home">Login</a></li>
			<?php } ?>
		</ul>
	</div>
	<!--End Mobile Menu-->
    
    <!--Body Content-->
    <div id="page-content">
    	<!--Home slider-->
    	<div class="slideshow slideshow-wrapper pb-section">
        	<div class="home-slideshow">
            	<div class="slide">
                	<div class="blur-up lazyload">
                        <img class="blur-up lazyload" data-src="theme/belle/assets/images/slideshow-banners/carousel1.jpg" src="theme/belle/assets/images/slideshow-banners/skin1.jpg" alt="Shop Our New Product" title="Shop Our New Product" />
                    </div>
                </div>
                <div class="slide">
                	<div class="blur-up lazyload">
                        <img class="blur-up lazyload" data-src="theme/belle/assets/images/slideshow-banners/carousel2.jpg" src="theme/belle/assets/images/slideshow-banners/skin2.jpg" alt="Shop Our New Product" title="Shop Our New Product" />
                    </div>
                </div>
                <div class="slide">
                	<div class="blur-up lazyload">
                        <img class="blur-up lazyload" data-src="theme/belle/assets/images/slideshow-banners/carousel3.jpg" src="theme/belle/assets/images/slideshow-banners/skin2.jpg" alt="Shop Our New Product" title="Shop Our New Product" />
                    </div>
                </div>
            </div>
        </div>
<!--End Home slider-->
<!--Shop the Latest & Greatest Shoes-->
        <div class="section">
        	<div class="container">
            	<div class="row">
                	<div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="section-header text-center">
                            <h2 class="h2">Product Categories</h2>
                            <!--<p>Shop The most popular shoes from belle store</p>-->
                        </div>
                    </div>
            	</div>
                <div class="grid-products grid-products-hover-btn">
	                <div class="productSlider-style1">
                        <div class="col-12 col-sm-12 col-md-12 item grid-view-item style2">
                        	<div class="grid-view_image">
                                <!-- start product image -->
                                <a href="shop" class="grid-view-item__link">
                                    <!-- image -->
                                    <img class="grid-view-item__image primary blur-up lazyload" data-src="theme/belle/assets/images/product-images/categories1.jpg" src="theme/belle/assets/images/product-images/categories1.jpg" alt="image" title="product">
                                    <!-- End image -->
                                    <!-- Hover image -->
                                    <img class="grid-view-item__image hover blur-up lazyload" data-src="theme/belle/assets/images/product-images/categories1.jpg" src="theme/belle/assets/images/product-images/categories1.jpg" alt="image" title="product">
                                    <!-- End hover image -->
                                </a>
                                <!-- end product image -->
                                <!--start product details -->
                                <div class="product-details hoverDetails mobile">
									<!-- Start product button -->
										<form class="variants add">
											<a href="shop#product" class="button btn-addto-cart text-center" type="button" tabindex="0">View More</a>
										</form>
									<!-- end product button -->
                                </div><br>
								<div class="product-details text-center">
                                                <!-- product name -->
                                                <div class="product-name">
                                                    <a href="#">Wholesaler Package</a>
                                                </div>
                                                <!-- End product name -->
                                </div>
                                <!-- End product details -->
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 item grid-view-item style2">
                        	<div class="grid-view_image">
                                <!-- start product image -->
                                <a href="shop" class="grid-view-item__link">
                                    <!-- image -->
                                    <img class="grid-view-item__image primary blur-up lazyload" data-src="theme/belle/assets/images/product-images/categories2.jpg" src="theme/belle/assets/images/product-images/categories2.jpg" alt="image" title="product">
                                    <!-- End image -->
                                    <!-- Hover image -->
                                    <img class="grid-view-item__image hover blur-up lazyload" data-src="theme/belle/assets/images/product-images/categories2.jpg" src="theme/belle/assets/images/product-images/categories2.jpg" alt="image" title="product">
                                    <!-- End hover image -->
                                </a>
                                <!-- end product image -->
                                <!--start product details -->
                                <div class="product-details hoverDetails text-center mobile">
                                    <!-- Start product button -->
										<form class="variants add">
											<a href="shop#product" class="button btn-addto-cart text-center" type="button" tabindex="0">View More</a>
										</form>
									<!-- end product button -->
                                </div><br>
								<div class="product-details text-center">
									<!-- product name -->
									<div class="product-name">
										<a href="#">City Distributor Package</a>
									</div>
									<!-- End product name -->
                                </div>
                                <!-- End product details -->
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 item grid-view-item style2">
                        	<div class="grid-view_image">
                                <!-- start product image -->
                                <a href="shop" class="grid-view-item__link">
                                    <!-- image -->
                                    <img class="grid-view-item__image primary blur-up lazyload" data-src="theme/belle/assets/images/product-images/categories3.jpg" src="theme/belle/assets/images/product-images/categories3.jpg" alt="image" title="product">
                                    <!-- End image -->
                                    <!-- Hover image -->
                                    <img class="grid-view-item__image hover blur-up lazyload" data-src="theme/belle/assets/images/product-images/categories3.jpg" src="theme/belle/assets/images/product-images/categories3.jpg" alt="image" title="product">
                                    <!-- End hover image -->
                                </a>
                                <!-- end product image -->
                                <!--start product details -->
                                <div class="product-details hoverDetails text-center mobile">
                                    <!-- Start product button -->
										<form class="variants add">
											<a href="shop#product" class="button btn-addto-cart text-center" type="button" tabindex="0">View More</a>
										</form>
									<!-- end product button -->
                                </div><br>
								<div class="product-details text-center">
									<!-- product name -->
									<div class="product-name">
										<a href="#">Retail</a>
									</div>
									<!-- End product name -->
                                </div>
                                <!-- End product details -->
                            </div>
                        </div>
						<div class="col-12 col-sm-12 col-md-12 item grid-view-item style2">
                        	<div class="grid-view_image">
                                <!-- start product image -->
                                <a href="shop" class="grid-view-item__link">
                                    <!-- image -->
                                    <img class="grid-view-item__image primary blur-up lazyload" data-src="theme/belle/assets/images/product-images/categories4.jpg" src="theme/belle/assets/images/product-images/categories4.jpg" alt="image" title="product">
                                    <!-- End image -->
                                    <!-- Hover image -->
                                    <img class="grid-view-item__image hover blur-up lazyload" data-src="theme/belle/assets/images/product-images/categories4.jpg" src="theme/belle/assets/images/product-images/categories4.jpg" alt="image" title="product">
                                    <!-- End hover image -->
                                </a>
                                <!-- end product image -->
                                <!--start product details -->
                                <div class="product-details hoverDetails text-center mobile">
                                    <!-- Start product button -->
										<form class="variants add">
											<a href="shop#product" class="button btn-addto-cart text-center" type="button" tabindex="0">View More</a>
										</form>
									<!-- end product button -->
                                </div><br>
								<div class="product-details text-center">
									<!-- product name -->
									<div class="product-name">
										<a href="#">Reseller Package</a>
									</div>
									<!-- End product name -->
                                </div>
                                <!-- End product details -->
                            </div>
                        </div>
                	</div>
                </div>
            </div>
        </div>
        <!--End Shop the Latest & Greatest Shoes-->
		<!--Weekly Bestseller-->
	<form action="" method="post" id="form" enctype="multipart/form-data">
		<input type="hidden" name="task" id="task" value="">
		<div class="section">
			<div class="container">
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="section-header text-center">
							<h2 class="h2">All Products</h2>
							<p>Our most delicious products based on sales</p>
						</div>
						<div class="productSlider grid-products" id="product">
	<!--======================================================================================================-->
				<?php if($this->user->isLogged()){ ?>
					<?php if($this->user->getUserGroupId() == 12 || $this->user->getUserGroupId() == 13 || $this->user->getUserGroupId() == 36 || $this->user->getUserGroupId() == 39 || $this->user->getUserGroupId() == 41 || $this->user->getUserGroupId() == 42
					 || $this->user->getUserGroupId() == 43 || $this->user->getUserGroupId() == 45 || $this->user->getUserGroupId() == 46 || $this->user->getUserGroupId() == 47 || $this->user->getUserGroupId() == 49 || $this->user->getUserGroupId() == 52 || $this->user->getUserGroupId() == 53
					 || $this->user->getUserGroupId() == 54 || $this->user->getUserGroupId() == 56 || $this->user->getUserGroupId() == 57) { ?>
							<?php foreach($items as $item) { ?>
								<div class="col-12 item">
									<!-- start product image -->
									<div class="product-image">
										<!-- start product image -->
										<a class="grid-view-item__link" href="item/<?php echo $item['item_id']; ?>">
											<!-- image -->
											<img src="image/products/product<?php echo $item['item_id']."_main.".$item['main_extension'];?>" alt="image" title="product">
											<!-- End image -->
										</a>
										<!-- end product image -->
									</div>
									<!-- end product image -->

									<!--start product details -->
									<div class="product-details text-center">
										<!-- product name -->
										<div class="product-name">
											<a href="item/<?php echo $item['item_id']; ?>">
												<?php $length = strlen($item['item_name']);?>
													<?php if($length > 20) { ?>
														<h3><b><?php echo substr($item['item_name'],0,20)."...";?></b></h3>
													<?php } else { ?>
														<h3><b><?php echo substr($item['item_name'],0,20);?></b></h3>
												<?php } ?>
											</a>
										</div>
										<!-- End product name -->
										<!-- product price -->
										<div class="product-price">
											<span class="price">Php <?php echo number_format($item['price'],2);?></span>
										</div>
										<!-- End product price -->
										<div class="product-review">
												<i class="font-13 fa fa-star"></i>
												<i class="font-13 fa fa-star"></i>
												<i class="font-13 fa fa-star"></i>
												<i class="font-13 fa fa-star"></i>
												<i class="font-13 fa fa-star"></i>
										</div>
										<a href="item/<?php echo $item['item_id'];?>" class="button btn-addto-cart" type="button" tabindex="0"><i class="fa fa-plus"></i>&nbsp;Add to Cart</a>
										<!-- End Variant -->
									</div>
									<!-- End product details -->
								</div>
							<?php } ?>
						<?php } ?>
						<?php } else { ?>
							<?php foreach($itempackage as $ip) { ?>
									<div class="col-12 item">
										<!-- start product image -->
										<div class="product-image">
											<!-- start product image -->
											<a class="grid-view-item__link" href="item/<?php echo $ip['item_id']; ?>">
												<!-- image -->
												<img src="image/products/product<?php echo $ip['item_id']."_main.".$ip['main_extension'];?>" alt="image" title="product">
												<!-- End image -->
											</a>
											<!-- end product image -->
										</div>
										<!-- end product image -->

										<!--start product details -->
										<div class="product-details text-center">
											<!-- product name -->
											<div class="product-name">
												<a href="item/<?php echo $ip['item_id']; ?>">
													<?php $length = strlen($ip['item_name']);?>
														<?php if($length > 20) { ?>
															<h3><b><?php echo substr($ip['item_name'],0,20)."...";?></b></h3>
														<?php } else { ?>
															<h3><b><?php echo substr($ip['item_name'],0,20);?></b></h3>
													<?php } ?>
												</a>
											</div>
											<!-- End product name -->
											<!-- product price -->
											<div class="product-price">
												<span class="price">Php <?php echo number_format($ip['price'],2);?></span>
											</div>
											<!-- End product price -->
											<div class="product-review">
													<i class="font-13 fa fa-star"></i>
													<i class="font-13 fa fa-star"></i>
													<i class="font-13 fa fa-star"></i>
													<i class="font-13 fa fa-star"></i>
													<i class="font-13 fa fa-star"></i>
											</div>
											<a href="item/<?php echo $ip['item_id'];?>" class="button btn-addto-cart" type="button" tabindex="0"><i class="fa fa-plus"></i>&nbsp;Add to Cart</a>
											<!-- End Variant -->
										</div>
										<!-- End product details -->
									</div>
							<?php } ?>
						<?php } ?>	
					
	<!--======================================================================================================-->													
						</div>
					</div>
				</div>    
			</div>
		</div>
	</form>
	<!--======================================================================================================-->
		<!--Weekly Bestseller-->
    </div>
    <!--End Body Content-->
    <!--======================================================================================================-->
    <!--Footer-->
    <footer id="footer" class="footer-2">
                <hr>
                <div class="footer-bottom">
                	<div class="row">
                    	<!--Footer Copyright-->
	                	<div class="col-12 col-sm-12 col-md-12 col-lg-12 order-1 order-md-0 order-lg-0 order-sm-1 copyright text-center">
						<a href="#"><img src="image/homelogo.png"></a>
						<p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved</p>
						</div>
                        <!--End Footer Copyright-->
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--End Footer-->
    <!--Scoll Top-->
    <span id="site-scroll"><i class="icon anm anm-angle-up-r"></i></span>
    <!--End Scoll Top-->
    
     <!-- Including Jquery -->
     <script src="theme/belle/assets/js/vendor/jquery-3.3.1.min.js"></script>
     <script src="theme/belle/assets/js/vendor/modernizr-3.6.0.min.js"></script>
     <script src="theme/belle/assets/js/vendor/jquery.cookie.js"></script>
     <script src="theme/belle/assets/js/vendor/wow.min.js"></script>
     <!-- Including Javascript -->
     <script src="theme/belle/assets/js/bootstrap.min.js"></script>
     <script src="theme/belle/assets/js/plugins.js"></script>
     <script src="theme/belle/assets/js/popper.min.js"></script>
     <script src="theme/belle/assets/js/lazysizes.js"></script>
     <script src="theme/belle/assets/js/main.js"></script>
</body>
<!-- belle/home2-default.html   11 Nov 2019 12:23:42 GMT -->
</html>