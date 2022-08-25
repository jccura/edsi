<?php echo $header; ?>
<h3 class="page-heading mb-4"><i class="fa fa-money"></i> Deposit Endorsements</h3>

<div class="card">	  
    <div class="card-body">
		<div class="col-md-12">
			<form action="" method="post" enctype="multipart/form-data" id="form">
				<?php if($this->user->getUserGroupId() == 13 or $this->user->getUserGroupId() == 39 or $this->user->getUserGroupId() == 40) { ?>			
				<br>									
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-4"><label>Total Amount to Endorse (in Php):</label></div>
					<div class="col-md-4">
						<div class="form-group">
							<select class="form-control" name="total" id="total" >
								<option value='0'>Select One Amount</option>
								<?php for($i=500;$i<=50000;) { ?>
									<option value="<?php echo $i;?>">Php <?php echo number_format($i,2);?> or $<?php echo number_format($i/50,2);?></option>
									<?php $i += 500; ?>
								<?php } ?>
							</select>
						</div>					
					</div>
					<div class="col-md-2"></div>
				</div>
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-4"><label>Mode of Payment:</label></div>
					<div class="col-md-4">
						<div class="form-group">
							<select class="form-control" name="mode_of_remittance" id="mode_of_remittance" >
								<option value='0'>Select One Amount</option>
								<option value='50'>DEPOSIT TO BDO</option>
								<option value='68'>PALAWAN EXPRESS</option>
								<option value='61'>CEBUANA LHUILLER</option>
							</select>
						</div>					
					</div>
				</div>	
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-4"><label>Reference:</label></div>
					<div class="col-md-4">
						<div class="form-group">
							<input class="form-control" name="reference" id="reference" type="text" value="">
						</div>					
					</div>
					<div class="col-md-2"></div>
				</div>
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-4 sm-12 col-xs-12"><label>Exact Date and Time in Deposit Slip:</label></div>
					<div class="col-md-5 sm-12 col-xs-12">
						<div class="row">
							<div class="col-md-4 sm-4 col-xs-4">
								<select class="form-control" id="deposit_slip_year" name="deposit_slip_year">
									<option value="<?php echo date('Y'); ?>"><?php date_default_timezone_set('Asia/Manila'); echo date("Y"); ?></option>
									<?php $cur_year = date("Y"); for($i=$cur_year;$i < 2099;$i++) {?>
											<option value="<?php echo $i;?>"><?php echo $i;?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-4 sm-4 col-xs-4">
								<select class="form-control" id="deposit_slip_month" name="deposit_slip_month">
									<option value="<?php echo date('m'); ?>"><?php echo date("M"); ?></option>
									<?php $months = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"); ?>
									<?php for($i=0;$i < 12;$i++) {?>
											<?php if($i < 9){ ?>
												<option value="<?php echo '0' . ($i + 1);?>"><?php echo $months[$i];?></option>
											<?php }else{ ?>
												<option value="<?php echo $i + 1;?>"><?php echo $months[$i];?></option>
											<?php } ?>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-4 sm-4 col-xs-4">
								<select class="form-control" id="deposit_slip_date" name="deposit_slip_date">
									<option value="<?php echo date('d'); ?>"><?php echo date("d"); ?></option>
									<?php for($i=1;$i <= 31;$i++) {?>
											<?php if($i < 10){ ?>
												<option value="<?php echo '0' . $i;?>"><?php echo '0' . $i;?></option>
											<?php }else{ ?>
												<option value="<?php echo $i;?>"><?php echo $i;?></option>
											<?php } ?>
									<?php } ?>
								</select>							
							</div>
						</div>					
					</div>
					<div class="col-md-2"></div>
				</div>
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-4 sm-12 col-xs-12"></div>
					<div class="col-md-5 sm-12 col-xs-12">
						<br>
						<div class="row">
							<div class="col-md-4 sm-4 col-xs-4">
								<select class="form-control" id="deposit_slip_hour" name="deposit_slip_hour">
									<option value="">HOUR</option>
									<?php for($i=0;$i<24;$i++) {?>
										<?php if($i<10) { ?>
											<option value="<?php echo "0".$i;?>"><?php echo "0".$i;?></option>
										<?php } else { ?>
											<option value="<?php echo $i;?>"><?php echo $i;?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-4 sm-4 col-xs-4">
								<select class="form-control" id="deposit_slip_minute" name="deposit_slip_minute">
									<option value="">MIN</option>
									<?php for($i=0;$i<60;$i++) {?>
										<?php if($i<10) { ?>
											<option value="<?php echo "0".$i;?>"><?php echo "0".$i;?></option>
										<?php } else { ?>
											<option value="<?php echo $i;?>"><?php echo $i;?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-4 sm-4 col-xs-4">
								<select class="form-control" id="deposit_slip_seconds" name="deposit_slip_seconds">
									<option value="">SEC</option>
									<?php for($i=0;$i<60;$i++) {?>
										<?php if($i<10) { ?>
											<option value="<?php echo "0".$i;?>"><?php echo "0".$i;?></option>
										<?php } else { ?>
											<option value="<?php echo $i;?>"><?php echo $i;?></option>
										<?php } ?>
									<?php } ?>
								</select>							
							</div>
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-md-5 sm-12 col-xs-12"></div>
					<div class="col-md-5 text-left col-sm-12 col-xs-12">
						<br>
						<div class="help-tip">
							<p>** You should provide the exact date and time located at your deposit transaction.</p>
						</div>				
					</div>
				</div>
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-4"><label>Picture of Proof of Endorsement</label><br><label class="red-color">(Kindly submit legit proof or else this endorsement will be denied):</label></div>
					<div class="col-md-4">
						<div class="form-group">
							<input class="form-control" id="photo_remittance" name="photo_remittance" type="file" />
						</div>					
					</div>
					<div class="col-md-3"></div>
				</div>	
				<div class="row">
					<div class="col-md-12">
						<center>
							<input type="hidden" id="task" name="task" value="" />
							<input type="hidden" id="trans_session_id" name="trans_session_id" value="<?php echo $trans_session_id; ?>" />
							<input class="btn btn-primary btn" type="button" id="search" value="Send" onclick="javascript: process('add');">																
						</center>
					</div>
				</div>				
				<?php } ?>
				
				<?php if($this->user->getUserGroupId() == 36) { ?>
				<div class="row">
					<div class="col-md-2">
						<center>
							<input class="form-control input" type="text" id="datefrom" name="datefrom" placeholder="Date From" value="">						
						</center>
					</div>
					<div class="col-md-2">
						<center>
							<input class="form-control input" type="text" id="dateto" name="dateto" placeholder="Date To" value="">						
						</center>
					</div>
					<div class="col-md-2">
						<center>
							<input class="form-control input" type="text" id="deposit_slip_datetime" name="deposit_slip_datetime" placeholder="Deposit Slip Datetime" value="">						
						</center>
					</div>
					<div class="col-md-2">
						<center>
							<select class="form-control input" id="mode_of_remittance" name="mode_of_remittance">
								<option value=''>Select Mode of Remittance</option>
								<option value='50'>DEPOSIT TO BDO</option>	
								<option value='68'>PALAWAN EXPRESS</option>					
								<option value='61'>CEBUANA LHUILLER</option>	
								<option value='75'>COINS.PH</option>
							</select>				
						</center>
					</div>	
					<div class="col-md-2">
						<center>
							<select class="form-control input" id="status_flag" name="status_flag">
								<option value=''>Status</option>
								<option value='1'>For Approval</option>	
								<option value='2'>Cancelled</option>
								<option value='3'>Deleted</option>
								<option value='4'>Approved</option>
								<option value='5'>Denied</option>
							</select>				
						</center>
					</div>
					<div class="col-md-2">
						<center>
							<select class="form-control input" id="receipt_counter" name="receipt_counter">
								<option value=''>Counter</option>
								<?php for($i=1;$i<11;$i++){ ?>
									<option value="<?php echo $i; ?>"><?php echo $i; ?></option>	
								<?php } ?>	
							</select>				
						</center>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						<center>
							<input type="hidden" id="task" name="task" value="" />
							<input type="hidden" id="trans_session_id" name="trans_session_id" value="<?php echo $trans_session_id; ?>" />
							<input class="btn btn-primary btn" type="button" id="search" value="Search" onclick="javascript: searchEndorsement();">						
						</center>
					</div>
				</div>
				<?php } ?>
				
				<div class="row">
					<div class="col-md-12">
						<div align="center">
							<div style="width: 100%; overflow: auto;">	
								<input type="hidden" id="trans_session_id" name="trans_session_id" value="<?php echo $trans_session_id; ?>" />
								<input type="hidden" id="cashier_rem_id" name="cashier_rem_id" value="" />
								<table class="list">
									<thead>
									<tr>
										<td class="left numeric" >Deposit Id#</td>
										<td class="left numeric" >Proof of Send Fund</td>
										<td class="left numeric" >Actions</td>
										<td class="left numeric" >Endorse By Username</td>
										<td class="left numeric" >Endorse By Name</td>				
										<td class="left numeric" >Amount</td>					
										<td class="left numeric" >Mode Of Remittance</td>					
										<td class="left numeric" >Date Submitted</td>															
										<td class="left numeric" >Status</td>									
										<td class="left numeric" >Deposit Slip Date/Time</td>									
										<td class="left numeric" >Deposit Reference Number</td>
										<?php if($this->user->getUserGroupId() == 36) { ?> 
										<td class="left numeric" >Counter</td>
										<?php } ?> 
										<td class="left numeric" >Approved By</td>
									</tr>
									</thead>
									<tbody>
									<?php if (isset($remittances)) { ?>
										<?php foreach ($remittances as $rem) { ?>
											<tr>
												<td data-title="Id"><?php echo $rem['deposit_id'];?></td>
												<td data-title="Proof of Send Funds"><a href="remittanceb/remittanceb<?php echo $rem['deposit_id'];?>.<?php echo $rem['file_extension'];?>" target="_blank"><img src="remittanceb/remittanceb<?php echo $rem['deposit_id'];?>.<?php echo $rem['file_extension'];?>" width="150px"></td>	
												<td data-title="Action">											
												<?php if($this->user->getUserGroupId() == 13 or $this->user->getUserGroupId() == 39 or $this->user->getUserGroupId() == 40) { ?>	
													<?php if($rem['status_flag'] == 1 ) { ?>
														<a class="btn btn-primary btn red-btn" href="deposit/0/cancel/<?php echo $rem['deposit_id'];?>">Cancel</a>
													<?php } ?>
												<?php } ?>
												<?php if($this->user->getUserGroupId() == 36) { ?>	
													<?php if($rem['status_flag'] == 1 ) { ?>
														<br>
														<a class="btn btn-primary btn red-btn" href="deposit/0/approve/<?php echo $rem['deposit_id'];?>">Approve</a>
														<br><br>
														<a class="btn btn-warning btn red-btn" href="deposit/0/deny/<?php echo $rem['deposit_id'];?>">Deny</a>
													<?php } ?>
												<?php } ?>
												</td>
												<td data-title="Username"><?php echo $rem['cashier_username'];?></td>
												<td data-title="Name"><?php echo $rem['cashier_name'];?></td>
												<td data-title="Amount">Php <?php echo number_format($rem['amount'], 2);?></td>
												<td data-title="Mode Of Send Funds"><?php echo $rem['mode_of_rem_desc'];?></td>
												<td data-title="Date Submitted"><?php echo $rem['date_added'];?></td>
												<td data-title="Status" class="<?php if($rem['status'] == 'Approved'){echo 'approved_bg' ;}else if($rem['status'] == 'Denied'){echo 'denied_bg' ;}else if($rem['status'] == 'Cancelled'){echo 'denied_bg' ;} ?>"><?php echo $rem['status'];?></td>
												<td data-title="Deposit Slip DateTime"><?php echo $rem['deposit_slip_datetime'];?></td>
												<td data-title="Reference"><?php echo $rem['reference'];?></td>
												<?php if($this->user->getUserGroupId() == 36) { ?> 
												<td data-title="Counter"><?php echo $rem['receipt_counter'];?></td>
												<?php } ?>
												<td data-title="Approved By"><?php echo $rem['approved_by_name'];?></td>
											</tr>
										<?php } ?>	
									<?php } ?>
									</tbody>
								</table>
							</div>
							<div class="pagination"><?php echo $pagination; ?></div>
						</div>
					</div>
				</div>
				<hr>			
				<div id="dialog-message" title="Confirmation Message" style="display:none; width: 400px;">
				  <span id="msg"></span>
				</div>
			</form>
		</div>
	</div>
</div>	

<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date_deposited').datepicker({dateFormat: 'yy-mm-dd'});
	$('#deposit_slip_date').datepicker({dateFormat: 'yy-mm-dd'});
	$('#datefrom').datepicker({dateFormat: 'yy-mm-dd'});
	$('#dateto').datepicker({dateFormat: 'yy-mm-dd'});	

	<?php if(isset($err_msg)) { ?>
		<?php if(!empty($err_msg)) { ?>
			$('#msg').html("<?php echo $err_msg; ?>");			      
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
	<?php } ?>	
});

<?php if($this->user->getUserGroupId() == 13 or $this->user->getUserGroupId() == 39 or $this->user->getUserGroupId() == 40) { ?>
function process(task) {
	var total = $('#total').val();	
	msg = "Are you sure you want to endorse Php "+total+" with legit picture of proof of deposit? Submitting invalid picture will deny this deposit.";	 
	$('#msg').html(msg);	
	$('#task').val(task);
	var error_msg = "";
	var proceed = 1;
	if($('#photo_remittance').val() == "") {
		proceed = 0;
		error_msg += "Please provide a photo of the proof of endorsement. <br>";
	}
	if($('#package').val() == "") {
		proceed = 0;
		error_msg += "Please provide the Package. <br>";
	} else {
		 var packageValue = $("input[id='package']:checked").val();
		if(packageValue == "PROMO PLAN") {
			if($('#total').val() < 20000) {
				proceed = 0;
				error_msg += "Amount for Deposit starts from P20,000. <br>";
			}
		}
	}
	if($('#mode_of_remittance').val() == "") {
		proceed = 0;
		error_msg += "Please provide the mode of endorsement. <br>";
	}
	if($('#total').val() == "0") {
		proceed = 0;
		error_msg += "Amount Deposited is mandatory. <br>";
	}			
	if($('#deposit_slip_hour').val() == "") {
		proceed = 0;
		error_msg += "Please indicate exact hour.<br>";
	}
	if($('#deposit_slip_minute').val() == "") {
		proceed = 0;
		error_msg += "Please indicate exact minute.<br>";
	}
	if($('#deposit_slip_seconds').val() == "") {
		proceed = 0;
		error_msg += "Please indicate exact seconds.<br>";
	}	
	if(proceed == 1) {	
		$(function() {
			$( "#dialog-message" ).dialog({
			  modal: true,
			  width: 600,
			  buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
					$('form').attr('action', 'deposit'); 
					$('form').submit();
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}				
			  }
			});
		});
	} else {
		$('#msg').html(error_msg);		
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
	}
} 

function checkRows(row) {
	var total = 0;
	var trade_amt = 0;
	var topup_amt = 0;
	var act_fee = 0;
	var msg = "";
	if($('#user_id'+row).val() != "0") {
		total = Number($('#trade_amt'+row).val()) + Number($('#topup_amt'+row).val()) + Number($('#act_fee'+row).val());
		if(total == 0) {
			msg = "Please check the entries for row "+row+". One of them should be more than 0 USD to proceed.<br>";
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
		}
	}
	return msg;
}
function totalForPayment() {
	var total_trade_amt = 0;
	var total_topup_amt = 0;
	var total_activation_amt = 0;
	<?php for($i=1; $i<=20; $i++) { ?>
		total_trade_amt = total_trade_amt + Number($('#trade_amt<?php echo $i; ?>').val());
	<?php } ?>
	<?php for($i=1; $i<=20; $i++) { ?>
		total_topup_amt = total_topup_amt + Number($('#topup_amt<?php echo $i; ?>').val());
	<?php } ?>
	<?php for($i=1; $i<=20; $i++) { ?>
		total_activation_amt = total_activation_amt + Number($('#act_fee<?php echo $i; ?>').val());
	<?php } ?>
	var total = total_trade_amt + total_topup_amt + total_activation_amt;
	//alert(total);
	$('#total').val(total);
}
<?php } ?>

<?php if($this->user->getUserGroupId() == 36) { ?>
function searchEndorsement() {
	$('#task').val("search");
	$('form').attr('action', 'deposit'); 
	$('form').submit();
}

function deny(cashier_rem_id, cashier_name, amount) {
	amount_comma = insertCommas(amount);
	msg = "Are you sure you want to deny Group Trader Send Fund ID#"+cashier_rem_id+" of "+cashier_name+" with amount of USD "+amount_comma+"?";	 
	$('#msg').html(msg);	
	$('#task').val("deny");
	$('#cashier_rem_id').val(cashier_rem_id);
	$(function() {
		$( "#dialog-message" ).dialog({
		  modal: true,
		  width: 600,
		  buttons: {
			Ok: function() {
				$( this ).dialog( "close" );				
				$('form').attr('action', '<?php echo HTTP_SERVER;?>index.php?route=account/endorsefund'); 
				$('form').submit();				
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}				
		  }
		});
	});	
}

function approve(cashier_rem_id, cashier_name, amount) {
	amount_comma = insertCommas(amount);
	msg = "Are you sure you want to approve Group Trader Send Fund ID#"+cashier_rem_id+" of "+cashier_name+" with amount of USD "+amount_comma+"?";	 
	$('#msg').html(msg);	
	$('#task').val("approve");
	$('#cashier_rem_id').val(cashier_rem_id);
	$(function() {
		$( "#dialog-message" ).dialog({
		  modal: true,
		  width: 600,
		  buttons: {
			Ok: function() {
				$( this ).dialog( "close" );				
				$('form').attr('action', '<?php echo HTTP_SERVER;?>index.php?route=account/endorsefund'); 
				$('form').submit();				
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}				
		  }
		});
	});		
}
<?php } ?>

function insertCommas(s) {
    // get stuff before the dot
    var d = s.indexOf('.');
    var s2 = d === -1 ? s : s.slice(0, d);
    // insert commas every 3 digits from the right
    for (var i = s2.length - 3; i > 0; i -= 3)
      s2 = s2.slice(0, i) + ',' + s2.slice(i);
    // append fractional part
    if (d !== -1)
      s2 += s.slice(d);
    return s2;
}


//--> 
</script>
<?php echo $footer; ?>