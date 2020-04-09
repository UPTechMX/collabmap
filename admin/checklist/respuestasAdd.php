<?php  

	include_once '../../lib/j/j.func.php';
	checaAcceso(50);
	if($_POST['respId'] != ''){
		$datP = $db-> query("SELECT * FROM Respuestas WHERE id = $_POST[respId]")->fetch(PDO::FETCH_ASSOC);
	}

	// print2($datP);
	$sql = "SELECT p.id as pId, a.id as aId, b.id as bId, c.id as cId
		FROM Preguntas p 
		LEFT JOIN Areas a ON p.areasId = a.id
		LEFT JOIN Bloques b ON a.bloquesId = b.id
		LEFT JOIN Checklist c ON b.checklistId = c.id
		WHERE p.id = $_POST[pregId]";

	$info = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

	$identificador = "r_$info[cId]_$info[bId]_$info[aId]_$info[pId]_";
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

		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			dat.justif = $('#justif').is(':checked')?1:0;

			var allOk = camposObligatorios('#nEmp');
			<?php 
				if(isset($_POST['respId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[respId];";
				}else{
					echo 'var acc = 1;';
					echo "dat.preguntasId = $_POST[pregId];";
					echo 'dat.orden = $("#respuestasList_'.$_POST['pregId'].' .respEle").length+1;';
					echo 'dat.identificador = "'.$identificador.'"+dat.orden';
				}
			?>

			// console.log(dat);
			if(allOk){
				var rj = jsonF('admin/checklist/json/json.php',{datos:dat,acc:acc,opt:8,chkId:checklistId});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#respuestasList_<?php echo $_POST["pregId"]; ?>').load(rz+'admin/checklist/respuestasList.php',
						{ajax:1,pregId:<?php echo $_POST['pregId']; ?>});
				}
			}

		});

	});
</script>
<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php if(isset($_POST['respId'])): ?>
				Editar 
			<?php else: ?>
				Nueva 
			<?php endif; ?>
			respuesta
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
				<td style="vertical-align: middle;">Respuesta</td>
				<td>
					<textarea name="respuesta" id="respuesta" class="form-control txArea"><?php echo $datP['respuesta'] ?></textarea>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Valor</td>
				<td>
					<input type="text" value="<?php echo $datP['valor']; ?>" name="valor" id="valor" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td>Justificar</td>
				<td><input type="checkbox" name="justif" id="justif" <?php echo $datP['justif'] == 1?'checked':''; ?> ></td>
				<td></td>
			</tr>
		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>
