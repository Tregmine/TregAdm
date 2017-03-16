<?php

checkIfOnline();

checkRank("senior_admin");

$sql  = "SELECT player_id, player_name, CAST(property_value AS UNSIGNED) rank, count(login_id) logins, "
      . "IF(NOT login_timestamp IS NULL, from_unixtime(max(login_timestamp)), 'N/A') last_login "
      . "FROM player_property ";
$sql .= "INNER JOIN player USING (player_id) ";
$sql .= "LEFT JOIN player_login USING (player_id) ";
$sql .= "WHERE property_key = 'guardian' ";
$sql .= "GROUP BY (player_id) ORDER BY rank";

$stmt = $conn->prepare($sql);
$stmt->execute();

$guardians = $stmt->fetchAll(PDO::FETCH_ASSOC);

$context = array();
$context["guardians"] = $guardians;

$styles = array();
$scripts = array();
render('guardians.phtml', 'Guardians', $context, $styles, $scripts);
