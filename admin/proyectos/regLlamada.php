<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">Registro de llamadas</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>
</div>


<?php  

	include_once '../../lib/j/j.func.php';
	session_start();
	checaAcceso(49);
	$usrId = $_SESSION['IU']['admin']['usrId'];
	// echo $usrId;
	$datCte = $db->query("SELECT * FROM Clientes WHERE id = $_POST[cteId]")->fetchAll(PDO::FETCH_ASSOC)[0];

	$cteNom = "$datCte[nombre] $datCte[aPat] $datCte[aMat] ";

	$llamadas = $db->query("SELECT lh.*, CONCAT(u.nombre,' ',u.aPat,' ',u.aMat) as uNom
		FROM LlamadasHist lh 
		LEFT JOIN usrAdmin u ON u.id = lh.usuariosId
		WHERE clientesId = $_POST[cteId]")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#nvoReg').click(function(event) {
			/* Act on the event */
			var cteId = <?php echo $_POST['cteId']; ?>;
			popUpMapa('admin/proyectos/regLlamadaAdd.php',{cteId:cteId})
		});
	});
</script>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
<div style="margin-bottom: 10px;">
	<table class="table" style="font-size: small;">
		<tr>
			<th  width="20%">Cliente:</th>
			<td style="text-align: left;" colspan="3"><?php echo $cteNom; ?></td>
		</tr>
		<tr>
			<th>Telefono:</th>
			<td style="text-align: left;"><?php echo $datCte['telefono']; ?></td>
			<th width="20%">Celular:</th>
			<td style="text-align: left;"><?php echo $datCte['celular1']; ?></td>
		</tr>
		<tr>
			<th>Celular: 2</th>
			<td style="text-align: left;"><?php echo $datCte['celular2']; ?></td>
			<th>Celular: 3</th>
			<td style="text-align: left;"><?php echo $datCte['celular3']; ?></td>
		</tr>
	</table>
</div>
<div class="nuevo">Historial</div>
<table class="table">
	<thead>
		<tr>
			<th>Fecha</th>
			<th>Usuario</th>
			<th>Comentarios</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($llamadas as $l){ ?>
			<tr>
				<td><?php echo $l['timestamp']; ?></td>
				<td><?php echo $l['uNom']; ?></td>
				<td><?php echo $l['comentarios']; ?></td>
			</tr>
		<?php } ?>
		<?php if (count($llamadas) == 0){ ?>
			<tr>
				<td colspan="3" style="text-align: center;">NO HAY LLAMADAS REGISTRADAS A ESTE CLIENTE</td>
			</tr>
		<?php } ?>
	</tbody>
</table>

</div>

<div class="modal-footer">
	<div style="text-align: right;">
		<span id="nvoReg" class="btn btn-sm btn-shop">Nuevo registro</span>
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-shop">Cerrar</span>
	</div>
</div>
