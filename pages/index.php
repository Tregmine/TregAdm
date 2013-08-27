<?php 
require_once '../include/tregmine_api.php';

//if (array_key_exists("id", $_SESSION)) {
//    header('Location: index.php/start');
//    exit;
//}

$sql  = "SELECT * FROM player_login "; 
$sql .= "INNER JOIN player USING (player_id) ";
$sql .= "WHERE login_action = 'login' ";
$sql .= "ORDER BY login_timestamp DESC LIMIT 10";

$stmt = $conn->prepare($sql);
$stmt->execute();

$logins = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql  = "SELECT * FROM player "; 
$sql .= "WHERE NOT player_rank IN ('unverified', 'tourist') ";
$sql .= "ORDER BY player_created DESC LIMIT 10";

$stmt = $conn->prepare($sql);
$stmt->execute();

$settlers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$context = array();
$context["players"] = tregmine_online_players($tregmineApiKey);
$context["logins"] = $logins;
$context["settlers"] = $settlers;

$styles = array();
$scripts = array("/js/start.js");
render('index.phtml', 'Welcome to Tregmine!', $context, $styles, $scripts);
