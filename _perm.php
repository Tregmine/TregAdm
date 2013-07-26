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
        "police" => "BLUE",
		"white" => "WHITE"
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
			// Get player's color attribute
		  $stmt = $conn->prepare("SELECT property_key, property_value FROM player_property WHERE ( property_key = 'senioradmin' OR property_key = 'admin' OR property_key = 'mentor' OR property_key = 'builder' OR property_key = 'donator' OR property_key = 'color' ) AND player_id = ?");
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
				if ($playerInfo[$temp]['property_key'] == "mentor" and $playerInfo[$temp]['property_value'] == "true" and $playerColor != "vampire" and $playerColor != "admin") {
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

			return " class=\"" . strtolower($colors[$playerColor]) . "$reported\" ";
		}
		else {
			return " class=\"" . $colors['No Color / Tourist'] . "\" ";
		}
	}
	else {
		return " class=\"" . $colors['No Color / Tourist'] . "\" ";
	}
	
}
?>