<?php
// print2($_POST);
@date_default_timezone_set('America/Mexico_City');
$hoy = getdate();
$fechaHoy = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'];
require_once raiz().'admin/seguridad/'.'acceso.php';
if ($_SESSION['CM']['admin']['activo'] != 1){
  unset($_SESSION['CM']['admin']['activo']);
  include raiz().'admin/seguridad/'.'login.php';
  exit;
}
?>

