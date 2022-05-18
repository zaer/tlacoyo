<?php
require_once('imageworkshop.php');
 
$norwayLayer = new ImageWorkshop(array(
    "imageFromPath" => "sgs3.jpg",
));
 
$textLayer = new ImageWorkshop(array(
    "text" => "PHP Image Workshop",
    "fontPath" => "arial.ttf",
    "fontSize" => 14,
    "fontColor" => "ffffff",
    "textRotation" => 0,
));
 
$norwayLayer->addLayer(1, $textLayer, 12, 12, "LB");

$image = $norwayLayer->getResult();
 
header('Content-type: image/jpeg');
 
imagejpeg($image, null, 95); 
