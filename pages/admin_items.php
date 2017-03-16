<?php

checkIfOnline();
checkRank("junior_admin", "senior_admin");

$orders = array("id", "name", "sign");
$dir = array("id" => "ASC", "name" => "ASC", "value" => "ASC");
$order = "id";
$page = 1;
$pageSize = 20;

if (array_key_exists("order", $_GET)) {
    $order = $_GET["order"];
}

if (!in_array($order, $orders)) {
    $order = "id";
}

if (array_key_exists("pg", $_GET)) {
    $page = intval($_GET["pg"]) > 0 ? intval($_GET["pg"]) : 1;
}

$start = ($page - 1) * $pageSize;

$sql  = "SELECT * FROM item ";
$sql .= sprintf("ORDER BY item_%s %s ", $order, $dir[$order]);
$sql .= sprintf("LIMIT %d, %d", $start, $pageSize);

$stmt = $conn->prepare($sql);
$stmt->execute();

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$sql = "SELECT count(*) FROM item";
$stmt = $conn->prepare($sql);
$stmt->execute();

$itemCount = $stmt->fetchColumn(0);

$stmt->closeCursor();

$minusOne = $page - 1;
$previousStatus = "";
$previousLink = "";
if ($minusOne <= 0) {
    $previousStatus =  "class=\"disabled\"";
} else {
    $previousLink = "href=\"/index.php/admin/items?pg=" . $minusOne . "\"";
}

$last = round(($itemCount / $pageSize)) + 1;

$plusOne = $page + 1;
$nextStatus = "";
$nextLink = "";
if ($plusOne >= $last) {
    $nextStatus = "class=\"disabled\"";
} else {
    $nextLink = "href=\"/index.php/admin/items?pg=" . $plusOne . "\"";
}

$context = array();
$context["items"] = $items;

// Pagination
$context["previous"] = $previousStatus;
$context["previousLink"] = $previousLink;
$context["current"] = $page;
$context["nextLink"] = $nextLink;
$context["next"] = $nextStatus;
$context["last"] = $last;

$styles = array();
$scripts = array();
render('admin_items.phtml', 'Tregmine Items', $context, $styles, $scripts);
