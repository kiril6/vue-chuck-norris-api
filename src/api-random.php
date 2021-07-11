<?php
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Origin: *");
header('content-type: application/json; charset=utf-8');

header("Expires: on, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$ch=curl_init();
$url='https://api.chucknorris.io/jokes/random';

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Cache-Control: no-cache, no-store, must-revalidate', 'Pragma: no-cache', 'Expires: 0'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$resp=curl_exec($ch);

if($e= curl_error($ch)) {
    echo $e;
}else{
    $decoded = json_decode($resp, true);
    $encoded = json_encode($decoded, true);

    print_r($encoded);
}

curl_close($ch);

?>