<?php 

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50);


	$checklists = $db->query("SELECT * FROM ChecklistExt")->fetchAll(PDO::FETCH_ASSOC);
	// print2($checklists);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#checklists').on('click', '.edtChk', function(event) {
			event.preventDefault();
			var checklistId = $(this).closest('li').attr('id').split('_')[1];
			$('#bloques').load(rz+'admin/checklistExt/bloques.php',{checklistId:checklistId});
			$('#general').load(rz+'admin/checklistExt/general.php',{checklistId:checklistId});
			$('#infoChk').load(rz+'admin/checklistExt/infoChk.php',{checklistId:checklistId});
			$('#areas').empty();
			$('#preguntas').empty();
		});

		$('#checklists').on('click', '.modifChk', function(event) {
			event.preventDefault();
			var checklistId = $(this).closest('li').attr('id').split('_')[1];
			console.log('asas');
			popUp('admin/checklistExt/checklistAdd.php',{checklistId:checklistId,aplicacion:'chk'},function(e){},{});
		});

		$('#checklists').on('click', '.condChk', function(event) {
			event.preventDefault();
			var checklistId = $(this).closest('li').attr('id').split('_')[1];
			popUp('admin/checklistExt/condiciones.php',{eleId:checklistId,aplicacion:'chk'},function(e){},{});
		});

		$('#checklists').on('click', '.tPromChk', function(event) {
			event.preventDefault();
			var checklistId = $(this).closest('li').attr('id').split('_')[1];
			popUp('admin/checklistExt/chkTipoProm.php',{chkId:checklistId},function(e){},{});
		});

	});
</script>
<ul class="list-group" id="checklists">
	<?php foreach ($checklists as $c){ ?>
	<li id="checklist_<?php echo $c['id']; ?>" class="list-group-item">
		<div class="row">
			<div class="col-md-7 col-sm-7" style="border:none 1px;">
				<?php echo $c['nombre']; ?>
			</div>
			<div class="col-md-1 col-sm-1" style="border:none 1px;">
				<i class="glyphicon glyphicon-pencil manita modifChk"></i>
			</div>
			<div class="col-md-1 col-sm-1" style="border:none 1px;">
				<i class="glyphicon glyphicon-question-sign manita condChk"></i>
			</div>
			<div class="col-md-1 col-sm-1" style="border:none 1px;">
				<i class="glyphicon glyphicon-ok manita tPromChk"></i>
			</div>
			<div class="col-md-1 col-sm-1" style="border:none 1px;">
				<i class="glyphicon glyphicon-chevron-right manita edtChk"></i>
			</div>
		</div>
	</li>
	<?php } ?>
</ul>
