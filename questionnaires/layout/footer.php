<?php
	@date_default_timezone_set('America/Mexico_City');
	$y = @date('Y');
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" rel="stylesheet" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/js/all.min.js" crossorigin="anonymous"></script>
<style>
	.footerSection a { color: #5A5A5A; }
	.footerSection a:hover { color: #000}
	#tutorialsSection a {text-decoration: none;}
	#tutorialsSection a .tHeader { color: #000; text-decoration: none;}
	#tutorialsSection a .tDesc { color: grey; text-decoration: none;}
	#tutorialsSection a:hover .tHeader { color: #000; text-decoration: none;}
	#tutorialsSection a:hover .tDesc { color: grey; text-decoration: none;}
</style>

<div id="tutorialsSection" style="display: none;">
	<hr/>
	<div style="margin-top: 10px; color: grey;">
		<div class="row" style="border:solid 0px;">
			<div class="col-md-6" >
				<a href="https://www.youtube.com/watch?v=Ro-aHLC6N2o" target="_blank">
					<div class="row" style="padding-top: 10px;">
						<div class="col-4" style="padding-right: 0px;">
								<img class="imgTut" file="tutorial0.png" width="100%" />
						</div>
						<div class="col-8">
							<div style="border-left: solid 1px black;padding: 10px;height: 100px;">
								<strong class="tHeader"><?php echo TR('vid1Name'); ?></strong><br/>
								<span class="tDesc"><?php echo TR('vid1Descript'); ?></span>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-md-6">
				<a href="https://docs.google.com/presentation/d/1peJmf30ESzwH0nMUvSM_R8bsIAvfHYDoc-CcvzyvXgI/edit#slide=id.g8598ae5746_0_0" target="_blank">
					<div class="row" style="padding-top: 10px;">
						<div class="col-4" style="padding-right: 0px;">
								<img class="imgTut" file="tutorial1.png" width="100%" />
						</div>
						<div class="col-8">
							<div style="border-left: solid 1px black;padding: 10px;height: 100px;">
								<strong class="tHeader"><?php echo TR('tut1Name'); ?></strong><br/>
								<span class="tDesc"><?php echo TR('tut1Descript'); ?></span>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
</div>
<hr/>
<div style="font-size:x-small;position:relative; width:100%; border:none 1px;background-color:rgba(0,0,0,0);" >
	<div style="position:relative;float:right;margin:-2px 0px 0px 0; border:none 1px;font-size:x-small">

		<!-- <table class="tablaSB" border="0" style ="margin:0px 0px 0px 0px;"cellspacing="0" cellpadding="0">
			<tr>
				<td style ="font-style:oblique; font-size:x-small;color:#333;text-align:right;">
					&nbsp; &nbsp; powered by:&nbsp;
				</td>
				<td style="opacity:1">
					<a target="new" href = "http://notland.mx" style = "color:#A0A0A0;text-decoration: none">
						<img id="imgReporta_<?echo $infoMeta['id'];?>" src="<?php echo aRaiz(); ?>img/logoNotLand.png" width="50">
					</a>
				</td>
			</tr>
		</table> -->

	</div>
	<div style="none:none 1px; font-size:x-small; color:#333; font-size: x-small; display: flex; justify-content: space-between;" id="botonesAct" class="footerSection">
		<div>&#169;&nbsp;<?php echo $y?>. CAPSUS.</div>

		<!-- show the tutorial if only user not logged in -->
		<?php if($_SESSION['CM']['questionnaires']['usrId'] == "") echo '<div id="tutorial"><a href="https://docs.google.com/forms/d/e/1FAIpQLSf79SblRdMXkp3xaL6JOJADlH93EjCODxyceYQ2xbdBEi04Ew/viewform" target="_blank"><i class="fas fa-bug"></i> ' . TR('reportProblems') . '</a> | <a href="javascript:void(0)" id="tutorialButton"><i class="far fa-file-alt"></i> ' . TR('tutorials') . '</a></div>'; ?>
	</div>

</div>


<script type="text/javascript">

	$("#tutorialButton").on("click", () => {
		$("#tutorialsSection").slideToggle("slow");
		$("html, body").animate({ scrollTop: $(document).height() }, 1000);
	})

	$(document).ready(function() {
		$.each($('.imgTut'), function(index, val) {
			var file = $(this).attr('file');
			$(this).attr({src:rz+'img/'+file});
		});
		$.each($('.aFile'), function(index, val) {
			var file = $(this).attr('file');
			$(this).attr({href:rz+'img/'+file});
		});
	});

</script>

<br/>
<div id="debug">
<?php 
//echo "\n"."Session:"."\n";
//prr($_SESSION); 
//echo "\n"."Post:"."\n";
//prr($_POST); 
?>
</div>