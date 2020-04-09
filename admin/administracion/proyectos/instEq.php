<?php  

	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);



// print2($elems);

?>

<?php

$nivel = $_SESSION['IU']['admin']['nivel'];
if($nivel<50){
	exit('No tienes acceso a esta área');
}

?>

<script type="text/javascript">
	$(document).ready(function() {

		// console.log(elems);
		optsSel(areasEquipos,$('#selArea'),false,'- - - Tipo de equipo - - -',false);

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

		$('#selArea').change(function(event) {
			var areasId = $(this).val();
			$('#elemSels').empty();
			if(areasId !=""){
				$('#addEqElem').show();
				for(var i in dimensiones[areasId]){
					var dim = dimensiones[areasId][i];
					var divId = 'divSel_'+dim.val;
					var selId = 'sel_'+dim.val;
					$('<div>')
					.attr({class:'col-4',id:divId})
					.html(`
						<select id="${selId}" class="form-control selElem selElem_${dim.nivel}" nivel="${dim.nivel}"></select>
					`)
					.appendTo('#elemSels');

					optsSel(elems[dim.val],$('#'+selId),false,'- - '+dim.nom+' - -', false);
					if(dim.nivel != 1){

						$.each($('#'+selId+' option'), function(index, val) {
							 if($(this).attr('value') != ''){
							 	$(this).hide();
							 }
						});
					}
					
				}
			}else{
				$('#addEqElem').hide();
			}
		});

		$('#elemSels').on('change', '.selElem', function(event) {
			event.preventDefault();
			var nivel = parseInt($(this).attr('nivel'));
			var areasId = $('#selArea').val();
			var numDim = dimensiones[areasId].length;
			var elem = $(this).val();
			// console.log(elem);
			if(nivel < numDim){
				var nivelSig = nivel+1;

				$.each($('.selElem_'+nivelSig+' option'), function(index, val) {
					 if($(this).attr('value') != ''){
					 	$(this).hide();
					 }
				});

				$('.padre_'+elem).show();
			}
		});

		$('#addEqElem').click(function(event) {
			var areasId = $('#selArea').val();
			var numDim = dimensiones[areasId].length;
			var elem = $('.selElem_'+numDim).val();
			// console.log($('#tr_'+elem).length);
			if($('#trEq_'+elem).length == 0){
				if(elem != ''){
					var dat = {};
					dat.dimensionesElemId = elem;
					dat.instalacionesId = <?php echo $_POST['eleId']; ?>;

					var rj = jsonF('admin/administracion/proyectos/json/json.php',{datos:dat,acc:1,opt:6});
					// console.log(rj);
					var r = $.parseJSON(rj);
					// console.log(r);
					if(r.ok == 1){
						// $('#popUp').modal('toggle');
						$('#instEqList').load(rz+'admin/administracion/proyectos/instEqList.php',{eleId:dat.instalacionesId});
					}

				}				
			}else{
				alertar('Ya existe el equipo seleccionado en esta instalación',function(){},{});
			}
		});

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			Equipos que definen al tipo de instalación
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<select class="form-control" id="selArea" name="selArea"></select>
	<div class="row" id="elemSels" style="margin: 10px 0px;"></div>
	<div style="text-align: right;">
		<span class="btn btn-sm btn-shop" id="addEqElem" style="display: none;">Agregar</span>
	</div>
	<div id="instEqList" style="margin: 10px 0px;"><?php include_once 'instEqList.php'; ?></div>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-shop">Cerrar</span>
	</div>
</div>
