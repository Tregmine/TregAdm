<?php

require_once '_init.php';
require_once '_check.php';

if (!array_key_exists("q", $_GET)) {
    exit;
}

$sql  = "SELECT player_name FROM player ";
$sql .= "WHERE player_name LIKE ? ";
$sql .= "ORDER BY player_name ";
$sql .= "LIMIT 20";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_GET["q"] . "%"));

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$players = array();
foreach ($result as $player) {
    $players[] = $player["player_name"];
}

echo json_encode($players);

