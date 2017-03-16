<?php

$sql  = "SELECT player_id, player_name, player_rank, player_flags, "
      . "CAST(property_value as unsigned) player_playtime "
      . "FROM player_property ";
$sql .= "INNER JOIN player using (player_id) ";
$sql .= "WHERE property_key = 'playtime' ";
$sql .= "ORDER BY player_playtime DESC";
if($_SESSION["mobile"]){
	$sql .= " LIMIT 50";
}else{
	$sql .= " LIMIT 250";
}

$stmt = $conn->prepare($sql);
$stmt->execute();

$players = $stmt->fetchAll(PDO::FETCH_ASSOC);

$context = array("players" => $players);

$styles = array();
$scripts = array();
render('stats_playtime.phtml', 'Playtime', $context, $styles, $scripts);

