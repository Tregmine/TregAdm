<?php
ini_set('display_errors', '0');
error_reporting(E_ALL | E_STRICT);
require_once '../include/init.php';
require_once '../include/perm.php';
require_once '../include/check.php';
require_once '../include/settings.php';
$requesturi = explode(".",$_SERVER['HTTP_HOST']);

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
if(array_shift((explode(".",$_SERVER['HTTP_HOST']))) == "m"){
  $settings['mobile'] = true;
  $_SESSION["mobile"] = true;
}else{
  $settings['mobile'] = false;
  $_SESSION["mobile"] = false;
}
function render($page, $title, $context = array(), $styles = array(), $scripts = array())
{
  $context["settings"] = $settings;
  print_r($settings);
	extract($context);
	echo "<!ERIC WAS HERE 04/28/2016 8:02 PM EST>";
	echo "<!ERIC WAS HERE 05/14/2016 1:23 PM EST>";
	require_once '../templates/layout.phtml';
}
$path = array_key_exists("PATH_INFO", $_SERVER) ? $_SERVER["PATH_INFO"] : "/index";
$path = preg_replace("/[^a-z0-9_\\/]+/i", "", $path);
$components = array_slice(explode("/", $path), 1);
$errCode = false;
if($settings["maintenance"]){
	require_once "../pages/maintenance.php";
}else{
if($components[0] == "error" || (strpos($components[0], "error") !== false)){
	$errCode = $components[1];

	require_once "../pages/index.php";
	exit;
}
if($components[0] == "code" || (strpos($components[0], "code") !== false)){
  $errCode = $components[1];
  require_once "../pages/index.php";
  exit;
}
$page = "../pages/" . implode("_", $components) . ".php";
if (!file_exists($page)) {
    header('Location: /error/404');
    exit;
}

require_once $page;
}
