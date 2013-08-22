<?php

require_once '../include/tregmine_api.php';

checkIfOnline();
checkRank("junior_admin", "senior_admin", "coder", "guardian", "builder");

$token = tregmine_auth($tregmineApiKey, $_SESSION["id"]);
if (!$token["found"]) {
    header('Location: /index.php');
}


$context = array("token" => $token["token"]);

$styles = array();
$scripts = array("/js/chat.js");
render('chat.phtml', 'Chat', $context, $styles, $scripts);
