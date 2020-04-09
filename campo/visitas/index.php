<?php 

	if ($nivel != 30 && $nivel < 50) {
		echo "err: 46654";
		exit('NO TIENES ACCESO');
	}

?>
<script type="text/javascript">
	$(function() {
		$('#asignadas-tab').click(function(event) {
			$('#asignadas').load(rz+'campo/visitas/asignadas.php');
		});
	});
</script>

<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item">
		<a class="nav-link active" id="asignadas-tab" data-toggle="tab" href="#asignadas" role="tab" aria-controls="asignadas" aria-selected="true">
			Visitas programadas
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="cercanas-tab" data-toggle="tab" href="#cercanas" role="tab" aria-controls="cercanas" aria-selected="false">
			Visitas cercanas
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="clientes-tab" data-toggle="tab" href="#clientes" role="tab" aria-controls="clientes" aria-selected="false">
			Clientes
		</a>
	</li>
</ul>
<div class="tab-content" id="myTabContent">
	<div class="tab-pane fade show active" id="asignadas" role="tabpanel" aria-labelledby="asignadas-tab"><?php include_once 'asignadas.php'; ?></div>
	<div class="tab-pane fade" id="cercanas" role="tabpanel" aria-labelledby="cercanas-tab"><?php $etapaCampo = "visitas"; include 'mapaCercanas.php'; ?></div>
	<div class="tab-pane fade" id="clientes" role="tabpanel" aria-labelledby="clientes-tab"><?php include 'clientes.php'; ?></div>
</div>
