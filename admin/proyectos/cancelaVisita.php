<?php  

	include_once '../../lib/j/j.func.php';
	session_start();
	checaAcceso(49);
	$usrId = $_SESSION['CM']['admin']['usrId'];
	// echo $usrId;
	$fechaHoy = date("Y-m-d");
	// echo $fechaHoy;

	// print2($_POST);
	$datCte = $db->query("SELECT * FROM Clientes WHERE id = $_POST[cteId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	$cteNom = "$datCte[nombre] $datCte[aPat] $datCte[aMat] ";

	$elem = explode('_',$_POST['act'])[1];
	// echo $elem;
	$datVis = $db->query("SELECT v.timestamp, CONCAT(u.nombre,' ',u.aPat,' ',u.aMat) as uNom, 
		v.fecha, v.hora, ei.nombre as eNom, CONCAT(ue.nombre,' ',ue.aPat,' ',ue.aMat) as ueNom
		FROM Visitas v
		LEFT JOIN usrAdmin u ON u.id = v.usuarioProgramado
		LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
		LEFT JOIN usrAdmin ue ON ue.id = ei.instalador
		WHERE v.id = $_POST[vId]")->fetchAll(PDO::FETCH_ASSOC)[0];

?>

<script type="text/javascript">
	$(document).ready(function() {

		var comentarios = new Quill('#comentarios', {
		  theme: 'snow'
		});


		$('#env').click(function(event) {
			var dat = {};
			dat.visitasId = <?php echo $_POST['vId']; ?>;
			dat.comentarios = comentarios.root.innerHTML.trim();

			var stripComent =  strip(dat.comentarios);
			var allOk = true;
			if(stripComent.length < 10){
				alertar('El comentario deben contener al menos 10 caracteres',function(){},{});
				allOk = false;
				// console.log('aa')
			}else{
				if(allOk){
					conf('¿Deseas cancelar la <?php echo $elem; ?>?',{dat:dat},function(e){
						var act = '<?php echo $elem; ?>';
						var cteId = '<?php echo $_POST['cteId']; ?>';
						var rj = jsonF('admin/proyectos/json/agendas.php',{datos:e.dat,acc:3,opt:2,act:act});
						// console.log(rj);
						try{
							var r = $.parseJSON(rj);
							console.log(r);
						}catch(e){
							console.log('Error de parseo');
							console.log(rj);
							var r = {ok:0};
						}
						// console.log(r);
						if(r.ok == 1){
							$('#popUp').modal('toggle');
							console.log(cteId);
							$('#tr_'+cteId).load(rz+'admin/proyectos/clienteFila.php',{cId: cteId},function(){});
						}
					});


				}
			}
			
		});
	});
</script>


<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">Cancelar <?php echo $elem; ?></h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<table class="table">
		<tr>
			<td> Cliente:</td>
			<td> <?php echo $cteNom; ?></td>
		</tr>
		<?php if ( $elem != 'visita'){ ?>
			<tr>
				<td>Equipo</td>
				<td>
					<?php echo $datVis['eNom']; ?>
				</td>
			</tr>
		<?php } ?>
		<tr>
			<td><?php echo $elem == 'visita'?'visitador':'instalador'; ?></td>
			<td>
				<?php echo $elem == 'visita'?$datVis['uNom']:(!empty($datVis['ueNom'])?$datVis['ueNom']:'Pendiente de asignación'); ?>
			</td>
		</tr>
		<tr>
			<td>Fecha programada</td>
			<td>
				<?php echo $datVis['fecha']; ?>
			</td>
		</tr>
		<tr>
			<td><?php echo $elem == 'visita'?'Hora programada':'Horario programado'; ?> </td>
			<td>
				<?php echo $elem == 'visita'? ($datVis['hora']):($datVis['horario'] == 1?'Matutino':'Vespertino'); ?>
			</td>
		</tr>
		<tr>
			<td>Fecha de programacion</td>
			<td>
				<?php echo $datVis['timestamp']; ?>
			</td>
		</tr>
	</table>
	<div style="border-bottom: solid 1px;">Comentarios de cancelación:</div>
	<div id="comentarios" style="height: 200px;"></div>

</div>
<div class="modal-footer">
	<div style="text-align: left;width: 100%;">
		<span id="env" class="btn btn-sm btn-cancel">Cancelar visita</span>
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-shop">Salir</span>
	</div>
</div>
