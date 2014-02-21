<?php

checkIfOnline();
checkRank("junior_admin", "senior_admin");

if (!array_key_exists("id", $_GET)) {
    exit;
}
if (!array_key_exists("data", $_GET)) {
    exit;
}

$item_id = $_GET['id'];
$item_data = $_GET['data'];

$sql  = "UPDATE item ";
$sql .= "SET sellable = 'no', item_value = '0' ";
$sql .= "WHERE item_id = ? AND item_data = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($item_id, $item_data));

header('Location: /index.php/economy');