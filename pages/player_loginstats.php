<?php

// Simple least square regressions and linear interpolation
function trend($points, $idx) {
    $A = 0; $B = 0; $C = 0; $D = 0;
    $n = count($points);
    foreach ($points as $x => $row) {
        $y = $row[$idx];

        $A += $x * $x;
        $B += $x;
        $C += $x * $y;
        $D += $y;
    }

    $m = ($A * $D - $B * $C) / ($n * $A - $B * $B);
    $k = ($C - $m * $B) / $A;

    $result = array();
    for ($x = 0; $x < $n; $x++) {
        $result[] = round($k * $x + $m);
    }

    return $result;
}

$sql  = "SELECT date(from_unixtime(login_timestamp)) d, count(login_id) c, "
      . "count(distinct player_id) uc, max(login_onlineplayers) op FROM player_login ";
$sql .= "WHERE login_timestamp BETWEEN (unix_timestamp() - 30*86400) AND unix_timestamp(date(now()))-1 "
      . "AND login_action = 'login' ";
$sql .= "GROUP BY d ORDER BY d";

$stmt = $conn->prepare($sql);
$stmt->execute(array());

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
    $data["online"][] = array($row["d"], intval($row["op"])+1); // the online stats is always off by one
}

echo json_encode($data);

