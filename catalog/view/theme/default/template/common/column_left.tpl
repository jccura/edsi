<div class="row">

	<div class="col-md-3">
		<div class="row">
			<div class="col-md-12" align="center">
				<a href="index.php?route=<?php echo $this->user->getRedirectPage();?>" align="center"><img src="image/mainmenu.png" alt="" width="120px" height="120px" /></a>
				<a href="index.php?route=account/logout" align="center"><img src="image/logout.png" alt="" width="120px" height="120px" /></a>
			</div>
		</div>
		
		<div class="row-offcanvas row-offcanvas-left">
			<div id="sidebar" class="sidebar-offcanvas">
				<div class="col-md-12">
					<ul class="nav nav-pills nav-stacked">
					<?php if (isset($modules)) { ?>
					<?php foreach ($modules as $modules) { ?>					
						<li><a class="btn btn-primary btn-lg" href="index.php?route=<?php echo $modules['module']?>" align="center"><?php echo $modules['description']?></a></li>
					<?php } ?>
					<?php } ?>						
					</ul>
				</div>			
			</div>
		</div>							

	</div>

