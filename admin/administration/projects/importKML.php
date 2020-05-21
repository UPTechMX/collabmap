<?php

	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso Projects

	$project = $db -> query("SELECT *
		FROM Projects p 
		WHERE p.id = $_POST[prjId]")->fetchAll(PDO::FETCH_ASSOC)[0];

	$now = date('Y-m-d H:i:s');
	$hash = md5($now);
	// echo $hash;
	$prefijo = "KML_$_POST[prjId]_$hash"."_";

?>

<script type="text/javascript">
	woAttr = false;
	$(document).ready(function() {
		subArch(
			$('#selFile'),
			3,
			'<?php echo $prefijo; ?>',
			'kml',
			false,
			function(e){
				// console.log(e);
				var file = e.prefijo+e.nombreArchivo;
				var rj = jsonF('admin/administration/projects/json/getAttributes.php',{file:file});
				console.log(rj);
				var r = $.parseJSON(rj);

				$('#attrs').show();
				optsSel(r,$('#selAttr'),false,'<?php echo TR('selectIdAttr'); ?>',false);
				$('#selAttr').val('').trigger('change');
				if(r.length != 0){
					$('#selAttr').closest('tr').show();
				}else{
					$('#selAttr').closest('tr').hide();
					woAttr = true;
					$('#env').show();
				}
				$('#KMLfile').val(file);

			},
			false,
			uploadStr = "<?php echo TR('file'); ?>",
			extErrorStr = "<?php echo TR('extErrorStr'); ?>"
		);
		$('#selAttr').change(function(event) {
			var attr = $(this).val();
			if(attr != ''){
				var type = $('#selAttr option:selected').attr('class');
				$('#env').show();
				$('#KMLattr').val(attr);
			}else{
				$('#KMLattr').val('');
				$('#env').hide();
			}
		});

		$('#env').click(function(event) {
			var allOk = camposObligatorios('#formKML');

			var file = $('#KMLfile').val();
			var idAttr = !woAttr?$('#KMLattr').val():-1;
			var prjId = '<?php echo $_POST["prjId"]; ?>';
			var KMLName = $('#KMLName').val();

			if(file != '' && idAttr != '' && KMLName != ''){
				var rj = jsonF('admin/administration/projects/json/uplKML.php',{file:file,idAttr:idAttr,prjId:prjId,KMLName:KMLName});
				console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#KMLlist').load(rz+'admin/administration/projects/KMLlist.php',{prjId:prjId});
				}

			}


		});
	});
</script>
<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('importKML'); ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<div>
		<table class="table">
			<tr>
				<td><?php echo TR('selFile'); ?></td>
				<td>
					<div id="selFile"></div>
				</td>
			</tr>
		</table>
	</div>
	<form id="formKML">
		<div id="attrs" style="display: none;">
			<table class="table">
				<tr>
					<td width="30%"><?php echo TR('name') ?></td>
					<td><input type="text" class="form-control oblig" id="KMLName" /></td>
				</tr>
				<tr>
					<td width="30%"><?php echo TR('selectIdAttr') ?></td>
					<td><select class="form-control" id="selAttr"></select></td>
				</tr>
			</table>
		</div>
		<input type="hidden" name="KMLfile" id="KMLfile" />
		<input type="hidden" name="KMLattr" id="KMLattr" />
	</form>

</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
		<span id="env" class="btn btn-sm btn-shop" style="display: none;"><?php echo TR('send'); ?></span>
	</div>
</div>