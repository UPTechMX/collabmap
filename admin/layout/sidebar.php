<?php 



?>

<script type="text/javascript">
	$(document).ready(function() {

		$('#edtAbout').click(function(event) {
			var elem = 'about';
			popUp('admin/administration/general/edtGral.php',{elem:elem});
		});
		$('#edtPrivacy').click(function(event) {
			var elem = 'privacy';
			popUp('admin/administration/general/edtGral.php',{elem:elem});
		});

		$('[data-toggle="tooltip"]').tooltip({
			html:true,
		});


	});
</script>

<div style="padding: 0px 10px;">
	<!-- <?php include raiz().'general/lang.php'; ?> -->
</div>

<div style="margin: 40px 10px;padding-left: 10%;padding-top: 10px;">
	<a href="<?php echo $_SERVER['PHP_SELF'];?>?">
		<img src="../img/cmLogo.png" style="width: 65%;margin-left:17%" />
	</a>
</div>

<div style="padding: 0px 30px;">
	<table style="width: 100%; font-size: .8em;" >
		<tr>
			<td class="sideMenuTitle azul"><?= TR('general'); ?></td>
		</tr>
		<tr>
			<td class="sideMenuOption" style="padding-top: 10px;">
				<span class="manita" id="edtAbout">
					<?= TR('about'); ?>
				</span>
				<i class="glyphicon glyphicon-info-sign" style="margin-left: 15px;" 
					data-toggle="tooltip" data-placement="right" title="<?= TR('aboutTooltip') ?>"></i>
			</td>
		</tr>
		<tr>
			<td class="sideMenuOption">
				<a href="<?php echo $_SERVER['PHP_SELF'];?>?Act=news"><?= TR('news'); ?></a>
				<i class="glyphicon glyphicon-info-sign" style="margin-left: 15px;" 
					data-toggle="tooltip" data-placement="right" title="<?= TR('newsTooltip') ?>"></i>
			</td>
		</tr>
		<tr>
			<td class="sideMenuOption">
				<span class="manita" id="edtPrivacy">
					<?= TR('noticeofprivacy'); ?>
				</span>
				<i class="glyphicon glyphicon-info-sign" style="margin-left: 15px;" 
					data-toggle="tooltip" data-placement="right" title="<?= TR('privacyTooltip') ?>"></i>
			</td>
		</tr>

		<tr>
			<td class="sideMenuTitle azul"><?= TR('projects'); ?></td>
		</tr>
		<tr>
			<td class="sideMenuOption">
				<a href="<?php echo $_SERVER['PHP_SELF'];?>?Act=prjs"><?= TR('projects'); ?></a>
				<i class="glyphicon glyphicon-info-sign" style="margin-left: 15px;" 
					data-toggle="tooltip" data-placement="right" title="<?= TR('projectsTooltip') ?>"></i>
			</td>
		</tr>

		<tr>
			<td class="sideMenuTitle azul"><?= TR('surveys'); ?></td>
		</tr>
		<tr>
			<td class="sideMenuOption">
				<a href="<?php echo $_SERVER['PHP_SELF'];?>?Act=chk"><?= TR('surveys'); ?></a>
				<i class="glyphicon glyphicon-info-sign" style="margin-left: 15px;" 
					data-toggle="tooltip" data-placement="right" title="<?= TR('surveysTooltip') ?>"></i>
			</td>
		</tr>

		<tr>
			<td class="sideMenuTitle azul"><?= TR('modules'); ?></td>
		</tr>
		<!-- <tr>
			<td class="sideMenuOption">
				<a href="<?php echo $_SERVER['PHP_SELF'];?>?Act=cons"><?= TR('consultations'); ?></a>
				<i class="glyphicon glyphicon-info-sign" style="margin-left: 15px;" 
					data-toggle="tooltip" data-placement="right" title="<?= TR('consultationsTooltip') ?>"></i>
				
				<ul style="margin-top: 5px;margin-block-end:0px;font-size: .9em;">
					<li>
						<a href="<?php echo $_SERVER['PHP_SELF'];?>?Act=complaints" style="display: inline;"  ><?= TR('complaints'); ?></a>
						
					</li>
				</ul>
			</td>
		</tr> -->
		<tr>
			<td class="sideMenuOption">
				<a href="<?php echo $_SERVER['PHP_SELF'];?>?Act=trg"><?= TR('targets'); ?></a>
				<i class="glyphicon glyphicon-info-sign" style="margin-left: 15px;" 
					data-toggle="tooltip" data-placement="right" title="<?= TR('tergetsTooltip') ?>"></i>
			</td>
		</tr>
		<tr>
			<td class="sideMenuOption">
				<a href="<?php echo $_SERVER['PHP_SELF'];?>?Act=pubC"><?= TR('publicCons'); ?></a>
				<i class="glyphicon glyphicon-info-sign" style="margin-left: 15px;" 
					data-toggle="tooltip" data-placement="right" title="<?= TR('publicCTooltip') ?>"></i>
			</td>
		</tr>

		<tr>
			<td class="sideMenuTitle azul"><?= TR('usersAndAdminitrators'); ?></td>
		</tr>
		<tr>
			<td class="sideMenuOption">
				<a href="<?php echo $_SERVER['PHP_SELF'];?>?Act=extUsr"><?= TR('externalUsers'); ?></a>
				<i class="glyphicon glyphicon-info-sign" style="margin-left: 15px;" 
					data-toggle="tooltip" data-placement="right" title="<?= TR('usersTooltip') ?>"></i>
			</td>
		</tr>
		<tr>
			<td class="sideMenuOption">
				<a href="<?php echo $_SERVER['PHP_SELF'];?>?Act=usrInt"><?= TR('internalUsers'); ?></a>
				<i class="glyphicon glyphicon-info-sign" style="margin-left: 15px;" 
					data-toggle="tooltip" data-placement="right" title="<?= TR('adminsTooltip') ?>"></i>
			</td>
		</tr>



	</table>
</div>