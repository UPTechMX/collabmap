<?php 

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50);

	// include_once '../../../lib/j/j.func.php';
	$clientes = $db-> query("SELECT * FROM Clientes")->fetchAll(PDO::FETCH_ASSOC);
	if(isset($_GET['chkId'])){
		$info = $db->query("SELECT c.repeticionesId as repId, c.id as chkId, p.id as pryId, cl.id as clId
			FROM Checklist c 
			LEFT JOIN Repeticiones r ON r.id = c.repeticionesId
			LEFT JOIN Proyectos p ON p.id = r.proyectosId
			LEFT JOIN Clientes cl ON cl.id = p.clientesId
			WHERE c.id = $_GET[chkId]")->fetch(PDO::FETCH_ASSOC);

		$coord = atj($info);
	}else{
		$coord = '{"clId":0}';
	}
	// print2($coord);

	$etapas = $db->query("SELECT * FROM Etapas ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);
	// print2($etapas);
?>

<script type="text/javascript">
	var checklistId = '';
	$(document).ready(function() {


		$('#etapasSel').change(function(event) {
			var eleId = $(this).val();
			if(eleId != ""){
				var chksj = jsonF('admin/checklist/json/json.php',{opt:4,acc:3,eleId:eleId});
				try{
					var chks = $.parseJSON(chksj);
					console.log(chks);
				}catch(e){
					console.log('error de parseo');
					console.log(chksj);
					chks = [];

				}
				// optsSel(chks,$('#checklistsSel'),false,"");
				// console.log(chks);
				$('#checklists').empty();
				for(var i in chks){
					$('<li>')
					.attr({
						id:'chk_'+chks[i].val,
						class:'list-group-item'
					})
					.html(
						'<div class="row">'+
							'<div class="col-5" style="border:none 1px;">'+
								chks[i].nom+
							'</div>'+
							'<div class="col-1" style="border:none 1px;">'+
								'<i class="glyphicon glyphicon-pencil manita edtInfoChk"></i>'+
							'</div>'+
							'<div class="col-1" style="border:none 1px;">'+
								'<i class="glyphicon glyphicon-question-sign manita condChk"></i>'+
							'</div>'+
							'<div class="col-1" style="border:none 1px;">'+
								'<i class="glyphicon glyphicon-ok manita tPromChk"></i>'+
							'</div>'+
							'<div class="col-1" style="border:none 1px;">'+
								'<i class="glyphicon glyphicon-chevron-right manita edtChk"></i>'+
							'</div>'+
						'</div>'
					)
					.appendTo('#checklists');
				}

			}else{
				$('#checklistsSel').empty()
				var o = new Option("- - - - - - - -","");
				$('#checklistsSel').append(o);
			}
			$('#checklistsSel').trigger('change');
		});
		$('#checklistsSel').change(function(event) {
			var checklistId = $(this).val();
			if(checklistId != ""){
				$('#edtChklist').show();
			}else{
				$('#edtChklist').hide();
			}
		});

		$('#checklists').on('click', '.edtInfoChk', function(event) {
			event.preventDefault();
			checklistId = $(this).closest('li').attr('id').split('_')[1];
			// console.log(checklistId);
			popUp('admin/checklist/checklistAdd.php',{checklistId:checklistId},function(e){},{});

		});

		$('#checklists').on('click', '.edtChk', function(event) {
			event.preventDefault();
			checklistId = $(this).closest('li').attr('id').split('_')[1];
			$('#bloques').load(rz+'admin/checklist/bloques.php',{checklistId:checklistId});
			$('#general').load(rz+'admin/checklist/general.php',{checklistId:checklistId});
			$('#areas').empty();
			$('#preguntas').empty();
		});

		$('#checklists').on('click', '.condChk', function(event) {
			event.preventDefault();
			var checklistId = $(this).closest('li').attr('id').split('_')[1];
			popUp('admin/checklist/condiciones.php',{eleId:checklistId,aplicacion:'chk'},function(e){},{});
		});

		$('#checklists').on('click', '.tPromChk', function(event) {
			event.preventDefault();
			var checklistId = $(this).closest('li').attr('id').split('_')[1];
			popUp('admin/checklist/chkTipoProm.php',{chkId:checklistId},function(e){},{});
		});

		var coord = <?php echo $coord; ?>;
		// console.log(coord);

		if(coord.clId != 0){
			$('#clientesSel').val(coord.clId).trigger('change');
			$('#proyectosSel').val(coord.pryId).trigger('change');
			$('#repeticionesSel').val(coord.repId).trigger('change');
			$('#chk_'+coord.chkId).find('.edtChk').trigger('click');
		}

		$('#addChk').click(function(event) {
			popUp('admin/checklist/checklistAdd.php',{},function(e){},{});
		});

		$('#dupChk').click(function(event) {
			popUp('admin/checklist/checklistCopy.php',{},function(e){},{});
		});

	});
</script>
<div class="row">
	<div class="col-3">
		<div class="nuevo">Etapa</div>
		<select class="form-control" id="etapasSel">
			<option value="">- - - - - - - -</option>
			<?php foreach ($etapas as $e){ ?>
				<option value="<?php echo $e['nomInt']; ?>"><?php echo $e['nombre']; ?></option>
			<?php } ?>
		</select>
		<span id="addChk" class="btn btn-sm btn-shop" style="margin: 10px 0px;">Agregar Cuestionario</span>
		<span id="dupChk" class="btn btn-sm btn-shop">Duplicar Cuestionario</span>
	</div>
	<div class="col-3" >
		<div class="nuevo">Checklist</div>
		<div style="height: 150px;overflow-y: auto;">			
			<ul class="list-group" id="checklists">			
			</ul>
		</div>
	</div>
</div>
<hr/>
<div class="row" id="general" style="margin-top:20px;margin-bottom: 20px;"></div>
<div class="row">
	<div class="col-md-6" style="min-height: 350px;max-height: 350px;overflow-y: auto;" id="bloques"></div>
	<div class="col-md-6" style="min-height: 350px;max-height: 350px;overflow-y: auto;" id="areas"></div>
</div>
<div id="preguntas"></div>
