<?php

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso news

	// print2($_POST);
	if($_POST['eleId'] != ''){
		$datC = $db-> query("SELECT * FROM News WHERE id = $_POST[eleId]")->fetch(PDO::FETCH_ASSOC);
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
			var dat = $('#nEmp').serializeObject();
			var allOk = camposObligatorios('#nEmp');

			<?php 
				if(isset($_POST['eleId'])){
					echo 'var acc = 2;';
					echo "dat.id = $_POST[eleId];";
				}else{
					echo 'var acc = 1;';
				}
			?>

			dat.news = quill.root.innerHTML;
			// console.log(news);
			if(allOk){
				var rj = jsonF('admin/news/json/json.php',{datos:dat,acc:acc,opt:1});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#newsList').load(rz+'admin/news/newsList.php',{ajax:1});
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
			<?php echo TR('news'); ?>
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
				<td><?php echo TR('name'); ?></td>
				<td>
					<input type="text" value="<?php echo $datC['name']; ?>" name="name" id="name" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('header'); ?></td>
				<td>
					<input type="text" value="<?php echo $datC['header']; ?>" name="header" id="header" class="form-control oblig" >
				</td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('news'); ?></td>
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
						<div id="editor-container"><?php echo $datC['news']; ?></div>
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
