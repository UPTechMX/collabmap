<?php
// print2($_POST);
@date_default_timezone_set('America/Mexico_City');
$hoy = getdate();
$fechaHoy = $hoy['year'].'-'.$hoy['mon'].'-'.$hoy['mday'];
require_once raiz().'questionnaires/seguridad/'.'acceso.php';
if ($_SESSION['CM']['consultations']['validated'] != 1){
  unset($_SESSION['CM']['consultations']['activo']);
  include raiz().'questionnaires/seguridad/'.'login.php';
  exit;
}
?>

