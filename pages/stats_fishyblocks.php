<?php

$sql  = "SELECT fishyblock.player_id, player_name, "
      . "count(distinct fishyblock_id) blocks, "
      . "sum(transaction_amount) sold, "
      . "sum(transaction_totalcost) cost from fishyblock ";
$sql .= "INNER JOIN player ON player.player_id = fishyblock.player_id ";
$sql .= "LEFT JOIN fishyblock_transaction USING (fishyblock_id) ";
$sql .= "WHERE transaction_type = 'buy' ";
$sql .= "GROUP BY fishyblock.player_id ";
$sql .= "ORDER BY cost DESC";
if($_SESSION["mobile"]){
	$sql .= " LIMIT 15";
}

$stmt = $conn->prepare($sql);
$stmt->execute();

$fishyblocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$context = array("fishyblocks" => $fishyblocks);

$styles = array();
$scripts = array();
render('stats_fishyblocks.phtml', 'Fishyblocks by player', $context, $styles, $scripts);

