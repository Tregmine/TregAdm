<?php

checkIfOnline();

$sql  = "SELECT date(from_unixtime(login_timestamp)) d, count(login_id) c, "
      . "count(distinct player_id) uc FROM player_login ";
$sql .= "WHERE login_timestamp > unix_timestamp() - 30*86400 ";
$sql .= "GROUP BY d ORDER BY d";

$stmt = $conn->prepare($sql);
$stmt->execute(array());

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = array();
$data[] = array("Date", "Logins", "Unique Logins");
foreach ($result as $row) {
    $data[] = array($row["d"], intval($row["c"]), intval($row["uc"]));
}

echo json_encode($data);

