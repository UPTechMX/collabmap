<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Consultations

$dims = $db->query("SELECT * FROM Dimensiones 
	WHERE elemId = $_POST[elemId] AND type = 'documents' ORDER BY nivel")->fetchAll(PDO::FETCH_ASSOC);



?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#HSStructureFilter .dimSelDoc').change(function(event) {
			var dimElemId = $(this).val();
			var dimNivel = this.id.split('_')[1];
			var niveles = $(this).closest('#HSStructureFilter').find('.dimSelDoc').length;
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
				var elemSel = $(this).closest('#HSStructureFilter').find('#dimSelDoc_'+nextNivel);
				
				var nomNext = $(elemSel.find('option')[0]).text();
				optsSel(r,elemSel,false,nomNext,false);
				elemSel.val('').trigger('change');
			}
		});

		$('#generate').click(function(event) {
			var nivelMax = 0;
			var padre = 0;
			$.each($('.dimSelDoc'), function(index, val) {
				var nivel = this.id.split('_')[1];
				if($(this).val() != '' && nivel > nivelMax){
					nivelMax = parseInt(nivel);
					padre = $(this).val();
				}
			});
			var elemId = <?php echo $_POST['elemId']; ?>;
			$('#chartStatDoc').load(rz+'admin/administration/consultations/chartStatDoc.php',{padre:padre,nivelMax:nivelMax,elemId:elemId})

		});
		$('#download').click(function(event) {
			var nivelMax = 0;
			var padre = 0;
			$.each($('.dimSelDoc'), function(index, val) {
				var nivel = this.id.split('_')[1];
				if($(this).val() != '' && nivel > nivelMax){
					nivelMax = parseInt(nivel);
					padre = $(this).val();
				}
			});
			var elemId = <?php echo $_POST['elemId']; ?>;
			$('#padreForm').val(padre);
			$('#nivelMaxForm').val(nivelMax);
			$('#elemIdForm').val(elemId);
			$('#dwlForm').submit();

		});

	});
</script>
<div style="background-color: whitesmoke;padding: 10px;border-radius: 5px;<?php echo $displayStruct; ?>" id="HSStructureFilter">
	<h4><?php echo TR('structureFilter'); ?></h4>
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
				<select class="form-control dimSelDoc" id="dimSelDoc_<?php echo "$d[nivel]"; ?>">
					<option value="">- - - <?php echo $d['nombre']; ?> - - -</option>
					<?php foreach ($dimsElems as $de){ ?>
						<option value="<?php echo $de['id']; ?>"><?php echo $de['nombre']; ?></option>
					<?php } ?>
				</select>
			</div>
		<?php } ?>
	</div>
	<div style="text-align: right;">
		<span class="btn btn-shop" id="generate"><?php echo TR('generate'); ?></span>
		<span class="btn btn-shop" id="download"><?php echo TR('download'); ?></span>
	</div>

	<form action="administration/consultations/dwlComments.php" target="_blank" method="post" id="dwlForm">
		<input type="hidden" name="padre" id="padreForm">
		<input type="hidden" name="nivelMax" id="nivelMaxForm">
		<input type="hidden" name="elemId" id="elemIdForm">
	</form>
</div>


<div id="chartStatDoc"><?php include_once 'chartStatDoc.php'; ?></div>