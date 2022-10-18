<?php
	session_start();
	$root = $_SESSION['CM']['raiz'];
	include_once $root.'lib/j/j.func.php';

	checaAccesoQuest();

	// print2($_POST);

	$targetInfo = $db->query("SELECT * FROM Targets WHERE id = $_POST[targetId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	// print2($targetInfo);
	$addStructure = $targetInfo['addStructure'] == 1;
	$dims = $db->query("SELECT * FROM Dimensiones WHERE elemId = $_POST[targetId] AND type = 'structure'")->fetchAll(PDO::FETCH_ASSOC);

?>
<script type="text/javascript">
	$(document).ready(function() {

		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:'white'});
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:'white'});
		});

		$('.dimSelAdd').change(function(event) {
			var dimElemId = $(this).val();
			var dimNivel = this.id.split('_')[1];
			var niveles = $('.dimSelAdd').length;
			console.log(dimElemId,parseInt(dimNivel),niveles);
			if(parseInt(dimNivel) < parseInt(niveles) ){

				var r = []
				if(dimElemId != ''){
					var rj = jsonF('questionnaires/targets/json/getDims.php',{padre:dimElemId});
					// console.log(rj);
					r = $.parseJSON(rj);
				}
				var nextNivel = parseInt(dimNivel)+1;
				// console.log('nextNivel = '+nextNivel);
				var elemSel = $('#dimSelAdd_'+nextNivel);
				
				var nomNext = $(elemSel.find('option')[0]).text();
				optsSel(r,elemSel,false,nomNext,false);
				elemSel.val('').trigger('change');

			}

		});


		<?php if ($addStructure){ ?>
			$('#env').click(function(event) {
				var dat = {};
				dat.nombre = $('#name').val();

				var sels = $('.dimSelAdd');
				if(sels.length == 0){
					dat.padre = 0;
				}else{
					dat.padre = $(sels[sels.length - 1]).val();
				}
				dat.nivel = sels.length+1;
				
				var targetId = <?php echo $_POST['targetId']; ?>;
				var usersTargetsId = <?php echo $_POST['usersTargetsId']; ?>;

				var allOk = camposObligatorios('#nTrgt');

				var elemNom = $('#name').val();
				var datCopy = dat;

				/*if(elemNom.length != 2){ // check digits
					$('#nTrgt #name').css({backgroundColor:'rgba(255,0,0,.5)'});
						$('#nTrgt #name').after(() => {
							return '<span id="duplicateWarning"><?php echo TR('2digitsName'); ?></span>'
						});
						$('#nTrgt #name').on('input', () => {
							if($('#nTrgt #name').length){
								$('#duplicateWarning').remove()
							}
						});
					allOk = false;
				}else{ // check duplication
					var rj = jsonF('questionnaires/targets/json/json.php',{datos:dat,acc:7,targetId:targetId});
					// console.log(rj);
					var r = $.parseJSON(rj);

					if(r.duplicated === 1){
						$('#nTrgt #name').css({backgroundColor:'rgba(255,0,0,.5)'});
						
						if($('#duplicateWarning').length == 0){
							$('#nTrgt #name').after(() => {
								return '<span id="duplicateWarning"><?php echo TR('duplicateRTWarning') ?></span>'
							});
						}

						$('#nTrgt #name').on('input', () => {
							if($('#nTrgt #name').length){
								$('#duplicateWarning').remove()
							}
						});

						allOk = false;
					}
				}*/

				if(allOk){
					// hide selection modal
					$('#popUp').modal('toggle');

					// tn = target's name
					var tn = $('#nTrgt tr td:first-child')

					// fd = form data
					var fd = $("#nTrgt :input");

					var selectRTconfTable = "";

					// build table data
					$(fd).each((i, el) => {
						if(i < fd.length-1){
							selectRTconfTable = selectRTconfTable + '<tr><td>' + tn[i].textContent + '</td><td>:</td><td>' + $(el).find(":selected").text() + '</td></tr>';
						}else{
							selectRTconfTable = selectRTconfTable + '<tr><td>' + tn[i].textContent + '</td><td>:</td><td>' + $(el).val() + '</td></tr>';
						}
					})

					// build table data
					selectRTConfData = '<?php echo TR('selectRTConfirmationText1'); ?>' + selectRTconfTable + '<?php echo TR('selectRTConfirmationText2'); ?>';
					
					$('#selectRTconfirmationModalBody').html(selectRTConfData)
					$('#selectRTconfirmationModal').modal('show');

					$('#RTCancelConf').on('click', (e) => {
						$('#selectRTconfirmationModal').modal('hide');
						$('#popUp').modal('toggle');

						// unbind the listener, to define only one listener
						$('#RTCancelConf').off('click');
						$('#RTProceedConf').off('click');
					})

					$('#RTProceedConf').on('click', (e) => {
						// e.preventDefault();
						var rj = jsonF('questionnaires/targets/json/json.php',{datos:datCopy,acc:6,targetId:targetId});
						// console.log(rj);
						var r = $.parseJSON(rj);
						// console.log(r);
						if(r.ok == 1){
							$('#divTrgt_'+targetId+'_'+usersTargetsId+' #dimSel_1').val('').trigger('change');
							console.log(datCopy);
							if(sels.length == 0){
								var o = new Option(datCopy.nombre,r.nId);
								$('#divTrgt_'+targetId+'_'+usersTargetsId+' #dimSel_1').append(o);							
							}
	
							var dat = {targetsId:targetId,usersTargetsId:usersTargetsId,dimensionesElemId:r.nId};
							var rrj = jsonF('questionnaires/targets/json/json.php',{datos:dat,acc:5});
							var rr = $.parseJSON(rrj);
							// console.log(r);
							if(rr.ok == 1){
								$('#divTrgt_'+targetId+'_'+usersTargetsId)
								.find('.targetTable')
								.load(rz+'questionnaires/targets/targetTable.php',{targetId:targetId});
							}
							location.reload();
						}
					})
				}
			});
		<?php } ?>


	});
</script>


<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('target'); ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>

<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nTrgt">
		<table class="table" border="0">
			<?php
			$i = 1;
			foreach ($dims as $k => $d){ 
				if($k == 0){
					$dimsElems = $db->query("SELECT * FROM DimensionesElem 
						WHERE dimensionesId = $d[id] ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
				}else{
					$dimsElems = array();
				}
			?>
				<tr>
					<td><?php echo $d['nombre']; ?></td>
					<td>
						<?php if ($k < count($dims) -1){ ?>						
							<select class="form-control dimSelAdd oblig" id="dimSelAdd_<?php echo "$i"; ?>">
								<option value="">- - - <?php echo $d['nombre']; ?> - - -</option>
								<?php foreach ($dimsElems as $de){ ?>
									<option value="<?php echo $de['id']; ?>"><?php echo $de['nombre']; ?></option>
								<?php } ?>
							</select>
						<?php }else{ ?>
							<input type="text" value="<?php echo $datC['name']; ?>" name="name" id="name" 
								class="form-control oblig" placeholder="<?php echo TR('RTPlaceholder') ?>">
						<?php } ?>
					</td>
				</tr>

			<?php $i++;} ?>
				
		</table>
	</form>
		<div style="color:#FF0000;"	>
					<?php echo TR('mnjTargetAdd') ?>
		</div>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
		<?php if ($addStructure){ ?>
			<span id="env" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
		<?php } ?>
	</div>
</div>
