<?php

function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
}


function getTokenForTableField($table, $campo, $tokenLength) {
    global $db;
    $token = getToken($tokenLength);
    while(count($db-> query("SELECT * FROM ".$table." WHERE ".$campo." = '$token'")->fetchAll(PDO::FETCH_ASSOC)) != 0)
        $token = getToken($tokenLength);
    return $token;
}

function getToken($length)
{
    $token = "";
    // sin 0 ni O para evitar confusiones
    $codeAlphabet = "ABCDEFGHIJKLMNPQRSTUVWXYZ";
    // $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "123456789";
    $max = strlen($codeAlphabet); 

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
    }

    return $token;
}


