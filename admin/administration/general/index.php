<?php

	if(!function_exists('raiz')){
		include_once '../../../lib/j/j.func.php';
	}
	checaAcceso(50);// checaAcceso Consultations

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.edt').click(function(event) {

			var elem = this.id;
			popUp('admin/administration/general/edtGral.php',{elem:elem});
		});
	});
</script>

<div style="margin-top: 10px;margin-bottom: 10px;">
	<div class="row">
		<div class="col-12">
			<table class="table">
				<thead>
					<tr>
						<th><?php echo TR('element'); ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo TR('about'); ?></td>
						<td>
							<i class="glyphicon glyphicon-pencil manita edt" id="about"></i>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
