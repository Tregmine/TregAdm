<?php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
require __DIR__ . '/vendor/autoload.php';
include 'init.php';
//api
$api = new ApiContext(
new OAuthTokenCredential(
'paypalClient',
'paypalSecret'
)
);
$api->setConfig([
  'mode' => 'live',
  'http.ConnectionTimeOut' => 45,
  'log.LogEnabled' => false,
  'log.FileName' => '',
  'log.LogLevel' => 'FINE',
  'validation.level' => 'log'
]);
//Connection is $conn

?>
