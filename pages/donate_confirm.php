<?php
checkIfOnline();
if (!array_key_exists("PayerID", $_GET)) {
    exit;
}
$payerId = $_GET["PayerID"];
$context = array("payerId" => $payerId);
$styles = array();
$scripts = array();
render('donate_confirm.phtml', 'Confirm your Donation', $context, $styles, $scripts);