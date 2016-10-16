<?php

require_once '../include/functions.php';

checkIfOnline();

checkRank("senior_admin", "junior_admin");

$sql  = "SELECT * FROM donation ";
$sql .= "INNER JOIN player USING (player_id) ";
$sql .= "ORDER BY donation_timestamp DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$donations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$context = array();
$context["donations"] = $donations;

$styles = array();
$scripts = array();
render('donations.phtml', 'Donations', $context, $styles, $scripts);
