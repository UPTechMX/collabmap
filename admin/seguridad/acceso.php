<?php
include raiz().'admin/seguridad/'.'inf.login.php';
@session_start();

if($_POST['usuario'] != "") {
  $v = verifPWD($_POST['usuario'],$_POST['pwd'],'admin');
  // print2($v);
 if($v['verif'] && $v['nivel']>=5) {
  // echo "aaa";
 	
    $_SESSION['CM']['admin']['usrId']  = $v['usrId'];
    $_SESSION['CM']['admin']['nombre']  = $v['nombre'];
    $_SESSION['CM']['admin']['activo'] = ($v['nivel']>=5?1:0);
    $_SESSION['CM']['admin']['nivel'] = $v['nivel'];
    // $_SESSION['CM']['admin']['privs'] = (count($v['priv'])>0?$v['priv'] :array());
  }else{
    unset($_SESSION['CM']['admin']);
  }
  // print2($_SESSION);
}





if (isset($_REQUEST['logout'])) {
  unset($_SESSION['CM']['admin']);
  session_destroy();

  
}
?>
