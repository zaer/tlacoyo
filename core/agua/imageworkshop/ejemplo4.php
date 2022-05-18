<?php
require_once('imageworkshop.php');
 
$norwayLayer = new ImageWorkshop(array(
    "imageFromPath" => "sgs3.jpg",
));
 
$watermarkLayer = new ImageWorkshop(array(
    "imageFromPath" => "android.jpg",
));

//$watermarkLayer->opacity(60);
$watermarkLayer->rotate(90);
$norwayLayer->addLayer(1, $watermarkLayer, 12, 12, "LT");
 
$image = $norwayLayer->getResult();
header('Content-type: image/jpeg');
 
imagejpeg($image, null, 95);
