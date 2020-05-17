<?php
session_start();
include_once '../../lib/j/j.func.php';
checaAcceso(5); // checaAcceso analysis;

?>

<div class="attrNew">
	<div class="row attrRow" style="margin-top: 10px;">
		<div class="col-1" style="text-align: center;">
			<h5>&nbsp;</h5>
			<span class="manita rojo delAttr">
				<i class="glyphicon glyphicon-trash"></i>
			</span>
		</div>
		<div class="col-1">
			<h5>&nbsp;</h5>
			<select class="form-control logic">
				<option value="1"><?php echo TR('and'); ?></option>
				<option value="2"><?php echo TR('or'); ?></option>
			</select>
		</div>
		<div class="col-3" id="attrDiv">
			<h5 style="font-weight: bold;"><?php echo TR('selAttr'); ?></h5>
			<select class="form-control attrSel" style="margin-top: 10px;"></select>
		</div>
		<div class="col-3 valSel attrValDiv" style="display: none;">
			<h5 style="font-weight: bold;"><?php echo TR('attrVal'); ?></h5>
			<select class="form-control attrValSel" style="margin-top: 10px;"></select>
		</div>
		<div class="col-3 valSel numMod" style="display:none;">
			<h5><?php echo TR('inequality'); ?></h5>
			<select class="form-control KMLInequality">
				<option value=">"><?php echo TR('greater-than'); ?></option>
				<option value=">="><?php echo TR('greater-or-equal'); ?></option>
				<option value="="><?php echo TR('equal'); ?></option>
				<option value="<="><?php echo TR('less-or-equal'); ?></option>
				<option value="<"><?php echo TR('less-than'); ?></option>
			</select>
		</div>
		<div class="col-3 valSel numMod" style="display:none;">
			<h5><?php echo TR('value'); ?></h5>
			<input type="text" class="form-control KMLValue" value="10"/>
		</div>
	</div>
</div>
