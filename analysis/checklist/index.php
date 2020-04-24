
<script type="text/javascript">
	var pregsDesp = {};
	$(document).ready(function() {

		$('#structureFilter .dimSelChk').change(function(event) {
			var dimElemId = $(this).val();
			var dimNivel = this.id.split('_')[1];
			var niveles = $(this).closest('#structureFilter').find('.dimSelChk').length;
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
				var elemSel = $(this).closest('#structureFilter').find('#dimSelChk_'+nextNivel);
				
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
			var trgtChk = <?php echo $_GET['trgtChk']; ?>;
			$('#chkCont').load(rz+'analysis/checklist/chkCont.php',{nivelMax:nivelMax,padre:padre,trgtChk:trgtChk});
		});


	});
</script>

<?php 
	$displayStruct = count($dims) == 1? 'display:none;':'';
?>
<div id="checklistAnalysisCont">

	<div style="margin-top:10px;background-color: whitesmoke;padding: 10px;border-radius: 5px;<?php echo $displayStruct; ?>" id="structureFilter">
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
	</div>

	<div id="chkCont"><?php include_once 'chkCont.php'; ?></div>

</div>



