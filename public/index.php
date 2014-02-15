<?php

require_once '../include/init.php';
require_once '../include/perm.php';
require_once '../include/check.php';

if ($_SERVER["SERVER_NAME"] != "tregmine.info") {
    header('Location: http://tregmine.info' . $_SERVER["REQUEST_URI"]);
}

function render($page, $title, $context = array(), $styles = array(), $scripts = array())
{
    extract($context);
    require_once '../templates/layout.phtml';
}

if (array_key_exists("tregadm_login_nonce", $_COOKIE) &&
    (!array_key_exists("online", $_SESSION) || !$_SESSION["online"])) {

    $nonce = $_COOKIE["tregadm_login_nonce"];

    $sql  = "SELECT * FROM player ";
    $sql .= "INNER JOIN player_webcookie USING (player_id) ";
    $sql .= "WHERE webcookie_nonce = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute(array($nonce));

    $user = $stmt->fetch();

    $stmt->closeCursor();

    if ($user) {
        echo "<!-- restored session from nonce -->\n";
        $_SESSION["online"] = true;
        $_SESSION["id"] = $user["player_id"];
        $_SESSION["name"] = $user["player_name"];
        $_SESSION["rank"] = $user["player_rank"];
        $_SESSION["flags"] = $user["player_flags"];
    }
}

$path = array_key_exists("PATH_INFO", $_SERVER) ? $_SERVER["PATH_INFO"] : "/index";
$path = preg_replace("/[^a-z0-9_\\/]+/i", "", $path);
$components = array_slice(explode("/", $path), 1);

$page = "../pages/" . implode("_", $components) . ".php";
if (!file_exists($page)) {
    echo "Not found";
    exit;
}

require_once $page;
