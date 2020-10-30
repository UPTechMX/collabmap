<?php

if (!function_exists('raiz')) {
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(60);// checaAcceso Consultations

// print2($_POST);
?>



<script type="text/javascript">
	$(document).ready(function() {
		subArch(
			$('#uplFiles'),
			4,
			'<?php echo "consDoc_$_POST[consultationsId]_"?>',
			'pdf',
			false,
			function(e){
				var dat = {};
				dat.consultationsId = <?php echo "$_POST[consultationsId]"?>;
				dat.file = e.prefijo+e.nombreArchivo;
				dat.name = e.nombreArchivo;
				var rj = jsonF("admin/administration/consultations/json/json.php",{datos:dat, acc:1,opt:3});
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#documentsList').load(rz+'admin/administration/consultations/documentsList.php',{consultationsId:dat.consultationsId});
				}

			},
			false,
			'<?php echo TR("select"); ?>',
			extErrorStr = "<?php echo TR('extErrorStr'); ?>"
		)
	});
</script>

<div class="nuevo grisBkg"><?php echo TR('documents'); ?></div>

<div id="uplDocs" style="margin-top: 10px;">
	<div>
		<table class="table">
			<tr>
				<td><?php echo TR('uploadFiles'); ?></td>
				<td>
					<div id="uplFiles"></div>
				</td>
			</tr>
		</table>
	</div>
</div>

<div id="documentsList"><?php include 'documentsList.php'; ?></div>