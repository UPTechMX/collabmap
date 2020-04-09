<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);

	if(!empty($_POST['eleId'])) {	
		$busq = $db->query("SELECT ru.id, u.nombre, u.id as uId 
			FROM ReconocimientosUbicaciones ru 
			LEFT JOIN Ubicaciones u ON u.id = ru.ubicacionesId
			WHERE reconocimientosId = $_POST[eleId]")->fetchAll(PDO::FETCH_ASSOC);
	}else{
		$busq = array();
	}
	
	// print2($busq);

	// $db->query("DELETE FROM reconocimientosubiconias");

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.delRecUbic').click(function(event) {
			var elemId = $(this).closest('.ubic').attr('id').split('_')[1];
			var recId = "<?php echo $_POST['eleId']; ?>";
			conf('¿Estás seguro que deseas eliminar esta ubicacion del reconocimiento?',{elemId:elemId,recId:recId},function(e){
				var rj = jsonF('admin/administracion/reconocimientos/json/json.php',{elemId:e.elemId,pryId:e.pryId,acc:6,opt:5});
				// console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#ubic_'+e.elemId).remove();
				}
			})

		});

		$('.verRecUbic').click(function(event) {
			var ubicId = $(this).closest('.ubic').attr('uId').split('_')[1];

			popUp('general/ubicaciones/ubicacionesAdd.php',{eleId:ubicId,readonly:1},function(){
				$('<div>').attr({class:'modal-header nuevo',id:'headerMod'})
				.insertAfter( "#encabezado" )
				.html(`
					<div style="text-align: center;">
						<h4 class="modal-title">Ubicación</h4>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				`);
				$("#encabezado").remove();
				$('<div>').attr({class:'modal-footer',id:'pieMod'})
				.insertAfter( "#pie" )
				.html(`
					<span id="cancelM" data-dismiss="modal" class="btn btn-sm btn-shop">Salir</span>
				`);

				$("#pie").remove();
			},{});
		});


	});
</script>

<table class="table" id="pryUbics">
	<thead>
		<tr>
			<th>Nombre</th>
			<th></th>
			<th></th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($busq as $k => $f){ ?>
			<tr class="ubic <?php echo "ubic_$f[uId]"; ?>" uId="<?php echo "ubic_$f[uId]"; ?>" id="<?php echo "ubic_$f[id]"; ?>">
				<td ><?php echo $f['nombre']; ?></td>
				<td style="text-align: right;"><i class="glyphicon glyphicon-trash manita rojo delRecUbic"></i></td>
				<td style="text-align: right;"><i class="glyphicon glyphicon-eye-open manita verRecUbic"></i></td>
			</tr>
		<?php } ?>
	</tbody>
</table>