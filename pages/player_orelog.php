<?php

checkIfOnline();
checkRank("guardian", "coder", "builder", "junior_admin", "senior_admin");

if (!array_key_exists("id", $_GET)) {
    exit;
}

$stmt = $conn->prepare("SELECT * FROM player WHERE player_id = ?");
$stmt->execute(array($_GET["id"]));

$player = $stmt->fetch();

$stmt->closeCursor();

$sql  = "SELECT date(from_unixtime(orelog_timestamp)) date, "
      . "hour(from_unixtime(orelog_timestamp)) hour, "
      . "minute(from_unixtime(orelog_timestamp)) - "
      . "(minute(from_unixtime(orelog_timestamp)) % 15) min, "
      . "count(if(orelog_material = 14, 1, null)) gold, "
      . "count(if(orelog_material = 56, 1, null)) diamond, "
      . "count(if(orelog_material = 21, 1, null)) lapis, "
      . "count(if(orelog_material = 129, 1, null)) emerald "
      . "FROM player_orelog ";
$sql .= "WHERE player_id = ? ";
$sql .= "GROUP BY date, hour, min";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_GET["id"]));

$log = $stmt->fetchAll();

$title = "Ore Log: " . $player["player_name"];

$context = array();
$context["player"] = $player;
$context["log"] = $log;

$styles = array();
$scripts = array("/js/player_orelog.js");
render('player_orelog.phtml', $title, $context, $styles, $scripts);
