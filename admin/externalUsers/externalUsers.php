<?php

	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(60);// checaAcceso externalUsers

?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#addUsr').click(function(event) {
			popUp('admin/externalUsers/usuariosAdd.php',{},function(){},{});
		});
		$('#searchBtn').click(function(event) {
			var search = $.trim($('#search').val());
			if(search != ''){
				$('#usuariosList').load(rz+'admin/externalUsers/usuariosList.php',{search: search});
			}
			
		});
		$('#search').keyup(function(event) {
			if(event.keyCode == 13){
				event.preventDefault();
				$('#searchBtn').trigger('click');
			}

		});
		$('#search').focus();

	});
</script>

<div class="">
	<div class="nuevo"><?php echo TR('externalUsers') ?></div>
	<div class="row" style="margin-bottom: 10px;">
		<div class="col-8">
			<div class="input-group">
				<input id="search" class="form-control" placeholder="<?php echo TR('search'); ?>" type="text" /> 
				 <div class="input-group-append">
				 	<span class="input-group-text btn-shop" id="searchBtn">
				 		<div>
				 			<i class="glyphicon glyphicon-search"></i>
				 		</div> 
				 	</span>
				</div>
			</div><!-- /input-group -->
		</div>
		<div class="col-4">
			<div style="text-align: right;">
				<span class="btn btn-sm btn-shop" id="addUsr"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php echo TR('user') ?></span>
			</div>			
		</div>
	</div>
	<div id="usuariosList"></div>
</div>
