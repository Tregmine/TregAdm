<?php

checkIfOnline();
checkRank("guardian", "coder", "builder", "junior_admin", "senior_admin");

$sql  = "SELECT date(from_unixtime(orelog_timestamp)) timestamp, "
      . "count(if(orelog_material = 14, 1, null)) gold, "
      . "count(if(orelog_material = 56, 1, null)) diamond, "
      . "count(if(orelog_material = 21, 1, null)) lapis, "
      . "count(if(orelog_material = 129, 1, null)) emerald ";
$sql .= "FROM player_orelog GROUP BY timestamp";

$stmt = $conn->prepare($sql);
$stmt->execute(array());

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = array();
$data[] = array("Date", "Gold", "Diamond", "Lapis", "Emerald");
foreach ($result as $row) {
    $data[] = array($row["timestamp"], 
                    intval($row["gold"]), 
                    intval($row["diamond"]), 
                    intval($row["lapis"]), 
                    intval($row["emerald"]));
}

echo json_encode($data);

