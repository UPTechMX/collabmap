<?php include_once '../../lib/j/j.func.php'; ?>


				<div style="text-align: center;">
					<br/>
					<br/>
					<span style="font-size:small;">Este correo ha sido enviado de manera automática, favor de no responderlo.</span>
				</div>
			</div>
			<div class="footerL">
				<?php
					@date_default_timezone_set('America/Mexico_City');
					$d = @getdate();
				?>
				<div style="font-size:x-small;position:relative; border:none 1px;background-color:rgba(0,0,0,0);" >
					<div style="position:relative;float:right;margin:-2px 0px 0px 0; border:none 1px;font-size:x-small">
						<table class="tablaSB" border="0" style ="margin:0px 0px 0px 0px;"cellspacing="0" cellpadding="0">
							<tr>
								<td style ="font-style:oblique; font-size:x-small;color:#333;text-align:right;">
									powered by:&nbsp;
								</td>
								<td style="opacity:1">
									<a target="new" href = "http://capsus.mx" style = "color:#A0A0A0;text-decoration: none">
										<img id="imgReporta_<?echo $infoMeta['id'];?>" src="http://url/logoCapsus.png" width="50">
									</a>
								</td>
							</tr>
						</table>
					</div>
					<div style ="none:none 1px;font-size:x-small;color:#333;" id="botonesAct">
						&#169;&nbsp; <a href="http://www.capsus.mx" target="_blank">CAPSUS.</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>


