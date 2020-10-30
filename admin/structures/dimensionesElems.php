<?php  
	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}


?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#dimensionesElems_<?php echo $_POST['dimensionId']; ?> .addDimEle').click(function(event) {
			var dimensionId = this.id.split('_')[1];
			var padreId = this.id.split('_')[2];
			popUp('admin/structures/dimensionesElemAdd.php',{
				elemId:<?php echo $_POST['elemId']; ?>,
				type:"<?php echo $_POST['type']; ?>",
				dimensionId:<?php echo $_POST['dimensionId']; ?>,
				padreId:padreId
			},function(e){},{});
		});
	});
</script>




<?php if (isset($_POST['padreId'])): ?>
	<div class="row justify-content-between" style="margin: 20px 0px;">
		<div class="col-4" style="font-weight: bold;text-align: left;"><?= TR('element'); ?></div>
		<div class="col-4" style="text-align: right;">
			<span class="btn btn-shop btn-sm addDimEle" id="addDimEle_<?php echo "$dim[id]_$_POST[padreId]";?>">
				<i class="glyphicon glyphicon-plus">&nbsp;</i><?php echo TR('new'); ?>
			</span>
		</div>
	</div>

	<div id="dimensionesElemList_<?php echo $_POST['dimensionId']; ?>"><?php include_once 'dimensionesElemList.php' ?></div>
<?php endif ?>
