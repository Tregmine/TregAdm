<?php

function gensalt($len = 16) {
    $data = "abcdefghijklmnopqrstuvwxyz0123456789";
    $buf = "";
    for ($i = 0; $i < $len; $i++) {
        $buf .= $data[mt_rand(0, strlen($data)-1)];
    }

    return $buf;
}
