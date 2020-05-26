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
			// console.log(dimElemId,dimNivel,niveles);
			if(dimNivel < niveles ){
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

				// console.log(dat);

				if(allOk){
					var rj = jsonF('questionnaires/targets/json/json.php',{datos:dat,acc:6,targetId:targetId});
					console.log(rj);
					var r = $.parseJSON(rj);
					// console.log(r);
					if(r.ok == 1){
						$('#popUp').modal('toggle');
						$('#divTrgt_'+targetId+'_'+usersTargetsId+' #dimSel_1').val('').trigger('change');

						if(sels.length == 0){
							var o = new Option(dat.nombre,r.nId);
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
						
					}else if(r.ok == 2){

						$('#popUp').modal('toggle');

					}else if(r.ok == 3){
						
						$('#nTrgt #name').css({backgroundColor:'rgba(255,0,0,.5)'});
						$('#nTrgt #name').after(() => {
							return '<span id="duplicateWarning">Duplicate entry detected.</span>'
						});
						$('#nTrgt #name').on('input', () => {
							if($('#nTrgt #name').length){
								$('#duplicateWarning').remove()
							}
						});
						
					}
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
							<select class="form-control dimSelAdd oblig" id="dimSelAdd_<?php echo "$d[nivel]"; ?>">
								<option value="">- - - <?php echo $d['nombre']; ?> - - -</option>
								<?php foreach ($dimsElems as $de){ ?>
									<option value="<?php echo $de['id']; ?>"><?php echo $de['nombre']; ?></option>
								<?php } ?>
							</select>
						<?php }else{ ?>
							<input type="text" value="<?php echo $datC['name']; ?>" name="name" id="name" class="form-control oblig" >
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
		<?php if ($addStructure){ ?>
			<span id="env" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
		<?php } ?>
	</div>
</div>
