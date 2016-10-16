<?php
$redirect = "https://discordapp.com/invite/0uSbLbQIFDRpUYvW";
header("HTTP/1.1 301 Moved Permanently");
header("Location: $redirect");
exit;
$_SESSION["ischat"] = true;
require_once '../include/tregmine_api.php';
unset($_SESSION["ischat"]);
checkIfOnline();
checkRank("donator", "junior_admin", "senior_admin", "coder", "guardian", "builder");

$token = tregmine_auth($tregmineApiKey, $_SESSION["id"]);
if (!$token["found"]) {
    header('Location: /index.php');
}

$context = array("token" => $token["token"]);

$styles = array();
$scripts = array("/js/chat.js");
render('chat.phtml', 'Chat', $context, $styles, $scripts);
