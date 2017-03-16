<?php

checkIfOnline();
if ($_SESSION["id"] != 41802) {
    checkRank("senior_admin");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemName = $_POST['itemName_search'];
    $itemID = $_POST['itemID'];
    $itemData = $_POST['itemData'];
    $itemPrice = $_POST['itemPrice'];
	$minePrice = $_POST['mineItemPrice'];

    // Chose the ID/Data route
    if ($itemID != null) {
        $sql  = "UPDATE item ";
        $sql .= "SET sellable = 'yes', item_value = ?, mine_value = ? ";
        $sql .= "WHERE item_id = ? AND item_data = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute(array($itemPrice, $minePrice, $itemID, $itemData));

        header('Location: /index.php/items');
        exit;
    } else {
        $sql  = "UPDATE item ";
        $sql .= "SET sellable = 'yes', item_value = ? ";
        $sql .= "WHERE item_name = ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute(array($itemPrice, $itemName));

        header('Location: /index.php/items');
        exit;
    }
}
