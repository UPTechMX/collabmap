<?php  
	include_once '../lib/j/j.func.php';
	// print2($_POST);
	$preguntasId = $_POST['datos']['preguntasId'];
	$categories = $db->query("SELECT * 
		FROM Categories 
		WHERE preguntasId = $preguntasId
	") -> fetchAll(PDO::FETCH_ASSOC);

	$countP = $db->query("SELECT COUNT(*) as cuenta 
		FROM Problems p
		LEFT JOIN RespuestasVisita rv ON rv.id = p.respuestasVisitaId
		WHERE rv.visitasId = $_POST[vId] AND rv.preguntasId = $preguntasId")->fetchAll(PDO::FETCH_NUM)[0][0];

	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT * FROM Problems WHERE id = $_POST[eleId]")->fetch(PDO::FETCH_ASSOC);
	}
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:''});
			// event.preventDefault();
			if(event.keyCode == 13){
				event.preventDefault();
				return false;
			}
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:''});
		});

		$('#env').click(function(event) {
			var dat = $('#nPrb').serializeObject();
			dat.type = "<?php echo $_POST['problem']['type']; ?>";
			var allOk = camposObligatorios('#nEmp');

			<?php 
				if(isset($_POST['eleId'])){
					echo 'var acc = 13;';
					echo "dat.id = $_POST[eleId];";
				}else{
					echo 'var acc = 8;';
				}
			?>


			if(allOk){
				// console.log(dat);
				<?php if (isset($_POST['eleId'])){ ?>
					delete dat.type;
					// console.log(dat);
					var rj = jsonF('checklist/json/json.php',{
						datos:dat,
						hash:"<?php echo $_POST['hash']; ?>",
						pId:"<?php echo $_POST['pIdAct']; ?>",
						acc:acc,
						vId:<?php echo $_POST['vId']; ?>,
					});
					console.log(rj);
					var r = $.parseJSON(rj);
				<?php }else{ ?>
					var rj = jsonF('checklist/json/json.php',{
						datos:<?php echo atj($_POST['datos']); ?>,
						hash:"<?php echo $_POST['hash']; ?>",
						pId:"<?php echo $_POST['pIdAct']; ?>",
						acc:acc,
						vId:<?php echo $_POST['vId']; ?>,
						problem:dat,
						latlngs: <?php echo atj($_POST['latlngs']); ?>
					});
					// console.log(rj);
					var r = $.parseJSON(rj);
					// console.log(r);
					if(r.ok == 1){
						layer.dbId = r.prId;
						drawnItems.addLayer(layer);
						spatialYa = true;
					}
				<?php } ?>
				if(r.ok == 1){
					$('#popUpMapa').modal('toggle');

					var vId = <?php echo $_POST['vId']; ?>;
					var spatial = {};
					spatial['id'] = "<?php echo $_POST['datos']['preguntasId']; ?>";
					spatial['identificador'] = "<?php echo $_POST['datos']['identificador']; ?>";
					var hash = "<?php echo $_POST['hash']; ?>";
					$('#problemList').load(rz+'checklist/problemList.php',{
						vId:vId,
						spatial:spatial,
						hash:hash,
					});
				}
			}

		});
		// console.log('layerPop:',layer);

		$('#nPrb').on('click', '.multDel', function(event) {
			event.preventDefault();
			$('#photo').empty().hide();
			$('#photoUpload').show();
			$('#photoInput').val('');
		});

		subArch($('#photoUpload'),2,
				'problem_<?php echo $_POST['vId']; ?>_<?php echo $_POST['datos']['preguntasId']; ?>_','jpg,png,gif,jpeg',false,function(e){
			// console.log(e);
			$('#photoInput').val(e.prefijo+e.nombreArchivo);
			$('#photoUpload').hide();
			$('#photo').show().append(
			'<div class="row">'+
				'<div class="col-md-10" id="imgNom_">'+
					'<img  class="verImg manita" src="'+rz+'problemsPhotos/'+e.prefijo+e.nombreArchivo+'" height="100px"/>'+
				'</div>'+
				'<div class="col-md-2" style="text-align: right;">'+
					'<i class="glyphicon glyphicon-trash manita multDel rojo" '+
						'id="imgDel_"></i>&nbsp;&nbsp;'+
				'</div>'+
			'</div>'
			);

		});


		var photo = $('#photo').attr('file');
		if(photo!=''){
			$('#photo').append(
				'<div class="row">'+
					'<div class="col-md-10" id="imgNom_">'+
						'<img  class="verImg manita" src="'+rz+'problemsPhotos/'+photo+'" height="100px"/>'+
					'</div>'+
					'<div class="col-md-2" style="text-align: right;">'+
						'<i class="glyphicon glyphicon-trash manita multDel rojo" '+
							'id="imgDel_"></i>&nbsp;&nbsp;'+
					'</div>'+
				'</div>'
			);
		}

	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('problem'); ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>

<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nPrb">
		<table class="table" border="0">
			<tr>
				<td><?php echo TR('name'); ?></td>
				<td>
					<input type="text" 
						value="<?php echo empty($datC['name'])?TR('problem')."_".($countP+1):$datC['name']; ?>" 
						name="name" id="name" class="form-control oblig" />
				</td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('description'); ?></td>
				<td>
					<input type="text" value="<?php echo $datC['description']; ?>" name="description" id="description" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('category'); ?></td>
				<td>
					<select name="categoriesId" class="form-control oblig">
						<option value="">- - - - <?php echo TR('category'); ?> - - - -</option>
						<?php foreach ($categories as $c){ ?>
							<option value="<?php echo $c['id'] ?>" <?php echo $c['id'] == $datC['categoriesId']?'selected':''; ?>>
								<?php echo $c['name']; ?>
							</option>
						<?php } ?>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('photo'); ?></td>
				<td>
					<div id="photoUpload" style="display:<?php echo $datC['photo'] ==''? 'block':'none'; ?>"></div>
					<div id="photo" class="img" file="<?php echo $datC['photo']; ?>" 
						style="display:<?php echo $datC['photo'] !=''? 'block':'none'; ?>">
					</div>
				</td>
				<td></td>
				<input type="hidden" name="photo" value="<?php echo $datC['photo']; ?>" id="photoInput">
			</tr>
		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
		<span id="env" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
	</div>
</div>
