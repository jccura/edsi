<?php echo $header; ?>
<div class="header btn-outline-upper pb-6">
  <div class="container-fluid">
	<div class="header-body">
	  <div class="row align-items-center py-4">
		<div class="col-lg-6 col-7">
			<i class="fa fa-shopping-cart ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp; Inventory</h6>
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
												  <?php if($this->user->getUserGroupId() == 66) { ?>
												  <td class="left">Branch Name</td>
												  <?php } ?>
												  <td class="left">Item Name</td>
												  <td class="left">Quantity</td>
												  <td class="left">Cost</td>										  
												  <td class="left">Total Cost</td>						
												  <td class="left">History</td>		
												</tr>
											  </thead>
											  <tbody>
												<?php if (isset($products)) { ?>
												<?php if (sizeof($products) != 0) { ?>
												<?php foreach ($products as $ew) { ?>			
												<tr>
												  <?php if($this->user->getUserGroupId() == 66) { ?>
												  <td><?php echo $ew['branch_name'];?></td>
												  <?php } ?>
												  <td><?php echo $ew['item_name'];?></td>
												  <td><?php echo $ew['qty'];?></td>
												  <td>Php <?php echo number_format($ew['price'],2);?></td>
												  <td>Php <?php echo number_format($ew['price']*$ew['qty'],2);?></td>
												  <?php if($this->user->getUserGroupId() == 66) {?>
													<td><a class="btn btn-warning" href="inventoryhist/<?php echo $ew['item_id'];?>/<?php echo $this->data['branch_id'];?>"><i class="fa fa-list"></i> History </a></td>
												  <?php } else {?>
													<td><a class="btn btn-warning" href="inventoryhist/<?php echo $ew['item_id'];?>"><i class="fa fa-list"></i> History </a></td>
												  <?php }?>
												</tr>
												<?php } ?>
												<?php }else{ ?>
													<tr>
														<td colspan="6"><div class="text-center">Sorry, you don't have inventory yet.</div></td></tr>
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
	$('#form').attr('action', 'inventory');
	$('form').submit();
}
//--></script>						