<?php

require_once '../include/functions.php';

checkIfOnline();

checkRank("guardian", "coder", "builder", "junior_admin", "senior_admin");

$x = $_GET["x"];
$y = $_GET["y"];
$z = $_GET["z"];
$world = array_key_exists("world", $_GET) ? $_GET["world"] : "world";

$sql  = "SELECT * FROM inventory ";
$sql .= "WHERE inventory_x = ? "
      . "AND inventory_y = ? "
      . "AND inventory_z = ? "
      . "AND inventory_world = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($x, $y, $z, $world));

$inventory = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$inventory) {
    echo "no such inventory\n";
    exit;
}

$sql  = "SELECT * FROM inventory_accesslog ";
$sql .= "INNER JOIN player USING (player_id) ";
$sql .= "WHERE inventory_id = ? ";
$sql .= "ORDER BY accesslog_timestamp DESC ";

$stmt = $conn->prepare($sql);
$stmt->execute(array($inventory["inventory_id"]));

$accessLog = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql  = "SELECT * FROM inventory_changelog ";
$sql .= "INNER JOIN player USING (player_id) ";
$sql .= "WHERE inventory_id = ? ";
$sql .= "ORDER BY changelog_timestamp DESC ";

$stmt = $conn->prepare($sql);
$stmt->execute(array($inventory["inventory_id"]));

$changeLog = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql  = "SELECT * FROM inventory_item ";
$sql .= "WHERE inventory_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($inventory["inventory_id"]));

$inventoryItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$slots = array();
foreach ($inventoryItems as $item) {
    $slots[$item["item_slot"]] = $item;
}

$title = sprintf("Inventory: %d, %d, %d", $x, $y, $z);

require_once '../include/items.php';

$context = array();
$context["slots"] = $slots;
$context["items"] = $items;
$context["accessLog"] = $accessLog;
$context["changeLog"] = $changeLog;

$styles = array();
$scripts = array();
render('inventory.phtml', $title, $context, $styles, $scripts);
