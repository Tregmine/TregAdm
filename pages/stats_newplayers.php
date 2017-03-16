<?php

$sql  = "SELECT year(player_created) year, "
      . "month(player_created) month, "
      . "count(if(player_rank = 'unverified' or player_rank = 'tourist', 1, null)) unverified, "
      . "count(if(player_rank != 'unverified' and player_rank != 'tourist', 1, null)) settlers "
      . "FROM player ";
$sql .= "GROUP BY year, month HAVING year != 0";
if($_SESSION["mobile"]){
	$sql .= " ORDER BY year DESC LIMIT 5";
}

$stmt = $conn->prepare($sql);
$stmt->execute();

$players = $stmt->fetchAll(PDO::FETCH_ASSOC);

$context = array("players" => $players);

$styles = array();
$scripts = array();
render('stats_newplayers.phtml', 'New players', $context, $styles, $scripts);

