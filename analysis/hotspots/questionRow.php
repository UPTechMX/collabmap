<?php
session_start();
include_once '../../lib/j/j.func.php';
checaAcceso(5); // checaAcceso analysis;

?>

<div class="row rowPreg" style="margin-top: 10px;">
	<div class="col-1" style="text-align: center;">
		<span>
			<h5>&nbsp;</h5>
			<i class="glyphicon glyphicon-trash manita rojo delRowPreg"></i>
		</span>
	</div>
	<div class="col-3">
		<h5><?php echo TR('selChk'); ?></h5>
		<select class="form-control selChkHSanalysis">
			<option value=""><?php echo TR('selChk'); ?></option>
			<?php foreach ($chks as $c){ ?>
				<option value="<?php echo $c['cId']; ?>" tcId="<?php echo $c['id']; ?>">
					<?php echo $c['cName']; ?>
				</option>
			<?php } ?>
		</select>
	</div>
	<div class="col-2">
		<h5><?php echo TR('numericQuestion'); ?></h5>
		<select class="form-control HSNumQuestionSel">
			<option value=""><?php echo TR('numericQuestion'); ?></option>
		</select>
	</div>
	<div class="col-2 qMods numModCHK" style="display:none;">
		<h5><?php echo TR('inequality'); ?></h5>
		<select class="form-control HSInequality" id="HSInequality">
			<option value=">"><?php echo TR('greater-than'); ?></option>
			<option value=">="><?php echo TR('greater-or-equal'); ?></option>
			<option value="="><?php echo TR('equal'); ?></option>
			<option value="<="><?php echo TR('less-or-equal'); ?></option>
			<option value="<"><?php echo TR('less-than'); ?></option>
		</select>
	</div>
	<div class="col-2 qMods numModCHK numModInq" style="display:none;">
		<h5><?php echo TR('value'); ?></h5>
		<input type="text" class="form-control HSValue" value="10"/>
	</div>
	<div class="col-3 qMods multMod" style="display:none;">
		<h5><?php echo TR('answers'); ?></h5>
		<select class="form-control HSAnswer"></select>
	</div>
</div>
