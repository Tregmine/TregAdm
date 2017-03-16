<?php

$orders = array("id", "name", "value");
$dir = array("id" => "ASC", "name" => "ASC", "value" => "DESC");
$order = "value";
if (array_key_exists("order", $_GET)) {
    $order = $_GET["order"];
}
if (!in_array($order, $orders)) {
    $order = "value";
}

$sql  = "SELECT item_id, item_data, item_name, item_value, mine_value, id ";
$sql .= "FROM item ";
$sql .= "WHERE sellable = 'YES' ";
$sql .= sprintf("ORDER BY item_%s %s", $order, $dir[$order]);

$stmt = $conn->prepare($sql);
$stmt->execute();

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$context = array("items" => $items);
$styles = array();
$scripts = array("/js/item_autocomplete.js");
render('items.phtml', 'Tregmine Items', $context, $styles, $scripts);
