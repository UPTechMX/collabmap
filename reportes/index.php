<?php

include_once '../lib/j/j.func.php';
include_once raiz().'reportes/seguridad/seguridad.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="initial-scale = 1.0,maximum-scale = 1.0" />

	<title>Hábitat IU</title>
	
	<!-- LIBRERIAS CSS -->
	<link href="<?php echo aRaizHtml();?>lib/js/bootstrap4/css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo aRaizHtml();?>lib/j/j.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo aRaizHtml();?>lib/js/jQuery-TE/jquery-te-1.4.0.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo aRaizHtml();?>lib/js/jquery-upload-file/css/uploadfile.css " rel="stylesheet" type="text/css" />
	<link href="<?php echo aRaizHtml();?>lib/js/sumoselect/sumoselect.css " rel="stylesheet" type="text/css" />
	<link href="<?php echo aRaizHtml();?>lib/js/gridstack/dist/gridstack.css " rel="stylesheet" type="text/css" />
	<link href="<?php echo aRaizHtml();?>lib/css/general.css" rel="stylesheet" type="text/css" />

	<!-- <link href="<?php echo aRaizHtml();?>lib/D/D.css" rel="stylesheet" type="text/css" /> -->
	<!-- <link href="<?php echo aRaizHtml();?>lib/js/jstree/themes/default/style.css" rel="stylesheet" type="text/css" /> -->
	<!-- <link href="<?php echo aRaizHtml();?>lib/js/jqueryUI/jquery-ui.css" rel="stylesheet" type="text/css" /> -->
	<link href="<?php echo aRaizHtml();?>lib/js/jqueryUI/jquery-ui.css" rel="stylesheet" type="text/css" />
	
	<!-- LIBRERIAS JAVASCRIPT -->
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/0.10.0/lodash.min.js"></script>
	<script src="<?php echo aRaizHtml();?>lib/js/jquery-3.1.1.min.js"></script>
	<script src="<?php echo aRaizHtml();?>lib/js/jqueryUI/jquery-ui.js"></script>
	<script src="<?php echo aRaizHtml();?>lib/js/bootstrap4/js/bootstrap.min.js"></script>
	<script src="<?php echo aRaizHtml();?>lib/js/jquery-upload-file/js/jquery.uploadfile.js"></script>
	<script src="<?php echo aRaizHtml();?>lib/js/jQuery-TE/jquery-te-1.4.0.min.js"></script>
	<script src="<?php echo aRaizHtml();?>lib/js/sumoselect/jquery.sumoselect.js"></script>
	<script src="<?php echo aRaizHtml();?>lib/js/gridstack/dist/gridstack.js"></script>
	<script src="<?php echo aRaizHtml();?>lib/js/gridstack/dist/gridstack.jQueryUI.js"></script>
	<script src="<?php echo aRaizHtml();?>lib/js/audiojs/audiojs/audio.min.js"></script>
	<script src="<?php echo aRaizHtml();?>lib/js/html2canvas/dist/html2canvas.js"></script>
	<script src="<?php echo aRaizHtml();?>lib/js/jsPDF/dist/jspdf.min.js"></script>
	<script src="<?php echo aRaizHtml();?>lib/js/selpicker/js/bootstrap-select.js"></script>
	<link rel="stylesheet" href="<?php echo aRaizHtml();?>lib/js/selpicker/css/bootstrap-select.css">


	<script src="https://docraptor.com/docraptor-1.0.0.js"></script>
	
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/highcharts-more.js"></script>
	<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/data.js"></script>
	<script src="https://code.highcharts.com/modules/drilldown.js"></script>


	<script src="<?php echo aRaizHtml();?>lib/js/graficas.js"></script>

	<script src="<?php echo aRaizHtml();?>lib/j/j.js"></script>
	

	<script>
		$(document).ready(function() {
			// console.log('aaa');
		});
	</script>

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129363582-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-129363582-1');
	</script>


</head>

<body style="background-color: #FFF;">
	<div class="container" >
		<?php if (!isset($_GET['mId'])){ ?>
			<div class="header" id="header"><?php include 'layout/header.php'; ?></div>
		<?php } ?>
		<div>
			<div class="content" style="min-height:30px;" id="content">
				<?php include raiz().'reportes/layout/content.php'; ?>
			</div>
			<br/>
			<?php if (!isset($_GET['mId'])){ ?>
				<div class="footerL"><?php include raiz().'admin/layout/footer.php'; ?></div>
			<?php } ?>

		</div>
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


