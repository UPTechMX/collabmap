<?php

function root(){
	if (session_status() == PHP_SESSION_NONE) {
	    @session_start();
	}

	if(empty($_SESSION['CM']['raiz'])){
		$dir = getcwd();
		$dirE = explode('/',$dir);
		$ciclos = count($dirE);
		for($i = $ciclos; $i > 0; $i--){
			$dN = '';
			for($j = 0; $j<$i;$j++){
				$dN .= $dirE[$j].'/';
			}
			if(file_exists($dN.'/raiz')){
				$_SESSION['CM']['raiz'] = $dN;
				return $dN;
			}else{
				if($i == 1){
					exit("Archivo 'raiz' no encontrado, inserta un archivo con nombre 'raiz' (sin extensiones) en el direcorio raiz.");
				}
			}
		}
	}else{
		return $_SESSION['CM']['raiz'];
	}
}

include_once root().'lib/j/j.func.php';


?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#conf').click(function(event) {
			location.reload();
		});

	});
</script>

<div class="" id='pano' style='width:80%;border:solid #00aeef; grey;border-radius: 10px;margin-left: auto;margin-right: auto; padding: 10px'>
	<?php echo TR('regConf'); ?>
	<div style="text-align: right;">
		<span id="conf" class="btn btn-sm btn-shop"><?php echo TR('ok'); ?></span>
	</div>
</div>