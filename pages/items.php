<?php

$sql  = "SELECT item_id, item_data, item_name, item_value ";
$sql .= "FROM item ";
$sql .= "WHERE sellable = 'YES' ";
$sql .= "ORDER BY item_value DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$context = array("items" => $items);
$styles = array();
$scripts = array("/js/item_autocomplete.js");
render('items.phtml', 'Tregmine Items', $context, $styles, $scripts);
