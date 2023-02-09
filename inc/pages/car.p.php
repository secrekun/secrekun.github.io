<?php
header('Content-Type: image/png');

$im = imagecreatefromjpeg('images/cars/' . Config::$_url[1] . '.jpg');

$red = imagecolorallocate($im, 255, 0, 0);

$text = '* Donator car';
$font = 'css/arial.ttf';

imagettftext($im, 40, 0, 10, 440, $red, $font, $text);

imagepng($im);
imagedestroy($im);
?>