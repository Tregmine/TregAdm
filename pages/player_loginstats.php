<?php

require_once '../include/functions.php';

$start = 0; //strtotime(date("Y-m-d", time() - 30*86400));
$end = strtotime(date("Y-m-d"));

$sql  = "SELECT date(from_unixtime(login_timestamp)) d, count(login_id) c, "
      . "count(distinct player_id) uc, max(login_onlineplayers) op FROM player_login ";
$sql .= "WHERE login_timestamp BETWEEN ? AND ? "
      . "AND login_action = 'login' ";
$sql .= "GROUP BY d ORDER BY d";

$stmt = $conn->prepare($sql);
$stmt->execute(array($start, $end));

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = array();
$data["logins"] = array();

$data["logins"][] = array("Date", "Logins", "Trend");
$trendLine = trend($result, "c");
foreach ($result as $i => $row) {
    $data["logins"][] = array($row["d"], intval($row["c"]), $trendLine[$i]);
}

$data["unique"][] = array("Date", "Unique Players", "Trend");
$trendLine = trend($result, "uc");
foreach ($result as $i => $row) {
    $data["unique"][] = array($row["d"], intval($row["uc"]), $trendLine[$i]);
}

$data["online"][] = array("Date", "Max Online Players");
foreach ($result as $row) {
    if ($row["op"] == 0) {
        continue;
    }
    $data["online"][] = array($row["d"], intval($row["op"])+1); // the online stats is always off by one
}

echo json_encode($data);

