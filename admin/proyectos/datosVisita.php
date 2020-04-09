<script type="text/javascript">
	$(document).ready(function() {


	});
</script>
<?php  

	include_once '../../lib/j/j.func.php';
	session_start();
	checaAcceso(49);
	$usrId = $_SESSION['CM']['admin']['usrId'];
	// echo $usrId;
	$datCte = $db->query("SELECT * FROM Clientes WHERE id = $_POST[cteId]")->fetchAll(PDO::FETCH_ASSOC)[0];

	$cteNom = "$datCte[nombre] $datCte[aPat] $datCte[aMat] ";
	// print2($_POST);
	// print2($datCte);
	$elem = explode('_',$_POST['act'])[1];

	$datVis = $db->query("SELECT v.timestamp, CONCAT(u.nombre,' ',u.aPat,' ',u.aMat) as uNom, 
		v.fecha, v.hora, ei.nombre as eNom, CONCAT(ue.nombre,' ',ue.aPat,' ',ue.aMat) as ueNom, v.horario
		FROM Visitas v
		LEFT JOIN usrAdmin u ON u.id = v.usuarioProgramado
		LEFT JOIN EquiposInstalacion ei ON ei.id = v.equipo
		LEFT JOIN usrAdmin ue ON ue.id = ei.instalador
		WHERE v.id = $_POST[vId]")->fetchAll(PDO::FETCH_ASSOC)[0];
?>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">Datos de la <?php echo $elem == 'visita'?'visita':'instalación'; ?></h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" id="cieraModal">
	  <span aria-hidden="true">&times;</span>
	</button>
</div>


<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>

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
</div>


<div class="modal-footer">
	<div style="text-align: right;">
		<span data-dismiss="modal" class="btn btn-sm btn-shop">Cerrar</span>
	</div>
</div>

