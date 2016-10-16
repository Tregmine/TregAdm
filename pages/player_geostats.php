<?php

$sql  = "SELECT login_country, count(*) c, count(distinct player_id) uc "
      . "FROM player_login ";
$sql .= "WHERE NOT login_country IS NULL and login_action = 'login' ";
$sql .= "GROUP BY login_country";

$stmt = $conn->prepare($sql);
$stmt->execute(array());

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = array();
$data[] = array("Country", "Unique Logins", "Logins");
foreach ($result as $row) {
    $data[] = array($row["login_country"], intval($row["uc"]), intval($row["c"]));
}

echo json_encode($data);

