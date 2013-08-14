<?php

checkIfOnline();

$sql  = "SELECT count(login_id) c FROM player_login ";
$sql .= "WHERE login_action = 'login' ";

$stmt = $conn->prepare($sql);
$stmt->execute(array());

$total = $stmt->fetchColumn(0);

$sql  = "SELECT hour(from_unixtime(login_timestamp)) h, count(login_id) c "
      . "FROM player_login ";
$sql .= "WHERE login_action = 'login' ";
$sql .= "GROUP BY h ORDER BY h";

$stmt = $conn->prepare($sql);
$stmt->execute(array());

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = array();
$data[] = array("Hour", "Logins (% of total)");
foreach ($result as $row) {
    $data[] = array(sprintf("%02d:00", $row["h"]), round($row["c"] * 100 / $total, 2));
}

echo json_encode($data);

