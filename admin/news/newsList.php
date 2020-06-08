<?php  
	if(!function_exists('raiz')){
		include_once '../../lib/j/j.func.php';
	}
// print2($_POST);
checaAcceso(50);// checaAcceso news

$news = $db->query("SELECT * FROM News ORDER BY `timestamp` DESC ")->fetchAll(PDO::FETCH_ASSOC);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#newsTable .edtEle').click(function(event) {
			var eleId = $(this).closest('tr').attr('id').split('_')[1];
			popUp('admin/news/newsAdd.php',{eleId:eleId},function(){},{});
			// $('#infoFinanciador').load(rz+'admin/administration/financiadores/financiadoresAdd.php',{eleId:eleId})
		});
		$('#newsTable .delNot').click(function(event) {
			var nId = $(this).closest('tr').attr('id').split('_')[1];
			var tr = $(this).closest('tr');
			conf('<?php echo TR("confdeleteDoc");?>',{tr:tr,nId:nId},function(e){
				var rj = jsonF('admin/news/json/json.php',{acc:3,nId:e.nId});
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					e.tr.remove();
				}
			});

		});

	});
</script>

<div>
	<table class="table" id="newsTable">
		<thead>
			<tr>
				<th><?php echo TR('news'); ?></th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach($news as $n){ 
				$count = $db->query("SELECT COUNT(*) as cuenta FROM UsersLikes WHERE newsId = $n[id]")->fetchAll(PDO::FETCH_NUM)[0][0];
			?>
				<tr id="trNot_<?php echo $n['id']; ?>">
					<td>
						<div class="newsName"><?php echo $n['name']; ?></div>
						<div class="newsHeader"><?php echo $n['header']; ?></div>
						<div class="likes">Likes: <strong><?php echo $count; ?></strong></div>
					</td>
					<td>
						<i class="glyphicon glyphicon-pencil manita edtEle"></i>
					</td>
					<td>
						<i class="glyphicon glyphicon-trash manita rojo delNot"></i>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
