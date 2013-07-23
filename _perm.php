<?php

$permissionList = array(
        "admin" => "Admin", 
        "mentor" => "Guardian", 
#        "builder" => "Builder", 
        "zones" => "Zones", 
        "trusted" => "Trusted", 
        "donator" => "Donator", 
        "invis" => "Invisible",
        "banned" => "Banned",
	"archive" => "Archive",
	"senioradmin" => "Senior Admin",
	"hiddenlocation" => "hiddenlocation"
    );
    
$colors = array(
	"No Color / Tourist" => "WHITE",
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

// Colorize the username based upon the color stored in the db, and strikethrough banned players
// Output is: " class="COLOR" " or " class="COLOR" banned "
function userCSSColor ($userID) {
	global $conn, $colors;
	
	// Check if userID supplied, return default value otherwise
	if ($userID) {
		// Determine whether $userID represents the numeric player_id or player_name
		$playerID = "";
		$playerColor = "";
		$banned = "";
		
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
		  $stmt = $conn->prepare("SELECT property_value FROM player_property WHERE ( property_key = 'color' OR property_key = 'banned' ) AND player_id = ?");
			$stmt->execute(array($playerID));
		
			$playerInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
			if ($stmt->rowCount() > 1) {
				foreach($playerInfo as $pI) {
					if ($pI['property_value'] == "true") {
						$banned = "banned";
					}
					else {
						$playerColor = $pI['property_value'];
					}
				}
				
			}
			else {
				$playerColor = $playerInfo[0]['property_value'];
			}

			if (!$playerColor) {
				$playerColor = "No Color / Tourist";
			}
			return " class=\"" . $colors[$playerColor] . " $banned\" ";
		}
		else {
			return " class=\"" . $colors['No Color / Tourist'] . "\" ";
		}
	}
	else {
		return " class=\"" . $colors['No Color / Tourist'] . "\" ";
	}
	
}