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

// Get zone users and roles
$sqlUsers  = "SELECT zone_user.*, player.*, property_value color FROM zone_user ";
$sqlUsers .= "INNER JOIN player ON user_id = player.player_id ";
$sqlUsers .= "INNER JOIN player_property ON player_property.player_id = player.player_id ";
$sqlUsers .= "WHERE zone_id = ? ORDER BY user_perm";

$stmt = $conn->prepare($sqlUsers);

$stmt->execute(array($zone["zone_id"]));
$users_dirty = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();
$users = array();
$count = 0;
foreach($users_dirty as $user){
	$continue = true;
	foreach($users as $check){
		if($check["user_id"] == $user["user_id"]){
			$continue = false;
		}
	}
	if($continue){
	$count++;
	$users[$count] = $user;
	}else{
		continue;
	}
}

// Get lot and owner info
$stmt = $conn->prepare("SELECT * FROM zone_lot WHERE zone_id = ?");
$stmt->execute(array($zone["zone_id"]));
$lots = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sqlLotUsers  = "SELECT zone_lotuser.*, player.*, property_value color FROM zone_lotuser ";
$sqlLotUsers .= "INNER JOIN player ON user_id = player_id ";
$sqlLotUsers .= "LEFT JOIN player_property ON player_property.player_id = player.player_id "
              . "AND property_key = 'color' ";
$sqlLotUsers .= "WHERE lot_id = ?";
$stmt = $conn->prepare($sqlLotUsers);
foreach ($lots as &$lot) {
    $stmt->execute(array($lot["lot_id"]));

    $lot["users"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

$title = "Zone Info: " . $zone["zone_name"];

$context = array();
$context["zone"] = $zone;
$context["users"] = $users;
$context["lots"] = $lots;
$context["rects"] = $rects;
$context["warps"] = $warps;

$styles = array();
$scripts = array();
render('zone_info.phtml', $title, $context, $styles, $scripts);
