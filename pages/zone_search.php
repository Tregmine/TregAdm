<?php

checkIfOnline();

$q = array_key_exists("q", $_GET) ? $_GET["q"] : "";

$params = array();
$sql  = "SELECT * FROM zone ";
$sql .= "WHERE 1 ";
if ($q) {
    $sql  .= "AND zone_name LIKE ? ";
    $params[] = $q;
}
if (!hasRank("junior_admin", "senior_admin")) {
    $sql .= "AND zone_publicprofile = '1' ";
}
$sql .= "AND zone_name NOT LIKE '!%' ";
$sql .= "ORDER BY zone_name";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$hits = $stmt->fetchAll();

$context = array();
$context["q"] = $q;
$context["hits"] = $hits;

$styles = array();
$scripts = array("/js/zone_autocomplete.js");
render('zone_search.phtml', 'Zones', $context, $styles, $scripts);
