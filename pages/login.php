<?php

require_once '../include/password.php';

if (array_key_exists("id", $_SESSION)) {
    header('Location: /index.php/start');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["pass"];

    $stmt = $conn->prepare("SELECT * FROM player WHERE player_name = ?");
    $stmt->execute(array($username));

    $user = $stmt->fetch();

    $stmt->closeCursor();

    if (!$user) {
        header('Location: /index.php?error=fail');
        exit;
    }

    if (crypt($password, $user["player_password"]) != $user["player_password"]) {
        header('Location: /index.php?error=fail');
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM player_property WHERE player_id = ?");
    $stmt->execute(array($user["player_id"]));

    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $_SESSION["online"] = true;
    $_SESSION["id"] = $user["player_id"];
    $_SESSION["name"] = $user["player_name"];

    foreach ($properties as $property) {
        if ($property["property_value"] == "false" || $property["property_value"] == "0") {
            continue;
        }
        $_SESSION[$property["property_key"]] = $property["property_value"];
    }

    if (!array_key_exists("admin", $_SESSION) &&
        !array_key_exists("guardian", $_SESSION)) {

        session_destroy();
    }

    header('Location: /index.php/start');
    exit;
}
