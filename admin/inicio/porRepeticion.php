<?php  
	session_start();
	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
		include_once raiz().'/lib/php/usrInt.php';
		$usrId = $_SESSION['CM']['admin']['usrId'];
		$usr = new Usuario($usrId);
	}
	checaAcceso(50);

	$pry = $usr->getProyectos();
	foreach ($pry as $cId => $p) {
		$p = $p[0];
		if($p['finalizado'] == 1)
			continue;

		$reps = $usr->getRepeticiones($p['id']);
		foreach ($reps as $r) {
		}
	}
?>

<script type="text/javascript">
	$(document).ready(function() {

		$('#modal').css({width:''});
		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:''});
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:''});
		});



		$('#env').click(function(event) {
			$('#popUp').modal('toggle');
		});

	});
</script>

<div class="modal-header nuevo grad-shop-v" >
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white">×</button>
	<div style="text-align: center;">
		<h4>Visitas <?php echo $_POST['dato'] ?> por Repetición</h4>
	</div>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<table class="table">
		<thead>
			<tr>
				<th>Cliente</th>
				<th>Proyecto</th>
				<th>Repeticion</th>
				<th style="text-align: right;"><?php echo ucfirst($_POST['dato']); ?></th>
			</tr>
		</thead>
		<tbody>
			
		<?php 
		$pry = $usr->getProyectos();
		foreach ($pry as $cId => $p) {
			$p = $p[0];
			if($p['finalizado'] == 1)
				continue;
			
			$reps = $usr->getRepeticiones($p['id']);
			foreach ($reps as $r) {
				switch ($_POST['dato']) {
					case 'faltantes':
						$sql = "SELECT COUNT(*) as cuenta
							FROM Rotaciones r 
							WHERE (r.estatus >= 0 AND r.estatus != 4 AND r.estatus < 20)
							AND r.repeticionesId = $r[id]";
						break;
					case 'enviadas':
						$sql = "SELECT COUNT(*) as cuenta
							FROM Rotaciones r 
							WHERE (r.estatus >= 20 AND r.estatus < 60)
							AND r.repeticionesId = $r[id]";
						break;
					case 'revisadas':
						$sql = "SELECT COUNT(*) as cuenta
							FROM Rotaciones r 
							WHERE (r.estatus >= 90 AND r.estatus < 100)
							AND r.repeticionesId = $r[id]";
						break;
					case 'enviadas ayer':
						$sql = "SELECT COUNT(*) as cuenta
							FROM Rotaciones r 
							LEFT JOIN RotacionesHistorial rh ON rh.rotacionesId = r.id AND rh.estatus = r.estatus
							WHERE (r.estatus >= 20 AND r.estatus < 60)
							AND TIMESTAMPDIFF(DAY, rh.timestamp, NOW()) = 1
							AND r.repeticionesId = $r[id]";
						break;
					case 'que requieren atención':
						$sql = "SELECT COUNT(*) as cuenta
							FROM Rotaciones r 
							WHERE (r.estatus = 91)
							AND r.repeticionesId = $r[id]";
						break;
					default:
						break;
				}
				$rots = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];

				if($rots['cuenta'] == 0){
					continue;
				}

		?>

				<tr>
					<td><?php echo $p['cNom']; ?></td>
					<td><?php echo $p['nombre']; ?></td>
					<td><?php echo $r['nombre']; ?></td>
					<td style="text-align: right;"><?php echo $rots['cuenta']; ?></td>
				</tr>

		<?php
			}
		}
		?>
		</tbody>
	</table>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="env" class="btn btn-sm btn-shop">Aceptar</span>
	</div>
</div>
