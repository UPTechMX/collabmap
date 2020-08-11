<?php
	session_start();
	if(!function_exists('raiz')){
		include '../../lib/j/j.func.php';
	}
?>


<div class="consultationCardCont manita" style="color:black;padding: 0px 15px;" id="consltCard_<?php echo $c['id'];?>">
	<div class="dateCard">
		<?php echo $c['initDate']; ?>
	</div>
	<div style="margin: 10px 5px;border-top: solid 2px grey;position: relative;"></div>
	<div style="">
		<div class="icono" style="margin-top: 20px;">
			<div style="width:30%;margin-left: auto;margin-right: auto;" class="iconContainer">
				<div style="width: 90px;height: 108px; " class="imgFondo">
					<div style="width: 100%;height: 100%;">
						<div style="text-align: center;padding-top: 15px;" class="iconDiv azul">
							<i class="fas <?php echo $c['icon']; ?> fa-4x"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div>
		<div class="consultationName negro">
			<?php echo $c['name']; ?>
		</div>
	</div>

</div>