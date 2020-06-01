<?php

// print2($consInf);

$dims = $db->query("SELECT * FROM Dimensiones 
	WHERE elemId = $consInf[projectsId] AND type = 'complaints' ORDER BY nivel")->fetchAll(PDO::FETCH_ASSOC);

// print2($dims);

?>
<script type="text/javascript">
	$(document).ready(function() {
		
		$('.dimSelCompSt').change(function(event) {
			var dimElemId = $(this).val();
			var dimNivel = this.id.split('_')[1];
			var niveles = $(this).closest('.structCont').find('.dimSelCompSt').length;
			// console.log(dimElemId,dimNivel,niveles);
			if(dimNivel < niveles){
				// console.log('aa');
				var r = []
				if(dimElemId != ''){
					var rj = jsonF('analysis/socialMon/json/getDims.php',{padre:dimElemId});
					// console.log(rj);
					r = $.parseJSON(rj);
					// console.log(r);
				}
				var nextNivel = parseInt(dimNivel)+1;
				var elemSel = $(this).closest('.structCont').find('#dimSelCompSt_'+nextNivel);
				
				var nomNext = $(elemSel.find('option')[0]).text();
				optsSel(r,elemSel,false,nomNext,false);
				elemSel.val('').trigger('change');

			}

		});

		$('#addAud').click(function(event) {
			var numDim = <?php echo count($dims); ?>;
			var nivelMax = 0;
			var padre = 0;
			$.each($('.dimSelCompSt'), function(index, val) {
				var nivel = this.id.split('_')[1];
				if($(this).val() != '' && nivel > nivelMax){
					nivelMax = parseInt(nivel);
					padre = $(this).val();
				}
			});
			var levelType = $('#levelTypeAud').val();
			var valid = true;
			valid = levelType != '';
			if(padre == 0){
				if(levelType == 1 || levelType == 3 || levelType == 5){
					valid = false;
				}
			}
			if(nivelMax == numDim){
				if(levelType > 1){
					valid = false;
				}
			}

			if(valid){
				// var audiencesId = <?php echo $_POST['elemId']; ?>;
				// var consultationsId = <?php echo $_POST['consultationsId']; ?>;
				// var rj = jsonF('admin/administration/consultations/json/addAud.php',{
				// 	audiencesId:audiencesId,
				// 	nivelMax:nivelMax,
				// 	padre:padre,
				// 	consultationsId:consultationsId,
				// 	levelType:levelType,
				// });
				// // console.log(rj);
				// var r = $.parseJSON(rj);
				// if(r.ok == 1){
				// 	$('#audiencesList').load(rz+'admin/administration/consultations/audiencesList.php',{consultationsId:consultationsId});
				// }
			}else{
				alertar('<?php echo TR("invalidAudSel"); ?>');
			}

		});
	});
</script>
<div style="margin-top: 20px;" class="structCont">
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
		<div class="" style="padding:5px 10px 0px 10px;">
			<select class="form-control dimSelCompSt" id="dimSelCompSt_<?php echo "$d[nivel]"; ?>">
				<option value=""><?php echo $d['nombre']; ?></option>
				<?php foreach ($dimsElems as $de){ ?>
					<option value="<?php echo $de['id']; ?>"><?php echo $de['nombre']; ?></option>
				<?php } ?>
			</select>
		</div>
	<?php } ?>
	<div class="" style="padding:5px 10px 0px 10px; margin-bottom: 10px;" >
		<div>
			<textarea class="form-control"></textarea>
		</div>
	</div>
</div>




