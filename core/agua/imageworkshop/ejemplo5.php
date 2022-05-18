<?php
require_once('imageworkshop.php');

function calculAngleBtwHypoAndLeftSide($bottomSideWidth, $leftSideWidth)
{
    $hypothenuse = sqrt(pow($bottomSideWidth, 2) + pow($leftSideWidth, 2));
    $bottomRightAngle = acos($bottomSideWidth / $hypothenuse) + 180 / pi();
     
    return -round(90 - $bottomRightAngle);
}
 
$norwayLayer = new ImageWorkshop(array(
    "imageFromPath" => "sgs3.jpg",
));
 
$textLayer = new ImageWorkshop(array(
    "text" => "PHP Image Workshop",
    "fontPath" => "arial.ttf",
    "fontSize" => 40,
    "fontColor" => "ffffff",
    "textRotation" => calculAngleBtwHypoAndLeftSide($norwayLayer->getWidth(), $norwayLayer->getHeight()),
));
 
// Some funky opacity
$textLayer->opacity(70);
 
// We add the $textLayer on the norway layer, in its middle
$norwayLayer->addLayer(1, $textLayer, 0, 0, 'MM');
 
$image = $norwayLayer->getResult();
header('Content-type: image/jpeg');
 
imagejpeg($image, null, 95);