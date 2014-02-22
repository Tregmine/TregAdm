<?php

checkIfOnline();
checkRank("senior_admin");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['itemID'];
    $data = $_POST['itemData'];
    $name = $_POST['itemFullName'];
    $sign = $_POST['itemSignName'];
    $link = $_POST['itemLink'];

    if(isset($_POST['itemEnch'])) {
        $ench = "yes";
    } else {
        $ench = "no";
    }

    if(isset($_POST['itemRound'])) {
        $round = "yes";
    } else {
        $round = "no";
    }

    $sql  = "INSERT INTO item ";
    $sql .= "(item_id, item_data, item_name, item_sign, enchantable, link, round) ";
    $sql .= "VALUES (?,?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->execute(array($id, $data, $name, $sign, $ench, $link, $round));

    header('Location: /index.php/admin/items');
}
