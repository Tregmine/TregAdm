<?php

if (!array_key_exists("id", $_SESSION)) {
    header('Location: index.php');
    exit;
}

$context = array();
$styles = array();
$scripts = array("/js/start.js");
render('start.phtml', 'Welcome to Tregmine!', $context, $styles, $scripts);
