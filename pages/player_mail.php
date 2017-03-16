<?php
checkIfOnline();
$sql = "SELECT * FROM player WHERE player_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_SESSION['id']));

$player = $stmt->fetch();
$mail = array();
$deletable = false;
if(!isset($_GET["type"])){
	$msgtype = "All";
	$type = "all";
}elseif($_GET["type"] == "all"){
	$msgtype = "All";
	$type = "all";
}elseif($_GET["type"] == "sent"){
	$msgtype = "Sent";
	$type = "sent";
}elseif($_GET["type"] == "received"){
	$msgtype = "Received";
	$type = "received";
}elseif($_GET["type"] == "deleted"){
	$msgtype = "Deleted";
	$type = "deleted";
}
if($type == "all"){
	$deletable = false;
$sql = "SELECT * FROM player_mail WHERE receiver_name = ? AND deleted = 'false' OR sender_name = ? AND deleted = 'false'";

$stmt = $conn->prepare($sql);
$stmt->execute(array($player["player_name"], $player["player_name"]));

$result = $stmt->fetchAll();

foreach ($result as $row) {
	$type = "";
	if($row["sender_name"] == $player['player_name']){
		$type = "sent";
	}elseif($row["receiver_name"] == $player['player_name']){
		$type = "rec";
	}else{
	
	}
    $array = array(
    'sender_name' => $row["sender_name"],
    'receiver_name' => $row["receiver_name"],
    'message' => $row["message"],
    'mail_id' => $row["mail_id"],
    'timestamp' => $row["timestamp"],
    'type' => $type
    );
    $mail[$row["mail_id"]] = $array;
    
}
$stmt->closeCursor();
}elseif($_GET["type"] == "sent"){
	$deletable = false;
$sql = "SELECT * FROM player_mail WHERE sender_name = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($player["player_name"]));

$result = $stmt->fetchAll();

foreach ($result as $row) {
	$type = "";
	if($row["sender_name"] == $player['player_name']){
		$type = "sent";
	}elseif($row["receiver_name"] == $player['player_name']){
		$type = "rec";
	}else{
	
	}
    $array = array(
    'sender_name' => $row["sender_name"],
    'receiver_name' => $row["receiver_name"],
    'message' => $row["message"],
    'mail_id' => $row["mail_id"],
    'timestamp' => $row["timestamp"],
    'type' => $type
    );
    $mail[$row["mail_id"]] = $array;
    
}
$stmt->closeCursor();
}elseif($type == "received"){
		$deletable = true;
$sql = "SELECT * FROM player_mail WHERE receiver_name = ? AND deleted = 'false'";

$stmt = $conn->prepare($sql);
$stmt->execute(array($player["player_name"]));

$result = $stmt->fetchAll();

foreach ($result as $row) {
	$type = "";
	if($row["sender_name"] == $player['player_name']){
		$type = "sent";
	}elseif($row["receiver_name"] == $player['player_name']){
		$type = "rec";
	}else{
	
	}
    $array = array(
    'sender_name' => $row["sender_name"],
    'receiver_name' => $row["receiver_name"],
    'message' => $row["message"],
    'mail_id' => $row["mail_id"],
    'timestamp' => $row["timestamp"],
    'type' => $type
    );
    $mail[$row["mail_id"]] = $array;
    
}
$stmt->closeCursor();
}elseif($type == "deleted"){
			$deletable = false;
$sql = "SELECT * FROM player_mail WHERE receiver_name = ? AND deleted = 'true'";

$stmt = $conn->prepare($sql);
$stmt->execute(array($player["player_name"]));

$result = $stmt->fetchAll();

foreach ($result as $row) {
	$type = "";
	if($row["sender_name"] == $player['player_name']){
		$type = "sent";
	}elseif($row["receiver_name"] == $player['player_name']){
		$type = "rec";
	}else{
	
	}
    $array = array(
    'sender_name' => $row["sender_name"],
    'receiver_name' => $row["receiver_name"],
    'message' => $row["message"],
    'mail_id' => $row["mail_id"],
    'timestamp' => $row["timestamp"],
    'type' => $type
    );
    $mail[$row["mail_id"]] = $array;
    
}
$stmt->closeCursor();
}
$title = "Player Mail: " . $player["player_name"];


$context = array();
$context["player"] = $player;
$context["mail"] = $mail;
$context["settings"] = array(
'deletable' => $deletable,
'msgtype' => $msgtype,
'title' => $title
);

$styles = array("/css/inventory.css");
$scripts = array();

render('player_mail.phtml', $title, $context, $styles, $scripts);
