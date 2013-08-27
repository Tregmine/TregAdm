<?php

require_once '../include/tregmine_api.php';

checkIfOnline();

$context = array();
$context["players"] = tregmine_online_players($tregmineApiKey);

$styles = array();
$scripts = array("/js/start.js");
render('start.phtml', 'Welcome to Tregmine!', $context, $styles, $scripts);
