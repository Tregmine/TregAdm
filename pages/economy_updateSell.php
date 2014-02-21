<?php

checkIfOnline();
checkRank("junior_admin", "senior_admin");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['itemID'];
    $data = $_POST['itemData'];
    $value = $_POST['newPrice'];

    $sql  = "UPDATE item ";
    $sql .= "SET sellable = 'yes', item_value = ? ";
    $sql .= "WHERE item_id = ? AND item_data = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute(array($value, $id, $data));

    header('Location: /index.php/economy');
}