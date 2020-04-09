<?php


	$visitas = $db->query("SELECT c.id as cId, v.*, c.nombre as cNom, p.id as pId, p.nombre as pNom, 
		rep.id as rId, rep.nombre as rNom, t.POS, t.nombre as tNom, e.Nombre as estatus, 
		pv.estatus as pv, pr.estatus as pr, v.reembolsoTipo
		FROM Visitas v
		LEFT JOIN Estatus e ON e.estatus = v.aceptada
		LEFT JOIN Rotaciones rot ON rot.id = v.rotacionesId
		LEFT JOIN Repeticiones rep ON rep.id = rot.repeticionesId
		LEFT JOIN Proyectos p ON p.id = rep.proyectosId
		LEFT JOIN Clientes c ON c.id = p.clientesId
		LEFT JOIN Tiendas t ON t.id = rot.tiendasId
		LEFT JOIN Pagos pv ON pv.visitasId = v.id AND pv.concepto = 2
		LEFT JOIN Pagos pr ON pr.visitasId = v.id AND pr.concepto = 1
		WHERE shoppersId = $uId 
		ORDER BY p.nombre, rep.nombre")->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
?>

<script type="text/javascript">
</script>

<div class="nuevo">Visitas del shopper</div>

<div class="row">
	<?php foreach ($visitas as $c){ ?>
		<div class="col-md-6" style="margin-top: 10px;">
			<div class="nuevo"><?php echo $c[0]['cNom']; ?></div>
			<div style="height: 300px;overflow-y: auto;">
				<table class="table" >
					<?php
						$pId = '';
						$rId = '';
						foreach ($c as $v){
							if($pId != $v['pId']){
								$pId = $v['pId'];
								// echo "<tr><td colspan='6' class='nuevo' style='background-color:grey;'>$v[pNom]</td></tr>";
							}
							if($rId != $v['rId']){
								$rId = $v['rId'];
							?>
								<tr id="rep_<?php echo $rId?>" class="verVisRep">
									<td colspan='6' class='nuevo verRep manita' style='background-color:whitesmoke;color:black'>
										&nbsp;<?php echo $v[rNom]; ?>
									</td>
								</tr>
								<tr class="rep_<?php echo $rId?>">
									<th>POS</th>
									<th>Nombre del POS</th>
									<th style="text-align: center;">fecha</th>
									<th style="text-align: center;">Estatus</th>
									<th style="text-align: center;">Estatus pago visita</th>
									<th style="text-align: center;">Estatus pago reembolso</th>
								</tr>
							<?php }
					?>
						<tr class="rep_<?php echo $rId?>">
							<td><?php echo $v['POS'] ; ?></td>
							<td><?php echo $v['tNom']; ?></td>
							<td ><?php echo $v['fecha']; ?></td>
							<td style="text-align: center;"><?php echo $v['estatus']; ?></td>
							<td style="text-align: center;"><?php echo estatusPagoTexto($v['pv']); ?></td>
							<td style="text-align: center;">
								<?php if ($v['reembolsoTipo'] >= 1){ ?>
									<?php echo estatusPagoTexto($v['pr']); ?>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</table>
			</div>
		</div>
	<?php } ?>
</div>

