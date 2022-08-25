<?php echo $header; ?>
	<div class="header btn-outline-upper pb-6">
	  <div class="container-fluid">
		<div class="header-body">
		  <div class="row align-items-center py-4">
			<div class="col-lg-6 col-7">
				<i class="ni ni-app ni-2x text-white"></i><h6 class="h1 text-white d-inline-block mb-0">&nbsp; Expenses</h6>
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
				
				<!-- Modal for new Expense Record -->
					<div id="expense-modal" class="modal" role="dialog">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
							  <div class="modal-header bg-avgreen text-white">
								  <h5 class="modal-title  text-white" id="exampleModalLabel"> Add Expense Record</h5>
								  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span class="text-white" aria-hidden="true">&times;</span>
								  </button>
							  </div>
							  <div class="modal-body">
							  
								<div class="col-md-12">
									<div class="row">	
										<div class="col-md-3">
											<label>Expense Type:</label>
											<select class="form-control input" id="exp_type" name="exp_type" onchange="javascript: showUpload();">
												<option value="">Select Expense Type</option>
												<?php if(isset($exp_type)) {?>
													<?php foreach($exp_type as $et) { ?>
														<option value="<?php echo $et['expenses_type_id'];?>"><?php echo $et['description']; ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
										</br>
										<div class="col-md-3">
											<label>Name:</label>
											<input type="text" class="form-control" id="exp_user" name="exp_user" value="" placeholder="Full Name">
										</div>
										</br>
										<div class="col-md-3">
											<label>Amount:</label>
											<input type="number" class="form-control" id="amount" name="amount" value="" placeholder="0.00">					
										</div>
										</br>
										<div class="col-md-3">
											<label>Remarks:</label>
											<input type="text" class="form-control" id="remarks" name="remarks" value="">
										</div>
										<br/>
									</div>
								</div>
								<br/>
								<div class="row proof_img">	
									<div class="col-md-12">								
										<div class="row">										
											<?php for ($i=1; $i<=10; $i++) { ?>
											<div class="col-md-6">
												<label>Proof of Expense <?php echo $i; ?>:</label>
												<input type="file" class="form-control" id="proof_img<?php echo $i; ?>" name="proof_img<?php echo $i; ?>" value="">			
											</div>
											<?php } ?>					
										</div>
									</div>
								</div>
								<br/>
								
								<div class="modal-footer">
								  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
								  <input class="btn bg-avgreen text-white btn" type="button" id="add" value="Add" onclick="addTask('add')">
								  <span id="modal-button-admin"></span>
								</div>
							  </div>
							</div>
						</div>
					</div>				
				<!-- END Modal-->
				
				<!-- Modal for viewing Proof of Expenses -->
					<div id="proofimage-modal" class="modal" role="dialog">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Proof of Expense</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								  </button>
								</div>
								<div class="modal-body">
									<div class="row" id="expensesImages">
										
									</div>
									<br/>
									<div class="modal-footer">
									  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									  <span id="modal-button-admin"></span>
									</div>
								</div>
							</div>
						</div>
					</div>				
				<!-- END Modal-->
				
					<div class="row">
						<div class="col-md-12">
						<!--<h4 class="card-title">Filter Expenses</h4>-->
						<!--<br/>-->
							<div class="row">
								<div class="col-md-4">
									<div class="col-md-12">
										<label>Date From:</label></div>
										<input class="form-control input" placeholder="Date From" type="date" id="datefrom" name="datefrom">
								</div>
								<div class="col-md-4">
									<div class="col-md-12">
										<label>Date To:</label>
										<input class="form-control input" placeholder="Date To" type="date" id="dateto" name="dateto">
									</div>
								</div>
								<div class="col-md-4">
									<div class="col-md-12">
										<label>Expense Type:</label>
										<select class="form-control input" id="exp_type_search" name="exp_type_search">
											<option value="">Select Expense Type</option>
											<?php if(isset($exp_type)) {?>
												<?php foreach($exp_type as $et) { ?>
													<option value="<?php echo $et['expenses_type_id'];?>"><?php echo $et['description']; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="cold-md-2"></div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">
									<center>
										<input class="btn bg-avgreen text-white btn" type="button" id="search" value="Search" onclick="javascript:submitTask('search');"/>	
										<input class="btn bg-avgreen text-white btn" type="button" id="search" value="Delete" onclick="javascript:submitTask('delete');"/>	
										<input class="btn bg-avgreen text-white" type="button" id="btnSearch" name="btnExport" value="Export" onclick="javascript: exportToCsv()"/>
										<button class="btn bg-gradient-darkorange text-white " type="button" data-toggle="modal" data-target="#expense-modal"><i class="fa fa-plus"></i> Add Expense</button>
								</div>
							</div>
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
							  <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
							  <th class="text-dark">Expense Id</th>	
							  <th class="text-dark">Action</th>	
							  <th class="text-dark">Created By</th>
							  <th class="text-dark">Expense Type</th>
							  <th class="text-dark">Name</th>
							  <th class="text-dark">Amount</th>
							  <th class="text-dark">Remarks</th>
							  <th class="text-dark">Date Created</th>	
							</tr>
						  </thead>
						  <tbody>	
							<?php if (isset($expenseshistory)) { ?>
							<?php foreach ($expenseshistory as $eh) { ?>			
							<tr>
							  <td style="text-align: center;">
								<input type="checkbox" name="selected[]" value="<?php echo $eh['expenses_id'];?>"/>
							  </td>
							  <td><?php echo $eh['expenses_id'];?></td>
							  <td>								
								<button class="btn bg-avgreen text-white btn-sm" type="button" onclick="javascript: viewProofImage(<?php echo $eh['expenses_id'];?>)" data-toggle="modal" data-target="#proofimage-modal"><i class="fa fa-file-image-o" aria-hidden="true"></i> View Image</button>
							  </td>
							  <td><?php echo $eh['creator'];?></td>
							  <td><?php echo $eh['exp_type'];?></td>
							  <td><?php echo $eh['exp_user'];?></td>
							  <td>Php <?php echo $eh['amount'];?></td>
							  <td><?php echo $eh['remarks'];?></td>
							  <td><?php echo $eh['date_added'];?></td>
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
	$('form').attr('action', 'expenses');
	$('form').submit();
}

function addTask(task) {
  var exp_type = $("#exp_type").val();
  var exp_user = $("#exp_user").val();
  var amount = $("#amount").val();
  var proceed = 1;
  var msg = "";
  
  if(exp_type == ""){
	msg += "Expense type is required.<br>";
	proceed = 0;
  }

  if(exp_user == ""){
	msg += "Expense user is required.<br>";
	proceed = 0;
  }
	
  if(amount == ""){
	msg += "Amount is required and greather than 0.<br>";
	proceed = 0;
  }
  

  if(proceed == 0){
	msg = "Please check the following errors: <br><br>" + msg;
	// alert(msg);
	swal ("Oops!", msg, "error");
  }else{
	$('#task').val(task);
	$('form').attr('action', 'expenses');
	$('form').submit();

  }
}

function addExpenseTask(task) {
  var description = $("#description").val();
  var proceed = 1;
  var msg = "";
  
  if(description == "") {
	msg += "Description is required.\n";
	proceed = 0;
  }
  
  if(proceed == 0){
	msg = "Please check the following errors: \n\n" + msg;
	// alert(msg);
	swal ("Oops!", msg, "error");
  }else{
	$('#task').val(task);
	$('form').attr('action', 'expenses');
	$('form').submit();
  }
	
}

function viewProofImage(expenses_id){
	$.ajax({
    url: 'expensesimagedetails/' + expenses_id,
    type: 'get',
    dataType: 'json',
    success: function(json) {             
      if (json['status'] == "Success") {
			console.log(json['expenses_images']);
			$("#expensesImages").html(json['expenses_images']);
			// $("#proofimage-modal").modal('toggle');
        
      }   
    }
  });
}

function exportToCsv() {
	$('form').attr('action', 'exportexpenses'); 
	$('form').submit();
}

//--></script>