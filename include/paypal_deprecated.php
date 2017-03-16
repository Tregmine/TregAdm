<?php

function getAccessToken($endpoint, $clientId, $secret) {
    $path = "/v1/oauth2/token";
    $data = "grant_type=client_credentials";

    $url = "https://" . $endpoint . $path;

    $headers = array("Accept: application/json",
                     "Accept-Language: en_US");

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://api.paypal.com/v1/oauth2/token");
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERPWD, $clientId.":".$secret);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    $credentials = curl_exec($curl);
    if(empty($credentials)){
    	return null;
    }

    curl_close($curl);

    $credentials = json_decode($credentials, true);
    return $credentials["access_token"];
}

function createPayment($endpoint, $accessToken, $amount) {
    $amount = sprintf("%0.2d", $amount);

    $request = array();
    $request["intent"] = "sale";
    $request["redirect_urls"] = array(
        "return_url" => "https://rabil.org/index.php/donate/confirm",
        "cancel_url" => "https://rabil.org/index.php/donate");
    $request["payer"] = array("payment_method" => "paypal");
    $request["transactions"] = array();
    $request["transactions"][] =
        array(
            "amount" => array("total" => $amount, "currency" => "USD"),
            "description" => "Tregmine donation"
        );

    $path = "/v1/payments/payment";
    $data = json_encode($request);

    $url = "https://" . $endpoint . $path;
    echo $url;
    $headers = array("Content-Type: application/json",
                     "Content-Length: " . strlen($data),
                     "Authorization: Bearer " . $accessToken);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, $headers);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    $payment = curl_exec($curl);
    
    exit;
    if (empty($payment)) {
        return null;
    }

    curl_close($curl);

    return json_decode($payment, true);
}

function executePayment($endpoint, $accessToken, $id, $payerId) {
    $request = array();
    $request["payer_id"] = $payerId;

    $path = "/v1/payments/payment/" . $id . "/execute/";
    $data = json_encode($request);

    $url = "https://" . $endpoint . $path;

    $headers = array("Content-Type: application/json",
                     "Content-Length: " . strlen($data),
                     "Authorization: Bearer " . $accessToken);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    $confirmation = curl_exec($curl);
    if (!$confirmation) {
        return null;
    }

    curl_close($curl);

    return json_decode($confirmation, true);
}
