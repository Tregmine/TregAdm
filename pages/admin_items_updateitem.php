<?php

checkIfOnline();
checkRank("junior_admin", "senior_admin");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['itemID'];
    $data = $_POST['itemData'];
    $name = $_POST['itemFullName'];
    $sign = $_POST['itemSignName'];
    $link = $_POST['itemLink'];
    $page = $_POST['itemPage'];

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

    $sql  = "UPDATE item ";
    $sql .= "SET item_name = ?, item_sign = ?, enchantable = ?, link = ?, round = ? ";
    $sql .= "WHERE item_id = ? AND item_data = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute(array($name, $sign, $ench, $link, $round, $id, $data));

    header('Location: /index.php/admin/items?pg='.$page.'');
}
