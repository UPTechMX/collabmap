<?php
if($_POST['ajax'] == 1){
	session_start();
	include_once '../../lib/j/j.func.php';
	$nivel = $_SESSION['CM']['admin']['nivel'];
	$uId = $_SESSION['CM']['admin']['usrId'];
	if($nivel != 10){
		exit('No tienes acceso');
	}

}

// $revisadas = $db->query("SELECT r.id as repId, vu.*, v.aceptada,t.nombre as tNom, m.nombre as mNom, v.fechaIngreso, v.id as vId,
// 	rot.fecha, rot.fechaLimite, e.nombre as estatus, r.nombre as rNom, c.nombre as cNom, p.nombre as pNom,t.POS
// 	FROM VisitasUsuarios vu
// 	LEFT JOIN Visitas v ON v.id = vu.visitasId
// 	LEFT JOIN Estatus e ON v.aceptada = e.estatus
// 	LEFT JOIN Rotaciones rot ON rot.id = v.rotacionesId
// 	LEFT JOIN Repeticiones r ON r.id = rot.repeticionesId
// 	LEFT JOIN Tiendas t ON t.id = rot.tiendasId
// 	LEFT JOIN Marcas m ON m.id = t.marcasId
// 	LEFT JOIN Clientes c ON m.clientesId = c.id
// 	LEFT JOIN Proyectos p ON p.id = r.proyectosId
// 	WHERE vu.usuariosId = $uId AND vu.asignada = 1 AND v.aceptada >= 90 AND v.aceptada <= 100 
// 	ORDER BY c.nombre ,v.aceptada, v.fechaIngreso")->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);


$revisadas = $db->query("SELECT r.id as repId, vu.*, vu.estatus as vEstatus,t.nombre as tNom, 
	m.nombre as mNom, v.fechaIngreso, v.id as vId, rot.fecha, rot.fechaLimite, e.nombre as estatus, 
	r.nombre as rNom, c.nombre as cNom, p.nombre as pNom,t.POS, rot.repeticionesId, rot.visitaAct, pagos.estatus as estatusPagoRev
	FROM VisitasUsuarios vu
	LEFT JOIN Visitas v ON v.id = vu.visitasId
	LEFT JOIN Pagos pagos ON pagos.visitasId = v.id AND pagos.concepto = 3
	LEFT JOIN Estatus e ON v.aceptada = e.estatus
	LEFT JOIN Rotaciones rot ON rot.id = v.rotacionesId
	LEFT JOIN Repeticiones r ON r.id = rot.repeticionesId
	LEFT JOIN Tiendas t ON t.id = rot.tiendasId
	LEFT JOIN Marcas m ON m.id = t.marcasId
	LEFT JOIN Clientes c ON m.clientesId = c.id
	LEFT JOIN Proyectos p ON p.id = r.proyectosId
	WHERE vu.usuariosId = $uId 
	ORDER BY c.nombre,p.nombre ,vu.estatus, v.fechaIngreso")->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);

// print2($revisadas);
?>

<script type="text/javascript">
	$('.verRes').click(function(event) {
		var repId = this.id.split('_')[1];
		// console.log(repId);
		$('#res_'+repId).toggle();
		if($('#res_'+repId).is(':visible')){
			$(this).removeClass('glyphicon-chevron-right');
			$(this).addClass('glyphicon-chevron-down');
		}else{
			$(this).addClass('glyphicon-chevron-right');
			$(this).removeClass('glyphicon-chevron-down');
		}
	});

</script>

<script type="text/javascript">
	$(document).ready(function() {
	});
</script>

<div class="nuevo">
	Visitas revisadas
</div>
<table class="table">
	<tbody>
		<?php 
		$cNom = null; $pNom = null;
		foreach ($revisadas as $rId => $vis){
			$dat = $vis[0];
		?>
			<?php
			if ($cNom != $dat['cNom'] || $pNom != $dat['pNom']){
				$cNom = $dat['cNom'];
				$pNom = $dat['pNom'];
			?>			
				<tr>
					<td colspan="8" class="nuevo">
						<?php echo "$dat[cNom] ( $dat[pNom] ) ";?>
					</td>
				</tr>
			<tr>
				<th>Periodo</th>
				<th style="text-align: center;">Revisadas</th>
				<th style="text-align: center;">Por Revisar</th>
				<th style="text-align: center;">Verificadas</th>
				<th style="text-align: center;">Por pagar</th>
				<th style="text-align: center;">Pagadas</th>
				<th style="text-align: center;">Total</th>
				<th style="text-align: center;"></th>
			</tr>
			<?php } ?>
			<?php 
				$numRev = 0;
				$numPub = 0;
				$numPPagar = 0;
				$numRech = 0;
				$numPagadas = 0;
				$porRev = 0;
				$total = 0;

				foreach ($vis as $v){ 
					$numRev = ($v['vEstatus'] >= 90 && $v['vEstatus'] <= 91)?$numRev+1:$numRev;
					$porRev = $v['vEstatus'] == 70?$porRev+1:$porRev;
					$numPub = ($v['vEstatus'] == 100 || $v['vEstatus'] < 20)?$numPub+1:$numPub;
					$numPPagar = $v['estatusPagoRev'] == 10 ? $numPPagar+1:$numPPagar;
					$numPagadas = $v['estatusPagoRev'] == 30 ? $numPagadas+1:$numPagadas;
					// $total = $v['vEstatus'] >= 70 ? $total+1:$total;
					$porRevShop = $v['vEstatus'] == 91 ? $porRevShop+1:$porRevShop;
					$total++;
				} 
			?>
				<tr>
					<td><?php echo $dat['rNom']; ?></td>
					<td style="text-align: center;"><?php echo $numRev; ?></td>
					<td style="text-align: center;"><?php echo $porRev; ?></td>
					<td style="text-align: center;"><?php echo $numPub; ?></td>
					<td style="text-align: center;"><?php echo $numPPagar; ?></td>
					<td style="text-align: center;"><?php echo $numPagadas; ?></td>
					<td style="text-align: center;"><?php echo $total; ?></td>
					<td style="text-align: center;">
						<i class="glyphicon glyphicon-chevron-right verRes manita" id="verRes_<?php echo "$dat[repeticionesId]"; ?>"></i>
					</td>
				</tr>
				<tr id="res_<?php echo $dat['repeticionesId'];?>" style="display:none;">
					<td colspan="100%">
						<table class="table">
							<thead>
								<tr>
									<th>POS</th>	
									<th>Marca</th>
									<th>Tienda</th>
									<th>Estatus</th>
									<th>Estatus pago</th>
								</tr>
							</thead>
							<tbody>
							<?php 
							foreach ($vis as $v){
								$pago = estatusPagoTexto($v['estatusPagoRev']);
							?>
								<tr>
									<td><?php echo "$v[POS]"; ?></td>
									<td><?php echo $v['mNom']; ?></td>
									<td><?php echo $v['tNom']; ?></td>
									<td><?php echo $v['estatus']; ?></td>
									<td><?php echo $pago; ?></td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</td>
				</tr>
		<?php } ?>
	</tbody>
</table>