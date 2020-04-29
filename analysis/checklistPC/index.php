
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





	});
</script>

<div id="checklistAnalysisCont">

	<div id="chkCont"><?php include_once 'chkCont.php'; ?></div>

</div>



