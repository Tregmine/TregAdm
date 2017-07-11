<?php
ini_set('display_errors', '1');
error_reporting(E_WARNING || E_ERROR || E_RECOVERABLE_ERROR || E_COMPILE_ERROR || E_COMPILE_WARNING || E_CORE_ERROR || E_CORE_WARNING);
require_once '../include/init.php';
require_once '../include/perm.php';
require_once '../include/check.php';
require_once '../include/settings.php';
$requesturi = explode(".", $_SERVER['HTTP_HOST']);
$subdomain = array_shift($requesturi);
if (array_key_exists("tregadm_login_nonce", $_COOKIE) && (! array_key_exists("online", $_SESSION) || ! $_SESSION["online"])) {
    
    $nonce = $_COOKIE["tregadm_login_nonce"];
    
    $sql = "SELECT * FROM player ";
    $sql .= "INNER JOIN player_webcookie USING (player_id) ";
    $sql .= "WHERE webcookie_nonce = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute(array(
        $nonce
    ));
    
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
if ($subdomain == "m") {
    $settings['mobile'] = true;
    $_SESSION["mobile"] = true;
} else {
    $settings['mobile'] = false;
    $_SESSION["mobile"] = false;
}

function render($page, $title, $context = array(), $styles = array(), $scripts = array())
{
    global $settings;
    $context["settings"] = $settings;
    extract($context);
    require_once '../templates/layout.phtml';
}
$requestPath = array_keys($_REQUEST)[0];
$path = empty($_REQUEST) && empty($_SERVER['PATH_INFO']) ? "/index" : empty($requestPath) ? $_SERVER['PATH_INFO'] : $requestPath;
$path = empty($path) ? "/index" : $path;
$path = preg_replace("/[^a-z0-9_\\/]+/i", "", $path);
$components = array_slice(explode("/", $path), 1);
if ($settings["maintenance"]) {
    require_once "../pages/maintenance.php";
} else {
    $errCode = false;
    if (sizeof($components) >= 1) {
        if ($components[0] == "error" || (strpos($components[0], "error") !== false)) {
            $errCode = $components[1];
            require_once "../pages/index.php";
            exit();
        }
        if ($components[0] == "code" || (strpos($components[0], "code") !== false)) {
            $errCode = $components[1];
            require_once "../pages/index.php";
            exit();
        }
    }
    
    $page = "../pages/" . implode("_", $components) . ".php";
    if (! file_exists($page)) {

        header('Location: /error/404');
        exit();
    }
    require_once $page;
}
