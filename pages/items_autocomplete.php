<?php

checkIfOnline();

if (!array_key_exists("q", $_GET)) {
    exit;
}

$sql  = "SELECT item_name FROM item ";
$sql .= "WHERE item_name LIKE ? ";
$sql .= "ORDER BY item_id ";
$sql .= "LIMIT 20";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_GET["q"] . "%"));

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$items = array();
foreach ($result as $item) {
    $items[] = $item["item_name"];
}

echo json_encode($items);

