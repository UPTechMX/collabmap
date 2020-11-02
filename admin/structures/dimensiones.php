<?php  

if (!function_exists('raiz')) {
	include_once '../../lib/j/j.func.php';
}

// print2($_POST);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#addDim").click(function(event) {
			var elemId = <?php echo $_POST['elemId']; ?>;
			var type = "<?php echo $_POST['type']; ?>";
			popUp('admin/structures/dimensionesAdd.php',{elemId:elemId,type:type},function(e){},{});

		});

		$('[data-toggle="tooltip"]').tooltip({
			html:true,
		});

		// $("#cpDim").click(function(event) {
		// 	var elemId = <?php echo $_POST['elemId']; ?>;
		// 	popUp('admin/structures/dimensionesCopy.php',{elemId:elemId,elemId},function(e){},{});
		// });
	});
</script>
<div class="row" style="margin-top: 10px;">
	<div class="col-md-12">
		<div class="nuevo">
			<?php echo TR('level'); ?>
			<i class="glyphicon glyphicon-info-sign" style="margin-left: 30px;" 
				data-toggle="tooltip" data-placement="right" title="<?= TR('levelsTooltip') ?>"></i>	

		</div>
		<div class="row justify-content-between" style="margin: 20px 0px;">
			<div class="col-4" style="font-weight: bold;text-align: left;"><?= TR('level'); ?></div>
			<div class="col-4" style="text-align: right;">
				<span class="btn btn-shop btn-sm" id="addDim">
					<i class="glyphicon glyphicon-plus">&nbsp;</i><?php echo TR('new'); ?> 
				</span>
			</div>
		</div>

		<div id="dimensionesList" style="margin-top: 10px;"><?php include_once 'dimensionesList.php'; ?></div>
	</div>

	<div class="col-md-12"  style="text-align: left;">
		<div class="nuevo">
			<?php echo TR('elements'); ?>
			<i class="glyphicon glyphicon-info-sign" style="margin-left: 30px;" 
				data-toggle="tooltip" data-placement="right" title="<?= TR('elementsTooltip') ?>"></i>	

		</div>
		<div class="" id="dimensionesArbol"><?php include_once 'dimensionesArbol.php'; ?></div>
	</div>
</div>