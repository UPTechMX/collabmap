<?php  

	date_default_timezone_set('America/Mexico_City');	
	// include_once '../../lib/j/j.func.php';

	$clientes = $db -> query("SELECT * FROM Clientes ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

	// print2($clientes);

?>
<script type="text/javascript">
	$(document).ready(function() {
		$('.dwlAcc').click(function(event) {
			var cteId = this.id.split('_')[1];
			$('#forma_'+cteId).submit();
		});
	});
</script>

<div>
	<table class="table">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Siglas</th>
				<th style="text-align: center;"></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ($clientes as $c):
				$prys = $db -> query("SELECT * FROM Proyectos WHERE clientesId = $c[id]")->fetchAll(PDO::FETCH_ASSOC);
				$cuenta = count($prys);
				$cAct = 0;
				if($cuenta > 0){
					foreach ($prys as $pry) {
						$pryAct = $db->query("SELECT * FROM Repeticiones 
							WHERE proyectosId = $pry[id]  AND (elim != 1 OR elim IS NULL)
							AND fechaIni <= '$fechaHoy' AND fechaFin >= '$fechaHoy' ")->fetchAll(PDO::FETCH_ASSOC);
						$cAct += count($pryAct);
					}
				}
			?>
				<tr>
					<td><?php echo $c['nombre']; ?></td>
					<td><?php echo $c['siglas']; ?></td>
					<td style="text-align: center;">
						<i id="dwlAcc_<?php echo $c['id'];?>" class="glyphicon glyphicon-download-alt manita dwlAcc"></i>
						<form id="forma_<?php echo $c['id'];?>" target="_blank" method="POST"
							action="<?php echo aRaizHtml()."admin/descargas/dwlAccesos.php"?>">
							<input type="hidden" name="cteId" value="<?php echo $c['id'];?>">
						</form>

					</td>
				</tr>	
				
			<?php endforeach ?>
		</tbody>
	</table>	
</div>


