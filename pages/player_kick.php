<?php

checkIfOnline();

if (!array_key_exists("subject", $_GET)) {
    exit;
}
if (!array_key_exists("message", $_GET)) {
    exit;
}

$subjectId = $_GET["subject"];
$issuerId = $_SESSION["id"];
$message = $_GET["message"];

$key = "F67R4LNVSUG8SO8TQ07DXHDQC34NAU4I";
$host = "localhost:9192";
$path = "/playerkick";
$query = sprintf("subjectId=%d&issuerId=%d&message=%s",
        $subjectId,
        $issuerId,
        urlencode($message));

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

echo json_encode($data);

