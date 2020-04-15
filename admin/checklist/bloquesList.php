<?php  

	// if($_POST['ajax'] == 1){
		include_once '../../lib/j/j.func.php';
	// }
		checaAcceso(50); // checaAcceso Checklist

	$bloques = $db->query("SELECT * FROM Bloques 
		WHERE checklistId = $_POST[checklistId] AND (elim IS NULL OR elim != 1) 
		ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

?>
<br/>

<script type="text/javascript">
	$(document).ready(function() {
		$( "#bloquesSort" ).sortable({
			scrollSpeed: 5,
			update: function( event, ui ) {
				$('#saveBloqueOrden').show();
			}
		});
		$( "#bloquesSort" ).disableSelection();

		$('#bloquesSort .edtBloque').click(function(event) {
			var bloqueId = this.id.split('_')[1];
			var checklistId = <?php echo $_POST['checklistId']; ?>;
			popUp('admin/checklist/bloquesAdd.php',{bloqueId:bloqueId,checklistId:checklistId},function(e){},{});
		});

		$('#bloquesSort .verBloque').click(function(event) {
			var bloqueId = this.id.split('_')[1];
			var nomBloq = $('#nomBloq_'+bloqueId).text();
			$('#areas').load(rz+'admin/checklist/areas.php',{bloqueId:bloqueId,nomBloq:nomBloq});
			$('#preguntas').empty();
		});

		$('#bloquesSort .delBloque').click(function(event) {
			var bloqueId = this.id.split('_')[1];
			// console.log(bloqueId);

			conf('<?php echo TR('delBlockMessage') ?>',{bloqueId:bloqueId},function(e){
				var rj = jsonF('admin/checklist/json/json.php',{datos:{id:e.bloqueId,elim:1},acc:2,opt:5,chkId:checklistId});
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#bloquesList').load(rz+'admin/checklist/bloquesList.php',
						{ajax:1,checklistId:<?php echo $_POST['checklistId']; ?>});
				}

			})
		});

		$('#bloquesSort .condBloque').click(function(event) {
			var areaId = this.id.split('_')[1];
			popUp('admin/checklist/condiciones.php',{eleId:areaId,aplicacion:'bloque'},function(e){},{});
		});



		
	});
</script>

<ul id="bloquesSort" class="list-group">
	<?php foreach ($bloques as $b): ?>
		<li class="list-group-item bloqueEle arrastra" id="bloqueEle_<?php echo $b['id'];?>">
			<div class="row">
				<div class="col-md-2">
					
					<strong>(<?php echo $b["identificador"]; ?>)</strong>
				</div>
				<div class="col-md-7" id="nomBloq_<?php echo $b['id'];?>">
					<?php echo "$b[nombre]"; ?>
				</div>
				<div class="col-md-3" style="text-align: right;">
					<i class="glyphicon glyphicon-pencil manita edtBloque" 
					id="edtBloque_<?php echo $b['id'];?>"></i>&nbsp;&nbsp;
					<i class="glyphicon glyphicon-trash manita delBloque rojo" 
					id="delBloque_<?php echo $b['id'];?>"></i>&nbsp;&nbsp;
					<i class="glyphicon glyphicon-question-sign manita condBloque" 
					id="condBloque_<?php echo $b['id'];?>"></i>&nbsp;&nbsp;

					<i class="glyphicon glyphicon-chevron-right manita verBloque" 
					id="verBloque_<?php echo $b['id'];?>"></i>&nbsp;&nbsp;
				</div>
			</div>
		</li>
	<?php endforeach ?>
  
</ul>
