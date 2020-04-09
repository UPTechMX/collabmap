<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);

	if(!empty($_POST['eleId'])) {	
		$busq = $db->query("SELECT rl.id, CONCAT(IFNULL(l.nombre,''),' ',IFNULL(l.aPat,''),' ',IFNULL(l.aMat,'')) as nombre, l.id as uId 
			FROM ReconocimientosLideres rl 
			LEFT JOIN LideresComunitarios l ON l.id = rl.lideresId
			WHERE reconocimientosId = $_POST[eleId] ORDER BY l.nombre, l.aPat, l.aMat")->fetchAll(PDO::FETCH_ASSOC);
	}else{
		$busq = array();
	}
	
	// print2($busq);


?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.delRecLider').click(function(event) {
			var elemId = $(this).closest('.lider').attr('id').split('_')[1];
			var recId = "<?php echo $_POST['eleId']; ?>";
			console.log(elemId,recId);
			conf('¿Estás seguro que deseas eliminar este líder comunitario del reconocimiento?',{elemId:elemId,recId:recId},function(e){
				var rj = jsonF('admin/administracion/reconocimientos/json/json.php',{elemId:e.elemId,pryId:e.pryId,acc:7,opt:6});
				// console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#lider_'+e.elemId).remove();
				}
			})

		});

		$('.verRecLider').click(function(event) {
			var liderId = $(this).closest('.lider').attr('uId').split('_')[1];
			console.log(liderId);
			popUp('general/ubicaciones/lideresAdd.php',{eleId:liderId,readonly:1},function(){
				$('<div>').attr({class:'modal-header nuevo',id:'headerMod'})
				.insertAfter( "#encabezado" )
				.html(`
					<div style="text-align: center;">
						<h4 class="modal-title">Líder comunitario</h4>
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

<table class="table" id="pryLider">
	<thead>
		<tr>
			<th>Nombre</th>
			<th></th>
			<th></th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($busq as $k => $f){ ?>
			<tr class="lider <?php echo "lider_$f[uId]"; ?>" uId="<?php echo "lider_$f[uId]"; ?>" id="<?php echo "lider_$f[id]"; ?>">
				<td ><?php echo $f['nombre']; ?></td>
				<td style="text-align: right;"><i class="glyphicon glyphicon-trash manita rojo delRecLider"></i></td>
				<td style="text-align: right;"><i class="glyphicon glyphicon-eye-open manita verRecLider"></i></td>
			</tr>
		<?php } ?>
	</tbody>
</table>