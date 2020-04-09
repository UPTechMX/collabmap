<?php 

	if ($nivel != 46 && $nivel < 50) {
		echo "err: 46654";
		exit('NO TIENES ACCESO');
	}

?>
<script type="text/javascript">
	$(function() {
		$('#instalacionesA-tab').click(function(event) {
			// console.log('aa')
			$('#dAsignadas').load(rz+'campo/instalaciones/asignadas.php');
		});
	});
</script>

<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item">
		<a class="nav-link active" id="instalacionesA-tab" data-toggle="tab" href="#dAsignadas" role="tab" aria-controls="asignadas" aria-selected="true">
			Instalaciones programadas
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="cercanas-tab" data-toggle="tab" href="#cercanas" role="tab" aria-controls="cercanas" aria-selected="false">
			Instalaciones cercanas
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="clientes-tab" data-toggle="tab" href="#clientes" role="tab" aria-controls="clientes" aria-selected="false">
			Clientes
		</a>
	</li>
</ul>
<div class="tab-content" id="myTabContent">
	<div class="tab-pane fade show active" id="dAsignadas" role="tabpanel" aria-labelledby="instalacionesA-tab">
		<?php include_once 'asignadas.php'; ?>
	</div>
	<div class="tab-pane fade" id="cercanas" role="tabpanel" aria-labelledby="cercanas-tab"><?php $etapaCampo = "instalaciones"; include $aRaiz.'visitas/mapaCercanas.php'; ?></div>
	<div class="tab-pane fade" id="clientes" role="tabpanel" aria-labelledby="clientes-tab"><?php include 'clientes.php'; ?></div>
</div>
