<?php  
@session_start();
$Act = $_REQUEST['Act'];
$usrId = $_SESSION['pub']['usrId'];
$pryId = isset($_GET['pryId'])?$_GET['pryId']:0;
$mId = isset($_GET['mId'])?$_GET['mId']:0;

?>

<script type="text/javascript">
	$(document).ready(function() {
		var height = $('#marquesina').height();
		// console.log(height);
		$('#logo').css({
			height: height,
		});
		// console.log($('#logo').width());
	});
</script>


<div style="text-align:center;margin-top: 20px;" class="hidden-xs" style="position: relative;">
	<?php if (!empty($_SESSION['pub']['logotipo'])){ ?>	
		<img src="../img/logos/<?php echo $_SESSION['pub']['logotipo'];?>" height="165px" id="logo"
			style="position:absolute;"  />
	<?php } ?>
	<img src="<?php echo aRaiz(); ?>img/marquesina.png" width="100%" id="marquesina" 
		style=" margin-left:auto;margin-right:auto;" usemap="#logosMap" />
</div>
<?php include 'menu.php'; ?>
<div id="dAlerta"></div>