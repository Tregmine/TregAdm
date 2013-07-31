<?php

require_once '../include/init.php';
require_once '../include/perm.php';
require_once '../include/check.php';

function render($page, $title, $context = array(), $styles = array(), $scripts = array())
{
    extract($context);
    require_once '../templates/layout.phtml';
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
