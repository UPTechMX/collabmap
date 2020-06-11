<?php
	session_start();
	if(!function_exists('raiz')){
		include '../../lib/j/j.func.php';
	}
?>


<div class="consultationCardCont manita" style="color:<?php echo $color;?>;" id="consltCard_<?php echo $c['id'];?>">
	<div class="dateCard">
		<?php echo $c['initDate']; ?>
	</div>
	<div style="margin: 10px 5px;border-top: solid 2px;position: relative;"></div>
	<div style="">
		<div class="icono" style="margin-top: 20px;">
			<div style="width:30%;margin-left: auto;margin-right: auto;" class="iconContainer">
				<div style="width: 150px;height: 180px; " class="imgFondo">
					<div style="width: 100%;height: 100%;">
						<div style="text-align: center;padding-top: 15px;" class="iconDiv">
							<i class="fas <?php echo $c['icon']; ?> fa-4x"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div>
		<div class="consultationName">
			<?php echo $c['name']; ?>
		</div>
	</div>

</div>