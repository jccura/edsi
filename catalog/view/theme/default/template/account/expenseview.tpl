<?php echo $header; ?>
<div class="row">
	<div class="col-sm-12 mb-4 mb-xl-0">
		<?php if(isset($view_images['expenses_id'])) { ?>
		<h4 class="font-weight-bold text-dark">Proof of Expense Id #<?php echo $view_images['expenses_id']; ?></h4>
		<?php } ?>
	</div>
</div>
<div class="card card-margin">	  
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<?php for($i=1;$i<=10;$i++) { ?>
						<?php if($view_images['extension_'.$i] != "") { ?>
							<div class="col-md-6">
								<label>Detail Photo <?php echo $i; ?>:</label>
								<img width="100%" height="100%" src="image/expenseimages/proof_img<?php echo $view_images['expenses_id'] ?>_<?php echo $i; ?>.<?php echo $view_images['extension_'.$i] ?>" class="img-fluid" />
								<hr/>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript"><!--
$(document).ready(function() {
	<?php if(isset($err_msg)) { ?>
		<?php if(($err_msg !="")) { ?>	
			alert("<?php echo $err_msg; ?>");
		<?php } ?>
	<?php } ?>
	
});
 
//--></script>

<?php //echo $footer; ?>


