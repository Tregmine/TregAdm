<?php

function checkIfOnline() {
    if (!array_key_exists("online", $_SESSION)) {
        header('Location: /index.php');
        exit;
    }
}

function checkRank() {
    $ranks = func_get_args();
    if (!in_array($_SESSION["rank"], $ranks)) {
        header('Location: /index.php');
        exit;
    }
}

function hasRank() {
    $ranks = func_get_args();
    return in_array($_SESSION["rank"], $ranks);
}
