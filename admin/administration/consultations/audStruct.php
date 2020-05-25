<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Consultations

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
					var rj = jsonF('analysis/socialMon/json/getDims.php',{padre:dimElemId});
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
			var nivelMax = 0;
			var padre = 0;
			$.each($('.dimSelAud'), function(index, val) {
				var nivel = this.id.split('_')[1];
				if($(this).val() != '' && nivel > nivelMax){
					nivelMax = parseInt(nivel);
					padre = $(this).val();
				}
			});
			var levelType = $('#levelTypeAud').val();
			if(levelType != ''){
				var audiencesId = <?php echo $_POST['elemId']; ?>;
				var consultationsId = <?php echo $_POST['consultationsId']; ?>;
				var rj = jsonF('admin/administration/consultations/json/addAud.php',{
					audiencesId:audiencesId,
					nivelMax:nivelMax,
					padre:padre,
					consultationsId:consultationsId,
					levelType:levelType,
				});
				console.log(rj);
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
					<option value="">- - - <?php echo $d['nombre']; ?> - - -</option>
					<?php foreach ($dimsElems as $de){ ?>
						<option value="<?php echo $de['id']; ?>"><?php echo $de['nombre']; ?></option>
					<?php } ?>
				</select>
			</div>
		<?php } ?>
	</div>
</div>

<?php if (count($dims) > 0){ ?>
	<div class="row" style="margin-top: 10px;">
		<div class="col-6">
			<select class="form-control" id="levelTypeAud">
				<option value=""><?php echo TR('levelTypeAud'); ?></option>
				<option value="1"><?php echo TR("onlyThis"); ?></option>
				<option value="2"><?php echo TR("onlyChildrens"); ?></option>
				<option value="3"><?php echo TR("thisAndChildrens"); ?></option>
			</select>
		</div>
		<div class="col-6">
			<span class="btn btn-shop" id="addAud"><?php echo TR('add'); ?></span>
		</div>
	</div>
<?php } ?>
