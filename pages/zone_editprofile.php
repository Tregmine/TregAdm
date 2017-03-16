<?php

checkIfOnline();

if (!array_key_exists("id", $_GET)) {
    exit;
}

// Get general zone info
$stmt = $conn->prepare("SELECT * FROM zone WHERE zone_id = ?");
$stmt->execute(array($_GET["id"]));
$zone = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$sqlUsers  = "SELECT zone_user.*, player.* FROM zone_user ";
$sqlUsers .= "INNER JOIN player ON user_id = player.player_id ";
$sqlUsers .= "WHERE zone_id = ? AND user_perm IN ('owner', 'maker') ORDER BY user_perm, player_name";

$stmt = $conn->prepare($sqlUsers);
$stmt->execute(array($zone["zone_id"]));

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$isOwner = false;
foreach ($users as $user) {
    if ($user["player_id"] == $_SESSION["id"] && $user["user_perm"] == "owner") {
        $isOwner = true;
    }
}

$stmt->closeCursor();

if (!$isOwner && !hasRank("junior_admin", "senior_admin")) {
    exit;
}

$sql  = "SELECT * FROM zone_profile WHERE zone_id = ? ORDER BY profile_timestamp DESC LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->execute(array($zone["zone_id"]));

$profile = $stmt->fetch();

$stmt->closeCursor();

$title = "Edit Zone Profile: " . $zone["zone_name"];

$context = array();
$context["zone"] = $zone;
$context["profile"] = $profile;

$styles = array();
$scripts = array();
render('zone_editprofile.phtml', $title, $context, $styles, $scripts);
