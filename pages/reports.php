<?php

require_once '../include/functions.php';

checkIfOnline();
checkRank("guardian", "coder", "builder", "junior_admin", "senior_admin");

$sql  = "SELECT player_report.*, a.player_name issuer, "
      . "b.player_name subject FROM player_report ";
$sql .= "INNER JOIN player a ON a.player_id = issuer_id ";
$sql .= "INNER JOIN player b ON b.player_id = subject_id ";
$sql .= "ORDER BY report_timestamp DESC ";
$sql .= "LIMIT 100";

$stmt = $conn->prepare($sql);
$stmt->execute(array());

$reports = $stmt->fetchAll();

$context = array();
$context["reports"] = $reports;

$styles = array();
$scripts = array();
render('reports.phtml', 'Reports', $context, $styles, $scripts);
