<?php

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(50); // checaAcceso Tracking

	$targets = $db->query("SELECT * FROM Targets ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);


?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#trgtSel').change(function(event) {
			var trgtId = $(this).val();
			if(trgtId != ''){
				$('#trgtDims').load(rz+'admin/tracking/trgtDims.php',{trgtId:trgtId});
			}
		});
	});
</script>


<div style="margin-top: 10px;">
	<div class="row">
		<div class="col-4">
			<select class="form-control" id="trgtSel">
				<option value="">- - - Targets - - -</option>
				<?php foreach ($targets as $t){ ?>
					<option value="<?php echo $t['id'] ?>"><?php echo $t['name']; ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div id="trgtDims" style="margin-top: 10px;"></div>
</div>
<div style="margin-top: 10px;" id="trackingList"></div>
