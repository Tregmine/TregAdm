<?php
checkIfOnline();
require_once('../include/tregmine_api.php');
$sql = "SELECT * FROM player WHERE player_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_SESSION['id']));

$player = $stmt->fetch();
$stmt->closeCursor();
$sql = "SELECT * FROM player WHERE player_name = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_POST["toPlayer"]));

$sendTo = $stmt->fetch();
$stmt->closeCursor();
if(!isset($_POST["toPlayer"])){
	header("Location: /index.php/player/mail");
}
$sql = "INSERT INTO player_mail (sender_name, receiver_name, message, deleted) VALUES (?, ?, ?, 'false')";

$stmt = $conn->prepare($sql);
$stmt->execute(array($player["player_name"], $_POST["toPlayer"], $_POST["message"]));
$stmt->closeCursor();
$title = "Player Mail: " . $player["player_name"];

$context = array();
$context["player"] = $player;
$context["title"] = $title;
$context["settings"] = array(

);
tregmine_push_notification($sendTo["player_id"], $player["player_id"], "mail");
$styles = array("/css/inventory.css");
$scripts = array();
print_r($_POST);
$_SESSION["message"] = "sendsuccess";
header("Location: /index.php/player/mail");
//render('player_mail_compose.phtml', $title, $context, $styles, $scripts);
