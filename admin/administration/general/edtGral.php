<?php  
	include_once '../../../lib/j/j.func.php';
	checaAcceso(50);// checaAcceso Projects
	$p = $_POST;
	// print2($_POST);

	$stmt = $db->prepare("SELECT * FROM General WHERE name = ?");
	$stmt -> execute(array($_POST['elem']));
	$datC = $stmt -> fetchAll(PDO::FETCH_ASSOC)[0];


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
			allOk = true;			
			var elem = '<?php echo $_POST["elem"]; ?>';
			var texto = $('#texto').val();

			if(texto == ''){
				allOk = false;
			}

			if(allOk){
				console.log(elem,texto);
				var rj = jsonF('admin/administration/general/json/json.php',{acc:1,texto:texto,elem:elem});
				console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					// $('#projectsList').load(rz+'admin/administration/projects/projectsList.php',{ajax:1});
				}
			}

		});
		$(".txArea").jqte({
			source:true,
			rule: false,
			link:false,
			unlink: false,
			format:false
		});


	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR($_POST['elem']); ?>
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
				<td><?php echo TR($_POST['elem']); ?></td>
				<td>
					<textarea name="texto" id="texto" class="form-control oblig txArea"><?php echo $datC['texto']; ?></textarea>
				</td>
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
