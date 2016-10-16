<?php

$sql  = "SELECT warp_id, warp_name, count(*) c, count(distinct player_id) uc, hidden FROM warp_log ";
$sql .= "INNER JOIN warp USING (warp_id) ";
$sql .= "GROUP BY warp_id ";
$sql .= "ORDER BY warp_name ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$warps = $stmt->fetchAll(PDO::FETCH_ASSOC);

$context = array("warps" => $warps);

$styles = array();
$scripts = array();
render('stats_warps.phtml', 'All warps', $context, $styles, $scripts);

