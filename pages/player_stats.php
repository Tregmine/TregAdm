<?php

checkIfOnline();

if (!array_key_exists("id", $_GET)) {
    exit;
}

if ($_GET["id"] != $_SESSION["id"]) {
    checkRank("guardian", "coder", "builder", "junior_admin", "senior_admin");
}

$sql = "SELECT * FROM player WHERE player_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_GET["id"]));

$player = $stmt->fetch();

$stmt->closeCursor();

$sql = "SELECT * FROM player_property WHERE player_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($player["player_id"]));

$properties = array();
$properties["playtime"] = 0;

$result = $stmt->fetchAll();
foreach ($result as $row) {
    $properties[$row["property_key"]] = $row["property_value"];
}

$stmt->closeCursor();

$sql  = "SELECT count(*) c FROM player_login ";
$sql .= "WHERE player_id = ? ";
$sql .= "AND login_action = 'login' ";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_GET["id"]));

$loginCount = $stmt->fetch();

$stmt->closeCursor();

$sql  = "SELECT * FROM player_login ";
$sql .= "WHERE player_id = ? ";
$sql .= "ORDER BY login_timestamp DESC ";
if (!array_key_exists("all_logins", $_GET)) {
    $sql .= "LIMIT 20";
}

$stmt = $conn->prepare($sql);
$stmt->execute(array($_GET["id"]));

$logins = $stmt->fetchAll();

$sqlTransactions  = "SELECT * FROM player_transaction " 
                  . "INNER JOIN player ON player_id = recipient_id "
                  . "WHERE sender_id = ? ";
$sqlTransactions .= "UNION SELECT * FROM player_transaction "
                  . "INNER JOIN player ON player_id = sender_id "
                  . "WHERE recipient_id = ? ";
$sqlTransactions .= "ORDER BY transaction_timestamp DESC ";
if (!array_key_exists("all_transactions", $_GET)) {
    $sqlTransactions .= "LIMIT 20";
}

$stmt = $conn->prepare($sqlTransactions);
$stmt->execute(array($_GET["id"], $_GET["id"]));

$transactions = $stmt->fetchAll();
$stmt->closeCursor();

if (array_key_exists("inv", $_GET)) {
    $playerinventory_name = $_GET["inv"];
} else {
    $playerinventory_name = "survival";
}

// Main Inventory
$sql  = "SELECT * FROM playerinventory_item ";
$sql .= "INNER JOIN playerinventory USING (playerinventory_id) ";
$sql .= "WHERE playerinventory_type = 'main' AND playerinventory_name = ? AND player_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($playerinventory_name, $player["player_id"]));

$inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$slots = array();
foreach ($inventory as $item) {
    $slots[$item["item_slot"]] = $item;
}

// Armour Inventory
$sql  = "SELECT * FROM playerinventory_item ";
$sql .= "INNER JOIN playerinventory USING (playerinventory_id) ";
$sql .= "WHERE playerinventory_type = 'armour' AND playerinventory_name = ? AND player_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($playerinventory_name, $player["player_id"]));

$armour = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$armourslots = array();
foreach ($armour as $item) {
    $armourslots[$item["item_slot"]] = $item;
}

// Ender Inventory
$sql  = "SELECT * FROM playerinventory_item ";
$sql .= "INNER JOIN playerinventory USING (playerinventory_id) ";
$sql .= "WHERE playerinventory_type = 'ender' AND playerinventory_name = ? AND player_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($playerinventory_name, $player["player_id"]));

$ender = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$enderslots = array();
foreach ($ender as $item) {
    $enderslots[$item["item_slot"]] = $item;
}

// Items Database
$sql  = "SELECT * FROM item";

$stmt = $conn->prepare($sql);
$stmt->execute();

$itemDB = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$itemDBArray = array();
foreach ($itemDB as $item) {
    if (!array_key_exists($item["item_id"], $itemDBArray)) {
        $itemDBArray[$item["item_id"]] = array();
    }
    $itemDBArray[$item["item_id"]][$item["item_data"]] = $item;
}

// Aliases

$sql  = "SELECT DISTINCT login_ip FROM player_login WHERE player_id = ? AND NOT login_ip IS NULL";
$stmt = $conn->prepare($sql);
$stmt->execute(array($player["player_id"]));
$ips = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$cleanedIps = array_map(function($a) { return "'".$a["login_ip"]."'"; }, $ips);

$aliases = array();
if (count($cleanedIps)) {
    $sql  = "SELECT DISTINCT player.player_name FROM player ";
    $sql .= "INNER JOIN player_login USING (player_id) ";
    $sql .= sprintf("WHERE login_ip IN (%s)", implode(", ", $cleanedIps));

    $stmt = $conn->prepare($sql);
    $stmt->execute(array());
    $aliases = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
}

$title = "Player Stats: " . $player["player_name"];

require_once '../include/items.php';

$context = array();
$context["player"] = $player;
$context["transactions"] = $transactions;
$context["inventory"] = $inventory;
$context["slots"] = $slots;
$context["armour"] = $armour;
$context["armourslots"] = $armourslots;
$context["ender"] = $ender;
$context["enderslots"] = $enderslots;
$context["itemDB"] = $itemDB;
$context["itemDBArray"] = $itemDBArray;
$context["invname"] = $playerinventory_name;
$context["logins"] = $logins;
$context["aliases"] = $aliases;
$context["items"] = $items;
$context["properties"] = $properties;
$context["loginCount"] = $loginCount["c"];

$styles = array("/css/inventory.css");
$scripts = array();
render('player_stats.phtml', $title, $context, $styles, $scripts);
