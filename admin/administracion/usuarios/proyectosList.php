<?php  

if($_POST['ajax'] == 1){
	include_once '../../../lib/j/j.func.php';
}
checaAcceso(50);
// print2($_POST);
$usrPry = $db->query("SELECT ap.*, p.nombre
	FROM AdminProyectos ap 
	LEFT JOIN Proyectos p ON p.id = ap.proyectosId
	LEFT JOIN usrAdmin u ON ap.usrAdminId = u.id
	WHERE ap.usrAdminId = $_POST[usrId] AND u.nivel = ap.rol
	ORDER BY p.nombre")->fetchAll(PDO::FETCH_ASSOC);
// print2($usrPry);
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.delPrv').click(function(event) {
			var prvId = this.id.split('_')[1];
			conf('¿Desea eliminar el acceso del usuario a este proyecto?',{},function(){
				// console.log('asas');
				$('#alertas').modal('toggle');
				setTimeout(function(){
					// console.log('asas');
					var rj = jsonF('admin/administracion/usuarios/json/json.php',{prvId:prvId,acc:3});
					// console.log(rj);
					var r = $.parseJSON(rj);
					if(r.ok == 1){
						alerta('success','El usuario ya no tendrá acceso al proyecto');
						$('#proyectosList').load(rz+'admin/administracion/usuarios/proyectosList.php',{ajax:1,usrId:<?php echo $_POST['usrId']; ?>});
					}else{
						alerta('danger','Hubo un error al eliminar los accesos del usuario <br/> Error: U061');
					}

				},500);

			});

		});
	});
</script>
<table class="table">
	<thead>
		<tr>
			<th>Proyecto</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($usrPry as $p){ ?>		
			<tr id="tr_<?php echo "$p[proyectosId]";?>">
				<td><?php echo "$p[cNom] - $p[nombre]"; ?></td>
				<td style="text-align: center;">
					<i class="glyphicon glyphicon-trash manita rojo delPrv" id="delPrv_<?php echo $p['id'];?>"></i>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
