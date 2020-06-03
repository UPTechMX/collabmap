<?php

	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
	checaAcceso(10);// checaAcceso complaints

	// print2($_POST);

	$complaint = $db->query("SELECT c.*, e.name as eName, con.name as conName, de.nombre as deName, 
		ua.name as uaName, ua.lastname as uaLastname
		FROM Complaints c
		LEFT JOIN usrAdmin ua ON ua.id = adminId
		LEFT JOIN Consultations con ON con.id = c.consultationsId
		LEFT JOIN DimensionesElem de ON de.id = c.dimensionesElemId
		LEFT JOIN Estatus e ON c.status = e.code AND e.tabla = 'complaints'
		WHERE c.id = $_POST[complaintsId]")->fetchAll(PDO::FETCH_ASSOC)[0];
	
	$status = $db->query("SELECT * FROM Estatus WHERE tabla = 'Complaints' ORDER BY code")->fetchAll(PDO::FETCH_ASSOC);


?>

<script type="text/javascript">
	$(document).ready(function() {

		$('.form-control').keydown(function(event) {
			$(this).css({backgroundColor:''});
			// event.preventDefault();
			if(event.keyCode == 13){
				event.preventDefault();
				return false;
			}
		});

		$('.selOblig').change(function(event) {
			$(this).css({backgroundColor:''});
		});

		$('#env').click(function(event) {
			var dat = $('#nEmp').serializeObject();
			dat.id = <?php echo $_POST['complaintsId']; ?>;
			var allOk = camposObligatorios('#nEmp');


			if(allOk){
				var rj = jsonF('admin/complaints/json/json.php',{datos:dat,acc:3,opt:1});
				// console.log(rj);
				var r = $.parseJSON(rj);
				// console.log(r);
				if(r.ok == 1){
					$('#popUp').modal('toggle');
					$('#complaintsInfo').load(rz+'admin/complaints/complaintsInfo.php',{complaintsId:dat.id});
					$('#trComp_'+dat.id).appendTo('#tbodyComp_'+dat.status);
				}
			}

		});
		$(".txArea").jqte({
			source:true,
			rule: false,
			link:false,
			unlink: false,
			format:false
		});


	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('project'); ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
	  <span aria-hidden="true">&times;</span>
	</button>

</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<br/>
	<form id="nEmp">
		<table class="table" border="0">
			<tr>
				<td><?php echo TR("actualStatus"); ?></td>
				<td><?php echo TR($complaint['eName']); ?></td>
			</tr>
			<tr>
				<td><?php echo TR("description"); ?></td>
				<td><?php echo $complaint['description']; ?></td>
			</tr>
			<tr>
				<td><?php echo TR('status'); ?></td>
				<td>
					<select class="form-control oblig" id="status" name="status">
						<option value="">- - - <?php echo TR('status'); ?> - - -</option>
						<?php foreach ($status as $s){ ?>
							<option value="<?php echo $s['code']; ?>"><?php echo TR($s['name']); ?></option>
						<?php } ?>
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo TR('comment'); ?></td>
				<td>
					<textarea class="form-control oblig" id="comment" name="comment"></textarea>
				</td>
				<td></td>
			</tr>
		</table>		
	</form>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-cancel"><?php echo TR('cancel'); ?></span>
		<span id="env" class="btn btn-sm btn-shop"><?php echo TR('send'); ?></span>
	</div>
</div>