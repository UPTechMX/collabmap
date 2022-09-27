<?php  

	if($_POST['ajax'] == 1){
		include_once '../../../../lib/j/j.func.php';
	}

	$p = $_POST;
	// print2($_POST);
	$dimElem = $db->query("SELECT de.* 
		FROM DimensionesElem de
		LEFT JOIN Dimensiones d ON de.dimensionesId = d.id
		WHERE elemId = $_POST[targetsId] AND type = 'structure' AND de.padre = $_POST[padreId] ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
	$dims = $db->query("SELECT id FROM Dimensiones WHERE elemId = $_POST[targetsId] AND type = 'structure' ORDER BY id")->fetchAll(PDO::FETCH_NUM);
	// print2($dims);
	$numDims = count($dims);
	for($i = 0;$i<$numDims;$i++){
		if($dims[$i][0] == $_POST['dimensionId']){
			break;
		}
	}
	$dimSig = $dims[$i+1][0];

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#dimensionesElemList_<?php echo $_POST['dimensionId'];?> .edtDimElem').click(function(event) {
			var dimensionElemId = this.id.split('_')[1];
			popUp('admin/administration/targets/structure/dimensionesElemAdd.php',{
				dimensionElemId:dimensionElemId,
				targetsId:<?php echo $_POST['targetsId']; ?>,
				dimensionId:<?php echo $_POST['dimensionId']; ?>,
				padreId:<?php echo $_POST['padreId']; ?>,
			},function(e){},{});

		});

		$('#dimensionesElemList_<?php echo $_POST['dimensionId'];?> .delElemDim').click(function(event) {
			var elemId = this.id.split('_')[1];
			// console.log(elemId);
			conf('¿Estás seguro que deseas borrar el elemento?<br/> '+
				'Se perderá toda la información relacionada.',{elemId:elemId,elem:$(this)},function(e){
					var rj = jsonF('admin/administration/targets/structure/json/json.php',{acc:6,elemId:e.elemId});
					// console.log(rj);
					var r = $.parseJSON(rj);
					if(r.ok == 1){
						e.elem.closest('tr').remove();
						<?php if (!empty($dimSig)): ?>
							$.each($('.dimensionesElems'), function(index, val) {
								var dimId = this.id.split('_')[1];
								// console.log(dimId,<?php echo $dimSig;?>);
								if(dimId >= <?php echo $dimSig;?> ){
									$('#dimensionesElems_'+dimId).empty();
								}

							});
						<?php endif ?>

					}

			})

		});


		<?php if (!empty($dimSig)): ?>
			$('#dimensionesElemList_<?php echo $_POST['dimensionId'];?> .verDimElem').click(function(event) {
				var dimensionElemId = this.id.split('_')[1];
				// console.log('aa');
				$('#dimensionesElems_'+<?php echo $dimSig; ?>)
				.load(rz+'admin/administration/targets/structure/dimensionesElems.php',{
					ajax:1,
					targetsId:<?php echo $_POST['targetsId']; ?>,
					padreId:dimensionElemId,
					dimensionId:<?php echo $dimSig; ?>
				});

				$.each($('.dimensionesElems'), function(index, val) {
					var dimId = this.id.split('_')[1];
					// console.log(dimId,<?php echo $dimSig;?>);
					if(dimId > <?php echo $dimSig;?> ){
						$('#dimensionesElems_'+dimId).empty();
					}

				});
				$.each($('#dimensionesElemList_<?php echo $_POST['dimensionId'];?> tr'), function(index, val) {
					 $(this).removeClass('seleccionado');
				});
				$(this).closest('tr').addClass('seleccionado');

			});

		<?php endif ?>

	});

</script>

<table class="table" style="margin-top: 10px;">
	<thead>
		<tr>
			<th><?php echo TR('name'); ?></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($dimElem as $de): ?>
			<tr>
				<td>
					<?php echo "$de[nombre]"; ?><br/>
					ID: <strong><?php echo "$de[idGeo]"; ?></strong>
				</td>
				<td><i class="glyphicon glyphicon-pencil edtDimElem manita" id="edtDimElem_<?php echo $de['id'];?>"></i></td>
				<td><i class="glyphicon glyphicon-trash manita rojo delElemDim" id="delElemDim_<?php echo $de['id'];?>"></i></td>
				<?php if (!empty($dimSig)): ?>
					<td class="verDimElem manita" id="tdVerDimElem_<?php echo $de['id'];?>" >
						<i class="glyphicon glyphicon-chevron-right " id="verDimElem_<?php echo $de['id'];?>"></i>
					</td>
				<?php else: ?>
					<td></td>
				<?php endif ?>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
