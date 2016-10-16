<?php
checkIfOnline();
$sql = "SELECT * FROM player WHERE player_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute(array($_SESSION['id']));

$player = $stmt->fetch();
$stmt->closeCursor();

$title = "Player Mail: " . $player["player_name"];

$context = array();
$context["player"] = $player;
$context["title"] = $title;
$context["settings"] = array(

);

$styles = array("/css/inventory.css");
$scripts = array();

render('player_mail_compose.phtml', $title, $context, $styles, $scripts);
