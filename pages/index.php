<?php 
require_once '../include/tregmine_api.php';
require_once '../include/functions.php';

$sql  = "SELECT * FROM player_login "; 
$sql .= "INNER JOIN player USING (player_id) ";
$sql .= "WHERE login_action = 'login' ";
$sql .= "ORDER BY login_timestamp DESC LIMIT 10";

$stmt = $conn->prepare($sql);
$stmt->execute();

$logins = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql  = "SELECT student_id, student.player_name student_name, "
      . "mentor_id, mentor.player_name mentor_name, "
      . "mentorlog_completedtime FROM mentorlog ";
$sql .= "INNER JOIN player student ON student.player_id = student_id ";
$sql .= "INNER JOIN player mentor ON mentor.player_id = mentor_id ";
$sql .= "WHERE mentorlog_status = 'completed' ";
$sql .= "ORDER BY mentorlog_completedtime DESC LIMIT 10";

$stmt = $conn->prepare($sql);
$stmt->execute();

$settlers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql  = "SELECT date(from_unixtime(login_timestamp)) d, count(login_id) c, "
      . "count(distinct player_id) uc, max(login_onlineplayers) op FROM player_login ";
$sql .= "WHERE login_timestamp > unix_timestamp() - 30*86400 "
      . "AND login_action = 'login' ";
$sql .= "GROUP BY d ORDER BY d";

$stmt = $conn->prepare($sql);
$stmt->execute(array());

$stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

$resultCount = count($stats);
$loginsCount = $stats[$resultCount-1]["c"];
$uniqueCount = $stats[$resultCount-1]["uc"];
$maxPlayers = $stats[$resultCount-1]["op"]+1;
$loginsTrend = trend($stats, "c");
$uniqueTrend = trend($stats, "uc");

$context = array();
$context["players"] = tregmine_online_players($tregmineApiKey);
$context["logins"] = $logins;
$context["settlers"] = $settlers;
$context["loginsCount"] = $loginsCount;
$context["uniqueCount"] = $uniqueCount;
$context["maxPlayers"] = $maxPlayers;
$context["loginsTrend"] = $loginsTrend[count($loginsTrend)-1];
$context["uniqueTrend"] = $uniqueTrend[count($uniqueTrend)-1];

$styles = array();
$scripts = array("/js/start.js");
render('index.phtml', 'Welcome to Tregmine!', $context, $styles, $scripts);

// JAMES WUZ ERE 2K14 @11:37PM GMT ON LYK FRIDAY 14TH FEBZ