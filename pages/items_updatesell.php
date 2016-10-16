<?php

checkIfOnline();
if ($_SESSION["id"] != 41802) {
    checkRank("senior_admin");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['itemID'];
    $data = $_POST['itemData'];
    $value = $_POST['newPrice'];
	$mineValue = $_POST['newMiningPrice'];

    $sql  = "UPDATE item ";
    $sql .= "SET sellable = 'yes', item_value = ?, mine_value = ? ";
    $sql .= "WHERE item_id = ? AND item_data = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute(array($value, $mineValue, $id, $data));

    header('Location: /index.php/items');
}
