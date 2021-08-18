<?php  

	include_once '../../../lib/j/j.func.php';

	checaAcceso(50);// checaAcceso Targets

	// print2($_POST);
?>

<script type="text/javascript">
	$(document).ready(function() {

		var archChk = '';
		$('#env').click(function(event) {
			$('#popUp').modal('toggle');

			if(archChk != ''){
				loading();
			}

			var ok = true;
			setTimeout(function () {
				if(archChk != ''){
					var rj = jsonF('lib/php/subeDatos.php',{
						chkId:<?php echo $_POST['checklistId']; ?>,
						archChk:archChk,
						targetsId:<?php echo $_POST['targetsId']; ?>,
					});
					console.log(rj);
					var r = $.parseJSON(rj);
					if(r.ok == 1){						
					}else{
						ok = false
					}
				}

				if(ok){
					removeLoading();
					alerta('success','<?php echo TR('successfulImport'); ?>')
				}else{
					removeLoading();
					alerta('danger','<?php echo TR('importError'); ?> : Err:'+r.err)
				}

			}, 100);

			// removeLoading();
		});

		subArch($('#archivoChk'),3,'<?php echo $_POST['checklistId'];?>_CHK_','csv',false,function(a){
			archChk = a.prefijo+a.nombreArchivo;
			$('#nomArch').text(a.nombreArchivo);
		})


	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('uploadFiles'); ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nEmp">
		<table class="table" border="0">
			<tr>
				<td><?php echo TR('file'); ?></td>
				<td>
					<div id="archivoChk">aaa</div>
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="3"  id="nomArch"></td>
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
