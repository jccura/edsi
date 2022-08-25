<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
			<h6 class="h1 text-white d-inline-block mb-0">&nbsp; Network</h6>
		</div>
	  </div>
	</div>
  </div>
</div>
	
<div class="container-fluid mt--6">
	<div class="row card-margin">
		<div class="col-xl-6 col-md-6">
			<div class="card btn-outline-upper border-0">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12 text-center">
							<h1 class="card-title text-uppercase text-muted mb-0 text-white"><?php echo $total_distributor; ?></h1>
							<h5 class="card-title text-uppercase text-muted mb-0 text-white">Total Distributor</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-xl-6 col-md-6">
			<div class="card btn-outline-upper border-0">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12 text-center">
							<h1 class="card-title text-uppercase text-muted mb-0 text-white"><?php echo $total_reseller; ?></h1>
							<h5 class="card-title text-uppercase text-muted mb-0 text-white">Total Reseller</h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br>
	<div class="card card-margin">	  
		<div class="card-body">
			<div class="row">
				<div class="col-md-12">	
					<form action="" method="post" enctype="multipart/form-data" id="form">
				
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">	
									<input class="form-control input" type="text" id="search_user" placeholder="Search Username" name="search_user" value="<?php if(isset($search_user)) { echo $search_user;} ?>">
								</div>				
							</div>
							<div class="col-md-2">
								<div class="form-group">	
									<input class="btn btn-primary btn" type="button" id="search" value="Search" onclick="$('form').attr('action', 'network'); $('form').submit();">
								</div>									
							</div>	
							<div class="col-md-2">
								<div class="form-group">	
									<input class="btn btn-primary text-white btn" type="button" id="add" value="Export" onclick="javascript: exportToCsv();">
								</div>									
							</div>	
						</div>
						
						<div class="row">
							<div class="col">
								<div class="card card bg-default shadow">
									<div class="card-header bg-transparent border-0">
									  <h3 class="text-white mb-0">Network List</h3>
									</div>
									<div class="table-responsive">			
										<table class="table align-items-center table-dark table-flush">
											<thead class="thead-dark">
											<tr>
												<th class="text-info">Sponsor<br>Username</th>								
												<th class="text-info">Downline<br>Username</th>
												<th class="text-info">Downline<br>Name</th>
												<th class="text-info">Level</th>
												<th class="text-info">Active?</th>
												<th class="text-info">Sales</th>
												<th class="text-info">Date Encoded</th>
	
											</tr>
											</thead>
											<tbody>
											<?php if (isset($unilevel_list)) { ?>
												<?php foreach ($unilevel_list as $uni) { ?>
													<tr>
														<td><?php echo $uni['sp_username'];?></td>
														<td><?php echo $uni['dl_username'];?></td>
														<td><?php echo $uni['dl_desc'];?></td>
														<td><?php echo $uni['level'];?></td>
														<td>
															<?php if($uni['act_flag'] == "Active") { ?>
																<center><h6 style='color:green; font-weight: bold;'><?php echo $uni['act_flag'];?></h6>
															<?php } else { ?>
																<center><h6 style='color:red; font-weight: bold;'><?php echo $uni['act_flag'];?></h6>
															<?php } ?>
														</td>
														
														<td><?php echo $uni['sales'];?></td> 
														<td><?php echo $uni['date_added'];?></td> 
														
													</tr>
												<?php } ?>	
											<?php } ?>
											</tbody>			
										</table>
									</div>
									<br>
									<ul class="pagination">
										<div class="page-link bg-transparent border-0"><?php echo $pagination; ?></div>
									</ul>
								</div>
							</div>
						</div>					
					</form>					
				</div>		
			</div>
		</div>
	</div>
</div>	 



<?php echo $footer; ?>
<script type="text/javascript">
  $(function() {
	var moveLeft = 200;
	var moveDown = 50;
	
	$('a#trigger1').hover(function(e) {
	  $('div#pop-up1').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up1').hide();
	});
	
	$('a#trigger1').mousemove(function(e) {
	  $("div#pop-up1").css('top', e.pageY + moveDown).css('left', e.pageX - moveLeft);
	});
	
	$('a#trigger2').hover(function(e) {
	  $('div#pop-up2').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up2').hide();
	});
	
	$('a#trigger2').mousemove(function(e) {
	  $("div#pop-up2").css('top', e.pageY + moveDown).css('left', e.pageX - moveLeft);
	});

	$('a#trigger3').hover(function(e) {
	  $('div#pop-up3').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up3').hide();
	});
	
	$('a#trigger3').mousemove(function(e) {
	  $("div#pop-up3").css('top', e.pageY + moveDown).css('left', e.pageX - moveLeft);
	});		

	$('a#trigger4').hover(function(e) {
	  $('div#pop-up4').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up4').hide();
	});
	
	$('a#trigger4').mousemove(function(e) {
	  $("div#pop-up4").css('top', e.pageY + moveDown).css('left', e.pageX - moveLeft);
	});

	$('a#trigger5').hover(function(e) {
	  $('div#pop-up5').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up5').hide();
	});
	
	$('a#trigger5').mousemove(function(e) {
	  $("div#pop-up5").css('top', e.pageY + moveDown).css('left', e.pageX - moveLeft);
	});

	$('a#trigger6').hover(function(e) {
	  $('div#pop-up6').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up6').hide();
	});
	
	$('a#trigger6').mousemove(function(e) {
	  $("div#pop-up6").css('top', e.pageY + moveDown).css('left', e.pageX - moveLeft);
	});
	
	$('a#trigger7').hover(function(e) {
	  $('div#pop-up7').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up7').hide();
	});
	
	$('a#trigger7').mousemove(function(e) {
	  $("div#pop-up7").css('top', e.pageY + moveDown).css('left', e.pageX - moveLeft);
	});

	$('a#trigger8').hover(function(e) {
	  $('div#pop-up8').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up8').hide();
	});
	
	$('a#trigger8').mousemove(function(e) {
	  $("div#pop-up8").css('top', e.pageY + moveDown).css('left', e.pageX - moveLeft);
	});

	$('a#trigger9').hover(function(e) {
	  $('div#pop-up9').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up9').hide();
	});
	
	$('a#trigger9').mousemove(function(e) {
	  $("div#pop-up9").css('top', e.pageY + moveDown).css('left', e.pageX - moveLeft);
	});

	$('a#trigger10').hover(function(e) {
	  $('div#pop-up10').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up10').hide();
	});
	
	$('a#trigger10').mousemove(function(e) {
	  $("div#pop-up10").css('top', e.pageY + moveDown).css('left', e.pageX - moveLeft);
	});

	$('a#trigger11').hover(function(e) {
	  $('div#pop-up11').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up11').hide();
	});
	
	$('a#trigger11').mousemove(function(e) {
	  $("div#pop-up11").css('top', e.pageY + moveDown).css('left', e.pageX - moveLeft);
	});

	$('a#trigger12').hover(function(e) {
	  $('div#pop-up12').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up12').hide();
	});
	
	$('a#trigger12').mousemove(function(e) {
	  $("div#pop-up12").css('top', e.pageY + moveDown).css('left', e.pageX - moveLeft);
	});

	$('a#trigger13').hover(function(e) {
	  $('div#pop-up13').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up13').hide();
	});
	
	$('a#trigger13').mousemove(function(e) {
	  $("div#pop-up13").css('top', e.pageY + moveDown).css('left', e.pageX - moveLeft);
	});

	$('a#trigger14').hover(function(e) {
	  $('div#pop-up14').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up14').hide();
	});
	
	$('a#trigger14').mousemove(function(e) {
	  $("div#pop-up14").css('top', e.pageY + moveDown).css('left', e.pageX - moveLeft);
	});

	$('a#trigger15').hover(function(e) {
	  $('div#pop-up15').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up15').hide();
	});
	
	$('a#trigger15').mousemove(function(e) {
	  $("div#pop-up15").css('top', e.pageY + moveDown).css('left', e.pageX - moveLeft);
	});	
	
  });
  
  function exportToCsv() {
	$('form').attr('action', 'networkexport'); 
	$('form').submit();
  }
</script>