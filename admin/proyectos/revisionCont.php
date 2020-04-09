<?php  
session_start();
if($_SESSION['CM']['admin']['nivel'] <10){
	exit('No tienes acceso');
}

include_once '../../lib/j/j.func.php';

// print2($_POST);

$chk = "$_POST[vId]_NLIU";
$v = password_verify($chk,$_POST['hash']);

if(!$v)
	exit('NO EXISTE LA VISITA SOLICITADA111');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="initial-scale = 1.0,maximum-scale = 1.0" />

	<title>Hábitat IU</title>

	<link href="../../lib/js/bootstrap4/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="../../lib/j/j.css" rel="stylesheet" type="text/css" />
	<link href="../../lib/js/jQuery-TE/jquery-te-1.4.0.css" rel="stylesheet" type="text/css" />
	<link href="../../lib/js/selectpicker/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css" />
	<link href="../../lib/js/jquery-upload-file/css/uploadfile.css " rel="stylesheet" type="text/css" />
	<link href="../../lib/css/general.css" rel="stylesheet" type="text/css" />
	<link href="../../lib/js/sumoselect/sumoselect.css " rel="stylesheet" type="text/css" />


<!-- 	<link href="http://vjs.zencdn.net/6.2.5/video-js.css" rel="stylesheet">
	<script src="http://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
	<script src="http://vjs.zencdn.net/6.2.5/video.js"></script>
 -->
	<!-- <link href="../../lib/D/D.css" rel="stylesheet" type="text/css" /> -->
	<!-- <link href="../../lib/js/jstree/themes/default/style.css" rel="stylesheet" type="text/css" /> -->
	<!-- <link href="../../lib/js/jqueryUI/jquery-ui.css" rel="stylesheet" type="text/css" /> -->
	<link href="../../lib/js/jqueryUI/jquery-ui.css" rel="stylesheet" type="text/css" />

	<!-- LIBRERIAS JAVASCRIPT -->
	<script src="../../lib/js/jquery-3.1.1.min.js"></script>
	<script src="../../lib/js/jqueryUI/jquery-ui.js"></script>
	<script src="../../lib/js/bootstrap4/js/bootstrap.min.js"></script>
	<script src="../../lib/js/jquery-upload-file/js/jquery.uploadfile.js"></script>
	<script src="../../lib/j/j.js"></script>
	<script src="../../lib/js/selectpicker/dist/js/bootstrap-select.js"></script>
	<script src="../../lib/js/jQuery-TE/jquery-te-1.4.0.min.js"></script>
	<script src="../../lib/js/sumoselect/jquery.sumoselect.js"></script>

	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	

</head>
<body style="background-color: #FFF;">
	<div class="container">
		<div class="header" id="header">
			<div style="text-align:center;margin-top: 20px;" class="hidden-xs">
				<img src="../../img/marquesina.png" width="100%" id="Insert_logo" 
					style=" margin-left:auto;margin-right:auto;" usemap="#logosMap" />
			</div>
			<div id="dAlerta"></div>
		</div>
		<div  id="visita"><?php include_once 'revision.php'; ?></div>
	</div>
	<div class="modal fade" id="popUp" role="dialog" style="">
		<div id="modal" class="modal-dialog">
			<div class="modal-content" style="border-radius: 0px;" id="popCont">
				Cargando...
			</div>
		</div>
	</div>
	<div class="modal fade" id="alertas" role="dialog" style="">
		<div id="modal" class="modal-dialog">
			<div class="modal-content" style="border-radius: 0px;" id="alertasCont">
				Cargando...
			</div>
		</div>
	</div>
</body>
</html>






