<?php

// Simple least square regressions and linear interpolation
function trend($points, $idx) {
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
}

