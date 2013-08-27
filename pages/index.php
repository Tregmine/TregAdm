<?php

require_once '../include/tregmine_api.php';

if (array_key_exists("id", $_SESSION)) {
    header('Location: index.php/start');
    exit;
}

$context = array();
$context["players"] = tregmine_online_players($tregmineApiKey);

$styles = array();
$scripts = array();
render('index.phtml', 'Welcome to Tregmine!', $context, $styles, $scripts);
