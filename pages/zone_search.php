<?php

if (!array_key_exists("admin", $_SESSION)) {
    header('Location: index.php');
    exit;
}

$q = array_key_exists("q", $_GET) ? $_GET["q"] : "";

$params = array();
$sql  = "SELECT * FROM zone ";
if ($q) {
    $sql  .= "WHERE zone_name LIKE ? ";
    $params[] = $q;
}
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
