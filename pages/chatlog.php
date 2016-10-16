<?php

checkIfOnline();
checkRank("junior_admin", "senior_admin");

$players = array_key_exists("players", $_GET) ? $_GET["players"] : "";
$start = array_key_exists("start", $_GET) ? $_GET["start"] : "";
$text = array_key_exists("text", $_GET) ? $_GET["text"] : "";
$channel = array_key_exists("channel", $_GET) ? $_GET["channel"] : "";

$params = array();

$sql  = "SELECT * FROM player_chatlog ";
$sql .= "INNER JOIN player USING (player_id) ";
$sql .= "WHERE 1 ";
if ($players) {
    $players = explode(",", $players);
    $sql .= "AND (";
    $delim = "";
    foreach ($players as $player) {
        $sql .= $delim;
        $sql .= "player_name = ? ";
        $delim = "OR ";
        $params[] = $player;
    }
    $sql .= ") ";
} else {
    $players = array();
}
if ($start) {
    $sql .= "AND chatlog_timestamp >= ? ";
    $params[] = strtotime($start);
}
if ($text) {
    $sql .= "AND chatlog_message LIKE ? ";
    $params[] = $text;
}
if ($channel) {
    $sql .= "AND chatlog_channel = ? ";
    $params[] = $channel;
}
if ($start) {
    $sql .= "ORDER BY chatlog_timestamp ";
} else {
    $sql .= "ORDER BY chatlog_timestamp DESC ";
}
$sql .= "LIMIT 1000";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$chatlogs = $stmt->fetchAll();

if ($start) {
    $chatlogs = array_reverse($chatlogs);
}

$context = array();
$context["players"] = $players;
$context["start"] = $start;
$context["text"] = $text;
$context["channel"] = $channel;
$context["chatlogs"] = $chatlogs;

$styles = array();
$scripts = array();
render('chatlog.phtml', 'Chat Logs', $context, $styles, $scripts);
