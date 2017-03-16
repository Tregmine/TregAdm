<?php

function checkIfOnline() {
    if (!array_key_exists("online", $_SESSION)) {
        header('Location: /index.php');
        exit;
    }
}

function checkRank() {
    if (!array_key_exists("rank", $_SESSION)) {
        exit;
    }
    $ranks = func_get_args();
    if (!in_array($_SESSION["rank"], $ranks)) {
        header('Location: /index.php');
        exit;
    }
}

function hasRank() {
    if (!array_key_exists("rank", $_SESSION)) {
        return false;
    }
    $ranks = func_get_args();
    return in_array($_SESSION["rank"], $ranks);
}
