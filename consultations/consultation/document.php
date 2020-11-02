<?php
	session_start();
	if(!function_exists('raiz')){
		include '../../lib/j/j.func.php';
	}

	if(!is_numeric($_POST['documentId'])){
		exit();
	}
	$usrId = $_SESSION['CM']['consultations']['usrId'];

	$document = $db->query("SELECT * FROM Documents WHERE id = $_POST[documentId]")->fetchAll(PDO::FETCH_ASSOC)[0];


	$dims = $db->query("SELECT * FROM Dimensiones 
		WHERE elemId = $_POST[documentId] AND type = 'documents' ORDER BY nivel")->fetchAll(PDO::FETCH_ASSOC);


?>

<script type="text/javascript">
	$(document).ready(function() {
		
		$('.dimSelDocSt').change(function(event) {
			var dimElemId = $(this).val();
			var dimNivel = this.id.split('_')[1];
			var niveles = $(this).closest('.structCont').find('.dimSelDocSt').length;
			// console.log(dimElemId,dimNivel,niveles);
			if(dimNivel < niveles){
				// console.log('aa');
				var r = []
				if(dimElemId != ''){
					var rj = jsonF('consultations/consultation/json/getDims.php',{padre:dimElemId});
					// console.log(rj);
					r = $.parseJSON(rj);
					// console.log(r);
				}
				var nextNivel = parseInt(dimNivel)+1;
				var elemSel = $(this).closest('.structCont').find('#dimSelDocSt_'+nextNivel);
				
				var nomNext = $(elemSel.find('option')[0]).text();
				optsSel(r,elemSel,false,nomNext,false);
				elemSel.val('').trigger('change');

			}

		});

		$("#allComments").click(function(event) {
			<?php if (!empty($usrId)){ ?>
				var documentId = <?php echo $_POST['documentId']; ?>;
				popUp('consultations/consultation/comments.php',{documentId:documentId});
			<?php }else{ ?>
				alerta('success','<?php echo TR("needLogin"); ?>');
			<?php } ?>
		});


		$('#sendDocComment').click(function(event) {
			var numDim = <?php echo count($dims); ?>;
			var nivelMax = 0;
			var padre = 0;
			$.each($('.dimSelDocSt'), function(index, val) {
				var nivel = this.id.split('_')[1];
				if($(this).val() != '' && nivel > nivelMax){
					nivelMax = parseInt(nivel);
					padre = $(this).val();
				}
			});
			var docCommentText = $('#docCommentText').val();
			var valid = true;
			valid = docCommentText != '';
			if(padre == 0){
				valid = false;
			}
			if(nivelMax != numDim){
				valid = false;
			}

			// console.log(numDim,nivelMax,padre);
			<?php if (!empty($usrId)){ ?>
				if(valid){
					var documentsId = <?php echo $_POST['documentId']; ?>;
					var rj = jsonF('consultations/consultation/json/json.php',{
						nivelMax:nivelMax,
						padre:padre,
						documentsId:documentsId,
						comment:docCommentText,
						acc:8,
					});
					console.log(rj);
					var r = $.parseJSON(rj);
					if(r.ok == 1){
						$('#docCommentText').val('');
						$('#dimSelDocSt_1').val('').trigger('change');
						alertar('<?php echo TR("commentSent"); ?>');
					}
				}
			<?php }else{ ?>
				alerta('success','<?php echo TR("needLogin"); ?>');
			<?php } ?>


		});

		$.each($('.documentDiv'), function(index, val) {
			var file = $(this).attr('file');
			if(file != undefined){
				$(this).attr({src:rz+'consultationDocuments/'+file})
			}
		});

	});
</script>


<div class="modal-header nuevo titleL1Bkg" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('document')." ($document[name])"; ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
	  <span aria-hidden="true">&times;</span>
	</button>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<div class="row">
		<div class="col-md-8">
			<embed src="" file="<?php echo $document['file'];?>"
				width= "100%" style=" max-height:600px;min-height: 500px;" class="documentDiv">
		</div>
		<div class="col-md-4">
			<div style="text-align: center;">
				<h2><?php echo TR('comments'); ?></h2>
			</div>
			<?php if (count($dims) != 0){ ?>			
				<!-- <div style="text-align: right;padding-right: 10px;margin-top:10px;font-size: 1em;">
					<span class="manita" style="text-transform: uppercase;" id="allComments">
						
						<i class="glyphicon glyphicon-check"></i>	
					</span>
				</div> -->
				<div class="structCont" style="margin-top: 10px;">
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
							<select class="form-control dimSelDocSt" id="dimSelDocSt_<?php echo "$d[nivel]"; ?>">
								<option value=""><?php echo $d['nombre']; ?></option>
								<?php foreach ($dimsElems as $de){ ?>
									<option value="<?php echo $de['id']; ?>"><?php echo $de['nombre']; ?></option>
								<?php } ?>
							</select>
						</div>
					<?php } ?>
					<div class="" style="padding:5px 10px 0px 10px; margin-bottom: 10px;" >
						<div>
							<textarea class="form-control" id="docCommentText"></textarea>
						</div>
					</div>
					<div class="row justify-content-between" >
						<div class="col-4" style="text-align: center; ">
							<span id="sendDocComment" class="manita" style="border:solid 2px #004aad; padding: 5px 20px; border-radius: 3px;">
								<?php echo TR('send'); ?>
							</span>	
						</div>
						<div class="col-7" style="text-align: center; ">
							<span id="allComments" class="manita" style="background-color: #004aad;color: white; padding: 5px 20px; border-radius: 3px;">
								<?php echo TR('myComments'); ?>
							</span>	
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-shop"><?php echo TR('close'); ?></span>
	</div>
</div>
