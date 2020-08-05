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

include_once '../../lib/j/j.func.php';

// print2(datCodigoPostal('85203'));;
// print2($_SESSION);

// echo $_TRANSLATE['pollHealth'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="initial-scale = 1.0,maximum-scale = 1.0" />

	<title><?php echo TR('systemName'); ?></title>
	
	<!-- LIBRERIAS CSS -->
	<link href="../../lib/js/bootstrap4/css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="../../lib/j/j.css" rel="stylesheet" type="text/css" />
	<link href="../../lib/css/general.css" rel="stylesheet" type="text/css" />

	<link href="../../lib/js/jqueryUI/jquery-ui.css" rel="stylesheet" type="text/css" />
	
	<!-- LIBRERIAS JAVASCRIPT -->
	<script src="../../lib/js/jquery-3.1.1.min.js"></script>
	<script src="../../lib/js/popper.min.js"></script>
	<script src="../../lib/js/jqueryUI/jquery-ui.js"></script>
	<script src="../../lib/js/bootstrap4/js/bootstrap.js"></script>
	<script src="../../lib/j/j.js" charset="utf-8"></script>

	<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
	<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">


	<script type="text/javascript">
	</script>

	

</head>
<body style="background-color: #fff;">
	<div class="container" >
		<div class="header" id="header">
			<div style="text-align:center;margin-top: 20px;" class="hidden-xs">
				<img src="../../img/marquesina.png" width="100%" id="Insert_logo" style=" margin-left:auto;margin-right:auto;" usemap="#logosMap" />
			</div>

		</div>
		<div>
			<div class="content" style="min-height:30px;" id="content">
				<div class="" id='pano' 
					style='width:80%;border:solid #00aeef; grey;border-radius: 10px;margin-left: auto;margin-right: auto; padding: 10px'>


				<?php

					$hash = $_GET['confId'];
					$getUser = $db->prepare("SELECT id,confirmed FROM Users WHERE hashConf = ?");

					$getUser -> execute(array($hash));

					$user = $getUser -> fetchAll(PDO::FETCH_ASSOC)[0];

					if(empty($user)){
						echo TR("confHashNotFound");
					}elseif($user['confirmed'] == ''){
						echo TR("userConf");
					}else{
						echo TR("userAlreadyConf");
					}

				?>
				</div>
			</div>
			<br/>
			<div class="footerL"><?php include '../layout/footer.php'; ?></div>
		</div>
	</div>
</body>
</html>


