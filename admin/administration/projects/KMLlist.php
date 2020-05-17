<?php  
	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);// checaAcceso Projects
	

	$kmls = $db->query("SELECT * FROM KML WHERE projectsId = $_POST[prjId] ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.verKML').click(function(event) {
			var KMLId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(KMLId);
			popUp('admin/administration/projects/KMLview.php',{KMLId:KMLId});
		});
		$('.delKML').click(function(event) {
			var KMLId = $(this).closest('tr').attr('id').split('_')[1];
			// console.log(KMLId);
			conf('<?php echo TR('delKML'); ?>',{KMLId:KMLId,elem:$(this)},function(e){
				// console.log(e);
				var rj = jsonF('admin/administration/projects/json/json.php',{acc:3,KMLId:e.KMLId});
				var r = $.parseJSON(rj);;

				if(r.ok == 1){
					e.elem.closest('tr').remove();
				}
			})
		});
	});
</script>

<table class="table">
	<thead>
		<tr>
			<th><?php echo TR('name'); ?></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($kmls as $kml){ ?>
			<tr id="kml_<?php echo $kml['id']; ?>">
				<td><?php echo $kml['name']; ?></td>
				<td>
					<i class="glyphicon glyphicon-eye-open manita verKML"></i>
				</td>
				<td>
					<i class="glyphicon glyphicon-trash manita rojo delKML"></i>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
