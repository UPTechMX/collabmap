<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);

	$fondeadores = $db->query("SELECT pf.id, f.nombre, f.id as fId , pf.presupuesto
		FROM ProyectosFondeadores pf 
		LEFT JOIN Fondeadores f ON f.id = pf.fondeadoresId
		WHERE proyectosId = $_POST[eleId]
		ORDER BY f.nombre")->fetchAll(PDO::FETCH_ASSOC);
	
?>

<script type="text/javascript">
	jQuery(document).ready(function($) {

		

		$('.edtPryFond').click(function(event) {
			var pryId = <?php echo $_POST['eleId']; ?>;
			var eleId = $(this).closest('.fond').attr('id').split('_')[1];
			console.log(eleId);
			popUp('admin/administracion/proyectos/pryFondAdd.php',{pryId:pryId,eleId:eleId},function(){},{});
		});

		$(".delPryFond").click(function(event) {
			var elemId = $(this).closest('.fond').attr('id').split('_')[1];
			var pryId = <?php echo $_POST['eleId']; ?>;
			conf('¿Estás seguro que deseas eliminar este financiador del proyecto?',{elemId:elemId,pryId:pryId},function(e){
				var rj = jsonF('admin/administracion/proyectos/json/json.php',{elemId:e.elemId,pryId:e.pryId,acc:3,opt:2});
				console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#fond_'+e.elemId).remove();
				}
			})
		});

	});
</script>
<table class="table" id="pryFonds">
	<thead>
		<tr>
			<th></th>
			<th>Financiador</th>
			<th>Presupuesto</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($fondeadores as $k => $f){ ?>
			<tr class="fond <?php echo "fond_$f[fId]"; ?>" id="<?php echo "fond_$f[id]"; ?>">
				<td ><i class="glyphicon glyphicon-pencil manita edtPryFond"></i></td>
				<td ><?php echo $f['nombre']; ?></td>
				<td >$ <?php echo number_format($f['presupuesto'],2); ?></td>
				<td style="text-align: right;"><i class="glyphicon glyphicon-trash manita rojo delPryFond"></i></td>
			</tr>
		<?php } ?>

	</tbody>

</table>
