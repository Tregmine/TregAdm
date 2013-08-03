<?php

checkIfOnline();

$key = "F67R4LNVSUG8SO8TQ07DXHDQC34NAU4I";
$host = "localhost:9192";
$path = "/playerlist";
$query = "";

$signingKey = $path;
$url = "http://" . $host . $path;
if ($query) {
    $signingKey .= "?" . $query;
    $url .= "?" . $query;
}

$signingKey = base64_encode(hash_hmac("sha1", $signingKey, $key, true));

$headers = array("Authorization: " . $signingKey);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($curl);

$players = array();
if ($data) {
    $players = json_decode($data, true);
}

$context = array();
$context["players"] = $players;

$styles = array();
$scripts = array("/js/start.js");
render('start.phtml', 'Welcome to Tregmine!', $context, $styles, $scripts);
