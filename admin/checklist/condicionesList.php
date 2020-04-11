<?php  

	include_once '../../lib/j/j.func.php';
	checaAcceso(50); // checaAcceso Checklist

	// print2($_POST);

	$conds = $db->query("SELECT * FROM Condicionales 
		WHERE aplicacion = '$_POST[aplicacion]' AND eleId = $_POST[eleId] ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

	$accs = $_POST['accs'];
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.delCond').click(function(event) {
			var condId = this.id.split('_')[1];
			conf('¿Está seguro que desea elilminar la condición?',{condId:condId},function(e){
				var rj = jsonF('admin/checklist/json/json.php',{acc:4,condId:e.condId,chkId:checklistId});
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#delCond_'+e.condId).closest('li').remove();
				}

			})
		});
		$( "#condsSort" ).sortable({
			handle: '.mueveCond',
			scrollSpeed: 5,
			update: function( event, ui ) {
				// var pregId = $(this).closest('.subpreguntas').attr('id').split('_')[1];
				// console.log(pregId);
				$('#saveCondsOrden').show();
			}
		});

		$('#saveCondsOrden').click(function(event) {
			
			var bloques = [];
			var orden = 1;
			$.each($('.condEle'), function(index, val) {
				if(this.id != ''){
					// console.log(this.id);
					bloques.push({"id":this.id.split('_')[1],"orden":orden++});
				}
			});
			// console.log(bloques);
			var rj = jsonF('admin/checklist/json/json.php',{bloques:bloques,acc:5,opt:9,chkId:checklistId});
			var r = $.parseJSON(rj);
			if(r.ok == 1){
				$('#saveCondsOrden').hide();
			}

		});



		// $("#condsSort" ).disableSelection();

	});
</script>
<span class="btn btn-shop btn-sm" id="saveCondsOrden" style="display: none;">
	<i class="glyphicon glyphicon-floppy-disk">&nbsp;</i>Guardar orden
</span>

<ul class="list-group condsSort" style="margin-top: 10px;" id="condsSort">
	<?php foreach ($conds as $c): ?>
		<li class="list-group-item condEle" id="condEle_<?php echo $c['id'];?>">
			<div class="row">
				<div class="col-sm-12 col-md-5 col-lg-5 condicion" id="condicion">
					<div  style=" width: 100%;word-wrap:break-word">						
						<i id="" class="glyphicon glyphicon-resize-vertical arrastra mueveCond"></i>
						<i id="delCond_<?php echo $c['id']; ?>" class="glyphicon glyphicon-trash manita rojo delCond"></i>
						<?php echo $c['condicion']; ?>&nbsp;
					</div>
				</div>
				<div class="col-sm-12 col-md-2 col-lg-2 condicion arrastra" id="condicion" >
					<div style="text-align: center;">
						<?php echo $accs[$c['accion']]; ?>
					</div>
				</div>
				<div class="col-sm-12 col-md-5 col-lg-5 condicion arrastra" id="condicion">
					<div style="width:100%;word-wrap:break-word;	">
						<?php echo $c['valor']; ?>
					</div>
					
				</div>
			</div>
		</li>
	<?php endforeach ?>
</ul>
