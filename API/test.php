<?php
include_once '../lib/j/j.func.php';

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
	<script src="../lib/j/j.js"></script>

	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

	<script src="../lib/js/hereMaps/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
	<script src="../lib/js/hereMaps/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="../lib/js/hereMaps/mapsjs-ui.js"></script>
	<script type="text/javascript" src="../lib/js/hereMaps/mapsjs-mapevents.js"></script>
	<script type="text/javascript" src="../lib/js/jquery-confirm/dist/jquery-confirm.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../lib/js/jquery-confirm/dist/jquery-confirm.min.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="../lib/js/hereMaps/mapsjs-ui.css" media="screen" />


	<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
	<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">


	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129367922-1"></script>
	

</head>

<?php

$myfile = fopen("data.txt", "r") or die("Unable to open file!");
$rj = fread($myfile,filesize("data.txt"));
$r = json_decode($rj,true);
fclose($myfile);
// print2(atj($r['elemento']));;


?>

<script type="text/javascript">
	$(function() {
	});
</script>

<body style="background-color: #fff;">
	<div class="container" >
		<div>
			<div class="content" style="min-height:30px;" id="content">
				<form action="sendCampo.php" method="post" target="_blank">
					usrId: <input type="text"  name="usrId" value="<?php echo $r['usrId']; ?>"><br/>
					nivel: <input type="text"  name="nivel" value="<?php echo $r['nivel']; ?>"><br/>
					hash: <input type="text"  name="hash" value="<?php echo $r['hash']; ?>"><br/>
					tipo: <input type="text"  name="tipo" value="<?php echo $r['tipo']; ?>"><br/>
					elemento: <textarea  name="elemento"><?php echo atj($r['elemento']); ?></textarea><br/>
					<input type="submit">
				</form>

			</div>
			<br/>
			<div class="footerL"><?php include '../admin/layout/footer.php'; ?></div>
		</div>
	</div>
</body>
</html>


