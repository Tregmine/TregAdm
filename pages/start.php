<?php

checkIfOnline();

$players_data = file_get_contents("http://mc.tregmine.info:9192/playerlist");
$players = array();
if ($players_data) {
    $players = json_decode($players_data, true);
}

$context = array();
$context["players"] = $players;

$styles = array();
$scripts = array("/js/start.js");
render('start.phtml', 'Welcome to Tregmine!', $context, $styles, $scripts);
