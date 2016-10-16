<?php

$sql  = "SELECT hour(from_unixtime(login_timestamp)) h, "
      . "minute(from_unixtime(login_timestamp)) m, "
      . "max(login_onlineplayers) c "
      . "FROM player_login ";
$sql .= "WHERE login_action = 'login' AND login_timestamp > unix_timestamp() - 86400 ";
$sql .= "GROUP BY h, m ORDER BY h, m";

$stmt = $conn->prepare($sql);
$stmt->execute(array());

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stats = array();
foreach ($result as $row) {
    $tstamp = sprintf("%02d:%02d", $row["h"], $row["m"]);
    $stats[$tstamp] = intval($row["c"]);
}

$currentHour = intval(date("H"));
$currentMinute = intval(date("i"));

$data = array();
$data[] = array("Hour", "Players online");

$last = 0;
for ($h = $currentHour; $h < 24; $h++) {
    $minMinute = $h == $currentHour ? $currentMinute : 0;
    for ($m = $minMinute; $m < 60; $m++) {
        $tstamp = sprintf("%02d:%02d", $h, $m);
        $value = $last;
        if (array_key_exists($tstamp, $stats)) {
            $value = $stats[$tstamp];
        }
        $data[] = array($tstamp, $value);
        $last = $value;
    }
}

for ($h = 0; $h <= $currentHour; $h++) {
    $maxMinute = $h == $currentHour ? $currentMinute : 60;
    for ($m = 0; $m < $maxMinute; $m++) {
        $tstamp = sprintf("%02d:%02d", $h, $m);
        $value = $last;
        if (array_key_exists($tstamp, $stats)) {
            $value = $stats[$tstamp];
        }
        $data[] = array($tstamp, $value);
        $last = $value;
    }
}

echo json_encode($data);

