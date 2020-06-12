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
			var texto = quill.root.innerHTML;

			if(texto == ''){
				allOk = false;
			}
			

			if(allOk){
				// console.log(elem,texto);
				var rj = jsonF('admin/administration/general/json/json.php',{acc:1,texto:texto,elem:elem});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					// $('#projectsList').load(rz+'admin/administration/projects/projectsList.php',{ajax:1});
				}
			}

		});
		var quill = new Quill('#editor-container', {
		  // modules: { toolbar: true },
		  modules: {
		  	toolbar: '#toolbar-container'
		  },
		  // placeholder: 'Compose an epic...',

		  theme: 'snow'
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

					<div>
						<div id="toolbar-container">
							<span class="ql-formats">
								<select class="ql-font"></select>
								<select class="ql-size"></select>
							</span>
							<span class="ql-formats">
								<button class="ql-bold"></button>
								<button class="ql-italic"></button>
								<button class="ql-underline"></button>
								<button class="ql-strike"></button>
							</span>
							<span class="ql-formats">
								<select class="ql-color"></select>
								<select class="ql-background"></select>
							</span>
							<span class="ql-formats">
								<button class="ql-script" value="sub"></button>
								<button class="ql-script" value="super"></button>
							</span>
							<span class="ql-formats">
								<button class="ql-header" value="1"></button>
								<button class="ql-header" value="2"></button>
								<button class="ql-blockquote"></button>
								<!-- <button class="ql-code-block"></button> -->
							</span>
							<span class="ql-formats">
								<button class="ql-list" value="ordered"></button>
								<button class="ql-list" value="bullet"></button>
								<button class="ql-indent" value="-1"></button>
								<button class="ql-indent" value="+1"></button>
							</span>
							<span class="ql-formats">
								<button class="ql-direction" value="rtl"></button>
								<select class="ql-align"></select>
							</span>
							<span class="ql-formats">
								<button class="ql-link"></button>
								<button class="ql-image"></button>
								<button class="ql-video"></button>
								<!-- <button class="ql-formula"></button> -->
							</span>
							<span class="ql-formats">
								<button class="ql-clean"></button>
							</span>
						</div>
						<div id="editor-container"><?php echo $datC['texto']; ?></div>
					</div>
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
