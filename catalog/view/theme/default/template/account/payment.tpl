<?php echo $header; ?>
<div class="container-scroller">

	<form action="" method="post" enctype="multipart/form-data" id="form">
		<input type="hidden" id="task" name="task" value=""> 
		<input type="hidden" name="reference" id="reference">
		
		<div class="row">
			<div class="col-sm-12 mb-4 mb-xl-0">
				<h4 class="font-weight-bold text-dark">Orderlist</h4>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">
						
							<h3 class="card-title"><i class="fa fa-filter"></i> Filter</h3>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fa fa-tasks"></i></span>
											</div>
												<input class="form-control input" type="text" value="" placeholder="Order ID" id="order_id_search" name="order_id_search" title="Order ID">
										</div>											
									</div>											
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fa fa-tasks"></i></span>
											</div>
												<input class="form-control input" type="text" value="" placeholder="Customer Name" id="cust_name_search" name="cust_name_search" title="Customer Name">
										</div>											
									</div>	
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fa fa-calendar"></i></span>
											</div>
												<input class="form-control input" value=""  type="date" id="datefrom_search" placeholder="Order Date From" name="datefrom_search" title="Order Date From" >
										</div>											
									</div>
								</div>		
							</div>
							<div class="row">							
								<div class="col-md-4">
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fa fa-calendar"></i></span>
											</div>
												<input class="form-control input" type="date" value=""  id="dateto_search" placeholder="Order Date To" name="dateto_search" title="Order Date To" >
										</div>											
									</div>
								</div>	
								<div class="col-md-4">
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fa fa-tasks"></i></span>
											</div>
												<select class="form-control input" id="rider" name="rider">
											<option value="" readonly>Select Rider</option>
												<?php if(isset($riders)){
													foreach($riders as $ps){ ?>
														<option value="<?php echo $ps['user_id']; ?>"><?php echo $ps['name']; ?></option>
													<?php 
													}
												} ?>												
										</select>
										</div>											
									</div>												
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="col-md-2 pull-right">
									<input type="hidden" id="order_status" name="order_status" value="" />
									<button class="btn btn-info btn-block" type="button" onclick="javascript: create();"> <i class="fa fa-paper-plane "></i> Create Ride </button>	
								</div>
								<div class="col-md-2 pull-right">
									<button class="btn btn-primary btn-block" type="button" onclick="javascript: performTask('search');"> <i class="fa fa-search "></i> Search </button>											
								</div>			
							</div>			
						</div>
					</div>
					
					<br/>
					
					<div class="col-md-12">
						<div class="row">
							<div class="table-responsive" style="overflow: auto;">				
								<table class="table table-bordered table-hover">
									<thead>
										<tr class="table-primary">
											<td class="left">&nbsp;</td>
											<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
											<td class="left">Order ID</td>
											<td class="left">Branch Name</td>
											<td class="left">Admin Name</td>
											<td class="left">Affiliate Name</td>
											<td class="left">Distributor Name</td>
											<td class="left">Customer Name</td>
											<td class="left">Status</td>
											<td class="left">Shipping Fee</td>
											<td class="left">Items</td>
											<td class="left">Quantity</td>
											<td class="left">Total</td>
											<td class="left">Date Added</td>
											<td class="left">Requested Date Delivery</td>
											<td class="left">Latest Remark</td>
										</tr>
									</thead>
									<tbody>
										<?php if (isset($order_list)) { ?>
											<?php foreach ($order_list as $order) { ?>
												<tr>
													<td class="left">
														<a class="btn btn-info btn-block" type="button" href="trackorder/<?php echo $order['reference'];?>" target="_blank"> <i class="fa fa-history"></i> Track Order</a>
													</td>
													<td style="text-align: center;">
														<input type="checkbox" name="selected[]" value="<?php echo $order['order_id'];?>"/>
													</td>
													<td class="left"><?php echo $order['order_id'] ?></td>
													<td class="left"><?php echo $order['branch'] ?></td>
													<td class="left"><?php echo $order['admin'] ?></td>
													<td class="left"><?php echo $order['affiliate'] ?></td>
													<td class="left"><?php echo $order['distributor'] ?></td>
													<td class="left"><?php echo $order['customer_fullname'] ?></td>
													<td class="left"><?php echo $order['status'] ?></td>
													<td class="left"><?php echo $order['shipping_fee'] ?></td>
													<td class="left"><?php echo $order['item_details'] ?></td>
													<td class="left"><?php echo $order['quantity'] ?></td>
													<td class="left"><?php echo $order['total'] ?></td>
													<td class="left"><?php echo $order['date_added'] ?></td>
													<td class="left"><?php echo $order['requested_delivery_date'] ?></td>
													<td class="left"><?php echo $order['remarks'] ?></td>
												</tr>		
											<?php } ?>
										<?php } ?>
									</tbody>
								</table>
							</div>
							<div class="pagination"><div class="results"><?php echo $pagination; ?></div></div>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</form>	
</div>

<div id="dialog-message" title="Message" style="display:none; width: 600px;">
  <span id="msg"></span>
</div>

<?php echo $footer; ?>

<script type="text/javascript"><!--
$(document).ready(function() {
    
	<?php if(isset($err_msg)) { ?>
    var msg = "<?php echo $err_msg; ?>";
    $('#msg').html(msg);
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
});

function performTask(task) {

	$('#task').val(task);
	$('form').attr('action', 'orderlistforcod'); 
	$('form').submit();
	
}

function create() {
	var rider = $('#rider').val();
	var msg = "Are you sure you want to assign the selected order(s) to the Rider?";
	var proceed = 1;
	$('#msg').html(msg);
		$(function() {
			$( "#dialog-message" ).dialog({
			  modal: true,
			  width: 600,
			  buttons: {
				Ok: function() {
					if(rider == ""){
						msg = "Error! You must select a rider first to continue.";
						proceed = 0;
					}

					if(proceed == 1){
						$('#task').val("create1");					
						$('form').attr('action', 'orderlistforcod'); 
						$('form').submit();		
					}else{
						alert(msg.replace(/\\n/g,"\n"));
					}
					
					$( this ).dialog( "close" );
					
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}			
			  }	
			});
		});
	
	
	
}
//--></script>							