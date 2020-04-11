<?php  

	include_once '../../lib/j/j.func.php';
	checaAcceso(50); // checaAcceso Checklist
	$tipos = $db->query("SELECT * FROM Tipos WHERE tabla = 'Preguntas' AND siglas != 'sub'")->fetchAll(PDO::FETCH_ASSOC);
	if($_POST['subpregId'] != ''){
		$datP = $db-> query("SELECT * FROM Preguntas WHERE id = $_POST[subpregId]")->fetch(PDO::FETCH_ASSOC);
	}

	$sql = "SELECT p.id as pId, a.id as aId, b.id as bId, c.id as cId
		FROM Preguntas p 
		LEFT JOIN Areas a ON p.areasId = a.id
		LEFT JOIN Bloques b ON a.bloquesId = b.id
		LEFT JOIN Checklist c ON b.checklistId = c.id
		WHERE p.id = $_POST[pregId]";

	$areaId = $db->query("SELECT areasId FROM Preguntas WHERE id = $_POST[pregId]")->fetch(PDO::FETCH_NUM)[0];

	$info = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

	$identificador = "p_$info[cId]_$info[bId]_$info[aId]_";
	// print2($info);

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

		$(".txArea").jqte({
			source:false,
			rule: false,
			link:false,
			unlink: false,
			format:false
		});

		<?php if(isset($_POST['pregId'])): ?>
			$('#chkIdentificador').click(function(event) {
				if($('#identificador').is(':visible')){
					$('#identificador').val("<?php echo $datP['identificador'];?>")
					$('#identificador').toggle();
				}else{
					conf('Cambiar el identificador modifica las asociaciones entre preguntas de distintos checklist',{},function(){
						$('#identificador').toggle();
					});
				}
			});
		<?php endif; ?>


		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			dat.justif = $('#justif').is(':checked')?1:0;
			var allOk = camposObligatorios('#nEmp');
			<?php 
				if(isset($_POST['subpregId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[subpregId];";
				}else{
					echo 'var acc = 1;';
					echo "dat.areasId = $areaId;";
					echo 'dat.orden = $(".pregEle").length+1;';
					echo 'dat.identificador = "'.$identificador.'";';
					echo 'dat.subareasId = '.$_POST['pregId'].";";
				}
			?>
			dat.influyeValor = $('#influyeValor').is(':checked')?1:0;
			// console.log(dat);
			if(allOk){
				var rj = jsonF('admin/checklist/json/json.php',{datos:dat,acc:acc,opt:7,chkId:checklistId});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#subpreguntasList_<?php echo $_POST["pregId"]; ?>').load(rz+'admin/checklist/subpreguntasList.php',
						{ajax:1,pregId:<?php echo $_POST['pregId']; ?>});
				}
			}

		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php if(isset($_POST['subpregId'])): ?>
				Editar 
			<?php else: ?>
				Nueva 
			<?php endif; ?>
			pregunta
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
				<td style="vertical-align: middle;">Pregunta</td>
				<td>
					<textarea name="pregunta" id="pregunta" class="form-control txArea"><?php echo $datP['pregunta'] ?></textarea>
				</td>
				<td></td>
			</tr>
			<tr>
				<td style="vertical-align: middle;">Comentarios al visitador/instalador</td>
				<td>
					<textarea name="comShopper" id="comShopper" class="form-control txArea"><?php echo $datP['comShopper'] ?></textarea>
				</td>
				<td></td>
			</tr>
			<!-- <tr>
				<td style="vertical-align: middle;">Comentarios al verificador</td>
				<td>
					<textarea name="comVerif" id="comVerif" class="form-control txArea"><?php echo $datP['comVerif'] ?></textarea>
				</td>
				<td></td>
			</tr> -->
			<tr>
				<td>Tipo de pregunta</td>
				<td>
					<select id="tiposId" name="tiposId" class="form-control oblig">
						<option value="">- - - - - - - - -</option>
						<?php foreach ($tipos as $t): ?>
							<option value="<?php echo $t['id']; ?>" 
								<?php echo ($t['id'] == $datP['tiposId']?'selected':''); ?> >
									<?php echo $t['nombre']; ?>
							</option>
						<?php endforeach ?>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Tantos</td>
				<td>
					<input type="text" value="<?php echo isset($datP['puntos'])?$datP['puntos']:1; ?>" 
						name="puntos" id="puntos" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Contabilizar valor</td>
				<td>
					<?php if ($_POST['subpregId'] != ''): ?>
						<input type="checkbox" name="influyeValor" id="influyeValor" 
						<?php echo $datP['influyeValor'] == 1?'checked':''; ?> />
					<?php else: ?>
						<input type="checkbox" name="influyeValor" id="influyeValor" checked />
					<?php endif ?>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Justificar</td>
				<td><input type="checkbox" name="justif" id="justif" <?php echo $datP['justif'] == 1?'checked':''; ?> ></td>
				<td></td>
			</tr>
			<?php if(isset($_POST['pregId'])): ?>
				<tr>
					<td><span class="btn btn-sm btn-default" id="chkIdentificador">Cambiar identificador</span></td>
					<td>
						<input type="text" value="<?php echo $datP['identificador']; ?>" 
						name="identificador" id="identificador" class="form-control oblig" style="display: none;" />
					</td>
					<td></td>
				</tr>
			<?php endif; ?>


		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>
