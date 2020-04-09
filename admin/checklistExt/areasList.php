<?php  

	// if($_POST['ajax'] == 1){
		include_once '../../lib/j/j.func.php';
	// }
	checaAcceso(50);



	$areas = $db->query("SELECT * FROM AreasExt 
		WHERE bloquesId = $_POST[bloqueId] AND (elim IS NULL OR elim != 1) 
		ORDER BY orden")->fetchAll(PDO::FETCH_ASSOC);

?>
<br/>

<script type="text/javascript">
	$(document).ready(function() {
		$( "#areasSort" ).sortable({
			scrollSpeed: 5,
			update: function( event, ui ) {
				$('#saveAreaOrden').show();
			}
		});
		$( "#areasSort" ).disableSelection();

		$('#areasSort .edtArea').click(function(event) {
			var areaId = this.id.split('_')[1];
			var bloqueId = <?php echo $_POST['bloqueId']; ?>;
			popUp('admin/checklistExt/areasAdd.php',{areaId:areaId,bloqueId:bloqueId},function(e){},{});
		});

		$('#areasSort .verArea').click(function(event) {
			var areaId = this.id.split('_')[1];
			var nomArea = $('#nomArea_'+areaId).text();
			// console.log(areaId);
			$('#preguntas').load(rz+'admin/checklistExt/preguntas.php',{areaId:areaId,nomArea:nomArea});
		});

		$('#areasSort .delArea').click(function(event) {
			var areaId = this.id.split('_')[1];
			// console.log(areaId);
			conf('¿Está seguro que desea elilminar el área del bloque?',{areaId:areaId},function(e){
				var rj = jsonF('admin/checklistExt/json/json.php',{datos:{id:e.areaId,elim:1},acc:2,opt:6});
				// console.log(rj);
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					$('#areasList').load(rz+'admin/checklistExt/areasList.php',
						{ajax:1,bloqueId:<?php echo $_POST['bloqueId']; ?>});
				}
			})
		});

		$('#areasSort .condArea').click(function(event) {
			var areaId = this.id.split('_')[1];
			popUp('admin/checklistExt/condiciones.php',{eleId:areaId,aplicacion:'area'},function(e){},{});
		});




		
	});
</script>

<ul id="areasSort" class="list-group">
	<?php foreach ($areas as $b): ?>
		<li class="list-group-item areaEle arrastra" id="areaEle_<?php echo $b['id'];?>">
			<div class="row">
				<div class="col-md-3">
					<strong>(<?php echo $b["identificador"]; ?>)</strong>
				</div>

				<div class="col-md-6" id="nomArea_<?php echo $b['id'];?>"><?php echo $b['nombre'] ?></div>
				<div class="col-md-3">
					<i class="glyphicon glyphicon-pencil manita edtArea" 
					id="edtArea_<?php echo $b['id'];?>"></i>&nbsp;&nbsp;
					<i class="glyphicon glyphicon-trash manita delArea rojo" 
					id="delArea_<?php echo $b['id'];?>"></i>&nbsp;&nbsp;
					<i class="glyphicon glyphicon-question-sign manita condArea" 
					id="condArea_<?php echo $b['id'];?>"></i>&nbsp;&nbsp;
					<i class="glyphicon glyphicon-edit manita verArea" 
					id="verArea_<?php echo $b['id'];?>"></i>&nbsp;&nbsp;
				</div>
			</div>
		</li>
	<?php endforeach ?>
  
</ul>
