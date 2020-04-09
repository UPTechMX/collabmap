
<?php

$Act = $_REQUEST['Act'];
// include_once 'buscaDatos.php';
// print2($_SESSION);
// print2($_REQUEST);
$usrId = $_SESSION['pub']['usrId'];
$pryId = isset($_GET['pryId'])?$_GET['pryId']:0;
$mId = isset($_GET['mId'])?$_GET['mId']:0;

?>

<script type="text/javascript">
	$(document).ready(function() {
		// console.log('content');
		$('#prySel').change(function(event) {
			var pryId = $(this).val();
			// $('#tabGral').trigger('click');
			if(pryId != ''){
				$('#general').load(rz+'reportes/general/index.php',{proyectoId: pryId,ajax:1});
				$('#comparativo').load(rz+'reportes/comparativo/index.php',{proyectoId: pryId,mId:mId,ajax:1});
				setTimeout(function () {
					grid = genGrid();
					ajustaWidget(0);
					$('#calcComp').trigger('click');

				}, 1000);

			}
		});
		pryId = <?php echo $pryId; ?>;
		mId = <?php echo $mId; ?>;
		if(pryId != 0){
			// console.log('cambió');
			$('#general').load(rz+'reportes/general/index.php',{proyectoId: pryId,mId:mId,ajax:1});
		}

	});
</script>
<?php include raiz().'reportes/comparativo/index.php'; ?>



