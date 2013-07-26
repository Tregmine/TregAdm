<?php

$permissionList = array(
        "senioradmin" => "Senior Admin",
        "admin" => "Admin",
        "builder" => "Builder",
        "donator" => "Donator",
        "resident" => "Resident",
        "trusted" => "Trusted",
        "invis" => "Invisible",
        "archive" => "Archive",
        "hiddenlocation" => "Hidden Location"
    );

$colors = array(
        "white" => "WHITE",
        "admin" => "RED",
        "broker" => "DARK_RED",
        "helper" => "YELLOW",
        "purle" => "DARK_PURPLE",
        "donator" => "GOLD",
        "trusted" => "DARK_GREEN",
        "warned" => "GRAY",
        "trial" => "GREEN",
        "vampire" => "DARK_RED",
        "hunter" => "BLUE",
        "pink" => "LIGHT_PURPLE",
        "child" => "AQUA",
        "mentor" => "DARK_AQUA",
        "police" => "BLUE"
    );

// Colorize the username based on rank or the color stored in the db
// Also, grey background warned players and strikethrough banned players
// Output is: " class="COLOR" " or " class="COLOR banned "
function userCSSColor ($userID) {
    global $conn, $colors;

    $date = time();

    // Check if userID supplied, return default value otherwise
    if ($userID) {
        // Determine whether $userID represents the numeric player_id or player_name
        $playerID = "";
        $playerColor = "";
        $reported = "";

        if (ctype_digit($userID)) {
            $stmt = $conn->prepare("SELECT player_id FROM player WHERE player_id = ?");
            $stmt->execute(array($userID));	
            $playerIDTmp = $stmt->fetchColumn();
            $stmt->closeCursor();

            if ($playerIDTmp) {
                $playerID = $playerIDTmp;
            }
        }

        // $userID wasn't player_id, now check if its player_name
        if (!$playerID) {
            $stmt = $conn->prepare("SELECT player_id FROM player WHERE player_name = ?");
            $stmt->execute(array($userID));	
            $playerIDTmp = $stmt->fetchColumn();
            $stmt->closeCursor();

            if ($playerIDTmp) {
                $playerID = $playerIDTmp;
            }
        }

        // We found a match and have a valid player_id
        if ($playerID) {
            $sql  = "SELECT property_key, property_value FROM player_property ";
            $sql .= "WHERE ( property_key = 'senioradmin' OR property_key = 'admin' "
                  . "OR property_key = 'guardian' OR property_key = 'builder' "
                  . "OR property_key = 'donator' OR property_key = 'color' ) "
                  . "AND player_id = ?";
            // Get player's color attribute
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($playerID));
            $playerInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            // Set player color according to their staff rank, if defined
            foreach($playerInfo as $temp => $val) {
                if ($playerInfo[$temp]['property_key'] == "senioradmin" and $playerInfo[$temp]['property_value'] == "true") {
                    $playerColor = "vampire";
                }
                if ($playerInfo[$temp]['property_key'] == "admin" and $playerInfo[$temp]['property_value'] == "true" and $playerColor != "vampire") {
                    $playerColor = "admin";
                }
                if ($playerInfo[$temp]['property_key'] == "guardian" and $playerColor != "vampire" and $playerColor != "admin") {
                    $playerColor = "police";
                }
                if ($playerInfo[$temp]['property_key'] == "builder" and $playerInfo[$temp]['property_value'] == "true" and $playerColor != "vampire" and $playerColor != "admin" and $playerColor != "mentor") {
                    $playerColor = "helper";
                }
                if ($playerInfo[$temp]['property_key'] == "donator" and $playerInfo[$temp]['property_value'] == "true" and $playerColor != "vampire" and $playerColor != "admin" and $playerColor != "mentor" and $playerColor != "helper") {
                    $playerColor = "donator";
                }
                if ($playerInfo[$temp]['property_key'] == "color" and !$playerColor) {
                    $playerColor = $playerInfo[$temp]['property_value'];
                }
            }

            if (!$playerColor) {
                $playerColor = "No Color / Tourist";
            }

            // Get warned/banned status
            $stmt = $conn->prepare("SELECT report_action FROM player_report WHERE subject_id = ? AND report_validuntil >= ? AND report_action IN ('softwarn', 'hardwarn', 'ban') ORDER BY report_id DESC");
            $stmt->execute(array($playerID,$date));
            $playerReport = $stmt->fetchColumn();
            $stmt->closeCursor();

            if ($playerReport == "ban") {
                $reported = " banned";
            }
            else if ($playerReport == "softwarn" or $playerReport == "hardwarn") {
                $reported = " warned";
            }

            return " class=\"" . $colors[$playerColor] . "$reported\" ";
        }
        else {
            return " class=\"" . $colors['No Color / Tourist'] . "\" ";
        }
    }
    else {
        return " class=\"" . $colors['No Color / Tourist'] . "\" ";
    }

}

