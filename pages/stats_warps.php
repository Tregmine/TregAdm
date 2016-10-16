<?php

$sql  = "SELECT warp_id, warp_name, count(*) c, count(distinct player_id) uc, hidden FROM warp_log ";
$sql .= "INNER JOIN warp USING (warp_id) ";
$sql .= "WHERE log_timestamp > unix_timestamp() - 7*86400 AND NOT warp_name LIKE '!%' ";
$sql .= "GROUP BY warp_id ";
$sql .= "ORDER BY uc DESC, warp_name ";
if(!$settings["mobile"]){
$sql .= "LIMIT 50";
}else{
$sql .= "LIMIT 10";	
}

$stmt = $conn->prepare($sql);
$stmt->execute();

$warps = $stmt->fetchAll(PDO::FETCH_ASSOC);

$context = array("warps" => $warps);

$styles = array();
$scripts = array();
render('stats_warps.phtml', 'Most popular warps', $context, $styles, $scripts);

