<?php

checkIfOnline();

$hits = array();
$q = "";
if (array_key_exists("q", $_GET)) {
    $q = $_GET["q"];
    $stmt = $conn->prepare("SELECT * FROM player WHERE player_name LIKE ? ORDER BY player_name LIMIT 20");
    $stmt->execute(array($q));

    $hits = $stmt->fetchAll();
}

$players_data = file_get_contents("http://mc.tregmine.info:9192/playerlist");
$players = array();
if ($players_data) {
    $players = json_decode($players_data, true);
}


$context = array();
$context["q"] = $q;
$context["hits"] = $hits;
$context["players"] = $players;


$styles = array();
$scripts = array("/js/player_autocomplete.js");
render('player_search.phtml', 'Player Search', $context, $styles, $scripts);
