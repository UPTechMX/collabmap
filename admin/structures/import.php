<?php  

	include_once '../../lib/j/j.func.php';
	// print2($_POST);

?>

<script type="text/javascript">
	$(document).ready(function() {

		$file = null;

		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:''});
			// event.preventDefault();
			if(event.keyCode == 13){
				event.preventDefault();
				return false;
			}
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:'white'});
		});


		$('#nombre').keypress(function(event){
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if(keycode == '13'){
				event.preventDefault();
			}
		});

		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			// console.log(dat);
			var elemId = <?php echo $_POST['elemId']; ?>;
			
			
			if(file != null && dat.file != '' && dat.file == file){
				$('#popUp').modal('toggle');
				setTimeout(function(){
					var type = "<?php echo $_POST['type']; ?>";
					var rj = jsonF('admin/structures/json/importStructure.php',{file:file,elemId:elemId,type:type});
					// console.log(rj);
					var r = $.parseJSON(rj);
					// console.log(r);
					if(r.ok == 1){
						removeLoading();
						alerta('success','<?php echo TR('successfulImport'); ?>');
						var type = "<?php echo $_POST['type']; ?>";
						var elemName = "<?php echo $_POST['elemName']; ?>";
						$('#structures').load(rz+'admin/structures/index.php',{elemId:elemId,type:type,elemName:elemName});

					}else{
						removeLoading();
						alerta('danger','<?php echo TR('importError'); ?> : Err:'+r.err)
					}
				},200);
			}

		});

		subArch($('#uplFile'),3,'structure_<?php echo $_POST['elemId']; ?>_','csv',false,function(e){
			// console.log(e);
			file = e.prefijo+e.nombreArchivo;
			$('#fileName').text(e.nombreArchivo);
			$('#file').val(e.prefijo+e.nombreArchivo);

		},false,'<?php echo TR('selFile'); ?>','<?php echo TR('extErrorStr'); ?>');


	});
</script>


<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('importStr'); ?>
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
				<td><?php echo TR('file'); ?></td>
				<td>
					<div id="uplFile"></div>
				</td>
				<td>
					<input type="hidden" name="file" id="file" />
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<div id="fileName"></div>
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
