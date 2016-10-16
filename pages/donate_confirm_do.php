<?php
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
require_once '../include/paypal.php';

header('Content-type: text/plain');

checkIfOnline();

if(empty($_GET['paymentId']) || empty($_GET['token']) || empty($_GET['payerId'])){
  exit;
}
$payerId = $_GET['payerId'];
$payment = Payment::get($_GET['paymentId'], $api);
$execution = new PaymentExecution();
$execution->setPayerId($payerId);
try{
$done = $payment->execute($execution, $api);
}catch(PayPal\Exception\PayPalConnectionException $ex){
	echo $ex->getCode();
	echo $ex->getData();
	die($ex);
}
$information = json_decode($done, true);
$payer = $information['payer'];
$info = $payer['payer_info'];
$transactions = $information['transactions'];
foreach($transactions as $transaction){
  $amount = $transaction['amount'];
}


$sql  = "INSERT INTO donation (player_id, donation_timestamp, donation_amount, donation_paypalid, "
        . "donation_payerid, donation_email, donation_firstname, donation_lastname, donation_message) ";
$sql .= "VALUES (?, unix_timestamp(), ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$id = $_SESSION["paypal_id"];
$donatorStatus = false;
foreach ($transactions as $transaction) {
    $amount = $transaction["amount"];
    if ($amount >= 5) {
        $donatorStatus = true;
    }
    $stmt->execute(array($_SESSION["id"], $amount["total"], $_GET['paymentId'], $payerId, $info["email"],
                         $info["first_name"], $info["last_name"], $_SESSION["donation_message"]));
}
if (($_SESSION["rank"] == "unverified" ||
     $_SESSION["rank"] == "tourist" ||
     $_SESSION["rank"] == "settler" ||
     $_SESSION["rank"] == "resident") && $donatorStatus) {

    $sql = "UPDATE player SET player_rank = ? WHERE player_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute(array("donator", $_SESSION["id"]));

    $_SESSION["rank"] = "donator";
}

header('Location: /index.php/donate/finish');
exit;
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

if (!array_key_exists("payerId", $_GET)) {
    exit;
}

$payerId = $_GET["payerId"];

if (!array_key_exists("paypal_id", $_SESSION)) {
    exit;
}

$id = $_SESSION["paypal_id"];

$result = executePayment($paypalEndpoint, $accessToken, $id, $payerId);

print_r($result);

$payer = $result["payer"];
$info = $payer["payer_info"];
$transactions = $result["transactions"];

$sql  = "INSERT INTO donation (player_id, donation_timestamp, donation_amount, donation_paypalid, "
        . "donation_payerid, donation_email, donation_firstname, donation_lastname, donation_message) ";
$sql .= "VALUES (?, unix_timestamp(), ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$donatorStatus = false;
foreach ($transactions as $transaction) {
    $amount = $transaction["amount"];
    if ($amount >= 5) {
        $donatorStatus = true;
    }
    $stmt->execute(array($_SESSION["id"], $amount["total"], $id, $payerId, $info["email"],
                         $info["first_name"], $info["last_name"], $_SESSION["donation_message"]));
}

if (($_SESSION["rank"] == "unverified" ||
     $_SESSION["rank"] == "tourist" ||
     $_SESSION["rank"] == "settler" ||
     $_SESSION["rank"] == "resident") && $donatorStatus) {

    $sql = "UPDATE player SET player_rank = ? WHERE player_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute(array("donator", $_SESSION["id"]));

    $_SESSION["rank"] = "donator";
}

header('Location: /index.php/donate/finish');
