<?php
include raiz().'admin/seguridad/'.'inf.login.php';
@session_start();

if($_POST['usuario'] != "") {
  $v = verifPWD($_POST['usuario'],$_POST['pwd'],'questionnaires');
  // print2($v);
 if($v['verif'] && $v['validated'] == 1) {
    $_SESSION['CM']['questionnaires']['usrId']  = $v['usrId'];
    $_SESSION['CM']['questionnaires']['validated']  = $v['validated'];
    $_SESSION['CM']['questionnaires']['name']  = "$v[name] $v[lastname]";
    // $_SESSION['CM']['questionnaires']['privs'] = (count($v['priv'])>0?$v['priv'] :array());
  }else{
    unset($_SESSION['CM']['questionnaires']);
    $failedLogin = 1;
  }
  // print2($_SESSION);
}





if (isset($_REQUEST['logout'])) {
  unset($_SESSION['CM']['questionnaires']);
  unset($_SESSION['CM']['chk']);
  session_destroy();
}
?>
