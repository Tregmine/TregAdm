<?php

require_once '../include/php-markdown/Michelf/Markdown.php';

if (!array_key_exists("id", $_GET)) {
    exit;
}

// Get general zone info
$stmt = $conn->prepare("SELECT * FROM zone WHERE zone_id = ?");
$stmt->execute(array($_GET["id"]));
$zone = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

// Get zone users and roles
$sqlUsers  = "SELECT zone_user.*, player.* FROM zone_user ";
$sqlUsers .= "INNER JOIN player ON user_id = player.player_id ";
$sqlUsers .= "WHERE zone_id = ? AND user_perm IN ('owner', 'maker') ORDER BY user_perm, player_name";

$stmt = $conn->prepare($sqlUsers);
$stmt->execute(array($zone["zone_id"]));

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$isOwner = false;
foreach ($users as $user) {
    if (array_key_exists("id", $_SESSION) &&
        $user["player_id"] == $_SESSION["id"] &&
        $user["user_perm"] == "owner") {

        $isOwner = true;
    }
}

$stmt->closeCursor();

if (!$zone["zone_publicprofile"] && !$isOwner && !hasRank("junior_admin", "senior_admin")) {
    header('Location: /index.php');
    exit;
}


// Get zone coordinates
$stmt = $conn->prepare("SELECT rect_x1, rect_y1, rect_x2, rect_y2 FROM zone_rect WHERE zone_id=?");
$stmt->execute(array($zone["zone_id"]));
$rects = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$sql  = "SELECT warp_name, warp_x, warp_y, warp_z FROM warp ";
$sql .= "WHERE (warp_x BETWEEN ? AND ?) "
      . "AND (warp_z BETWEEN ? AND ?) "
      . "AND warp_world = ?";
$stmt = $conn->prepare($sql);
$warps = array();
foreach ($rects as $rect) {
    // Get warps within zone
    // Note: In the zone table, rect_y is actually the z coordinate in the warps table
    if ($rect['rect_x1'] > $rect['rect_x2']) {
        $qX1 = $rect['rect_x2'];
        $qX2 = $rect['rect_x1'];
    }
    else {
        $qX1 = $rect['rect_x1'];
        $qX2 = $rect['rect_x2'];
    }
    if ($rect['rect_y1'] > $rect['rect_y2']) {
        $qY1 = $rect['rect_y2'];
        $qY2 = $rect['rect_y1'];
    }
    else {
        $qY1 = $rect['rect_y1'];
        $qY2 = $rect['rect_y2'];
    }
    $stmt->execute(array($qX1, $qX2, $qY1, $qY2, $zone["zone_world"]));
    $warps += $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$sql  = "SELECT * FROM zone_profile WHERE zone_id = ? ORDER BY profile_timestamp DESC LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->execute(array($zone["zone_id"]));

$profile = $stmt->fetch();

$stmt->closeCursor();

$map = strtoupper(md5($zone["zone_id"] . "," . "supersecret")) . ".png";

$title = $zone["zone_name"];

$context = array();
$context["zone"] = $zone;
$context["users"] = $users;
$context["rects"] = $rects;
$context["warps"] = $warps;
$context["map"] = $map;
$context["isOwner"] = $isOwner;
$context["profile"] = $profile;

$styles = array();
$scripts = array();
render('zone_profile.phtml', $title, $context, $styles, $scripts);
