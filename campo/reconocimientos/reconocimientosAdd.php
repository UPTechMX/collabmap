<?php  

	include_once '../../lib/j/j.func.php';
	checaAcceso(60);

	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT * FROM Reconocimientos WHERE id = $_POST[eleId]")->fetchAll(PDO::FETCH_ASSOC)[0];
		// print2($datC);
	}
	
	$usuarios = $db->query("SELECT id as val, nombre as nom, 'clase' as clase 
		FROM usrAdmin WHERE id > 1")->fetchAll(PDO::FETCH_ASSOC);

	// print2($usuarios);
?>

<?php

$nivel = $_SESSION['IU']['admin']['nivel'];
if($nivel<60){
	exit('No tienes acceso a esta área');
}

?>

<script type="text/javascript">
	$(document).ready(function() {

		var usuarios = <?php echo atj($usuarios); ?>;
		optsSel(usuarios,$('#usuariosId'),false,'- - Usuario - -');
		<?php if($_POST['eleId'] != ''){ ?>
			// console.log('aa');
			$('#usuariosId').val(<?php echo $datC['usuariosId']; ?>);
		<?php } ?>

		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:''});
			// event.preventDefault();
			if(event.keyCode == 13){
				event.preventDefault();
				return false;
			}
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:''});
		});

		$('#fIni').datepicker( { dateFormat: 'yy-mm-dd',changeMonth: true, changeYear: true });
		$('#fFin').datepicker( { dateFormat: 'yy-mm-dd',changeMonth: true, changeYear: true });

		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			var allOk = camposObligatorios('#nEmp');


			<?php 
				if(isset($_POST['eleId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[eleId];";
				}else{
					echo 'var acc = 1;';
				}
			?>


			if(allOk){
				var rj = jsonF('campo/reconocimientos/json/json.php',{datos:dat,acc:acc,opt:1});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#reconocimientosList').load(rz+'campo/reconocimientos/reconocimientosList.php',{ajax:1});
				}
			}

		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php if(isset($_POST['eleId'])): ?>
				Editar 
			<?php else: ?>
				Nuevo 
			<?php endif; ?>
			reconocimiento
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nEmp">
		<table class="table" border="0">
			<tr>
				<td>Nombre</td>
				<td>
					<input type="text" value="<?php echo $datC['nombre']; ?>" name="nombre" id="nombre" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<!-- <tr>
				<td>Usuario que realizó el reconocimiento</td>
				<td>
					<select id="usuariosId" name="usuariosId" class="form-control"></select>
				</td>
				<td></td>
			</tr> -->

		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>
