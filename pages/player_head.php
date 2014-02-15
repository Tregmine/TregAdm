<?php

error_reporting(0);
ini_set("display_errors", "0");

class Area {
    function __construct($x, $y, $w, $h) {
        $this->x = $x;
        $this->y = $y;
        $this->w = $w;
        $this->h = $h;
    }
}

function copy_area($src, $dst, $area, $x, $y) {
    imagecopy($dst, $src, $x, $y, $area->x, $area->y, $area->w, $area->h);
}

$areas = array();
$areas["head_front"] = new Area(8, 8, 8, 8);

$width = $areas["head_front"]->w;
$height = $areas["head_front"]->h;

$player = $_GET["player"];
$factor = 2;
if (array_key_exists("factor", $_GET)) {
    $factor = intval($_GET["factor"]);
    if ($factor != 1 && $factor != 2 && $factor != 3 && $factor != 4 && $factor != 16) {
        exit;
    }
}

$filename = sprintf("%s.%d.png", $player, $factor);
$cachePath = "../public/img/players/";
if (file_exists($cachePath . $filename)) {
    //header('Location: /img/players/' . $filename);
    header('Content-type: image/png');
    $fh = fopen($cachePath . $filename, "r");
    fpassthru($fh);
    exit;
}

$url = sprintf("http://s3.amazonaws.com/MinecraftSkins/%s.png", $player);
$data = file_get_contents($url);
if (!$data) {
    $url = "http://s3.amazonaws.com/MinecraftSkins/char.png";
    $data = file_get_contents($url);
}

$src = imagecreatefromstring($data);

$dst = imagecreatetruecolor($width, $height);
imagealphablending($dst, false);
imagesavealpha($dst, true);
$alpha = imagecolorallocatealpha($dst, 0, 0, 0, 0x7F);
imagefill($dst, 0, 0, $alpha);
copy_area($src, $dst, $areas["head_front"], 0, 0);

if (function_exists("image_scale_hqx4")) {
    if ($factor == 16) {
        $tmp = image_scale_hqx4($dst);
        imagedestroy($dst);
        $final = image_scale_hqx4($tmp);
        imagedestroy($tmp);
    } else if ($factor == 4) {
        $final = image_scale_hqx4($dst);
        imagedestroy($dst);
    } else if ($factor == 3) {
        $final = image_scale_hqx3($dst);
        imagedestroy($dst);
    } else if ($factor == 2) {
        $final = image_scale_hqx2($dst);
        imagedestroy($dst);
    } else {
        $final = $dst;
    }
} else {
    $final = imagecreatetruecolor($factor*imagesx($dst),
                                  $factor*imagesy($dst));
    imagealphablending($final, false);
    imagesavealpha($final, true);
    imagecopyresized($final, $dst, 0, 0, 0, 0,
        imagesx($final), imagesy($final), imagesx($dst), imagesy($dst));
}

header('Content-type: image/png');
imagepng($final);
imagepng($final, $cachePath . "/" . $filename);

imagedestroy($final);
imagedestroy($src);
