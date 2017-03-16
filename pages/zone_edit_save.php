<?php

checkIfOnline();
checkRank("junior_admin", "senior_admin");

if (!array_key_exists("id", $_GET)) {
    header('Location: /index.php');
    exit;
}

$id = $_GET["id"];

// lookup zone
$stmt = $conn->prepare("SELECT * FROM zone WHERE zone_id = ?");
$stmt->execute(array($id));

$zone = $stmt->fetch();

$stmt->closeCursor();

if (!$zone) {
    exit;
}

$name = $_POST["name"];
$owner = $_POST["owner"];
$enterDefault = array_key_exists("enter", $_POST) ? $_POST["enter"] : "0";
$placeDefault = array_key_exists("place", $_POST) ? $_POST["place"] : "0";
$destroyDefault = array_key_exists("destroy", $_POST) ? $_POST["destroy"] : "0";
$pvp = array_key_exists("pvp", $_POST) ? $_POST["pvp"] : "0";
$hostiles = array_key_exists("hostiles", $_POST) ? $_POST["hostiles"] : "0";
$communist = array_key_exists("communist", $_POST) ? $_POST["communist"] : "0";
$enterMsg = $_POST["entermsg"];
$exitMsg = $_POST["exitmsg"];
$texture = $_POST["texture"];

$sqlUpdate  = "UPDATE zone SET zone_name = ?, zone_owner = ?, "
            . "zone_enterdefault = ?, zone_placedefault = ?, "
            . "zone_destroydefault = ?, zone_pvp = ?, "
            . "zone_hostiles = ?, zone_communist = ?, "
            . "zone_entermessage = ?, zone_exitmessage = ?, "
            . "zone_texture = ? ";
$sqlUpdate .= "WHERE zone_id = ?";

$stmt = $conn->prepare($sqlUpdate);
$stmt->execute(array($name, $owner, $enterDefault, $placeDefault,
                     $destroyDefault, $pvp, $hostiles, $communist, 
                     $enterMsg, $exitMsg, $texture, $id));

header('Location: /index.php/zone/edit?id='.$id);
