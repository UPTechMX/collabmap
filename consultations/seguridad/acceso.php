<?php

include_once '../../lib/j/j.func.php';
include raiz().'consultations/seguridad/'.'inf.login.php';
@session_start();

// exit('aaa');

if($_POST['usuario'] != "") {
  $v = verifPWD($_POST['usuario'],$_POST['pwd'],'questionnaires');
  // print2($v);
 if($v['verif'] && $v['validated'] == 1) {

    $_SESSION['CM']['consultations']['usrId']  = $v['usrId'];
    $_SESSION['CM']['consultations']['validated']  = $v['validated'];
    $_SESSION['CM']['consultations']['name']  = "$v[name] $v[lastname]";

    $_SESSION['CM']['questionnaires']['usrId']  = $v['usrId'];
    $_SESSION['CM']['questionnaires']['validated']  = $v['validated'];
    $_SESSION['CM']['questionnaires']['name']  = "$v[name] $v[lastname]";
    echo '{"ok":1}';
    // $_SESSION['CM']['consultations']['privs'] = (count($v['priv'])>0?$v['priv'] :array());
  }else{
    unset($_SESSION['CM']['consultations']);
    unset($_SESSION['CM']['questionnaires']);
    echo '{"ok":0}';
  }
  // print2($_SESSION);
}





if (isset($_REQUEST['logout'])) {

  unset($_SESSION['CM']['consultations']);
  unset($_SESSION['CM']['questionnaires']);
  unset($_SESSION['CM']['chk']);
  session_destroy();
}
?>
