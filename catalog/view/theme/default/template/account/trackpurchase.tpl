<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo COMPANY_NAME;?></title>
<base href="<?php echo LOCAL_PROD; ?>" />
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="icon.png" type="image/x-icon">

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/popper.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="css/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />

<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="css/font-awesome/css/font-awesome.css" />

<link rel="stylesheet" type="text/css" href="stylesheet.css" />
<script type="text/javascript" src="catalog/view/ajax/ajax.js"></script>
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>

</head>
<body>

<div class="container-fluid">

	<div class="row">
		<div class="col-md-12">
			<div class="header_box">
				<p><h1>MDP Shoppe Purchase Tracking</h1></p>
			</div>			
		</div>
	</div>

</div>

<div class="row">
	<div class="col-md-12">
		<form action="" method="post" enctype="multipart/form-data" id="form">
			<input type="hidden" id="task" name="task" />
			<input type="hidden" id="reference" name="reference" value="<?php echo $purchase_header['purchase_reference']; ?>" />

			<div class="container">
				<div class="row">

					<div class="col-md-12 mt-5">
						<div class="row mt-3 text-center">
							<div class="col-md-12"><h1><span class="text-success"><span class="display-2"><i class="fa fa-check"></i></span><br /> <?php echo $purchase_header['description']; ?></span> </h1></div>
						</div>
					</div>

				</div>
				<div class="row">
					<div class="col-md-12">

						<?php if($purchase_header['status'] == '70'){?>
							<div class="card mt-3">
							  <div class="card-header">
								<h3 class="h3"><i class="fa fa-tasks"></i> Actions</h3>
							  </div>
							  <div class="card-block">
								<div class="ml-3 mt-3 mr-3">

									<div class="form-group row">
										<h4 class="h4 col-md-4 text-right">Upload Proof of Payment: </h4>
										<div class="col-md-5">
											<input type="file" id="photo_remittance" name="photo_remittance" class="form-control" />
										</div>
										<div class="col-md-3">
											<button class="btn btn-primary" type="button" onclick="upload()"> <i class="fa fa-upload"></i> Upload</button>
										</div>
									</div>
									
								</div>
								
							  </div>
							</div>
						<?php } ?>

						<div class="row">
							<div class="col-md-12">
								<center>
									<br/>
									<a class="btn btn-primary" href="purchase/<?php echo $purchase_header['purchase_id']; ?>">Back To Purchase ID # <?php echo $purchase_header['purchase_id']; ?></a>
								</center>
							</div>
						</div>	

						<div class="card mt-3">
						  <div class="card-header">
							<?php if($this->user->getUserGroupId() == 45) { ?>
						    <h3 class="h3"><i class="fa fa-list-alt"></i> Purchase Details of <a href="purchase/<?php echo $purchase_header['purchase_id']; ?>">Purchase ID # <?php echo $purchase_header['purchase_id']; ?></a></h3>
							<?php } else { ?>
						    <h3 class="h3"><i class="fa fa-list-alt"></i> Purchase Details of Purchase ID # <?php echo $purchase_header['purchase_id']; ?></h3>							
							<?php } ?>
						  </div>
						  <div class="card-block">
						  	<div class="mt-3 ml-3 mr-3">
								<div class="row">
									<div class="col-md-12">
										<div>
											<p><h5>Supplier : <?php if(isset($supplier)){ echo $supplier; }?></h5></p>
										</div>			
									</div>
								</div>
						  		<table class="table table-bordered table-sm text-center">
									<thead>
										<th>#</th>
										<th>ITEM</th>
										<th>PRICE</th>
										<th>QTY</th>
										<th>AMOUNT</th>
									</thead>
									<tbody id="customers_list">
										<?php if(isset($purchase_details)){
											$count = 1;
											foreach($purchase_details as $o){
												?>
												<tr>
													<td><?php echo $count; ?></td>
													<td><?php echo $o['item_name']; ?></td>
													<td><?php echo number_format($o['cost'] / $o['total_qty'],2); ?></td>
													<td><?php echo $o['total_qty']; ?></td>
													<td><?php echo number_format($o['cost'],2); ?></td>
												</tr>
												<?php $count += 1;
											}
										} ?>
										
									</tbody>
								</table>

						  	</div>
						  	<hr />
						  	<div class="mt-5 ml-3 mr-3">
						  		<div class="row">
						  			<div class="col-md-12">
						  				<div class="col-md-12">
						  					<textarea class="form-control" placeholder="Add Remark / Comment" rows="3" name="remark"></textarea>
						  				</div>
						  				<div class="col-md-12 mt-3 text-center">
						  					<button class="btn btn-success" type="button" onclick="javascript:addcomment();">Add Comment</button>
						  				</div>
						  			</div>
						  			<div class="col-md-12 mt-3">
						  				<table class="table table-bordered table-sm text-center">
											<thead>
												<th>#</th>
												<th>USER</th>
												<th>COMMENT / REMARK</th>
												<th>DATE ADDED</th>
											</thead>
											<tbody id="customers_list">
												<?php if(isset($remarks)){
													$count = 1;
													foreach($remarks as $o){
														?>
														<tr>
															<td class="w-10"><?php echo $count; ?></td>
															<td class="w-20"><?php echo $o['fullname']; ?></td>
															<td class="w-50"><?php echo $o['remark']; ?></td>
															<td class="w-20"><?php echo $o['date_added']; ?></td>
														</tr>
														<?php $count += 1;
													}
												} ?>
												
											</tbody>
										</table>
						  			</div>
						  			
						  		</div>
						  	</div>
						   	
						  </div>
						</div>
						<?php if($purchase_header['status'] != '70'){?>
						<div class="col-md-12 text-center">
							<img src="purchaseimages/purchaseimages<?php echo $purchase_header['purchase_id'] . $purchase_header['extension'] ?>" class="img-fluid" />
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div id="dialog-message" title="Message" style="display:none; width: 400px;">
			  <span id="msg"></span>
			</div>			
		</form>
	</div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
	
	$(document).ready(function(){

		var height = 0;

		if($(document).height() > $(window).height()){
			height = ($(document).height() - 50);
		}else{
			height = $(document).height();
		}

	     $("#bottom-footer").offset({left: 0, top: height });

	});

</script>
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

function upload() {	
	if($('#photo_remittance').val() == "") {
		var msg = "No proof of payment picture to upload.";
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
	} else {
		var msg = "Are you sure you have provided the correct proof of payment?";
		$('#msg').html(msg);		
		$(function() {
			$( "#dialog-message" ).dialog({
			  modal: true,
			  width: 600,
			  buttons: {
				Proceed: function() {
					$('#task').val('upload');
					$('form').attr('action', "trackpurchase/<?php echo $purchase_header['purchase_reference']; ?>"); $('form').submit();
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}				
			  }
			});
		});		
	}
}

function addcomment() {
	$('#task').val('remarks');
	$('form').attr('action', "trackpurchase/<?php echo $purchase_header['purchase_reference']; ?>"); $('form').submit();
}



//--></script>							