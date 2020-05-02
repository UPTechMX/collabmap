<?php

$a = array();

$a['aa'] = 'aaa';
$a['bb'] = 'bbb';
$a['cc'] = 'ccc';
$a['dd'] = 'ddd';
$a['ee'] = 'eee';
$a['ff'] = 'fff';
$a['gg'] = 'ggg';

echo 'END: '.end($a).'<br/>';

foreach ($a as $k => $v) {
	prev($a);
	echo $k." - ".key($a)."<br/>";// next($a);

}


?>