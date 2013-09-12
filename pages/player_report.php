<?php

checkIfOnline();
checkRank("guardian", "coder", "builder", "junior_admin", "senior_admin");

if (!array_key_exists("id", $_GET)) {
    exit;
}

$stmt = $conn->prepare("SELECT * FROM player WHERE player_id = ?");
$stmt->execute(array($_GET["id"]));

$player = $stmt->fetch();

$stmt->closeCursor();

$sql  = "SELECT * FROM player_report ";
$sql .= "INNER JOIN player ON player_id = issuer_id ";
$sql .= "WHERE subject_id = ? ";
$sql .= "ORDER BY report_timestamp DESC";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_GET["id"]));

$reports = $stmt->fetchAll();

$title = "Report: " . $player["player_name"];

$context = array();
$context["player"] = $player;
$context["reports"] = $reports;

$styles = array();
$scripts = array();
render('player_report.phtml', $title, $context, $styles, $scripts);
