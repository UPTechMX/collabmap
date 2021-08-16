<?php 



?>

<script type="text/javascript">
	$(document).ready(function() {

		setTimeout(function(){ $('#M<?php echo $_GET['Act']; ?>').addClass('active'); }, 1);
		var inc = '<?php echo $_GET['Act']; ?>';
		if(inc == ''){
			setTimeout(function(){ $('#Minic').addClass('active'); }, 1);
		}

		$('#chPwd').click(function(event) {
			// console.log('chPwd');
			popUp('analysis/inicio/chPwd.php',{},function(){},{});
		});
		$('#chChk').click(function(event) {
			// console.log('chPwd');
			popUp('analysis/inicio/chChk.php',{},function(){},{});
		});


	});
</script>

<div style="padding: 0px 10px;">
	<!-- <?php include raiz().'general/lang.php'; ?> -->
</div>

<div style="margin: 40px 10px;padding-left: 10%;padding-top: 10px;">
	<a href="<?php echo $_SERVER['PHP_SELF'];?>?">
		<img src="../img/cmLogo.png" style="width: 90%;margin-left:5%" />
	</a>
</div>

<div style="padding: 0px 30px;">
	<table style="width: 100%; font-size: .8em;" >
		<tr>
			<td class="sideMenuTitle azul"><?= TR('chChk'); ?></td>
		</tr>
		<tr>
			<td class="sideMenuOption" style="padding-top: 10px;">
				<span class="manita" id="chChk">
					<?= TR('chChk'); ?>
				</span>
			</td>
		</tr>

		<tr>
			<td class="sideMenuTitle azul"><?= TR('user'); ?></td>
		</tr>
		<tr>
			<td class="sideMenuOption" style="padding-top: 10px;">
				<span class="manita" id="chPwd">
					<?= TR('chPwd'); ?>
				</span>
			</td>
		</tr>
		<tr>
			<td class="sideMenuOption">
				<a href="<?php echo $_SERVER['PHP_SELF'];?>?logout=1"><?= TR('close_session'); ?></a>
			</td>
		</tr>

	</table>
</div>