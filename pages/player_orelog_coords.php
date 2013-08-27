<?php

checkIfOnline();
checkRank("guardian", "coder", "builder", "junior_admin", "senior_admin");

if (!array_key_exists("id", $_GET)) {
    exit;
}

$sql  = "SELECT from_unixtime(orelog_timestamp) timestamp, "
      . "orelog_x x, orelog_y y, orelog_z z, item_id id, "
      . "item_name name FROM player_orelog ";
$sql .= "LEFT JOIN item ON item_id = orelog_material ";
$sql .= "WHERE player_id = ? ";
$sql .= "AND orelog_timestamp BETWEEN ? AND ? ";
$sql .= "AND orelog_material != 153 ";
$sql .= "ORDER BY orelog_timestamp";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_GET["id"], $_GET["timestamp"], $_GET["timestamp"] + 60*15));

$log = $stmt->fetchAll();

echo json_encode($log);
