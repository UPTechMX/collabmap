<?php

if (!function_exists('raiz')) {
	include_once '../../lib/j/j.func.php';
}
checaAccesoConsult();// checaAcceso Consultations

$dims = $db->query("SELECT * FROM Dimensiones 
	WHERE elemId = $_POST[elemId] AND type = '$_POST[type]' ORDER BY nivel")->fetchAll(PDO::FETCH_ASSOC);

// print2($_POST);

?>
<script type="text/javascript">
	$(document).ready(function() {
		
		$('.dimSelAud').change(function(event) {
			var dimElemId = $(this).val();
			var dimNivel = this.id.split('_')[1];
			var niveles = $(this).closest('.structCont').find('.dimSelAud').length;
			// console.log(dimElemId,dimNivel,niveles);
			if(dimNivel < niveles){
				// console.log('aa');
				var r = []
				if(dimElemId != ''){
					var rj = jsonF('consultations/profile/json/getDims.php',{padre:dimElemId});
					// console.log(rj);
					r = $.parseJSON(rj);
					// console.log(r);
				}
				var nextNivel = parseInt(dimNivel)+1;
				var elemSel = $(this).closest('.structCont').find('#dimSelAud_'+nextNivel);
				
				var nomNext = $(elemSel.find('option')[0]).text();
				optsSel(r,elemSel,false,nomNext,false);
				elemSel.val('').trigger('change');

			}

		});

		$('#addAud').click(function(event) {
			var numDim = <?php echo count($dims); ?>;
			var nivelMax = 0;
			var padre = 0;
			$.each($('.dimSelAud'), function(index, val) {
				var nivel = this.id.split('_')[1];
				if($(this).val() != '' && nivel > nivelMax){
					nivelMax = parseInt(nivel);
					padre = $(this).val();
				}
			});
			var valid = true;
			if(padre == 0){
				valid = false;
			}
			if(nivelMax == 0){
				valid = false;
			}
			var audiencesId = <?php echo $_POST['elemId']; ?>;
			// console.log({
			// 	audiencesId:audiencesId,
			// 	nivelMax:nivelMax,
			// 	padre:padre,
			// });
			if(valid){
				var dat = {
					audiencesId:audiencesId,
					nivelMax:nivelMax,
					padre:padre,
				}
				var rj = jsonF('consultations/profile/json/json.php',{acc:2,datos:dat,elemId:34});
				// console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#audiencesList').load(rz+'consultations/profile/audiencesList.php');
				}
			}else{
				alertar('<?php echo TR("invalidAudSel"); ?>');
			}

		});
	});
</script>
<div style="margin-top: 10px;">
	<div class='row structCont'>
		<?php 
		foreach ($dims as $k => $d){
			// if($k > count($dims)-2){
			// 	break;
			// }
			if($k == 0){
				$dimsElems = $db->query("SELECT * FROM DimensionesElem 
					WHERE dimensionesId = $d[id]
					ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
			}else{
				$dimsElems = array();
			}
		?>
			<div class="col-3">
				<select class="form-control dimSelAud" id="dimSelAud_<?php echo "$d[nivel]"; ?>">
					<option value=""><?php echo $d['nombre']; ?></option>
					<?php foreach ($dimsElems as $de){ ?>
						<option value="<?php echo $de['id']; ?>"><?php echo $de['nombre']; ?></option>
					<?php } ?>
				</select>
			</div>
		<?php } ?>
	</div>
</div>
<div style="margin-top: 10px;text-align: right;">
	<span class="btn btn-shop" id="addAud"><?php echo TR('add'); ?></span>
</div>
