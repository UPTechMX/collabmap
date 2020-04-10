<?php

$host 		= 'localhost';
$DBuser		= 'pba';
$DBpassw	= '123';
// $DB	        = 'notland_IU2';
$DB	        = 'collabmap';

// $host 		= 'notland.mx';
// $DBuser		= 'notland_pba';
// $DBpassw	= '_Y4Uv{9}Gct~';
// // $DB	        = 'notland_IU2';
// $DB	        = 'notland_islaUrbana';


try {
  $db = new PDO('mysql:host='.$host.';dbname='.$DB, $DBuser, $DBpassw,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch(PDOException $ex) {
  echo $ex->getMessage();
}



?>
