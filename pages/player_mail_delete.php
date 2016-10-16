<?php
checkIfOnline();
if($_GET['id'] <= 0){
	header("Location: /index.php/player/mail?type=received");
}
$sql = "SELECT * FROM player WHERE player_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_SESSION['id']));

$player = $stmt->fetch();
$stmt->closeCursor();
//Start delete mail
$sql = "SELECT 1 FROM player_mail WHERE receiver_name = ? AND mail_id = ? AND deleted = 'false'";
$stmt = $conn->prepare($sql);
$stmt->execute(array($player["player_name"], $_GET['id']));
$result = $stmt->fetchAll();
$count = 0;
foreach($result as $row){
	$count = 1;
}
if($count == 0){
	header("Location: /index.php/player/mail?type=received&err=delfail");
	exit;
}
$stmt->closeCursor();
$sql = "UPDATE player_mail SET deleted = 'true' WHERE receiver_name = ? AND mail_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($player["player_name"], $_GET['id']));
//End delete mail
$mail = array();
$deletable = true;
$sql = "SELECT * FROM player_mail WHERE receiver_name = ? AND deleted = 'false'";

$stmt = $conn->prepare($sql);
$stmt->execute(array($player["player_name"]));
$stmt->closeCursor();
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

$title = "Player Mail: " . $player["player_name"];

$context = array();
$context["player"] = $player;
$context["mail"] = $mail;
$context["settings"] = array(
'deletable' => $deletable,
'msgtype' => "Received",
'title' => $title,
);

$styles = array("/css/inventory.css");
$scripts = array();

render('player_mail.phtml', $title, $context, $styles, $scripts);
