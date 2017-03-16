<?php

function niceTime($timestamp)
{
    $now = time();
    $diff = $now - $timestamp;
    $full = date("Y-m-d H:i:s", $timestamp);
    if ($diff < 60) {
        $short = "Just now";
    } else if ($diff < 600) {
        $short = "A couple of minutes ago";
    } else if ($diff < 3600) {
        $short = intval($diff/60) . " minutes ago";
    } else if (date("d", $timestamp) == date("d")) {
        $short = date("H:i", $timestamp) . " today";
    } else if (date("Y-m", $timestamp) == date("Y-m")) {
        $short = date("H:i, F j", $timestamp);
    } else if (date("Y", $timestamp) == date("Y")) {
        $short = date("H:i, F j", $timestamp);
    } else {
        $short = date("H:i, F j, Y", $timestamp);
    }

    return sprintf("<span title=\"%s\">%s</span>", $full, $short);
}

// Simple least square regressions and linear interpolation
/*function trend($points, $idx) {
    $A = 0; $B = 0; $C = 0; $D = 0;
    $n = count($points);
    foreach ($points as $x => $row) {
        $y = $row[$idx];

        $A += $x * $x;
        $B += $x;
        $C += $x * $y;
        $D += $y;
    }

    $m = ($A * $D - $B * $C) / ($n * $A - $B * $B);
    $k = ($C - $m * $B) / $A;

    $result = array();
    for ($x = 0; $x < $n; $x++) {
        $result[] = round($k * $x + $m);
    }

    return $result;
}*/

function trend($points, $idx) {
    //$A = 0; $B = 0; $C = 0; $D = 0;
    //$n = count($points);
    //foreach ($points as $x => $row) {
    //    $y = $row[$idx];

    //    $A += $x * $x;
    //    $B += $x;
    //    $C += $x * $y;
    //    $D += $y;
    //}

    //$m = ($A * $D - $B * $C) / ($n * $A - $B * $B);
    //$k = ($C - $m * $B) / $A;

    //$result = array();
    //for ($x = 0; $x < $n; $x++) {
    //    $result[] = round($k * $x + $m);
    //}

    //return $result;

    $result = array();
    $current = -1;
    $a = 0.95;
    foreach ($points as $x => $row) {
        $y = $row[$idx];
        if ($current == -1) {
            $current = $y;
        } else {
            $current = $a * $current + (1-$a) * $y;
        }
        $result[] = round($current);
    }

    return $result;
}

