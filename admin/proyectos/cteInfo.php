<?php  
	session_start();
	include_once '../../lib/j/j.func.php';
	include_once '../../lib/php/creaTokens.php';

	checaAcceso(30);
	$nivel = $_SESSION['IU']['admin']['nivel'];
	// print2($nivel);

	if($_POST['eleId'] != ''){
		// print2($_POST);
		$datC = $db-> query("SELECT * FROM Clientes WHERE id = $_POST[eleId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	}
	else{
		$nuevo = true;
		// nuevo, creamos webId y verificamos que no exista para otra junta
		$token = getTokenForTableField('Clientes', 'token', 6);
		// echo "Nuevo token> ".$token;
		$datC['token'] = $token;
	}

	if( !empty($datC['estadosId'])){
		$edos = $db->query("SELECT * FROM Estados WHERE id = $datC[estadosId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	}
	if( !empty($datC['municipiosId'])){

	$munc = $db->query("SELECT * FROM Municipios WHERE id = $datC[municipiosId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	}
?>

<script type="text/javascript">
	$(document).ready(function() {


		$('#cancelaCte').click(function(event) {
			popUp('admin/proyectos/cancelarCliente.php',{act:1,cteId:<?php echo $_POST['eleId'];?>},function(){},{});
		});

		$('#genBarCode').click(function(event) {
			console.log('aa');
			$('#formBarCode').submit();
		});


	});
</script>


<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">Información del cliente</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>

<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<table class="table" border="0">
		<tr>
			<th width="25%">Nombre</th>
			<td colspan="3">
				<?php echo $datC['nombre']; ?>
			</td>
		</tr>
		<tr>
			<th>Apellido paterno</th>
			<td colspan="3">
				<?php echo $datC['aPat']; ?>
			</td>
		</tr>
		<tr>
			<th>Apellido materno</th>
			<td colspan="3">
				<?php echo $datC['aMat']; ?>
			</td>
		</tr>
		<tr>
			<th>Calle</th>
			<td colspan="3">
				<?php echo $datC['calle']; ?>
			</td>
		</tr>
		<tr>
			<th>Numero exterior</th>
			<td>
				<?php echo $datC['numeroExt']; ?>
			</td>
			<th width="25%">Numero interior</th>
			<td>
				<?php echo $datC['numeroInt']; ?>
			</td>
		</tr>
		<tr>
			<th>Lote</th>
			<td colspan="3">
				<?php echo $datC['lote']; ?>
			</td>
		</tr>
		<tr>
			<th>CP</th>
			<td colspan="3">
				<?php echo $datC['codigoPostal']; ?>
			</td>
		</tr>
		<tr>
			<th>Estado</th>
			<td id="eNom"><?php echo $edos['nombre']; ?></td>
			<th id="mNom">Municipio</th>
			<td id="eNom"><?php echo $munc['nombre']; ?></td>
		</tr>
		<tr>
			<th>Colonia</th>
			<td colspan="3" id="tdColonia">
				<?php echo $datC['colonia']; ?>
			</td>
		</tr>
		<tr>
			<th>Territorial</th>
			<td colspan="3" id="tdColonia">
				<?php echo $datC['territorial']; ?>
			</td>
		</tr>
		<tr>
			<th>Barrio</th>
			<td colspan="3" id="tdColonia">
				<?php echo $datC['barrio']; ?>
			</td>
		</tr>
		<tr>
			<th>Entre calles</th>
			<td colspan="3">
				<?php echo $datC['entreCalles']; ?>
			</td>
		</tr>
		<tr>
			<th>Referencia</th>
			<td colspan="3">
				<?php echo $datC['referencia']; ?>
			</td>
		</tr>
		<tr>
			<th>e-mail</th>
			<td colspan="3">
				<?php echo $datC['mail']; ?>
			</td>
		</tr>
		<tr>
			<th>Teléfono</th>
			<td>
				<?php echo $datC['telefono']; ?>
			</td>
			<th>Celular</th>
			<td>
				<?php echo $datC['celular1']; ?>
			</td>
		</tr>
		<tr>
			<th>Celular 2</th>
			<td>
				<?php echo $datC['celular2']; ?>
			</td>
			<th>Celular 3</th>
			<td>
				<?php echo $datC['celular3']; ?>
			</td>
		</tr>
		<tr>
			<th>Identificador alfanumérico único</th>
			<td colspan="3">
				<?php echo $nuevo ? $token : $datC['token']; ?>
			</td>
		</tr>
		<tr>
			<td colspan="4" style="text-align: center;">
				<span class="btn btn-sm btn-shop" id="genBarCode" >
					<i class="glyphicon glyphicon-barcode"></i>
					Generar código de barras
				</span>
			</td>
		</tr>
	</table>
</div>
<form target="_blank" action="../general/codigo.php" method="post" id="formBarCode">
	<input type="hidden" name="token" value="<?php echo $datC['token']; ?>">
</form>
<div class="modal-footer">
	<table class="table">
		<tr>
			<td style="text-align: left;">
				<span id="cancelaCte"  class="btn btn-sm btn-cancel">Cancelar cliente</span>
			</td>
			<td style="text-align: right;">
				<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-shop">Cerrar</span>
			</td>
		</tr>
	</table>
</div>
