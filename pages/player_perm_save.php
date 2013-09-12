<?php

require_once '../include/password.php';

checkIfOnline();
checkRank("senior_admin");

if (!array_key_exists("id", $_GET)) {
    header('Location: /start.php');
    exit;
}

$id = $_GET["id"];
$rank = array_key_exists("rank", $_POST) ? $_POST["rank"] : "unverified";

// lookup player
$stmt = $conn->prepare("SELECT * FROM player WHERE player_id = ?");
$stmt->execute(array($_GET["id"]));

$player = $stmt->fetch();

$stmt->closeCursor();

// change guardian rank
$guardian = array_key_exists("guardian", $_POST) ? $_POST["guardian"] : "";
if ($guardian) {
    $stmt = $conn->prepare("REPLACE INTO player_property VALUES (?, 'guardian', ?, null)");
    $stmt->execute(array($id, $guardian));
} else {
    $stmt = $conn->prepare("DELETE FROM player_property WHERE property_key = 'guardian' AND player_id = ?");
    $stmt->execute(array($id));
}

// change quitmessage
$quitMessage = array_key_exists("quitmsg", $_POST) ? $_POST["quitmsg"] : "";
if ($quitMessage) {
    $stmt = $conn->prepare("REPLACE INTO player_property VALUES (?, 'quitmessage', ?, null)");
    $stmt->execute(array($id, $quitMessage));
} else {
    $stmt = $conn->prepare("DELETE FROM player_property WHERE property_key = 'quitmessage' AND player_id = ?");
    $stmt->execute(array($id));
}

// change player settings
$flagsArr = array_key_exists("flags", $_POST) ? $_POST["flags"] : array();
$flags = 0;
foreach ($flagsArr as $key => $flag) {
    $flags |= (1 << $key);
}

$params = array();

$sql  = "UPDATE player SET player_rank = ?, player_flags = ? ";
$params[] = $rank;
$params[] = $flags;

$password = array_key_exists("password", $_POST) ? $_POST["password"] : "";
if ($password) {
    $sql .= ", player_password = ? ";
    $params[] = crypt($password, "$5$" . gensalt() . "$");
}

$email = array_key_exists("email", $_POST) ? $_POST["email"] : "";
if ($email) {
    $sql .= ", player_email = ? ";
    $params[] = $email;
}

$sql .= "WHERE player_id = ?";
$params[] = $id;

$stmt = $conn->prepare($sql);
$stmt->execute($params);

//$conn->commit();

header('Location: /index.php/player/perm?id='.$id);
