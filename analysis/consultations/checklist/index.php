
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
			var consChk = <?php echo $_GET['consChk']; ?>;
			$('#chkCont').load(rz+'analysis/consultations/checklist/chkCont.php',{nivelMax:nivelMax,padre:padre,consChk:consChk});
		});


	});
</script>

<?php 
	// $displayStruct = count($dims) == 1? 'display:none;':'';
$consultation = $db->query("SELECT * FROM Consultations c
	LEFT JOIN ConsultationsChecklist cc ON cc.consultationsId = c.id
	WHERE cc.id = $_GET[consChk]")->fetchAll(PDO::FETCH_ASSOC)[0];
?>
<div id="checklistAnalysisCont">

	<div style="display:none;margin-top:10px;background-color: whitesmoke;padding: 10px;border-radius: 5px;<?php echo $displayStruct; ?>" id="structureFilter">

		<div style="text-align: left; margin-top: 10px;">
			<span class="btn btn-shop" id="genChk">
				<?php echo TR('generate'); ?>
			</span>
		</div>
	</div>
	<div style="border:solid 1px black; border-radius: 5px;padding: 10px;margin-top: 10px;">
		<?php echo "<strong>".TR('description')."</strong>: "; ?><br/>
		<?php echo $consultation['description']; ?>
	</div>
	<div id="chkCont"><?php include_once 'chkCont.php'; ?></div>

</div>



