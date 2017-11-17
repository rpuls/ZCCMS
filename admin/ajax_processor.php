<?php

include("../includes/mysqli.php");

$action = $_POST['action'];
$zite_id = $_POST['zite_id'];

if($action == 'layout'){
    $pos = 1;
    foreach($_POST as $key => $value) {
        if (strstr($key, 'se')){
            $values = [];
            foreach($value as $se) {
                $values[] = $se;
            }
            $color1 = validateColor($values[1]);
            $color2 = validateColor($values[2]);
            $color3 = validateColor($values[3]);
            
            $stmt = $mysqli_link->prepare("REPLACE INTO tblSvgElement(PlaceID, SVG, SVGPosition, Path1Color, Path2Color, Path3Color) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param("iissss", $zite_id, $values[0], $pos, $color1, $color2, $color3);
            $stmt->execute();
            $pos +=1;
        }
    }
    $stmt->close();
    $mysqli_link->close();
}

function validateColor($input){
    $match = preg_match("/^(\#[\da-f]{3}|\#[\da-f]{6}|rgba\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)(,\s*(0\.\d+|1))\)|hsla\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)(,\s*(0\.\d+|1))\)|rgb\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)|hsl\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)\))$/",$input);
    if(!$match){
        return '#000000';
    }
    return $input;
}

?>