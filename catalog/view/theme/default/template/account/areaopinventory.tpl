<?php echo $header; ?>
	<div class="header btn-outline-upper pb-6">
	  <div class="container-fluid">
		<div class="header-body">
		  <div class="row align-items-center py-4">
			<div class="col-lg-6 col-7">
				<i class="ni ni-app ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp; Area Operator Inventory</h6>
			</div>
		  </div>
		</div>
	  </div>
	</div>
	<div class="container mt--6">		
		<form action="" method="post" enctype="multipart/form-data" id="form">
			<input type="hidden" id="task" name="task" value=""/>
			<div class="card card-margin">	  
				<div class="card-body">
					<div class="row">
						<div class="col-md-4"></div>
							<div class="col-md-4">
								<label>Area Operator:</label>
								<select class="form-control input" id="city_dist" name="city_dist" >
									<option value="0"> Select Area Operator</option>
									<?php if(isset($areaoperator)) { ?>
										<?php foreach($areaoperator as $ao) { ?>
												<option value="<?php echo $ao['user_id']?>"><?php echo $ao['username']?> (<?php echo $ao['firstname']." ".$ao['lastname'];?>)</option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						<div class="cold-md-4"></div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12">
							<center>
								<input class="btn btn-outline-user text-white btn" type="button" id="search" value="Search" onclick="javascript:submitTask('search');"/>	
								<input class="btn btn-outline-user text-white" type="button" id="btnSearch" name="btnExport" value="Export" onclick="javascript: exportToCsv()"/>
						</div>
					</div>
				</div>
			</div>		
			<br/>			
			<div class="card card-margin">
				<div class="card-body">
					<div class="table-responsive" style="width: 100%; overflow: auto;">			
						<table class="table table-striped table-bordered table-hover">
						  <thead>
							<tr class="table-primary">
							  <th class="text-dark">Area Operator</th>	
							  <th class="text-dark">Item(Raw)</th>
							  <th class="text-dark">Quantity</th>
							</tr>
						  </thead>
						  <tbody>	
							<?php if (isset($operatorinventory)) { ?>
							<?php foreach ($operatorinventory as $oi) { ?>			
							<tr>
							  <td><?php echo $oi['username'];?>(<?php echo $oi['ao_name'];?>)</td>
							  <td><?php echo $oi['item_name'];?></td>
							  <td><?php echo $oi['qty'];?></td>
							</tr>
							<?php } ?>
							<?php } ?>
						  </tbody>
						</table>
					</div>
					<div class="pagination"><?php echo $pagination; ?></div>
				</div>
			</div>
			</br>
		</form>		
	</div>

<div id="dialog-message" title="Message" style="display:none; width:400px;">
	<span id="msg"></span>
</div>

<?php echo $footer; ?>
<script type="text/javascript"><!--
var selected = [];
$(document).ready(function() {
	<?php if(isset($err_msg)) { ?>
		<?php if(!empty($err_msg)) { ?>
			var msg = "<?php echo $err_msg; ?>";
			swal("Good Job!", msg, "success");
		<?php } ?>
	<?php } ?>
});

function submitTask(task) {
	$('#task').val(task);
	$('form').attr('action', 'areaopinventory');
	$('form').submit();
}

function exportToCsv() {
	$('form').attr('action', 'areainventoryexport'); 
	$('form').submit();
}

//--></script>