<?php

checkIfOnline();

if (!array_key_exists("q", $_GET)) {
    exit;
}

$sql  = "SELECT zone_name FROM zone ";
$sql .= "WHERE zone_name LIKE ? ";
if (!hasRank("junior_admin", "senior_admin")) {
    $sql .= "AND zone_publicprofile = '1' ";
}
$sql .= "ORDER BY zone_name ";
$sql .= "LIMIT 20";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_GET["q"] . "%"));

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$zones = array();
foreach ($result as $zone) {
    $zones[] = $zone["zone_name"];
}

echo json_encode($zones);
