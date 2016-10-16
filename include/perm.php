<?php

$permissionList = array(
        "senior_admin" => array("name" => "Senior Admin", "color" => "dark_red", "staff" => true),
        "junior_admin" => array("name" => "Admin", "color" => "red", "staff" => true),
        "builder" => array("name" => "Builder", "color" => "yellow", "staff" => false),
        "coder" => array("name" => "Coder", "color" => "dark_purple", "staff" => true),
        "guardian" => array("name" => "Guardian", "color" => "dark_blue", "staff" => true),
        "donator" => array("name" => "Donator", "color" => "gold", "staff" => false),
        "resident" => array("name" => "Resident", "color" => "dark_green", "staff" => false),
        "settler" => array("name" => "Settler", "color" => "green", "staff" => false),
        "tourist" => array("name" => "Tourist", "color" => "white", "staff" => false),
        "unverified" => array("name" => "Unverified", "color" => "white", "staff" => false)
    );

function isStaff($rank, $permlist){
	$rankperms = $permlist[$rank];
	return $rankperms["staff"];
}

$flags = array(array("name" => "Child", "choice" => true, "rank" => "senior_admin"),
               array("name" => "Teleport Shield", "choice" => true),
               array("name" => "Soft Warned", "choice" => false),
               array("name" => "Hard Warned", "choice" => false),
               array("name" => "Invisible", "choice" => true),
               array("name" => "Hidden Location", "choice" => true),
               array("name" => "Fly Enabled", "choice" => true),
               array("name" => "Force shield", "choice" => true),
               array("name" => "Chest log", "choice" => true),
               array("name" => "Hidden Announcement", "choice" => true)
               );

// Colorize the username based on rank or the color stored in the db
// Also, grey background warned players and strikethrough banned players
// Output is: " class="COLOR" " or " class="COLOR banned "
function userCSSColor($userId) {
    global $conn, $permissionList;

    $color = "white";
    if (is_numeric($userId)) {
        $stmt = $conn->prepare("SELECT * FROM player WHERE player_id = ?");
        $stmt->execute(array($userId));
        $player = $stmt->fetch();
        $stmt->closeCursor();
    } else {
        $stmt = $conn->prepare("SELECT * FROM player WHERE player_name = ?");
        $stmt->execute(array($userId));
        $player = $stmt->fetch();
        $stmt->closeCursor();
    }

    if ($player) {
        $rank = $permissionList[$player["player_rank"]];
        $color = $rank["color"];
    }

    return " class=\"" . $color . "\" ";
}
