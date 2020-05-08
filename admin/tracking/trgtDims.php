<?php

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50); // checaAcceso Tracking

	$dims = $db->query("SELECT * FROM Dimensiones 
		WHERE elemId = $_POST[trgtId] AND type = 'structure' ORDER BY nivel")->fetchAll(PDO::FETCH_ASSOC);


?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#trgtDims .dimSelChk').change(function(event) {
			// console.log('aa');
			var dimElemId = $(this).val();
			var dimNivel = this.id.split('_')[1];
			var niveles = $(this).closest('#trgtDims').find('.dimSelChk').length;
			// console.log(dimElemId,dimNivel,niveles);
			if(dimNivel < niveles){
				// console.log('aa');
				var r = []
				if(dimElemId != ''){
					var rj = jsonF('analysis/socialMon/json/getDims.php',{padre:dimElemId});
					// console.log(rj);
					r = $.parseJSON(rj);
				}
				var nextNivel = parseInt(dimNivel)+1;
				var elemSel = $(this).closest('#trgtDims').find('#dimSelChk_'+nextNivel);
				
				var nomNext = $(elemSel.find('option')[0]).text();
				optsSel(r,elemSel,false,nomNext,false);
				elemSel.val('').trigger('change');

			}

		});

		$('#genChk').click(function(event) {
			var nivelMax = 0;
			var padre = 0;
			$.each($('.dimSelChk'), function(index, val) {
				var nivel = this.id.split('_')[1];
				if($(this).val() != '' && nivel > nivelMax){
					nivelMax = parseInt(nivel);
					padre = $(this).val();
				}
			});
			var trgtId = <?php echo $_POST['trgtId']; ?>;
			// console.log({nivelMax:nivelMax,padre:padre,trgtId:trgtId});
			$('#trackingList').load(rz+'admin/tracking/trackingList.php',{nivelMax:nivelMax,padre:padre,trgtId:trgtId});

		});

		$("#trackingList").on('click', '.nomElem', function(event) {
			event.preventDefault();
			var nivel = $(this).attr('nivel');
			var elemDim = this.id.split('_')[1];

			// $('#trgtDims #dimSelChk_'+nivel).val(elemDim);

			var nivelMax = nivel;
			var padre = elemDim;
			var trgtId = <?php echo $_POST['trgtId']; ?>;
			// console.log({nivelMax:nivelMax,padre:padre,trgtId:trgtId,popUp:1});
			popUp('admin/tracking/trackingList.php',{nivelMax:nivelMax,padre:padre,trgtId:trgtId,popUp:1});

		});


	});
</script>

<div class='row'>
	<?php 
	foreach ($dims as $k => $d){
		if($k > count($dims)-2){
			break;
		}
		if($k == 0){
			$dimsElems = $db->query("SELECT * FROM DimensionesElem 
				WHERE dimensionesId = $d[id]
				ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
		}else{
			$dimsElems = array();
		}
	?>
		<div class="col-3">
			<select class="form-control dimSelChk" id="dimSelChk_<?php echo "$d[nivel]"; ?>">
				<option value="">- - - <?php echo $d['nombre']; ?> - - -</option>
				<?php foreach ($dimsElems as $de){ ?>
					<option value="<?php echo $de['id']; ?>"><?php echo $de['nombre']; ?></option>
				<?php } ?>
			</select>
		</div>
	<?php } ?>
</div>
<div style="text-align: left; margin-top: 10px;">
	<span class="btn btn-shop" id="genChk">
		<?php echo TR('generate'); ?>
	</span>
</div>

