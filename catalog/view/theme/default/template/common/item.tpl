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
	<link rel="icon" href="image/edsiicon2.png" type="image/x-icon">
	<!-- Plugins CSS -->
	<link rel="stylesheet" href="theme/belle/assets/css/plugins.css">
	<!-- Bootstap CSS -->
	<link rel="stylesheet" href="theme/belle/assets/css/bootstrap.min.css">
	<!-- Main Style CSS -->
	<link rel="stylesheet" href="theme/belle/assets/css/style.css">
	<link rel="stylesheet" href="theme/belle/assets/css/responsive.css">
</head>
<body class="template-product belle">
	<div class="pageWrapper">
		<!--Header-->
		<div class="header-wrap animated d-flex border-bottom" style="background-color:#2D2D2D">
			<div class="container-fluid" style="background-color:#2D2D2D">        
				<div class="row align-items-center">
					<!--Desktop Logo-->
					<div class="logo col-md-2 col-lg-2 d-none d-lg-block" >
						<a href="shop">
							<img src="image/2.png" alt="Esthetique Direct Sale Inc." title="Esthetique Direct Sales Inc." style="width:90px; height:90px;"/>
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
									<li class="lvl1 parent megamenu"><a href="shop">Shop <?php if(isset($cart['with_package'])) { echo $cart['with_package']; } ?></a></li>
									<?php if($this->user->isLogged()){ ?>
										<?php if($this->user->getUserGroupId() == 12 || $this->user->getUserGroupId() == 13 || $this->user->getUserGroupId() == 36 || $this->user->getUserGroupId() == 39 || $this->user->getUserGroupId() == 41 || $this->user->getUserGroupId() == 42
										 || $this->user->getUserGroupId() == 43 || $this->user->getUserGroupId() == 45 || $this->user->getUserGroupId() == 46 || $this->user->getUserGroupId() == 47 || $this->user->getUserGroupId() == 49 || $this->user->getUserGroupId() == 52 || $this->user->getUserGroupId() == 53
										 || $this->user->getUserGroupId() == 54 ) { ?>
										<li class="lvl1 parent megamenu"><a href="orders">Orders</a></li>
										<?php } ?>
										<li class="lvl1 parent megamenu"><a href="profile">Profile</a></li>
										<li class="lvl1 parent megamenu"><a href="logout">Logout (<?php echo $this->user->getUsername(); ?>)</a></li>									
									<?php } else { ?>
										<li class="lvl1 parent megamenu"><a href="home">Login</a></li>
										<li class="lvl1 parent megamenu"><a href="register">Register</a></li>
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
								<img style="width:50%; height:auto;" src="image/logo6.png" alt="Esthetique Direct Sales Inc." title="Esthetique Direct Sales Inc." />
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
					<li class="lvl1 parent megamenu"><a href="logout">Logout  (<?php echo $this->user->getUsername(); ?>)</a></li>									
				<?php } else { ?>
					<li class="lvl1 parent megamenu"><a href="home">Login</a></li>
				<?php } ?>
			</ul>
		</div>
		<!--End Mobile Menu-->
        
		<!--Body Content-->
		<form action="" method="post" id="form" enctype="multipart/form-data">
			<input type="hidden" name="task" id="task" value="">
			<?php $order_id = 0;
				if(isset($this->session->data['order_id'])) { 
					$order_id = $this->session->data['order_id']; 
				}
				
				if(isset($cart['header']['order_id'])) {
					$order_id = $cart['header']['order_id'];
				}
			?>
			<div id="page-content">
				<!--MainContent-->
				<div id="MainContent" class="main-content" role="main">              
					<div id="ProductSection-product-template" class="product-template__container prstyle1 container">
						<!--product-single-->
						<div class="product-single">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12 col-12"><br>
									<div class="product-details-img">
										<div class="product-thumb">
											<div id="gallery" class="product-dec-slider-2 product-tab-left">
												<?php for($i=1; $i<=10; $i++) { ?>
													<?php if($item['flag_'.$i] == 1) { ?>
														<a data-image="image/products/product<?php echo $item['item_id'];?>_<?php echo $i; ?>.<?php echo $item['extension_'.$i];?>" data-zoom-image="image/products/product<?php echo $item['item_id'];?>_<?php echo $i; ?>.<?php echo $item['extension_'.$i];?>" class="slick-slide slick-cloned" data-slick-index="-4" aria-hidden="true" tabindex="-1">
															<img class="blur-up lazyload" src="image/products/product<?php echo $item['item_id'];?>_<?php echo $i; ?>.<?php echo $item['extension_'.$i];?>" alt="" />
														</a>
													<?php } ?> 
												<?php } ?>
											</div>
										</div>
										<div class="zoompro-wrap product-zoom-right pl-20">
											<div class="zoompro-span">
												<img class="zoompro blur-up lazyload" data-zoom-image="image/products/product<?php echo $item['item_id']."_main.".$item['main_extension'];?>" alt="" src="image/products/product<?php echo $item['item_id']."_main.".$item['main_extension'];?>"/>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-12">
									<div class="product-single__meta"><br>
										<h1 class="product-single__title"><b><?php echo $item['item_name'];?></b></h1>
										<div class="prInfoRow">
											<div class="product-review"><a class="reviewLink" href="#tab2"><i class="font-13 fa fa-star"></i><i class="font-13 fa fa-star"></i><i class="font-13 fa fa-star"></i><i class="font-13 fa fa-star"></i><i class="font-13 fa fa-star"></i></a></div>
										</div>
										<p class="product-single__price product-single__price-product-template">
											<span class="product-price__price product-price__price-product-template product-price__sale product-price__sale--single">
												<span id="amount" name="amount"><span class="money">Php <?php echo number_format($item['price'],2);?></span></span>
											</span> 
										</p>
									</div><br>
									<div class="product-single__description rte">
										<p><?php echo html_entity_decode($item['short_description'], ENT_QUOTES, 'UTF-8');?></p>
										<h5>Categories: <?php echo $item['category'];?></h5>
									</div>                                       
									<form method="post" accept-charset="UTF-8" class="product-form product-form-product-template hidedropdown" enctype="multipart/form-data">
										<!-- Product Action -->
										<div class="product-action clearfix">
											<div class="product-form__item--quantity">
												<div class="wrapQtyBtn">
													<div class="qtyField">
														<a class="qtyBtn minus" href="javascript:void(0);"><i class="fa anm anm-minus-r" aria-hidden="true"></i></a> 
														<input type="text" id="quantity" name="quantity" value="1" class="product-form__input qty" min="1">
														<a class="qtyBtn plus" href="javascript:void(0);"><i class="fa anm anm-plus-r" aria-hidden="true"></i></a>
													</div>
												</div>
											</div>																													
											<div class="product-form__item--submit">
												<input type="hidden" name="item_id" id="item_id" value="<?php echo $item['item_id'];?>">
												<input type="hidden" name="order_id" id="order_id" value="<?php echo $order_id; ?>">
												<a class="btn btn-edblue  btn-lg" onclick="addToCart()" href="javascript:void(0);">
												Add to cart</a>
											</div>
										</div>
										<!-- End Product Action -->
									</form>
									<div class="display-table shareRow">
										<div class="display-table-cell">
											<div class="social-sharing">
												<a href="#" class="btn btn--small btn--secondary btn--share share-facebook" title="Share on Facebook">
													<i class="fa fa-facebook-square" aria-hidden="true"></i> <span class="share-title" aria-hidden="true">Share</span>
												</a>
												<a href="#" class="btn btn--small btn--secondary btn--share share-twitter" title="Tweet on Twitter">
													<i class="fa fa-twitter" aria-hidden="true"></i> <span class="share-title" aria-hidden="true">Tweet</span>
												</a>
												<a href="#" title="Share on google+" class="btn btn--small btn--secondary btn--share" >
													<i class="fa fa-google-plus" aria-hidden="true"></i> <span class="share-title" aria-hidden="true">Google+</span>
												</a>
												<a href="#" class="btn btn--small btn--secondary btn--share share-pinterest" title="Share by Email" target="_blank">
													<i class="fa fa-envelope" aria-hidden="true"></i> <span class="share-title" aria-hidden="true">Email</span>
												</a>
											 </div>
										</div>
									</div>                                       
								</div>
							</div>
						</div>
						<!--End-product-single-->
						<!--Product Tabs-->
						<div class="tabs-listing">
							<ul class="product-tabs">
								<li rel="tab1"><a class="tablink">Product Details</a></li>
								<li rel="tab2"><a class="tablink">Reviews</a></li>
							</ul>
							<div class="tab-container">
								<div id="tab1" class="tab-content">
									<div class="product-description rte">
										<p><?php echo html_entity_decode($item['description'], ENT_QUOTES, 'UTF-8');?></p>
									</div>
								</div>                           
								<div id="tab2" class="tab-content">
									<div id="shopify-product-reviews">
										<div class="spr-container">
											<div class="spr-content">
												<div class="spr-reviews">
													<h1>Customer Reviews</h1>
													<?php if (isset($reviews)) { ?>
														<?php foreach ($reviews as $review) { ?>
														<div class="spr-review">
															<div class="spr-review-header">
																<span class="product-review spr-starratings spr-review-header-starratings"><span class="reviewLink"><i class="fa fa-star"></i><i class="font-13 fa fa-star"></i><i class="font-13 fa fa-star"></i><i class="font-13 fa fa-star"></i><i class="font-13 fa fa-star"></i></span></span>
																<p class="spr-review-header-title">by: <?php echo $review['reviewed_by']; ?></p>
																<span class="spr-review-header-byline"><strong>Posted</strong> on <strong><?php echo date("F j, Y, g:i a", strtotime($review['date_added']));?></strong></span>
															</div>
															<div class="spr-review-content">
																<p class="spr-review-content-body"><?php echo html_entity_decode($review['review'], ENT_QUOTES, 'UTF-8'); ?></p>
															</div>
															<br/>
															<img src="image/reviews/review<?php echo $review['review_id'];?>.<?php echo $review['main_extension'] ?>" class="img-responsive" style="width:100px; height:100px;">
														</div>
															<?php } ?>
													<?php } ?>
												</div>
												<div class="pagination"><?php echo $pagination; ?></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--End Product Tabs-->
					</div>
					<!--#ProductSection-product-template-->
				</div>
				<!--MainContent-->
			</div>
		</form>
		<!--End Body Content-->
		
    
		<!--Footer-->
		<footer id="footer" class="footer-2">
					<hr>
					<div class="footer-bottom">
						<div class="row">
							<!--Footer Copyright-->
							<div class="col-12 col-sm-12 col-md-12 col-lg-12 order-1 order-md-0 order-lg-0 order-sm-1 copyright text-center"><span></span>
								<a href="#"><img src="image/homelogo.gif"></a>
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
     <script src="theme/belle/assets/js/vendor/jquery.cookie.js"></script>
     <script src="theme/belle/assets/js/vendor/modernizr-3.6.0.min.js"></script>
     <script src="theme/belle/assets/js/vendor/wow.min.js"></script>
     <!-- Including Javascript -->
     <script src="theme/belle/assets/js/bootstrap.min.js"></script>
     <script src="theme/belle/assets/js/plugins.js"></script>
     <script src="theme/belle/assets/js/popper.min.js"></script>
     <script src="theme/belle/assets/js/lazysizes.js"></script>
     <script src="theme/belle/assets/js/main.js"></script>
     <!-- Photoswipe Gallery -->
     <script src="theme/belle/assets/js/vendor/photoswipe.min.js"></script>
     <script src="theme/belle/assets/js/vendor/photoswipe-ui-default.min.js"></script>
	 
	 <script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.11.2.min.js"></script>
	 <script src="theme/regal/vendors/jquery-bar-rating/jquery.barrating.min.js"></script>
	 <script src="css/jquery-ui.js"></script>
	 <script type="text/javascript">
	 
        $(function(){
            var $pswp = $('.pswp')[0],
                image = [],
                getItems = function() {
                    var items = [];
                    $('.lightboximages a').each(function() {
                        var $href   = $(this).attr('href'),
                            $size   = $(this).data('size').split('x'),
                            item = {
                                src : $href,
                                w: $size[0],
                                h: $size[1]
                            }
                            items.push(item);
                    });
                    return items;
                }
            var items = getItems();
        
            $.each(items, function(index, value) {
                image[index]     = new Image();
                image[index].src = value['src'];
            });
            $('.prlightbox').on('click', function (event) {
                event.preventDefault();
              
                var $index = $(".active-thumb").parent().attr('data-slick-index');
                $index++;
                $index = $index-1;
        
                var options = {
                    index: $index,
                    bgOpacity: 0.9,
                    showHideOpacity: true
                }
                var lightBox = new PhotoSwipe($pswp, PhotoSwipeUI_Default, items, options);
                lightBox.init();
            });
        });
		
		function addToCart() {

		// var valid = 1;
		// var msg = "";
		// if(with_package != 0){
			// valid = 0;
			// msg = "test";
		// }
			// if(valid == 0){
				// alert(msg);
			// }else {
				$('#task').val('addToCart');
				$('form').attr('action', 'cart'); 
				$('form').submit();
			// }
		 }
		
		function processTask(task) {
			$('#task').val(task); 
			$('form').attr('action', 'item');
			$('form').submit();
		}
	 </script>
    </div>

	<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        	<div class="pswp__bg"></div>
            <div class="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div><div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter"></div><button class="pswp__button pswp__button--close" title="Close (Esc)"></button><button class="pswp__button pswp__button--share" title="Share"></button><button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button><button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class="pswp__share-tooltip"></div></div><button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button><button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div></div>
	</div>

</body>

<!-- belle/short-description.html   11 Nov 2019 12:43:10 GMT -->
</html>