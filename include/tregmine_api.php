<?php
$tregmineApiKey = "kys-BITCH-u-420-uGLYY69696969";

function tregmine_online_players() {
    global $tregmineApiKey;
    $host = ":9192";
    $path = "/playerlist";
    $query = "";

    $signingKey = $path;
    $url = "http://" . $host . $path;
    if ($query) {
        $signingKey .= "?" . $query;
        $url .= "?" . $query;
    }

    $signingKey = base64_encode(hash_hmac("sha1", $signingKey, $tregmineApiKey, true));

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

    return $players;
}

function tregmine_kick_player($key, $subjectId, $issuerId, $message) {
	global $tregmineApiKey;
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

    $signingKey = base64_encode(hash_hmac("sha1", $signingKey, $tregmineApiKey, true));
    $headers = array("Authorization: " . $signingKey);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($curl);

    return json_decode($data, true);
}
function tregmine_push_notification($sendTo, $sentFrom, $type){
	global $tregmineApiKey;
	$host = "localhost:9192";
	$path = "/push";
	$query = sprintf("pushTo=%d&pushFrom=%d&type=%s", $sendTo, $sentFrom, $type);
	    $signingKey = $path;
    $url = "http://" . $host . $path;
    if ($query) {
        $signingKey .= "?" . $query;
        $url .= "?" . $query;
    }

    $signingKey = base64_encode(hash_hmac("sha1", $signingKey, $tregmineApiKey, true));
    $headers = array("Authorization: " . $signingKey);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($curl);

    return json_decode($data, true);
}
function tregmine_auth($key, $id) {
	global $tregmineApiKey;
    $host = "localhost:9192";
    $path = "/auth";
    $query = sprintf("id=%d", $id);

    $signingKey = $path;
    $url = "http://" . $host . $path;
    if ($query) {
        $signingKey .= "?" . $query;
        $url .= "?" . $query;
    }

    $signingKey = base64_encode(hash_hmac("sha1", $signingKey, $tregmineApiKey, true));

    $headers = array("Authorization: " . $signingKey);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($curl);

    return json_decode($data, true);
}
