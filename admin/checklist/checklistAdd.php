<?php  

	include_once '../../lib/j/j.func.php';
	checaAcceso(50); // checaAcceso Checklist

	$p = $_POST;
	// print2($_POST);

	if($_POST['checklistId'] != ''){
		$datM = $db-> query("SELECT * FROM Checklist WHERE id = $_POST[checklistId]")->fetch(PDO::FETCH_ASSOC);
	}

	// $etapas = $db->query("SELECT * FROM Etapas ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {

		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:'white'});
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:'white'});
		});

		// $('#tipoReembolso').val('<?php echo $datM['tipoReembolso']; ?>');
		$('#tipoReembolso').change(function(event) {
			var tipoReembolso = $(this).val();
			switch(tipoReembolso){
				case "0":
					$('#TRreembolso').hide();
					$('#TRmaxReembolso').hide();
					$('#reembolso').val('');
					$('#maxReembolso').val('');
					break;
				case "1":
					$('#TRreembolso').show();
					$('#TRmaxReembolso').show();
					// $('#reembolso').val('');
					// $('#maxReembolso').val('');
					break;
				case "2":
					$('#TRreembolso').hide();
					$('#TRmaxReembolso').show();
					// $('#reembolso').val('');
					// $('#maxReembolso').val('');
					break;

			}
		});
		$('#tipoReembolso').trigger('change');

		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			var allOk = camposObligatorios('#nEmp');
			<?php 
				if(isset($_POST['checklistId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[checklistId];";
				}else{
					echo 'var acc = 1;';
				}
			?>

			if(allOk){

				var rj = jsonF('admin/checklist/json/json.php',{datos:dat,acc:acc,opt:11});
				console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					<?php if(isset($_POST['checklistId'])){ ?>
						var chkId = <?php echo $_POST['checklistId']; ?>;
						$('#chkSel').children('option[value="'+chkId+'"]').text(dat.nombre);
					<?php }else{ ?>					
						var o = new Option(dat.nombre,r.nId);
						$('#chkSel').append(o);
						$('#chkSel').val(r.nId).trigger('change');
					<?php } ?>


					$('#popUp').modal('toggle');
					// $('#checklistList').load(rz+'admin/proyectos/checklist/checklistList.php',{ajax:1});
				}
			}

		});

	});
</script>


<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR("survey"); ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>

<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nEmp">
		<table class="table" border="0">
			<tr>
				<td><?php echo TR('name'); ?></td>
				<td>
					<input type="text" value="<?php echo $datM['nombre']; ?>" name="nombre" id="nombre" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('code'); ?></td>
				<td><input type="text" value="<?php echo $datM['siglas']; ?>" name="siglas" id="siglas" class="form-control oblig"></td>
				<td></td>
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
