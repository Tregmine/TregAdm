<?php

checkIfOnline();
checkRank("junior_admin", "senior_admin", "guardian", "coder", "builder");

$start = array_key_exists("start", $_GET) ? strtotime($_GET["start"]) : strtotime("-1 day");
$end = array_key_exists("end", $_GET) ? strtotime($_GET["end"]) : null;

$params = array();

$sql  = "SELECT player_id, player_name, orelog_world, "
      . "item_name, count(*) c from player_orelog ";
$sql .= "INNER JOIN player USING (player_id) ";
$sql .= "LEFT JOIN item on item_id = orelog_material ";
$sql .= "WHERE orelog_material != 153 ";
if ($start) {
    $sql .= "AND orelog_timestamp > ? ";
    $params[] = $start;
}
if ($end) {
    $sql .= "AND orelog_timestamp < ? ";
    $params[] = $end;
}
$sql .= "GROUP BY player_id, orelog_world, orelog_material ";
$sql .= "HAVING c > 10 ";
$sql .= "ORDER BY c DESC, player_name";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$log = $stmt->fetchAll(PDO::FETCH_ASSOC);

$context = array();
$context["start"] = $start ? date("Y-m-d", $start) : "";
$context["end"] = $end ? date("Y-m-d", $end) : "";
$context["log"] = $log;

$styles = array();
$scripts = array();
render('orelog.phtml', 'Ore Log', $context, $styles, $scripts);
