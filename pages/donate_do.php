<?php

require_once '../include/paypal.php';

header('Content-type: text/plain');

checkIfOnline();

$amount = $_GET["amount"];
$message = $_GET["message"];

$accessToken = null;
if (!array_key_exists("paypal_access_token", $_SESSION)) {
    $accessToken = getAccessToken($paypalEndpoint, $paypalClientId, $paypalSecret);
    if (!$accessToken) {
        header('Location: /index.php/donate');
        exit;
    }
} else {
    $accessToken = $_SESSION["paypal_access_token"];
}

$payment = createPayment($paypalEndpoint, $accessToken, $amount);
if (!$payment) {
    header('Location: /index.php/donate');
    exit;
}

if (!array_key_exists("links", $payment)) {
    header('Location: /index.php/donate');
    exit;
}

$_SESSION["paypal_id"] = $payment["id"];
$_SESSION["donation_message"] = $message;

$links = $payment["links"];
$approval = null;
foreach ($links as $link) {
    if ($link["rel"] == "approval_url") {
        $approval = $link["href"];
        break;
    }
}

if (!$approval) {
    header('Location: /index.php/donate');
    exit;
}

header('Location: ' . $approval);
