<?php

checkIfOnline();
checkRank("junior_admin", "senior_admin");

if (!array_key_exists("id", $_GET)) {
    exit;
}

// Get general zone info
$stmt = $conn->prepare("SELECT * FROM zone WHERE zone_id = ?");
$stmt->execute(array($_GET["id"]));
$zone = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$title = "Edit Zone: " . $zone["zone_name"];

$context = array();
$context["zone"] = $zone;

$styles = array();
$scripts = array("/js/zone_edit.js");
render('zone_edit.phtml', $title, $context, $styles, $scripts);
