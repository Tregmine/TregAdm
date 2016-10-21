<?php

checkIfOnline();
checkRank("senior_admin");

if (!array_key_exists("id", $_GET)) {
    exit;
}

$stmt = $conn->prepare("SELECT * FROM player WHERE player_id = ?");
$stmt->execute(array($_GET["id"]));

$player = $stmt->fetch();

$stmt->closeCursor();

$stmt = $conn->prepare("SELECT * FROM player_property WHERE player_id = ? AND NOT property_key = ?");
$stmt->execute(array($_GET["id"], "keyword"));

$rawSettings = $stmt->fetchAll();
foreach ($rawSettings as $setting) {
    $psettings[$setting["property_key"]] = $setting["property_value"];
}

$sql  = "SELECT player_id, player_name, property_value FROM player_property ";
$sql .= "INNER JOIN player USING (player_id) ";
$sql .= "WHERE property_key = 'guardian' ";
$sql .= "ORDER BY property_value ";

$stmt = $conn->prepare($sql);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$guardians = array();
$maxRank = 0;
foreach ($result as $guardian) {
    $guardians[$guardian["property_value"]] = $guardian;
    $maxRank = max($maxRank, $guardian["property_value"]);
}

$title = "Permissions: " . $player["player_name"];

$context = array();
$context["player"] = $player;
$context["psettings"] = $psettings;
$context["guardians"] = $guardians;
$context["permissionList"] = $permissionList;
$context["maxRank"] = $maxRank;
$context["flags"] = $flags;
if(array_key_exists($_POST['keywordRemoved'])){
  $context["keywordRemoved"] = true;
}else{
  $context["keywordRemoved"] = 0;
}

$styles = array();
$scripts = array("/js/player_perm.js");
render('player_perm.phtml', $title, $context, $styles, $scripts);
