<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../include/minecraft_skins/skin.class.php';

$user = isset($_GET['u']) ? $_GET['u'] : '';

$avatar = new Skin('Notch', 32);
$avatar->show();
 ?>
