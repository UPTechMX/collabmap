<?php  
	if(!function_exists('raiz')){
		include_once '../../../../lib/j/j.func.php';
	}


?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#dimensionesElems_<?php echo $_POST['dimensionId']; ?> .addDimEle').click(function(event) {
			var dimensionId = this.id.split('_')[1];
			var padreId = this.id.split('_')[2];
			popUp('admin/administration/targets/structure/dimensionesElemAdd.php',{
				targetsId:<?php echo $_POST['targetsId']; ?>,
				dimensionId:<?php echo $_POST['dimensionId']; ?>,
				padreId:padreId
			},function(e){},{});
		});
	});
</script>




<?php if (isset($_POST['padreId'])): ?>
	<span class="btn btn-shop btn-sm addDimEle" id="addDimEle_<?php echo "$dim[id]_$_POST[padreId]";?>">
		<i class="glyphicon glyphicon-plus">&nbsp;</i><?php echo TR('addElement'); ?>
	</span>
	<div id="dimensionesElemList_<?php echo $_POST['dimensionId']; ?>"><?php include_once 'dimensionesElemList.php' ?></div>
<?php endif ?>
