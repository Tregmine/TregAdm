<?php
checkIfOnline();
checkRank("junior_admin", "senior_admin");
$sql = "SELECT * FROM player WHERE UPPER(player_name) = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute(array(strtoupper($_POST["old_player"])));
$oldplayer = $stmt->fetch();
$stmt->closeCursor();

$sql = "SELECT * FROM player WHERE UPPER(player_name) = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute(array(strtoupper($_POST["new_player"])));
$newplayer = $stmt->fetch();
$stmt->closeCursor();

$sql = "DELETE FROM player WHERE player_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($oldplayer["player_id"]));
$stmt->closeCursor();
$sql = "UPDATE player SET player_id = ? WHERE player_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($oldplayer["player_id"], $newplayer["player_id"]));
$stmt->closeCursor();

$context = array();

$context["oldplayer"] = $oldplayer;
$context["newplayer"] = $newplayer;

$_SESSION[907] = array();
$_SESSION[907][1] = $oldplayer;
$_SESSION[907][2] = $newplayer;
header('Location: /?code=907');