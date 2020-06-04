<?php
	session_start();
	if(!function_exists('raiz')){
		include '../../lib/j/j.func.php';
	}

	if(!is_numeric($_POST['documentId'])){
		exit();
	}
	$usrId = $_SESSION['CM']['consultations']['usrId'];

	$sql = "
		SELECT dc.*, de.nombre as deName
		FROM DocumentsComments dc
		LEFT JOIN DimensionesElem de ON de.id = dc.dimensionesElemId
		WHERE dc.documentsId = $_POST[documentId] AND dc.usersId = '$usrId'";
	$comments = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

	

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.edtComment').click(function(event) {
			var tr = $(this).closest('tr');
			tr.find('.noEdt').hide();
			tr.find('.edt').show();
		});

		$('.sendEdt').click(function(event) {
			var tr = $(this).closest('tr');
			var cId = tr.attr('id').split('_')[1];
			var comment = tr.find('.commentText').val();
			// console.log(comment);
			if(comment != ''){
				var rj = jsonF('consultations/consultation/json/json.php',{acc:9,cId:cId,comment:comment});
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					tr.find('.edt').hide();
					tr.find('.noEdt').show();
					tr.find('.textOrig').text(comment);
				}
			}
		});
		$('.cancelEdt').click(function(event) {
			var tr = $(this).closest('tr');
			var cId = tr.attr('id').split('_')[1];
			var comment = tr.find('.textOrig').text();
			console.log(comment);
			tr.find('.commentText').val(comment);
			tr.find('.commentSpan').text(comment);
			tr.find('.edt').hide();
			tr.find('.noEdt').show();

		});

		$('.commentText').keyup(function(event) {
			var tr = $(this).closest('tr');
			var comment = $(this).val();
			tr.find('.commentSpan').text(comment);
		});

		$('.delComment').click(function(event) {
			// console.log('aaa');
			var tr = $(this).closest('tr');
			var cId = tr.attr('id').split('_')[1];
			conf('<?php echo TR("confdeleteComment") ?>',{tr:tr,cId:cId},function(e){
				var rj = jsonF('consultations/consultation/json/json.php',{acc:10,cId:e.cId});
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					e.tr.remove();
				}
			})
		});




	});
</script>

<div class="modal-header nuevo" >
	<div style="text-align: center;">
		<h4 class="modal-title">
			<?php echo TR('comments'); ?>
		</h4>
	</div>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
	  <span aria-hidden="true">&times;</span>
	</button>
</div>
<div class="modal-body" id='pano' style='width:100%;border: none 1px;'>
	<div>
		<table class="table">
			<thead>
				<tr>
					<th><?php echo TR('docLevel'); ?></th>
					<th><?php echo TR('comment'); ?></th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($comments as $c){ ?>
					<tr id="trComment_<?php echo $c['id'];?>">
						<td><?php echo $c['deName']; ?></td>
						<td>
							<span class="noEdt commentSpan">
								<?php echo $c['comment']; ?>
							</span>
							<span class="textOrig" style="display:none;"><?php echo $c['comment']; ?></span>
							<input type="text" name="comment" class="commentText form-control edt" 
							value="<?php echo $c['comment']; ?>" style="display:none;">
						</td>
						<td>
							<i class="glyphicon glyphicon-pencil manita noEdt edtComment"></i>
							<span class="btn btn-sm btn-shop sendEdt edt" style="display: none;"><?php echo TR('send'); ?></span>
							<span class="btn btn-sm btn-cancel cancelEdt edt" style="display: none;"><?php echo TR('cancel'); ?></span>
						</td>
						<td>
							<i class="glyphicon glyphicon-trash manita rojo delComment"></i>
						</td>
					</tr>	
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<div class="modal-footer">
	<div style="text-align: right;">
		<span id="cancel" data-dismiss="modal" class="btn btn-sm btn-shop"><?php echo TR('close'); ?></span>
	</div>
</div>
