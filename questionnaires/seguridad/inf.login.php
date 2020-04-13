<?php
// @include_once raiz().'basepdo.php';
$usuario =$_POST['usuario'];
// print2($_POST);
try{
  $infoPer = $db->prepare("SELECT * FROM usrAdmin WHERE username = ?");
  $infoPer -> execute(array($usuario));
  $datos = $infoPer->fetch(PDO::FETCH_ASSOC);
  // print2($datos);
  $id  = $datos['id'];
  $usr = $datos['username'];
  $pwd = $datos['pwd'];
  $nivel = $datos['nivel'];

  $nombre = htmlspecialchars($datos['nombre']);
  $aPat   = htmlspecialchars($datos['aPat']);
  $aMat   = htmlspecialchars($datos['aMat']);

}catch(PDOException $ex){
  echo $ex->getMessage().' en inf';
}
?>
