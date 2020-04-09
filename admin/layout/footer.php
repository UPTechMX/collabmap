<?php
	@date_default_timezone_set('America/Mexico_City');
	$y = @date('Y');
?>
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
	<div style ="none:none 1px;font-size:x-small;color:#333;" id="botonesAct">
		&#169;&nbsp;<?php echo $y?>. Isla Urbana.
	</div>
</div>
<br/>
<div id="debug">
<?php 
//echo "\n"."Session:"."\n";
//prr($_SESSION); 
//echo "\n"."Post:"."\n";
//prr($_POST); 
?>
</div>