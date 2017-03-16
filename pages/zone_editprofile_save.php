<?php

checkIfOnline();

if (!array_key_exists("id", $_GET)) {
    header('Location: /index.php');
    exit;
}

$id = $_GET["id"];

// lookup zone
$stmt = $conn->prepare("SELECT * FROM zone WHERE zone_id = ?");
$stmt->execute(array($id));

$zone = $stmt->fetch();

$stmt->closeCursor();

if (!$zone) {
    exit;
}

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

$text = $_POST["text"];

$sql  = "INSERT INTO zone_profile (zone_id, player_id, profile_timestamp, profile_text) ";
$sql .= "VALUES (?, ?, unix_timestamp(), ?)";

$stmt = $conn->prepare($sql);
$stmt->execute(array($id, $_SESSION["id"], $text));

header('Location: /index.php/zone/profile?id='.$id);
