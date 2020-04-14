<?php

session_start();

include_once '../../lib/j/j.func.php';
include_once raiz().'lib/php/calcCuest.php';
include_once raiz().'lib/php/checklist.php';



$chk = new Checklist($_POST['vId']);
$chk->getGeneral($_POST['vId']);
$chk->getVisita($_POST['vId']);

// print2($chk);

?>

<script type="text/javascript">
	$(document).ready(function() {

		$('.oblig').keyup(function(event) {
			$(this).prev('.livespell_textarea').css({backgroundColor:'rgba(255,255,255,1)'});
			$(this).css({backgroundColor:'rgba(255,255,255,1)'});
		});

		$( "#fecha" ).datepicker({ changeYear: true });
		$( "#fecha" ).datepicker( "option", "dateFormat", 'yy-mm-dd' );
		$('#fecha').val('<?php echo $chk->visita['fecha']; ?>');

		$('#env').click(function(event) {
			// console.log('asas');
			event.preventDefault
			var allOk = camposObligatorios('#tablaGral');

				// console.log(datos);
			if(allOk){
				var datos = $('#tablaGral').serializeObject();
				// console.log(datos);
				var rj = jsonF('admin/revisores/json/json.php',{acc:2,datos:datos,vId:<?php echo $_POST['vId'];?>});
				// console.log(rj);
				var r = $.parseJSON(rj);
				$('#popUp').modal('toggle');
				if(r.ok == 1){
					<?php if ($_POST['noRevisor'] == 1) { ?>
						// var rotId = <?php echo $_POST['rotId']; ?>;
						// var repId = <?php echo $_POST['repId']; ?>;
						var hash = '<?php echo $_POST['hash']; ?>';
						// console.log('noRevisor = 1');
						setTimeout(function(){
							$('#visita').load(rz+'admin/proyectos/revision.php',{vId:<?php echo $_POST['vId'];?>,hash:hash,rev:1});
						},500)
						
					<?php }else{ ?>
						// console.log('noRevisor != 1');
						$('#porRevisar').load(rz+'admin/revisores/visita.php',{div:1,vId:<?php echo $_POST['vId']; ?>});
					<?php }  ?>

				}

			}

		});

	});
</script>

<div class="modal-header nuevo grad-shop-v" >
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:white">×</button>
	<div style="text-align: center;">
		<h4>
			<?php echo TR('generalData'); ?>
		</h4>
	</div>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="tablaGral">
		<div class="nomSubArea">Resumen</div>
		<div style="margin-top: 10px;">
			<textarea class="form-control oblig" id="resumen" spellcheck="true" lang="es" name="resumen"
				style="display: block;width: 100%;height: 100px;padding: 6px 12px;font-size: 14px;line-height: 
					1.42857143;color: #555;background-color: #fff;background-image: none;  resize: vertical;
					border: 1px solid #ccc;border-radius: 4px;"><?php echo $chk->visita['resumen']; ?></textarea>

		</div>
	</form>

</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel">Cancelar</span>
		<span id="env" class="btn btn-sm btn-shop">Enviar</span>
	</div>
</div>
