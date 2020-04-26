<?php
// print_r($_COOKIE);
include_once '../lib/j/j.func.php';
include_once 'seguridad/seguridad.php';

checaAcceso(49);

// print2(datCodigoPostal('85203'));;
// print2($_SESSION);

// echo $_TRANSLATE['pollHealth'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="initial-scale = 1.0,maximum-scale = 1.0" />

	<title><?php echo TR('systemName'); ?> ADMIN</title>
	
	<!-- LIBRERIAS CSS -->
	<link href="../lib/js/bootstrap4/css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="../lib/j/j.css" rel="stylesheet" type="text/css" />
	<link href="../lib/js/jQuery-TE/jquery-te-1.4.0.css" rel="stylesheet" type="text/css" />
	<link href="../lib/js/selectpicker/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css" />
	<link href="../lib/js/jquery-upload-file/css/uploadfile.css " rel="stylesheet" type="text/css" />
	<link href="../lib/css/general.css" rel="stylesheet" type="text/css" />
	<link href="../lib/css/font-awesome.min.css " rel="stylesheet" type="text/css" />
	<link href="../lib/js/sumoselect/sumoselect.css " rel="stylesheet" type="text/css" />
	<link href="../lib/js/starrr/starrr.css " rel="stylesheet" type="text/css" />


	<!-- <link href="../lib/D/D.css" rel="stylesheet" type="text/css" /> -->
	<!-- <link href="../lib/js/jstree/themes/default/style.css" rel="stylesheet" type="text/css" /> -->
	<!-- <link href="../lib/js/jqueryUI/jquery-ui.css" rel="stylesheet" type="text/css" /> -->
	<link href="../lib/js/jqueryUI/jquery-ui.css" rel="stylesheet" type="text/css" />
	<!-- LIBRERIAS JAVASCRIPT -->
	<script src="../lib/js/jquery-3.1.1.min.js"></script>
	<script src="../lib/js/popper.min.js"></script>
	<script src="../lib/js/jqueryUI/jquery-ui.js"></script>
	<script src="../lib/js/bootstrap4/js/bootstrap.js"></script>
	<script src="../lib/js/jquery-upload-file/js/jquery.uploadfile.js"></script>
	<script src="../lib/js/selectpicker/dist/js/bootstrap-select.js"></script>
	<script src="../lib/js/jQuery-TE/jquery-te-1.4.0.min.js"></script>
	<script src="../lib/js/sumoselect/jquery.sumoselect.js"></script>
	<script src="../lib/js/starrr/starrr.js"></script>
	<script src="../lib/j/j.js" charset="utf-8"></script>

	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

	<script type="text/javascript" src="../lib/js/jquery-confirm/dist/jquery-confirm.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../lib/js/jquery-confirm/dist/jquery-confirm.min.css" media="screen" />

	<script src="../lib/js/leaflet/leaflet.js"></script>
	<script src="../lib/js/LeafletHeat/dist/leaflet-heat.js"></script>
	<link href="../lib/js/leaflet/leaflet.css" rel="stylesheet" type="text/css" />

	<script src="../lib/js/leaflet.draw/Leaflet.draw.js"></script>
	<script src="../lib/js/leaflet.draw/Leaflet.Draw.Event.js"></script>
	<link rel="stylesheet" href="../lib/js/leaflet.draw/leaflet.draw.css"/>

	<script src="../lib/js/leaflet.draw/Toolbar.js"></script>
	<script src="../lib/js/leaflet.draw/Tooltip.js"></script>

	<script src="../lib/js/leaflet.draw/ext/GeometryUtil.js"></script>
	<script src="../lib/js/leaflet.draw/ext/LatLngUtil.js"></script>
	<script src="../lib/js/leaflet.draw/ext/LineUtil.Intersect.js"></script>
	<script src="../lib/js/leaflet.draw/ext/Polygon.Intersect.js"></script>
	<script src="../lib/js/leaflet.draw/ext/Polyline.Intersect.js"></script>
	<script src="../lib/js/leaflet.draw/ext/TouchEvents.js"></script>

	<script src="../lib/js/leaflet.draw/draw/DrawToolbar.js"></script>
	<script src="../lib/js/leaflet.draw/draw/handler/Draw.Feature.js"></script>
	<script src="../lib/js/leaflet.draw/draw/handler/Draw.SimpleShape.js"></script>
	<script src="../lib/js/leaflet.draw/draw/handler/Draw.Polyline.js"></script>
	<script src="../lib/js/leaflet.draw/draw/handler/Draw.Marker.js"></script>
	<script src="../lib/js/leaflet.draw/draw/handler/Draw.Circle.js"></script>
	<script src="../lib/js/leaflet.draw/draw/handler/Draw.CircleMarker.js"></script>
	<script src="../lib/js/leaflet.draw/draw/handler/Draw.Polygon.js"></script>
	<script src="../lib/js/leaflet.draw/draw/handler/Draw.Rectangle.js"></script>


	<script src="../lib/js/leaflet.draw/edit/EditToolbar.js"></script>
	<script src="../lib/js/leaflet.draw/edit/handler/EditToolbar.Edit.js"></script>
	<script src="../lib/js/leaflet.draw/edit/handler/EditToolbar.Delete.js"></script>

	<script src="../lib/js/leaflet.draw/Control.Draw.js"></script>

	<script src="../lib/js/leaflet.draw/edit/handler/Edit.Poly.js"></script>
	<script src="../lib/js/leaflet.draw/edit/handler/Edit.SimpleShape.js"></script>
	<script src="../lib/js/leaflet.draw/edit/handler/Edit.Rectangle.js"></script>
	<script src="../lib/js/leaflet.draw/edit/handler/Edit.Marker.js"></script>
	<script src="../lib/js/leaflet.draw/edit/handler/Edit.CircleMarker.js"></script>
	<script src="../lib/js/leaflet.draw/edit/handler/Edit.Circle.js"></script>





	<script src="https://cdn.quilljs.com/1.1.9/quill.js"></script>
	<link href="https://cdn.quilljs.com/1.1.9/quill.snow.css" rel="stylesheet">

	<script type="text/javascript">
	</script>

	

</head>

<body style="background-color: #fff;">
	<div class="container" >
		<div class="header" id="header"><?php include 'layout/header.php'; ?></div>
		<div>
			<div class="content" style="min-height:30px;" id="content">
				<?php include raiz().'admin/layout/content.php'; ?>

			</div>
			<br/>
			<div class="footerL"><?php include 'layout/footer.php'; ?></div>
		</div>
	</div>
	<div class="modal fade" id="popUpCuest" role="dialog"  style="overflow-y: auto !important;" data-backdrop="static" data-keyboard="false">
		<div id="modalCuest" class="modal-dialog modal-xl">
			<div class="modal-content" style="border-radius: 0px;" id="popContCuest">
				Cargando...
			</div>
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
</body>
</html>


