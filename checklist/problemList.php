<?php

	if(!function_exists('raiz')){
		include_once '../lib/j/j.func.php';
		$spatial = $_POST['spatial'];
		$hash = $_POST['hash'];
	}

	// print2($_POST);
	$vId = $_POST['vId'];
	$pId = $spatial['id'];
	$problems = $db->query("SELECT p.*, c.name as cName
		FROM Problems p
		LEFT JOIN RespuestasVisita rv ON rv.id = p.respuestasVisitaId
		LEFT JOIN Categories c ON c.id = p.categoriesId
		WHERE rv.visitasId = $vId AND rv.preguntasId = $pId
		ORDER BY p.name
	")->fetchAll(PDO::FETCH_ASSOC);

	// print2($problems);


?>

<script type="text/javascript">

	$(document).ready(function() {
		
		$.each($('.img'), function(index, val) {
			var file = $(this).attr('file');
			if(file != ''){
				$(this).append(
					'<img  class="verImg manita" src="'+rz+'problemsPhotos/'+file+'" height="100px"/>'
				)
			}
		});


		$('.edtPrb').click(function(event) {
			var prbId = $(this).closest('tr').attr('id').split('_')[1];
			var type = $(this).closest('tr').attr('id').split('_')[2];
			
			var datos = {};
			datos['visitasId'] = <?php echo $_POST['vId']; ?>;
			datos['preguntasId'] = <?php echo $spatial['id']; ?>;
			datos['identificador'] = '<?php echo $spatial['identificador']; ?>';
			datos['respuesta'] = 'spatial';
			datos['justificacion'] = '';

			var hash = '<?php echo $hash; ?>';
			var pIdAct = '<?php echo $spatial['id']; ?>';
			// console.log(datos)
			var problem = {};

			popUpMapa('checklist/problemsAdd.php',{
				datos:datos,
				hash:hash,
				pIdAct:pIdAct,
				acc:8,
				vId:datos['visitasId'],
				eleId:prbId
			});
		});

		$('.delPrb').click(function(event) {
			var prbId = $(this).closest('tr').attr('id').split('_')[1];
			var layers = drawnItems._layers;



			conf("<?php echo TR('deleteProblem'); ?>",{prbId:prbId,elem:this},function(e){
				var rj = jsonF('checklist/json/json.php',{acc:12,lIds:[e.prbId],vId:<?php echo $_POST['vId']; ?>});
				var r = $.parseJSON(rj);

				if(r.ok == 1){

					$(e.elem).closest('tr').remove();
					for(var i in layers){
						var layer = layers[i];
						// console.log(layer);

						if(layer.dbId == prbId){
							delLayer = layer;
							break;
						}
					}
					drawnItems.removeLayer(delLayer);

					if(r.count == 0){
						spatialYa = false;
					}
				}
			});
		});

	});
</script>

<table class="table">
	<?php foreach ($problems as $p){ ?>
		<tr id="prbTr_<?php echo "$p[id]_$p[type]"; ?>">
			<td><?php echo $p['name']; ?></td>
			<td><?php echo $p['description']; ?></td>
			<td><?php echo $p['cName']; ?></td>
			<td class="img" file="<?php echo $p['photo']; ?>"></td>
			<td>
				<i class="glyphicon glyphicon-pencil manita edtPrb"></i>
			</td>
			<td>
				<i class="glyphicon glyphicon-trash manita rojo delPrb"></i>
			</td>
		</tr>
	<?php } ?>
</table>