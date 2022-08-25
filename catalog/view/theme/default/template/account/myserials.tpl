<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
			<i class="fa fa-shopping-cart ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp; My Serials</h6>
		</div>
	  </div>
	</div>
  </div>
</div>
<div class="container-fluid mt--6">
	<form action="" method="post" enctype="multipart/form-data" id="form">
		<div class="row">		
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">				
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive" style="width: 100%;" id="inventory_table" name="inventory_table">				
											<table class="table table-bordered table-hover">
											  <thead>
												<tr>
													<td class="left">Order Id</td>
													<td class="left">Serial Type</td>
													<td class="left">Serial Code</td>
													<td class="left">Ordered Through</td>
													<td class="left">Used By</td>
													<td class="left">Use Date</td>
												  	<td class="left">Date</td>		
												</tr>
											  </thead>
											  <tbody>
												<?php if (isset($myserials)) { ?>
													<?php if (sizeof($myserials) != 0) { ?>
														<?php foreach ($myserials as $ms) { ?>			
														<tr>
															<td><?php echo $ms['order_id'];?></td>
															<td><?php echo $ms['item_name'];?></td>
															<td><?php echo $ms['serial_code'];?></td>
															<td><?php echo $ms['ordered_thru'];?></td>
															<td><?php echo $ms['used_by'];?></td>
															<td><?php echo $ms['date_used'];?></td>
															<td><?php echo $ms['date_added'];?></td>
														</tr>
														<?php } ?>
													<?php }else{ ?>
														<tr>
															<td colspan="6"><div class="text-center">You don't have serials yet.</div></td></tr>
													<?php } ?>
												<?php } ?>
											  </tbody>
											</table>
											<div class="pagination"><?php echo $pagination; ?></div>
										</div>
									</div>							
								</div>
							</div>
						</div>			
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<div id="dialog-message" title="Result" style="display:none; width: 500px;">
  <span id="msg"></span>
</div>

<?php echo $footer; ?>

<script type="text/javascript"><!--

$(document).ready(function() {	
	<?php if(isset($err_msg)) { ?>
		var msg = "<?php echo $err_msg; ?>";
		$('#msg').html(msg);		
		$(function() {
			$( "#dialog-message" ).dialog({
			  modal: true,
			  width: 600,
			  buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
				}			
			  }
			});
		});			
	<?php } ?>
	
});

function search() {
	$('#task').val('search');
	$('#form').attr('action', 'inventory'); $('form').submit();
}
//--></script>						