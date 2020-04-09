<?php
include_once '../lib/j/j.func.php';
// include_once raiz().'lib/php/shoppers.php';
include_once raiz().'campo/seguridad/seguridad.php';
checaAcceso(30);
$uId = $_SESSION['CM']['admin']['usrId'];
// $shopper = new Shopper($uId);
// print2($_SESSION['CM']);

// echo encriptaUsr(123);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="initial-scale = 1.0,maximum-scale = 1.0" />

	<title>Hábitat IU</title>
	
	<!-- LIBRERIAS CSS -->
	<link href="../lib/js/bootstrap4/css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="../lib/j/j.css" rel="stylesheet" type="text/css" />
	<link href="../lib/js/jQuery-TE/jquery-te-1.4.0.css" rel="stylesheet" type="text/css" />
	<link href="../lib/js/selectpicker/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css" />
	<link href="../lib/js/jquery-upload-file/css/uploadfile.css " rel="stylesheet" type="text/css" />
	<link href="../lib/css/general.css" rel="stylesheet" type="text/css" />
	<link href="../lib/js/jqueryUI/jquery-ui.css" rel="stylesheet" type="text/css" />

	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">


	
	<!-- LIBRERIAS JAVASCRIPT -->
	<script src="../lib/js/jquery-3.1.1.min.js"></script>
	<script src="../lib/js/jqueryUI/jquery-ui.js"></script>
	<script src="../lib/js/bootstrap4/js/bootstrap.min.js"></script>
	<script src="../lib/js/jquery-upload-file/js/jquery.uploadfile.js"></script>
	<script src="../lib/js/selectpicker/dist/js/bootstrap-select.js"></script>
	<script src="../lib/js/jQuery-TE/jquery-te-1.4.0.min.js"></script>
	<script src="../lib/j/j.js"></script>
	<script src="../lib/js/hereMaps/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
	<script src="../lib/js/hereMaps/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="../lib/js/hereMaps/mapsjs-ui.js"></script>
	<script type="text/javascript" src="../lib/js/hereMaps/mapsjs-mapevents.js"></script>
	<script type="text/javascript" src="../lib/js/jquery-confirm/dist/jquery-confirm.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../lib/js/jquery-confirm/dist/jquery-confirm.min.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="../lib/js/hereMaps/mapsjs-ui.css" media="screen" />

	<script type="text/javascript" src="../lib/js/mapa.js"></script>

	<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
	<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
	<script src="../lib/js/quaggaJS/dist/quagga.js"></script>


	<!-- If you'd like to support IE8 -->

	<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>


	<style>
		#interactive.viewport {position: relative; width: 100%; height: auto; overflow: hidden; text-align: center;}
		#interactive.viewport > canvas, #interactive.viewport > video {max-width: 100%;width: 100%;}
		canvas.drawing, canvas.drawingBuffer {position: absolute; left: 0; top: 0;}
	</style>


	<script>
		ini = 0;

		$(document).ready(function() {
				// Create the QuaggaJS config object for the live stream
				var liveStreamConfig = {
					inputStream: {
						type : "LiveStream",
						constraints: {
							width: {min: 640},
							height: {min: 480},
							aspectRatio: {min: 1, max: 100},
								facingMode: "environment" // or "user" for the front camera
						}
					},
					locator: {
						patchSize: "medium",
						halfSample: false
					},
					numOfWorkers: (navigator.hardwareConcurrency ? navigator.hardwareConcurrency : 4),
					decoder: {
						"readers":[
						{"format":"code_128_reader","config":{}}
						]
					},
					locate: true
				};
			// The fallback to the file API requires a different inputStream option. 
			// The rest is the same 
				var fileConfig = $.extend(
					{}, 
					liveStreamConfig,
					{
						inputStream: {
							size: 800
						}
					}
				);
			// Start the live stream scanner when the modal opens
			if(ini == 0){
				ini = 1;
				$('#livestream_scanner').on('shown.bs.modal', function (e) {
					Quagga.init(
						liveStreamConfig, 
						function(err) {
							if (err) {
								$('#livestream_scanner .modal-body .error').html('<div class="alert alert-danger"><strong><i class="fa fa-exclamation-triangle"></i> '+err.name+'</strong>: '+err.message+'</div>');
								Quagga.stop();
								return;
							}
							Quagga.start();
						}
					);
				});
				// console.log('AAAAA');
			}else{
				// console.log('BBBBB');
				// Quagga.start();
			}
			
			// Make sure, QuaggaJS draws frames an lines around possible 
			// barcodes on the live stream
			Quagga.onProcessed(function(result) {
				var drawingCtx = Quagga.canvas.ctx.overlay,
				drawingCanvas = Quagga.canvas.dom.overlay;

				if (result) {
					if (result.boxes) {
						drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
						result.boxes.filter(function (box) {
							return box !== result.box;
						}).forEach(function (box) {
							Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "green", lineWidth: 2});
						});
					}

					if (result.box) {
						Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "#00F", lineWidth: 2});
					}

					if (result.codeResult && result.codeResult.code) {
						Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 3});
					}
				}
			});
			

			// Stop quagga in any case, when the modal is closed
			$('#livestream_scanner').on('hide.bs.modal', function(){
				if (Quagga){
					console.log('encendido')
					Quagga.stop();	
				}
			});
			
			// Call Quagga.decodeSingle() for every file selected in the 
			// file input
			$("#livestream_scanner input:file").on("change", function(e) {
				if (e.target.files && e.target.files.length) {
					Quagga.decodeSingle($.extend({}, fileConfig, {src: URL.createObjectURL(e.target.files[0])}), function(result) {alert(result.codeResult.code);});
				}
			});

		});
	</script>

</head>

<body style="background-color: #fff;">
	<div class="container" >
		<div class="header" id="header"><?php include 'layout/header.php'; ?></div>
		<div>
			<div class="content" style="min-height:30px;" id="content">
				<?php include raiz().'campo/layout/content.php'; ?>
			</div>
			<br/>
			<div class="footerL"><?php include 'layout/footer.php'; ?></div>
		</div>
	</div>
	<div class="modal fade" id="popUp" role="dialog"  style="overflow-y: auto !important;" data-backdrop="static" data-keyboard="false">
		<div id="modal" class="modal-dialog modal-lg">
			<div class="modal-content" style="border-radius: 0px;" id="popCont">
				Cargando...
			</div>
		</div>
	</div>
	<div class="modal fade" id="popUpMapa" role="dialog" style="overflow-y: auto !important;" data-backdrop="static" data-keyboard="false">
		<div id="modalMapa" class="modal-dialog modal-lg">
			<div class="modal-content" style="border-radius: 0px;" id="popContMapa">
				Cargando...
			</div>
		</div>
	</div>
	<div class="modal fade" id="popUpImg" role="dialog" style="overflow-y: auto !important;" data-backdrop="static" data-keyboard="false">
		<div id="modalImg" class="modal-dialog modal-lg">
			<div class="modal-content" style="border-radius: 0px;" id="popContImg">
				Cargando...
			</div>
		</div>
	</div>
	<div class="modal fade" id="alertas" role="dialog" style="overflow-y: auto !important;" >
		<div id="modalAlerta" class="modal-dialog">
			<div class="modal-content" style="border-radius: 0px;" id="alertasCont">
				Cargando...
			</div>
		</div>
	</div>

	<div class="modal fade" id="livestream_scanner" role="dialog" style="overflow-y: auto !important;" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" id="modalScanner">
			<div class="modal-content">
				<div class="modal-header nuevo" >
					<div style="text-align: center;">
						<h4 class="modal-title">Escanear código de barras</h4>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="position: static;">
					<div id="interactive" class="viewport"></div>
					<div class="error"></div>
				</div>
				<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>


</body>
</html>



