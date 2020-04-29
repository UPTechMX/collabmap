<?php 

	if(!function_exists('raiz')){
		function root(){
			if (session_status() == PHP_SESSION_NONE) {
			    @session_start();
			}

			if(empty($_SESSION['CM']['raiz'])){
				$dir = getcwd();
				$dirE = explode('/',$dir);
				$ciclos = count($dirE);
				for($i = $ciclos; $i > 0; $i--){
					$dN = '';
					for($j = 0; $j<$i;$j++){
						$dN .= $dirE[$j].'/';
					}
					if(file_exists($dN.'/raiz')){
						$_SESSION['CM']['raiz'] = $dN;
						return $dN;
					}else{
						if($i == 1){
							exit("Archivo 'raiz' no encontrado, inserta un archivo con nombre 'raiz' (sin extensiones) en el direcorio raiz.");
						}
					}
				}
			}else{
				return $_SESSION['CM']['raiz'];
			}
		}
		include_once root().'lib/j/j.func.php';
	}

	$stmt = $db->prepare("SELECT * FROM PublicConsultations WHERE code = ?");
	$stmt -> execute([$_REQUEST['pc']]);

	$pcInfo = $stmt ->fetchAll(PDO::FETCH_ASSOC)[0];

 ?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		<?php if ($pcInfo['emailReq'] == 1){ ?>

			$('#enter').click(function(event) {
				
				var code = '<?php echo $_REQUEST['pc']; ?>';
				var email = $('#email').val();
				if(email != '' && validateEmail(email)){
					var rj = jsonF('publicConsultations/json/json.php',{email:email,acc:1,code:code});
					var r = $.parseJSON(rj);
					if(r.ok == 1){
						var vId = r.vId;
						popUpCuest('publicConsultations/answer.php',{vId:vId},function(){})
						setTimeout(function(){
							$('#contentCuest').load(rz+'checklist/cuestionario.php',{vId:vId},function(){});
						},500);

					}else if(r.ok == 2){
						alertar('<?php echo TR('oneAnsUser'); ?>');
					}
					// console.log(rj);
				}else{
					alertar('<?php echo TR("invalidEmail"); ?>')
				}


			});
		<?php }else{ ?>

			$('#enter').click(function(event) {
				
				var code = '<?php echo $_REQUEST['pc']; ?>';
				var rj = jsonF('publicConsultations/json/json.php',{email:'--',acc:1,code:code});
				var r = $.parseJSON(rj);
				if(r.ok == 1){
					var vId = r.vId;
					popUpCuest('publicConsultations/answer.php',{vId:vId},function(){})
					setTimeout(function(){
						$('#contentCuest').load(rz+'checklist/cuestionario.php',{vId:vId},function(){});
					},500);

				}


			});

		<?php } ?>
	});
</script>

 <div class="container">
   <div class="row justify-content-md-center">
    
	<div class="col-4">
		<div align="center" style="border:solid #00aeef;padding:20px 10px 20px 10px;
			background:#fff;width:100%;border-radius:10px;color:black;">
			<?php if ($pcInfo['emailReq'] == 1){ ?>

				<table>
					<tr>
						<td><?php echo TR('email'); ?>: </td>
						<td>&nbsp;&nbsp;</td>
						<td><input type="text" name="email" id="email" class="form-control" style="border-radius:0px;" /></td>
					</tr>
				</table>
				<br/>
				<br/>
			<?php } ?>

			<span id="enter" class="btn btn-shop"><?php echo TR('startQuestionnaire'); ?></span>
		</div>
     
   </div>
 </div>
