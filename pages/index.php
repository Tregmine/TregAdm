<?php

if (array_key_exists("id", $_SESSION)) {
    header('Location: index.php/start');
    exit;
}

render('index.phtml', 'Welcome to Tregmine!');
