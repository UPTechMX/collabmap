<?php

session_start();

include_once '../lib/j/j.func.php';
include_once raiz().'lib/php/calcCuest.php';
include_once raiz().'lib/php/checklist.php';



$chk = new Checklist($_POST['vId']);
$chk->getGeneral($_POST['vId']);
$chk->getVisita();

// print2($chk);

?>

<script type="text/javascript">
	$(document).ready(function() {
		// $('#revisar').click(function(event) {
		// 	$Spelling.SpellCheckInWindow('resumen');
		// });

		// $Spelling.DefaultDictionary = "Espanol";
		// $Spelling.UserInterfaceTranslation = "es";
		// $Spelling.SpellCheckAsYouType('resumen');


		$('.oblig').keyup(function(event) {
			$(this).prev('.livespell_textarea').css({backgroundColor:'rgba(255,255,255,1)'});
			$(this).css({backgroundColor:'rgba(255,255,255,1)'});
		});

		// $( "#fechaRealizacion" ).datepicker({ changeYear: true });
		// $( "#fechaRealizacion" ).datepicker( "option", "dateFormat", 'yy-mm-dd' );
		$('#fechaRealizacion').val('<?php echo $chk->visita['fechaRealizacion']; ?>');

		soloNumeros($('#gasto'));


		// $('.timepicker').timepicker({
		//     timeFormat: 'HH:mm ',
		//     interval: 1,
		//     minTime: '8',
		//     maxTime: '10:00pm',
		//     // defaultTime: '11',
		//     startTime: '8:00',
		//     dynamic: false,
		//     dropdown: true,
		//     scrollbar: true
		// });

	});
</script>
<form id="tablaGral">
	<div class="nomSubArea" style="margin-top: 10px;"><?php echo TR('summary'); ?></div>
	<div style="margin-top: 10px;">
		<!-- <div style="text-align: right;margin-bottom:5px;" id="revisar">
			<span class="btn btn-sm btn-shop">Revisar ortografía</span>
		</div> -->
		<textarea class="form-control " id="resumen" spellcheck="true" lang="es" name="resumen"
			style="display: block;width: 100%;height: 100px;padding: 6px 12px;font-size: 14px;line-height: 
				1.42857143;color: #555;background-color: #fff;background-image: none;  resize: vertical;
				border: 1px solid #ccc;border-radius: 4px;"><?php echo $chk->visita['resumen']; ?></textarea>

	</div>
</form>

<div style="text-align:center;width: 96%;margin-top: 5px;">
	<span id="siguienteGral" class="btn btn-sm btn-shop"><?php echo TR('next'); ?> ></span>
</div>
