<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Consultations


$audiences = $db->query("SELECT ca.id, a.name as aName, de.nombre as deName, d.nombre as dName,ca.levelType, de.id as deId
	FROM ConsultationsAudiences ca
	LEFT JOIN Audiences a ON a.id = ca.audiencesId
	LEFT JOIN DimensionesElem de ON de.id = ca.dimensionesElemId
	LEFT JOIN Dimensiones d ON d.id = de.dimensionesId
	WHERE consultationsId = $_POST[consultationsId]
")->fetchAll(PDO::FETCH_ASSOC);

// print2($audiences);

$levelType[1] = 'onlyThis';
$levelType[2] = 'onlyChildrens';
$levelType[3] = 'thisAndChildrens';
$levelType[4] = 'offspring';
$levelType[5] = 'thisAndOffspring';

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.delAud').click(function(event) {
			var eleId = $(this).closest('tr').attr('id').split('_')[1];
			conf('<?php echo TR('delAud'); ?>',{eleId:eleId,elem:$(this)},function(e){
				// console.log(e);
				var rj = jsonF('admin/administration/consultations/json/json.php',{acc:4,eleId:e.eleId});
				var r = $.parseJSON(rj);;

				if(r.ok == 1){
					e.elem.closest('tr').remove();
				}
			})

		});
	});
</script>
<div style="margin-top: 10px;">
	
	<table class="table">
		<thead>
			<th><?php echo TR('audience'); ?></th>
			<th><?php echo TR('level'); ?></th>
			<th><?php echo TR('element'); ?></th>
			<th><?php echo TR('levelTypeAud'); ?></th>
			<th></th>
		</thead>
		<tbody>
			<?php 
			foreach ($audiences as $a){
				if($a['deId'] == 0){
					$a['dName'] = '- -';
					$a['deName'] = '- -';
				}
			?>
				<tr id="<?php echo "audTr_$a[id]"; ?>">
					<td><?php echo $a['aName']; ?></td>
					<td><?php echo $a['dName']; ?></td>
					<td><?php echo $a['deName']; ?></td>
					<td><?php echo TR($levelType[$a['levelType']]); ?></td>
					<td>
						<i class="glyphicon glyphicon-trash manita rojo delAud"></i>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>