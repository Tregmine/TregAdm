<?php

$permissionList = array(
        "senior_admin" => array("name" => "Senior Admin", "color" => "dark_red"),
        "junior_admin" => array("name" => "Admin", "color" => "red"),
        "builder" => array("name" => "Builder", "color" => "yellow"),
        "coder" => array("name" => "Coder", "color" => "dark_purple"),
        "guardian" => array("name" => "Guardian", "color" => "dark_blue"),
        "donator" => array("name" => "Donator", "color" => "gold"),
        "resident" => array("name" => "Resident", "color" => "dark_green"),
        "settler" => array("name" => "Settler", "color" => "green"),
        "tourist" => array("name" => "Tourist", "color" => "white"),
        "unverified" => array("name" => "Unverified", "color" => "white")
    );

$flags = array(array("name" => "Child", "choice" => false),
               array("name" => "Telport Shield", "choice" => true),
               array("name" => "Soft Warned", "choice" => false),
               array("name" => "Hard Warned", "choice" => false),
               array("name" => "Invisible", "choice" => true),
               array("name" => "Hidden Location", "choice" => true));

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
