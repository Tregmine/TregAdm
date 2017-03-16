<?php
/*
Title:      Minecraft Avatar
URL:        http://github.com/jamiebicknell/Minecraft-Avatar
Author:     Jamie Bicknell
Twitter:    @jamiebicknell
*/
$size = isset($_GET['s']) ? max(40, min(800, $_GET['s'])) : 250;
$user = isset($_GET['u']) ? $_GET['u'] : '';

/*Code created by ericpedia / gist:3583170 */
function trimimage( $img = null ) {

	// If there's no image
	if (!$img) {
		echo 'no file';
		exit();
	}
	// If the $img is a filename, convert it into an image resource
	if (is_string($img)) {
		$img = imagecreatefrompng( $img );
	}
	// Get the width and height
	$width = imagesx($img);
	$height = imagesy($img);
	// Find the size of the borders
	$top = 0;
	$bottom = 0;
	$left = 0;
	$right = 0;
	$bgcolor = 0xFFFFFF; // Use this if you only want to crop out white space
	$bgcolor = imagecolorat( $img, $top, $left ); // This works with any color, including transparent backgrounds
	//top
	for(; $top < $height; ++$top) {
	  for($x = 0; $x < $width; ++$x) {
	    if(imagecolorat($img, $x, $top) != $bgcolor) {
	       break 2; //out of the 'top' loop
	    }
	  }
	}
	//bottom
	for(; $bottom < $height; ++$bottom) {
	  for($x = 0; $x < $width; ++$x) {
	    if(imagecolorat($img, $x, $height - $bottom-1) != $bgcolor) {
	       break 2; //out of the 'bottom' loop
	    }
	  }
	}
	//left
	for(; $left < $width; ++$left) {
	  for($y = 0; $y < $height; ++$y) {
	    if(imagecolorat($img, $left, $y) != $bgcolor) {
	       break 2; //out of the 'left' loop
	    }
	  }
	}
	//right
	for(; $right < $width; ++$right) {
	  for($y = 0; $y < $height; ++$y) {
	    if(imagecolorat($img, $width - $right-1, $y) != $bgcolor) {
	       break 2; //out of the 'right' loop
	    }
	  }
	}
	//copy the contents, excluding the border
	$newimg = imagecreate(
	    $width-($left+$right), $height-($top+$bottom));
	imagecopy($newimg, $img, 0, 0, $left, $top, imagesx($newimg), imagesy($newimg));
	//finally, output the image
	return $newimg;


}

function get_skin($user)
{
    // Default Steve Skin: http://assets.mojang.com/SkinTemplates/steve.png
    $output = 'iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAFDUlEQVR42u2a20sUURzH97G0LKMotPuWbVpslj1olJ';
    $output .= 'XdjCgyisowsSjzgrB0gSKyC5UF1ZNQWEEQSBQ9dHsIe+zJ/+nXfM/sb/rN4ZwZ96LOrnPgyxzP/M7Z+X7OZc96JpE';
    $output .= 'ISfWrFhK0YcU8knlozeJKunE4HahEqSc2nF6zSEkCgGCyb+82enyqybtCZQWAzdfVVFgBJJNJn1BWFgC49/VpwGVl';
    $output .= 'D0CaxQiA5HSYEwBM5sMAdKTqygcAG9+8coHKY/XXAZhUNgDYuBSPjJL/GkzVVhAEU5tqK5XZ7cnFtHWtq/TahdSw2';
    $output .= 'l0HUisr1UKIWJQBAMehDuqiDdzndsP2EZECAG1ZXaWMwOCODdXqysLf++uXUGv9MhUHIByDOijjdiSAoH3ErANQD7';
    $output .= '3C7TXXuGOsFj1d4YH4OTJAEy8y9Hd0mCaeZ5z8dfp88zw1bVyiYhCLOg1ZeAqC0ybaDttHRGME1DhDeVWV26u17lR';
    $output .= 'APr2+mj7dvULfHw2q65fhQRrLXKDfIxkau3ZMCTGIRR3URR5toU38HbaPiMwUcKfBAkoun09PzrbQ2KWD1JJaqswj';
    $output .= 'deweoR93rirzyCMBCmIQizqoizZkm2H7iOgAcHrMHbbV9KijkUYv7qOn55sdc4fo250e+vUg4329/Xk6QB/6DtOws';
    $output .= '+dHDGJRB3XRBve+XARt+4hIrAF4UAzbnrY0ve07QW8uHfB+0LzqanMM7qVb+3f69LJrD90/1axiEIs6qIs21BTITo';
    $output .= 'ewfcSsA+Bfb2x67OoR1aPPzu2i60fSNHRwCw221Suz0O3jO+jh6V1KyCMGse9721XdN5ePutdsewxS30cwuMjtC86';
    $output .= '0T5JUKpXyKbSByUn7psi5l+juDlZYGh9324GcPKbkycaN3jUSAGxb46IAYPNZzW0AzgiQ5tVnzLUpUDCAbakMQXXr';
    $output .= 'OtX1UMtHn+Q9/X5L4wgl7t37r85OSrx+TYl379SCia9KXjxRpiTjIZTBFOvrV1f8ty2eY/T7XJ81FQAwmA8ASH1ob';
    $output .= '68r5PnBsxA88/xAMh6SpqW4HRnLBrkOA9Xv5wPAZjAUgOkB+SHxgBgR0qSMh0zmZRsmwDJm1gFg2PMDIC8/nAHIMl';
    $output .= 's8x8GgzOsG5WiaqREgYzDvpTwjLDy8NM15LpexDEA3LepjU8Z64my+8PtDCmUyRr+fFwA2J0eAFYA0AxgSgMmYBMZ';
    $output .= 'TwFQnO9RNAEaHOj2DXF5UADmvAToA2ftyxZYA5BqgmZZApDkdAK4mAKo8GzPlr8G8AehzMAyA/i1girUA0HtYB2Ca';
    $output .= 'IkUBEHQ/cBHSvwF0AKZFS5M0ZwMQtEaEAmhtbSUoDADH9ff3++QZ4o0I957e+zYAMt6wHkhzpjkuAcgpwNcpA7AZD';
    $output .= 'LsvpwiuOkBvxygA6Bsvb0HlaeKIF2EbADZpGiGzBsA0gnwQHGOhW2snRpbpPexbAB2Z1oicAMQpTnGKU5ziFKc4xS';
    $output .= 'lOcYpTnOIUpzgVmgo+XC324WfJAdDO/+ceADkCpuMFiFKbApEHkOv7BfzfXt+5gpT8V7rpfYJcDz+jAsB233r6yyB';
    $output .= 'sJ0mlBCDofuBJkel4vOwBFPv8fyYAFPJ+wbSf/88UANNRVy4Awo6+Ig2gkCmgA5DHWjoA+X7AlM//owLANkX0w035';
    $output .= '9od++pvX8fdMAcj3/QJ9iJsAFPQCxHSnQt8vMJ3v2wCYpkhkAOR7vG7q4aCXoMoSgG8hFAuc/grMdAD4B/kHl9da7';
    $output .= 'Ne9AAAAAElFTkSuQmCC';
    $output = base64_decode($output);
    if ($user != '') {
        $ch = curl_init('http://skins.minecraft.net/MinecraftSkins/' . $user . '.png');
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status == 301) {
            preg_match('/location:(.*)/i', $result, $matches);
            curl_setopt($ch, CURLOPT_URL, trim($matches[1]));
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOBODY, 0);
            $result = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($status == 200) {
                $output = $result;
            }
        }
        curl_close($ch);
    }
    return $output;
}
$filename = sprintf("%s.%d.png", $user, $size);
$cachePath = "../public/img/players/";
if (file_exists($cachePath . $filename)) {
     header('Content-type: image/png');
     $fh = fopen($cachePath . $filename, "r");
     fpassthru($fh);
     exit;
}
$skin = get_skin($user);
$g = 4;
$p = ($size / 100) * 5;
$s = floor(($size - ($p * 2)) / (48 + ($g * 3)));
$p = floor(($size - ($s * (48 + ($g * 3)))) / 2);
$h = ($s * 32) + ($p * 2);
$im = imagecreatefromstring($skin);
$av = imagecreatetruecolor($size, $h);
imagesavealpha($av, true);
imagefill($av, 0, 0, imagecolorallocatealpha($av, 0, 0, 0, 127));
if (imagesy($im) > 32) {
    // 1.8+ Skin
    if ((imagecolorat($im, 54, 20) >> 24) & 0x7F == 127) {
        // Alex Style Skin (3px Arm Width)
        // Front
        imagecopyresized($av, $im, $p + $s * 4, $p, 8, 8, $s * 8, $s * 8, 8, 8);
        imagecopyresized($av, $im, $p + $s * 4, $p + $s * 8, 20, 20, $s * 8, $s * 12, 8, 12);
        imagecopyresized($av, $im, $p + $s * 1, $p + $s * 8, 44, 20, $s * 3, $s * 12, 3, 12);
        imagecopyresized($av, $im, $p + $s * 12, $p + $s * 8, 36, 52, $s * 3, $s * 12, 3, 12);
        imagecopyresized($av, $im, $p + $s * 4, $p + $s * 8 + $s * 12, 4, 20, $s * 4, $s * 12, 4, 12);
        imagecopyresized($av, $im, $p + $s * 8, $p + $s * 8 + $s * 12, 20, 52, $s * 4, $s * 12, 4, 12);
        // Black Hat Issue
        imagecolortransparent($im, imagecolorat($im, 63, 0));
        // Face Accessories
        imagecopyresized($av, $im, $p + $s * 4, $p, 40, 8, $s * 8, $s * 8, 8, 8);
        // Body Accessories
        imagecopyresized($av, $im, $p + $s * 4, $p + $s * 8, 20, 36, $s * 8, $s * 12, 8, 12);

        // Arm Accessores
        imagecopyresized($av, $im, $p + $s * 1, $p + $s * 8, 44, 36, $s * 3, $s * 12, 3, 12);
        imagecopyresized($av, $im, $p + $s * 12, $p + $s * 8, 52, 52, $s * 3, $s * 12, 3, 12);
          // Leg Accessores
        imagecopyresized($av, $im, $p + $s * 4, $p + $s * 8 + $s * 12, 4, 36, $s * 4, $s * 12, 4, 12);
        imagecopyresized($av, $im, $p + $s * 8, $p + $s * 8 + $s * 12, 4, 52, $s * 4, $s * 12, 4, 12);
        } else {
        // Steve Style Skin (4px Arm Width)
        // Front
        imagecopyresized($av, $im, $p + $s * 4, $p, 8, 8, $s * 8, $s * 8, 8, 8);
        imagecopyresized($av, $im, $p + $s * 4, $p + $s * 8, 20, 20, $s * 8, $s * 12, 8, 12);
        imagecopyresized($av, $im, $p, $p + $s * 8, 44, 20, $s * 4, $s * 12, 4, 12);
        imagecopyresized($av, $im, $p + $s * 12, $p + $s * 8, 36, 52, $s * 4, $s * 12, 4, 12);
        imagecopyresized($av, $im, $p + $s * 4, $p + $s * 8 + $s * 12, 4, 20, $s * 4, $s * 12, 4, 12);
        imagecopyresized($av, $im, $p + $s * 8, $p + $s * 8 + $s * 12, 20, 52, $s * 4, $s * 12, 4, 12);
        // Black Hat Issue
        imagecolortransparent($im, imagecolorat($im, 63, 0));
        // Face Accessories
        imagecopyresized($av, $im, $p + $s * 4, $p, 40, 8, $s * 8, $s * 8, 8, 8);
        // Body Accessories
        imagecopyresized($av, $im, $p + $s * 4, $p + $s * 8, 20, 36, $s * 8, $s * 12, 8, 12);
        // Arm Accessores
        imagecopyresized($av, $im, $p, $p + $s * 8, 44, 36, $s * 4, $s * 12, 4, 12);
        imagecopyresized($av, $im, $p + $s * 12, $p + $s * 8, 52, 52, $s * 4, $s * 12, 4, 12);
        // Leg Accessores
        imagecopyresized($av, $im, $p + $s * 4, $p + $s * 8 + $s * 12, 4, 36, $s * 4, $s * 12, 4, 12);
        imagecopyresized($av, $im, $p + $s * 8, $p + $s * 8 + $s * 12, 4, 52, $s * 4, $s * 12, 4, 12);
        }
} else {
    $mi = imagecreatetruecolor(64, 32);
    imagecopyresampled($mi, $im, 0, 0, 64 - 1, 0, 64, 32, -64, 32);
    imagesavealpha($mi, true);
    imagefill($mi, 0, 0, imagecolorallocatealpha($mi, 0, 0, 0, 127));

    // Front
    imagecopyresized($av, $im, $p + $s * 4, $p, 8, 8, $s * 8, $s * 8, 8, 8);
    imagecopyresized($av, $im, $p + $s * 4, $p + $s * 8, 20, 20, $s * 8, $s * 12, 8, 12);
    imagecopyresized($av, $im, $p, $p + $s * 8, 44, 20, $s * 4, $s * 12, 4, 12);
    imagecopyresized($av, $mi, $p + $s * 12, $p + $s * 8, 16, 20, $s * 4, $s * 12, 4, 12);
    imagecopyresized($av, $im, $p + $s * 4, $p + $s * 8 + $s * 12, 4, 20, $s * 4, $s * 12, 4, 12);
    imagecopyresized($av, $mi, $p + $s * 8, $p + $s * 8 + $s * 12, 56, 20, $s * 4, $s * 12, 4, 12);
    // Right
    imagecopyresized($av, $im, $p + $s * $g + $s * 16, $p, 0, 8, $s * 8, $s * 8, 8, 8);
    imagecopyresized($av, $im, $p + $s * $g + $s * 18, $p + $s * 8, 40, 20, $s * 4, $s * 12, 4, 12);
    imagecopyresized($av, $im, $p + $s * $g + $s * 18, $p + $s * 8 + $s * 12, 0, 20, $s * 4, $s * 12, 4, 12);
    // Back
    imagecopyresized($av, $im, $p + $s * $g * 2 + $s * 28, $p, 24, 8, $s * 8, $s * 8, 8, 8);
    imagecopyresized($av, $im, $p + $s * $g * 2 + $s * 28, $p + $s * 8, 32, 20, $s * 8, $s * 12, 8, 12);
    imagecopyresized($av, $mi, $p + $s * $g * 2 + $s * 24, $p + $s * 8, 8, 20, $s * 4, $s * 12, 4, 12);
    imagecopyresized($av, $im, $p + $s * $g * 2 + $s * 36, $p + $s * 8, 52, 20, $s * 4, $s * 12, 4, 12);
    imagecopyresized($av, $mi, $p + $s * $g * 2 + $s * 28, $p + $s * 8 + $s * 12, 48, 20, $s * 4, $s * 12, 4, 12);
    imagecopyresized($av, $im, $p + $s * $g * 2 + $s * 32, $p + $s * 8 + $s * 12, 12, 20, $s * 4, $s * 12, 4, 12);
    // Left
    imagecopyresized($av, $im, $p + $s * $g * 3 + $s * 40, $p, 16, 8, $s * 8, $s * 8, 8, 8);
    imagecopyresized($av, $mi, $p + $s * $g * 3 + $s * 42, $p + $s * 8, 20, 20, $s * 4, $s * 12, 4, 12);
    imagecopyresized($av, $mi, $p + $s * $g * 3 + $s * 42, $p + $s * 8 +$s * 12, 60, 20, $s * 4, $s * 12, 4, 12);
    // Black Hat Issue
    imagecolortransparent($im, imagecolorat($im, 63, 0));
    // Accessories
    imagecopyresized($av, $im, $p + $s * 4, $p, 40, 8, $s * 8, $s * 8, 8, 8);
    imagecopyresized($av, $im, $p + $s * $g + $s * 16, $p, 32, 8, $s * 8, $s * 8, 8, 8);
    imagecopyresized($av, $im, $p + $s * $g * 2 + $s * 28, $p, 56, 8, $s * 8, $s * 8, 8, 8);
    imagecopyresized($av, $im, $p + $s * $g * 3 + $s * 40, $p, 48, 8, $s * 8, $s * 8, 8, 8);

    imagedestroy($mi);
}
header('Content-type: image/png');
$output = trimimage($av);
imagepng($output);
imagepng($output, $cachePath . $filename);
imagedestroy($im);
imagedestroy($av);
 ?>
