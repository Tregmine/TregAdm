<?php

checkIfOnline();

if (!array_key_exists("id", $_GET)) {
    header('Location: /index.php');
    exit;
}

if ($_SESSION["id"] != $_GET["id"]) {
    checkRank("junior_admin", "senior_admin");
}

$stmt = $conn->prepare("SELECT * FROM player WHERE player_id = ?");
$stmt->execute(array($_GET["id"]));
$player = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$stmt = $conn->prepare("SELECT player.player_name, zone.zone_name, zone.zone_id, zone.zone_world, zone_user.user_perm FROM zone_user INNER JOIN player ON zone_user.user_id = player.player_id INNER JOIN zone ON zone_user.zone_id = zone.zone_id WHERE player.player_id = ? ORDER BY zone.zone_name");
$stmt->execute(array($_GET["id"]));
$zones = $stmt->fetchAll(PDO::FETCH_ASSOC);;
$stmt->closeCursor();

$stmt = $conn->prepare("SELECT zone_lot.lot_name, zone_lot.zone_id, zone_lotuser.lot_id, zone.zone_name, zone.zone_world FROM zone_lot INNER JOIN zone_lotuser ON zone_lot.lot_id = zone_lotuser.lot_id INNER JOIN zone ON zone_lot.zone_id = zone.zone_id WHERE zone_lotuser.user_id = ? ORDER BY zone_lot.lot_name");
$stmt->execute(array($_GET["id"]));
$lots = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$title = "Zones and Lots: " . $player["player_name"];

$context = array();
$context["player"] = $player;
$context["zones"] = $zones;
$context["lots"] = $lots;

$styles = array();
$scripts = array("/js/start.js");
render('player_zones.phtml', $title, $context, $styles, $scripts);
