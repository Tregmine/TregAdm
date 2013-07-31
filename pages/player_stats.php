<?php

checkIfOnline();

if (!array_key_exists("id", $_GET)) {
    exit;
}

$stmt = $conn->prepare("SELECT * FROM player WHERE player_id = ?");
$stmt->execute(array($_GET["id"]));

$player = $stmt->fetch();

$stmt->closeCursor();

$stmt = $conn->prepare("SELECT * FROM player_login WHERE player_id = ? ORDER BY login_timestamp");
$stmt->execute(array($_GET["id"]));

$logins = $stmt->fetchAll();

$sqlTransactions  = "SELECT * FROM player_transaction " 
                  . "INNER JOIN player ON player_id = recipient_id "
                  . "WHERE sender_id = ? ";
$sqlTransactions .= "UNION SELECT * FROM player_transaction "
                  . "INNER JOIN player ON player_id = sender_id "
                  . "WHERE recipient_id = ? ";
$sqlTransactions .= "ORDER BY transaction_timestamp";

$stmt = $conn->prepare($sqlTransactions);
$stmt->execute(array($_GET["id"], $_GET["id"]));

$transactions = $stmt->fetchAll();
$stmt->closeCursor();

$sql  = "SELECT * FROM inventory_item ";
$sql .= "INNER JOIN inventory USING (inventory_id) ";
$sql .= "WHERE inventory_type = 'player' AND player_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($player["player_id"]));

$inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$slots = array();
foreach ($inventory as $item) {
    $slots[$item["item_slot"]] = $item;
}

// Aliases

$stmt = $conn->prepare("SELECT property_value FROM player_property WHERE property_key = 'ip' AND player_id = ?");
$stmt->execute(array($_GET["id"]));
$playerIP = $stmt->fetchColumn();
$stmt->closeCursor();

$stmt = $conn->prepare("SELECT player.player_name FROM player INNER JOIN player_property ON player_property.player_id = player.player_id WHERE player_property.property_key = 'ip' AND player_property.property_value = ?");
$stmt->execute(array($playerIP));
$aliases = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$title = "Player Stats: " . $player["player_name"];

require_once '../include/items.php';

$context = array();
$context["player"] = $player;
$context["transactions"] = $transactions;
$context["inventory"] = $inventory;
$context["slots"] = $slots;
$context["logins"] = $logins;
$context["aliases"] = $aliases;
$context["items"] = $items;

$styles = array();
$scripts = array();
render('player_stats.phtml', $title, $context, $styles, $scripts);
