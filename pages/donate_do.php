<?php

use PayPal\Api\Payer;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;

require_once '../include/paypal.php';

header('Content-type: text/plain');

checkIfOnline();

$giveprice = $_GET["amount"];
$message = $_GET["message"];
$payer = new Payer();
$details = new Details();
$amount = new Amount();
$payment = new Payment();
$transaction = new Transaction();
$redirectUrls = new RedirectUrls();
$payer->setPaymentMethod('paypal');
$details->setShipping('0.00')
->setTax('0.00')
->setSubtotal($giveprice);
$amount->setCurrency('USD')
->setTotal($giveprice)
->setDetails($details);
$transaction->setAmount($amount)
->setDescription('Donation to Tregmine :)');
$payment->setIntent('sale')
->setPayer($payer)
->setTransactions([$transaction]);
$redirectUrls->setReturnUrl('https://www.tregmine.com/index.php/donate/confirm')
->setCancelUrl('https://rabil.org/index.php/donate');
$payment->setRedirectUrls($redirectUrls);
try{
  $payment->create($api);

}catch(PPConnectionException $e){
  print_r($e);
  //header('Location: https://rabil.org/index.php/donate');
}
foreach($payment->getLinks() as $link){
  if($link->getRel() == 'approval_url'){
    $redirectUrl = $link->getHref();
  }
}
$_SESSION["donation_message"] = $_GET['message'];
header('Location: '.$redirectUrl);
exit;
$accessToken = null;
if (!array_key_exists("paypal_access_token", $_SESSION)) {
    $accessToken = getAccessToken($paypalEndpoint, $paypalClientId, $paypalSecret);
    if (empty($accessToken)) {
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
